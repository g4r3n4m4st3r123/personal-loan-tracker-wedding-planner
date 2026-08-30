<x-app-layout>

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<x-slot name="header">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-indigo-600">
                Finance
            </p>

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                Salary Management
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Monitor your salary, loan deductions, and available income.
            </p>
        </div>

        {{-- ADD SALARY BUTTON --}}
        <button
            type="button"
            onclick="document.getElementById('salaryModal').classList.remove('hidden')"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <span class="text-lg leading-none">+</span>
            Add Salary
        </button>

    </div>

</x-slot>


<div class="py-8">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ========================================================= --}}

        @if (session('success'))

            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">

                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    ✓
                </div>

                <p class="text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </p>

            </div>

        @endif


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
        {{-- CURRENT SALARY HERO --}}
        {{-- ========================================================= --}}

        <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="p-6 sm:p-8">

                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                    {{-- CURRENT SALARY --}}

                    <div>

                        <div class="flex items-center gap-2">

                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-lg">
                                💰
                            </span>

                            <p class="text-sm font-semibold text-gray-500">
                                Current Salary
                            </p>

                        </div>


                        @if ($currentSalary)

                            <p class="mt-4 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                                {{ $formatter->money($currentSalary->salary_amount) }}
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-gray-500">

                                <span>
                                    {{ ucfirst(str_replace('-', ' ', $currentSalary->salary_type)) }}
                                </span>

                                <span class="text-gray-300">•</span>

                                <span>
                                    Received {{ $formatter->date($currentSalary->salary_date) }}
                                </span>

                            </div>

                        @else

                            <p class="mt-4 text-3xl font-bold text-gray-300">
                                No salary yet
                            </p>

                            <p class="mt-2 text-sm text-gray-500">
                                Add your salary to start tracking your available income.
                            </p>

                        @endif

                    </div>


                    {{-- AVAILABLE SALARY --}}

                    @if ($currentSalary)

                        <div class="rounded-2xl bg-emerald-50 px-6 py-5 lg:min-w-[280px]">

                            <p class="text-sm font-medium text-emerald-700">
                                Available After Loans
                            </p>

                            <p class="mt-2 text-3xl font-bold text-emerald-700">
                                {{ $formatter->money($currentAvailableSalary) }}
                            </p>

                            <p class="mt-2 text-xs text-emerald-600">
                                Salary minus current loan deductions
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- CURRENT SALARY PERIOD --}}

            @if ($currentSalary)

                <div class="border-t border-gray-100 bg-gray-50/70 px-6 py-4 sm:px-8">

                    <div class="flex flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <span class="font-medium text-gray-600">
                                Salary Period
                            </span>

                            <span class="ml-2 text-gray-500">
                                {{ $formatter->date($currentSalary->period_start) }}
                                –
                                {{ $formatter->date($currentSalary->period_end) }}
                            </span>

                        </div>

                        <div class="text-xs text-gray-400">
                            Automatic loan deduction tracking enabled
                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- FINANCIAL SNAPSHOT --}}
        {{-- ========================================================= --}}

        <div class="mb-8">

            <div class="mb-4">

                <h3 class="text-lg font-bold text-gray-900">
                    Financial Snapshot
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    A quick view of how your salary is being allocated.
                </p>

            </div>


            <div class="grid gap-4 md:grid-cols-3">


                {{-- LOAN DEDUCTIONS --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Loan Deductions
                            </p>

                            <p class="mt-2 text-2xl font-bold text-red-600">
                                {{ $formatter->money($totalLoanDeductions) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Salary-based loan payments
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50">
                            📉
                        </div>

                    </div>

                </div>


                {{-- REMAINING --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Total Remaining
                            </p>

                            <p class="mt-2 text-2xl font-bold text-emerald-600">
                                {{ $formatter->money($totalRemainingSalary) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Salary received minus deductions
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50">
                            💵
                        </div>

                    </div>

                </div>


                {{-- DEDUCTION RATE --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Deduction Rate
                            </p>

                            <p class="mt-2 text-2xl font-bold text-gray-900">
                                {{ number_format($deductionPercentage, 1) }}%
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Of salary received
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50">
                            📊
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SALARY HEALTH --}}
        {{-- ========================================================= --}}

        <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                <div>

                    <div class="flex items-center gap-3">

                        <p class="text-sm font-semibold text-gray-500">
                            Salary Health
                        </p>


                        @if ($salaryStatus === 'healthy')

                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Healthy
                            </span>

                        @elseif ($salaryStatus === 'moderate')

                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                Moderate
                            </span>

                        @else

                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                High Deduction
                            </span>

                        @endif

                    </div>


                    <p class="mt-2 text-sm text-gray-500">

                        @if ($salaryStatus === 'healthy')

                            Your loan deductions are currently at a manageable level.

                        @elseif ($salaryStatus === 'moderate')

                            A significant portion of your salary is going toward loan payments.

                        @else

                            More than half of your salary is currently being used for loan payments.

                        @endif

                    </p>

                </div>


                {{-- PROGRESS --}}

                <div class="w-full md:max-w-md">

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-xs font-medium text-gray-400">
                            Salary Used for Loans
                        </span>

                        <span class="text-sm font-bold text-gray-800">
                            {{ number_format($deductionPercentage, 1) }}%
                        </span>

                    </div>


                    <div class="h-2.5 overflow-hidden rounded-full bg-gray-100">

                        <div
                            class="h-full rounded-full transition-all
                            @if ($salaryStatus === 'healthy')
                                bg-emerald-500
                            @elseif ($salaryStatus === 'moderate')
                                bg-amber-500
                            @else
                                bg-red-500
                            @endif"
                            style="width: {{ min(100, max(0, $deductionPercentage)) }}%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LOAN DEDUCTIONS --}}
        {{-- ========================================================= --}}

        <div class="mb-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Loan Deductions
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Loan payments deducted directly from your salary.
                    </p>

                </div>


                @if ($salaryDeductions->count())

                    <span class="text-xs font-medium text-gray-400">
                        {{ $salaryDeductions->count() }}
                        {{ $salaryDeductions->count() === 1 ? 'payment' : 'payments' }}
                    </span>

                @endif

            </div>


            @if ($salaryDeductions->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead>

                            <tr class="border-b border-gray-100 bg-gray-50/70">

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Loan
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
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

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $payment->loan->lender }}
                                            </p>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-red-600">
                                            - {{ $formatter->money($payment->amount) }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $formatter->date($payment->payment_date) }}
                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-400">
                                        {{ $payment->reference_number ?: '—' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-xl">
                        💳
                    </div>

                    <h3 class="mt-4 font-semibold text-gray-900">
                        No salary deductions
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Salary Deduction loan payments will appear here.
                    </p>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- SALARY HISTORY --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Salary History
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Your previously recorded salary information.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="document.getElementById('salaryModal').classList.remove('hidden')"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    + Add Salary
                </button>

            </div>


            @if ($salaries->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead>

                            <tr class="border-b border-gray-100 bg-gray-50/70">

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Salary
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Type
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Effective Date
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($salaries as $salary)

                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-gray-900">
                                            {{ $formatter->money($salary->amount) }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4">

                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium capitalize text-indigo-700">
                                            {{ str_replace('-', ' ', $salary->salary_type) }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        {{ $formatter->date($salary->effective_date) }}

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
                                                class="text-sm font-semibold text-red-500 transition hover:text-red-700"
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

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-xl">
                        💰
                    </div>

                    <h3 class="mt-4 font-semibold text-gray-900">
                        No salary records
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Add your salary to start tracking your available income.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ADD SALARY MODAL --}}
{{-- ========================================================= --}}

<div
    id="salaryModal"
    class="fixed inset-0 z-50 hidden overflow-y-auto"
>

    {{-- BACKDROP --}}

    <div
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
        onclick="document.getElementById('salaryModal').classList.add('hidden')"
    ></div>


    {{-- MODAL CONTAINER --}}

    <div class="relative flex min-h-full items-center justify-center p-4">

        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl">


            {{-- MODAL HEADER --}}

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Add Salary
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Record your current or future salary.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="document.getElementById('salaryModal').classList.add('hidden')"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                >
                    ×
                </button>

            </div>


            {{-- FORM --}}

            <form
                method="POST"
                action="{{ route('salary.store') }}"
                class="p-6"
            >

                @csrf


                <div class="space-y-5">


                    {{-- SALARY AMOUNT --}}

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
                                class="block w-full rounded-xl border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    {{-- EFFECTIVE DATE --}}

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
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- SALARY TYPE --}}

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
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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


                    {{-- NOTES --}}

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
                            class="mt-2 block w-full rounded-xl border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                {{-- BUTTONS --}}

                <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">

                    <button
                        type="button"
                        onclick="document.getElementById('salaryModal').classList.add('hidden')"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Save Salary
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- AUTO OPEN MODAL IF VALIDATION ERROR --}}
{{-- ========================================================= --}}

@if ($errors->any())

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('salaryModal').classList.remove('hidden');
        });
    </script>

@endif

</x-app-layout>
