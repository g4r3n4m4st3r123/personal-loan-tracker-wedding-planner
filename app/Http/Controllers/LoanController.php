<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Display a listing of the user's loans.
     */
    public function index(): View
    {
        $loans = Loan::where(
            'user_id',
            auth()->id()
        )
            ->latest()
            ->get();

        return view(
            'loans.index',
            compact('loans')
        );
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create(): View
    {
        return view('loans.create');
    }

    /**
     * Store a newly created loan.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'loan_name' => [
                'required',
                'string',
                'max:255',
            ],

            'lender' => [
                'nullable',
                'string',
                'max:255',
            ],

            'principal_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'interest_type' => [
                'nullable',
                'in:none,simple,fixed',
            ],

            'term_months' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'monthly_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'repayment_strategy' => [
                'required',
                'in:standard,extra_principal,balloon,snowball,avalanche,custom',
            ],

            'planned_extra_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'balloon_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $validated['interest_rate'] =
            $validated['interest_rate'] ?? 0;

        $validated['interest_type'] =
            $validated['interest_type'] ?? 'none';

        $validated['term_months'] =
            $validated['term_months'] ?? 1;

        $validated['repayment_strategy'] =
            $validated['repayment_strategy'] ?? 'standard';

        $validated['planned_extra_payment'] =
            $validated['planned_extra_payment'] ?? 0;

        $validated['balloon_payment'] =
            $validated['balloon_payment'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Calculate Total Payable
        |--------------------------------------------------------------------------
        */

        $principal =
            (float) $validated['principal_amount'];

        $interestRate =
            (float) $validated['interest_rate'];

        $interestType =
            $validated['interest_type'];

        $termMonths =
            (int) $validated['term_months'];

        $totalInterest = 0;

        if (
            in_array(
                $interestType,
                ['simple', 'fixed'],
                true
            )
        ) {
            $totalInterest =
                $principal
                * ($interestRate / 100);
        }

        $totalPayable =
            round(
                $principal + $totalInterest,
                2
            );

        /*
        |--------------------------------------------------------------------------
        | Strategy-Specific Validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['repayment_strategy'] === 'balloon'
            && (float) $validated['balloon_payment'] <= 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'balloon_payment' =>
                        'Please enter a final balloon payment amount.',
                ]);
        }

        if (
            $validated['repayment_strategy'] === 'balloon'
            && (float) $validated['balloon_payment'] >= $totalPayable
            && $termMonths > 1
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'balloon_payment' =>
                        'The balloon payment must be less than the total payable amount when the loan has more than one installment.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Strategy-Specific Values
        |--------------------------------------------------------------------------
        */

        if (
            $validated['repayment_strategy'] !== 'balloon'
        ) {
            $validated['balloon_payment'] = 0;
        }

        if (
            $validated['repayment_strategy'] !== 'extra_principal'
        ) {
            $validated['planned_extra_payment'] = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically Calculate Monthly Payment
        |--------------------------------------------------------------------------
        |
        | We only calculate it when the user leaves it empty or enters 0.
        |
        */

        if (
            !isset($validated['monthly_payment'])
            || (float) $validated['monthly_payment'] <= 0
        ) {
            $validated['monthly_payment'] =
                round(
                    $totalPayable / $termMonths,
                    2
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Initial Loan Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'active';

        /*
        |--------------------------------------------------------------------------
        | Assign Authenticated User
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] =
            auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Create Loan
        |--------------------------------------------------------------------------
        */

        $loan = Loan::create(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Installment Schedule
        |--------------------------------------------------------------------------
        */

        $loan->generateInstallments();

        /*
        |--------------------------------------------------------------------------
        | Create Due-Date Notification
        |--------------------------------------------------------------------------
        */

        $this->createDueNotification($loan);

        return redirect()
            ->route('loans.index')
            ->with(
                'success',
                'Loan added successfully.'
            );
    }

    /**
     * Display the specified loan.
     */
    public function show(
        Loan $loan
    ): View {

        $this->authorizeLoan($loan);

        /*
        |--------------------------------------------------------------------------
        | Make Sure Installments Exist
        |--------------------------------------------------------------------------
        */

        if (
            $loan->installments()->count() === 0
        ) {
            $loan->generateInstallments();
        }

        /*
        |--------------------------------------------------------------------------
        | Refresh Installment Statuses
        |--------------------------------------------------------------------------
        */

        $loan->refreshInstallmentStatuses();

        /*
        |--------------------------------------------------------------------------
        | Payment History
        |--------------------------------------------------------------------------
        */

        $payments = $loan->payments()
            ->with('installment')
            ->latest('payment_date')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Installment Schedule
        |--------------------------------------------------------------------------
        */

        $installments = $loan->installments()
            ->orderBy('installment_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Next Unpaid Installment
        |--------------------------------------------------------------------------
        */

        $nextInstallment = $installments
            ->first(
                fn ($installment) =>
                    $installment->status !== 'paid'
            );

        /*
        |--------------------------------------------------------------------------
        | Schedule Statistics
        |--------------------------------------------------------------------------
        */

        $totalInstallments =
            $installments->count();

        $paidInstallments =
            $installments
                ->where('status', 'paid')
                ->count();

        $partialInstallments =
            $installments
                ->where('status', 'partial')
                ->count();

        $overdueInstallments =
            $installments
                ->where('status', 'overdue')
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'loans.show',
            compact(
                'loan',
                'payments',
                'installments',
                'nextInstallment',
                'totalInstallments',
                'paidInstallments',
                'partialInstallments',
                'overdueInstallments'
            )
        );
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit(
        Loan $loan
    ): View {

        $this->authorizeLoan($loan);

        return view(
            'loans.edit',
            compact('loan')
        );
    }

    /**
     * Update the specified loan.
     */
    public function update(
        Request $request,
        Loan $loan
    ): RedirectResponse {

        $this->authorizeLoan($loan);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'loan_name' => [
                'required',
                'string',
                'max:255',
            ],

            'lender' => [
                'nullable',
                'string',
                'max:255',
            ],

            'principal_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'interest_type' => [
                'nullable',
                'in:none,simple,fixed',
            ],

            'term_months' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'monthly_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'repayment_strategy' => [
                'required',
                'in:standard,extra_principal,balloon,snowball,avalanche,custom',
            ],

            'planned_extra_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'balloon_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $validated['interest_rate'] =
            $validated['interest_rate'] ?? 0;

        $validated['interest_type'] =
            $validated['interest_type'] ?? 'none';

        $validated['term_months'] =
            $validated['term_months'] ?? 1;

        $validated['repayment_strategy'] =
            $validated['repayment_strategy'] ?? 'standard';

        $validated['planned_extra_payment'] =
            $validated['planned_extra_payment'] ?? 0;

        $validated['balloon_payment'] =
            $validated['balloon_payment'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Calculate Total Payable
        |--------------------------------------------------------------------------
        */

        $principal =
            (float) $validated['principal_amount'];

        $interestRate =
            (float) $validated['interest_rate'];

        $interestType =
            $validated['interest_type'];

        $termMonths =
            (int) $validated['term_months'];

        $totalInterest = 0;

        if (
            in_array(
                $interestType,
                ['simple', 'fixed'],
                true
            )
        ) {
            $totalInterest =
                $principal
                * ($interestRate / 100);
        }

        $totalPayable =
            round(
                $principal + $totalInterest,
                2
            );

        /*
        |--------------------------------------------------------------------------
        | Strategy-Specific Validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['repayment_strategy'] === 'balloon'
            && (float) $validated['balloon_payment'] <= 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'balloon_payment' =>
                        'Please enter a final balloon payment amount.',
                ]);
        }

        if (
            $validated['repayment_strategy'] === 'balloon'
            && (float) $validated['balloon_payment'] >= $totalPayable
            && $termMonths > 1
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'balloon_payment' =>
                        'The balloon payment must be less than the total payable amount when the loan has more than one installment.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Strategy-Specific Values
        |--------------------------------------------------------------------------
        */

        if (
            $validated['repayment_strategy'] !== 'balloon'
        ) {
            $validated['balloon_payment'] = 0;
        }

        if (
            $validated['repayment_strategy'] !== 'extra_principal'
        ) {
            $validated['planned_extra_payment'] = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically Calculate Monthly Payment
        |--------------------------------------------------------------------------
        */

        if (
            !isset($validated['monthly_payment'])
            || (float) $validated['monthly_payment'] <= 0
        ) {
            $validated['monthly_payment'] =
                round(
                    $totalPayable / $termMonths,
                    2
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Track Whether The Loan Already Has Payments
        |--------------------------------------------------------------------------
        */

        $hasPayments =
            $loan->payments()->exists();

        /*
        |--------------------------------------------------------------------------
        | Update Loan
        |--------------------------------------------------------------------------
        */

        $loan->update(
            $validated
        );

        $loan->refresh();

        /*
        |--------------------------------------------------------------------------
        | Installment Schedule Handling
        |--------------------------------------------------------------------------
        |
        | If there are no payments yet, it is safe to rebuild the schedule.
        |
        | If payments already exist, we DO NOT regenerate installments because
        | doing so would wipe the existing installment payment allocations.
        |
        */

        if (!$hasPayments) {

            $loan->generateInstallments();

        } else {

            $loan->refreshInstallmentStatuses();
        }

        /*
        |--------------------------------------------------------------------------
        | Refresh Loan Status
        |--------------------------------------------------------------------------
        */

        $loan->updateStatus();

        /*
        |--------------------------------------------------------------------------
        | Due Notification
        |--------------------------------------------------------------------------
        */

        $this->createDueNotification($loan);

        return redirect()
            ->route(
                'loans.show',
                $loan
            )
            ->with(
                'success',
                'Loan updated successfully.'
            );
    }

    /**
     * Remove the specified loan.
     */
    public function destroy(
        Loan $loan
    ): RedirectResponse {

        $this->authorizeLoan($loan);

        $loan->delete();

        return redirect()
            ->route('loans.index')
            ->with(
                'success',
                'Loan deleted successfully.'
            );
    }

    /**
     * Create a notification when a loan is due within 7 days.
     */
    private function createDueNotification(
        Loan $loan
    ): void {

        /*
        |--------------------------------------------------------------------------
        | No Due Date
        |--------------------------------------------------------------------------
        */

        if (!$loan->due_date) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Days Until Due
        |--------------------------------------------------------------------------
        */

        $daysUntilDue = now()
            ->startOfDay()
            ->diffInDays(
                $loan->due_date
                    ->copy()
                    ->startOfDay(),
                false
            );

        /*
        |--------------------------------------------------------------------------
        | Only Notify Within 7 Days
        |--------------------------------------------------------------------------
        */

        if (
            $daysUntilDue < 0
            || $daysUntilDue > 7
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Notification
        |--------------------------------------------------------------------------
        */

        $alreadyExists = Notification::where(
            'user_id',
            auth()->id()
        )
            ->where(
                'type',
                'loan'
            )
            ->where(
                'title',
                'Loan Due Soon'
            )
            ->where(
                'url',
                route(
                    'loans.show',
                    $loan
                )
            )
            ->whereDate(
                'created_at',
                now()->toDateString()
            )
            ->exists();

        if ($alreadyExists) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' =>
                auth()->id(),

            'type' =>
                'loan',

            'title' =>
                'Loan Due Soon',

            'message' =>
                $loan->loan_name
                . ' is due on '
                . $loan->due_date->format('F d, Y')
                . '.',

            'url' =>
                route(
                    'loans.show',
                    $loan
                ),
        ]);
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