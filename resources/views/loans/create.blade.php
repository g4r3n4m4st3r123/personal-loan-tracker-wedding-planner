<x-app-layout>

    <div class="mb-8">

        <a
            href="{{ route('loans.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>

            Back to Loans
        </a>

        <div class="mt-4">

            <p class="text-sm font-medium text-slate-500">
                Finance
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Add New Loan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Enter the details of your loan account and choose a repayment strategy.
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">

            <div class="flex gap-3">

                <div class="text-rose-600">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a1 1 0 001.75 1.02l7.82-14a1 1 0 001.75 1.02zM13.71 3.86l7.82 14a1 1 0 011.75 1.02l-7.82-14a1 1 0 011.75 1.02z"
                        />

                    </svg>

                </div>

                <div>

                    <p class="font-semibold text-rose-800">
                        Please fix the following errors:
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MAIN FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('loans.store') }}"
        x-data="loanCalculator()"
        class="space-y-6"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- LOAN INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-base font-bold text-slate-900">
                    Loan Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Basic information about your loan.
                </p>

            </div>


            <div class="grid gap-6 p-6 md:grid-cols-2">


                {{-- LOAN NAME --}}

                <div class="md:col-span-2">

                    <label
                        for="loan_name"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Loan Name
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="loan_name"
                        name="loan_name"
                        value="{{ old('loan_name') }}"
                        required
                        placeholder="e.g. Personal Loan"
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>


                {{-- LENDER --}}

                <div>

                    <label
                        for="lender"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Lender
                    </label>

                    <input
                        type="text"
                        id="lender"
                        name="lender"
                        value="{{ old('lender') }}"
                        placeholder="e.g. Bank, Lending Company, Friend"
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>


                {{-- PRINCIPAL --}}

                <div>

                    <label
                        for="principal_amount"
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
                            id="principal_amount"
                            name="principal_amount"
                            x-model.number="principal"
                            value="{{ old('principal_amount') }}"
                            min="0"
                            step="0.01"
                            required
                            placeholder="0.00"
                            class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                </div>


                {{-- INTEREST RATE --}}

                <div>

                    <label
                        for="interest_rate"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Interest Rate (%)
                    </label>

                    <div class="relative mt-2">

                        <input
                            type="number"
                            id="interest_rate"
                            name="interest_rate"
                            x-model.number="interestRate"
                            value="{{ old('interest_rate', 0) }}"
                            min="0"
                            step="0.01"
                            placeholder="0"
                            class="block w-full rounded-xl border-slate-300 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">
                            %
                        </span>

                    </div>

                </div>


                {{-- INTEREST TYPE --}}

                <div>

                    <label
                        for="interest_type"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Interest Type
                    </label>

                    <select
                        id="interest_type"
                        name="interest_type"
                        x-model="interestType"
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
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


                {{-- TERM --}}

                <div>

                    <label
                        for="term_months"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Term (Months)
                    </label>

                    <input
                        type="number"
                        id="term_months"
                        name="term_months"
                        x-model.number="termMonths"
                        value="{{ old('term_months', 12) }}"
                        min="1"
                        step="1"
                        placeholder="12"
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>


                {{-- START DATE --}}

                <div>

                    <label
                        for="start_date"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Start Date
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                        required
                        class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>


                {{-- DUE DATE --}}

                <div>

                    <label
                        for="due_date"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Due Date
                    </label>

                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="mt-2 block w-full rounded-xl border-slate-300 py-3 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>


                {{-- MONTHLY PAYMENT --}}

                <div>

                    <label
                        for="monthly_payment"
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
                            id="monthly_payment"
                            name="monthly_payment"
                            x-model.number="monthlyPayment"
                            :value="monthlyPaymentValue"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                    <p class="mt-2 text-xs text-slate-400">
                        Automatically calculated. You can adjust this amount if needed.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- REPAYMENT STRATEGY --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="repayment_strategy"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Repayment Strategy
                    </label>

                    <select
                        id="repayment_strategy"
                        name="repayment_strategy"
                        x-model="repaymentStrategy"
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
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
                        for="planned_extra_payment"
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
                            id="planned_extra_payment"
                            name="planned_extra_payment"
                            x-model.number="plannedExtraPayment"
                            value="{{ old('planned_extra_payment', 0) }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        Additional amount you plan to pay on top of your regular monthly payment.
                    </p>

                    <div class="mt-3 rounded-lg bg-white/80 px-3 py-2">

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-500">
                                Planned Monthly Total
                            </span>

                            <span class="font-bold text-emerald-700">
                                {{ $formatter->symbol() }}<span x-text="formatMoney(plannedMonthlyTotal)"></span>
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
                        for="balloon_payment"
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
                            id="balloon_payment"
                            name="balloon_payment"
                            x-model.number="balloonPayment"
                            value="{{ old('balloon_payment', 0) }}"
                            min="0"
                            step="0.01"
                            :max="Math.max(0, totalPayable - 0.01)"
                            placeholder="0.00"
                            class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        A larger payment that will be due on the final installment.
                    </p>

                    <div class="mt-3 space-y-2 rounded-lg bg-white/80 px-3 py-3">

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-500">
                                Regular Payment Estimate
                            </span>

                            <span class="font-bold text-amber-700">
                                {{ $formatter->symbol() }}<span x-text="formatMoney(balloonMonthlyEstimate)"></span>
                            </span>

                        </div>

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-500">
                                Final Balloon
                            </span>

                            <span class="font-bold text-slate-900">
                                {{ $formatter->symbol() }}<span x-text="formatMoney(balloonPayment)"></span>
                            </span>

                        </div>

                    </div>

                </div>


                {{-- NOTES --}}

                <div class="md:col-span-2">

                    <label
                        for="notes"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        placeholder="Add any additional notes about this loan..."
                        class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >{{ old('notes') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- STRATEGY INFORMATION --}}
        {{-- ========================================================= --}}

        <div
            x-show="repaymentStrategy !== 'standard'"
            x-transition
            x-cloak
            class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-6"
        >

            <div class="flex gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-lg">
                    💡
                </div>

                <div>

                    <h3 class="font-bold text-slate-900">
                        <span x-text="strategyLabel"></span>
                    </h3>

                    <p
                        class="mt-1 text-sm leading-6 text-slate-600"
                        x-text="strategyGuide"
                    ></p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LOAN SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-base font-bold text-slate-900">
                    Loan Summary
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Estimated payment breakdown.
                </p>

            </div>


            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">


                {{-- PRINCIPAL --}}

                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-sm text-slate-500">
                        Principal
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900">
                        {{ $formatter->symbol() }}<span x-text="formatMoney(principal)"></span>
                    </p>

                </div>


                {{-- INTEREST --}}

                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-sm text-slate-500">
                        Total Interest
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900">
                        {{ $formatter->symbol() }}<span x-text="formatMoney(totalInterest)"></span>
                    </p>

                </div>


                {{-- TOTAL PAYABLE --}}

                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-sm text-slate-500">
                        Total Payable
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900">
                        {{ $formatter->symbol() }}<span x-text="formatMoney(totalPayable)"></span>
                    </p>

                </div>


                {{-- MONTHLY --}}

                <div class="rounded-xl bg-slate-900 p-5 text-white">

                    <p class="text-sm text-slate-300">
                        Monthly Payment
                    </p>

                    <p class="mt-2 text-xl font-bold">
                        {{ $formatter->symbol() }}<span x-text="formatMoney(monthlyPaymentValue)"></span>
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('loans.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
            >
                Save Loan
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

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
                | Extra Principal Monthly Total
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

                            return 'Pay an additional amount regularly to reduce the debt faster.';

                        case 'balloon':

                            return 'Pay smaller regular installments followed by a larger final payment.';

                        case 'snowball':

                            return 'Prioritize your smallest remaining debt first.';

                        case 'avalanche':

                            return 'Prioritize your highest-interest debt first.';

                        case 'custom':

                            return 'Use your own repayment strategy while continuing normal loan tracking.';

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

                            return 'Your planned extra amount will be tracked separately from your required monthly payment. You can also make additional advance payments at any time.';

                        case 'balloon':

                            return 'A portion of the loan is reserved for a larger final payment. Plan ahead because the final installment will be significantly larger than the regular installments.';

                        case 'snowball':

                            return 'When managing multiple loans, prioritize the loan with the smallest remaining balance. Once it is paid, redirect that payment toward the next smallest loan.';

                        case 'avalanche':

                            return 'When managing multiple loans, prioritize the loan with the highest interest rate. Once it is paid, redirect that payment toward the next highest-interest loan.';

                        case 'custom':

                            return 'Your loan will continue using the normal installment and payment tracking system, while you manage your own repayment approach.';

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