<x-app-layout>

<x-slot name="header">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        {{-- Page Title --}}
        <div>

            <p class="text-sm font-medium text-gray-500">
                Wedding Planner
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Wedding Expenses
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Track actual wedding spending and connect expenses to your budget categories.
            </p>

        </div>


        {{-- Header Actions --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- Add Expense --}}
            <button
                type="button"
                onclick="openAddExpenseModal()"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                <svg
                    class="mr-2 h-4 w-4"
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

                Add Expense
            </button>

<!-- 
            {{-- Budget --}}
            <a
                href="{{ route('wedding.budget') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                Budget
            </a>


            {{-- Wedding Overview --}}
            <a
                href="{{ route('wedding.index') }}"
                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Wedding Overview
            </a> -->

        </div>

    </div>

</x-slot>


<div class="py-8">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if (session('success'))

            <div class="mb-6 flex items-start justify-between rounded-xl border border-green-200 bg-green-50 p-4">

                <div class="flex items-start">

                    <svg
                        class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-green-600"
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


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="flex items-start">

                    <svg
                        class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <div>

                        <p class="font-semibold text-red-800">
                            Please fix the following:
                        </p>

                        <ul class="mt-2 list-inside list-disc text-sm text-red-700">

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


        {{-- ========================================================= --}}
        {{-- SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Total Wedding Expenses --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Expenses
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $formatter->money($totalExpenses) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            All recorded wedding expenses
                        </p>

                    </div>

                    <div class="rounded-xl bg-gray-100 p-2.5">

                        <svg
                            class="h-5 w-5 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 14l6-6m2 8h.01M7 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- Paid --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Paid
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-600">
                            {{ $formatter->money($paidExpenses) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Expenses already paid
                        </p>

                    </div>

                    <div class="rounded-xl bg-emerald-50 p-2.5">

                        <svg
                            class="h-5 w-5 text-emerald-600"
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

                    </div>

                </div>

            </div>


            {{-- Pending --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Pending
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-600">
                            {{ $formatter->money($pendingExpenses) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Outstanding expenses
                        </p>

                    </div>

                    <div class="rounded-xl bg-amber-50 p-2.5">

                        <svg
                            class="h-5 w-5 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- This Month --}}
            <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-indigo-700">
                            This Month
                        </p>

                        <p class="mt-2 text-2xl font-bold text-indigo-700">
                            {{ $formatter->money($monthlyExpenses) }}
                        </p>

                        <p class="mt-1 text-xs text-indigo-600">
                            Wedding spending this month
                        </p>

                    </div>

                    <div class="rounded-xl bg-white/70 p-2.5">

                        <svg
                            class="h-5 w-5 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CATEGORY BREAKDOWN --}}
        {{-- ========================================================= --}}

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="mb-6">

                <h3 class="text-lg font-bold text-gray-900">
                    Spending by Budget Category
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Actual wedding spending grouped by your budget categories.
                </p>

            </div>


            @if ($categoryTotals->count())

                <div class="grid gap-5 md:grid-cols-2">

                    @foreach ($categoryTotals as $category => $amount)

                        @php

                            $percentage = $totalExpenses > 0
                                ? ($amount / $totalExpenses) * 100
                                : 0;

                        @endphp

                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                            <div class="mb-2 flex items-center justify-between">

                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $category }}
                                </span>

                                <span class="text-sm font-bold text-gray-900">
                                    {{ $formatter->money($amount) }}
                                </span>

                            </div>


                            <div class="h-2 overflow-hidden rounded-full bg-gray-200">

                                <div
                                    class="h-full rounded-full bg-indigo-500 transition-all"
                                    style="width: {{ min(100, max(0, $percentage)) }}%"
                                ></div>

                            </div>


                            <p class="mt-2 text-xs text-gray-400">
                                {{ number_format($percentage, 1) }}% of total spending
                            </p>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center">

                    <div class="text-4xl">
                        💸
                    </div>

                    <p class="mt-3 text-sm font-semibold text-gray-700">
                        No wedding expenses yet
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Add your first expense using the button above.
                    </p>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- EXPENSE HISTORY --}}
        {{-- ========================================================= --}}

        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Wedding Expense History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        All recorded wedding spending.
                    </p>

                </div>

            </div>


            @if ($expenses->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Expense
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Budget Category
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($expenses as $expense)

                                <tr class="transition hover:bg-gray-50">


                                    {{-- Expense --}}
                                    <td class="px-6 py-4">

                                        <p class="font-semibold text-gray-900">
                                            {{ $expense->expense_name }}
                                        </p>

                                        @if ($expense->notes)

                                            <p class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                                {{ $expense->notes }}
                                            </p>

                                        @endif

                                    </td>


                                    {{-- Budget Category --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">

                                            {{ $expense->budget?->category ?? 'Uncategorized' }}

                                        </span>

                                    </td>


                                    {{-- Amount --}}
                                    <td class="px-6 py-4 text-right">

                                        <span class="font-semibold text-rose-600">
                                            {{ $formatter->money($expense->amount) }}
                                        </span>

                                    </td>


                                    {{-- Date --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">

                                        {{ $formatter->date($expense->expense_date) }}

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @if ($expense->payment_status === 'paid')

                                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Paid
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="px-6 py-4 text-right">

                                        <div class="flex items-center justify-end gap-2 whitespace-nowrap">


                                            {{-- Edit --}}
                                            <button
                                                type="button"
                                                onclick="openEditExpenseModal({{ $expense->id }})"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                            >
                                                Edit
                                            </button>


                                            {{-- Delete --}}
                                            <form
                                                method="POST"
                                                action="{{ route('wedding.expenses.destroy', $expense) }}"
                                                onsubmit="return confirm('Delete this wedding expense?');"
                                                class="inline-flex"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ========================================================= --}}
                                {{-- EDIT EXPENSE MODAL --}}
                                {{-- ========================================================= --}}

                                <div
                                    id="edit-expense-{{ $expense->id }}"
                                    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8"
                                >

                                    <div
                                        class="mx-auto w-full max-w-2xl rounded-2xl bg-white shadow-2xl"
                                        onclick="event.stopPropagation()"
                                    >

                                        {{-- Modal Header --}}
                                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                                            <div>

                                                <h3 class="text-lg font-bold text-gray-900">
                                                    Edit Wedding Expense
                                                </h3>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Update the expense details and payment status.
                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="closeEditExpenseModal({{ $expense->id }})"
                                                class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                            >
                                                ✕
                                            </button>

                                        </div>


                                        {{-- Form --}}
                                        <form
                                            method="POST"
                                            action="{{ route('wedding.expenses.update', $expense) }}"
                                            class="p-6"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <div class="grid gap-5 md:grid-cols-2">


                                                {{-- Expense Name --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Expense Name
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="expense_name"
                                                        value="{{ $expense->expense_name }}"
                                                        required
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                </div>


                                                {{-- Budget Category --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Budget Category
                                                    </label>

                                                    <select
                                                        name="wedding_budget_id"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option value="">
                                                            Uncategorized
                                                        </option>

                                                        @foreach ($budgets as $budget)

                                                            <option
                                                                value="{{ $budget->id }}"
                                                                @selected($expense->wedding_budget_id == $budget->id)
                                                            >
                                                                {{ $budget->category }}
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- Amount --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Amount
                                                    </label>

                                                    <div class="relative mt-2">

                                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                                            {{ $formatter->symbol() }}
                                                        </span>

                                                        <input
                                                            type="number"
                                                            name="amount"
                                                            value="{{ $expense->amount }}"
                                                            min="0.01"
                                                            step="0.01"
                                                            required
                                                            class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        >

                                                    </div>

                                                </div>


                                                {{-- Date --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Expense Date
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="expense_date"
                                                        value="{{ $expense->expense_date->format('Y-m-d') }}"
                                                        required
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                </div>


                                                {{-- Payment Status --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Payment Status
                                                    </label>

                                                    <select
                                                        name="payment_status"
                                                        required
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option
                                                            value="paid"
                                                            @selected($expense->payment_status === 'paid')
                                                        >
                                                            Paid
                                                        </option>

                                                        <option
                                                            value="pending"
                                                            @selected($expense->payment_status === 'pending')
                                                        >
                                                            Pending
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- Payment Method --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Payment Method
                                                    </label>

                                                    <select
                                                        name="payment_method"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option value="">
                                                            Not specified
                                                        </option>

                                                        @foreach ([
                                                            'Cash',
                                                            'Bank Transfer',
                                                            'GCash',
                                                            'Maya',
                                                            'Credit Card',
                                                            'Other'
                                                        ] as $method)

                                                            <option
                                                                value="{{ $method }}"
                                                                @selected($expense->payment_method === $method)
                                                            >
                                                                {{ $method }}
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- Notes --}}
                                                <div class="md:col-span-2">

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Notes
                                                        <span class="font-normal text-gray-400">
                                                            (Optional)
                                                        </span>
                                                    </label>

                                                    <textarea
                                                        name="notes"
                                                        rows="3"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >{{ $expense->notes }}</textarea>

                                                </div>

                                            </div>


                                            {{-- Buttons --}}
                                            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">

                                                <button
                                                    type="button"
                                                    onclick="closeEditExpenseModal({{ $expense->id }})"
                                                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                                >
                                                    Save Changes
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </tbody>


                        {{-- Total --}}
                        <tfoot class="border-t border-gray-200 bg-gray-50">

                            <tr>

                                <td
                                    colspan="2"
                                    class="px-6 py-4 font-bold text-gray-900"
                                >
                                    Total
                                </td>

                                <td class="px-6 py-4 text-right font-bold text-rose-600">
                                    {{ $formatter->money($totalExpenses) }}
                                </td>

                                <td colspan="3"></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="px-6 py-14 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-2xl">
                        🧾
                    </div>

                    <h3 class="mt-4 font-bold text-gray-900">
                        No wedding expense records
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Start tracking your wedding spending by adding your first expense.
                    </p>

                    <button
                        type="button"
                        onclick="openAddExpenseModal()"
                        class="mt-5 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        + Add Expense
                    </button>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ADD EXPENSE MODAL --}}
{{-- ========================================================= --}}

<div
    id="add-expense-modal"
    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8"
    aria-hidden="true"
>

    <div
        class="mx-auto w-full max-w-2xl rounded-2xl bg-white shadow-2xl"
        onclick="event.stopPropagation()"
    >

        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50">

                        <svg
                            class="h-5 w-5 text-indigo-600"
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

                    </div>

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Add Wedding Expense
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Record an actual wedding expense.
                        </p>

                    </div>

                </div>

            </div>


            <button
                type="button"
                onclick="closeAddExpenseModal()"
                class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                aria-label="Close"
            >
                ✕
            </button>

        </div>


        {{-- Modal Form --}}
        <form
            method="POST"
            action="{{ route('wedding.expenses.store') }}"
            class="p-6"
        >

            @csrf


            <div class="grid gap-5 md:grid-cols-2">


                {{-- Expense Name --}}
                <div>

                    <label
                        for="modal_expense_name"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Expense Name
                    </label>

                    <input
                        type="text"
                        name="expense_name"
                        id="modal_expense_name"
                        value="{{ old('expense_name') }}"
                        required
                        placeholder="e.g. Catering Down Payment"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Budget Category --}}
                <div>

                    <label
                        for="modal_wedding_budget_id"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Budget Category
                    </label>

                    <select
                        name="wedding_budget_id"
                        id="modal_wedding_budget_id"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Uncategorized
                        </option>

                        @foreach ($budgets as $budget)

                            <option
                                value="{{ $budget->id }}"
                                @selected(old('wedding_budget_id') == $budget->id)
                            >
                                {{ $budget->category }}
                                — Planned {{ $formatter->money($budget->planned_amount) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Amount --}}
                <div>

                    <label
                        for="modal_amount"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Amount
                    </label>

                    <div class="relative mt-2">

                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            {{ $formatter->symbol() }}
                        </span>

                        <input
                            type="number"
                            name="amount"
                            id="modal_amount"
                            value="{{ old('amount') }}"
                            min="0.01"
                            step="0.01"
                            required
                            placeholder="10000.00"
                            class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                {{-- Expense Date --}}
                <div>

                    <label
                        for="modal_expense_date"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Expense Date
                    </label>

                    <input
                        type="date"
                        name="expense_date"
                        id="modal_expense_date"
                        value="{{ old('expense_date', now()->format('Y-m-d')) }}"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Payment Status --}}
                <div>

                    <label
                        for="modal_payment_status"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Payment Status
                    </label>

                    <select
                        name="payment_status"
                        id="modal_payment_status"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="paid"
                            @selected(old('payment_status', 'paid') === 'paid')
                        >
                            Paid
                        </option>

                        <option
                            value="pending"
                            @selected(old('payment_status') === 'pending')
                        >
                            Pending
                        </option>

                    </select>

                </div>


                {{-- Payment Method --}}
                <div>

                    <label
                        for="modal_payment_method"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Payment Method
                    </label>

                    <select
                        name="payment_method"
                        id="modal_payment_method"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Not specified
                        </option>

                        @foreach ([
                            'Cash',
                            'Bank Transfer',
                            'GCash',
                            'Maya',
                            'Credit Card',
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


                {{-- Notes --}}
                <div class="md:col-span-2">

                    <label
                        for="modal_notes"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Notes
                        <span class="font-normal text-gray-400">
                            (Optional)
                        </span>
                    </label>

                    <textarea
                        name="notes"
                        id="modal_notes"
                        rows="3"
                        placeholder="Add notes about this expense..."
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            {{-- Modal Footer --}}
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">

                <button
                    type="button"
                    onclick="closeAddExpenseModal()"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    Add Expense
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | Add Expense Modal
    |--------------------------------------------------------------------------
    */

    function openAddExpenseModal() {

        const modal = document.getElementById('add-expense-modal');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

        setTimeout(function () {

            const input = document.getElementById('modal_expense_name');

            if (input) {
                input.focus();
            }

        }, 100);

    }


    function closeAddExpenseModal() {

        const modal = document.getElementById('add-expense-modal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('overflow-hidden');

    }


    /*
    |--------------------------------------------------------------------------
    | Edit Expense Modal
    |--------------------------------------------------------------------------
    */

    function openEditExpenseModal(id) {

        const modal = document.getElementById('edit-expense-' + id);

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

    }


    function closeEditExpenseModal(id) {

        const modal = document.getElementById('edit-expense-' + id);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    /*
    |--------------------------------------------------------------------------
    | Close Add Modal When Clicking Backdrop
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        const addModal = document.getElementById('add-expense-modal');

        if (addModal) {

            addModal.addEventListener('click', function (event) {

                if (event.target === addModal) {

                    closeAddExpenseModal();

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Automatically Open Add Modal If Validation Failed
        |--------------------------------------------------------------------------
        */

        @if ($errors->any())

            openAddExpenseModal();

        @endif

    });


    /*
    |--------------------------------------------------------------------------
    | ESC Key
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') {
            return;
        }


        const addModal = document.getElementById('add-expense-modal');

        if (addModal && !addModal.classList.contains('hidden')) {

            closeAddExpenseModal();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Close Any Open Edit Modal
        |--------------------------------------------------------------------------
        */

        @foreach ($expenses as $expense)

            const editModal{{ $expense->id }} =
                document.getElementById('edit-expense-{{ $expense->id }}');

            if (
                editModal{{ $expense->id }} &&
                !editModal{{ $expense->id }}.classList.contains('hidden')
            ) {

                closeEditExpenseModal({{ $expense->id }});

            }

        @endforeach

    });

</script>

</x-app-layout>
