<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Wedding Calendar
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View your wedding activities, deadlines, and services by month.
                </p>

            </div>


            <a
                href="{{ route('wedding.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                ← Wedding Overview
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- MONTH NAVIGATION --}}
            {{-- ========================================================= --}}

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <a
                        href="{{ route('wedding.calendar', ['month' => $previousMonth]) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        ← Previous
                    </a>


                    <div class="text-center">

                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $currentMonth->format('F Y') }}
                        </h3>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $monthlyEventCount }} event(s) this month
                        </p>

                    </div>


                    <div class="flex gap-2">

                        <a
                            href="{{ route('wedding.calendar', ['month' => now()->format('Y-m')]) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Today
                        </a>

                        <a
                            href="{{ route('wedding.calendar', ['month' => $nextMonth]) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Next →
                        </a>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- CALENDAR --}}
            {{-- ========================================================= --}}

            <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                {{-- Week Header --}}

                <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50">

                    @php

                        $calendarDaysOfWeek =
                            $userSettings->week_starts_on === 'sunday'

                                ? [
                                    'Sunday',
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday',
                                ]

                                : [
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday',
                                    'Sunday',
                                ];

                    @endphp


                    @foreach ($calendarDaysOfWeek as $day)

                        <div
                            class="border-r border-gray-200 px-2 py-3 text-center text-xs font-bold uppercase tracking-wide text-gray-500 last:border-r-0 sm:px-3"
                        >

                            <span class="sm:hidden">
                                {{ substr($day, 0, 1) }}
                            </span>

                            <span class="hidden sm:inline">
                                {{ $day }}
                            </span>

                        </div>

                    @endforeach

                </div>


                {{-- Calendar Days --}}

                <div class="grid grid-cols-7">

                    @foreach ($calendarDays as $day)

                        <div
                            class="min-h-[145px] border-b border-r border-gray-100 p-2
                            {{ !$day['isCurrentMonth'] ? 'bg-gray-50/70' : 'bg-white' }}
                            {{ $day['isToday'] ? 'ring-2 ring-inset ring-indigo-500' : '' }}"
                        >


                            {{-- Date Number --}}

                            <div class="mb-2 flex items-center justify-between">

                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold
                                    {{ $day['isToday']
                                        ? 'bg-indigo-600 text-white'
                                        : ($day['isCurrentMonth']
                                            ? 'text-gray-700'
                                            : 'text-gray-300') }}"
                                    title="{{ $formatter->date($day['date']) }}"
                                >
                                    {{ $day['date']->format('j') }}
                                </span>

                            </div>


                            {{-- Events --}}

                            <div class="space-y-1">

                                @foreach ($day['events']->take(4) as $event)

                                    @php

                                        $eventClass = match ($event['type']) {

                                            'wedding' =>
                                                'bg-rose-50 text-rose-700 border-rose-100',

                                            'timeline' =>
                                                'bg-indigo-50 text-indigo-700 border-indigo-100',

                                            'checklist' =>
                                                'bg-amber-50 text-amber-700 border-amber-100',

                                            'vendor' =>
                                                'bg-emerald-50 text-emerald-700 border-emerald-100',

                                            default =>
                                                'bg-gray-50 text-gray-700 border-gray-100',

                                        };

                                    @endphp


                                    <div
                                        class="rounded-lg border px-2 py-1.5 {{ $eventClass }}"
                                        title="{{ $event['title'] }}"
                                    >

                                        <div class="flex items-start gap-1.5">

                                            <span class="mt-0.5 shrink-0 text-[10px]">

                                                @if ($event['type'] === 'wedding')

                                                    💍

                                                @elseif ($event['type'] === 'timeline')

                                                    📅

                                                @elseif ($event['type'] === 'checklist')

                                                    📋

                                                @elseif ($event['type'] === 'vendor')

                                                    🧑‍💼

                                                @endif

                                            </span>


                                            <div class="min-w-0">

                                                <p class="truncate text-[11px] font-semibold">
                                                    {{ $event['title'] }}
                                                </p>


                                                @if ($event['start_time'])

                                                    <p class="mt-0.5 text-[10px] opacity-75">
                                                        {{ \Carbon\Carbon::parse($event['start_time'])->format('g:i A') }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach


                                @if ($day['events']->count() > 4)

                                    <p class="px-1 text-[10px] font-semibold text-gray-400">
                                        + {{ $day['events']->count() - 4 }} more
                                    </p>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- LEGEND --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-wrap items-center gap-5">

                    <div class="flex items-center gap-2">

                        <span class="h-3 w-3 rounded-full bg-rose-500"></span>

                        <span class="text-xs font-medium text-gray-600">
                            Wedding Day
                        </span>

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="h-3 w-3 rounded-full bg-indigo-500"></span>

                        <span class="text-xs font-medium text-gray-600">
                            Timeline
                        </span>

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="h-3 w-3 rounded-full bg-amber-500"></span>

                        <span class="text-xs font-medium text-gray-600">
                            Checklist
                        </span>

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="h-3 w-3 rounded-full bg-emerald-500"></span>

                        <span class="text-xs font-medium text-gray-600">
                            Vendor
                        </span>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- EVENTS THIS MONTH --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Events This Month
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            All events currently scheduled within
                            {{ $currentMonth->format('F Y') }}.
                        </p>

                    </div>


                    <span class="text-sm font-semibold text-indigo-600">
                        {{ $monthlyEventCount }} event(s)
                    </span>

                </div>


                @if ($calendarEvents->count())

                    <div class="mt-5 divide-y divide-gray-100">

                        @foreach ($calendarEvents as $event)

                            <div class="flex items-center justify-between gap-4 py-4">

                                <div class="flex min-w-0 items-center gap-3">


                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-50">

                                        @if ($event['type'] === 'wedding')

                                            💍

                                        @elseif ($event['type'] === 'timeline')

                                            📅

                                        @elseif ($event['type'] === 'checklist')

                                            📋

                                        @else

                                            🧑‍💼

                                        @endif

                                    </div>


                                    <div class="min-w-0">

                                        <p class="truncate font-semibold text-gray-900">
                                            {{ $event['title'] }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">

                                            {{ $formatter->date(
                                                \Carbon\Carbon::parse($event['date'])
                                            ) }}

                                            @if ($event['description'])

                                                · {{ $event['description'] }}

                                            @endif

                                        </p>

                                    </div>

                                </div>


                                <span
                                    class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold capitalize text-gray-600"
                                >
                                    {{ $event['type'] }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="mt-5 rounded-xl border border-dashed border-gray-200 p-8 text-center">

                        <div class="text-3xl">
                            📅
                        </div>

                        <p class="mt-3 text-sm font-semibold text-gray-700">
                            No events this month
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Add a timeline event, checklist deadline,
                            vendor service, or wedding date.
                        </p>

                    </div>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>