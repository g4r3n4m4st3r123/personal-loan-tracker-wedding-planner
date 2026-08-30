<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\SalaryPeriod;
use App\Models\Wedding;
use App\Services\AvailableFundsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private AvailableFundsService $availableFundsService
    ) {
    }

    /**
     * Display the main financial dashboard.
     */
    public function index(): View
    {
        $userId = auth()->id();

        $userSettings = auth()->user()->appSettings();

        $today = now()->startOfDay();

        $monthStart = now()
            ->startOfMonth()
            ->toDateString();

        $monthEnd = now()
            ->endOfMonth()
            ->toDateString();


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH SALARY
        |--------------------------------------------------------------------------
        */

        $monthlySalary = (float) SalaryPeriod::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'salary_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('salary_amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH ADDITIONAL INCOME
        |--------------------------------------------------------------------------
        */

        $monthlyOtherIncome = (float) Income::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'income_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | TOTAL MONTHLY INCOME
        |--------------------------------------------------------------------------
        */

        $monthlyIncome =
            $monthlySalary
            + $monthlyOtherIncome;


        /*
        |--------------------------------------------------------------------------
        | CURRENT SALARY PERIOD
        |--------------------------------------------------------------------------
        */

        $currentSalary = SalaryPeriod::where(
            'user_id',
            $userId
        )
            ->whereDate(
                'salary_date',
                '<=',
                $today->toDateString()
            )
            ->latest('salary_date')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | CURRENT SALARY PERIOD DEDUCTIONS
        |--------------------------------------------------------------------------
        */

        $currentSalaryDeductions = 0;

        $currentSalaryAvailable = 0;


        if ($currentSalary) {

            $currentSalaryDeductions = LoanPayment::where(
                'user_id',
                $userId
            )
                ->where(
                    'payment_source',
                    'Salary'
                )
                ->whereDate(
                    'payment_date',
                    '>=',
                    $currentSalary->period_start
                )
                ->whereDate(
                    'payment_date',
                    '<=',
                    $currentSalary->period_end
                )
                ->sum('amount');


            $currentSalaryAvailable = max(
                0,
                (float) $currentSalary->salary_amount
                + (float) $currentSalary->carry_over
                - (float) $currentSalaryDeductions
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH ADDITIONAL-INCOME PAYMENTS
        |--------------------------------------------------------------------------
        */

        $monthlyAdditionalIncomePayments = (float) LoanPayment::where(
            'user_id',
            $userId
        )
            ->whereIn(
                'payment_source',
                [
                    'Side Income',
                    'Freelance',
                    'Other Income',
                ]
            )
            ->whereBetween(
                'payment_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH NON-INCOME PAYMENTS
        |--------------------------------------------------------------------------
        |
        | Savings or legacy payment without an income source.
        |
        */

        $monthlyNonIncomePayments = (float) LoanPayment::where(
            'user_id',
            $userId
        )
            ->where(
                function ($query) {

                    $query
                        ->whereNull('payment_source')
                        ->orWhere(
                            'payment_source',
                            'Savings'
                        );
                }
            )
            ->whereBetween(
                'payment_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | MONTHLY LOAN PAYMENTS
        |--------------------------------------------------------------------------
        */

        $monthlyLoanPayments = (float) LoanPayment::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'payment_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | MONTHLY EXPENSES
        |--------------------------------------------------------------------------
        */

        $monthlyExpenses = (float) Expense::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'expense_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH ADDITIONAL INCOME AVAILABLE
        |--------------------------------------------------------------------------
        */

        $monthlyAdditionalIncomeAvailable = max(
            0,
            $monthlyOtherIncome
            - $monthlyAdditionalIncomePayments
        );


        /*
        |--------------------------------------------------------------------------
        | CURRENT TOTAL AVAILABLE MONEY
        |--------------------------------------------------------------------------
        |
        | Current salary available:
        |
        | Current Salary
        | + Carry-over
        | - Salary-funded payments
        |
        | Additional income available:
        |
        | Additional Income
        | - Additional-income loan payments
        |
        */

        $currentTotalAvailableMoney =
            $currentSalaryAvailable
            + $monthlyAdditionalIncomeAvailable;


        /*
        |--------------------------------------------------------------------------
        | REMAINING MONEY AFTER CURRENT COMMITMENTS
        |--------------------------------------------------------------------------
        |
        | Subtract:
        |
        | - Monthly expenses
        | - Savings-funded loan payments
        | - Legacy/non-income loan payments
        |
        | Salary and additional-income loan payments are already
        | deducted from their respective balances above.
        |
        */

        $remainingMoney = max(
            0,
            $currentTotalAvailableMoney
            - $monthlyExpenses
            - $monthlyNonIncomePayments
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL AVAILABLE FUNDS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | The dashboard's "Available Money" now uses the same
        | current-money calculation as the dashboard itself.
        |
        | This prevents the Dashboard from showing ₱0 while the
        | Salary page still shows money remaining in the current period.
        |
        */

        $totalAvailableFunds = $remainingMoney;


        /*
        |--------------------------------------------------------------------------
        | TOTAL INCOME-FUNDED PAYMENTS
        |--------------------------------------------------------------------------
        */

        $incomeFundedLoanPayments =
            $this->availableFundsService
                ->totalIncomeFundedPayments(
                    $userId
                );


        /*
        |--------------------------------------------------------------------------
        | ALL LOANS
        |--------------------------------------------------------------------------
        */

        $loans = Loan::where(
            'user_id',
            $userId
        )
            ->withSum(
                'payments',
                'amount'
            )
            ->get();


        $totalOutstandingLoans = 0;

        $totalOriginalLoans = 0;

        $totalAmountPaid = 0;

        $activeLoans = 0;

        $completedLoans = 0;

        $overdueLoans = 0;


        foreach ($loans as $loan) {

            /*
            |--------------------------------------------------------------------------
            | Calculate Total Payable
            |--------------------------------------------------------------------------
            */

            $totalPayable =
                (float) $loan->principal_amount;


            if (
                in_array(
                    $loan->interest_type,
                    [
                        'simple',
                        'fixed',
                    ],
                    true
                )
            ) {

                $totalPayable +=
                    $totalPayable
                    * (
                        (float) $loan->interest_rate
                        / 100
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Amount Paid
            |--------------------------------------------------------------------------
            */

            $paid =
                (float) (
                    $loan->payments_sum_amount
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | Remaining Balance
            |--------------------------------------------------------------------------
            */

            $remaining =
                max(
                    0,
                    $totalPayable - $paid
                );


            $totalOriginalLoans +=
                $totalPayable;

            $totalAmountPaid +=
                $paid;

            $totalOutstandingLoans +=
                $remaining;


            /*
            |--------------------------------------------------------------------------
            | Loan Status
            |--------------------------------------------------------------------------
            */

            if ($remaining <= 0) {

                $completedLoans++;

            } elseif (
                $loan->due_date
                && $loan->due_date->isPast()
            ) {

                $overdueLoans++;

            } else {

                $activeLoans++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | WEDDING DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->first();


        $weddingBudget = 0;

        $weddingPlannedBudget = 0;

        $weddingActualExpenses = 0;

        $weddingBudgetRemaining = 0;

        $weddingBudgetUsagePercentage = 0;

        $weddingTotalGuests = 0;

        $weddingAttendingGuests = 0;

        $weddingPendingGuests = 0;

        $weddingPlusOnes = 0;

        $weddingEstimatedHeadcount = 0;

        $weddingTotalTasks = 0;

        $weddingCompletedTasks = 0;

        $weddingPendingTasks = 0;

        $weddingOverdueTasks = 0;

        $weddingChecklistPercentage = 0;

        $weddingTotalVendors = 0;

        $weddingVendorContracted = 0;

        $weddingVendorPaid = 0;

        $weddingVendorOutstanding = 0;

        $weddingDaysRemaining = null;

        $weddingNextTimelineItem = null;

        $weddingNextTask = null;

        $weddingNextVendor = null;


        if ($wedding) {

            /*
            |--------------------------------------------------------------------------
            | Wedding Date / Countdown
            |--------------------------------------------------------------------------
            */

            if ($wedding->wedding_date) {

                $weddingDaysRemaining = now()
                    ->startOfDay()
                    ->diffInDays(
                        $wedding->wedding_date,
                        false
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Wedding Budget
            |--------------------------------------------------------------------------
            */

            $weddingBudget =
                (float) $wedding->budget;


            $weddingBudgets = $wedding->budgets()
                ->with('expenses')
                ->get();


            $weddingPlannedBudget =
                (float) $weddingBudgets->sum(
                    'planned_amount'
                );


            $weddingActualExpenses =
                (float) $weddingBudgets->sum(
                    fn ($budget) =>
                        $budget->actual_amount
                );


            $weddingBudgetRemaining =
                max(
                    0,
                    $weddingPlannedBudget
                    - $weddingActualExpenses
                );


            $weddingBudgetUsagePercentage =
                $weddingPlannedBudget > 0
                    ? min(
                        100,
                        round(
                            (
                                $weddingActualExpenses
                                / $weddingPlannedBudget
                            ) * 100,
                            1
                        )
                    )
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Wedding Guests
            |--------------------------------------------------------------------------
            */

            $weddingGuests =
                $wedding->guests()->get();


            $weddingTotalGuests =
                $weddingGuests->count();


            $weddingAttendingGuests =
                $weddingGuests
                    ->where(
                        'rsvp_status',
                        'attending'
                    )
                    ->count();


            $weddingPendingGuests =
                $weddingGuests
                    ->where(
                        'rsvp_status',
                        'pending'
                    )
                    ->count();


            $weddingPlusOnes =
                $weddingGuests
                    ->where(
                        'plus_one',
                        true
                    )
                    ->count();


            $weddingEstimatedHeadcount =
                $weddingAttendingGuests
                + $weddingPlusOnes;


            /*
            |--------------------------------------------------------------------------
            | Wedding Checklist
            |--------------------------------------------------------------------------
            */

            $weddingTasks =
                $wedding->tasks()->get();


            $weddingTotalTasks =
                $weddingTasks->count();


            $weddingCompletedTasks =
                $weddingTasks
                    ->where(
                        'status',
                        'completed'
                    )
                    ->count();


            $weddingPendingTasks =
                $weddingTasks
                    ->where(
                        'status',
                        'pending'
                    )
                    ->count();


            $weddingOverdueTasks =
                $weddingTasks
                    ->filter(
                        fn ($task) =>
                            $task->is_overdue
                    )
                    ->count();


            $weddingChecklistPercentage =
                $weddingTotalTasks > 0
                    ? round(
                        (
                            $weddingCompletedTasks
                            / $weddingTotalTasks
                        ) * 100,
                        1
                    )
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Wedding Vendors
            |--------------------------------------------------------------------------
            */

            $weddingVendors =
                $wedding->vendors()->get();


            $weddingTotalVendors =
                $weddingVendors->count();


            $weddingVendorContracted =
                (float) $weddingVendors->sum(
                    'agreed_amount'
                );


            $weddingVendorPaid =
                (float) $weddingVendors->sum(
                    'amount_paid'
                );


            $weddingVendorOutstanding =
                max(
                    0,
                    $weddingVendorContracted
                    - $weddingVendorPaid
                );


            /*
            |--------------------------------------------------------------------------
            | Next Timeline Item
            |--------------------------------------------------------------------------
            */

            $weddingNextTimelineItem =
                $wedding
                    ->timelineItems()
                    ->whereDate(
                        'event_date',
                        '>=',
                        $today->toDateString()
                    )
                    ->where(
                        'status',
                        '!=',
                        'completed'
                    )
                    ->orderBy('event_date')
                    ->orderBy('start_time')
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | Next Checklist Task
            |--------------------------------------------------------------------------
            */

            $weddingNextTask =
                $wedding
                    ->tasks()
                    ->whereNotNull(
                        'due_date'
                    )
                    ->where(
                        'status',
                        '!=',
                        'completed'
                    )
                    ->whereDate(
                        'due_date',
                        '>=',
                        $today->toDateString()
                    )
                    ->orderBy('due_date')
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | Next Vendor Service
            |--------------------------------------------------------------------------
            */

            $weddingNextVendor =
                $wedding
                    ->vendors()
                    ->whereNotNull(
                        'service_date'
                    )
                    ->whereDate(
                        'service_date',
                        '>=',
                        $today->toDateString()
                    )
                    ->orderBy('service_date')
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | LOAN PAYMENT PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $monthlyLoanPaymentPercentage =
            $monthlyIncome > 0
                ? min(
                    100,
                    round(
                        (
                            $monthlyLoanPayments
                            / $monthlyIncome
                        ) * 100,
                        1
                    )
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | DEBT-TO-INCOME RATIO
        |--------------------------------------------------------------------------
        */

        $debtToIncomeRatio =
            $monthlyIncome > 0
                ? round(
                    (
                        $totalOutstandingLoans
                        / $monthlyIncome
                    ) * 100,
                    1
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | UPCOMING PAYMENTS
        |--------------------------------------------------------------------------
        */

        $upcomingPayments = Loan::where(
            'user_id',
            $userId
        )
            ->where(
                'status',
                '!=',
                'completed'
            )
            ->whereNotNull(
                'due_date'
            )
            ->whereDate(
                'due_date',
                '>=',
                $today->toDateString()
            )
            ->orderBy('due_date')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | OVERDUE PAYMENTS
        |--------------------------------------------------------------------------
        */

        $overduePayments = Loan::where(
            'user_id',
            $userId
        )
            ->where(
                'status',
                '!=',
                'completed'
            )
            ->whereNotNull(
                'due_date'
            )
            ->whereDate(
                'due_date',
                '<',
                $today->toDateString()
            )
            ->orderBy('due_date')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | LOAN BALANCE CHART
        |--------------------------------------------------------------------------
        */

        $loanChartLabels =
            $loans
                ->map(
                    fn ($loan) =>
                        $loan->loan_name
                )
                ->values()
                ->all();


        $loanChartBalances =
            $loans
                ->map(
                    function ($loan) {

                        $totalPayable =
                            (float) $loan->principal_amount;


                        if (
                            in_array(
                                $loan->interest_type,
                                [
                                    'simple',
                                    'fixed',
                                ],
                                true
                            )
                        ) {

                            $totalPayable +=
                                $totalPayable
                                * (
                                    (float) $loan->interest_rate
                                    / 100
                                );
                        }


                        $paid =
                            (float) (
                                $loan
                                    ->payments_sum_amount
                                ?? 0
                            );


                        return round(
                            max(
                                0,
                                $totalPayable - $paid
                            ),
                            2
                        );
                    }
                )
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | LOAN STATUS CHART
        |--------------------------------------------------------------------------
        */

        $loanStatusChart = [
            'active' =>
                $activeLoans,

            'completed' =>
                $completedLoans,

            'overdue' =>
                $overdueLoans,
        ];


        /*
        |--------------------------------------------------------------------------
        | FINANCIAL SUMMARY
        |--------------------------------------------------------------------------
        */

        $financialSummary = [
            'income' =>
                round(
                    $monthlyIncome,
                    2
                ),

            'loanPayments' =>
                round(
                    $monthlyLoanPayments,
                    2
                ),

            'expenses' =>
                round(
                    $monthlyExpenses,
                    2
                ),

            'remaining' =>
                round(
                    $remainingMoney,
                    2
                ),
        ];


        /*
        |--------------------------------------------------------------------------
        | INCOME BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $incomeBreakdown = [
            'salary' =>
                round(
                    $monthlySalary,
                    2
                ),

            'additional' =>
                round(
                    $monthlyOtherIncome,
                    2
                ),
        ];


        /*
        |--------------------------------------------------------------------------
        | PAYMENT BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $paymentBreakdown = [
            'incomeFunded' =>
                round(
                    $monthlyLoanPayments
                    - $monthlyNonIncomePayments,
                    2
                ),

            'otherFunded' =>
                round(
                    $monthlyNonIncomePayments,
                    2
                ),
        ];


        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'monthlyIncome',
                'monthlySalary',
                'monthlyOtherIncome',

                'totalOutstandingLoans',
                'totalOriginalLoans',
                'totalAmountPaid',

                'monthlyLoanPayments',
                // 'monthlyIncomeFundedLoanPayments',

                'monthlyAdditionalIncomePayments',
                'monthlyNonIncomePayments',

                'monthlyExpenses',

                'currentSalary',
                'currentSalaryAvailable',
                'currentSalaryDeductions',

                'monthlyAdditionalIncomeAvailable',
                'currentTotalAvailableMoney',

                'totalAvailableFunds',

                'incomeFundedLoanPayments',

                'remainingMoney',

                'monthlyLoanPaymentPercentage',
                'debtToIncomeRatio',

                'upcomingPayments',
                'overduePayments',

                'activeLoans',
                'completedLoans',
                'overdueLoans',

                'loanChartLabels',
                'loanChartBalances',
                'loanStatusChart',

                'financialSummary',
                'incomeBreakdown',
                'paymentBreakdown',

                /*
                |--------------------------------------------------------------------------
                | Wedding
                |--------------------------------------------------------------------------
                */

                'wedding',

                'weddingBudget',
                'weddingPlannedBudget',
                'weddingActualExpenses',
                'weddingBudgetRemaining',
                'weddingBudgetUsagePercentage',

                'weddingTotalGuests',
                'weddingAttendingGuests',
                'weddingPendingGuests',
                'weddingPlusOnes',
                'weddingEstimatedHeadcount',

                'weddingTotalTasks',
                'weddingCompletedTasks',
                'weddingPendingTasks',
                'weddingOverdueTasks',
                'weddingChecklistPercentage',

                'weddingTotalVendors',
                'weddingVendorContracted',
                'weddingVendorPaid',
                'weddingVendorOutstanding',

                'weddingDaysRemaining',

                'weddingNextTimelineItem',
                'weddingNextTask',
                'weddingNextVendor',

                'userSettings'
            )
        );
    }
}