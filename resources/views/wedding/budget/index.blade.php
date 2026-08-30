<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Wedding Budget
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Plan your wedding spending and track actual costs.
                </p>

            </div>

            <div class="flex flex-wrap gap-2">

<!--                 <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                >
                    ← Wedding Overview
                </a> -->

                {{-- ADD BUDGET BUTTON --}}
                <button
                    type="button"
                    onclick="document.getElementById('add-budget-modal').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <span class="text-lg leading-none">+</span>
                    Add Budget
                </button>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

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
            {{-- BUDGET SUMMARY --}}
            {{-- ========================================================= --}}

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                {{-- Overall Wedding Budget --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Overall Wedding Budget
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $formatter->money($overallWeddingBudget) }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Budget from wedding overview
                    </p>

                </div>


                {{-- Total Planned --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Total Planned
                    </p>

                    <p class="mt-2 text-2xl font-bold text-indigo-600">
                        {{ $formatter->money($totalPlanned) }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Allocated across categories
                    </p>

                </div>


                {{-- Actual Spending --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Actual Spending
                    </p>

                    <p class="mt-2 text-2xl font-bold text-rose-600">
                        {{ $formatter->money($totalActual) }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Current recorded spending
                    </p>

                </div>


                {{-- Remaining --}}

                <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                    <p class="text-sm font-medium text-emerald-700">
                        Budget Remaining
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $formatter->money($totalRemaining) }}
                    </p>

                    <p class="mt-1 text-xs text-emerald-600">
                        {{ number_format($budgetUsagePercentage, 1) }}% of planned budget used
                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- OVERALL BUDGET PROGRESS --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Budget Progress
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Actual spending compared with your planned category budget.
                        </p>

                    </div>

                    <div class="text-left sm:text-right">

                        <p class="text-2xl font-bold text-indigo-600">
                            {{ number_format($budgetUsagePercentage, 1) }}%
                        </p>

                        <p class="text-xs text-gray-400">
                            Used
                        </p>

                    </div>

                </div>


                <div class="mt-5 h-4 overflow-hidden rounded-full bg-gray-100">

                    <div
                        class="h-full rounded-full transition-all
                        @if ($budgetUsagePercentage >= 100)
                            bg-red-500
                        @elseif ($budgetUsagePercentage >= 80)
                            bg-amber-500
                        @else
                            bg-emerald-500
                        @endif"
                        style="width: {{ min(100, max(0, $budgetUsagePercentage)) }}%"
                    ></div>

                </div>


                <div class="mt-4 grid gap-4 sm:grid-cols-3">

                    <div>

                        <p class="text-xs text-gray-400">
                            Wedding Budget
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $formatter->money($overallWeddingBudget) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-gray-400">
                            Unallocated
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $formatter->money($plannedVsWeddingBudgetRemaining) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-gray-400">
                            Budget Remaining
                        </p>

                        <p class="mt-1 font-semibold text-emerald-600">
                            {{ $formatter->money($actualVsWeddingBudgetRemaining) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- BUDGET CATEGORY TABLE --}}
            {{-- ========================================================= --}}

            <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-4 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Budget Categories
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Manage your planned and actual wedding spending.
                        </p>

                    </div>


                    {{-- SECOND ADD BUTTON --}}

                    <button
                        type="button"
                        onclick="document.getElementById('add-budget-modal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        <span class="text-lg leading-none">+</span>
                        Add Budget Category
                    </button>

                </div>


                @if ($budgets->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Category
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Planned
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Actual
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Remaining
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Usage
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($budgets as $budget)

                                    <tr class="transition hover:bg-gray-50">


                                        {{-- CATEGORY --}}

                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-900">
                                                {{ $budget->category }}
                                            </p>

                                            @if ($budget->notes)

                                                <p class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                                    {{ $budget->notes }}
                                                </p>

                                            @endif

                                        </td>


                                        {{-- PLANNED --}}

                                        <td class="whitespace-nowrap px-6 py-5 text-right">

                                            <span class="font-semibold text-gray-900">
                                                {{ $formatter->money($budget->planned_amount) }}
                                            </span>

                                        </td>


                                        {{-- ACTUAL --}}

                                        <td class="whitespace-nowrap px-6 py-5 text-right">

                                            <span class="font-semibold text-rose-600">
                                                {{ $formatter->money($budget->actual_amount) }}
                                            </span>

                                        </td>


                                        {{-- REMAINING --}}

                                        <td class="whitespace-nowrap px-6 py-5 text-right">

                                            <span
                                                class="font-semibold
                                                {{ $budget->remaining_amount <= 0
                                                    ? 'text-red-600'
                                                    : 'text-emerald-600' }}"
                                            >
                                                {{ $formatter->money($budget->remaining_amount) }}
                                            </span>

                                        </td>


                                        {{-- USAGE --}}

                                        <td class="px-6 py-5">

                                            <div class="min-w-[130px]">

                                                <div class="mb-1 flex items-center justify-between text-xs">

                                                    <span class="text-gray-400">
                                                        Used
                                                    </span>

                                                    <span class="font-semibold text-gray-700">
                                                        {{ number_format($budget->usage_percentage, 1) }}%
                                                    </span>

                                                </div>


                                                <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                                    <div
                                                        class="h-full rounded-full
                                                        {{ $budget->usage_percentage >= 100
                                                            ? 'bg-red-500'
                                                            : ($budget->usage_percentage >= 80
                                                                ? 'bg-amber-500'
                                                                : 'bg-emerald-500') }}"
                                                        style="width: {{ min(100, max(0, $budget->usage_percentage)) }}%"
                                                    ></div>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- ACTIONS --}}

                                        <td class="px-6 py-5">

                                            <div class="flex items-center justify-center gap-2 whitespace-nowrap">

                                                {{-- EDIT BUTTON --}}

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-budget-{{ $budget->id }}').classList.remove('hidden')"
                                                    class="inline-flex h-9 min-w-[60px] items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                                >
                                                    Edit
                                                </button>


                                                {{-- DELETE BUTTON --}}

                                                <form
                                                    method="POST"
                                                    action="{{ route('wedding.budget.destroy', $budget) }}"
                                                    onsubmit="return confirm('Delete this budget category?');"
                                                    class="m-0 inline-flex"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex h-9 min-w-[60px] items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>


                                    {{-- ================================================= --}}
                                    {{-- EDIT BUDGET MODAL --}}
                                    {{-- ================================================= --}}

                                    <div
                                        id="edit-budget-{{ $budget->id }}"
                                        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
                                    >

                                        <div
                                            class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                            onclick="event.stopPropagation()"
                                        >

                                            {{-- Modal Header --}}

                                            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                                                <div>

                                                    <p class="text-sm font-medium text-indigo-600">
                                                        Budget Management
                                                    </p>

                                                    <h3 class="mt-1 text-xl font-bold text-gray-900">
                                                        Edit Budget Category
                                                    </h3>

                                                </div>


                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-budget-{{ $budget->id }}').classList.add('hidden')"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                                                >
                                                    ✕
                                                </button>

                                            </div>


                                            {{-- Edit Form --}}

                                            <form
                                                method="POST"
                                                action="{{ route('wedding.budget.update', $budget) }}"
                                                class="p-6"
                                            >

                                                @csrf

                                                @method('PATCH')


                                                <div class="grid gap-5 md:grid-cols-2">


                                                    {{-- Category --}}

                                                    <div>

                                                        <label class="block text-sm font-semibold text-gray-700">
                                                            Category
                                                        </label>

                                                        <select
                                                            name="category"
                                                            required
                                                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        >

                                                            @foreach ([
                                                                'Venue',
                                                                'Catering',
                                                                'Attire',
                                                                'Photography',
                                                                'Decoration',
                                                                'Invitations',
                                                                'Rings',
                                                                'Transportation',
                                                                'Entertainment',
                                                                'Other'
                                                            ] as $category)

                                                                <option
                                                                    value="{{ $category }}"
                                                                    @selected($budget->category === $category)
                                                                >
                                                                    {{ $category }}
                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    {{-- Planned Amount --}}

                                                    <div>

                                                        <label class="block text-sm font-semibold text-gray-700">
                                                            Planned Amount
                                                        </label>

                                                        <div class="relative mt-2">

                                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                                                {{ $formatter->symbol() }}
                                                            </span>

                                                            <input
                                                                type="number"
                                                                name="planned_amount"
                                                                value="{{ $budget->planned_amount }}"
                                                                min="0"
                                                                step="0.01"
                                                                required
                                                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                            >

                                                        </div>

                                                    </div>


                                                    {{-- Notes --}}

                                                    <div class="md:col-span-2">

                                                        <label class="block text-sm font-semibold text-gray-700">
                                                            Notes
                                                            <span class="font-normal text-gray-400">
                                                                (Optional)
                                                            </span>
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="notes"
                                                            value="{{ $budget->notes }}"
                                                            placeholder="Budget notes..."
                                                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        >

                                                    </div>

                                                </div>


                                                {{-- Buttons --}}

                                                <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">

                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('edit-budget-{{ $budget->id }}').classList.add('hidden')"
                                                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                                    >
                                                        Cancel
                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                                    >
                                                        Save Changes
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                @endforeach

                            </tbody>


                            {{-- FOOTER TOTALS --}}

                            <tfoot class="border-t border-gray-200 bg-gray-50">

                                <tr>

                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        Total
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        {{ $formatter->money($totalPlanned) }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-rose-600">
                                        {{ $formatter->money($totalActual) }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                        {{ $formatter->money($totalRemaining) }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-700">
                                        {{ number_format($budgetUsagePercentage, 1) }}%
                                    </td>

                                    <td></td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-14 text-center">

                        <div class="text-4xl">
                            💍
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            No budget categories yet
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Start organizing your wedding budget by adding your first category.
                        </p>


                        <button
                            type="button"
                            onclick="document.getElementById('add-budget-modal').classList.remove('hidden')"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <span class="text-lg leading-none">+</span>
                            Add Budget Category
                        </button>

                    </div>

                @endif

            </div>


        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- ADD BUDGET MODAL --}}
    {{-- ============================================================= --}}

    <div
        id="add-budget-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
        onclick="if(event.target === this) this.classList.add('hidden')"
    >

        <div
            class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
            onclick="event.stopPropagation()"
        >

            {{-- Modal Header --}}

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Wedding Budget
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-gray-900">
                        Add Budget Category
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Set the planned amount for a wedding expense category.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="document.getElementById('add-budget-modal').classList.add('hidden')"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                >
                    ✕
                </button>

            </div>


            {{-- Add Form --}}

            <form
                method="POST"
                action="{{ route('wedding.budget.store') }}"
                class="p-6"
            >

                @csrf


                <div class="grid gap-5 md:grid-cols-2">


                    {{-- Category --}}

                    <div>

                        <label
                            for="modal_category"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Category
                        </label>

                        <select
                            name="category"
                            id="modal_category"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                Select category
                            </option>

                            @foreach ([
                                'Venue',
                                'Catering',
                                'Attire',
                                'Photography',
                                'Decoration',
                                'Invitations',
                                'Rings',
                                'Transportation',
                                'Entertainment',
                                'Other'
                            ] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(old('category') === $category)
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Planned Amount --}}

                    <div>

                        <label
                            for="modal_planned_amount"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Planned Amount
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                {{ $formatter->symbol() }}
                            </span>

                            <input
                                type="number"
                                name="planned_amount"
                                id="modal_planned_amount"
                                value="{{ old('planned_amount') }}"
                                min="0"
                                step="0.01"
                                required
                                placeholder="40000.00"
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    {{-- Actual Amount --}}

                    <div>

                        <label class="block text-sm font-semibold text-gray-700">
                            Actual Amount Spent
                        </label>

                        <div class="mt-2 rounded-lg bg-gray-50 px-4 py-3">

                            <p class="text-sm text-gray-500">
                                Actual spending is automatically calculated from your paid wedding expenses.
                            </p>

                        </div>

                    </div>


                    {{-- Notes --}}

                    <div>

                        <label
                            for="modal_notes"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Notes
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            name="notes"
                            id="modal_notes"
                            value="{{ old('notes') }}"
                            placeholder="e.g. Package includes food and drinks"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                {{-- Modal Buttons --}}

                <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">

                    <button
                        type="button"
                        onclick="document.getElementById('add-budget-modal').classList.add('hidden')"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        <span>+</span>
                        Add Budget Category
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MODAL JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>

        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                const modal = document.getElementById('add-budget-modal');

                if (modal) {
                    modal.classList.add('hidden');
                }

                document.querySelectorAll('[id^="edit-budget-"]').forEach(function (editModal) {
                    editModal.classList.add('hidden');
                });

            }

        });

    </script>


</x-app-layout>
