<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | Dashboard Display Settings
        |--------------------------------------------------------------------------
        */

        $showFinanceSection =
            $userSettings->show_finance_dashboard
            && $userSettings->dashboard_view !== 'wedding';

        $showWeddingSection =
            $userSettings->show_wedding_dashboard
            && $userSettings->dashboard_view !== 'finance';
    @endphp


    {{-- =============================================================== --}}
    {{-- FINANCE DASHBOARD --}}
    {{-- =============================================================== --}}

    @if ($showFinanceSection)

        {{-- PAGE HEADER --}}

        <div class="mb-8">

            <p class="text-sm font-medium text-slate-500">
                {{ $formatter->date(now()) }}
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Good evening, {{ Auth::user()->name }} 👋
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Here's an overview of your finances and financial commitments.
            </p>

        </div>


        {{-- =========================================================== --}}
        {{-- KPI CARDS --}}
        {{-- =========================================================== --}}

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Monthly Income --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Monthly Income
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $formatter->money($monthlyIncome) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                        💰
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Salary + additional income this month
                </p>

            </div>


            {{-- Outstanding Loans --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Outstanding Loans
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $formatter->money($totalOutstandingLoans) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl">
                        💳
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Total remaining loan balance
                </p>

            </div>


            {{-- Monthly Expenses --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Monthly Expenses
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $formatter->money($monthlyExpenses) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-xl">
                        💸
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Recorded expenses this month
                </p>

            </div>


            {{-- Total Available Funds --}}

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-emerald-700">
                            Total Available Funds
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-800">
                            {{ $formatter->money($totalAvailableFunds) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-xl shadow-sm">
                        💵
                    </div>

                </div>

                <p class="mt-4 text-xs text-emerald-600">
                    Salary + additional income − income-funded payments
                </p>

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- FINANCIAL ANALYTICS --}}
        {{-- =========================================================== --}}

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Loan Payment Rate --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Loan Payment Rate
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($monthlyLoanPaymentPercentage, 1) }}%
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-xl">
                        📊
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Percentage of this month's income used for loan payments
                </p>

            </div>


            {{-- Debt-to-Income Ratio --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Debt-to-Income Ratio
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($debtToIncomeRatio, 1) }}%
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-xl">
                        📈
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Outstanding debt compared with monthly income
                </p>

            </div>


            {{-- Overdue Loans --}}

            <div class="rounded-2xl border border-red-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Overdue Loans
                        </p>

                        <p class="mt-2 text-2xl font-bold text-red-600">
                            {{ $overdueLoans }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-xl">
                        ⚠️
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Loans past their due date
                </p>

            </div>


            {{-- Total Paid --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Total Paid
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-600">
                            {{ $formatter->money($totalAmountPaid) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                        ✅
                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Total payments recorded across all loans
                </p>

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- AVAILABLE FUNDS SUMMARY --}}
        {{-- =========================================================== --}}

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-bold text-slate-900">
                        Available Funds
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Your current financial position after income-funded loan payments.
                    </p>

                </div>

                <div class="text-left sm:text-right">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Remaining Money
                    </p>

                    <p class="mt-1 text-3xl font-bold text-slate-900">
                        {{ $formatter->money($remainingMoney) }}
                    </p>

                </div>

            </div>


            <div class="mt-6 grid gap-4 sm:grid-cols-3">


                {{-- Salary --}}

                <div class="rounded-xl bg-slate-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Salary This Month
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-800">
                        {{ $formatter->money($monthlySalary) }}
                    </p>

                </div>


                {{-- Additional Income --}}

                <div class="rounded-xl bg-slate-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Additional Income
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-800">
                        {{ $formatter->money($monthlyOtherIncome) }}
                    </p>

                </div>


                {{-- Income Loan Payments --}}

                <div class="rounded-xl bg-slate-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Income Used For Loans
                    </p>

                    <p class="mt-2 text-xl font-bold text-red-600">
                        {{ $formatter->money($incomeFundedLoanPayments) }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- CHARTS --}}
        {{-- =========================================================== --}}

        <div class="mt-6 grid gap-6 xl:grid-cols-2">


            {{-- Loan Balance --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-base font-bold text-slate-900">
                    Loan Balance Overview
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Remaining balance for each loan.
                </p>


                @if (count($loanChartLabels))

                    <div class="mt-5 h-72">
                        <canvas id="loanBalanceChart"></canvas>
                    </div>

                @else

                    <div class="mt-5 flex h-64 items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50">

                        <div class="text-center">

                            <div class="text-4xl">
                                📊
                            </div>

                            <p class="mt-3 text-sm font-semibold text-slate-700">
                                No loans yet
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Add a loan to see your balance chart.
                            </p>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Loan Status --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-base font-bold text-slate-900">
                    Loan Status
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Current status of your loans.
                </p>


                @if ($activeLoans || $completedLoans || $overdueLoans)

                    <div class="mt-5 h-72">
                        <canvas id="loanStatusChart"></canvas>
                    </div>

                @else

                    <div class="mt-5 flex h-64 items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50">

                        <div class="text-center">

                            <div class="text-4xl">
                                💳
                            </div>

                            <p class="mt-3 text-sm font-semibold text-slate-700">
                                No loan data yet
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Loan status will appear here.
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- MONTHLY FINANCIAL SUMMARY --}}
        {{-- =========================================================== --}}

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-base font-bold text-slate-900">
                Monthly Financial Summary
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Compare income, loan payments, expenses, and money remaining.
            </p>

            <div class="mt-5 h-64">
                <canvas id="financialSummaryChart"></canvas>
            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- PAYMENT SOURCE + INCOME BREAKDOWN --}}
        {{-- =========================================================== --}}

        <div class="mt-6 grid gap-6 xl:grid-cols-2">


            {{-- Payment Sources --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-base font-bold text-slate-900">
                    Loan Payment Sources
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    How your loan payments are being funded this month.
                </p>


                <div class="mt-6 space-y-5">


                    {{-- Income Funded --}}

                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-sm font-medium text-slate-600">
                                Salary / Additional Income
                            </span>

                            <span class="font-semibold text-emerald-600">
                                {{ $formatter->money($paymentBreakdown['incomeFunded']) }}
                            </span>

                        </div>


                        @php
                            $paymentTotal = $monthlyLoanPayments;

                            $incomePaymentPercentage = $paymentTotal > 0
                                ? (
                                    (
                                        $monthlyLoanPayments
                                        - $monthlyNonIncomePayments
                                    ) / $paymentTotal
                                ) * 100
                                : 0;
                        @endphp


                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-emerald-500 transition-all"
                                style="width: {{ min(100, max(0, $incomePaymentPercentage)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Other Funded --}}

                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-sm font-medium text-slate-600">
                                Savings / Other
                            </span>

                            <span class="font-semibold text-indigo-600">
                                {{ $formatter->money($paymentBreakdown['otherFunded']) }}
                            </span>

                        </div>


                        @php
                            $otherPaymentPercentage = $paymentTotal > 0
                                ? ($monthlyNonIncomePayments / $paymentTotal) * 100
                                : 0;
                        @endphp


                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-indigo-500 transition-all"
                                style="width: {{ min(100, max(0, $otherPaymentPercentage)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Total --}}

                    <div class="border-t border-slate-100 pt-4">

                        <div class="flex items-center justify-between">

                            <span class="font-semibold text-slate-700">
                                Total This Month
                            </span>

                            <span class="text-xl font-bold text-slate-900">
                                {{ $formatter->money($monthlyLoanPayments) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Income Breakdown --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-base font-bold text-slate-900">
                    Monthly Income Breakdown
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Where this month's income is coming from.
                </p>


                <div class="mt-6 space-y-5">


                    {{-- Salary --}}

                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-sm font-medium text-slate-600">
                                Salary
                            </span>

                            <span class="font-semibold text-slate-900">
                                {{ $formatter->money($incomeBreakdown['salary']) }}
                            </span>

                        </div>


                        @php
                            $incomeTotal = $monthlyIncome;

                            $salaryPercentage = $incomeTotal > 0
                                ? ($monthlySalary / $incomeTotal) * 100
                                : 0;
                        @endphp


                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-slate-700 transition-all"
                                style="width: {{ min(100, max(0, $salaryPercentage)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Additional Income --}}

                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-sm font-medium text-slate-600">
                                Side / Freelance / Other
                            </span>

                            <span class="font-semibold text-slate-900">
                                {{ $formatter->money($incomeBreakdown['additional']) }}
                            </span>

                        </div>


                        @php
                            $additionalPercentage = $incomeTotal > 0
                                ? ($monthlyOtherIncome / $incomeTotal) * 100
                                : 0;
                        @endphp


                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-indigo-500 transition-all"
                                style="width: {{ min(100, max(0, $additionalPercentage)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Total --}}

                    <div class="border-t border-slate-100 pt-4">

                        <div class="flex items-center justify-between">

                            <span class="font-semibold text-slate-700">
                                Total Monthly Income
                            </span>

                            <span class="text-xl font-bold text-emerald-600">
                                {{ $formatter->money($monthlyIncome) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- BOTTOM SECTION --}}
        {{-- =========================================================== --}}

        <div class="mt-6 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">


            {{-- Upcoming Payments --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Upcoming Payments
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Your next financial commitments
                        </p>

                    </div>

                    <span class="text-xl">
                        📅
                    </span>

                </div>


                @if ($upcomingPayments->count())

                    <div class="mt-5 space-y-3">

                        @foreach ($upcomingPayments as $loan)

                            <a
                                href="{{ route('loans.show', $loan) }}"
                                class="flex items-center justify-between rounded-xl border border-slate-100 p-4 transition hover:bg-slate-50"
                            >

                                <div>

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $loan->loan_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Due {{ $formatter->date($loan->due_date) }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-sm font-bold text-indigo-600">
                                        {{ $formatter->money($loan->monthly_payment) }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Monthly payment
                                    </p>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @else

                    <div class="mt-5 rounded-xl border border-dashed border-slate-200 p-6 text-center">

                        <p class="text-sm font-medium text-slate-600">
                            No upcoming payments
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Add a loan to start tracking payment dates.
                        </p>

                    </div>

                @endif

            </div>


            {{-- Overdue Loans --}}

            <div class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Overdue Loans
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Loans that need your attention.
                        </p>

                    </div>

                    <span class="text-xl">
                        ⚠️
                    </span>

                </div>


                @if ($overduePayments->count())

                    <div class="mt-5 space-y-3">

                        @foreach ($overduePayments as $loan)

                            <a
                                href="{{ route('loans.show', $loan) }}"
                                class="block rounded-xl border border-red-100 bg-red-50 p-4 transition hover:bg-red-100"
                            >

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-semibold text-red-800">
                                            {{ $loan->loan_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-red-600">
                                            Due {{ $formatter->date($loan->due_date) }}
                                        </p>

                                    </div>


                                    <div class="text-right">

                                        <p class="text-sm font-bold text-red-700">
                                            {{ $formatter->money($loan->monthly_payment) }}
                                        </p>

                                        <p class="mt-1 text-xs text-red-500">
                                            Payment due
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @else

                    <div class="mt-5 rounded-xl border border-dashed border-slate-200 p-6 text-center">

                        <div class="text-3xl">
                            ✅
                        </div>

                        <p class="mt-3 text-sm font-semibold text-slate-700">
                            No overdue loans
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            You're all caught up!
                        </p>

                    </div>

                @endif

            </div>


            {{-- Financial Summary --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Financial Summary
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Current monthly activity
                        </p>

                    </div>

                    <span class="text-xl">
                        📈
                    </span>

                </div>


                <div class="mt-5 space-y-4">


                    <div class="flex items-center justify-between">

                        <span class="text-sm text-slate-500">
                            Monthly Income
                        </span>

                        <span class="font-semibold text-emerald-600">
                            {{ $formatter->money($monthlyIncome) }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between">

                        <span class="text-sm text-slate-500">
                            Loan Payments
                        </span>

                        <span class="font-semibold text-red-600">
                            {{ $formatter->money($monthlyLoanPayments) }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between">

                        <span class="text-sm text-slate-500">
                            Expenses
                        </span>

                        <span class="font-semibold text-rose-600">
                            {{ $formatter->money($monthlyExpenses) }}
                        </span>

                    </div>


                    <div class="border-t border-slate-100 pt-4">

                        <div class="flex items-center justify-between">

                            <span class="font-semibold text-slate-700">
                                Remaining Money
                            </span>

                            <span class="text-xl font-bold text-slate-900">
                                {{ $formatter->money($remainingMoney) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =============================================================== --}}
    {{-- WEDDING DASHBOARD --}}
    {{-- =============================================================== --}}

    @if ($showWeddingSection)

        <div class="mt-8">

            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-rose-500">
                        Wedding Planner
                    </p>

                    <h2 class="text-2xl font-bold text-slate-900">
                        Wedding Overview
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Your wedding planning progress at a glance.
                    </p>

                </div>


                <a
                    href="{{ route('wedding.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:underline"
                >
                    Open Wedding Planner →
                </a>

            </div>


            {{-- Wedding Budget --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Wedding Budget
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Overall wedding budget progress
                        </p>

                    </div>

                    <span class="text-2xl">
                        💍
                    </span>

                </div>


                @if ($wedding)

                    <div class="mt-6">

                        <div class="flex items-end justify-between">

                            <div>

                                <p class="text-3xl font-bold text-slate-900">
                                    {{ number_format($weddingBudgetUsagePercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Budget used
                                </p>

                            </div>

                            <div class="text-right">

                                <p class="text-sm font-semibold text-rose-600">
                                    {{ $formatter->money($weddingActualExpenses) }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    Actual spending
                                </p>

                            </div>

                        </div>


                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-rose-500 transition-all"
                                style="width: {{ min(100, $weddingBudgetUsagePercentage) }}%"
                            ></div>

                        </div>


                        <div class="mt-4 flex justify-between text-sm">

                            <div>

                                <p class="text-xs text-slate-400">
                                    Budget
                                </p>

                                <p class="mt-1 font-semibold text-slate-800">
                                    {{ $formatter->money($weddingBudget) }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-xs text-slate-400">
                                    Remaining
                                </p>

                                <p class="mt-1 font-semibold text-emerald-600">
                                    {{ $formatter->money($weddingBudgetRemaining) }}
                                </p>

                            </div>

                        </div>

                    </div>

                @else

                    <div class="mt-8 rounded-xl border border-dashed border-slate-200 p-6 text-center">

                        <p class="text-sm font-medium text-slate-600">
                            No wedding created yet
                        </p>

                        <a
                            href="{{ route('wedding.index') }}"
                            class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:underline"
                        >
                            Create Wedding →
                        </a>

                    </div>

                @endif

            </div>


            @if ($wedding)

                {{-- Wedding Statistics --}}

                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    {{-- Headcount --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Wedding Headcount
                                </p>

                                <p class="mt-2 text-2xl font-bold text-slate-900">
                                    {{ $weddingEstimatedHeadcount }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $weddingAttendingGuests }} attending
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                👥
                            </div>

                        </div>

                    </div>


                    {{-- Checklist --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Checklist
                                </p>

                                <p class="mt-2 text-2xl font-bold text-emerald-600">
                                    {{ number_format($weddingChecklistPercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $weddingCompletedTasks }} / {{ $weddingTotalTasks }} completed
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                                ✅
                            </div>

                        </div>

                    </div>


                    {{-- Vendors --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Vendor Outstanding
                                </p>

                                <p class="mt-2 text-2xl font-bold text-amber-600">
                                    {{ $formatter->money($weddingVendorOutstanding) }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $weddingTotalVendors }} vendor(s)
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                🧑‍💼
                            </div>

                        </div>

                    </div>


                    {{-- Countdown --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Wedding Countdown
                                </p>


                                @if ($weddingDaysRemaining === null)

                                    <p class="mt-2 text-xl font-bold text-slate-400">
                                        No date set
                                    </p>

                                @elseif ($weddingDaysRemaining > 0)

                                    <p class="mt-2 text-2xl font-bold text-rose-600">
                                        {{ number_format($weddingDaysRemaining) }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        days to go
                                    </p>

                                @elseif ($weddingDaysRemaining === 0)

                                    <p class="mt-2 text-xl font-bold text-rose-600">
                                        Today!
                                    </p>

                                @else

                                    <p class="mt-2 text-xl font-bold text-slate-400">
                                        Passed
                                    </p>

                                @endif

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-xl">
                                💍
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Upcoming Wedding Activity --}}

                <div class="mt-6 grid gap-6 lg:grid-cols-3">


                    {{-- Timeline --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                        <h2 class="text-base font-bold text-slate-900">
                            Next Timeline Event
                        </h2>

                        @if ($weddingNextTimelineItem)

                            <div class="mt-5">

                                <p class="font-semibold text-slate-900">
                                    {{ $weddingNextTimelineItem->title }}
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $formatter->date($weddingNextTimelineItem->event_date) }}
                                </p>

                                @if ($weddingNextTimelineItem->start_time)

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($weddingNextTimelineItem->start_time)->format('g:i A') }}
                                    </p>

                                @endif

                            </div>

                        @else

                            <p class="mt-5 text-sm text-slate-400">
                                No upcoming timeline events.
                            </p>

                        @endif

                        <a
                            href="{{ route('wedding.timeline') }}"
                            class="mt-5 inline-block text-sm font-semibold text-indigo-600 hover:underline"
                        >
                            View Timeline →
                        </a>

                    </div>


                    {{-- Checklist --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                        <h2 class="text-base font-bold text-slate-900">
                            Next Checklist Task
                        </h2>

                        @if ($weddingNextTask)

                            <div class="mt-5">

                                <p class="font-semibold text-slate-900">
                                    {{ $weddingNextTask->task_name }}
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    Due {{ $formatter->date($weddingNextTask->due_date) }}
                                </p>

                                <p class="mt-2 text-xs font-semibold uppercase text-slate-400">
                                    {{ $weddingNextTask->priority }} priority
                                </p>

                            </div>

                        @else

                            <p class="mt-5 text-sm text-slate-400">
                                No upcoming checklist tasks.
                            </p>

                        @endif

                        <a
                            href="{{ route('wedding.checklist') }}"
                            class="mt-5 inline-block text-sm font-semibold text-indigo-600 hover:underline"
                        >
                            View Checklist →
                        </a>

                    </div>


                    {{-- Vendor --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                        <h2 class="text-base font-bold text-slate-900">
                            Next Vendor Service
                        </h2>

                        @if ($weddingNextVendor)

                            <div class="mt-5">

                                <p class="font-semibold text-slate-900">
                                    {{ $weddingNextVendor->vendor_name }}
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $weddingNextVendor->service_type }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $formatter->date($weddingNextVendor->service_date) }}
                                </p>

                            </div>

                        @else

                            <p class="mt-5 text-sm text-slate-400">
                                No upcoming vendor services.
                            </p>

                        @endif

                        <a
                            href="{{ route('wedding.vendors') }}"
                            class="mt-5 inline-block text-sm font-semibold text-indigo-600 hover:underline"
                        >
                            View Vendors →
                        </a>

                    </div>

                </div>

            @endif

        </div>

    @endif


    {{-- =============================================================== --}}
    {{-- CHART.JS --}}
    {{-- =============================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Loan Balance Chart
            |--------------------------------------------------------------------------
            */

            const loanBalanceElement =
                document.getElementById('loanBalanceChart');


            if (loanBalanceElement) {

                new Chart(
                    loanBalanceElement,
                    {
                        type: 'bar',

                        data: {
                            labels: @json($loanChartLabels),

                            datasets: [
                                {
                                    label: 'Remaining Balance',

                                    data: @json($loanChartBalances),

                                    borderWidth: 1,

                                    borderRadius: 6
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
                                        label: function (context) {
                                            return '{{ $formatter->symbol() }}' +
                                                Number(context.raw).toLocaleString(
                                                    'en-US',
                                                    {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    }
                                                );
                                        }
                                    }
                                }
                            },

                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Loan Status Chart
            |--------------------------------------------------------------------------
            */

            const loanStatusElement =
                document.getElementById('loanStatusChart');


            if (loanStatusElement) {

                new Chart(
                    loanStatusElement,
                    {
                        type: 'doughnut',

                        data: {
                            labels: [
                                'Active',
                                'Completed',
                                'Overdue'
                            ],

                            datasets: [
                                {
                                    data: [
                                        {{ $activeLoans }},
                                        {{ $completedLoans }},
                                        {{ $overdueLoans }}
                                    ],

                                    borderWidth: 2
                                }
                            ]
                        },

                        options: {
                            responsive: true,

                            maintainAspectRatio: false,

                            cutout: '70%',

                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Monthly Financial Summary
            |--------------------------------------------------------------------------
            */

            const financialElement =
                document.getElementById('financialSummaryChart');


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
                                'Remaining'
                            ],

                            datasets: [
                                {
                                    label: 'Amount',

                                    data: [
                                        {{ $financialSummary['income'] }},
                                        {{ $financialSummary['loanPayments'] }},
                                        {{ $financialSummary['expenses'] }},
                                        {{ $financialSummary['remaining'] }}
                                    ],

                                    borderWidth: 1,

                                    borderRadius: 6
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
                                        label: function (context) {
                                            return '{{ $formatter->symbol() }}' +
                                                Number(context.raw).toLocaleString(
                                                    'en-US',
                                                    {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    }
                                                );
                                        }
                                    }
                                }
                            },

                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    }
                );

            }

        });
    </script>

</x-app-layout>