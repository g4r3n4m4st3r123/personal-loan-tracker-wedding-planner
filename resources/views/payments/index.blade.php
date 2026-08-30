<x-app-layout>

    <x-slot name="header">

        <div>
            <p class="text-sm font-medium text-gray-500">
                Finance
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Loan Payments
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                View your payment history across all loans.
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- Total Payments --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <p class="text-sm font-medium text-gray-500">
                    Total Recorded Payments
                </p>

                <p class="mt-2 text-3xl font-bold text-indigo-600">
                    ₱{{ number_format($totalPayments, 2) }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $payments->count() }} recorded payment(s)
                </p>

            </div>


            {{-- Payment History --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Payment History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        All loan payments recorded in your account.
                    </p>

                </div>


                @if ($payments->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Loan
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Method
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Source
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($payments as $payment)

                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-6 py-4">

                                            <p class="font-semibold text-gray-900">
                                                {{ $payment->loan->loan_name }}
                                            </p>

                                            @if ($payment->loan->lender)

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $payment->loan->lender }}
                                                </p>

                                            @endif

                                        </td>


                                        <td class="px-6 py-4">

                                            <span class="font-semibold text-red-600">
                                                ₱{{ number_format($payment->amount, 2) }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $payment->payment_date->format('M d, Y') }}

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $payment->payment_method ?: '—' }}

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $payment->payment_source ?: '—' }}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="text-4xl">
                            💳
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            No payments yet
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Your recorded loan payments will appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>