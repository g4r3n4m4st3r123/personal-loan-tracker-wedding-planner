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

                        <p class="text-sm font-medium text-gray-500">
                            Loan Payments
                        </p>

                        <h2 class="text-2xl font-bold text-gray-800">
                            Payment History
                        </h2>

                    </div>

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    View all payments recorded for {{ $loan->loan_name }}.
                </p>

            </div>


            <a
                href="{{ route('loan-payments.create', $loan) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
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

                Record Payment

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">


            {{-- Success --}}

            @if (session('success'))

                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">

                    <div class="flex items-center gap-2">

                        <svg
                            class="h-5 w-5 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />

                        </svg>

                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- Loan Summary --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">


                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Loan
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $loan->loan_name }}
                        </p>

                        @if ($loan->lender)

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $loan->lender }}
                            </p>

                        @endif

                    </div>


                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Payable
                        </p>

                        @php

                            $paymentTotalPayable =
                                (float) $loan->principal_amount;

                            if (
                                in_array(
                                    $loan->interest_type,
                                    ['simple', 'fixed']
                                )
                            ) {

                                $paymentTotalPayable +=
                                    $paymentTotalPayable *
                                    (
                                        (float) $loan->interest_rate / 100
                                    );
                            }

                        @endphp


                        <p class="mt-1 text-xl font-bold text-gray-900">
                            {{ $formatter->money($paymentTotalPayable) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Paid
                        </p>

                        <p class="mt-1 text-xl font-bold text-green-600">
                            {{ $formatter->money($loan->total_paid) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Remaining Balance
                        </p>

                        <p class="mt-1 text-xl font-bold text-indigo-600">
                            {{ $formatter->money($loan->remaining_balance) }}
                        </p>

                    </div>

                </div>


                {{-- Progress --}}

                <div class="mt-6 border-t border-gray-100 pt-5">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-gray-500">
                            Payment Progress
                        </span>

                        <span class="text-sm font-bold text-gray-900">
                            {{ number_format($loan->payment_progress, 0) }}%
                        </span>

                    </div>


                    <div class="mt-2 h-3 overflow-hidden rounded-full bg-gray-100">

                        <div
                            class="h-full rounded-full bg-indigo-600 transition-all"
                            style="width: {{ min(100, $loan->payment_progress) }}%"
                        ></div>

                    </div>

                </div>

            </div>


            {{-- Payment History --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Payment Records
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $payments->count() }}
                                {{ $payments->count() === 1 ? 'payment' : 'payments' }}
                                recorded
                            </p>

                        </div>

                    </div>

                </div>


                @if ($payments->count() > 0)


                    {{-- Desktop Table --}}

                    <div class="hidden overflow-x-auto md:block">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Method
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Reference
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Notes
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100 bg-white">

                                @foreach ($payments as $payment)

                                    <tr class="transition hover:bg-gray-50">


                                        <td class="whitespace-nowrap px-6 py-4">

                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $formatter->date($payment->payment_date) }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $payment->created_at->format('h:i A') }}
                                            </p>

                                        </td>


                                        <td class="whitespace-nowrap px-6 py-4">

                                            <p class="text-sm font-bold text-green-600">
                                                {{ $formatter->money($payment->amount) }}
                                            </p>

                                        </td>


                                        <td class="whitespace-nowrap px-6 py-4">

                                            @if ($payment->payment_method)

                                                <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                    {{ $payment->payment_method }}
                                                </span>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">

                                            {{ $payment->reference_number ?: '—' }}

                                        </td>


                                        <td class="max-w-xs px-6 py-4">

                                            @if ($payment->notes)

                                                <p class="truncate text-sm text-gray-600">
                                                    {{ $payment->notes }}
                                                </p>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <form
                                                method="POST"
                                                action="{{ route('loan-payments.destroy', $payment) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this payment?');"
                                                class="inline"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-semibold text-red-600 transition hover:text-red-800"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Mobile Cards --}}

                    <div class="divide-y divide-gray-100 md:hidden">

                        @foreach ($payments as $payment)

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $formatter->date($payment->payment_date) }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $payment->payment_method ?: 'Payment' }}
                                        </p>

                                    </div>


                                    <p class="text-lg font-bold text-green-600">
                                        {{ $formatter->money($payment->amount) }}
                                    </p>

                                </div>


                                <div class="mt-4 space-y-2">

                                    @if ($payment->reference_number)

                                        <div class="flex justify-between gap-4 text-sm">

                                            <span class="text-gray-400">
                                                Reference
                                            </span>

                                            <span class="font-medium text-gray-700">
                                                {{ $payment->reference_number }}
                                            </span>

                                        </div>

                                    @endif


                                    @if ($payment->notes)

                                        <div class="rounded-lg bg-gray-50 p-3">

                                            <p class="text-xs font-medium text-gray-400">
                                                Notes
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $payment->notes }}
                                            </p>

                                        </div>

                                    @endif

                                </div>


                                <div class="mt-4 border-t border-gray-100 pt-4">

                                    <form
                                        method="POST"
                                        action="{{ route('loan-payments.destroy', $payment) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this payment?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-sm font-semibold text-red-600 hover:text-red-800"
                                        >
                                            Delete Payment
                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>


                @else


                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-3xl">
                            💳
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-gray-900">
                            No payments yet
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                            No payments have been recorded for this loan yet.
                        </p>

                        <a
                            href="{{ route('loan-payments.create', $loan) }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
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

                            Record First Payment

                        </a>

                    </div>

                @endif

            </div>


            {{-- Available for Loan Payments --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Available for Loan Payments
                        </p>

                        <p class="mt-2 text-3xl font-bold text-emerald-600">
                            {{ $formatter->money($availableIncomeForLoans) }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Additional income remaining after income-funded loan payments.
                        </p>

                    </div>


                    <div class="rounded-xl bg-emerald-50 px-5 py-4 text-right">

                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-700">
                            Used for Loans
                        </p>

                        <p class="mt-1 text-xl font-bold text-emerald-700">
                            {{ $formatter->money($incomeUsedForLoans) }}
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            {{ number_format($incomeUsagePercentage, 1) }}% of additional income
                        </p>

                    </div>

                </div>


                <div class="mt-5">

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-xs font-medium text-gray-500">
                            Income Used
                        </span>

                        <span class="text-xs font-semibold text-gray-700">
                            {{ number_format($incomeUsagePercentage, 1) }}%
                        </span>

                    </div>


                    <div class="h-3 overflow-hidden rounded-full bg-gray-100">

                        <div
                            class="h-full rounded-full bg-emerald-500 transition-all"
                            style="width: {{ min(100, max(0, $incomeUsagePercentage)) }}%"
                        ></div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>