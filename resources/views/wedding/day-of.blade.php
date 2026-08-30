<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-indigo-600">
                    Wedding Planner
                </p>

                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    Day-of Wedding Mode
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Your simplified wedding-day command center.
                </p>

            </div>


            <div class="flex flex-wrap items-center gap-2">

                <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    ← Wedding Overview
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- SUCCESS --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">

                    <p class="font-semibold text-rose-800">
                        Please fix the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-rose-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            @if (!$wedding)

                {{-- ===================================================== --}}
                {{-- NO WEDDING --}}
                {{-- ===================================================== --}}

                <section class="overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-sm sm:p-12">

                    <div class="max-w-2xl">

                        <div class="text-5xl">
                            💒
                        </div>

                        <p class="mt-5 text-sm font-medium text-indigo-300">
                            Day-of Wedding Mode
                        </p>

                        <h3 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                            Create your wedding first.
                        </h3>

                        <p class="mt-4 text-sm leading-6 text-slate-300">
                            Once your wedding is set up, this page will bring together
                            today's timeline, checklist, vendors, guests, and seating information.
                        </p>

                        <a
                            href="{{ route('wedding.index') }}"
                            class="mt-6 inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100"
                        >
                            Set Up Wedding
                        </a>

                    </div>

                </section>


            @else


                {{-- ===================================================== --}}
                {{-- WEDDING DAY HERO --}}
                {{-- ===================================================== --}}

                <section class="overflow-hidden rounded-3xl bg-slate-900 text-white shadow-sm">

                    <div class="p-7 sm:p-9">

                        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">


                            {{-- Wedding info --}}

                            <div>

                                <p class="text-sm font-medium text-indigo-300">
                                    💒 Today
                                </p>

                                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                                    {{ $wedding->wedding_name }}
                                </h1>


                                @if ($wedding->partner_name)

                                    <p class="mt-2 text-lg text-slate-300">
                                        With {{ $wedding->partner_name }}
                                    </p>

                                @endif


                                <p class="mt-5 text-sm font-medium text-slate-300">
                                    {{ $dayOfDate->format('l, F d, Y') }}
                                </p>


                                @if ($wedding->venue)

                                    <p class="mt-2 text-sm text-slate-400">
                                        📍 {{ $wedding->venue }}
                                    </p>

                                @endif

                            </div>


                            {{-- Wedding date status --}}

                            <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">

                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                                    Wedding Date
                                </p>

                                @if ($wedding->wedding_date)

                                    <p class="mt-2 text-xl font-bold">

                                        {{ $wedding->wedding_date->format('M d, Y') }}

                                    </p>

                                    @if ($wedding->wedding_date->isSameDay($dayOfDate))

                                        <p class="mt-1 text-sm font-semibold text-emerald-300">
                                            Today is the big day! 💍
                                        </p>

                                    @elseif ($wedding->wedding_date->isFuture())

                                        <p class="mt-1 text-sm text-slate-300">
                                            Wedding day is coming up.
                                        </p>

                                    @else

                                        <p class="mt-1 text-sm text-slate-300">
                                            Wedding date has passed.
                                        </p>

                                    @endif

                                @else

                                    <p class="mt-2 text-xl font-bold">
                                        Date not set
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Summary strip --}}

                    <div class="grid border-t border-white/10 sm:grid-cols-4">


                        <div class="border-b border-white/10 px-6 py-5 sm:border-b-0 sm:border-r">

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Today's Events
                            </p>

                            <p class="mt-1 text-2xl font-bold">
                                {{ $todayTimeline->count() }}
                            </p>

                        </div>


                        <div class="border-b border-white/10 px-6 py-5 sm:border-b-0 sm:border-r">

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Guests
                            </p>

                            <p class="mt-1 text-2xl font-bold">
                                {{ $attendingGuests }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                attending
                            </p>

                        </div>


                        <div class="border-b border-white/10 px-6 py-5 sm:border-b-0 sm:border-r">

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Tables
                            </p>

                            <p class="mt-1 text-2xl font-bold">
                                {{ $totalTables }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                seating tables
                            </p>

                        </div>


                        <div class="px-6 py-5">

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Vendors
                            </p>

                            <p class="mt-1 text-2xl font-bold">
                                {{ $todayVendors->count() }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                scheduled today
                            </p>

                        </div>

                    </div>

                </section>


                {{-- ===================================================== --}}
                {{-- TODAY'S SCHEDULE --}}
                {{-- ===================================================== --}}

                <section class="mt-8">

                    <div class="mb-4">

                        <p class="text-sm font-medium text-indigo-600">
                            Today's Schedule
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Timeline
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Everything scheduled for today, in order.
                        </p>

                    </div>


                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">


                        @if ($todayTimeline->count())

                            <div class="divide-y divide-slate-100">

                                @foreach ($todayTimeline as $item)

                                    <div class="p-5 sm:p-6">

                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">


                                            {{-- Time --}}

                                            <div class="w-full shrink-0 sm:w-28">

                                                @if ($item->start_time)

                                                    <p class="text-lg font-bold text-slate-900">
                                                        {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                                    </p>

                                                    @if ($item->end_time)

                                                        <p class="mt-1 text-xs text-slate-400">
                                                            until
                                                            {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                                        </p>

                                                    @endif

                                                @else

                                                    <p class="text-sm font-semibold text-slate-400">
                                                        Time TBA
                                                    </p>

                                                @endif

                                            </div>


                                            {{-- Event --}}

                                            <div class="min-w-0 flex-1">

                                                <div class="flex flex-wrap items-center gap-2">

                                                    <h4
                                                        class="font-bold
                                                        {{ $item->status === 'completed'
                                                            ? 'text-slate-400 line-through'
                                                            : 'text-slate-900' }}"
                                                    >
                                                        {{ $item->title }}
                                                    </h4>


                                                    @if ($item->status === 'completed')

                                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                            Completed
                                                        </span>

                                                    @elseif ($item->status === 'in_progress')

                                                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                            In Progress
                                                        </span>

                                                    @else

                                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                            Planned
                                                        </span>

                                                    @endif

                                                </div>


                                                <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-500">

                                                    @if ($item->location)

                                                        <span>
                                                            📍 {{ $item->location }}
                                                        </span>

                                                    @endif


                                                    <span>
                                                        {{ ucfirst($item->category) }}
                                                    </span>

                                                </div>


                                                @if ($item->description)

                                                    <p class="mt-3 text-sm leading-6 text-slate-500">
                                                        {{ $item->description }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>


                        @else

                            <div class="px-6 py-14 text-center">

                                <div class="text-4xl">
                                    📅
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    No events scheduled today
                                </h4>

                                <p class="mt-1 text-sm text-slate-500">
                                    Your timeline does not have any events for
                                    {{ $dayOfDate->format('F d, Y') }}.
                                </p>

                                <a
                                    href="{{ route('wedding.timeline') }}"
                                    class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                                >
                                    Open Timeline
                                </a>

                            </div>

                        @endif

                    </div>

                </section>


                {{-- ===================================================== --}}
                {{-- CHECKLIST + VENDORS --}}
                {{-- ===================================================== --}}

                <div class="mt-8 grid gap-6 lg:grid-cols-2">


                    {{-- ================================================= --}}
                    {{-- TODAY'S CHECKLIST --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 p-6">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-sm font-medium text-emerald-600">
                                        Planning
                                    </p>

                                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                                        Today's Checklist
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $completedTodayTasks }} of {{ $totalTodayTasks }} due today completed.
                                    </p>

                                </div>


                                <a
                                    href="{{ route('wedding.checklist') }}"
                                    class="text-sm font-semibold text-indigo-600 hover:underline"
                                >
                                    Open →
                                </a>

                            </div>

                        </div>


                        @if ($todayTasks->count())

                            <div class="divide-y divide-slate-100">

                                @foreach ($todayTasks as $task)

                                    <div class="p-5">

                                        <div class="flex items-start gap-3">

                                            <div class="mt-0.5">

                                                @if ($task->status === 'completed')

                                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-sm text-emerald-700">
                                                        ✓
                                                    </div>

                                                @else

                                                    <div class="h-6 w-6 rounded-full border-2 border-slate-300">
                                                    </div>

                                                @endif

                                            </div>


                                            <div class="min-w-0">

                                                <p
                                                    class="font-semibold
                                                    {{ $task->status === 'completed'
                                                        ? 'text-slate-400 line-through'
                                                        : 'text-slate-900' }}"
                                                >
                                                    {{ $task->task_name }}
                                                </p>


                                                @if ($task->description)

                                                    <p class="mt-1 text-sm text-slate-500">
                                                        {{ $task->description }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>


                        @else

                            <div class="p-8 text-center">

                                <div class="text-3xl">
                                    ✅
                                </div>

                                <p class="mt-3 font-semibold text-slate-800">
                                    No tasks due today
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    You're all clear for today's checklist.
                                </p>

                            </div>

                        @endif

                    </section>


                    {{-- ================================================= --}}
                    {{-- TODAY'S VENDORS --}}
                    {{-- ================================================= --}}

                    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 p-6">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-sm font-medium text-amber-600">
                                        Suppliers
                                    </p>

                                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                                        Today's Vendors
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Vendors scheduled to provide services today.
                                    </p>

                                </div>


                                <a
                                    href="{{ route('wedding.vendors') }}"
                                    class="text-sm font-semibold text-indigo-600 hover:underline"
                                >
                                    Open →
                                </a>

                            </div>

                        </div>


                        @if ($todayVendors->count())

                            <div class="divide-y divide-slate-100">

                                @foreach ($todayVendors as $vendor)

                                    <div class="p-5">

                                        <div class="flex items-start justify-between gap-4">

                                            <div class="min-w-0">

                                                <p class="font-semibold text-slate-900">
                                                    {{ $vendor->vendor_name }}
                                                </p>

                                                <p class="mt-1 text-sm text-slate-500">
                                                    {{ $vendor->service_type }}
                                                </p>

                                                @if ($vendor->contact_person)

                                                    <p class="mt-1 text-xs text-slate-400">
                                                        Contact: {{ $vendor->contact_person }}
                                                    </p>

                                                @endif

                                            </div>


                                            <div class="shrink-0 text-right">

                                                <p class="text-sm font-semibold text-indigo-600">
                                                    Today
                                                </p>

                                                @if ($vendor->balance > 0)

                                                    <p class="mt-1 text-xs text-amber-600">
                                                        {{ $formatter->money($vendor->balance) }} outstanding
                                                    </p>

                                                @else

                                                    <p class="mt-1 text-xs text-emerald-600">
                                                        Fully paid
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>


                        @else

                            <div class="p-8 text-center">

                                <div class="text-3xl">
                                    🏪
                                </div>

                                <p class="mt-3 font-semibold text-slate-800">
                                    No vendors scheduled today
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    There are no vendor services assigned to today's date.
                                </p>

                            </div>

                        @endif

                    </section>

                </div>


                {{-- ===================================================== --}}
                {{-- GUESTS + SEATING --}}
                {{-- ===================================================== --}}

                <div class="mt-8 grid gap-6 lg:grid-cols-2">


                    {{-- GUEST STATUS --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-rose-500">
                                    Guests
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Guest Status
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Current RSVP status for your wedding.
                                </p>

                            </div>


                            <a
                                href="{{ route('wedding.guests') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Open →
                            </a>

                        </div>


                        <div class="mt-6 grid grid-cols-3 gap-3">


                            <div class="rounded-2xl bg-emerald-50 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                                    Attending
                                </p>

                                <p class="mt-2 text-2xl font-bold text-emerald-700">
                                    {{ $attendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-2xl bg-amber-50 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                                    Pending
                                </p>

                                <p class="mt-2 text-2xl font-bold text-amber-700">
                                    {{ $pendingGuests }}
                                </p>

                            </div>


                            <div class="rounded-2xl bg-rose-50 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                                    Declined
                                </p>

                                <p class="mt-2 text-2xl font-bold text-rose-700">
                                    {{ $declinedGuests }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5">

                            <div>

                                <p class="text-xs text-slate-400">
                                    Total Guest Records
                                </p>

                                <p class="mt-1 text-lg font-bold text-slate-900">
                                    {{ $totalGuests }}
                                </p>

                            </div>

                            <div class="text-right">

                                <p class="text-xs text-slate-400">
                                    Assigned to Tables
                                </p>

                                <p class="mt-1 text-lg font-bold text-indigo-600">
                                    {{ $assignedGuests }}
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- SEATING STATUS --}}

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-indigo-600">
                                    Seating
                                </p>

                                <h3 class="mt-1 text-xl font-bold text-slate-900">
                                    Table Readiness
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Make sure your attending guests have assigned seats.
                                </p>

                            </div>


                            <a
                                href="{{ route('wedding.seating') }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline"
                            >
                                Open →
                            </a>

                        </div>


                        <div class="mt-8">

                            @if ($unassignedGuests > 0)

                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="text-xl">
                                            ⚠️
                                        </div>

                                        <div>

                                            <p class="font-semibold text-amber-800">
                                                {{ $unassignedGuests }} attending guest(s) still unassigned.
                                            </p>

                                            <p class="mt-1 text-sm text-amber-700">
                                                Review your seating arrangement before the wedding starts.
                                            </p>

                                        </div>

                                    </div>

                                </div>


                            @else

                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="text-xl">
                                            ✅
                                        </div>

                                        <div>

                                            <p class="font-semibold text-emerald-800">
                                                Seating arrangement is ready.
                                            </p>

                                            <p class="mt-1 text-sm text-emerald-700">
                                                All attending guests are assigned to tables.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>


                        <div class="mt-5 grid grid-cols-2 gap-4">

                            <div class="rounded-xl bg-slate-50 p-4">

                                <p class="text-xs text-slate-400">
                                    Tables
                                </p>

                                <p class="mt-1 text-xl font-bold text-slate-900">
                                    {{ $totalTables }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-slate-50 p-4">

                                <p class="text-xs text-slate-400">
                                    Unassigned
                                </p>

                                <p class="mt-1 text-xl font-bold {{ $unassignedGuests > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                    {{ $unassignedGuests }}
                                </p>

                            </div>

                        </div>

                    </section>

                </div>


                {{-- ===================================================== --}}
                {{-- QUICK ACTIONS --}}
                {{-- ===================================================== --}}

                <section class="mt-8">

                    <div class="mb-4">

                        <p class="text-sm font-medium text-slate-400">
                            Quick Access
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">


                        <a
                            href="{{ route('wedding.timeline') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            📅 Timeline
                        </a>


                        <a
                            href="{{ route('wedding.checklist') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            📋 Checklist
                        </a>


                        <a
                            href="{{ route('wedding.guests') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            👥 Guests
                        </a>


                        <a
                            href="{{ route('wedding.seating') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            🪑 Seating
                        </a>


                        <a
                            href="{{ route('wedding.vendors') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            🏪 Vendors
                        </a>


                        <a
                            href="{{ route('wedding.documents') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            📁 Documents
                        </a>

                    </div>

                </section>


            @endif

        </div>

    </div>

</x-app-layout>