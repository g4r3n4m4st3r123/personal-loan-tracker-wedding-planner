<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Finance
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Add Income
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Record a new side income, freelance earning, or other income.
                </p>

            </div>


            <a
                href="{{ route('income.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                ← Back to Income
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

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ADD INCOME FORM --}}
            {{-- ========================================================= --}}

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">


                <div class="mb-8">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-2xl">
                        💰
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-gray-900">
                        Income Details
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Enter the details of the income you received.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('income.store') }}"
                    class="space-y-6"
                >

                    @csrf


                    {{-- Income Type --}}

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
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                Select income type
                            </option>

                            <option
                                value="Side Income"
                                {{ old('income_type') === 'Side Income' ? 'selected' : '' }}
                            >
                                Side Income / Side Hustle
                            </option>

                            <option
                                value="Freelance"
                                {{ old('income_type') === 'Freelance' ? 'selected' : '' }}
                            >
                                Freelance
                            </option>

                            <option
                                value="Other Income"
                                {{ old('income_type') === 'Other Income' ? 'selected' : '' }}
                            >
                                Other Income
                            </option>

                        </select>

                        @error('income_type')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

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

                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
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
                                class="block w-full rounded-xl border-gray-300 py-3 pl-9 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                        @error('amount')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Date --}}

                    <div>

                        <label
                            for="income_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Date Received
                        </label>

                        <input
                            type="date"
                            name="income_date"
                            id="income_date"
                            value="{{ old('income_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('income_date')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

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
                            maxlength="1000"
                            placeholder="e.g. Graphic design project"
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('description')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Actions --}}

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('income.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Add Income
                        </button>

                    </div>

                </form>

            </div>


        </div>

    </div>

</x-app-layout>