<x-app-layout>

    @php

        /*
        |--------------------------------------------------------------------------
        | Dashboard Visibility
        |--------------------------------------------------------------------------
        */

        $showFinanceSection =
            $userSettings->show_finance_dashboard
            && $userSettings->dashboard_view !== 'wedding';

        $showWeddingSection =
            $userSettings->show_wedding_dashboard
            && $userSettings->dashboard_view !== 'finance';


        /*
        |--------------------------------------------------------------------------
        | MAIN AVAILABLE MONEY
        |--------------------------------------------------------------------------
        |
        | This matches the Salary page:
        |
        | Current Salary
        | + Carry-over
        | - Salary-based loan deductions
        |
        */

        $availableMoney =
            max(
                0,
                (float) $currentSalaryAvailable
            );


        /*
        |--------------------------------------------------------------------------
        | Available Money Percentage
        |--------------------------------------------------------------------------
        */

        $availableMoneyPercentage =
            $currentSalary && (float) $currentSalary->salary_amount > 0
                ? min(
                    100,
                    max(
                        0,
                        (
                            $availableMoney
                            /
                            (
                                (float) $currentSalary->salary_amount
                                +
                                (float) $currentSalary->carry_over
                            )
                        ) * 100
                    )
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Financial Health Helpers
        |--------------------------------------------------------------------------
        */

        $loanPaymentPercentage =
            $monthlyIncome > 0
                ? min(
                    100,
                    max(
                        0,
                        (
                            $monthlyLoanPayments
                            /
                            $monthlyIncome
                        ) * 100
                    )
                )
                : 0;


        $expensePercentage =
            $monthlyIncome > 0
                ? min(
                    100,
                    max(
                        0,
                        (
                            $monthlyExpenses
                            /
                            $monthlyIncome
                        ) * 100
                    )
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Wedding Helpers
        |--------------------------------------------------------------------------
        */

        $weddingBudgetPercentage =
            min(
                100,
                max(
                    0,
                    $weddingBudgetUsagePercentage
                )
            );


        $weddingTaskPercentage =
            min(
                100,
                max(
                    0,
                    $weddingChecklistPercentage
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Greeting
        |--------------------------------------------------------------------------
        */

        $hour = now()->hour;

        $greeting =
            $hour < 12
                ? 'Good morning'
                : (
                    $hour < 18
                        ? 'Good afternoon'
                        : 'Good evening'
                );

    @endphp


    {{-- ===================================================================== --}}
    {{-- FINANCE DASHBOARD --}}
    {{-- ===================================================================== --}}

    @if ($showFinanceSection)

        {{-- PAGE INTRO --}}

        <div class="mb-10">

            <p class="text-sm font-medium text-slate-500">
                {{ $formatter->date(now()) }}
            </p>


            <div class="mt-1 flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        {{ $greeting }}, {{ Auth::user()->name }}
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Here's a clear view of your money, upcoming commitments,
                        and anything that needs your attention.
                    </p>

                </div>


                <a
                    href="{{ route('reports.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                >
                    View Reports →
                </a>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- PRIMARY FINANCIAL OVERVIEW --}}
        {{-- ================================================================= --}}

        <div class="grid gap-6 xl:grid-cols-5">


            {{-- ============================================================= --}}
            {{-- AVAILABLE MONEY --}}
            {{-- ============================================================= --}}

            <div class="xl:col-span-3 overflow-hidden rounded-3xl bg-slate-900 p-7 text-white shadow-sm sm:p-8">

                <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">


                    {{-- Main Amount --}}

                    <div>

                        <p class="text-sm font-medium text-slate-300">
                            Available Money
                        </p>


                        <p class="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">
                            {{ $formatter->money($availableMoney) }}
                        </p>


                        @if ($currentSalary)

                            <p class="mt-3 max-w-md text-sm leading-6 text-slate-300">
                                Money still available from your current salary period
                                after salary-based loan deductions.
                            </p>

                        @else

                            <p class="mt-3 max-w-md text-sm leading-6 text-slate-300">
                                Add a salary record to start tracking your available money.
                            </p>

                        @endif


                        {{-- ================================================= --}}
                        {{-- CURRENT SALARY DETAILS --}}
                        {{-- ================================================= --}}

                        @if ($currentSalary)

                            <div class="mt-6 grid gap-3 sm:grid-cols-2">


                                {{-- Salary --}}

                                <div class="rounded-2xl bg-white/10 px-4 py-4">

                                    <p class="text-xs font-medium text-slate-400">
                                        Current Salary
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-white">
                                        {{ $formatter->money($currentSalary->salary_amount) }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ ucfirst(str_replace('-', ' ', $currentSalary->salary_type)) }}
                                    </p>

                                </div>


                                {{-- Salary Deductions --}}

                                <div class="rounded-2xl bg-white/10 px-4 py-4">

                                    <p class="text-xs font-medium text-slate-400">
                                        Salary Loan Deductions
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-white">
                                        {{ $formatter->money($currentSalaryDeductions) }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Deducted from current salary period
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- AVAILABLE RATE --}}

                    <div class="rounded-2xl bg-white/10 px-5 py-4 backdrop-blur-sm">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Available Rate
                        </p>

                        <p class="mt-1 text-2xl font-bold">
                            {{ number_format($availableMoneyPercentage, 1) }}%
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            of current salary
                        </p>

                    </div>

                </div>


                {{-- PROGRESS --}}

                @if ($currentSalary)

                    <div class="mt-8">

                        <div class="mb-2 flex items-center justify-between text-xs">

                            <span class="text-slate-400">
                                Salary remaining
                            </span>

                            <span class="font-semibold text-slate-200">
                                {{ number_format($availableMoneyPercentage, 1) }}%
                            </span>

                        </div>


                        <div class="h-2 overflow-hidden rounded-full bg-white/10">

                            <div
                                class="h-full rounded-full bg-white transition-all"
                                style="width: {{ $availableMoneyPercentage }}%"
                            ></div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- ============================================================= --}}
            {{-- MONTHLY SNAPSHOT --}}
            {{-- ============================================================= --}}

            <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-7 shadow-sm sm:p-8">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Monthly Snapshot
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-slate-900">
                            {{ now()->format('F Y') }}
                        </h2>

                    </div>


                    <span class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                        Live
                    </span>

                </div>


                <div class="mt-7 space-y-5">


                    {{-- Income --}}

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-sm text-slate-500">
                                Monthly Income
                            </p>

                            <p class="mt-1 text-base font-bold text-slate-900">
                                {{ $formatter->money($monthlyIncome) }}
                            </p>

                        </div>

                        <span class="text-sm font-semibold text-emerald-600">
                            100%
                        </span>

                    </div>


                    {{-- Loans --}}

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-sm text-slate-500">
                                Loan Payments
                            </p>

                            <p class="mt-1 text-base font-bold text-slate-900">
                                {{ $formatter->money($monthlyLoanPayments) }}
                            </p>

                        </div>

                        <span class="text-sm font-semibold text-rose-600">
                            {{ number_format($loanPaymentPercentage, 1) }}%
                        </span>

                    </div>


                    {{-- Expenses --}}

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-sm text-slate-500">
                                Expenses
                            </p>

                            <p class="mt-1 text-base font-bold text-slate-900">
                                {{ $formatter->money($monthlyExpenses) }}
                            </p>

                        </div>

                        <span class="text-sm font-semibold text-amber-600">
                            {{ number_format($expensePercentage, 1) }}%
                        </span>

                    </div>


                    {{-- Available Salary --}}

                    <div class="rounded-2xl bg-emerald-50 p-4">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-sm font-semibold text-emerald-800">
                                    Salary Still Available
                                </p>

                                <p class="mt-1 text-xs text-emerald-600">
                                    Current salary after salary-based loan deductions
                                </p>

                            </div>


                            <p class="text-lg font-bold text-emerald-700">
                                {{ $formatter->money($currentSalaryAvailable) }}
                            </p>

                        </div>

                    </div>


                    {{-- Outstanding Loans --}}

                    <div class="border-t border-slate-100 pt-5">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-semibold text-slate-700">
                                    Total Outstanding Loans
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Across all active loans
                                </p>

                            </div>

                            <p class="text-lg font-bold text-slate-900">
                                {{ $formatter->money($totalOutstandingLoans) }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- MONEY FLOW --}}
        {{-- ================================================================= --}}

        <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Money Flow
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-900">
                        This Month at a Glance
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        See how your income is distributed between loans, expenses,
                        and remaining money.
                    </p>

                </div>

            </div>


            <div class="mt-6 h-72">

                <canvas id="financialSummaryChart"></canvas>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- ACTION CENTER --}}
        {{-- ================================================================= --}}

        <div class="mt-8">

            <div class="mb-5">

                <p class="text-sm font-medium text-rose-500">
                    Action Center
                </p>

                <h2 class="mt-1 text-xl font-bold text-slate-900">
                    What needs your attention?
                </h2>

            </div>


            <div class="grid gap-6 lg:grid-cols-2">


                {{-- UPCOMING PAYMENTS --}}

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h3 class="text-base font-bold text-slate-900">
                                Upcoming Payments
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Your next loan commitments.
                            </p>

                        </div>


                        <a
                            href="{{ route('loans.index') }}"
                            class="text-sm font-semibold text-indigo-600 hover:underline"
                        >
                            View all
                        </a>

                    </div>


                    @if ($upcomingPayments->count())

                        <div class="mt-6 divide-y divide-slate-100">

                            @foreach ($upcomingPayments->take(4) as $loan)

                                @php

                                    $daysUntilPayment =
                                        now()
                                            ->startOfDay()
                                            ->diffInDays(
                                                $loan->due_date
                                                    ->copy()
                                                    ->startOfDay(),
                                                false
                                            );

                                @endphp


                                <a
                                    href="{{ route('loans.show', $loan) }}"
                                    class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0"
                                >

                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-semibold text-slate-800">
                                            {{ $loan->loan_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Due {{ $formatter->date($loan->due_date) }}
                                        </p>

                                    </div>


                                    <div class="shrink-0 text-right">

                                        <p class="text-sm font-bold text-slate-900">
                                            {{ $formatter->money($loan->monthly_payment) }}
                                        </p>


                                        @if ($daysUntilPayment === 0)

                                            <p class="mt-1 text-xs font-semibold text-rose-600">
                                                Due today
                                            </p>

                                        @elseif ($daysUntilPayment === 1)

                                            <p class="mt-1 text-xs font-semibold text-amber-600">
                                                Tomorrow
                                            </p>

                                        @else

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $daysUntilPayment }} days
                                            </p>

                                        @endif

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    @else

                        <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">

                            <p class="text-sm font-semibold text-slate-700">
                                No upcoming payments
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Your next loan commitments will appear here.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- NEEDS ATTENTION --}}

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h3 class="text-base font-bold text-slate-900">
                                Needs Attention
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Items that may require action.
                            </p>

                        </div>


                        <span class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-bold text-rose-600">
                            {{ $overdueLoans }} overdue
                        </span>

                    </div>


                    <div class="mt-6 space-y-3">


                        {{-- Overdue Loans --}}

                        @if ($overduePayments->count())

                            @foreach ($overduePayments->take(3) as $loan)

                                <a
                                    href="{{ route('loans.show', $loan) }}"
                                    class="block rounded-2xl border border-rose-100 bg-rose-50 p-4 transition hover:bg-rose-100"
                                >

                                    <div class="flex items-center justify-between gap-4">

                                        <div class="min-w-0">

                                            <p class="truncate text-sm font-semibold text-rose-800">
                                                {{ $loan->loan_name }}
                                            </p>

                                            <p class="mt-1 text-xs text-rose-600">
                                                Due {{ $formatter->date($loan->due_date) }}
                                            </p>

                                        </div>

                                        <span class="shrink-0 text-sm font-bold text-rose-700">
                                            {{ $formatter->money($loan->monthly_payment) }}
                                        </span>

                                    </div>

                                </a>

                            @endforeach

                        @else

                            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lg">
                                        ✓
                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-emerald-800">
                                            No overdue loans
                                        </p>

                                        <p class="mt-1 text-xs text-emerald-600">
                                            Your loan schedule is currently up to date.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- Wedding Task --}}

                        @if ($wedding && $weddingNextTask)

                            <a
                                href="{{ route('wedding.checklist') }}"
                                class="block rounded-2xl border border-amber-100 bg-amber-50 p-5 transition hover:bg-amber-100"
                            >

                                <p class="text-xs font-bold uppercase tracking-wide text-amber-700">
                                    Next Wedding Task
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{ $weddingNextTask->task_name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Due {{ $formatter->date($weddingNextTask->due_date) }}
                                </p>

                            </a>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- LOAN OVERVIEW --}}
        {{-- ================================================================= --}}

        <div class="mt-8 grid gap-6 xl:grid-cols-5">


            {{-- STATUS SUMMARY --}}

            <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                <div>

                    <h3 class="text-base font-bold text-slate-900">
                        Loan Overview
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Current position of your loans.
                    </p>

                </div>


                <div class="mt-7 space-y-6">


                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-slate-500">
                                Active Loans
                            </p>

                            <p class="mt-1 text-2xl font-bold text-indigo-600">
                                {{ $activeLoans }}
                            </p>

                        </div>

                        <span class="text-xs font-medium text-slate-400">
                            Currently ongoing
                        </span>

                    </div>


                    <div class="flex items-center justify-between border-t border-slate-100 pt-5">

                        <div>

                            <p class="text-sm text-slate-500">
                                Completed
                            </p>

                            <p class="mt-1 text-2xl font-bold text-emerald-600">
                                {{ $completedLoans }}
                            </p>

                        </div>

                        <span class="text-xs font-medium text-slate-400">
                            Fully paid
                        </span>

                    </div>


                    <div class="flex items-center justify-between border-t border-slate-100 pt-5">

                        <div>

                            <p class="text-sm text-slate-500">
                                Overdue
                            </p>

                            <p class="mt-1 text-2xl font-bold text-rose-600">
                                {{ $overdueLoans }}
                            </p>

                        </div>

                        <span class="text-xs font-medium text-slate-400">
                            Needs attention
                        </span>

                    </div>


                    <div class="border-t border-slate-100 pt-5">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-semibold text-slate-700">
                                    Debt-to-Income Ratio
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Outstanding debt vs monthly income
                                </p>

                            </div>


                            <p
                                class="text-xl font-bold
                                {{
                                    $debtToIncomeRatio >= 50
                                        ? 'text-rose-600'
                                        : (
                                            $debtToIncomeRatio >= 30
                                                ? 'text-amber-600'
                                                : 'text-emerald-600'
                                        )
                                }}"
                            >
                                {{ number_format($debtToIncomeRatio, 1) }}%
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- LOAN BALANCES --}}

            <div class="xl:col-span-3 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <h3 class="text-base font-bold text-slate-900">
                            Remaining Loan Balances
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Outstanding balance per loan.
                        </p>

                    </div>


                    <a
                        href="{{ route('loans.index') }}"
                        class="text-sm font-semibold text-indigo-600 hover:underline"
                    >
                        Manage loans →
                    </a>

                </div>


                @if (count($loanChartLabels))

                    <div class="mt-6 h-72">
                        <canvas id="loanBalanceChart"></canvas>
                    </div>

                @else

                    <div class="mt-6 flex h-64 items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50">

                        <div class="text-center">

                            <p class="text-sm font-semibold text-slate-700">
                                No loan data yet
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Add a loan to see your balances here.
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- ===================================================================== --}}
    {{-- WEDDING DASHBOARD --}}
    {{-- ===================================================================== --}}

    @if ($showWeddingSection)

        <div class="mt-14">


            {{-- WEDDING HEADER --}}

            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-rose-500">
                        Wedding Planner
                    </p>

                    <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                        Wedding Overview
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        The important details without the clutter.
                    </p>

                </div>


                <a
                    href="{{ route('wedding.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:underline"
                >
                    Open Wedding Planner →
                </a>

            </div>


            @if ($wedding)

                {{-- WEDDING MAIN PANEL --}}

                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">


                    {{-- WEDDING BASIC INFO --}}

                    <div class="flex flex-col gap-8 p-7 lg:flex-row lg:items-end lg:justify-between lg:p-8">

                        <div>

                            <p class="text-sm font-medium text-slate-500">

                                {{ $wedding->partner_name
                                    ? 'Celebrating with ' . $wedding->partner_name
                                    : 'Your Wedding' }}

                            </p>


                            <h3 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                                {{ $wedding->wedding_name }}
                            </h3>


                            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">

                                @if ($wedding->wedding_date)

                                    <span>
                                        {{ $formatter->date($wedding->wedding_date) }}
                                    </span>

                                @endif


                                @if ($wedding->venue)

                                    <span>
                                        {{ $wedding->venue }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- COUNTDOWN --}}

                        <div class="rounded-2xl bg-rose-50 px-6 py-5 text-center">

                            <p class="text-xs font-bold uppercase tracking-wide text-rose-500">
                                Countdown
                            </p>


                            @if ($weddingDaysRemaining === null)

                                <p class="mt-1 text-xl font-bold text-rose-700">
                                    No date
                                </p>

                            @elseif ($weddingDaysRemaining > 0)

                                <p class="mt-1 text-4xl font-bold text-rose-700">
                                    {{ number_format($weddingDaysRemaining) }}
                                </p>

                                <p class="mt-1 text-xs text-rose-500">
                                    days to go
                                </p>

                            @elseif ($weddingDaysRemaining === 0)

                                <p class="mt-1 text-xl font-bold text-rose-700">
                                    Today!
                                </p>

                            @else

                                <p class="mt-1 text-xl font-bold text-slate-500">
                                    Passed
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- WEDDING METRICS --}}

                    <div class="grid border-t border-slate-100 sm:grid-cols-2 lg:grid-cols-4">


                        {{-- Budget --}}

                        <div class="border-b border-slate-100 p-6 sm:border-r">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Budget Used
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($weddingBudgetPercentage, 1) }}%
                            </p>

                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full
                                    {{
                                        $weddingBudgetPercentage >= 100
                                            ? 'bg-rose-500'
                                            : (
                                                $weddingBudgetPercentage >= 80
                                                    ? 'bg-amber-500'
                                                    : 'bg-emerald-500'
                                            )
                                    }}"
                                    style="width: {{ $weddingBudgetPercentage }}%"
                                ></div>

                            </div>

                            <p class="mt-2 text-xs text-slate-400">
                                {{ $formatter->money($weddingBudgetRemaining) }} remaining
                            </p>

                        </div>


                        {{-- Guests --}}

                        <div class="border-b border-slate-100 p-6 lg:border-r">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Estimated Guests
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $weddingEstimatedHeadcount }}
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                {{ $weddingAttendingGuests }} attending
                                · {{ $weddingPlusOnes }} plus-one(s)
                            </p>

                        </div>


                        {{-- Checklist --}}

                        <div class="border-b border-slate-100 p-6 sm:border-r">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Checklist
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($weddingTaskPercentage, 1) }}%
                            </p>

                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full bg-indigo-500"
                                    style="width: {{ $weddingTaskPercentage }}%"
                                ></div>

                            </div>

                            <p class="mt-2 text-xs text-slate-400">
                                {{ $weddingCompletedTasks }} of {{ $weddingTotalTasks }} completed
                            </p>

                        </div>


                        {{-- Vendors --}}

                        <div class="p-6">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Vendor Balance
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $formatter->money($weddingVendorOutstanding) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                {{ $weddingTotalVendors }} vendor(s)
                            </p>

                        </div>

                    </div>


                    {{-- UPCOMING WEDDING --}}

                    <div class="border-t border-slate-100 p-6 lg:p-8">

                        <div class="grid gap-6 lg:grid-cols-3">


                            {{-- Timeline --}}

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-indigo-500">
                                    Next Timeline
                                </p>

                                @if ($weddingNextTimelineItem)

                                    <a
                                        href="{{ route('wedding.timeline') }}"
                                        class="mt-2 block"
                                    >

                                        <p class="font-semibold text-slate-900">
                                            {{ $weddingNextTimelineItem->title }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $formatter->date($weddingNextTimelineItem->event_date) }}
                                        </p>

                                        @if ($weddingNextTimelineItem->start_time)

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ \Carbon\Carbon::parse($weddingNextTimelineItem->start_time)->format('g:i A') }}
                                            </p>

                                        @endif

                                    </a>

                                @else

                                    <p class="mt-2 text-sm text-slate-400">
                                        No upcoming timeline event.
                                    </p>

                                @endif

                            </div>


                            {{-- Task --}}

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-amber-500">
                                    Next Task
                                </p>

                                @if ($weddingNextTask)

                                    <a
                                        href="{{ route('wedding.checklist') }}"
                                        class="mt-2 block"
                                    >

                                        <p class="font-semibold text-slate-900">
                                            {{ $weddingNextTask->task_name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Due {{ $formatter->date($weddingNextTask->due_date) }}
                                        </p>

                                        <p class="mt-1 text-xs uppercase text-slate-400">
                                            {{ $weddingNextTask->priority }} priority
                                        </p>

                                    </a>

                                @else

                                    <p class="mt-2 text-sm text-slate-400">
                                        No upcoming task.
                                    </p>

                                @endif

                            </div>


                            {{-- Vendor --}}

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-emerald-500">
                                    Next Vendor
                                </p>

                                @if ($weddingNextVendor)

                                    <a
                                        href="{{ route('wedding.vendors') }}"
                                        class="mt-2 block"
                                    >

                                        <p class="font-semibold text-slate-900">
                                            {{ $weddingNextVendor->vendor_name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $weddingNextVendor->service_type }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $formatter->date($weddingNextVendor->service_date) }}
                                        </p>

                                    </a>

                                @else

                                    <p class="mt-2 text-sm text-slate-400">
                                        No upcoming vendor service.
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-8 text-center shadow-sm">

                    <p class="text-lg font-bold text-slate-900">
                        Wedding Planner isn't set up yet
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Create your wedding to start tracking budget, guests,
                        vendors, tasks, and timeline.
                    </p>

                    <a
                        href="{{ route('wedding.index') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Create Wedding →
                    </a>

                </div>

            @endif

        </div>

    @endif


    {{-- ===================================================================== --}}
    {{-- CHART.JS --}}
    {{-- ===================================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Currency Formatter
                |--------------------------------------------------------------------------
                */

                const currencySymbol =
                    @json($formatter->symbol());


                const formatMoney = (value) => {

                    return currencySymbol
                        +
                        Number(value).toLocaleString(
                            'en-US',
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        );
                };


                /*
                |--------------------------------------------------------------------------
                | Financial Summary Chart
                |--------------------------------------------------------------------------
                */

                const financialElement =
                    document.getElementById(
                        'financialSummaryChart'
                    );


                if (financialElement) {

                    new Chart(
                        financialElement,
                        {
                            type: 'bar',

                            data: {

                                labels: [
                                    'Income',
                                    'Loan Payments',
                                    'Expenses',
                                    'Available Salary'
                                ],

                                datasets: [
                                    {
                                        label: 'Amount',

                                        data: [
                                            {{ $financialSummary['income'] }},
                                            {{ $financialSummary['loanPayments'] }},
                                            {{ $financialSummary['expenses'] }},
                                            {{ $currentSalaryAvailable }}
                                        ],

                                        borderWidth: 0,

                                        borderRadius: 8,

                                        barThickness: 42
                                    }
                                ]

                            },

                            options: {

                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {

                                    legend: {
                                        display: false
                                    },

                                    tooltip: {

                                        callbacks: {

                                            label:
                                                function (context) {

                                                    return formatMoney(
                                                        context.raw
                                                    );

                                                }

                                        }

                                    }

                                },

                                scales: {

                                    x: {

                                        grid: {
                                            display: false
                                        },

                                        border: {
                                            display: false
                                        }

                                    },

                                    y: {

                                        beginAtZero: true,

                                        grid: {
                                            color:
                                                'rgba(148, 163, 184, 0.12)'
                                        },

                                        border: {
                                            display: false
                                        },

                                        ticks: {

                                            callback:
                                                function (value) {

                                                    return formatMoney(
                                                        value
                                                    );

                                                }

                                        }

                                    }

                                }

                            }

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Loan Balance Chart
                |--------------------------------------------------------------------------
                */

                const loanBalanceElement =
                    document.getElementById(
                        'loanBalanceChart'
                    );


                if (loanBalanceElement) {

                    new Chart(
                        loanBalanceElement,
                        {
                            type: 'bar',

                            data: {

                                labels:
                                    @json($loanChartLabels),

                                datasets: [
                                    {
                                        label: 'Remaining Balance',

                                        data:
                                            @json($loanChartBalances),

                                        borderWidth: 0,

                                        borderRadius: 8,

                                        barThickness: 28
                                    }
                                ]

                            },

                            options: {

                                indexAxis: 'y',

                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {

                                    legend: {
                                        display: false
                                    },

                                    tooltip: {

                                        callbacks: {

                                            label:
                                                function (context) {

                                                    return formatMoney(
                                                        context.raw
                                                    );

                                                }

                                        }

                                    }

                                },

                                scales: {

                                    x: {

                                        beginAtZero: true,

                                        grid: {
                                            color:
                                                'rgba(148, 163, 184, 0.12)'
                                        },

                                        border: {
                                            display: false
                                        },

                                        ticks: {

                                            callback:
                                                function (value) {

                                                    return formatMoney(
                                                        value
                                                    );

                                                }

                                        }

                                    },

                                    y: {

                                        grid: {
                                            display: false
                                        },

                                        border: {
                                            display: false
                                        }

                                    }

                                }

                            }

                        }
                    );

                }

            }
        );

    </script>

</x-app-layout>