<?php

namespace App\Services;

use App\Models\Loan;
use Carbon\Carbon;

class DebtPayoffService
{
    /**
     * Simulate a debt payoff plan using Snowball or Avalanche.
     *
     * The total monthly debt budget remains fixed.
     *
     * Every time a loan is fully paid:
     *
     *     its minimum payment is freed
     *
     * and the freed amount is automatically rolled into the
     * next target loan.
     */
    public function simulate(
        $loans,
        string $strategy = 'avalanche',
        float $extraPayment = 0
    ): array {

        $strategy = in_array(
            $strategy,
            ['snowball', 'avalanche'],
            true
        )
            ? $strategy
            : 'avalanche';

        $extraPayment = max(
            0,
            round($extraPayment, 2)
        );

        /*
        |--------------------------------------------------------------------------
        | Prepare Debts
        |--------------------------------------------------------------------------
        */

        $debts = $loans
            ->filter(
                fn (Loan $loan) =>
                    (float) $loan->remaining_balance > 0
            )
            ->map(
                function (Loan $loan) {

                    return [
                        'id' =>
                            $loan->id,

                        'name' =>
                            $loan->loan_name,

                        'interest_rate' =>
                            (float) $loan->interest_rate,

                        'balance' =>
                            round(
                                (float) $loan->remaining_balance,
                                2
                            ),

                        'minimum_payment' =>
                            max(
                                0,
                                round(
                                    (float) $loan->monthly_payment,
                                    2
                                )
                            ),

                        'original_minimum_payment' =>
                            max(
                                0,
                                round(
                                    (float) $loan->monthly_payment,
                                    2
                                )
                            ),
                    ];
                }
            )
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | No Debt
        |--------------------------------------------------------------------------
        */

        if (empty($debts)) {

            return [
                'strategy' =>
                    $strategy,

                'strategy_label' =>
                    $this->strategyLabel($strategy),

                'extra_payment' =>
                    $extraPayment,

                'initial_debt' =>
                    0,

                'initial_minimum_payments' =>
                    0,

                'total_payment_budget' =>
                    0,

                'months' =>
                    0,

                'payoff_date' =>
                    Carbon::today(),

                'payoff_date_formatted' =>
                    Carbon::today()->format('F d, Y'),

                'order' =>
                    [],

                'payoff_events' =>
                    [],

                'monthly_schedule' =>
                    [],

                'target_loan' =>
                    null,

                'target_payment_map' =>
                    [],

                'rollover_total' =>
                    0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Initial Debt
        |--------------------------------------------------------------------------
        */

        $initialDebt = round(
            collect($debts)->sum('balance'),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Initial Minimum Payments
        |--------------------------------------------------------------------------
        */

        $initialMinimumPayments = round(
            collect($debts)->sum(
                fn (array $debt) =>
                    min(
                        $debt['minimum_payment'],
                        $debt['balance']
                    )
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Fixed Monthly Debt Budget
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Minimums = ₱12,000
        | Extra    = ₱1,000
        |
        | Total monthly debt budget = ₱13,000
        |
        | That same budget is retained throughout the simulation.
        |
        */

        $totalPaymentBudget = round(
            $initialMinimumPayments
            + $extraPayment,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Simulation Variables
        |--------------------------------------------------------------------------
        */

        $month = 0;

        $maxMonths = 600;

        $schedule = [];

        $payoffOrder = [];

        $payoffEvents = [];

        $rolloverTotal = 0;

        $targetPaymentMap = [];

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Main Simulation
        |--------------------------------------------------------------------------
        */

        while (
            $this->hasRemainingDebt($debts)
            && $month < $maxMonths
        ) {

            $month++;

            /*
            |--------------------------------------------------------------------------
            | Sort Current Debts
            |--------------------------------------------------------------------------
            */

            $debts = $this->sortDebts(
                $debts,
                $strategy
            );

            /*
            |--------------------------------------------------------------------------
            | Payment Budget
            |--------------------------------------------------------------------------
            */

            $remainingBudget =
                $totalPaymentBudget;

            /*
            |--------------------------------------------------------------------------
            | Month Payment Records
            |--------------------------------------------------------------------------
            */

            $monthPayments = [];

            $newlyPaidLoans = [];

            /*
            |--------------------------------------------------------------------------
            | Determine Current Priority Target
            |--------------------------------------------------------------------------
            */

            $activeBeforePayment = array_values(
                array_filter(
                    $debts,
                    fn (array $debt) =>
                        $debt['balance'] > 0
                )
            );

            $priorityBeforePayment =
                $activeBeforePayment[0]
                ?? null;

            if ($priorityBeforePayment) {

                $targetPaymentMap[
                    $priorityBeforePayment['id']
                ] = [
                    'loan_id' =>
                        $priorityBeforePayment['id'],

                    'loan_name' =>
                        $priorityBeforePayment['name'],

                    'month' =>
                        $month,

                    'planned_payment_budget' =>
                        $totalPaymentBudget,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Apply Minimum Payments
            |--------------------------------------------------------------------------
            */

            foreach ($debts as &$debt) {

                if (
                    $debt['balance'] <= 0
                ) {
                    continue;
                }

                if (
                    $remainingBudget <= 0
                ) {
                    break;
                }

                $minimumPayment = min(
                    $debt['minimum_payment'],
                    $debt['balance'],
                    $remainingBudget
                );

                $minimumPayment =
                    round(
                        max(
                            0,
                            $minimumPayment
                        ),
                        2
                    );

                if (
                    $minimumPayment <= 0
                ) {
                    continue;
                }

                $debt['balance'] =
                    round(
                        max(
                            0,
                            $debt['balance']
                            - $minimumPayment
                        ),
                        2
                    );

                $remainingBudget =
                    round(
                        max(
                            0,
                            $remainingBudget
                            - $minimumPayment
                        ),
                        2
                    );

                $monthPayments[] = [
                    'loan_id' =>
                        $debt['id'],

                    'loan_name' =>
                        $debt['name'],

                    'amount' =>
                        $minimumPayment,

                    'type' =>
                        'minimum',
                ];

                /*
                |--------------------------------------------------------------------------
                | Detect Fully Paid Loan
                |--------------------------------------------------------------------------
                */

                if (
                    $debt['balance'] <= 0
                ) {

                    $newlyPaidLoans[] =
                        $debt;
                }
            }

            unset($debt);

            /*
            |--------------------------------------------------------------------------
            | Apply Remaining Budget To Priority Debt
            |--------------------------------------------------------------------------
            |
            | This is where the rollover happens.
            |
            | Freed minimum payments + extra payment are redirected
            | to the next target.
            |
            */

            if (
                $remainingBudget > 0
            ) {

                $debts = $this->sortDebts(
                    $debts,
                    $strategy
                );

                foreach ($debts as &$debt) {

                    if (
                        $remainingBudget <= 0
                    ) {
                        break;
                    }

                    if (
                        $debt['balance'] <= 0
                    ) {
                        continue;
                    }

                    $extraApplied = min(
                        $remainingBudget,
                        $debt['balance']
                    );

                    $extraApplied =
                        round(
                            max(
                                0,
                                $extraApplied
                            ),
                            2
                        );

                    if (
                        $extraApplied <= 0
                    ) {
                        continue;
                    }

                    $debt['balance'] =
                        round(
                            max(
                                0,
                                $debt['balance']
                                - $extraApplied
                            ),
                            2
                        );

                    $remainingBudget =
                        round(
                            max(
                                0,
                                $remainingBudget
                                - $extraApplied
                            ),
                            2
                        );

                    $monthPayments[] = [
                        'loan_id' =>
                            $debt['id'],

                        'loan_name' =>
                            $debt['name'],

                        'amount' =>
                            $extraApplied,

                        'type' =>
                            'rollover_extra',
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Record Payment Capacity
                    |--------------------------------------------------------------------------
                    */

                    $targetPaymentMap[
                        $debt['id']
                    ] = [
                        'loan_id' =>
                            $debt['id'],

                        'loan_name' =>
                            $debt['name'],

                        'month' =>
                            $month,

                        'planned_payment_budget' =>
                            $totalPaymentBudget,

                        'regular_minimum' =>
                            $debt['original_minimum_payment'],

                        'priority_extra' =>
                            round(
                                max(
                                    0,
                                    $totalPaymentBudget
                                    - $debt['original_minimum_payment']
                                ),
                                2
                            ),
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Detect Newly Paid Target
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $debt['balance'] <= 0
                        && !collect(
                            $newlyPaidLoans
                        )->contains(
                            'id',
                            $debt['id']
                        )
                    ) {

                        $newlyPaidLoans[] =
                            $debt;
                    }
                }

                unset($debt);
            }

            /*
            |--------------------------------------------------------------------------
            | Process Paid Loans
            |--------------------------------------------------------------------------
            */

            foreach (
                $newlyPaidLoans
                as $paidLoan
            ) {

                if (
                    in_array(
                        $paidLoan['id'],
                        $payoffOrder,
                        true
                    )
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Add To Payoff Order
                |--------------------------------------------------------------------------
                */

                $payoffOrder[] =
                    $paidLoan['id'];

                /*
                |--------------------------------------------------------------------------
                | Freed Monthly Payment
                |--------------------------------------------------------------------------
                */

                $freedPayment =
                    round(
                        (float) $paidLoan[
                            'original_minimum_payment'
                        ],
                        2
                    );

                $rolloverTotal =
                    round(
                        $rolloverTotal
                        + $freedPayment,
                        2
                    );

                /*
                |--------------------------------------------------------------------------
                | Record Event
                |--------------------------------------------------------------------------
                */

                $payoffEvents[] = [
                    'month' =>
                        $month,

                    'date' =>
                        $today
                            ->copy()
                            ->addMonthsNoOverflow(
                                $month
                            ),

                    'loan_id' =>
                        $paidLoan['id'],

                    'loan_name' =>
                        $paidLoan['name'],

                    'freed_payment' =>
                        $freedPayment,

                    'message' =>
                        $paidLoan['name']
                        . ' is fully paid. '
                        . '₱'
                        . number_format(
                            $freedPayment,
                            2
                        )
                        . ' per month is now available for the next target.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Remaining Debt
            |--------------------------------------------------------------------------
            */

            $remainingDebt =
                round(
                    collect($debts)->sum(
                        'balance'
                    ),
                    2
                );

            /*
            |--------------------------------------------------------------------------
            | Current Target
            |--------------------------------------------------------------------------
            */

            $activeDebts =
                array_values(
                    array_filter(
                        $debts,
                        fn (array $debt) =>
                            $debt['balance'] > 0
                    )
                );

            $activeDebts =
                $this->sortDebts(
                    $activeDebts,
                    $strategy
                );

            $targetName =
                $activeDebts[0]['name']
                ?? null;

            /*
            |--------------------------------------------------------------------------
            | Monthly Payment Total
            |--------------------------------------------------------------------------
            */

            $monthlyTotal =
                round(
                    collect($monthPayments)
                        ->sum('amount'),
                    2
                );

            /*
            |--------------------------------------------------------------------------
            | Save Month
            |--------------------------------------------------------------------------
            */

            $schedule[] = [
                'month' =>
                    $month,

                'date' =>
                    $today
                        ->copy()
                        ->addMonthsNoOverflow(
                            $month
                        ),

                'payments' =>
                    $monthPayments,

                'total_payment' =>
                    $monthlyTotal,

                'remaining_debt' =>
                    $remainingDebt,

                'target_loan' =>
                    $targetName,

                'rollover' =>
                    collect(
                        $payoffEvents
                    )
                        ->where(
                            'month',
                            $month
                        )
                        ->values()
                        ->all(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Estimated Payoff Date
        |--------------------------------------------------------------------------
        */

        $payoffDate = $today
            ->copy()
            ->addMonthsNoOverflow(
                $month
            );

        /*
        |--------------------------------------------------------------------------
        | Initial Target
        |--------------------------------------------------------------------------
        */

        $orderedDebts =
            $this->sortDebts(
                $this->prepareFreshDebts(
                    $loans
                ),
                $strategy
            );

        $targetLoan =
            $orderedDebts[0]
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [
            'strategy' =>
                $strategy,

            'strategy_label' =>
                $this->strategyLabel(
                    $strategy
                ),

            'extra_payment' =>
                $extraPayment,

            'initial_debt' =>
                $initialDebt,

            'initial_minimum_payments' =>
                $initialMinimumPayments,

            'total_payment_budget' =>
                $totalPaymentBudget,

            'months' =>
                $month,

            'payoff_date' =>
                $payoffDate,

            'payoff_date_formatted' =>
                $payoffDate->format(
                    'F d, Y'
                ),

            'order' =>
                $payoffOrder,

            'payoff_events' =>
                $payoffEvents,

            'monthly_schedule' =>
                $schedule,

            'target_loan' =>
                $targetLoan,

            /*
            |--------------------------------------------------------------------------
            | Exact Payment Capacity Per Target
            |--------------------------------------------------------------------------
            */

            'target_payment_map' =>
                $targetPaymentMap,

            'rollover_total' =>
                $rolloverTotal,

            'interest_saved_note' =>
                'This simulation uses the application\'s current recorded loan balances and payment rules.',
        ];
    }

    /**
     * Sort debts according to strategy.
     */
    protected function sortDebts(
        array $debts,
        string $strategy
    ): array {

        usort(
            $debts,
            function (
                array $a,
                array $b
            ) use ($strategy) {

                if (
                    $strategy === 'snowball'
                ) {

                    $balanceCompare =
                        $a['balance']
                        <=>
                        $b['balance'];

                    if (
                        $balanceCompare !== 0
                    ) {
                        return $balanceCompare;
                    }

                    return
                        $b['interest_rate']
                        <=>
                        $a['interest_rate'];
                }

                /*
                |--------------------------------------------------------------------------
                | Avalanche
                |--------------------------------------------------------------------------
                */

                $interestCompare =
                    $b['interest_rate']
                    <=>
                    $a['interest_rate'];

                if (
                    $interestCompare !== 0
                ) {
                    return $interestCompare;
                }

                return
                    $a['balance']
                    <=>
                    $b['balance'];
            }
        );

        return array_values(
            $debts
        );
    }

    /**
     * Prepare fresh debts.
     */
    protected function prepareFreshDebts(
        $loans
    ): array {

        return $loans
            ->filter(
                fn (Loan $loan) =>
                    (float) $loan->remaining_balance > 0
            )
            ->map(
                function (Loan $loan) {

                    return [
                        'id' =>
                            $loan->id,

                        'name' =>
                            $loan->loan_name,

                        'interest_rate' =>
                            (float) $loan->interest_rate,

                        'balance' =>
                            round(
                                (float) $loan->remaining_balance,
                                2
                            ),

                        'minimum_payment' =>
                            max(
                                0,
                                round(
                                    (float) $loan->monthly_payment,
                                    2
                                )
                            ),

                        'original_minimum_payment' =>
                            max(
                                0,
                                round(
                                    (float) $loan->monthly_payment,
                                    2
                                )
                            ),
                    ];
                }
            )
            ->values()
            ->all();
    }

    /**
     * Check whether debt remains.
     */
    protected function hasRemainingDebt(
        array $debts
    ): bool {

        foreach ($debts as $debt) {

            if (
                $debt['balance'] > 0
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get strategy label.
     */
    protected function strategyLabel(
        string $strategy
    ): string {

        return match ($strategy) {

            'snowball' =>
                'Debt Snowball',

            'avalanche' =>
                'Debt Avalanche',

            default =>
                'Debt Avalanche',
        };
    }
}