<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Wedding Overview
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Your complete wedding planning command center.
                </p>

            </div>


            @if ($wedding)

                <div class="flex flex-wrap gap-2">

                    <a
                        href="{{ route('wedding.calendar') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Calendar
                    </a>

                    <a
                        href="{{ route('wedding.timeline') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Timeline
                    </a>

                </div>

            @endif

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


            @if ($wedding)


                {{-- ===================================================== --}}
                {{-- HERO / WEDDING SUMMARY --}}
                {{-- ===================================================== --}}

                <div class="overflow-hidden rounded-3xl bg-slate-900 p-6 text-white shadow-sm sm:p-8">

                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

                        <div class="max-w-3xl">

                            <p class="text-sm font-medium text-slate-300">
                                Your Wedding
                            </p>

                            <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                                {{ $wedding->wedding_name }}
                            </h1>

                            @if ($wedding->partner_name)

                                <p class="mt-2 text-lg text-slate-300">
                                    With {{ $wedding->partner_name }}
                                </p>

                            @endif


                            <div class="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-300">

                                <span>

                                    📅

                                    @if ($wedding->wedding_date)

                                        {{ $formatter->date($wedding->wedding_date) }}

                                    @else

                                        Date not set

                                    @endif

                                </span>


                                <span>
                                    📍
                                    {{ $wedding->venue ?: 'Venue not set' }}
                                </span>

                            </div>

                        </div>


                        <div class="min-w-[190px] rounded-2xl bg-white/10 p-5 backdrop-blur-sm">

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-300">
                                Countdown
                            </p>


                            @if ($daysUntilWedding === null)

                                <p class="mt-2 text-2xl font-bold">
                                    No date
                                </p>

                            @elseif ($daysUntilWedding > 0)

                                <p class="mt-1 text-4xl font-bold">
                                    {{ number_format($daysUntilWedding) }}
                                </p>

                                <p class="mt-1 text-sm text-slate-300">
                                    days to go
                                </p>

                            @elseif ($daysUntilWedding === 0)

                                <p class="mt-2 text-3xl font-bold">
                                    Today! 💍
                                </p>

                            @else

                                <p class="mt-2 text-xl font-bold">
                                    Wedding passed
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- LIVE STATISTICS --}}
                {{-- ===================================================== --}}

                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    {{-- Guests --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Estimated Headcount
                                </p>

                                <p class="mt-2 text-3xl font-bold text-indigo-600">
                                    {{ $estimatedHeadcount }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $attendingGuests }} attending
                                    + {{ $plusOneCount }} plus-one(s)
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                👥
                            </div>

                        </div>

                    </div>


                    {{-- Checklist --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Checklist
                                </p>

                                <p class="mt-2 text-3xl font-bold text-emerald-600">
                                    {{ number_format($checklistCompletionPercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $completedTasks }} of {{ $totalTasks }} completed
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                                ✅
                            </div>

                        </div>

                    </div>


                    {{-- Budget --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Budget Used
                                </p>

                                <p class="mt-2 text-3xl font-bold text-rose-600">
                                    {{ number_format($weddingBudgetUsagePercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $formatter->money($weddingBudgetRemaining) }} remaining
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-xl">
                                💰
                            </div>

                        </div>

                    </div>


                    {{-- Vendors --}}

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Vendor Outstanding
                                </p>

                                <p class="mt-2 text-2xl font-bold text-amber-600">
                                    {{ $formatter->money($weddingVendorOutstanding) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $weddingTotalVendors }} vendor(s)
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                🧑‍💼
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- BUDGET + GUESTS --}}
                {{-- ===================================================== --}}

                <div class="mt-6 grid gap-6 lg:grid-cols-2">


                    {{-- Budget Progress --}}

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Wedding Budget
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Track planned spending against actual paid expenses.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.budget') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-6 grid gap-4 sm:grid-cols-3">

                            <div>

                                <p class="text-xs text-gray-400">
                                    Overall Budget
                                </p>

                                <p class="mt-1 font-bold text-gray-900">
                                    {{ $formatter->money($weddingBudget) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-400">
                                    Actual Spent
                                </p>

                                <p class="mt-1 font-bold text-rose-600">
                                    {{ $formatter->money($totalActualWeddingExpenses) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-400">
                                    Remaining
                                </p>

                                <p class="mt-1 font-bold text-emerald-600">
                                    {{ $formatter->money($weddingBudgetRemaining) }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-6">

                            <div class="mb-2 flex items-center justify-between text-xs">

                                <span class="font-medium text-gray-500">
                                    Budget usage
                                </span>

                                <span class="font-bold text-gray-700">
                                    {{ number_format($weddingBudgetUsagePercentage, 1) }}%
                                </span>

                            </div>


                            <div class="h-3 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full transition-all
                                    @if ($weddingBudgetUsagePercentage >= 100)
                                        bg-red-500
                                    @elseif ($weddingBudgetUsagePercentage >= 80)
                                        bg-amber-500
                                    @else
                                        bg-emerald-500
                                    @endif"
                                    style="width: {{ min(100, $weddingBudgetUsagePercentage) }}%"
                                ></div>

                            </div>

                        </div>


                        <div class="mt-5 flex items-center justify-between rounded-xl bg-slate-50 p-4">

                            <div>

                                <p class="text-xs text-gray-400">
                                    Planned category budget
                                </p>

                                <p class="mt-1 font-semibold text-gray-800">
                                    {{ $formatter->money($totalPlannedBudget) }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-xs text-gray-400">
                                    Unallocated
                                </p>

                                <p class="mt-1 font-semibold text-indigo-600">
                                    {{ $formatter->money(
                                        max(
                                            0,
                                            $weddingBudget - $totalPlannedBudget
                                        )
                                    ) }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Guest Progress --}}

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Guest RSVP
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Keep track of confirmations and headcount.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.guests') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-6 grid grid-cols-3 gap-4">

                            <div class="rounded-xl bg-emerald-50 p-4">

                                <p class="text-xs font-medium text-emerald-700">
                                    Attending
                                </p>

                                <p class="mt-1 text-2xl font-bold text-emerald-700">
                                    {{ $attendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-amber-50 p-4">

                                <p class="text-xs font-medium text-amber-700">
                                    Pending
                                </p>

                                <p class="mt-1 text-2xl font-bold text-amber-700">
                                    {{ $pendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-red-50 p-4">

                                <p class="text-xs font-medium text-red-700">
                                    Declined
                                </p>

                                <p class="mt-1 text-2xl font-bold text-red-700">
                                    {{ $declinedGuests }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 rounded-xl bg-indigo-50 p-4">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-xs font-medium text-indigo-700">
                                        Estimated headcount
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-indigo-700">
                                        {{ $estimatedHeadcount }}
                                    </p>

                                </div>

                                <div class="text-right">

                                    <p class="text-xs text-indigo-600">
                                        Plus-ones
                                    </p>

                                    <p class="mt-1 font-bold text-indigo-700">
                                        {{ $plusOneCount }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- CHECKLIST + VENDORS --}}
                {{-- ===================================================== --}}

                <div class="mt-6 grid gap-6 lg:grid-cols-2">


                    {{-- Checklist --}}

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Checklist Progress
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Stay on top of important wedding tasks.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.checklist') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-6 flex items-center justify-between">

                            <div>

                                <p class="text-4xl font-bold text-emerald-600">
                                    {{ number_format($checklistCompletionPercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $completedTasks }} completed
                                </p>

                            </div>

                            <div class="text-right">

                                <p class="text-xs text-gray-400">
                                    Pending
                                </p>

                                <p class="mt-1 text-lg font-bold text-gray-800">
                                    {{ $pendingTasks }}
                                </p>

                                <p class="mt-2 text-xs text-gray-400">
                                    Overdue
                                </p>

                                <p class="mt-1 text-lg font-bold text-red-600">
                                    {{ $overdueTasks }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full bg-emerald-500 transition-all"
                                style="width: {{ min(100, $checklistCompletionPercentage) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Vendors --}}

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Vendor Financial Summary
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Your supplier contracts and outstanding balances.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.vendors') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-6 grid grid-cols-3 gap-4">

                            <div>

                                <p class="text-xs text-gray-400">
                                    Vendors
                                </p>

                                <p class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $weddingTotalVendors }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-400">
                                    Contracted
                                </p>

                                <p class="mt-1 font-bold text-gray-900">
                                    {{ $formatter->money($weddingVendorContracted) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-400">
                                    Paid
                                </p>

                                <p class="mt-1 font-bold text-emerald-600">
                                    {{ $formatter->money($weddingVendorPaid) }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 flex items-center justify-between rounded-xl bg-amber-50 p-4">

                            <div>

                                <p class="text-xs font-medium text-amber-700">
                                    Outstanding Vendor Balance
                                </p>

                                <p class="mt-1 text-xl font-bold text-amber-700">
                                    {{ $formatter->money($weddingVendorOutstanding) }}
                                </p>

                            </div>

                            <span class="text-2xl">
                                🧾
                            </span>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- QUICK ACTIONS --}}
                {{-- ===================================================== --}}

                <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-gray-900">
                            Quick Actions
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Jump directly to the part of your wedding planning that needs attention.
                        </p>

                    </div>


                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">


                        <a
                            href="{{ route('wedding.budget') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                💰 Budget
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Manage planned spending
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.expenses') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                💸 Expenses
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Record actual wedding costs
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.checklist') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                📋 Checklist
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Track tasks and deadlines
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.guests') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                👥 Guests
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Manage your guest list
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.vendors') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                🧑‍💼 Vendors
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Manage suppliers and contracts
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.timeline') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                📅 Timeline
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Organize your schedule
                            </p>

                        </a>


                        <a
                            href="{{ route('wedding.calendar') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                🗓️ Calendar
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                View all wedding dates
                            </p>

                        </a>


                        <a
                            href="{{ route('reports.index') }}"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50"
                        >

                            <p class="font-semibold text-gray-900">
                                📊 Reports
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                View financial analytics
                            </p>

                        </a>

                    </div>

                </div>


            @else


                {{-- ===================================================== --}}
                {{-- EMPTY STATE --}}
                {{-- ===================================================== --}}

                <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-sm sm:p-12">

                    <div class="max-w-2xl">

                        <p class="text-sm font-medium text-slate-300">
                            Wedding Planner
                        </p>

                        <h3 class="mt-2 text-3xl font-bold">
                            Let's start planning your wedding.
                        </h3>

                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Create your wedding below. Once saved, your budget, expenses,
                            guests, vendors, checklist, timeline, and calendar will all
                            connect to this wedding.
                        </p>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- WEDDING FORM --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $wedding ? 'Update Wedding Details' : 'Create Wedding' }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Keep your main wedding information up to date.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('wedding.store') }}"
                    class="grid gap-5 md:grid-cols-2"
                >

                    @csrf


                    {{-- Wedding Name --}}

                    <div>

                        <label
                            for="wedding_name"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Wedding Name
                        </label>

                        <input
                            type="text"
                            name="wedding_name"
                            id="wedding_name"
                            value="{{ old('wedding_name', $wedding?->wedding_name ?? 'Our Wedding') }}"
                            required
                            placeholder="e.g. Our Dream Wedding"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Partner --}}

                    <div>

                        <label
                            for="partner_name"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Partner Name
                        </label>

                        <input
                            type="text"
                            name="partner_name"
                            id="partner_name"
                            value="{{ old('partner_name', $wedding?->partner_name) }}"
                            placeholder="e.g. John Doe"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Wedding Date --}}

                    <div>

                        <label
                            for="wedding_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Wedding Date
                        </label>

                        <input
                            type="date"
                            name="wedding_date"
                            id="wedding_date"
                            value="{{ old('wedding_date', $wedding?->wedding_date?->format('Y-m-d')) }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Venue --}}

                    <div>

                        <label
                            for="venue"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Venue
                        </label>

                        <input
                            type="text"
                            name="venue"
                            id="venue"
                            value="{{ old('venue', $wedding?->venue) }}"
                            placeholder="e.g. Garden, Hotel, Church"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Budget --}}

                    <div>

                        <label
                            for="budget"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Wedding Budget
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                {{ $formatter->symbol() }}
                            </span>

                            <input
                                type="number"
                                name="budget"
                                id="budget"
                                value="{{ old('budget', $wedding?->budget ?? 0) }}"
                                min="0"
                                step="0.01"
                                required
                                placeholder="100000.00"
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

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
                            value="{{ old('notes', $wedding?->notes) }}"
                            placeholder="Important wedding notes"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Buttons --}}

                    <div class="flex flex-wrap items-center gap-3 md:col-span-2">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            {{ $wedding ? 'Update Wedding' : 'Save Wedding' }}
                        </button>


                        @if ($wedding)

                            <button
                                type="submit"
                                form="delete-wedding-form"
                                class="inline-flex items-center justify-center rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                                onclick="return confirm('Delete your wedding details?');"
                            >
                                Delete Wedding
                            </button>

                        @endif

                    </div>

                </form>


                @if ($wedding)

                    <form
                        id="delete-wedding-form"
                        method="POST"
                        action="{{ route('wedding.destroy') }}"
                        class="hidden"
                    >

                        @csrf

                        @method('DELETE')

                    </form>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>