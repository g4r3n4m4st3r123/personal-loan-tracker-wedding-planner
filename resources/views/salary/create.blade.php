<x-app-layout>

    <x-slot name="header">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Finance
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Add Salary
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Record your current or future salary.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">


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
            {{-- ADD SALARY --}}
            {{-- ========================================================= --}}

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">


                {{-- Header --}}

                <div class="mb-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                            💰
                        </div>

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Salary Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Enter the details of your salary.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Form --}}

                <form
                    method="POST"
                    action="{{ route('salary.store') }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- SALARY AMOUNT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="amount"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Salary Amount
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
                                placeholder="20000.00"
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                        <p class="mt-1 text-xs text-gray-400">
                            Enter the salary amount you receive for this period.
                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EFFECTIVE DATE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="effective_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            id="effective_date"
                            value="{{ old('effective_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <p class="mt-1 text-xs text-gray-400">
                            Date when this salary becomes effective.
                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SALARY TYPE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="salary_type"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Salary Type
                        </label>

                        <select
                            name="salary_type"
                            id="salary_type"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option
                                value="monthly"
                                {{ old('salary_type', 'monthly') === 'monthly' ? 'selected' : '' }}
                            >
                                Monthly
                            </option>

                            <option
                                value="semi-monthly"
                                {{ old('salary_type') === 'semi-monthly' ? 'selected' : '' }}
                            >
                                Semi-Monthly
                            </option>

                            <option
                                value="bi-weekly"
                                {{ old('salary_type') === 'bi-weekly' ? 'selected' : '' }}
                            >
                                Bi-Weekly
                            </option>

                            <option
                                value="weekly"
                                {{ old('salary_type') === 'weekly' ? 'selected' : '' }}
                            >
                                Weekly
                            </option>

                        </select>

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

                        <input
                            type="text"
                            name="notes"
                            id="notes"
                            value="{{ old('notes') }}"
                            placeholder="e.g. New salary effective this month"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- ================================================= --}}
                    {{-- AUTOMATIC DEDUCTION INFO --}}
                    {{-- ================================================= --}}

                    <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">

                        <div class="flex gap-3">

                            <div class="text-lg">
                                💡
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-indigo-800">
                                    Automatic Loan Tracking
                                </p>

                                <p class="mt-1 text-xs leading-5 text-indigo-700">
                                    Loan payments recorded using the Salary Deduction
                                    payment method will automatically reduce the
                                    available salary for the applicable period.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-end">

                        <a
                            href="{{ route('salary.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Save Salary
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>