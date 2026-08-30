<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('loans.show', $loan) }}"
                        class="text-gray-500 transition hover:text-gray-700"
                    >
                        ←
                    </a>

                    <div>

                        <h2 class="text-2xl font-bold text-gray-800">
                            Edit Loan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Update the details of {{ $loan->loan_name }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <div class="flex gap-2">

                        <div class="font-semibold text-red-800">
                            Please correct the following errors:
                        </div>

                    </div>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- LOAN FORM --}}
            {{-- ========================================================= --}}

            <div
                x-data="editLoanCalculator()"
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200"
            >

                <div class="border-b border-gray-100 px-6 py-5 sm:px-8">

                    <h3 class="text-lg font-bold text-gray-900">
                        Loan Information
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Update your loan details and repayment strategy below.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('loans.update', $loan) }}"
                    class="space-y-8 p-6 sm:p-8"
                >

                    @csrf
                    @method('PUT')


                    {{-- ================================================= --}}
                    {{-- BASIC INFORMATION --}}
                    {{-- ================================================= --}}

                    <div>

                        <h4 class="text-base font-semibold text-gray-900">
                            Basic Information
                        </h4>


                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            {{-- Loan Name --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="loan_name"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Loan Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="loan_name"
                                    id="loan_name"
                                    value="{{ old('loan_name', $loan->loan_name) }}"
                                    required
                                    placeholder="e.g. Personal Loan"
                                    class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Lender --}}

                            <div>

                                <label
                                    for="lender"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Lender
                                </label>

                                <input
                                    type="text"
                                    name="lender"
                                    id="lender"
                                    value="{{ old('lender', $loan->lender) }}"
                                    placeholder="e.g. Bank, Company, Person"
                                    class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Current Status --}}

                            <div>

                                <label class="block text-sm font-semibold text-gray-700">
                                    Current Status
                                </label>

                                <div class="mt-2 flex items-center">

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
                                                'bg-gray-100 text-gray-600 ring-gray-500/20',
                                        ];

                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold capitalize ring-1 ring-inset {{ $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-600 ring-gray-500/20' }}"
                                    >
                                        {{ $loan->status }}
                                    </span>

                                </div>

                                <p class="mt-2 text-xs text-gray-500">
                                    Status is automatically determined from the loan balance,
                                    installment schedule, and due dates.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- FINANCIAL INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="border-t border-gray-100 pt-8">

                        <h4 class="text-base font-semibold text-gray-900">
                            Financial Information
                        </h4>


                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            {{-- Principal --}}

                            <div>

                                <label
                                    for="principal_amount"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Principal Amount
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative mt-2">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                        {{ $formatter->symbol() }}
                                    </span>

                                    <input
                                        type="number"
                                        name="principal_amount"
                                        id="principal_amount"
                                        x-model.number="principal"
                                        value="{{ old('principal_amount', $loan->principal_amount) }}"
                                        min="0.01"
                                        step="0.01"
                                        required
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                            </div>


                            {{-- Interest Rate --}}

                            <div>

                                <label
                                    for="interest_rate"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Interest Rate (%)
                                </label>

                                <div class="relative mt-2">

                                    <input
                                        type="number"
                                        name="interest_rate"
                                        id="interest_rate"
                                        x-model.number="interestRate"
                                        value="{{ old('interest_rate', $loan->interest_rate) }}"
                                        min="0"
                                        step="0.01"
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-4 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        %
                                    </span>

                                </div>

                            </div>


                            {{-- Interest Type --}}

                            <div>

                                <label
                                    for="interest_type"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Interest Type
                                </label>

                                <select
                                    name="interest_type"
                                    id="interest_type"
                                    x-model="interestType"
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    for="term_months"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Term (Months)
                                </label>

                                <input
                                    type="number"
                                    name="term_months"
                                    id="term_months"
                                    x-model.number="termMonths"
                                    value="{{ old('term_months', $loan->term_months) }}"
                                    min="1"
                                    step="1"
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Monthly Payment --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="monthly_payment"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Monthly Payment
                                </label>

                                <div class="relative mt-2">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                        {{ $formatter->symbol() }}
                                    </span>

                                    <input
                                        type="number"
                                        name="monthly_payment"
                                        id="monthly_payment"
                                        x-model.number="monthlyPayment"
                                        value="{{ old('monthly_payment', $loan->monthly_payment) }}"
                                        min="0"
                                        step="0.01"
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                                <p class="mt-2 text-xs text-gray-500">
                                    Current monthly payment:
                                    <span class="font-semibold text-gray-700">
                                        {{ $formatter->money($loan->monthly_payment) }}
                                    </span>
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- REPAYMENT STRATEGY --}}
                    {{-- ================================================= --}}

                    <div class="border-t border-gray-100 pt-8">

                        <div>

                            <h4 class="text-base font-semibold text-gray-900">
                                Repayment Strategy
                            </h4>

                            <p class="mt-1 text-sm text-gray-500">
                                Choose how you want to approach paying down this loan.
                            </p>

                        </div>


                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            {{-- Strategy --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="repayment_strategy"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Repayment Strategy
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="repayment_strategy"
                                    id="repayment_strategy"
                                    x-model="repaymentStrategy"
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                                <p class="mt-2 text-xs text-gray-500">
                                    <span x-text="strategyDescription"></span>
                                </p>

                            </div>


                            {{-- Extra Principal --}}

                            <div
                                x-show="repaymentStrategy === 'extra_principal'"
                                x-transition
                                x-cloak
                                class="sm:col-span-2 rounded-xl border border-emerald-100 bg-emerald-50/60 p-5"
                            >

                                <label
                                    for="planned_extra_payment"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Planned Extra Payment
                                </label>

                                <div class="relative mt-2">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                        {{ $formatter->symbol() }}
                                    </span>

                                    <input
                                        type="number"
                                        name="planned_extra_payment"
                                        id="planned_extra_payment"
                                        x-model.number="plannedExtraPayment"
                                        value="{{ old('planned_extra_payment', $loan->planned_extra_payment ?? 0) }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                                <div class="mt-3 flex items-center justify-between rounded-lg bg-white/80 px-3 py-2">

                                    <span class="text-sm text-gray-500">
                                        Planned Monthly Total
                                    </span>

                                    <span class="font-bold text-emerald-700">
                                        {{ $formatter->symbol() }}
                                        <span x-text="formatMoney(plannedMonthlyTotal)"></span>
                                    </span>

                                </div>

                            </div>


                            {{-- Balloon --}}

                            <div
                                x-show="repaymentStrategy === 'balloon'"
                                x-transition
                                x-cloak
                                class="sm:col-span-2 rounded-xl border border-amber-100 bg-amber-50/60 p-5"
                            >

                                <label
                                    for="balloon_payment"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Final Balloon Payment
                                </label>

                                <div class="relative mt-2">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                        {{ $formatter->symbol() }}
                                    </span>

                                    <input
                                        type="number"
                                        name="balloon_payment"
                                        id="balloon_payment"
                                        x-model.number="balloonPayment"
                                        value="{{ old('balloon_payment', $loan->balloon_payment ?? 0) }}"
                                        min="0"
                                        step="0.01"
                                        :max="Math.max(0, totalPayable - 0.01)"
                                        placeholder="0.00"
                                        class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                                <div class="mt-3 space-y-2 rounded-lg bg-white/80 px-3 py-3">

                                    <div class="flex items-center justify-between text-sm">

                                        <span class="text-gray-500">
                                            Regular Payment Estimate
                                        </span>

                                        <span class="font-bold text-amber-700">
                                            {{ $formatter->symbol() }}
                                            <span x-text="formatMoney(balloonMonthlyEstimate)"></span>
                                        </span>

                                    </div>

                                    <div class="flex items-center justify-between text-sm">

                                        <span class="text-gray-500">
                                            Final Balloon
                                        </span>

                                        <span class="font-bold text-gray-900">
                                            {{ $formatter->symbol() }}
                                            <span x-text="formatMoney(balloonPayment)"></span>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- Strategy Information --}}

                            <div
                                x-show="repaymentStrategy !== 'standard'"
                                x-transition
                                x-cloak
                                class="sm:col-span-2 rounded-xl border border-indigo-100 bg-indigo-50/60 p-5"
                            >

                                <div class="flex gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100">
                                        💡
                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            <span x-text="strategyLabel"></span>
                                        </p>

                                        <p
                                            class="mt-1 text-sm leading-6 text-gray-600"
                                            x-text="strategyGuide"
                                        ></p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CURRENT LOAN SNAPSHOT --}}
                    {{-- ================================================= --}}

                    <div class="border-t border-gray-100 pt-8">

                        <h4 class="text-base font-semibold text-gray-900">
                            Current Loan Snapshot
                        </h4>

                        <div class="mt-4 grid gap-4 sm:grid-cols-3">

                            <div class="rounded-xl bg-gray-50 p-4">

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Total Payable
                                </p>

                                <p class="mt-2 text-lg font-bold text-gray-900">
                                    {{ $formatter->money($loan->total_payable) }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-4">

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Total Paid
                                </p>

                                <p class="mt-2 text-lg font-bold text-emerald-600">
                                    {{ $formatter->money($loan->total_paid) }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-900 p-4 text-white">

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Remaining Balance
                                </p>

                                <p class="mt-2 text-lg font-bold">
                                    {{ $formatter->money($loan->remaining_balance) }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DATES --}}
                    {{-- ================================================= --}}

                    <div class="border-t border-gray-100 pt-8">

                        <h4 class="text-base font-semibold text-gray-900">
                            Loan Dates
                        </h4>

                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            {{-- Start Date --}}

                            <div>

                                <label
                                    for="start_date"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Start Date
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="start_date"
                                    id="start_date"
                                    value="{{ old('start_date', $loan->start_date?->format('Y-m-d')) }}"
                                    required
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Due Date --}}

                            <div>

                                <label
                                    for="due_date"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Due Date
                                </label>

                                <input
                                    type="date"
                                    name="due_date"
                                    id="due_date"
                                    value="{{ old('due_date', $loan->due_date?->format('Y-m-d')) }}"
                                    class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- NOTES --}}
                    {{-- ================================================= --}}

                    <div class="border-t border-gray-100 pt-8">

                        <h4 class="text-base font-semibold text-gray-900">
                            Additional Notes
                        </h4>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="5"
                            placeholder="Add any additional information about this loan..."
                            class="mt-4 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('notes', $loan->notes) }}</textarea>

                    </div>


                    {{-- ================================================= --}}
                    {{-- WARNING --}}
                    {{-- ================================================= --}}

                    @if ($loan->payments()->exists())

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">

                            <div class="flex gap-3">

                                <div class="text-amber-600">
                                    ⚠️
                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-amber-800">
                                        This loan already has payment records.
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700">
                                        Changing the principal, term, interest, or repayment strategy
                                        will update the loan information, but the existing installment
                                        payment allocations will be preserved.
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('loans.show', $loan) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Save Changes
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- =============================================================== --}}

    <script>

        function editLoanCalculator() {

            return {

                principal: Number(
                    @json(
                        old(
                            'principal_amount',
                            $loan->principal_amount
                        )
                    )
                ),

                interestRate: Number(
                    @json(
                        old(
                            'interest_rate',
                            $loan->interest_rate
                        )
                    )
                ),

                interestType:
                    @json(
                        old(
                            'interest_type',
                            $loan->interest_type ?? 'none'
                        )
                    ),

                termMonths: Number(
                    @json(
                        old(
                            'term_months',
                            $loan->term_months ?? 1
                        )
                    )
                ),

                monthlyPayment: Number(
                    @json(
                        old(
                            'monthly_payment',
                            $loan->monthly_payment ?? 0
                        )
                    )
                ),

                repaymentStrategy:
                    @json(
                        old(
                            'repayment_strategy',
                            $loan->repayment_strategy ?? 'standard'
                        )
                    ),

                plannedExtraPayment: Number(
                    @json(
                        old(
                            'planned_extra_payment',
                            $loan->planned_extra_payment ?? 0
                        )
                    )
                ),

                balloonPayment: Number(
                    @json(
                        old(
                            'balloon_payment',
                            $loan->balloon_payment ?? 0
                        )
                    )
                ),


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
                | Planned Monthly Total
                |--------------------------------------------------------------------------
                */

                get plannedMonthlyTotal() {

                    const regularPayment =
                        Number(
                            this.monthlyPayment || 0
                        );

                    const extraPayment =
                        this.repaymentStrategy === 'extra_principal'
                            ? Number(
                                this.plannedExtraPayment || 0
                            )
                            : 0;

                    return (
                        regularPayment
                        + extraPayment
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

                            return 'Your planned extra payment can help reduce the debt faster. You can also make additional advance payments whenever you have extra funds.';

                        case 'balloon':

                            return 'A larger amount is reserved for the final installment. Make sure you can cover the larger final payment when it becomes due.';

                        case 'snowball':

                            return 'For multiple loans, prioritize the smallest remaining balance first. After paying it off, redirect that payment to the next smallest debt.';

                        case 'avalanche':

                            return 'For multiple loans, prioritize the loan with the highest interest rate first. After paying it off, redirect that payment to the next highest-interest debt.';

                        case 'custom':

                            return 'Continue using the normal loan and installment tracker while following your own repayment plan.';

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

    </script>

</x-app-layout>