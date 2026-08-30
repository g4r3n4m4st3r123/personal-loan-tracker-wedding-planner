<x-app-layout>


<x-slot name="header">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Finance
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Income Management
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Track your side income, freelance earnings, and other sources of income.
            </p>

        </div>


        {{-- ADD INCOME BUTTON --}}

        <button
            type="button"
            onclick="openIncomeModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
        >

            <span class="text-lg leading-none">
                +
            </span>

            Add Income

        </button>

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

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- INCOME OVERVIEW --}}
        {{-- ========================================================= --}}

        <div class="mb-8">

            <div class="mb-4">

                <h3 class="text-lg font-bold text-gray-900">
                    Income Overview
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Monitor additional income that can help fund your loans and expenses.
                </p>

            </div>


            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                {{-- Total Income --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Total Other Income
                            </p>

                            <p class="mt-2 text-2xl font-bold text-indigo-600">
                                {{ $formatter->money($totalIncome) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                All additional income
                            </p>

                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                            💰
                        </div>

                    </div>

                </div>


                {{-- Side Income --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Side Income
                            </p>

                            <p class="mt-2 text-2xl font-bold text-emerald-600">
                                {{ $formatter->money($sideIncomeTotal) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Side hustles and extra work
                            </p>

                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                            💼
                        </div>

                    </div>

                </div>


                {{-- Freelance --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Freelance
                            </p>

                            <p class="mt-2 text-2xl font-bold text-purple-600">
                                {{ $formatter->money($freelanceIncomeTotal) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Freelance earnings
                            </p>

                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-xl">
                            💻
                        </div>

                    </div>

                </div>


                {{-- Other Income --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Other Income
                            </p>

                            <p class="mt-2 text-2xl font-bold text-amber-600">
                                {{ $formatter->money($otherIncomeTotal) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Other income sources
                            </p>

                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-xl">
                            💵
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INCOME HISTORY --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


            <div class="border-b border-gray-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Income History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Your additional income records.
                    </p>

                </div>

            </div>


            @if ($incomes->count())


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Income Type
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

                            @foreach ($incomes as $income)

                                <tr class="transition hover:bg-gray-50">


                                    <td class="px-6 py-4">

                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                            {{ $income->income_type }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-gray-900">
                                            {{ $formatter->money($income->amount) }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $formatter->date($income->income_date) }}

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        {{ $income->description ?: '—' }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        <form
                                            method="POST"
                                            action="{{ route('income.destroy', $income) }}"
                                            onsubmit="return confirm('Delete this income record?');"
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
                        💰
                    </div>

                    <h3 class="mt-4 font-bold text-gray-900">
                        No income records
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Start by adding your first side income, freelance income, or other income.
                    </p>

                    <button
                        type="button"
                        onclick="openIncomeModal()"
                        class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Add Your First Income
                    </button>

                </div>


            @endif

        </div>


    </div>

</div>


{{-- ========================================================= --}}
{{-- ADD INCOME MODAL --}}
{{-- ========================================================= --}}

<div
    id="incomeModal"
    class="fixed inset-0 z-50 hidden"
    aria-labelledby="incomeModalTitle"
    role="dialog"
    aria-modal="true"
>


    {{-- BACKDROP --}}

    <div
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
        onclick="closeIncomeModal()"
    ></div>


    {{-- MODAL CONTAINER --}}

    <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">

        <div
            class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
            onclick="event.stopPropagation()"
        >


            {{-- MODAL HEADER --}}

            <div class="flex items-start justify-between border-b border-gray-200 px-6 py-5">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Finance
                    </p>

                    <h3
                        id="incomeModalTitle"
                        class="mt-1 text-xl font-bold text-gray-900"
                    >
                        Add Income
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Record your side income, freelance earnings, or other income.
                    </p>

                </div>


                {{-- CLOSE BUTTON --}}

                <button
                    type="button"
                    onclick="closeIncomeModal()"
                    class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                    aria-label="Close"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>


            {{-- ========================================================= --}}
            {{-- FORM --}}
            {{-- ========================================================= --}}

            <form
                method="POST"
                action="{{ route('income.store') }}"
            >

                @csrf


                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">


                    {{-- INCOME TYPE --}}

                    <div>

                        <label
                            for="income_type"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Income Type
                        </label>

                        <select
                            name="income_type"
                            id="income_type"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                Select income type
                            </option>

                            <option
                                value="Side Income"
                                {{ old('income_type') === 'Side Income' ? 'selected' : '' }}
                            >
                                Side Income
                            </option>

                            <option
                                value="Freelance"
                                {{ old('income_type') === 'Freelance' ? 'selected' : '' }}
                            >
                                Freelance
                            </option>

                            <option
                                value="Other"
                                {{ old('income_type') === 'Other' ? 'selected' : '' }}
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- AMOUNT --}}

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
                                placeholder="5000.00"
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    {{-- INCOME DATE --}}

                    <div>

                        <label
                            for="income_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Income Date
                        </label>

                        <input
                            type="date"
                            name="income_date"
                            id="income_date"
                            value="{{ old('income_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- DESCRIPTION --}}

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
                            placeholder="e.g. Online selling"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- MODAL FOOTER --}}
                {{-- ========================================================= --}}

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="closeIncomeModal()"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Save Income
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

    function openIncomeModal() {

        const modal = document.getElementById('incomeModal');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(function () {

            const incomeType = document.getElementById('income_type');

            if (incomeType) {
                incomeType.focus();
            }

        }, 100);

    }


    function closeIncomeModal() {

        const modal = document.getElementById('incomeModal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    {{-- ESC KEY --}}

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeIncomeModal();

        }

    });


    {{-- OPEN MODAL AUTOMATICALLY WHEN VALIDATION FAILS --}}

    @if ($errors->any())

        document.addEventListener('DOMContentLoaded', function () {

            openIncomeModal();

        });

    @endif

</script>

</x-app-layout>
