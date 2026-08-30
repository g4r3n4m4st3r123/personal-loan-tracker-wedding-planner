<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="flex items-center gap-2">

                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 text-rose-500">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 20.5S4 15.7 4 9.5A4.5 4.5 0 018.5 5c1.5 0 2.8.7 3.5 1.8A4.1 4.1 0 0115.5 5 4.5 4.5 0 0120 9.5c0 6.2-8 11-8 11z"
                            />
                        </svg>

                    </span>

                    <p class="text-sm font-semibold text-rose-500">
                        Reports
                    </p>

                </div>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                    Wedding Reports
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Keep track of your wedding budget, guests, tasks, and vendors.
                </p>

            </div>


            <a
                href="{{ route('reports.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 18l-6-6 6-6"
                    />
                </svg>

                Reports

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            @if ($wedding)

                {{-- ========================================================= --}}
                {{-- WEDDING HEADER --}}
                {{-- ========================================================= --}}

                <section class="rounded-3xl bg-slate-900 p-7 text-white shadow-sm sm:p-8">

                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

                        <div>

                            <p class="text-sm font-medium text-rose-300">
                                Wedding Overview
                            </p>

                            <h1 class="mt-2 text-3xl font-bold tracking-tight">
                                {{ $wedding->name ?? 'Your Wedding' }}
                            </h1>

                            @if ($wedding->wedding_date ?? false)

                                <p class="mt-2 text-sm text-slate-300">
                                    {{ \Carbon\Carbon::parse($wedding->wedding_date)->format('F d, Y') }}
                                </p>

                            @else

                                <p class="mt-2 text-sm text-slate-400">
                                    Wedding date not set
                                </p>

                            @endif

                        </div>


                        <a
                            href="{{ route('wedding.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15"
                        >
                            Manage Wedding
                        </a>

                    </div>

                </section>


                {{-- ========================================================= --}}
                {{-- BUDGET OVERVIEW --}}
                {{-- ========================================================= --}}

                <section class="mt-8">

                    <div class="mb-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-rose-500">
                            Budget
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Wedding budget overview
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            See how your planned wedding budget is being used.
                        </p>

                    </div>


                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                        {{-- Wedding Budget --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Wedding Budget
                            </p>

                            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                                {{ $formatter->money($weddingBudget) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Overall budget
                            </p>

                        </div>


                        {{-- Planned --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Planned
                            </p>

                            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                                {{ $formatter->money($weddingPlanned) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Planned categories
                            </p>

                        </div>


                        {{-- Actual --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Actual
                            </p>

                            <p class="mt-3 text-2xl font-bold tracking-tight text-rose-600">
                                {{ $formatter->money($weddingActual) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Recorded spending
                            </p>

                        </div>


                        {{-- Remaining --}}

                        <div class="rounded-2xl bg-slate-900 p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Remaining
                            </p>

                            <p class="mt-3 text-2xl font-bold tracking-tight text-white">
                                {{ $formatter->money($weddingRemaining) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                Planned less actual
                            </p>

                        </div>

                    </div>


                    {{-- Budget Progress --}}

                    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <h4 class="text-sm font-bold text-slate-900">
                                    Budget Usage
                                </h4>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ number_format($weddingBudgetUsage, 1) }}% of planned budget used
                                </p>

                            </div>

                            <span class="text-sm font-bold text-rose-600">
                                {{ number_format($weddingBudgetUsage, 1) }}%
                            </span>

                        </div>


                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-rose-400 transition-all"
                                style="width: {{ min(100, max(0, $weddingBudgetUsage)) }}%"
                            ></div>

                        </div>

                    </div>

                </section>


                {{-- ========================================================= --}}
                {{-- GUEST OVERVIEW --}}
                {{-- ========================================================= --}}

                <section class="mt-10">

                    <div class="mb-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                            Guests
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Guest overview
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Keep track of your guest list and expected headcount.
                        </p>

                    </div>


                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                        {{-- Total Guests --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Guest List
                            </p>

                            <p class="mt-3 text-2xl font-bold text-slate-900">
                                {{ $weddingGuests }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Total invited guests
                            </p>

                        </div>


                        {{-- Attending --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Attending
                            </p>

                            <p class="mt-3 text-2xl font-bold text-emerald-600">
                                {{ $weddingAttending }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Confirmed guests
                            </p>

                        </div>


                        {{-- Pending --}}

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Pending
                            </p>

                            <p class="mt-3 text-2xl font-bold text-amber-600">
                                {{ $weddingPending }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Awaiting RSVP
                            </p>

                        </div>


                        {{-- Headcount --}}

                        <div class="rounded-2xl bg-slate-900 p-5 shadow-sm">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Expected Headcount
                            </p>

                            <p class="mt-3 text-2xl font-bold text-white">
                                {{ $weddingHeadcount }}
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                Including plus-ones
                            </p>

                        </div>

                    </div>

                </section>


                {{-- ========================================================= --}}
                {{-- PLANNING PROGRESS --}}
                {{-- ========================================================= --}}

                <section class="mt-10">

                    <div class="mb-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">
                            Planning
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Planning progress
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            A quick look at your checklist and vendor commitments.
                        </p>

                    </div>


                    <div class="grid gap-6 lg:grid-cols-2">


                        {{-- ================================================= --}}
                        {{-- CHECKLIST --}}
                        {{-- ================================================= --}}

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                        Checklist
                                    </p>

                                    <h4 class="mt-2 text-lg font-bold text-slate-900">
                                        Task progress
                                    </h4>

                                </div>

                                <span class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">

                                    {{ $weddingTasks > 0
                                        ? number_format(
                                            ($weddingCompletedTasks / $weddingTasks) * 100,
                                            1
                                        )
                                        : 0
                                    }}%

                                </span>

                            </div>


                            @php

                                $taskPercentage =
                                    $weddingTasks > 0
                                        ? ($weddingCompletedTasks / $weddingTasks) * 100
                                        : 0;

                            @endphp


                            <div class="mt-7">

                                <div class="flex items-center justify-between">

                                    <p class="text-sm text-slate-500">
                                        {{ $weddingCompletedTasks }} of {{ $weddingTasks }} completed
                                    </p>

                                    <p class="text-xs font-semibold text-slate-400">
                                        {{ $weddingOverdueTasks }} overdue
                                    </p>

                                </div>


                                <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">

                                    <div
                                        class="h-full rounded-full bg-emerald-500"
                                        style="width: {{ min(100, max(0, $taskPercentage)) }}%"
                                    ></div>

                                </div>

                            </div>


                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">

                                <a
                                    href="{{ route('wedding.checklist') }}"
                                    class="text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                                >
                                    Open checklist
                                </a>

                                <span class="text-xs text-slate-400">
                                    {{ $weddingTasks }} total tasks
                                </span>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- VENDORS --}}
                        {{-- ================================================= --}}

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                        Vendors
                                    </p>

                                    <h4 class="mt-2 text-lg font-bold text-slate-900">
                                        Vendor commitments
                                    </h4>

                                </div>

                                <span class="rounded-xl bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700">
                                    {{ $weddingVendors }} vendors
                                </span>

                            </div>


                            <div class="mt-7 space-y-5">


                                {{-- Contracted --}}

                                <div class="flex items-center justify-between gap-4">

                                    <div>

                                        <p class="text-sm font-medium text-slate-500">
                                            Contracted
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-slate-900">
                                            {{ $formatter->money($weddingVendorContracted) }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Paid --}}

                                <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-5">

                                    <div>

                                        <p class="text-sm font-medium text-slate-500">
                                            Paid
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-emerald-600">
                                            {{ $formatter->money($weddingVendorPaid) }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Outstanding --}}

                                <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-5">

                                    <div>

                                        <p class="text-sm font-medium text-slate-500">
                                            Outstanding
                                        </p>

                                        <p class="mt-1 text-lg font-bold text-rose-600">
                                            {{ $formatter->money($weddingVendorOutstanding) }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="mt-6 border-t border-slate-100 pt-5">

                                <a
                                    href="{{ route('wedding.vendors') }}"
                                    class="text-sm font-semibold text-rose-500 hover:text-rose-600"
                                >
                                    Manage vendors
                                </a>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- ========================================================= --}}
                {{-- WEDDING SUMMARY --}}
                {{-- ========================================================= --}}

                <section class="mt-10">

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-7">

                        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wider text-rose-500">
                                    Wedding Planning Summary
                                </p>

                                <h3 class="mt-2 text-xl font-bold text-slate-900">
                                    You're making progress.
                                </h3>

                                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                    Your wedding currently has
                                    <span class="font-semibold text-slate-700">
                                        {{ $weddingHeadcount }} expected guest(s)
                                    </span>,
                                    with
                                    <span class="font-semibold text-slate-700">
                                        {{ number_format($weddingBudgetUsage, 1) }}%
                                    </span>
                                    of the planned budget used and
                                    <span class="font-semibold text-slate-700">
                                        {{ $weddingCompletedTasks }}
                                    </span>
                                    completed task(s).
                                </p>

                            </div>


                            <div class="flex flex-wrap gap-3">

                                <a
                                    href="{{ route('wedding.budget') }}"
                                    class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50"
                                >
                                    Budget
                                </a>

                                <a
                                    href="{{ route('wedding.guests') }}"
                                    class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50"
                                >
                                    Guests
                                </a>

                                <a
                                    href="{{ route('wedding.checklist') }}"
                                    class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                                >
                                    Checklist
                                </a>

                            </div>

                        </div>

                    </div>

                </section>


            @else

                {{-- ========================================================= --}}
                {{-- NO WEDDING --}}
                {{-- ========================================================= --}}

                <section class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm sm:p-14">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">

                        <svg
                            class="h-7 w-7"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 20.5S4 15.7 4 9.5A4.5 4.5 0 018.5 5c1.5 0 2.8.7 3.5 1.8A4.1 4.1 0 0115.5 5 4.5 4.5 0 0120 9.5c0 6.2-8 11-8 11z"
                            />
                        </svg>

                    </div>


                    <h3 class="mt-5 text-xl font-bold text-slate-900">
                        No wedding data yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Create your wedding first to see your budget,
                        guest, checklist, and vendor reports here.
                    </p>


                    <div class="mt-6">

                        <a
                            href="{{ route('wedding.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            Set Up Wedding
                        </a>

                    </div>

                </section>

            @endif


            {{-- ========================================================= --}}
            {{-- FOOTER --}}
            {{-- ========================================================= --}}

            <div class="mt-10 border-t border-slate-200 pt-6">

                <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">

                    <p class="text-xs text-slate-400">
                        Wedding report generated from your recorded planning data.
                    </p>

                    <a
                        href="{{ route('reports.index') }}"
                        class="text-xs font-semibold text-rose-500 hover:text-rose-600"
                    >
                        Back to Reports
                    </a>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>