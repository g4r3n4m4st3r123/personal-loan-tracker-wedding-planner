<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Finance
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Expense Management
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Track your spending and see how expenses affect your available money.
                </p>

            </div>


            {{-- Add Expense Button --}}

            <button
                type="button"
                @click="$dispatch('open-expense-modal')"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >

                <span class="text-lg leading-none">
                    +
                </span>

                Add Expense

            </button>

        </div>

    </x-slot>


    <div
        class="py-8"
        x-data="{ expenseModal: false }"
        @open-expense-modal.window="expenseModal = true"
        @keydown.escape.window="expenseModal = false"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- SUCCESS --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <p class="font-semibold text-red-800">
                        Please fix the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- EXPENSE OVERVIEW --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <div class="mb-4">

                    <h3 class="text-lg font-bold text-gray-900">
                        Expense Overview
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Monitor your spending and identify where your money goes.
                    </p>

                </div>


                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    {{-- Monthly Expenses --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    This Month
                                </p>

                                <p class="mt-2 text-2xl font-bold text-rose-600">
                                    {{ $formatter->money($monthlyExpenses) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Expenses recorded this month
                                </p>

                            </div>

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-xl">
                                💸
                            </div>

                        </div>

                    </div>


                    {{-- Total Expenses --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Total Expenses
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-900">
                                    {{ $formatter->money($totalExpenses) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    All recorded expenses
                                </p>

                            </div>

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-xl">
                                🧾
                            </div>

                        </div>

                    </div>


                    {{-- Expense Count --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Transactions
                                </p>

                                <p class="mt-2 text-2xl font-bold text-indigo-600">
                                    {{ $expenses->count() }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Recorded expense entries
                                </p>

                            </div>

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                📋
                            </div>

                        </div>

                    </div>


                    {{-- Highest Category --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <p class="text-sm font-medium text-gray-500">
                                    Highest Category
                                </p>


                                @if ($highestCategory)

                                    <p class="mt-2 truncate text-xl font-bold text-amber-600">
                                        {{ $highestCategory }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $formatter->money($highestCategoryAmount) }}
                                    </p>

                                @else

                                    <p class="mt-2 text-xl font-bold text-gray-400">
                                        No expenses
                                    </p>

                                @endif

                            </div>

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                📊
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- CATEGORY BREAKDOWN --}}
            {{-- ========================================================= --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Expense Breakdown
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        See how your recorded expenses are distributed by category.
                    </p>

                </div>


                @if ($categoryTotals->count())

                    <div class="space-y-4">

                        @foreach ($categoryTotals as $category => $amount)

                            @php

                                $percentage = $totalExpenses > 0
                                    ? ($amount / $totalExpenses) * 100
                                    : 0;

                            @endphp


                            <div>

                                <div class="mb-2 flex items-center justify-between">

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $category }}
                                    </span>

                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $formatter->money($amount) }}
                                    </span>

                                </div>


                                <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                    <div
                                        class="h-full rounded-full bg-indigo-500"
                                        style="width: {{ min(100, max(0, $percentage)) }}%"
                                    ></div>

                                </div>


                                <p class="mt-1 text-xs text-gray-400">
                                    {{ number_format($percentage, 1) }}%
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
                            No expense data yet
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Add your first expense above.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ========================================================= --}}
            {{-- EXPENSE HISTORY --}}
            {{-- ========================================================= --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Expense History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Your recorded expenses.
                    </p>

                </div>


                @if ($expenses->count())


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Category
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Description
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($expenses as $expense)

                                    <tr class="transition hover:bg-gray-50">


                                        <td class="px-6 py-4">

                                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                {{ $expense->category }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4">

                                            <span class="font-semibold text-red-600">
                                                {{ $formatter->money($expense->amount) }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $formatter->date($expense->expense_date) }}

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-500">

                                            {{ $expense->description ?: '—' }}

                                        </td>


                                        <td class="px-6 py-4 text-right">

                                            <form
                                                method="POST"
                                                action="{{ route('expenses.destroy', $expense) }}"
                                                onsubmit="return confirm('Delete this expense record?');"
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


                @else


                    <div class="px-6 py-12 text-center">

                        <div class="text-4xl">
                            🧾
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            No expense records
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Add your first expense above to start tracking your spending.
                        </p>

                        <button
                            type="button"
                            @click="expenseModal = true"
                            class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Add Your First Expense
                        </button>

                    </div>


                @endif

            </div>


        </div>


        {{-- ========================================================= --}}
        {{-- ADD EXPENSE MODAL --}}
        {{-- ========================================================= --}}

        <div
            x-show="expenseModal"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
        >

            {{-- Backdrop --}}

            <div
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
                @click="expenseModal = false"
            ></div>


            {{-- Modal Container --}}

            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">

                <div
                    x-show="expenseModal"
                    x-transition
                    @click.stop
                    class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                >


                    {{-- Modal Header --}}

                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">

                        <div>

                            <p class="text-sm font-medium text-indigo-600">
                                Finance
                            </p>

                            <h3 class="mt-1 text-xl font-bold text-gray-900">
                                Add Expense
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Record a new personal expense.
                            </p>

                        </div>


                        <button
                            type="button"
                            @click="expenseModal = false"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-2xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                        >
                            &times;
                        </button>

                    </div>


                    {{-- Modal Form --}}

                    <form
                        method="POST"
                        action="{{ route('expenses.store') }}"
                        class="p-6"
                    >

                        @csrf


                        <div class="grid gap-5 md:grid-cols-2">


                            {{-- Category --}}

                            <div>

                                <label
                                    for="category"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Category
                                </label>

                                <select
                                    name="category"
                                    id="category"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option value="">
                                        Select category
                                    </option>

                                    <option
                                        value="Food"
                                        {{ old('category') === 'Food' ? 'selected' : '' }}
                                    >
                                        Food
                                    </option>

                                    <option
                                        value="Transportation"
                                        {{ old('category') === 'Transportation' ? 'selected' : '' }}
                                    >
                                        Transportation
                                    </option>

                                    <option
                                        value="Bills"
                                        {{ old('category') === 'Bills' ? 'selected' : '' }}
                                    >
                                        Bills & Utilities
                                    </option>

                                    <option
                                        value="Shopping"
                                        {{ old('category') === 'Shopping' ? 'selected' : '' }}
                                    >
                                        Shopping
                                    </option>

                                    <option
                                        value="Healthcare"
                                        {{ old('category') === 'Healthcare' ? 'selected' : '' }}
                                    >
                                        Healthcare
                                    </option>

                                    <option
                                        value="Entertainment"
                                        {{ old('category') === 'Entertainment' ? 'selected' : '' }}
                                    >
                                        Entertainment
                                    </option>

                                    <option
                                        value="Housing"
                                        {{ old('category') === 'Housing' ? 'selected' : '' }}
                                    >
                                        Housing
                                    </option>

                                    <option
                                        value="Education"
                                        {{ old('category') === 'Education' ? 'selected' : '' }}
                                    >
                                        Education
                                    </option>

                                    <option
                                        value="Wedding"
                                        {{ old('category') === 'Wedding' ? 'selected' : '' }}
                                    >
                                        Wedding
                                    </option>

                                    <option
                                        value="Other"
                                        {{ old('category') === 'Other' ? 'selected' : '' }}
                                    >
                                        Other
                                    </option>

                                </select>

                            </div>


                            {{-- Amount --}}

                            <div>

                                <label
                                    for="amount"
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
                                        id="amount"
                                        value="{{ old('amount') }}"
                                        min="0.01"
                                        step="0.01"
                                        required
                                        placeholder="1000.00"
                                        class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>

                            </div>


                            {{-- Date --}}

                            <div>

                                <label
                                    for="expense_date"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Expense Date
                                </label>

                                <input
                                    type="date"
                                    name="expense_date"
                                    id="expense_date"
                                    value="{{ old('expense_date', now()->format('Y-m-d')) }}"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Description --}}

                            <div>

                                <label
                                    for="description"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Description

                                    <span class="font-normal text-gray-400">
                                        (Optional)
                                    </span>

                                </label>


                                <input
                                    type="text"
                                    name="description"
                                    id="description"
                                    value="{{ old('description') }}"
                                    placeholder="e.g. Grocery shopping"
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>

                        </div>


                        {{-- Modal Footer --}}

                        <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">

                            <button
                                type="button"
                                @click="expenseModal = false"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                            >
                                Cancel
                            </button>


                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                            >
                                Save Expense
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>