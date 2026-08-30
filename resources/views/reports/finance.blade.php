<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="flex items-center gap-2">

                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v12m-3-2.5h6M6.5 9h11M6 19h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>

                    </span>

                    <p class="text-sm font-semibold text-indigo-600">
                        Reports
                    </p>

                </div>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                    Finance Reports
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Understand your income, spending, payments, and debt position.
                </p>

            </div>


            <a
                href="{{ route('reports.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 18l-6-6 6-6"
                    />
                </svg>

                Reports

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- REPORT PERIOD --}}
            {{-- ========================================================= --}}

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                <div class="flex flex-col gap-1">

                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Reporting Period
                    </p>

                    <h3 class="text-lg font-bold text-slate-900">
                        Choose a date range
                    </h3>

                </div>


                <form
                    method="GET"
                    action="{{ route('reports.finance') }}"
                    class="mt-5 grid gap-4 md:grid-cols-3"
                >

                    <div>

                        <label
                            for="from"
                            class="block text-sm font-semibold text-slate-700"
                        >
                            From
                        </label>

                        <input
                            type="date"
                            name="from"
                            id="from"
                            value="{{ $from }}"
                            class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    <div>

                        <label
                            for="to"
                            class="block text-sm font-semibold text-slate-700"
                        >
                            To
                        </label>

                        <input
                            type="date"
                            name="to"
                            id="to"
                            value="{{ $to }}"
                            class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            Generate Report
                        </button>

                    </div>

                </form>

            </section>


            {{-- ========================================================= --}}
            {{-- FINANCIAL SNAPSHOT --}}
            {{-- ========================================================= --}}

            <section class="mt-8">

                <div class="mb-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">
                        Financial Snapshot
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                        Money overview
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Summary for {{ $from }} to {{ $to }}.
                    </p>

                </div>


                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    {{-- Income --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Total Income
                            </p>

                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        </div>

                        <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                            {{ $formatter->money($totalIncome) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-500">
                            Salary + additional income
                        </p>

                    </div>


                    {{-- Loan Payments --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Loan Payments
                            </p>

                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>

                        </div>

                        <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                            {{ $formatter->money($loanPayments) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-500">
                            {{ number_format($loanPaymentPercentage, 1) }}% of income
                        </p>

                    </div>


                    {{-- Expenses --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Expenses
                            </p>

                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                        </div>

                        <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                            {{ $formatter->money($expenses) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-500">
                            Recorded spending
                        </p>

                    </div>


                    {{-- Remaining --}}

                    <div class="rounded-2xl bg-slate-900 p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Remaining
                            </p>

                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                        </div>

                        <p class="mt-3 text-2xl font-bold tracking-tight text-white">
                            {{ $formatter->money($financialRemaining) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            After payments and expenses
                        </p>

                    </div>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- INCOME & EXPENSE BREAKDOWN --}}
            {{-- ========================================================= --}}

            <section class="mt-10">

                <div class="mb-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                        Activity
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                        Income & expense breakdown
                    </h3>

                </div>


                <div class="grid gap-6 lg:grid-cols-2">


                    {{-- ================================================= --}}
                    {{-- INCOME --}}
                    {{-- ================================================= --}}

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <h4 class="font-bold text-slate-900">
                                    Income Sources
                                </h4>

                                <p class="mt-1 text-sm text-slate-500">
                                    Where your money came from.
                                </p>

                            </div>

                            <span class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                                {{ $formatter->money($totalIncome) }}
                            </span>

                        </div>


                        <div class="mt-7 space-y-6">


                            {{-- Salary --}}

                            <div>

                                <div class="flex items-center justify-between gap-4">

                                    <span class="text-sm font-medium text-slate-600">
                                        Salary
                                    </span>

                                    <span class="text-sm font-bold text-slate-900">
                                        {{ $formatter->money($salaryIncome) }}
                                    </span>

                                </div>


                                @php

                                    $salaryPercentage =
                                        $totalIncome > 0
                                            ? ($salaryIncome / $totalIncome) * 100
                                            : 0;

                                @endphp


                                <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                    <div
                                        class="h-full rounded-full bg-emerald-500"
                                        style="width: {{ min(100, max(0, $salaryPercentage)) }}%"
                                    ></div>

                                </div>

                            </div>


                            {{-- Additional Income --}}

                            @forelse ($incomeTypeBreakdown as $income)

                                <div>

                                    <div class="flex items-center justify-between gap-4">

                                        <span class="text-sm font-medium text-slate-600">
                                            {{ $income->income_type ?: 'Other Income' }}
                                        </span>

                                        <span class="text-sm font-bold text-slate-900">
                                            {{ $formatter->money($income->total) }}
                                        </span>

                                    </div>


                                    @php

                                        $incomePercentage =
                                            $totalIncome > 0
                                                ? ($income->total / $totalIncome) * 100
                                                : 0;

                                    @endphp


                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                        <div
                                            class="h-full rounded-full bg-indigo-500"
                                            style="width: {{ min(100, max(0, $incomePercentage)) }}%"
                                        ></div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5">

                                    <p class="text-sm text-slate-400">
                                        No additional income recorded for this period.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EXPENSES --}}
                    {{-- ================================================= --}}

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <h4 class="font-bold text-slate-900">
                                    Expense Categories
                                </h4>

                                <p class="mt-1 text-sm text-slate-500">
                                    Where your money was spent.
                                </p>

                            </div>

                            <span class="rounded-xl bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700">
                                {{ $formatter->money($expenses) }}
                            </span>

                        </div>


                        <div class="mt-7 space-y-6">

                            @forelse ($expenseCategoryBreakdown as $expense)

                                @php

                                    $expensePercentage =
                                        $expenses > 0
                                            ? ($expense->total / $expenses) * 100
                                            : 0;

                                @endphp


                                <div>

                                    <div class="flex items-center justify-between gap-4">

                                        <span class="text-sm font-medium text-slate-600">
                                            {{ $expense->category ?: 'Other' }}
                                        </span>

                                        <span class="text-sm font-bold text-slate-900">
                                            {{ $formatter->money($expense->total) }}
                                        </span>

                                    </div>


                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                        <div
                                            class="h-full rounded-full bg-amber-500"
                                            style="width: {{ min(100, max(0, $expensePercentage)) }}%"
                                        ></div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5">

                                    <p class="text-sm text-slate-400">
                                        No expenses recorded for this period.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- DEBT POSITION --}}
            {{-- ========================================================= --}}

            <section class="mt-10">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wider text-rose-600">
                            Debt
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Loan position
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Your overall loan position across all recorded loans.
                        </p>

                    </div>


                    <div class="rounded-full bg-indigo-50 px-4 py-2">

                        <span class="text-xs font-bold text-indigo-700">
                            Debt-to-Income {{ number_format($debtToIncomeRatio, 1) }}%
                        </span>

                    </div>

                </div>


                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">


                    {{-- Payable --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Total Payable
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $formatter->money($totalLoanPayable) }}
                        </p>

                    </div>


                    {{-- Paid --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Total Paid
                        </p>

                        <p class="mt-2 text-xl font-bold text-emerald-600">
                            {{ $formatter->money($totalLoanPaid) }}
                        </p>

                    </div>


                    {{-- Outstanding --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Outstanding
                        </p>

                        <p class="mt-2 text-xl font-bold text-rose-600">
                            {{ $formatter->money($totalLoanOutstanding) }}
                        </p>

                    </div>


                    {{-- Active --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Active Loans
                        </p>

                        <p class="mt-2 text-xl font-bold text-indigo-600">
                            {{ $activeLoans }}
                        </p>

                    </div>


                    {{-- Overdue --}}

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Overdue
                        </p>

                        <p class="mt-2 text-xl font-bold text-rose-600">
                            {{ $overdueLoans }}
                        </p>

                    </div>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- PAYMENT METHODS --}}
            {{-- ========================================================= --}}

            <section class="mt-10">

                <div class="mb-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Loan Activity
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                        Payment methods
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        How your loan payments were made during this period.
                    </p>

                </div>


                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        @forelse ($paymentMethodBreakdown as $payment)

                            <div class="rounded-2xl bg-slate-50 p-5">

                                <div class="flex items-center justify-between gap-4">

                                    <div>

                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $payment->payment_method ?: 'Not specified' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Loan payment
                                        </p>

                                    </div>

                                    <p class="text-sm font-bold text-slate-900">
                                        {{ $formatter->money($payment->total) }}
                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-7 text-center">

                                <p class="text-sm text-slate-400">
                                    No loan payments recorded for this period.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- FOOTER --}}
            {{-- ========================================================= --}}

            <div class="mt-10 border-t border-slate-200 pt-6">

                <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">

                    <p class="text-xs text-slate-400">
                        Finance report generated from your recorded data.
                    </p>

                    <a
                        href="{{ route('reports.index') }}"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-700"
                    >
                        Back to Reports
                    </a>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>