<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\AvailableFundsService;
use App\Services\DebtPayoffService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DebtFreePlannerController extends Controller
{
    public function __construct(
        private AvailableFundsService $availableFundsService,
        private DebtPayoffService $debtPayoffService
    ) {
    }

    /**
     * Display the debt-free planner.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | User Selected Strategy
        |--------------------------------------------------------------------------
        |
        | This controls the payoff simulator.
        |
        */

        $strategy = $request->query(
            'strategy',
            'avalanche'
        );

        if (!in_array(
            $strategy,
            [
                'snowball',
                'avalanche',
            ],
            true
        )) {
            $strategy = 'avalanche';
        }

        /*
        |--------------------------------------------------------------------------
        | Extra Monthly Payment
        |--------------------------------------------------------------------------
        */

        $extraPayment = $request->query(
            'extra_payment',
            0
        );

        $extraPayment = is_numeric($extraPayment)
            ? max(
                0,
                round(
                    (float) $extraPayment,
                    2
                )
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Active Loans
        |--------------------------------------------------------------------------
        */

        $loans = Loan::where(
            'user_id',
            $userId
        )
            ->whereIn(
                'status',
                [
                    'active',
                    'overdue',
                ]
            )
            ->get()
            ->filter(
                fn (Loan $loan) =>
                    (float) $loan->remaining_balance > 0
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Total Outstanding Debt
        |--------------------------------------------------------------------------
        */

        $totalDebt = round(
            (float) $loans->sum(
                fn (Loan $loan) =>
                    (float) $loan->remaining_balance
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Total Monthly Minimum Payments
        |--------------------------------------------------------------------------
        */

        $totalMinimumPayments = round(
            (float) $loans->sum(
                fn (Loan $loan) =>
                    min(
                        (float) $loan->monthly_payment,
                        (float) $loan->remaining_balance
                    )
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Available Funds
        |--------------------------------------------------------------------------
        */

        $availableFunds =
            $this->availableFundsService
                ->availableFunds(
                    $userId
                );

        /*
        |--------------------------------------------------------------------------
        | Suggested Extra Payment
        |--------------------------------------------------------------------------
        */

        $suggestedExtraPayment = max(
            0,
            round(
                $availableFunds
                - $totalMinimumPayments,
                2
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Snowball Order
        |--------------------------------------------------------------------------
        |
        | Smallest remaining balance first.
        |
        */

        $snowballLoans = $loans
            ->sortBy(
                fn (Loan $loan) =>
                    (float) $loan->remaining_balance
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Avalanche Order
        |--------------------------------------------------------------------------
        |
        | Highest interest rate first.
        |
        */

        $avalancheLoans = $loans
            ->sortByDesc(
                fn (Loan $loan) =>
                    (float) $loan->interest_rate
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Recommended Strategy
        |--------------------------------------------------------------------------
        |
        | This remains for the recommendation card on the Blade page.
        |
        */

        $snowballCount = $loans
            ->where(
                'repayment_strategy',
                'snowball'
            )
            ->count();

        $avalancheCount = $loans
            ->where(
                'repayment_strategy',
                'avalanche'
            )
            ->count();

        if (
            $avalancheCount
            > $snowballCount
        ) {

            $recommendedStrategy = 'avalanche';

        } elseif (
            $snowballCount
            > $avalancheCount
        ) {

            $recommendedStrategy = 'snowball';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Default Recommendation
            |--------------------------------------------------------------------------
            |
            | Avalanche is used when the user has not expressed
            | a clear strategy preference.
            |
            */

            $recommendedStrategy = 'avalanche';
        }

        /*
        |--------------------------------------------------------------------------
        | Current Target Loan
        |--------------------------------------------------------------------------
        */

        $targetLoans =
            $strategy === 'snowball'
                ? $snowballLoans
                : $avalancheLoans;

        $targetLoan =
            $targetLoans->first();

        /*
        |--------------------------------------------------------------------------
        | Payoff Simulation
        |--------------------------------------------------------------------------
        */

        $simulation =
            $this->debtPayoffService->simulate(
                $loans,
                $strategy,
                $extraPayment
            );

        /*
        |--------------------------------------------------------------------------
        | Exact Target Payment Map
        |--------------------------------------------------------------------------
        |
        | This comes from the simulation.
        |
        | It lets the Debt-Free Planner know the payment budget available
        | for each specific target loan, including rollover.
        |
        */

        $targetPaymentMap =
            $simulation['target_payment_map']
            ?? [];

        /*
        |--------------------------------------------------------------------------
        | Recommended Payment For Current Target
        |--------------------------------------------------------------------------
        */

        $recommendedTargetPayment = 0;

        $targetRolloverAmount = 0;

        if ($targetLoan) {

            $targetPaymentData =
                $targetPaymentMap[
                    $targetLoan->id
                ] ?? null;

            if ($targetPaymentData) {

                $recommendedTargetPayment =
                    (float) (
                        $targetPaymentData[
                            'planned_payment_budget'
                        ] ?? 0
                    );

                $targetRegularPayment =
                    (float) (
                        $targetPaymentData[
                            'regular_minimum'
                        ]
                        ?? $targetLoan->monthly_payment
                    );

                $targetRolloverAmount = max(
                    0,
                    round(
                        $recommendedTargetPayment
                        - $targetRegularPayment,
                        2
                    )
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Fallback
                |--------------------------------------------------------------------------
                */

                $recommendedTargetPayment =
                    min(
                        (float) $targetLoan->monthly_payment,
                        (float) $targetLoan->remaining_balance
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Debt Load
        |--------------------------------------------------------------------------
        */

        $debtToAvailableFunds = 0;

        if ($availableFunds > 0) {

            $debtToAvailableFunds = min(
                100,
                round(
                    (
                        $totalMinimumPayments
                        / $availableFunds
                    ) * 100,
                    1
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Total Original Debt
        |--------------------------------------------------------------------------
        */

        $totalOriginalDebt = round(
            (float) $loans->sum(
                fn (Loan $loan) =>
                    (float) $loan->total_payable
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Total Paid
        |--------------------------------------------------------------------------
        */

        $totalPaid = round(
            (float) $loans->sum(
                fn (Loan $loan) =>
                    (float) $loan->total_paid
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Debt-Free Progress
        |--------------------------------------------------------------------------
        */

        $debtFreeProgress = 0;

        if ($totalOriginalDebt > 0) {

            $debtFreeProgress = min(
                100,
                round(
                    (
                        $totalPaid
                        / $totalOriginalDebt
                    ) * 100,
                    1
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'debt-free.index',
            compact(
                'loans',
                'snowballLoans',
                'avalancheLoans',
                'strategy',
                'recommendedStrategy',
                'targetLoan',
                'totalDebt',
                'totalMinimumPayments',
                'availableFunds',
                'suggestedExtraPayment',
                'extraPayment',
                'debtToAvailableFunds',
                'totalOriginalDebt',
                'totalPaid',
                'debtFreeProgress',
                'simulation',
                'targetPaymentMap',
                'recommendedTargetPayment',
                'targetRolloverAmount'
            )
        );
    }
}