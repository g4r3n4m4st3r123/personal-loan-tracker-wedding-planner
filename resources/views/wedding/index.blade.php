<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Wedding Planner
                </p>

                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    Wedding Overview
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Your wedding command center.
                </p>

            </div>


            <div class="flex flex-wrap gap-2">

                {{-- Setup / Edit Wedding --}}

                <button
                    type="button"
                    @click="$dispatch('open-wedding-modal')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >

                    <svg
                        class="h-4 w-4"
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

                    {{ $wedding ? 'Edit Wedding' : 'Set Up Wedding' }}

                </button>


                @if ($wedding)

                    <a
                        href="{{ route('wedding.calendar') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        Calendar
                    </a>

                    <a
                        href="{{ route('wedding.timeline') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                    >
                        Timeline
                    </a>

                @endif

            </div>

        </div>

    </x-slot>


    <div
        class="py-8"
        x-data="{ weddingModal: false }"
        @open-wedding-modal.window="weddingModal = true"
        @keydown.escape.window="weddingModal = false"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- SUCCESS --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4">

                    <div class="mt-0.5 text-emerald-600">

                        <svg
                            class="h-5 w-5"
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

                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <div class="mt-0.5 text-rose-600">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a1 1 0 001.75 1.02l7.82-14a1 1 0 00-1.75-1.02zM13.71 3.86l7.82 14a1 1 0 011.75 1.02l-7.82-14a1 1 0 011.75-1.02z"
                                />
                            </svg>

                        </div>

                        <div>

                            <p class="font-semibold text-rose-800">
                                Please fix the following:
                            </p>

                            <ul class="mt-2 list-inside list-disc text-sm text-rose-700">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            @if ($wedding)


                {{-- ===================================================== --}}
                {{-- HERO --}}
                {{-- ===================================================== --}}

                <section class="overflow-hidden rounded-3xl bg-slate-900 text-white shadow-sm">

                    <div class="p-7 sm:p-9">

                        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">


                            {{-- Wedding Information --}}

                            <div class="max-w-3xl">

                                <p class="text-sm font-medium text-rose-300">
                                    💍 Your Wedding
                                </p>

                                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                                    {{ $wedding->wedding_name }}
                                </h1>


                                @if ($wedding->partner_name)

                                    <p class="mt-2 text-lg text-slate-300">
                                        With {{ $wedding->partner_name }}
                                    </p>

                                @endif


                                <div class="mt-6 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-300">


                                    <span class="inline-flex items-center gap-2">

                                        <span>📅</span>

                                        <span>

                                            @if ($wedding->wedding_date)

                                                {{ $wedding->wedding_date->format('F d, Y') }}

                                            @else

                                                Date not set

                                            @endif

                                        </span>

                                    </span>


                                    <span class="inline-flex items-center gap-2">

                                        <span>📍</span>

                                        <span>
                                            {{ $wedding->venue ?: 'Venue not set' }}
                                        </span>

                                    </span>


                                </div>

                            </div>


                            {{-- Countdown --}}

                            <div class="w-full rounded-2xl bg-white/10 p-5 backdrop-blur-sm sm:w-auto sm:min-w-[190px]">

                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                                    Countdown
                                </p>


                                @if ($daysUntilWedding === null)

                                    <p class="mt-2 text-2xl font-bold">
                                        No date
                                    </p>

                                @elseif ($daysUntilWedding > 0)

                                    <p class="mt-2 text-5xl font-bold tracking-tight">
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

                                    <p class="mt-2 text-xl font-bold text-slate-300">
                                        Wedding passed
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- HERO SUMMARY --}}

                    <div class="grid border-t border-white/10 sm:grid-cols-3">


                        {{-- Budget --}}

                        <a
                            href="{{ route('wedding.budget') }}"
                            class="border-b border-white/10 px-6 py-5 transition hover:bg-white/5 sm:border-b-0 sm:border-r"
                        >

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Budget
                            </p>

                            <p class="mt-1 text-lg font-bold">
                                {{ $formatter->money($totalActualWeddingExpenses) }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                actual spending
                            </p>

                        </a>


                        {{-- Guests --}}

                        <a
                            href="{{ route('wedding.guests') }}"
                            class="border-b border-white/10 px-6 py-5 transition hover:bg-white/5 sm:border-b-0 sm:border-r"
                        >

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Headcount
                            </p>

                            <p class="mt-1 text-lg font-bold">
                                {{ $estimatedHeadcount }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                estimated guests
                            </p>

                        </a>


                        {{-- Checklist --}}

                        <a
                            href="{{ route('wedding.checklist') }}"
                            class="px-6 py-5 transition hover:bg-white/5"
                        >

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Planning
                            </p>

                            <p class="mt-1 text-lg font-bold">
                                {{ number_format($checklistCompletionPercentage, 1) }}%
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                checklist completed
                            </p>

                        </a>

                    </div>

                </section>


                {{-- ===================================================== --}}
                {{-- MAIN OVERVIEW --}}
                {{-- ===================================================== --}}

                <div class="mt-8 grid gap-6 lg:grid-cols-2">


                    {{-- ================================================= --}}
                    {{-- BUDGET --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-indigo-600">
                                    Financial Planning
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Wedding Budget
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Track your planned budget and actual spending.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.budget') }}"
                                class="shrink-0 text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-8 flex items-end justify-between gap-4">

                            <div>

                                <p class="text-3xl font-bold tracking-tight text-slate-900">
                                    {{ $formatter->money($totalActualWeddingExpenses) }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    actual spending
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-lg font-bold text-slate-700">
                                    {{ number_format($weddingBudgetUsagePercentage, 1) }}%
                                </p>

                                <p class="text-xs text-slate-400">
                                    used
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full transition-all
                                @if ($weddingBudgetUsagePercentage >= 100)
                                    bg-rose-500
                                @elseif ($weddingBudgetUsagePercentage >= 80)
                                    bg-amber-500
                                @else
                                    bg-emerald-500
                                @endif"
                                style="width: {{ min(100, max(0, $weddingBudgetUsagePercentage)) }}%"
                            ></div>

                        </div>


                        <div class="mt-6 grid grid-cols-3 gap-4 border-t border-slate-100 pt-5">

                            <div>

                                <p class="text-xs font-medium text-slate-400">
                                    Total Budget
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-800">
                                    {{ $formatter->money($weddingBudget) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-medium text-slate-400">
                                    Planned
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-800">
                                    {{ $formatter->money($totalPlannedBudget) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-medium text-slate-400">
                                    Remaining
                                </p>

                                <p class="mt-1 text-sm font-bold text-emerald-600">
                                    {{ $formatter->money($weddingBudgetRemaining) }}
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- ================================================= --}}
                    {{-- GUESTS --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-rose-500">
                                    Guest Management
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Guest RSVP
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Keep track of confirmations and headcount.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.guests') }}"
                                class="shrink-0 text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-8 grid grid-cols-3 gap-4">


                            <div class="rounded-2xl bg-emerald-50 p-5">

                                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                                    Attending
                                </p>

                                <p class="mt-2 text-3xl font-bold text-emerald-700">
                                    {{ $attendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-2xl bg-amber-50 p-5">

                                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                                    Pending
                                </p>

                                <p class="mt-2 text-3xl font-bold text-amber-700">
                                    {{ $pendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-2xl bg-rose-50 p-5">

                                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                                    Declined
                                </p>

                                <p class="mt-2 text-3xl font-bold text-rose-700">
                                    {{ $declinedGuests }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-6 flex items-center justify-between rounded-2xl bg-slate-50 p-5">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Guest Records
                                </p>

                                <p class="mt-1 text-xl font-bold text-slate-900">
                                    {{ $totalGuests }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Plus Ones
                                </p>

                                <p class="mt-1 text-xl font-bold text-indigo-600">
                                    {{ $plusOneCount }}
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- ================================================= --}}
                    {{-- CHECKLIST --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-emerald-600">
                                    Planning Progress
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Wedding Checklist
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Stay on top of your wedding tasks.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.checklist') }}"
                                class="shrink-0 text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-8 flex items-end justify-between gap-4">

                            <div>

                                <p class="text-4xl font-bold tracking-tight text-slate-900">
                                    {{ number_format($checklistCompletionPercentage, 1) }}%
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $completedTasks }} of {{ $totalTasks }} completed
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Pending
                                </p>

                                <p class="mt-1 text-xl font-bold text-slate-800">
                                    {{ $pendingTasks }}
                                </p>


                                @if ($overdueTasks > 0)

                                    <p class="mt-1 text-xs font-semibold text-rose-600">
                                        {{ $overdueTasks }} overdue
                                    </p>

                                @else

                                    <p class="mt-1 text-xs text-emerald-600">
                                        No overdue tasks
                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="mt-6 h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-emerald-500 transition-all"
                                style="width: {{ min(100, max(0, $checklistCompletionPercentage)) }}%"
                            ></div>

                        </div>

                    </section>


                    {{-- ================================================= --}}
                    {{-- VENDORS --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-amber-600">
                                    Supplier Management
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Vendor Finances
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Monitor contracts and outstanding payments.
                                </p>

                            </div>

                            <a
                                href="{{ route('wedding.vendors') }}"
                                class="shrink-0 text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Manage →
                            </a>

                        </div>


                        <div class="mt-8 space-y-5">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-sm text-slate-500">
                                        Vendors
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-slate-900">
                                        {{ $weddingTotalVendors }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-sm text-slate-500">
                                        Contracted
                                    </p>

                                    <p class="mt-1 text-lg font-bold text-slate-900">
                                        {{ $formatter->money($weddingVendorContracted) }}
                                    </p>

                                </div>

                            </div>


                            <div class="border-t border-slate-100 pt-5">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm text-slate-500">
                                            Paid
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-emerald-600">
                                            {{ $formatter->money($weddingVendorPaid) }}
                                        </p>

                                    </div>


                                    <div class="text-right">

                                        <p class="text-sm text-slate-500">
                                            Outstanding
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-amber-600">
                                            {{ $formatter->money($weddingVendorOutstanding) }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>

                </div>


                {{-- ===================================================== --}}
                {{-- PLANNING STATUS --}}
                {{-- ===================================================== --}}

                <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                        <div>

                            <p class="text-sm font-medium text-indigo-600">
                                Planning Status
                            </p>

                            <h3 class="mt-1 text-xl font-bold text-slate-900">
                                Your wedding at a glance
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Key progress indicators without unnecessary clutter.
                            </p>

                        </div>


                        <a
                            href="{{ route('wedding.calendar') }}"
                            class="text-sm font-semibold text-indigo-600 hover:underline"
                        >
                            Open Calendar →
                        </a>

                    </div>


                    <div class="mt-7 grid gap-6 md:grid-cols-3">


                        {{-- Budget Progress --}}

                        <div>

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-medium text-slate-600">
                                    Budget
                                </span>

                                <span class="text-sm font-bold text-slate-800">
                                    {{ number_format($weddingBudgetUsagePercentage, 1) }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full
                                    @if ($weddingBudgetUsagePercentage >= 100)
                                        bg-rose-500
                                    @elseif ($weddingBudgetUsagePercentage >= 80)
                                        bg-amber-500
                                    @else
                                        bg-emerald-500
                                    @endif"
                                    style="width: {{ min(100, max(0, $weddingBudgetUsagePercentage)) }}%"
                                ></div>

                            </div>

                        </div>


                        {{-- Checklist Progress --}}

                        <div>

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-medium text-slate-600">
                                    Checklist
                                </span>

                                <span class="text-sm font-bold text-slate-800">
                                    {{ number_format($checklistCompletionPercentage, 1) }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full bg-indigo-500"
                                    style="width: {{ min(100, max(0, $checklistCompletionPercentage)) }}%"
                                ></div>

                            </div>

                        </div>


                        {{-- Guests --}}

                        <div>

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-medium text-slate-600">
                                    RSVP
                                </span>

                                <span class="text-sm font-bold text-slate-800">
                                    {{ $attendingGuests }} attending
                                </span>

                            </div>


                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">

                                @php

                                    $rsvpPercentage = $totalGuests > 0
                                        ? ($attendingGuests / $totalGuests) * 100
                                        : 0;

                                @endphp

                                <div
                                    class="h-full rounded-full bg-rose-400"
                                    style="width: {{ min(100, max(0, $rsvpPercentage)) }}%"
                                ></div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- ===================================================== --}}
                {{-- QUICK ACCESS --}}
                {{-- ===================================================== --}}

                <section class="mt-10">

                    <div class="mb-4">

                        <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">
                            Quick Access
                        </h3>

                    </div>


                    <div class="flex flex-wrap gap-2">


                        <a
                            href="{{ route('wedding.budget') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            💰 Budget
                        </a>


                        <a
                            href="{{ route('wedding.expenses') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            💸 Expenses
                        </a>


                        <a
                            href="{{ route('wedding.checklist') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            📋 Checklist
                        </a>


                        <a
                            href="{{ route('wedding.guests') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            👥 Guests
                        </a>


                        <a
                            href="{{ route('wedding.vendors') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            🏪 Vendors
                        </a>


                        <a
                            href="{{ route('wedding.timeline') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            📅 Timeline
                        </a>


                        <a
                            href="{{ route('wedding.calendar') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            🗓️ Calendar
                        </a>


                        <a
                            href="{{ route('reports.index') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            📊 Reports
                        </a>

                    </div>

                </section>


            @else


                {{-- ===================================================== --}}
                {{-- EMPTY STATE --}}
                {{-- ===================================================== --}}

                <section class="overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-sm sm:p-12">

                    <div class="max-w-2xl">

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-3xl">
                            💍
                        </div>

                        <p class="mt-5 text-sm font-medium text-rose-300">
                            Wedding Planner
                        </p>

                        <h3 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                            Let's start planning your wedding.
                        </h3>

                        <p class="mt-4 text-sm leading-6 text-slate-300">
                            Create your wedding and manage your budget, expenses,
                            guests, vendors, checklist, timeline, and calendar
                            from one organized workspace.
                        </p>


                        <button
                            type="button"
                            @click="weddingModal = true"
                            class="mt-7 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >

                            <svg
                                class="h-5 w-5"
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

                            Set Up Wedding

                        </button>

                    </div>

                </section>

            @endif


            {{-- ========================================================= --}}
            {{-- WEDDING SETUP MODAL --}}
            {{-- ========================================================= --}}

            <div
                x-show="weddingModal"
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="wedding-modal-title"
                role="dialog"
                aria-modal="true"
            >

                {{-- Backdrop --}}

                <div
                    x-show="weddingModal"
                    x-transition.opacity
                    @click="weddingModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                ></div>


                {{-- Modal Wrapper --}}

                <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">

                    <div
                        x-show="weddingModal"
                        x-transition:enter="ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                        @click.stop
                        class="relative w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl"
                    >


                        {{-- Modal Header --}}

                        <div class="flex items-start justify-between gap-5 border-b border-slate-100 px-6 py-5 sm:px-7">

                            <div>

                                <p class="text-sm font-medium text-indigo-600">
                                    Wedding Planner
                                </p>

                                <h3
                                    id="wedding-modal-title"
                                    class="mt-1 text-xl font-bold tracking-tight text-slate-900"
                                >
                                    {{ $wedding ? 'Edit Wedding Details' : 'Set Up Your Wedding' }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $wedding
                                        ? 'Update your wedding information anytime.'
                                        : 'Add your main wedding details to get started.' }}
                                </p>

                            </div>


                            {{-- Close --}}

                            <button
                                type="button"
                                @click="weddingModal = false"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                aria-label="Close modal"
                            >

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>

                            </button>

                        </div>


                        {{-- Modal Body --}}

                        <form
                            method="POST"
                            action="{{ route('wedding.store') }}"
                        >

                            @csrf


                            <div class="max-h-[70vh] overflow-y-auto px-6 py-6 sm:px-7">


                                <div class="grid gap-5 sm:grid-cols-2">


                                    {{-- Wedding Name --}}

                                    <div>

                                        <label
                                            for="modal_wedding_name"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Wedding Name
                                            <span class="text-rose-500">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="wedding_name"
                                            id="modal_wedding_name"
                                            value="{{ old('wedding_name', $wedding?->wedding_name ?? 'Our Wedding') }}"
                                            required
                                            placeholder="e.g. Our Dream Wedding"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Partner --}}

                                    <div>

                                        <label
                                            for="modal_partner_name"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Partner Name
                                        </label>

                                        <input
                                            type="text"
                                            name="partner_name"
                                            id="modal_partner_name"
                                            value="{{ old('partner_name', $wedding?->partner_name) }}"
                                            placeholder="e.g. John Doe"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Wedding Date --}}

                                    <div>

                                        <label
                                            for="modal_wedding_date"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Wedding Date
                                        </label>

                                        <input
                                            type="date"
                                            name="wedding_date"
                                            id="modal_wedding_date"
                                            value="{{ old('wedding_date', $wedding?->wedding_date?->format('Y-m-d')) }}"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Venue --}}

                                    <div>

                                        <label
                                            for="modal_venue"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Venue
                                        </label>

                                        <input
                                            type="text"
                                            name="venue"
                                            id="modal_venue"
                                            value="{{ old('venue', $wedding?->venue) }}"
                                            placeholder="e.g. Garden, Hotel, Church"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>


                                    {{-- Budget --}}

                                    <div>

                                        <label
                                            for="modal_budget"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Wedding Budget
                                            <span class="text-rose-500">*</span>
                                        </label>

                                        <div class="relative mt-2">

                                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">
                                                {{ $formatter->symbol() }}
                                            </span>

                                            <input
                                                type="number"
                                                name="budget"
                                                id="modal_budget"
                                                value="{{ old('budget', $wedding?->budget ?? 0) }}"
                                                min="0"
                                                step="0.01"
                                                required
                                                placeholder="100000.00"
                                                class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </div>

                                    </div>


                                    {{-- Notes --}}

                                    <div>

                                        <label
                                            for="modal_notes"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            Notes
                                            <span class="font-normal text-slate-400">
                                                (Optional)
                                            </span>
                                        </label>

                                        <input
                                            type="text"
                                            name="notes"
                                            id="modal_notes"
                                            value="{{ old('notes', $wedding?->notes) }}"
                                            placeholder="Important wedding notes"
                                            class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                    </div>

                                </div>


                                {{-- Helpful Note --}}

                                <div class="mt-6 rounded-2xl bg-indigo-50 px-4 py-3.5">

                                    <div class="flex gap-3">

                                        <div class="mt-0.5 text-indigo-600">
                                            💡
                                        </div>

                                        <p class="text-sm leading-5 text-indigo-800">
                                            You can update these details later as your wedding plans change.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Modal Footer --}}

                            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">


                                <div>

                                    @if ($wedding)

                                        <button
                                            type="submit"
                                            form="delete-wedding-form"
                                            onclick="return confirm('Delete your wedding details? This may also affect your wedding planning records.');"
                                            class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50"
                                        >
                                            Delete Wedding
                                        </button>

                                    @endif

                                </div>


                                <div class="flex flex-col-reverse gap-3 sm:flex-row">

                                    <button
                                        type="button"
                                        @click="weddingModal = false"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                    >
                                        Cancel
                                    </button>


                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                    >
                                        {{ $wedding ? 'Save Changes' : 'Create Wedding' }}
                                    </button>

                                </div>

                            </div>

                        </form>


                        {{-- Delete Form --}}

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

        </div>

    </div>


    {{-- Prevent Alpine flash --}}

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</x-app-layout>