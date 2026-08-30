<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\SalaryPeriod;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Reports landing page.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Finance Reports.
     */
    public function finance(Request $request): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        $from = $request->input(
            'from',
            now()->startOfMonth()->format('Y-m-d')
        );

        $to = $request->input(
            'to',
            now()->endOfMonth()->format('Y-m-d')
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INVALID DATE RANGE
        |--------------------------------------------------------------------------
        */

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        /*
        |--------------------------------------------------------------------------
        | INCOME
        |--------------------------------------------------------------------------
        */

        $salaryIncome = (float) SalaryPeriod::where(
            'user_id',
            $userId
        )
            ->whereBetween('salary_date', [$from, $to])
            ->sum('salary_amount');

        $additionalIncome = (float) Income::where(
            'user_id',
            $userId
        )
            ->whereBetween('income_date', [$from, $to])
            ->sum('amount');

        $totalIncome = $salaryIncome + $additionalIncome;

        /*
        |--------------------------------------------------------------------------
        | LOAN PAYMENTS
        |--------------------------------------------------------------------------
        */

        $loanPayments = (float) LoanPayment::where(
            'user_id',
            $userId
        )
            ->whereBetween('payment_date', [$from, $to])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = (float) Expense::where(
            'user_id',
            $userId
        )
            ->whereBetween('expense_date', [$from, $to])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL REMAINING
        |--------------------------------------------------------------------------
        */

        $financialRemaining = max(
            0,
            $totalIncome - $loanPayments - $expenses
        );

        /*
        |--------------------------------------------------------------------------
        | LOAN REPORT
        |--------------------------------------------------------------------------
        */

        $loans = Loan::where(
            'user_id',
            $userId
        )
            ->withSum('payments', 'amount')
            ->get();

        $totalLoanPayable = 0;
        $totalLoanPaid = 0;
        $totalLoanOutstanding = 0;

        $activeLoans = 0;
        $completedLoans = 0;
        $overdueLoans = 0;

        foreach ($loans as $loan) {

            $payable = (float) $loan->principal_amount;

            if (
                in_array(
                    $loan->interest_type,
                    ['simple', 'fixed'],
                    true
                )
            ) {
                $payable +=
                    $payable *
                    (
                        (float) $loan->interest_rate / 100
                    );
            }

            $paid = (float) (
                $loan->payments_sum_amount ?? 0
            );

            $outstanding = max(
                0,
                $payable - $paid
            );

            $totalLoanPayable += $payable;
            $totalLoanPaid += $paid;
            $totalLoanOutstanding += $outstanding;

            if ($outstanding <= 0) {

                $completedLoans++;

            } elseif (
                $loan->due_date &&
                $loan->due_date->isPast()
            ) {

                $overdueLoans++;

            } else {

                $activeLoans++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOAN PAYMENT PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $loanPaymentPercentage = $totalIncome > 0
            ? min(
                100,
                round(
                    ($loanPayments / $totalIncome) * 100,
                    1
                )
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | DEBT TO INCOME RATIO
        |--------------------------------------------------------------------------
        */

        $debtToIncomeRatio = $totalIncome > 0
            ? round(
                ($totalLoanOutstanding / $totalIncome) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $paymentMethodBreakdown = LoanPayment::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'payment_date',
                [$from, $to]
            )
            ->selectRaw(
                'payment_method, SUM(amount) as total'
            )
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | INCOME TYPE BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $incomeTypeBreakdown = Income::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'income_date',
                [$from, $to]
            )
            ->selectRaw(
                'income_type, SUM(amount) as total'
            )
            ->groupBy('income_type')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | EXPENSE CATEGORY BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $expenseCategoryBreakdown = Expense::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'expense_date',
                [$from, $to]
            )
            ->selectRaw(
                'category, SUM(amount) as total'
            )
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REPORT SUMMARY
        |--------------------------------------------------------------------------
        */

        $reportSummary = [
            'totalIncome' => round($totalIncome, 2),
            'loanPayments' => round($loanPayments, 2),
            'expenses' => round($expenses, 2),
            'remaining' => round($financialRemaining, 2),
        ];

        return view(
            'reports.finance',
            compact(
                'from',
                'to',
                'salaryIncome',
                'additionalIncome',
                'totalIncome',
                'loanPayments',
                'expenses',
                'financialRemaining',
                'loans',
                'totalLoanPayable',
                'totalLoanPaid',
                'totalLoanOutstanding',
                'activeLoans',
                'completedLoans',
                'overdueLoans',
                'loanPaymentPercentage',
                'debtToIncomeRatio',
                'paymentMethodBreakdown',
                'incomeTypeBreakdown',
                'expenseCategoryBreakdown',
                'reportSummary'
            )
        );
    }

    /**
     * Wedding Reports.
     */
    public function wedding(): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | WEDDING
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->first();

        /*
        |--------------------------------------------------------------------------
        | DEFAULT VALUES
        |--------------------------------------------------------------------------
        */

        $weddingBudget = 0;
        $weddingPlanned = 0;
        $weddingActual = 0;
        $weddingRemaining = 0;
        $weddingBudgetUsage = 0;

        $weddingGuests = 0;
        $weddingAttending = 0;
        $weddingPending = 0;
        $weddingPlusOnes = 0;
        $weddingHeadcount = 0;

        $weddingTasks = 0;
        $weddingCompletedTasks = 0;
        $weddingOverdueTasks = 0;

        $weddingVendors = 0;
        $weddingVendorContracted = 0;
        $weddingVendorPaid = 0;
        $weddingVendorOutstanding = 0;

        if ($wedding) {

            /*
            |--------------------------------------------------------------------------
            | WEDDING BUDGET
            |--------------------------------------------------------------------------
            */

            $weddingBudget = (float) $wedding->budget;

            $weddingBudgets = $wedding->budgets()
                ->with('expenses')
                ->get();

            $weddingPlanned = (float) $weddingBudgets->sum(
                'planned_amount'
            );

            $weddingActual = (float) $weddingBudgets->sum(
                fn ($budget) => $budget->actual_amount
            );

            $weddingRemaining = max(
                0,
                $weddingPlanned - $weddingActual
            );

            $weddingBudgetUsage = $weddingPlanned > 0
                ? min(
                    100,
                    round(
                        ($weddingActual / $weddingPlanned) * 100,
                        1
                    )
                )
                : 0;

            /*
            |--------------------------------------------------------------------------
            | GUESTS
            |--------------------------------------------------------------------------
            */

            $guestRecords = $wedding->guests()->get();

            $weddingGuests = $guestRecords->count();

            $weddingAttending = $guestRecords
                ->where('rsvp_status', 'attending')
                ->count();

            $weddingPending = $guestRecords
                ->where('rsvp_status', 'pending')
                ->count();

            $weddingPlusOnes = $guestRecords
                ->where('plus_one', true)
                ->count();

            $weddingHeadcount =
                $weddingAttending +
                $weddingPlusOnes;

            /*
            |--------------------------------------------------------------------------
            | CHECKLIST
            |--------------------------------------------------------------------------
            */

            $taskRecords = $wedding->tasks()->get();

            $weddingTasks = $taskRecords->count();

            $weddingCompletedTasks = $taskRecords
                ->where('status', 'completed')
                ->count();

            $weddingOverdueTasks = $taskRecords
                ->filter(
                    fn ($task) => $task->is_overdue
                )
                ->count();

            /*
            |--------------------------------------------------------------------------
            | VENDORS
            |--------------------------------------------------------------------------
            */

            $vendorRecords = $wedding->vendors()->get();

            $weddingVendors = $vendorRecords->count();

            $weddingVendorContracted = (float) $vendorRecords->sum(
                'agreed_amount'
            );

            $weddingVendorPaid = (float) $vendorRecords->sum(
                'amount_paid'
            );

            $weddingVendorOutstanding = max(
                0,
                $weddingVendorContracted -
                $weddingVendorPaid
            );
        }

        return view(
            'reports.wedding',
            compact(
                'wedding',
                'weddingBudget',
                'weddingPlanned',
                'weddingActual',
                'weddingRemaining',
                'weddingBudgetUsage',
                'weddingGuests',
                'weddingAttending',
                'weddingPending',
                'weddingPlusOnes',
                'weddingHeadcount',
                'weddingTasks',
                'weddingCompletedTasks',
                'weddingOverdueTasks',
                'weddingVendors',
                'weddingVendorContracted',
                'weddingVendorPaid',
                'weddingVendorOutstanding'
            )
        );
    }
}