<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-indigo-600">
                    Finance
                </p>

                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                    Debt-Free Planner
                </h2>

                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    A simple plan to organize your debts, prioritize payments, and work toward becoming debt-free.
                </p>

            </div>


            <a
                href="{{ route('loans.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                View Loans
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- ===================================================== --}}
            {{-- EMPTY STATE --}}
            {{-- ===================================================== --}}

            @if ($loans->isEmpty())

                <div class="rounded-3xl bg-white px-6 py-16 text-center shadow-sm ring-1 ring-gray-200 sm:px-10">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-3xl">
                        🎉
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-gray-900">
                        You're debt-free!
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        You currently have no active or overdue loans that need a repayment plan.
                    </p>

                    <a
                        href="{{ route('loans.index') }}"
                        class="mt-6 inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Go to Loans
                    </a>

                </div>

            @else


                {{-- ================================================= --}}
                {{-- DEBT OVERVIEW --}}
                {{-- ================================================= --}}

                <div class="mb-8 overflow-hidden rounded-3xl bg-gray-900 text-white shadow-sm">

                    <div class="p-6 sm:p-8">

                        <div class="grid gap-8 lg:grid-cols-[1.3fr_1fr] lg:items-center">


                            {{-- MAIN DEBT --}}

                            <div>

                                <p class="text-sm font-medium text-indigo-300">
                                    Total Outstanding Debt
                                </p>

                                <h1 class="mt-2 text-4xl font-bold tracking-tight sm:text-5xl">
                                    {{ $formatter->money($totalDebt) }}
                                </h1>

                                <p class="mt-2 text-sm text-gray-400">
                                    across {{ $loans->count() }}
                                    {{ $loans->count() === 1 ? 'active loan' : 'active loans' }}
                                </p>


                                {{-- PROGRESS --}}

                                <div class="mt-8 max-w-xl">

                                    <div class="flex items-center justify-between text-xs">

                                        <span class="text-gray-400">
                                            Overall progress
                                        </span>

                                        <span class="font-semibold text-white">
                                            {{ number_format($debtFreeProgress, 1) }}%
                                        </span>

                                    </div>


                                    <div class="mt-2 h-3 overflow-hidden rounded-full bg-white/10">

                                        <div
                                            class="h-full rounded-full bg-indigo-400 transition-all duration-700"
                                            style="width: {{ min(100, max(0, $debtFreeProgress)) }}%"
                                        ></div>

                                    </div>

                                </div>

                            </div>


                            {{-- SNAPSHOT --}}

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-2">

                                <div class="rounded-2xl bg-white/10 p-4">

                                    <p class="text-xs text-gray-400">
                                        Monthly Minimum
                                    </p>

                                    <p class="mt-2 text-lg font-bold">
                                        {{ $formatter->money($totalMinimumPayments) }}
                                    </p>

                                </div>


                                <div class="rounded-2xl bg-white/10 p-4">

                                    <p class="text-xs text-gray-400">
                                        Available Funds
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-emerald-300">
                                        {{ $formatter->money($availableFunds) }}
                                    </p>

                                </div>


                                <div class="rounded-2xl bg-white/10 p-4">

                                    <p class="text-xs text-gray-400">
                                        Debt Load
                                    </p>

                                    <p class="mt-2 text-lg font-bold">
                                        {{ number_format($debtToAvailableFunds, 1) }}%
                                    </p>

                                </div>


                                <div class="rounded-2xl bg-white/10 p-4 sm:col-span-2 lg:col-span-1">

                                    <p class="text-xs text-gray-400">
                                        Total Paid
                                    </p>

                                    <p class="mt-2 text-lg font-bold">
                                        {{ $formatter->money($totalPaid) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PAYOFF SIMULATOR --}}
                {{-- ================================================= --}}

                <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">

                    <div class="flex flex-col gap-2">

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Step 1
                        </p>

                        <h2 class="text-xl font-bold text-gray-900">
                            Build Your Payoff Plan
                        </h2>

                        <p class="text-sm text-gray-500">
                            Choose a strategy and see how your repayment timeline changes.
                        </p>

                    </div>


                    <form
                        method="GET"
                        action="{{ route('debt-free.index') }}"
                        class="mt-6"
                    >

                        <div class="grid gap-5 lg:grid-cols-3 lg:items-start">


                            {{-- STRATEGY --}}

                            <div>

                                <label
                                    for="strategy"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Payoff Strategy
                                </label>

                                <select
                                    name="strategy"
                                    id="strategy"
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option
                                        value="avalanche"
                                        @selected($strategy === 'avalanche')
                                    >
                                        Debt Avalanche
                                    </option>

                                    <option
                                        value="snowball"
                                        @selected($strategy === 'snowball')
                                    >
                                        Debt Snowball
                                    </option>

                                </select>


                                <p class="mt-2 min-h-[40px] text-xs leading-5 text-gray-400">

                                    @if ($strategy === 'snowball')

                                        Smallest balance first for quicker visible wins.

                                    @else

                                        Highest interest first to prioritize expensive debt.

                                    @endif

                                </p>

                            </div>


                            {{-- EXTRA PAYMENT --}}

                            <div>

                                <label
                                    for="extra_payment"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Extra Monthly Payment
                                </label>

                                <div class="relative mt-2">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                        {{ $formatter->symbol() }}
                                    </span>

                                    <input
                                        type="number"
                                        name="extra_payment"
                                        id="extra_payment"
                                        value="{{ $extraPayment > 0 ? number_format($extraPayment, 2, '.', '') : '' }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                                <p class="mt-2 min-h-[40px] text-xs leading-5 text-gray-400">
                                    Additional amount you can dedicate to debt every month.
                                </p>

                            </div>


                            {{-- ACTION --}}

                            <div class="flex items-end">

                                <button
                                    type="submit"
                                    class="flex min-h-[50px] mt-7 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />

                                    </svg>

                                    Update Projection

                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                {{-- ================================================= --}}
                {{-- MAIN RECOMMENDATION --}}
                {{-- ================================================= --}}

                @if ($targetLoan)

                    <div class="mb-8 grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">


                        {{-- TARGET LOAN --}}

                        <div class="rounded-3xl border border-indigo-100 bg-indigo-50/70 p-6 sm:p-8">

                            <div class="flex items-start gap-4">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-xl">
                                    🎯
                                </div>

                                <div>

                                    <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                        Your Next Target
                                    </p>

                                    <h2 class="mt-1 text-2xl font-bold text-gray-900">
                                        {{ $targetLoan->loan_name }}
                                    </h2>

                                    @if ($targetLoan->lender)

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $targetLoan->lender }}
                                        </p>

                                    @endif

                                </div>

                            </div>


                            <div class="mt-7 grid gap-4 sm:grid-cols-3">

                                <div class="rounded-2xl bg-white p-4">

                                    <p class="text-xs text-gray-400">
                                        Current Balance
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-gray-900">
                                        {{ $formatter->money($targetLoan->remaining_balance) }}
                                    </p>

                                </div>


                                <div class="rounded-2xl bg-white p-4">

                                    <p class="text-xs text-gray-400">
                                        Interest Rate
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-gray-900">
                                        {{ number_format((float) $targetLoan->interest_rate, 2) }}%
                                    </p>

                                </div>


                                <div class="rounded-2xl bg-white p-4">

                                    <p class="text-xs text-gray-400">
                                        Minimum Payment
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-gray-900">
                                        {{ $formatter->money($targetLoan->monthly_payment) }}
                                    </p>

                                </div>

                            </div>


                            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">

                                <a
                                    href="{{ route('loan-payments.create', [
                                        'loan' => $targetLoan,
                                        'strategy' => $strategy,
                                        'extra_payment' => $extraPayment,
                                        'rollover' => $targetRolloverAmount,
                                    ]) }}"
                                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>

                                    Pay Target Loan
                                </a>


                                <span class="text-xs text-gray-500">

                                    @if ($strategy === 'snowball')

                                        Target chosen because it has the smallest remaining balance.

                                    @else

                                        Target chosen because it has the highest interest rate.

                                    @endif

                                </span>

                            </div>

                        </div>


                        {{-- SIMULATION SUMMARY --}}

                        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">

                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                Projection
                            </p>

                            <h3 class="mt-2 text-xl font-bold text-gray-900">
                                Debt-Free Goal
                            </h3>


                            <div class="mt-6">

                                <p class="text-4xl font-bold tracking-tight text-gray-900">

                                    {{ $simulation['months'] }}

                                    <span class="text-lg font-medium text-gray-400">
                                        {{ $simulation['months'] === 1 ? 'month' : 'months' }}
                                    </span>

                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    estimated payoff time
                                </p>

                            </div>


                            <div class="mt-6 border-t border-gray-100 pt-5">

                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-gray-500">
                                        Estimated payoff
                                    </span>

                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $simulation['payoff_date_formatted'] }}
                                    </span>

                                </div>


                                <div class="mt-3 flex items-center justify-between">

                                    <span class="text-sm text-gray-500">
                                        Extra payment
                                    </span>

                                    <span class="text-sm font-bold text-indigo-600">
                                        {{ $formatter->money($simulation['extra_payment']) }}
                                    </span>

                                </div>


                                <div class="mt-3 flex items-center justify-between">

                                    <span class="text-sm text-gray-500">
                                        Monthly debt budget
                                    </span>

                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $formatter->money($simulation['total_payment_budget']) }}
                                    </span>

                                </div>


                                <div class="mt-3 flex items-center justify-between">

                                    <span class="text-sm text-gray-500">
                                        Total rollover released
                                    </span>

                                    <span class="text-sm font-bold text-emerald-600">
                                        {{ $formatter->money($simulation['rollover_total'] ?? 0) }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- AUTOMATIC ROLLOVER --}}
                {{-- ================================================= --}}

                @if (!empty($simulation['payoff_events']))

                    <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">

                        <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                            <div class="flex items-start gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-lg">
                                    🔄
                                </div>

                                <div>

                                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                                        Automatic Rollover
                                    </p>

                                    <h2 class="mt-1 text-xl font-bold text-gray-900">
                                        Your Debt Power Grows
                                    </h2>

                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                        When one loan is fully paid, its monthly payment is automatically
                                        redirected to the next debt target in the simulation.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="divide-y divide-gray-100">

                            @foreach ($simulation['payoff_events'] as $event)

                                <div class="px-6 py-5 sm:px-8">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">


                                        {{-- Event --}}

                                        <div class="flex items-start gap-4">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                                ✓
                                            </div>

                                            <div>

                                                <p class="font-semibold text-gray-900">
                                                    {{ $event['loan_name'] }} paid off
                                                </p>

                                                <p class="mt-1 text-xs text-gray-400">
                                                    Month {{ $event['month'] }}
                                                    ·
                                                    {{ $event['date']->format('F Y') }}
                                                </p>

                                            </div>

                                        </div>


                                        {{-- Released Payment --}}

                                        <div class="rounded-xl bg-emerald-50 px-4 py-3 sm:min-w-[220px]">

                                            <p class="text-xs text-emerald-600">
                                                Payment Released
                                            </p>

                                            <p class="mt-1 text-lg font-bold text-emerald-700">

                                                {{ $formatter->money($event['freed_payment']) }}

                                                <span class="text-xs font-medium">
                                                    / month
                                                </span>

                                            </p>

                                        </div>

                                    </div>


                                    {{-- Message --}}

                                    <div class="mt-4 rounded-xl bg-gray-50 p-4">

                                        <p class="text-sm leading-6 text-gray-600">
                                            {{ $event['message'] }}
                                        </p>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @else

                    <div class="mb-8 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

                        <div class="flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-50 text-lg">
                                🔄
                            </div>

                            <div>

                                <h3 class="font-bold text-gray-900">
                                    Automatic Rollover
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    No loan has been fully paid in the current simulation yet.
                                    Once a loan reaches ₱0, its monthly payment will become available
                                    for the next target.
                                </p>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- PAYOFF ORDER --}}
                {{-- ================================================= --}}

                <div class="mb-8 rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">

                    <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Step 2
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-gray-900">
                            Payoff Order
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            The order your debts are prioritized under your selected strategy.
                        </p>

                    </div>


                    <div class="p-6 sm:p-8">

                        <div class="space-y-4">

                            @forelse ($simulation['order'] as $index => $loanId)

                                @php
                                    $orderedLoan = $loans->firstWhere('id', $loanId);
                                @endphp

                                @if ($orderedLoan)

                                    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 p-4 transition hover:border-gray-200 hover:bg-gray-50">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-sm font-bold text-gray-700">
                                            {{ $index + 1 }}
                                        </div>


                                        <div class="min-w-0 flex-1">

                                            <div class="flex flex-wrap items-center gap-2">

                                                <p class="font-semibold text-gray-900">
                                                    {{ $orderedLoan->loan_name }}
                                                </p>

                                                @if ($index === 0)

                                                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                                        Attack First
                                                    </span>

                                                @endif

                                            </div>


                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ number_format((float) $orderedLoan->interest_rate, 2) }}% interest
                                                ·
                                                {{ $formatter->money($orderedLoan->monthly_payment) }} minimum
                                            </p>

                                        </div>


                                        <div class="text-right">

                                            <p class="text-xs text-gray-400">
                                                Balance
                                            </p>

                                            <p class="mt-1 font-bold text-gray-900">
                                                {{ $formatter->money($orderedLoan->remaining_balance) }}
                                            </p>

                                        </div>

                                    </div>

                                @endif

                            @empty

                                <p class="text-sm text-gray-500">
                                    No payoff order is available.
                                </p>

                            @endforelse

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MONTHLY PROJECTION --}}
                {{-- ================================================= --}}

                <div class="mb-8 rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">

                    <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                    Step 3
                                </p>

                                <h2 class="mt-1 text-xl font-bold text-gray-900">
                                    Monthly Projection
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    See how your total debt is expected to decrease.
                                </p>

                            </div>


                            <span class="inline-flex w-fit rounded-full bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-600">
                                {{ $simulation['strategy_label'] }}
                            </span>

                        </div>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        Month
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        Payment
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        Remaining Debt
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        Current Target
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        Rollover
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($simulation['monthly_schedule'] as $month)

                                    <tr class="transition hover:bg-gray-50">


                                        {{-- Month --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            <p class="font-semibold text-gray-900">
                                                Month {{ $month['month'] }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $month['date']->format('M Y') }}
                                            </p>

                                        </td>


                                        {{-- Payment --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            <span class="font-semibold text-emerald-600">
                                                {{ $formatter->money($month['total_payment']) }}
                                            </span>

                                        </td>


                                        {{-- Remaining --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            <span class="font-semibold text-gray-900">
                                                {{ $formatter->money($month['remaining_debt']) }}
                                            </span>

                                        </td>


                                        {{-- Target --}}

                                        <td class="px-6 py-4">

                                            <span class="inline-flex max-w-[180px] truncate rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                {{ $month['target_loan'] ?: 'Debt-free' }}
                                            </span>

                                        </td>


                                        {{-- Rollover --}}

                                        <td class="px-6 py-4">

                                            @if (!empty($month['rollover']))

                                                <div class="space-y-1">

                                                    @foreach ($month['rollover'] as $rollover)

                                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                            + {{ $formatter->money($rollover['freed_payment']) }}
                                                        </span>

                                                    @endforeach

                                                </div>

                                            @else

                                                <span class="text-xs text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STRATEGY GUIDE --}}
                {{-- ================================================= --}}

                <div class="grid gap-5 lg:grid-cols-2">


                    {{-- SNOWBALL --}}

                    <div
                        class="rounded-2xl border p-6
                        @if ($strategy === 'snowball')
                            border-indigo-200 bg-indigo-50/40
                        @else
                            border-gray-200 bg-white
                        @endif"
                    >

                        <div class="flex gap-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50">
                                ⛄
                            </div>

                            <div class="flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-bold text-gray-900">
                                        Debt Snowball
                                    </h3>

                                    @if ($strategy === 'snowball')

                                        <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                            Selected
                                        </span>

                                    @endif

                                </div>


                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Start with the smallest remaining balance. When that loan reaches zero,
                                    its payment is rolled into the next target.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- AVALANCHE --}}

                    <div
                        class="rounded-2xl border p-6
                        @if ($strategy === 'avalanche')
                            border-amber-200 bg-amber-50/40
                        @else
                            border-gray-200 bg-white
                        @endif"
                    >

                        <div class="flex gap-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                                🏔️
                            </div>

                            <div class="flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-bold text-gray-900">
                                        Debt Avalanche
                                    </h3>

                                    @if ($strategy === 'avalanche')

                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                            Selected
                                        </span>

                                    @endif

                                </div>


                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Start with the highest-interest debt. When that loan reaches zero,
                                    its payment is rolled into the next highest-interest target.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PLANNING NOTE --}}
                {{-- ================================================= --}}

                <div class="mt-6 rounded-2xl border border-gray-100 bg-gray-50 p-5">

                    <div class="flex gap-3">

                        <div class="text-sm">
                            ℹ️
                        </div>

                        <p class="text-xs leading-5 text-gray-500">

                            <strong class="text-gray-700">
                                Planning note:
                            </strong>

                            The payoff projection is an estimate based on your current recorded loan
                            balances, minimum payments, selected strategy, and extra monthly payment.
                            Automatic rollover applies to the simulation only and does not submit actual
                            payments automatically.

                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>