<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Finance
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Add Expense
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Record a new personal expense.
                </p>

            </div>

            <a
                href="{{ route('expenses.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                ← Back to Expenses
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">


            {{-- Validation Errors --}}

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


            {{-- Add Expense Card --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-xl">
                            💸
                        </div>

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Expense Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Enter the details of your expense.
                            </p>

                        </div>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('expenses.store') }}"
                    class="p-6"
                >

                    @csrf


                    <div class="space-y-5">


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


                    {{-- Actions --}}

                    <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('expenses.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

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

    </div>

</x-app-layout>