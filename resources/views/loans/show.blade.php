```blade
<x-app-layout>

    {{-- =============================================================== --}}
    {{-- HEADER --}}
    {{-- =============================================================== --}}

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            {{-- Title --}}

            <div class="min-w-0">

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('loans.index') }}"
                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                        aria-label="Back to loans"
                    >
                        ←
                    </a>

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <h2 class="truncate text-2xl font-bold text-gray-800">
                                {{ $loan->loan_name }}
                            </h2>

                            @if ($loan->status === 'completed')

                                <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    Completed
                                </span>

                            @elseif ($loan->status === 'overdue')

                                <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                    Overdue
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    Active
                                </span>

                            @endif

                        </div>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $loan->lender ?: 'No lender specified' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Actions --}}

            <div class="flex flex-wrap gap-2">

                <a
                    href="{{ route('loans.edit', $loan) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Edit Loan
                </a>

                @if ($loan->remaining_balance > 0)

                    <a
                        href="{{ route('loan-payments.create', $loan) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        + Record Payment
                    </a>

                @endif

            </div>

        </div>

    </x-slot>


    {{-- =============================================================== --}}
    {{-- PAGE --}}
    {{-- =============================================================== --}}

    <div class="py-8">

        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">


            {{-- ======================================================= --}}
            {{-- SUCCESS --}}
            {{-- ======================================================= --}}

            @if (session('success'))

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">

                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ======================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ======================================================= --}}

            @if ($errors->any())

                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-4">

                    <p class="text-sm font-semibold text-rose-800">
                        Please fix the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-rose-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ======================================================= --}}
            {{-- LOAN OVERVIEW --}}
            {{-- ======================================================= --}}

            @php

                $totalPayable = (float) $loan->principal_amount;

                if (in_array($loan->interest_type, ['simple', 'fixed'], true)) {

                    $totalPayable +=
                        $totalPayable *
                        ((float) $loan->interest_rate / 100);
                }

                $paymentProgress = (float) $loan->payment_progress;

            @endphp


            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                {{-- Main Numbers --}}

                <div class="grid divide-y divide-gray-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">


                    {{-- Remaining --}}

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Remaining Balance
                        </p>

                        <p class="mt-2 text-3xl font-bold text-indigo-600">
                            {{ $formatter->money($loan->remaining_balance) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Still to be paid
                        </p>

                    </div>


                    {{-- Monthly --}}

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Monthly Payment
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $formatter->money((float) $loan->monthly_payment) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Scheduled installment
                        </p>

                    </div>


                    {{-- Progress --}}

                    <div class="p-6">

                        <div class="flex items-center justify-between gap-3">

                            <p class="text-sm font-medium text-gray-500">
                                Payment Progress
                            </p>

                            <span class="text-sm font-bold text-indigo-600">
                                {{ number_format($paymentProgress, 1) }}%
                            </span>

                        </div>

                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full bg-indigo-600 transition-all duration-500"
                                style="width: {{ min(100, max(0, $paymentProgress)) }}%"
                            ></div>

                        </div>

                        <div class="mt-2 flex justify-between text-xs text-gray-400">

                            <span>
                                {{ $formatter->money($loan->total_paid) }} paid
                            </span>

                            <span>
                                {{ $formatter->money($loan->remaining_balance) }} left
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Secondary Details --}}

                <div class="border-t border-gray-100 bg-gray-50/70 px-6 py-4">

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        <div>

                            <p class="text-xs font-medium text-gray-400">
                                Principal
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $formatter->money((float) $loan->principal_amount) }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-gray-400">
                                Total Payable
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $formatter->money($totalPayable) }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-gray-400">
                                Term
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $loan->term_months }} month(s)
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-gray-400">
                                Interest
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ number_format((float) $loan->interest_rate, 2) }}%
                                {{ $loan->interest_type ? '(' . ucfirst($loan->interest_type) . ')' : '' }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ======================================================= --}}
            {{-- NEXT PAYMENT --}}
            {{-- ======================================================= --}}

            @if ($nextInstallment)

                <div class="overflow-hidden rounded-2xl border border-indigo-100 bg-indigo-50/60">


                    <div class="flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                <p class="text-xs font-bold uppercase tracking-wide text-indigo-600">
                                    Next Payment
                                </p>

                                @if ($nextInstallment->status === 'overdue')

                                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">
                                        Overdue
                                    </span>

                                @elseif ($nextInstallment->status === 'due_today')

                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                        Due Today
                                    </span>

                                @elseif ($nextInstallment->status === 'partial')

                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                        Partial
                                    </span>

                                @endif

                            </div>

                            <h3 class="mt-1 text-xl font-bold text-gray-900">
                                Installment #{{ $nextInstallment->installment_number }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">

                                Due
                                {{ $formatter->date($nextInstallment->due_date) }}

                            </p>

                        </div>


                        <div class="flex flex-col gap-3 sm:items-end">

                            <div>

                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ $formatter->money($nextInstallment->remaining_amount) }}
                                </p>

                                @if ((float) $nextInstallment->amount_paid > 0)

                                    <p class="mt-1 text-right text-xs text-gray-400">

                                        {{ $formatter->money($nextInstallment->amount_paid) }}
                                        already paid

                                    </p>

                                @endif

                            </div>


                            <a
                                href="{{ route('loan-payments.create', $loan) }}"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                Record Payment
                            </a>

                        </div>

                    </div>

                </div>

            @elseif ($loan->remaining_balance <= 0)

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-xl">
                            ✓
                        </div>

                        <div>

                            <p class="font-bold text-emerald-800">
                                Loan Fully Paid
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">
                                Congratulations! This loan has been completely paid.
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ======================================================= --}}
            {{-- PAYMENT SCHEDULE --}}
            {{-- ======================================================= --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                {{-- Header --}}

                <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">
                            Repayment Plan
                        </p>

                        <h3 class="mt-1 text-lg font-bold text-gray-900">
                            Payment Schedule
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Your monthly installments and their current status.
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">

                        <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                            {{ $totalInstallments }} total
                        </span>

                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                            {{ $paidInstallments }} paid
                        </span>

                        @if ($partialInstallments > 0)

                            <span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                {{ $partialInstallments }} partial
                            </span>

                        @endif

                        @if ($overdueInstallments > 0)

                            <span class="rounded-full bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                {{ $overdueInstallments }} overdue
                            </span>

                        @endif

                    </div>

                </div>


                @if ($installments->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        #
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Due Date
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount Due
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Paid
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Remaining
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($installments as $installment)

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Number --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            <span class="font-semibold text-gray-700">
                                                #{{ $installment->installment_number }}
                                            </span>

                                        </td>


                                        {{-- Due Date --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $formatter->date($installment->due_date) }}
                                            </p>

                                            @if ($installment->status !== 'paid')

                                                @if ($installment->due_date->isToday())

                                                    <p class="mt-1 text-xs font-semibold text-blue-600">
                                                        Due today
                                                    </p>

                                                @elseif ($installment->due_date->isPast())

                                                    <p class="mt-1 text-xs font-semibold text-rose-500">
                                                        {{ $installment->due_date->diffForHumans() }}
                                                    </p>

                                                @else

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        {{ $installment->due_date->diffForHumans() }}
                                                    </p>

                                                @endif

                                            @endif

                                        </td>


                                        {{-- Due --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <span class="font-semibold text-gray-900">
                                                {{ $formatter->money($installment->amount_due) }}
                                            </span>

                                        </td>


                                        {{-- Paid --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <span class="font-semibold text-emerald-600">
                                                {{ $formatter->money($installment->amount_paid) }}
                                            </span>

                                        </td>


                                        {{-- Remaining --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <span
                                                class="font-semibold
                                                {{ $installment->remaining_amount > 0
                                                    ? 'text-amber-600'
                                                    : 'text-emerald-600' }}"
                                            >
                                                {{ $formatter->money($installment->remaining_amount) }}
                                            </span>

                                        </td>


                                        {{-- Status --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            @if ($installment->status === 'paid')

                                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Paid
                                                </span>

                                            @elseif ($installment->status === 'partial')

                                                <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Partial
                                                </span>

                                            @elseif ($installment->status === 'overdue')

                                                <span class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">
                                                    Overdue
                                                </span>

                                            @elseif ($installment->status === 'due_today')

                                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                                    Due Today
                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                    Upcoming
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>


                            <tfoot class="border-t border-gray-200 bg-gray-50">

                                <tr>

                                    <td
                                        colspan="2"
                                        class="px-6 py-4 font-bold text-gray-900"
                                    >
                                        Total
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        {{ $formatter->money($installments->sum('amount_due')) }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                        {{ $formatter->money($installments->sum('amount_paid')) }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-amber-600">
                                        {{ $formatter->money($installments->sum('remaining_amount')) }}
                                    </td>

                                    <td></td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-2xl">
                            📅
                        </div>

                        <h4 class="mt-4 font-semibold text-gray-900">
                            No payment schedule
                        </h4>

                        <p class="mt-1 text-sm text-gray-500">
                            This loan does not have any installments yet.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ======================================================= --}}
            {{-- PAYMENT HISTORY --}}
            {{-- ======================================================= --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                {{-- Header --}}

                <div class="flex flex-col gap-2 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Payment History
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Payments recorded for this loan.
                        </p>

                    </div>


                    <span class="rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">
                        {{ $payments->count() }}
                        {{ $payments->count() === 1 ? 'payment' : 'payments' }}
                    </span>

                </div>


                @if ($payments->count())

                    {{-- Desktop --}}

                    <div class="hidden overflow-x-auto md:block">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Installment
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Method
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Source
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($payments as $payment)

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Date --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                            {{ $formatter->date($payment->payment_date) }}
                                        </td>


                                        {{-- Installment --}}

                                        <td class="whitespace-nowrap px-6 py-4">

                                            @if ($payment->installment)

                                                <div>

                                                    <p class="text-sm font-semibold text-gray-800">
                                                        #{{ $payment->installment->installment_number }}
                                                    </p>

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        Due {{ $formatter->date($payment->installment->due_date) }}
                                                    </p>

                                                </div>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    Unassigned
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Amount --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <span class="font-bold text-emerald-600">
                                                {{ $formatter->money($payment->amount) }}
                                            </span>

                                        </td>


                                        {{-- Method --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                            {{ $payment->payment_method ?: '—' }}

                                        </td>


                                        {{-- Source --}}

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                            {{ $payment->payment_source ?: '—' }}

                                        </td>


                                        {{-- Action --}}

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
                                                    class="text-sm font-semibold text-rose-600 transition hover:text-rose-800"
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


                    {{-- Mobile --}}

                    <div class="divide-y divide-gray-100 md:hidden">

                        @foreach ($payments as $payment)

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $formatter->date($payment->payment_date) }}
                                        </p>

                                        @if ($payment->installment)

                                            <p class="mt-1 text-xs text-gray-400">
                                                Installment #{{ $payment->installment->installment_number }}
                                            </p>

                                        @endif

                                    </div>


                                    <p class="text-lg font-bold text-emerald-600">
                                        {{ $formatter->money($payment->amount) }}
                                    </p>

                                </div>


                                <div class="mt-3 flex flex-wrap gap-2">

                                    @if ($payment->payment_method)

                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            {{ $payment->payment_method }}
                                        </span>

                                    @endif


                                    @if ($payment->payment_source)

                                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-600">
                                            {{ $payment->payment_source }}
                                        </span>

                                    @endif

                                </div>


                                @if ($payment->reference_number)

                                    <p class="mt-3 text-sm text-gray-600">

                                        <span class="font-medium">
                                            Reference:
                                        </span>

                                        {{ $payment->reference_number }}

                                    </p>

                                @endif


                                @if ($payment->notes)

                                    <p class="mt-2 text-sm text-gray-600">
                                        {{ $payment->notes }}
                                    </p>

                                @endif


                                <form
                                    method="POST"
                                    action="{{ route('loan-payments.destroy', $payment) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this payment?');"
                                    class="mt-4"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-sm font-semibold text-rose-600 hover:text-rose-800"
                                    >
                                        Delete Payment
                                    </button>

                                </form>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-2xl">
                            💳
                        </div>

                        <h4 class="mt-4 font-semibold text-gray-900">
                            No payments yet
                        </h4>

                        <p class="mt-1 text-sm text-gray-500">
                            No payments have been recorded for this loan.
                        </p>

                        @if ($loan->remaining_balance > 0)

                            <a
                                href="{{ route('loan-payments.create', $loan) }}"
                                class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                + Record First Payment
                            </a>

                        @endif

                    </div>

                @endif

            </div>


            {{-- ======================================================= --}}
            {{-- LOAN DETAILS --}}
            {{-- ======================================================= --}}

            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-100 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Loan Details
                    </h3>

                </div>


                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-4">

                    <div>

                        <p class="text-xs font-medium text-gray-400">
                            Lender
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $loan->lender ?: 'Not specified' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium text-gray-400">
                            Start Date
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $formatter->date($loan->start_date) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium text-gray-400">
                            Final Due Date
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $loan->due_date ? $formatter->date($loan->due_date) : 'Not specified' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium text-gray-400">
                            Interest Type
                        </p>

                        <p class="mt-1 font-semibold capitalize text-gray-800">
                            {{ $loan->interest_type ?: 'None' }}
                        </p>

                    </div>

                </div>


                @if ($loan->notes)

                    <div class="border-t border-gray-100 px-6 py-5">

                        <p class="text-xs font-medium text-gray-400">
                            Notes
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $loan->notes }}
                        </p>

                    </div>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>
```
