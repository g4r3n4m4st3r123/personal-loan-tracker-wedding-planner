<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanPayment;
use App\Services\AvailableFundsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanPaymentController extends Controller
{
    public function __construct(
        private AvailableFundsService $availableFundsService
    ) {
    }

    /**
     * Display all loan payments for the authenticated user.
     */
    public function all(): View
    {
        $payments = LoanPayment::where(
            'user_id',
            auth()->id()
        )
            ->with([
                'loan',
                'installment',
            ])
            ->latest('payment_date')
            ->latest()
            ->get();

        $totalPayments = (float) $payments->sum('amount');

        return view(
            'payments.index',
            compact(
                'payments',
                'totalPayments'
            )
        );
    }

    /**
     * Display payment history for a loan.
     */
    public function index(Loan $loan): View
    {
        $this->authorizeLoan($loan);

        $loan->refreshInstallmentStatuses();

        $payments = $loan->payments()
            ->with('installment')
            ->latest('payment_date')
            ->latest()
            ->get();

        $installments = $loan->installments()
            ->orderBy('installment_number')
            ->get();

        return view(
            'loan-payments.index',
            compact(
                'loan',
                'payments',
                'installments'
            )
        );
    }

/**
 * Show the form for recording a payment.
 */
public function create(Loan $loan): View
{
    $this->authorizeLoan($loan);

    /*
    |--------------------------------------------------------------------------
    | Make sure the loan has an installment schedule.
    |--------------------------------------------------------------------------
    */

    if ($loan->installments()->count() === 0) {
        $loan->generateInstallments();
    }

    $loan->refreshInstallmentStatuses();

    /*
    |--------------------------------------------------------------------------
    | Next Installment
    |--------------------------------------------------------------------------
    */

    $nextInstallment = $loan->installments()
        ->where('status', '!=', 'paid')
        ->orderBy('installment_number')
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Available Income Funds
    |--------------------------------------------------------------------------
    */

    $availableIncomeFunds =
        $this->availableFundsService->availableFunds(
            auth()->id()
        );

    /*
    |--------------------------------------------------------------------------
    | Recommended Payment
    |--------------------------------------------------------------------------
    |
    | Normal payment starts with the required monthly payment.
    |
    | The Debt-Free Planner can optionally send a rollover amount
    | through the query string.
    |
    */

    $regularPayment = min(
        (float) $loan->monthly_payment,
        (float) $loan->remaining_balance
    );

    $rolloverAmount = request()->query(
        'rollover',
        0
    );

    $rolloverAmount = is_numeric($rolloverAmount)
        ? max(0, (float) $rolloverAmount)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Recommended Payment
    |--------------------------------------------------------------------------
    */

    $recommendedPayment = min(
        (float) $loan->remaining_balance,
        $regularPayment + $rolloverAmount
    );

    /*
    |--------------------------------------------------------------------------
    | Extra Payment From Planner
    |--------------------------------------------------------------------------
    */

    $plannerExtraPayment = request()->query(
        'extra_payment',
        0
    );

    $plannerExtraPayment = is_numeric(
        $plannerExtraPayment
    )
        ? max(
            0,
            (float) $plannerExtraPayment
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Strategy
    |--------------------------------------------------------------------------
    */

    $strategy = request()->query(
        'strategy',
        null
    );

    if (!in_array(
        $strategy,
        [
            'snowball',
            'avalanche',
        ],
        true
    )) {
        $strategy = null;
    }

    return view(
        'loan-payments.create',
        compact(
            'loan',
            'availableIncomeFunds',
            'nextInstallment',
            'regularPayment',
            'rolloverAmount',
            'recommendedPayment',
            'plannerExtraPayment',
            'strategy'
        )
    );
}

    /**
     * Store a new loan payment.
     *
     * A single payment can cover multiple installments.
     */
    public function store(
        Request $request,
        Loan $loan
    ): RedirectResponse {

        $this->authorizeLoan($loan);

        /*
        |--------------------------------------------------------------------------
        | Make sure installments exist.
        |--------------------------------------------------------------------------
        */

        if ($loan->installments()->count() === 0) {
            $loan->generateInstallments();
        }

        $loan->refreshInstallmentStatuses();

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . max(
                    0.01,
                    $loan->remaining_balance
                ),
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_source' => [
                'required',
                'in:Salary,Side Income,Freelance,Other Income,Savings',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $paymentSource = $validated['payment_source'];

        $paymentAmount = round(
            (float) $validated['amount'],
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Automatically mark Salary payments as Salary Deduction
        |--------------------------------------------------------------------------
        */

        if ($paymentSource === 'Salary') {
            $validated['payment_method'] = 'Salary Deduction';
        }

        /*
        |--------------------------------------------------------------------------
        | Find Unpaid Installments
        |--------------------------------------------------------------------------
        |
        | We intentionally get ALL unpaid installments because an advance
        | payment may cover more than one installment.
        |
        */

        $unpaidInstallments = $loan->installments()
            ->where('status', '!=', 'paid')
            ->orderBy('installment_number')
            ->get();

        if ($unpaidInstallments->isEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        'This loan has no unpaid installments.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Available Income Funds
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $paymentSource,
                AvailableFundsService::INCOME_SOURCES,
                true
            )
        ) {

            $availableFunds =
                $this->availableFundsService->availableFunds(
                    auth()->id(),
                    $validated['payment_date']
                );

            if ($paymentAmount > $availableFunds) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'amount' =>
                            'Insufficient available income funds. '
                            . 'You have only ₱'
                            . number_format(
                                $availableFunds,
                                2
                            )
                            . ' available for income-funded payments.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Record Payment + Allocate Across Installments
        |--------------------------------------------------------------------------
        */

        $payment = DB::transaction(function () use (
            $validated,
            $loan,
            $unpaidInstallments,
            $paymentAmount
        ) {

            /*
            |--------------------------------------------------------------------------
            | Determine Installments Covered By This Payment
            |--------------------------------------------------------------------------
            */

            $remainingPayment = $paymentAmount;

            $primaryInstallment = null;

            foreach ($unpaidInstallments as $installment) {

                if ($remainingPayment <= 0) {
                    break;
                }

                $installmentRemaining =
                    max(
                        0,
                        (float) $installment->amount_due
                        - (float) $installment->amount_paid
                    );

                if ($installmentRemaining <= 0) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Amount Applied To This Installment
                |--------------------------------------------------------------------------
                */

                $amountToApply = min(
                    $remainingPayment,
                    $installmentRemaining
                );

                $newAmountPaid =
                    round(
                        (float) $installment->amount_paid
                        + $amountToApply,
                        2
                    );

                $newAmountPaid = min(
                    (float) $installment->amount_due,
                    $newAmountPaid
                );

                /*
                |--------------------------------------------------------------------------
                | Determine Installment Status
                |--------------------------------------------------------------------------
                */

                if (
                    $newAmountPaid
                    >= (float) $installment->amount_due
                ) {

                    $status = 'paid';

                    $paidAt = now();

                } elseif ($newAmountPaid > 0) {

                    $status = 'partial';

                    $paidAt = null;

                } elseif (
                    $installment->due_date
                        ->isBefore(now()->startOfDay())
                ) {

                    $status = 'overdue';

                    $paidAt = null;

                } elseif (
                    $installment->due_date->isToday()
                ) {

                    $status = 'due_today';

                    $paidAt = null;

                } else {

                    $status = 'upcoming';

                    $paidAt = null;
                }

                $installment->update([
                    'amount_paid' => $newAmountPaid,

                    'status' => $status,

                    'paid_at' => $paidAt,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Keep The First Installment As The Payment's Primary Installment
                |--------------------------------------------------------------------------
                */

                if ($primaryInstallment === null) {
                    $primaryInstallment = $installment;
                }

                /*
                |--------------------------------------------------------------------------
                | Reduce Remaining Payment
                |--------------------------------------------------------------------------
                */

                $remainingPayment =
                    round(
                        $remainingPayment - $amountToApply,
                        2
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Safety Check
            |--------------------------------------------------------------------------
            |
            | This should never happen because the payment amount has already
            | been validated against the loan's remaining balance.
            |
            */

            if ($remainingPayment > 0.009) {

                throw new \RuntimeException(
                    'Payment could not be fully allocated to the loan installments.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create ONE Payment Record
            |--------------------------------------------------------------------------
            */

            return LoanPayment::create([
                'loan_id' => $loan->id,

                'loan_installment_id' =>
                    $primaryInstallment?->id,

                'user_id' => auth()->id(),

                'amount' => $paymentAmount,

                'payment_date' =>
                    $validated['payment_date'],

                'payment_method' =>
                    $validated['payment_method'] ?? null,

                'payment_source' =>
                    $validated['payment_source'],

                'reference_number' =>
                    $validated['reference_number'] ?? null,

                'notes' =>
                    $validated['notes'] ?? null,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Refresh Loan Status
        |--------------------------------------------------------------------------
        */

        $loan->refreshInstallmentStatuses();

        $loan->refresh();

        $loan->updateStatus();

        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('loans.show', $loan)
            ->with(
                'success',
                'Payment of '
                . $this->formatMoney($paymentAmount)
                . ' recorded successfully.'
            );
    }

    /**
     * Delete a payment.
     */
    public function destroy(
        LoanPayment $payment
    ): RedirectResponse {

        if (
            $payment->user_id !== auth()->id()
        ) {
            abort(403);
        }

        $loan = $payment->loan;

        DB::transaction(function () use (
            $payment,
            $loan
        ) {

            /*
            |--------------------------------------------------------------------------
            | Get All Installments
            |--------------------------------------------------------------------------
            */

            $installments = $loan->installments()
                ->orderBy('installment_number')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Remove Payment
            |--------------------------------------------------------------------------
            */

            $paymentAmount =
                (float) $payment->amount;

            $payment->delete();

            /*
            |--------------------------------------------------------------------------
            | Recalculate Installments From Remaining Payments
            |--------------------------------------------------------------------------
            |
            | This is safer than subtracting from only one installment because
            | an advance payment may have covered multiple installments.
            |
            */

            foreach ($installments as $installment) {

                $installment->update([
                    'amount_paid' => 0,
                    'status' => 'upcoming',
                    'paid_at' => null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Re-apply Remaining Payments In Chronological Order
            |--------------------------------------------------------------------------
            */

            $remainingPayments = $loan->payments()
                ->orderBy('payment_date')
                ->orderBy('id')
                ->get();

            foreach ($remainingPayments as $existingPayment) {

                $remainingAmount =
                    (float) $existingPayment->amount;

                foreach ($installments as $installment) {

                    if ($remainingAmount <= 0) {
                        break;
                    }

                    $installmentRemaining =
                        max(
                            0,
                            (float) $installment->amount_due
                            - (float) $installment->amount_paid
                        );

                    if ($installmentRemaining <= 0) {
                        continue;
                    }

                    $amountToApply = min(
                        $remainingAmount,
                        $installmentRemaining
                    );

                    $installment->amount_paid =
                        round(
                            (float) $installment->amount_paid
                            + $amountToApply,
                            2
                        );

                    $remainingAmount =
                        round(
                            $remainingAmount - $amountToApply,
                            2
                        );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate All Installment Statuses
            |--------------------------------------------------------------------------
            */

            $today = now()->startOfDay();

            foreach ($installments as $installment) {

                $amountPaid =
                    (float) $installment->amount_paid;

                $amountDue =
                    (float) $installment->amount_due;

                if ($amountPaid >= $amountDue) {

                    $installment->status = 'paid';
                    $installment->paid_at = now();

                } elseif ($amountPaid > 0) {

                    $installment->status =
                        $installment->due_date->lt($today)
                            ? 'overdue'
                            : 'partial';

                    $installment->paid_at = null;

                } elseif ($installment->due_date->lt($today)) {

                    $installment->status = 'overdue';
                    $installment->paid_at = null;

                } elseif ($installment->due_date->isToday()) {

                    $installment->status = 'due_today';
                    $installment->paid_at = null;

                } else {

                    $installment->status = 'upcoming';
                    $installment->paid_at = null;
                }

                $installment->save();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Refresh Loan Status
        |--------------------------------------------------------------------------
        */

        $loan->refreshInstallmentStatuses();

        $loan->refresh();

        $loan->updateStatus();

        return redirect()
            ->route('loans.show', $loan)
            ->with(
                'success',
                'Payment deleted successfully. '
                . 'Installment balances and loan status were recalculated.'
            );
    }

    /**
     * Format currency.
     */
    private function formatMoney(float $amount): string
    {
        return '₱' . number_format(
            $amount,
            2
        );
    }

    /**
     * Make sure the authenticated user owns the loan.
     */
    private function authorizeLoan(
        Loan $loan
    ): void {

        if (
            $loan->user_id !== auth()->id()
        ) {
            abort(403);
        }
    }
}