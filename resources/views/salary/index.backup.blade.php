<x-app-layout>

    <x-slot name="header">

        <div>
            <p class="text-sm font-medium text-gray-500">
                Finance
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Salary Management
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your salary and monitor how loan payments affect your available income.
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


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


            {{-- ========================================================= --}}
            {{-- SALARY OVERVIEW --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <div class="mb-4">

                    <h3 class="text-lg font-bold text-gray-900">
                        Salary Overview
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        See how much of your salary remains after loan deductions.
                    </p>

                </div>


                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    {{-- Current Salary --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Current Salary
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-900">

                                    @if ($currentSalary)

                                        ₱{{ number_format($currentSalary->amount, 2) }}

                                    @else

                                        ₱0.00

                                    @endif

                                </p>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                💰
                            </div>

                        </div>

                    </div>


                    {{-- Total Deductions --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Loan Deductions
                                </p>

                                <p class="mt-2 text-2xl font-bold text-red-600">
                                    ₱{{ number_format($totalDeductions, 2) }}
                                </p>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-xl">
                                📉
                            </div>

                        </div>

                    </div>


                    {{-- Available Salary --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Available Salary
                                </p>

                                <p class="mt-2 text-2xl font-bold text-emerald-600">
                                    ₱{{ number_format($availableSalary, 2) }}
                                </p>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                                💵
                            </div>

                        </div>

                    </div>


                    {{-- Deduction Percentage --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Salary Deduction
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-900">
                                    {{ number_format($deductionPercentage, 1) }}%
                                </p>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                📊
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SALARY HEALTH --}}
            {{-- ========================================================= --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Salary Health
                        </p>

                        @if ($salaryStatus === 'healthy')

                            <h3 class="mt-1 text-xl font-bold text-emerald-600">
                                Healthy
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Your loan deductions are currently at a manageable level.
                            </p>

                        @elseif ($salaryStatus === 'moderate')

                            <h3 class="mt-1 text-xl font-bold text-amber-600">
                                Moderate
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                A significant portion of your salary is going toward loan payments.
                            </p>

                        @else

                            <h3 class="mt-1 text-xl font-bold text-red-600">
                                High Deduction
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                More than half of your salary is currently being used for loan payments.
                            </p>

                        @endif

                    </div>


                    <div class="w-full sm:max-w-md">

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-xs font-medium text-gray-500">
                                Deduction Rate
                            </span>

                            <span class="text-sm font-bold text-gray-800">
                                {{ number_format($deductionPercentage, 1) }}%
                            </span>

                        </div>


                        <div class="h-3 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full transition-all
                                @if ($salaryStatus === 'healthy')
                                    bg-emerald-500
                                @elseif ($salaryStatus === 'moderate')
                                    bg-amber-500
                                @else
                                    bg-red-500
                                @endif"
                                style="width: {{ min(100, $deductionPercentage) }}%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SALARY DEDUCTIONS --}}
            {{-- ========================================================= --}}

            <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Loan Salary Deductions
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Payments recorded using the Salary Deduction payment method.
                    </p>

                </div>


                @if ($salaryDeductions->count())

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
                                        Payment Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Reference
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($salaryDeductions as $payment)

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


                                        <td class="px-6 py-4 text-sm text-gray-500">

                                            {{ $payment->reference_number ?: '—' }}

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
                            No salary deductions
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Loan payments recorded as Salary Deduction will appear here.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ========================================================= --}}
            {{-- CURRENT SALARY --}}
            {{-- ========================================================= --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Current Salary
                        </p>

                        @if ($currentSalary)

                            <p class="mt-1 text-3xl font-bold text-gray-900">
                                ₱{{ number_format($currentSalary->amount, 2) }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">

                                Effective
                                {{ $currentSalary->effective_date->format('M d, Y') }}

                                ·

                                {{ ucfirst(str_replace('-', ' ', $currentSalary->salary_type)) }}

                            </p>

                        @else

                            <p class="mt-1 text-2xl font-bold text-gray-400">
                                No salary recorded
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Add your salary to start tracking your available income.
                            </p>

                        @endif

                    </div>


                    <div class="rounded-xl bg-indigo-50 px-5 py-4">

                        <p class="text-sm font-medium text-indigo-600">
                            Automatic Tracking
                        </p>

                        <p class="mt-1 text-sm text-indigo-700">
                            Salary Deduction payments automatically reduce your available salary.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- ADD SALARY --}}
            {{-- ========================================================= --}}

            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Add Salary
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Record your current or future salary.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('salary.store') }}"
                    class="grid gap-5 md:grid-cols-2"
                >

                    @csrf


                    {{-- Amount --}}
                    <div>

                        <label
                            for="amount"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Salary Amount
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                ₱
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

                    </div>


                    {{-- Effective Date --}}
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

                    </div>


                    {{-- Salary Type --}}
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

                            <option value="monthly">
                                Monthly
                            </option>

                            <option value="semi-monthly">
                                Semi-Monthly
                            </option>

                            <option value="bi-weekly">
                                Bi-Weekly
                            </option>

                            <option value="weekly">
                                Weekly
                            </option>

                        </select>

                    </div>


                    {{-- Notes --}}
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


                    <div class="md:col-span-2">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Save Salary
                        </button>

                    </div>

                </form>

            </div>


            {{-- ========================================================= --}}
            {{-- SALARY HISTORY --}}
            {{-- ========================================================= --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Salary History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Your recorded salary history.
                    </p>

                </div>


                @if ($salaries->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Type
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Effective Date
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($salaries as $salary)

                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-6 py-4">

                                            <span class="font-semibold text-gray-900">
                                                ₱{{ number_format($salary->amount, 2) }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4">

                                            <span class="capitalize text-sm text-gray-600">
                                                {{ str_replace('-', ' ', $salary->salary_type) }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $salary->effective_date->format('M d, Y') }}

                                        </td>


                                        <td class="px-6 py-4 text-right">

                                            <form
                                                method="POST"
                                                action="{{ route('salary.destroy', $salary) }}"
                                                onsubmit="return confirm('Delete this salary record?');"
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
                            No salary records
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Add your salary above to start tracking your available income.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>