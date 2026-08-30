<x-app-layout>

    {{-- =============================================================== --}}
    {{-- PAGE WRAPPER --}}
    {{-- =============================================================== --}}

    <div
        x-data="{ showLoanModal: false }"
        @keydown.escape.window="showLoanModal = false"
    >

        {{-- =========================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- =========================================================== --}}

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Finance
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    My Loans
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage your loans, payments, repayment strategies, and remaining balances.
                </p>

            </div>


            {{-- ======================================================= --}}
            {{-- ADD LOAN BUTTON --}}
            {{-- ======================================================= --}}

            <button
                type="button"
                @click="showLoanModal = true"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >

                <svg
                    class="h-5 w-5"
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

                Add New Loan

            </button>

        </div>


        {{-- =========================================================== --}}
        {{-- VALIDATION ERRORS --}}
        {{-- =========================================================== --}}

        @if ($errors->any())

            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">

                <div class="flex gap-3">

                    <div class="shrink-0 text-rose-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a1 1 0 001.75 1.02l7.82-14a1 1 0 001.75 1.02zM13.71 3.86l7.82 14a1 1 0 011.75 1.02l-7.82-14a1 1 0 01-1.75-1.02z"
                            />

                        </svg>

                    </div>


                    <div>

                        <p class="font-semibold text-rose-800">
                            Please fix the following errors:
                        </p>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================== --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- =========================================================== --}}

        @if (session('success'))

            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                <p class="text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        {{-- =========================================================== --}}
        {{-- SUMMARY CARDS --}}
        {{-- =========================================================== --}}

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Total Loans --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Total Loans
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $loans->count() }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        💳
                    </div>

                </div>

            </div>


            {{-- Active Loans --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Active Loans
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $loans->where('status', 'active')->count() }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                        ✓
                    </div>

                </div>

            </div>


            {{-- Outstanding Balance --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Outstanding Balance
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $formatter->money($loans->sum('remaining_balance')) }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl">
                        💰
                    </div>

                </div>

            </div>


            {{-- Monthly Payments --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Monthly Payments
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">

                            {{ $formatter->money(
                                $loans
                                    ->where('status', 'active')
                                    ->sum('monthly_payment')
                            ) }}

                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-xl">
                        📅
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================== --}}
        {{-- LOAN TABLE --}}
        {{-- =========================================================== --}}

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


            {{-- Table Header --}}

            <div class="border-b border-slate-200 px-6 py-5">

                <div>

                    <h2 class="text-base font-bold text-slate-900">
                        Loan Accounts
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Your complete loan list.
                    </p>

                </div>

            </div>


            @if ($loans->count() > 0)


                {{-- =================================================== --}}
                {{-- DESKTOP TABLE --}}
                {{-- =================================================== --}}

                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Loan
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Principal
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Monthly
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Remaining
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Progress
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100 bg-white">

                            @foreach ($loans as $loan)

                                <tr class="transition hover:bg-slate-50">


                                    {{-- Loan --}}

                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                {{ $loan->loan_name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $loan->lender ?: 'No lender specified' }}
                                            </p>

                                            @if ($loan->repayment_strategy)

                                                <p class="mt-1 text-xs text-indigo-600">
                                                    {{ $loan->repayment_strategy_label }}
                                                </p>

                                            @endif

                                        </div>

                                    </td>


                                    {{-- Principal --}}

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">

                                        {{ $formatter->money($loan->principal_amount) }}

                                    </td>


                                    {{-- Monthly --}}

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">

                                        {{ $formatter->money($loan->monthly_payment ?? 0) }}

                                    </td>


                                    {{-- Remaining --}}

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900">

                                        {{ $formatter->money($loan->remaining_balance) }}

                                    </td>


                                    {{-- Progress --}}

                                    <td class="min-w-[160px] px-6 py-4">

                                        <div class="flex items-center justify-between">

                                            <span class="text-xs font-medium text-slate-500">
                                                {{ number_format($loan->payment_progress, 0) }}%
                                            </span>

                                        </div>

                                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                            <div
                                                class="h-full rounded-full bg-slate-900 transition-all duration-500"
                                                style="width: {{ min(100, max(0, $loan->payment_progress)) }}%"
                                            ></div>

                                        </div>

                                    </td>


                                    {{-- Status --}}

                                    <td class="whitespace-nowrap px-6 py-4">

                                        @php

                                            $statusClasses = [
                                                'active' =>
                                                    'bg-emerald-50 text-emerald-700 ring-emerald-600/20',

                                                'completed' =>
                                                    'bg-sky-50 text-sky-700 ring-sky-600/20',

                                                'overdue' =>
                                                    'bg-rose-50 text-rose-700 ring-rose-600/20',

                                                'upcoming' =>
                                                    'bg-amber-50 text-amber-700 ring-amber-600/20',

                                                'cancelled' =>
                                                    'bg-slate-100 text-slate-600 ring-slate-500/20',
                                            ];

                                        @endphp


                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset {{ $statusClasses[$loan->status] ?? 'bg-slate-100 text-slate-600' }}"
                                        >

                                            {{ $loan->status }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-4 whitespace-nowrap">


                                            {{-- View --}}

                                            <a
                                                href="{{ route('loans.show', $loan) }}"
                                                class="inline-flex items-center text-sm font-semibold text-slate-900 transition hover:text-slate-600"
                                            >
                                                View
                                            </a>


                                            {{-- Edit --}}

                                            <a
                                                href="{{ route('loans.edit', $loan) }}"
                                                class="inline-flex items-center text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                Edit
                                            </a>


                                            {{-- Delete --}}

                                            <form
                                                method="POST"
                                                action="{{ route('loans.destroy', $loan) }}"
                                                class="m-0 inline-flex"
                                                onsubmit="return confirm('Are you sure you want to delete this loan? All payment records associated with this loan will also be deleted.');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center text-sm font-semibold text-rose-600 transition hover:text-rose-800"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================== --}}
                {{-- MOBILE CARDS --}}
                {{-- =================================================== --}}

                <div class="divide-y divide-slate-100 md:hidden">

                    @foreach ($loans as $loan)

                        <div class="p-5 transition hover:bg-slate-50">


                            {{-- Loan Header --}}

                            <div class="flex items-start justify-between gap-4">

                                <a
                                    href="{{ route('loans.show', $loan) }}"
                                    class="min-w-0 flex-1"
                                >

                                    <p class="font-semibold text-slate-900">
                                        {{ $loan->loan_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $loan->lender ?: 'No lender specified' }}
                                    </p>

                                </a>


                                {{-- Status --}}

                                <span
                                    class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold capitalize
                                    @if ($loan->status === 'active')
                                        bg-emerald-50 text-emerald-700
                                    @elseif ($loan->status === 'completed')
                                        bg-sky-50 text-sky-700
                                    @elseif ($loan->status === 'overdue')
                                        bg-rose-50 text-rose-700
                                    @elseif ($loan->status === 'upcoming')
                                        bg-amber-50 text-amber-700
                                    @else
                                        bg-slate-100 text-slate-600
                                    @endif"
                                >
                                    {{ $loan->status }}
                                </span>

                            </div>


                            {{-- Strategy --}}

                            @if ($loan->repayment_strategy)

                                <div class="mt-3">

                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $loan->repayment_strategy_label }}
                                    </span>

                                </div>

                            @endif


                            {{-- Financial Information --}}

                            <a
                                href="{{ route('loans.show', $loan) }}"
                                class="block"
                            >

                                <div class="mt-5 grid grid-cols-2 gap-4">

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Principal
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $formatter->money($loan->principal_amount) }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Remaining
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $formatter->money($loan->remaining_balance) }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Progress --}}

                                <div class="mt-4">

                                    <div class="flex justify-between text-xs">

                                        <span class="text-slate-400">
                                            Payment Progress
                                        </span>

                                        <span class="font-semibold text-slate-600">
                                            {{ number_format($loan->payment_progress, 0) }}%
                                        </span>

                                    </div>


                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                        <div
                                            class="h-full rounded-full bg-slate-900 transition-all duration-500"
                                            style="width: {{ min(100, max(0, $loan->payment_progress)) }}%"
                                        ></div>

                                    </div>

                                </div>

                            </a>


                            {{-- Mobile Actions --}}

                            <div class="mt-5 flex items-center gap-5 border-t border-slate-100 pt-4">


                                {{-- View --}}

                                <a
                                    href="{{ route('loans.show', $loan) }}"
                                    class="inline-flex items-center text-sm font-semibold text-slate-900"
                                >
                                    View
                                </a>


                                {{-- Edit --}}

                                <a
                                    href="{{ route('loans.edit', $loan) }}"
                                    class="inline-flex items-center text-sm font-semibold text-indigo-600"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}

                                <form
                                    method="POST"
                                    action="{{ route('loans.destroy', $loan) }}"
                                    class="m-0 inline-flex"
                                    onsubmit="return confirm('Are you sure you want to delete this loan? All payment records associated with this loan will also be deleted.');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center text-sm font-semibold text-rose-600"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>


            @else


                {{-- =================================================== --}}
                {{-- EMPTY STATE --}}
                {{-- =================================================== --}}

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">
                        💳
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        No loans yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Start tracking your personal loans by adding your first loan account.
                    </p>

                    <button
                        type="button"
                        @click="showLoanModal = true"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >

                        <svg
                            class="h-5 w-5"
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

                        Add Your First Loan

                    </button>

                </div>

            @endif

        </div>


        {{-- =========================================================== --}}
        {{-- ADD LOAN MODAL --}}
        {{-- =========================================================== --}}

        <div
            x-show="showLoanModal"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="loan-modal-title"
            role="dialog"
            aria-modal="true"
        >

            {{-- Backdrop --}}

            <div
                x-show="showLoanModal"
                x-transition.opacity
                @click="showLoanModal = false"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
            ></div>


            {{-- Modal Container --}}

            <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">

                <div
                    x-show="showLoanModal"
                    x-transition
                    @click.stop
                    class="relative w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                >


                    {{-- ================================================= --}}
                    {{-- MODAL HEADER --}}
                    {{-- ================================================= --}}

                    <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5">

                        <div>

                            <p class="text-sm font-medium text-slate-500">
                                Finance
                            </p>

                            <h2
                                id="loan-modal-title"
                                class="mt-1 text-xl font-bold text-slate-900"
                            >
                                Add New Loan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Enter your loan details and choose how you plan to pay it off.
                            </p>

                        </div>


                        {{-- Close --}}

                        <button
                            type="button"
                            @click="showLoanModal = false"
                            class="ml-4 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />

                            </svg>

                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MODAL FORM --}}
                    {{-- ================================================= --}}

                    <form
                        method="POST"
                        action="{{ route('loans.store') }}"
                        x-data="loanCalculator()"
                    >

                        @csrf


                        {{-- Scrollable Content --}}

                        <div class="max-h-[70vh] overflow-y-auto">


                            {{-- ================================================= --}}
                            {{-- LOAN INFORMATION --}}
                            {{-- ================================================= --}}

                            <div class="p-6">

                                <div class="grid gap-5 md:grid-cols-2">


                                    {{-- Loan Name --}}

                                    <div class="md:col-span-2">

                                        <label
                                            for="modal_loan_name"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Loan Name
                                            <span class="text-rose-500">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="modal_loan_name"
                                            name="loan_name"
                                            value="{{ old('loan_name') }}"
                                            required
                                            placeholder="e.g. Personal Loan"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Lender --}}

                                    <div>

                                        <label
                                            for="modal_lender"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Lender
                                        </label>

                                        <input
                                            type="text"
                                            id="modal_lender"
                                            name="lender"
                                            value="{{ old('lender') }}"
                                            placeholder="e.g. Bank, Lending Company, Friend"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Principal --}}

                                    <div>

                                        <label
                                            for="modal_principal_amount"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Principal Amount
                                            <span class="text-rose-500">*</span>
                                        </label>

                                        <div class="relative mt-2">

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">
                                                {{ $formatter->symbol() }}
                                            </span>

                                            <input
                                                type="number"
                                                id="modal_principal_amount"
                                                name="principal_amount"
                                                x-model.number="principal"
                                                value="{{ old('principal_amount') }}"
                                                min="0.01"
                                                step="0.01"
                                                required
                                                placeholder="0.00"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </div>

                                    </div>


                                    {{-- Interest Rate --}}

                                    <div>

                                        <label
                                            for="modal_interest_rate"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Interest Rate (%)
                                        </label>

                                        <div class="relative mt-2">

                                            <input
                                                type="number"
                                                id="modal_interest_rate"
                                                name="interest_rate"
                                                x-model.number="interestRate"
                                                value="{{ old('interest_rate', 0) }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">
                                                %
                                            </span>

                                        </div>

                                    </div>


                                    {{-- Interest Type --}}

                                    <div>

                                        <label
                                            for="modal_interest_type"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Interest Type
                                        </label>

                                        <select
                                            id="modal_interest_type"
                                            name="interest_type"
                                            x-model="interestType"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                            <option value="none">
                                                No Interest
                                            </option>

                                            <option value="simple">
                                                Simple Interest
                                            </option>

                                            <option value="fixed">
                                                Fixed Interest
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Term --}}

                                    <div>

                                        <label
                                            for="modal_term_months"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Term (Months)
                                        </label>

                                        <input
                                            type="number"
                                            id="modal_term_months"
                                            name="term_months"
                                            x-model.number="termMonths"
                                            value="{{ old('term_months', 12) }}"
                                            min="1"
                                            step="1"
                                            placeholder="12"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Start Date --}}

                                    <div>

                                        <label
                                            for="modal_start_date"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Start Date
                                            <span class="text-rose-500">*</span>
                                        </label>

                                        <input
                                            type="date"
                                            id="modal_start_date"
                                            name="start_date"
                                            value="{{ old('start_date') }}"
                                            required
                                            class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Due Date --}}

                                    <div>

                                        <label
                                            for="modal_due_date"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Due Date
                                        </label>

                                        <input
                                            type="date"
                                            id="modal_due_date"
                                            name="due_date"
                                            value="{{ old('due_date') }}"
                                            class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Monthly Payment --}}

                                    <div>

                                        <label
                                            for="modal_monthly_payment"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Monthly Payment
                                        </label>

                                        <div class="relative mt-2">

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">
                                                {{ $formatter->symbol() }}
                                            </span>

                                            <input
                                                type="number"
                                                id="modal_monthly_payment"
                                                name="monthly_payment"
                                                x-model.number="monthlyPayment"
                                                :value="monthlyPaymentValue"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </div>

                                        <p class="mt-2 text-xs text-slate-400">
                                            Automatically calculated. You can adjust this amount.
                                        </p>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- REPAYMENT STRATEGY --}}
                                    {{-- ================================================= --}}

                                    <div>

                                        <label
                                            for="modal_repayment_strategy"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Repayment Strategy
                                        </label>

                                        <select
                                            id="modal_repayment_strategy"
                                            name="repayment_strategy"
                                            x-model="repaymentStrategy"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                            <option value="standard">
                                                Standard / Fixed Payment
                                            </option>

                                            <option value="extra_principal">
                                                Extra Principal Payment
                                            </option>

                                            <option value="balloon">
                                                Balloon Payment
                                            </option>

                                            <option value="snowball">
                                                Debt Snowball
                                            </option>

                                            <option value="avalanche">
                                                Debt Avalanche
                                            </option>

                                            <option value="custom">
                                                Custom
                                            </option>

                                        </select>

                                        <p class="mt-2 text-xs text-slate-400">
                                            <span x-text="strategyDescription"></span>
                                        </p>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- EXTRA PRINCIPAL --}}
                                    {{-- ================================================= --}}

                                    <div
                                        x-show="repaymentStrategy === 'extra_principal'"
                                        x-transition
                                        x-cloak
                                        class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-4"
                                    >

                                        <label
                                            for="modal_planned_extra_payment"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Planned Extra Payment
                                        </label>

                                        <div class="relative mt-2">

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">
                                                {{ $formatter->symbol() }}
                                            </span>

                                            <input
                                                type="number"
                                                id="modal_planned_extra_payment"
                                                name="planned_extra_payment"
                                                x-model.number="plannedExtraPayment"
                                                value="{{ old('planned_extra_payment', 0) }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </div>

                                        <p class="mt-2 text-xs text-slate-500">
                                            Additional amount planned on top of the required monthly payment.
                                        </p>

                                        <div class="mt-3 rounded-lg bg-white/80 px-3 py-2">

                                            <div class="flex items-center justify-between text-sm">

                                                <span class="text-slate-500">
                                                    Planned Monthly Total
                                                </span>

                                                <span class="font-bold text-emerald-700">
                                                    {{ $formatter->symbol() }}
                                                    <span x-text="formatMoney(plannedMonthlyTotal)"></span>
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- BALLOON PAYMENT --}}
                                    {{-- ================================================= --}}

                                    <div
                                        x-show="repaymentStrategy === 'balloon'"
                                        x-transition
                                        x-cloak
                                        class="rounded-xl border border-amber-100 bg-amber-50/60 p-4"
                                    >

                                        <label
                                            for="modal_balloon_payment"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Final Balloon Payment
                                        </label>

                                        <div class="relative mt-2">

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">
                                                {{ $formatter->symbol() }}
                                            </span>

                                            <input
                                                type="number"
                                                id="modal_balloon_payment"
                                                name="balloon_payment"
                                                x-model.number="balloonPayment"
                                                value="{{ old('balloon_payment', 0) }}"
                                                min="0"
                                                step="0.01"
                                                :max="Math.max(0, totalPayable - 0.01)"
                                                placeholder="0.00"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </div>

                                        <p class="mt-2 text-xs text-slate-500">
                                            A larger amount that will be due on the final installment.
                                        </p>

                                        <div class="mt-3 space-y-2 rounded-lg bg-white/80 px-3 py-3">

                                            <div class="flex items-center justify-between text-sm">

                                                <span class="text-slate-500">
                                                    Regular Payment Estimate
                                                </span>

                                                <span class="font-bold text-amber-700">
                                                    {{ $formatter->symbol() }}
                                                    <span x-text="formatMoney(balloonMonthlyEstimate)"></span>
                                                </span>

                                            </div>

                                            <div class="flex items-center justify-between text-sm">

                                                <span class="text-slate-500">
                                                    Final Balloon
                                                </span>

                                                <span class="font-bold text-slate-900">
                                                    {{ $formatter->symbol() }}
                                                    <span x-text="formatMoney(balloonPayment)"></span>
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Notes --}}

                                    <div class="md:col-span-2">

                                        <label
                                            for="modal_notes"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Notes
                                        </label>

                                        <textarea
                                            id="modal_notes"
                                            name="notes"
                                            rows="3"
                                            placeholder="Add any additional notes about this loan..."
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >{{ old('notes') }}</textarea>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- STRATEGY GUIDE --}}
                                {{-- ================================================= --}}

                                <div
                                    x-show="repaymentStrategy !== 'standard'"
                                    x-transition
                                    x-cloak
                                    class="mt-5 rounded-xl border border-indigo-100 bg-indigo-50/60 p-4"
                                >

                                    <div class="flex gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100">
                                            💡
                                        </div>

                                        <div>

                                            <p class="text-sm font-semibold text-slate-900">
                                                <span x-text="strategyLabel"></span>
                                            </p>

                                            <p
                                                class="mt-1 text-xs leading-5 text-slate-600"
                                                x-text="strategyGuide"
                                            ></p>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- MODAL SUMMARY --}}
                            {{-- ================================================= --}}

                            <div class="border-t border-slate-200 bg-slate-50 px-6 py-5">

                                <div class="mb-4">

                                    <h3 class="text-sm font-bold text-slate-900">
                                        Loan Summary
                                    </h3>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Estimated figures based on your loan information.
                                    </p>

                                </div>


                                <div class="grid gap-3 sm:grid-cols-3">


                                    {{-- Principal --}}

                                    <div class="rounded-xl bg-white p-4 ring-1 ring-slate-200">

                                        <p class="text-xs text-slate-500">
                                            Principal
                                        </p>

                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ $formatter->symbol() }}
                                            <span x-text="formatMoney(principal)"></span>
                                        </p>

                                    </div>


                                    {{-- Interest --}}

                                    <div class="rounded-xl bg-white p-4 ring-1 ring-slate-200">

                                        <p class="text-xs text-slate-500">
                                            Total Interest
                                        </p>

                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ $formatter->symbol() }}
                                            <span x-text="formatMoney(totalInterest)"></span>
                                        </p>

                                    </div>


                                    {{-- Total --}}

                                    <div class="rounded-xl bg-slate-900 p-4 text-white">

                                        <p class="text-xs text-slate-300">
                                            Total Payable
                                        </p>

                                        <p class="mt-2 text-lg font-bold">
                                            {{ $formatter->symbol() }}
                                            <span x-text="formatMoney(totalPayable)"></span>
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- MODAL ACTIONS --}}
                        {{-- ================================================= --}}

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end">

                            <button
                                type="button"
                                @click="showLoanModal = false"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Save Loan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================== --}}
    {{-- LOAN CALCULATOR --}}
    {{-- =============================================================== --}}

    <script>

        function loanCalculator() {

            return {

                principal: Number(
                    @json(old('principal_amount', 0))
                ),

                interestRate: Number(
                    @json(old('interest_rate', 0))
                ),

                interestType:
                    @json(old('interest_type', 'none')),

                termMonths: Number(
                    @json(old('term_months', 12))
                ),

                monthlyPayment: Number(
                    @json(old('monthly_payment', 0))
                ),

                repaymentStrategy:
                    @json(old('repayment_strategy', 'standard')),

                plannedExtraPayment: Number(
                    @json(old('planned_extra_payment', 0))
                ),

                balloonPayment: Number(
                    @json(old('balloon_payment', 0))
                ),

                currencySymbol:
                    @json($formatter->symbol()),


                /*
                |--------------------------------------------------------------------------
                | Total Interest
                |--------------------------------------------------------------------------
                */

                get totalInterest() {

                    if (
                        this.interestType === 'none'
                        || !this.principal
                        || !this.interestRate
                    ) {
                        return 0;
                    }

                    return (
                        this.principal
                        * (this.interestRate / 100)
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Total Payable
                |--------------------------------------------------------------------------
                */

                get totalPayable() {

                    return (
                        this.principal
                        + this.totalInterest
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Calculated Monthly Payment
                |--------------------------------------------------------------------------
                */

                get calculatedMonthlyPayment() {

                    if (
                        !this.totalPayable
                        || !this.termMonths
                    ) {
                        return 0;
                    }

                    return (
                        this.totalPayable
                        / this.termMonths
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Display Monthly Payment
                |--------------------------------------------------------------------------
                */

                get monthlyPaymentValue() {

                    if (
                        this.monthlyPayment
                        && this.monthlyPayment > 0
                    ) {
                        return Number(
                            this.monthlyPayment
                        );
                    }

                    return Number(
                        this.calculatedMonthlyPayment
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Planned Monthly Total
                |--------------------------------------------------------------------------
                */

                get plannedMonthlyTotal() {

                    return (
                        this.monthlyPaymentValue
                        +
                        (
                            this.repaymentStrategy
                            === 'extra_principal'
                                ? Number(
                                    this.plannedExtraPayment || 0
                                )
                                : 0
                        )
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Balloon Monthly Estimate
                |--------------------------------------------------------------------------
                */

                get balloonMonthlyEstimate() {

                    if (
                        this.repaymentStrategy !== 'balloon'
                        || !this.termMonths
                        || this.termMonths <= 1
                    ) {
                        return 0;
                    }

                    const regularAmount =
                        Math.max(
                            0,
                            this.totalPayable
                            - Number(
                                this.balloonPayment || 0
                            )
                        );

                    return (
                        regularAmount
                        / (this.termMonths - 1)
                    );
                },


                /*
                |--------------------------------------------------------------------------
                | Strategy Label
                |--------------------------------------------------------------------------
                */

                get strategyLabel() {

                    switch (
                        this.repaymentStrategy
                    ) {

                        case 'extra_principal':

                            return 'Extra Principal Payment';

                        case 'balloon':

                            return 'Balloon Payment';

                        case 'snowball':

                            return 'Debt Snowball';

                        case 'avalanche':

                            return 'Debt Avalanche';

                        case 'custom':

                            return 'Custom Strategy';

                        default:

                            return 'Standard / Fixed Payment';
                    }
                },


                /*
                |--------------------------------------------------------------------------
                | Strategy Description
                |--------------------------------------------------------------------------
                */

                get strategyDescription() {

                    switch (
                        this.repaymentStrategy
                    ) {

                        case 'extra_principal':

                            return 'Pay more than the required monthly amount to reduce debt faster.';

                        case 'balloon':

                            return 'Pay smaller regular installments followed by a larger final payment.';

                        case 'snowball':

                            return 'Prioritize your smallest remaining debt first.';

                        case 'avalanche':

                            return 'Prioritize your highest-interest debt first.';

                        case 'custom':

                            return 'Use your own repayment approach while keeping normal loan tracking.';

                        default:

                            return 'Make regular fixed payments according to your loan schedule.';
                    }
                },


                /*
                |--------------------------------------------------------------------------
                | Strategy Guide
                |--------------------------------------------------------------------------
                */

                get strategyGuide() {

                    switch (
                        this.repaymentStrategy
                    ) {

                        case 'extra_principal':

                            return 'Your planned extra amount will be tracked separately from the required monthly payment. You can also make advance payments whenever you have extra money available.';

                        case 'balloon':

                            return 'A larger amount is reserved for the final installment. Plan ahead because the final payment will be significantly larger than the regular payments.';

                        case 'snowball':

                            return 'When managing multiple loans, prioritize the loan with the smallest remaining balance. After paying it off, roll that payment into the next smallest debt.';

                        case 'avalanche':

                            return 'When managing multiple loans, prioritize the loan with the highest interest rate. After paying it off, roll that payment into the next highest-interest debt.';

                        case 'custom':

                            return 'Use your own repayment approach while continuing the standard loan, installment, and payment tracking system.';

                        default:

                            return '';
                    }
                },


                /*
                |--------------------------------------------------------------------------
                | Format Money
                |--------------------------------------------------------------------------
                */

                formatMoney(value) {

                    return Number(
                        value || 0
                    ).toLocaleString(
                        'en-US',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    );
                }

            };

        }


        /*
        |--------------------------------------------------------------------------
        | Automatically Open Modal After Validation Error
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'alpine:init',
            () => {}
        );

    </script>


    {{-- =============================================================== --}}
    {{-- OPEN MODAL WHEN VALIDATION ERRORS EXIST --}}
    {{-- =============================================================== --}}

    @if ($errors->any())

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    setTimeout(
                        function () {

                            const modalTrigger =
                                document.querySelector(
                                    '[x-data="{ showLoanModal: false }"]'
                                );

                            if (modalTrigger) {

                                const alpineData =
                                    Alpine.$data(modalTrigger);

                                if (alpineData) {

                                    alpineData.showLoanModal = true;

                                }

                            }

                        },
                        100
                    );

                }
            );

        </script>

    @endif

</x-app-layout>
