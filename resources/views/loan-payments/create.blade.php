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

                    <h2 class="text-2xl font-bold text-gray-800">
                        Record Payment
                    </h2>

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    Record a payment for {{ $loan->loan_name }}
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">

                    <div class="font-semibold text-rose-800">
                        Please fix the following errors:
                    </div>

                    <ul class="mt-2 list-inside list-disc text-sm text-rose-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- LOAN SUMMARY --}}
            {{-- ========================================================= --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Loan
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-gray-900">
                            {{ $loan->loan_name }}
                        </h3>

                        @if ($loan->lender)

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $loan->lender }}
                            </p>

                        @endif

                    </div>


                    <div class="text-left sm:text-right">

                        <p class="text-sm text-gray-500">
                            Remaining Balance
                        </p>

                        <p class="mt-1 text-2xl font-bold text-indigo-600">
                            {{ $formatter->money($loan->remaining_balance) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- DEBT-FREE RECOMMENDATION --}}
            {{-- ========================================================= --}}

            @if (
                $rolloverAmount > 0
                || $plannerExtraPayment > 0
                || $strategy
            )

                <div class="mb-6 rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5">

                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-lg">
                            🎯
                        </div>


                        <div class="flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-semibold text-gray-900">
                                    Debt-Free Plan Recommendation
                                </h3>


                                @if ($strategy === 'snowball')

                                    <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                        Snowball
                                    </span>

                                @elseif ($strategy === 'avalanche')

                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                        Avalanche
                                    </span>

                                @endif

                            </div>


                            <p class="mt-1 text-sm text-gray-500">
                                The planner recommends paying more than the regular minimum when extra funds are available.
                            </p>


                            <div class="mt-4 grid gap-3 sm:grid-cols-3">


                                {{-- Regular --}}

                                <div class="rounded-xl bg-white p-3">

                                    <p class="text-xs text-gray-400">
                                        Regular Payment
                                    </p>

                                    <p class="mt-1 font-bold text-gray-900">
                                        {{ $formatter->money($regularPayment) }}
                                    </p>

                                </div>


                                {{-- Extra/Rollover --}}

                                <div class="rounded-xl bg-white p-3">

                                    <p class="text-xs text-gray-400">
                                        Extra / Rollover
                                    </p>

                                    <p class="mt-1 font-bold text-indigo-600">
                                        {{ $formatter->money($rolloverAmount) }}
                                    </p>

                                </div>


                                {{-- Recommended --}}

                                <div class="rounded-xl bg-indigo-600 p-3 text-white">

                                    <p class="text-xs text-indigo-200">
                                        Recommended Payment
                                    </p>

                                    <p class="mt-1 font-bold">
                                        {{ $formatter->money($recommendedPayment) }}
                                    </p>

                                </div>

                            </div>


                            <p class="mt-3 text-xs text-gray-500">
                                You can still edit the payment amount below.
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- PAYMENT FORM --}}
            {{-- ========================================================= --}}

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">

                <form
                    method="POST"
                    action="{{ route('loan-payments.store', $loan) }}"
                    class="space-y-6"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- AMOUNT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="amount"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Payment Amount
                        </label>


                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                {{ $formatter->symbol() }}
                            </span>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                value="{{ old('amount', $recommendedPayment) }}"
                                min="0.01"
                                max="{{ $loan->remaining_balance }}"
                                step="0.01"
                                required
                                placeholder="0.00"
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 text-lg font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        <div class="mt-2 flex flex-col gap-1 text-xs sm:flex-row sm:items-center sm:justify-between">

                            <p class="text-gray-500">

                                Regular payment:

                                <span class="font-semibold text-gray-700">
                                    {{ $formatter->money($regularPayment) }}
                                </span>

                            </p>


                            <p class="text-gray-500">

                                Maximum:

                                <span class="font-semibold text-gray-700">
                                    {{ $formatter->money($loan->remaining_balance) }}
                                </span>

                            </p>

                        </div>


                        @if ($recommendedPayment > $regularPayment)

                            <div class="mt-3 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2">

                                <p class="text-xs leading-5 text-emerald-700">

                                    💡

                                    The recommended amount is higher than your regular payment because your Debt-Free Plan includes extra payment capacity.

                                    You can change the amount before recording the payment.

                                </p>

                            </div>

                        @else

                            <p class="mt-3 text-xs text-gray-400">
                                The monthly payment is filled in automatically. You can edit it for an advance or larger payment.
                            </p>

                        @endif

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAYMENT DATE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="payment_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAYMENT METHOD --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="payment_method"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                Select payment method
                            </option>

                            @foreach ([
                                'Cash',
                                'Bank Transfer',
                                'GCash',
                                'Maya',
                                'Salary Deduction',
                                'Other'
                            ] as $method)

                                <option
                                    value="{{ $method }}"
                                    @selected(old('payment_method') === $method)
                                >
                                    {{ $method }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAYMENT SOURCE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="payment_source"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Payment Source
                        </label>

                        <select
                            name="payment_source"
                            id="payment_source"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                Select where the money came from
                            </option>

                            <option
                                value="Salary"
                                @selected(old('payment_source') === 'Salary')
                            >
                                Salary
                            </option>

                            <option
                                value="Side Income"
                                @selected(old('payment_source') === 'Side Income')
                            >
                                Side Income
                            </option>

                            <option
                                value="Freelance"
                                @selected(old('payment_source') === 'Freelance')
                            >
                                Freelance
                            </option>

                            <option
                                value="Other Income"
                                @selected(old('payment_source') === 'Other Income')
                            >
                                Other Income
                            </option>

                            <option
                                value="Savings"
                                @selected(old('payment_source') === 'Savings')
                            >
                                Savings
                            </option>

                        </select>

                        <p class="mt-1 text-xs text-gray-400">
                            Select the source of funds used for this payment.
                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- REFERENCE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="reference_number"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Reference Number

                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            id="reference_number"
                            value="{{ old('reference_number') }}"
                            placeholder="e.g. TXN-123456"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- ================================================= --}}
                    {{-- NOTES --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="notes"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Notes

                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>

                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            placeholder="Add any notes about this payment..."
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('notes') }}</textarea>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAYMENT PREVIEW --}}
                    {{-- ================================================= --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-gray-500">
                                Current Balance
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatter->money($loan->remaining_balance) }}
                            </span>

                        </div>


                        <div class="mt-3 flex items-center justify-between border-t border-gray-200 pt-3">

                            <span class="font-semibold text-gray-700">
                                Balance After Payment
                            </span>

                            <span
                                id="remaining-preview"
                                class="text-lg font-bold text-indigo-600"
                            >
                                {{ $formatter->money(
                                    max(
                                        0,
                                        (float) $loan->remaining_balance
                                        - (float) $recommendedPayment
                                    )
                                ) }}
                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('loans.show', $loan) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Record Payment
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

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const amountInput =
                    document.getElementById('amount');

                const remainingPreview =
                    document.getElementById('remaining-preview');

                const paymentMethod =
                    document.getElementById('payment_method');

                const paymentSource =
                    document.getElementById('payment_source');

                const currentBalance =
                    Number(
                        @json(
                            (float) $loan->remaining_balance
                        )
                    );

                const currencySymbol =
                    @json($formatter->symbol());


                /*
                |--------------------------------------------------------------------------
                | Balance Preview
                |--------------------------------------------------------------------------
                */

                function updateBalancePreview() {

                    let payment =
                        parseFloat(
                            amountInput.value
                        ) || 0;

                    payment =
                        Math.max(
                            0,
                            payment
                        );

                    let remaining =
                        Math.max(
                            0,
                            currentBalance
                            - payment
                        );

                    remainingPreview.textContent =
                        currencySymbol
                        +
                        remaining.toLocaleString(
                            'en-US',
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        );
                }


                amountInput.addEventListener(
                    'input',
                    updateBalancePreview
                );


                /*
                |--------------------------------------------------------------------------
                | Automatically Set Salary Source
                |--------------------------------------------------------------------------
                */

                paymentMethod.addEventListener(
                    'change',
                    function () {

                        if (
                            this.value
                            === 'Salary Deduction'
                        ) {

                            paymentSource.value =
                                'Salary';

                        }

                    }
                );


                updateBalancePreview();

            }
        );

    </script>

</x-app-layout>