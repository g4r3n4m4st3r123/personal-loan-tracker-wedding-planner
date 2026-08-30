<x-app-layout>

<x-slot name="header">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-gray-500">
                Wedding Planner
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Wedding Timeline
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Organize your wedding preparations, appointments, payments, ceremony, and reception schedule.
            </p>
        </div>


        {{-- HEADER ACTIONS --}}

        <div class="flex flex-wrap items-center gap-2">

            {{-- ADD TIMELINE BUTTON --}}

            <button
                type="button"
                onclick="openAddTimelineModal()"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                <span class="text-lg leading-none">
                    +
                </span>

                Add Timeline
            </button>


<!--             {{-- WEDDING OVERVIEW --}}

            <a
                href="{{ route('wedding.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                ← Wedding Overview
            </a>
 -->
        </div>

    </div>

</x-slot>


<div class="py-8">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if (session('success'))

            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
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


        {{-- ========================================================= --}}
        {{-- STATISTICS --}}
        {{-- ========================================================= --}}

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">

            {{-- Total --}}

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <p class="text-sm font-medium text-gray-500">
                    Total
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    {{ $totalItems }}
                </p>

            </div>


            {{-- Completed --}}

            <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                <p class="text-sm font-medium text-emerald-700">
                    Completed
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-700">
                    {{ $completedItems }}
                </p>

            </div>


            {{-- Planned --}}

            <div class="rounded-2xl bg-slate-50 p-5 shadow-sm ring-1 ring-slate-200">

                <p class="text-sm font-medium text-slate-600">
                    Planned
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-700">
                    {{ $plannedItems }}
                </p>

            </div>


            {{-- In Progress --}}

            <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                <p class="text-sm font-medium text-indigo-700">
                    In Progress
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-700">
                    {{ $inProgressItems }}
                </p>

            </div>


            {{-- Overdue --}}

            <div class="rounded-2xl bg-red-50 p-5 shadow-sm ring-1 ring-red-200">

                <p class="text-sm font-medium text-red-700">
                    Overdue
                </p>

                <p class="mt-2 text-2xl font-bold text-red-700">
                    {{ $overdueItems }}
                </p>

            </div>


            {{-- Today --}}

            <div class="rounded-2xl bg-blue-50 p-5 shadow-sm ring-1 ring-blue-200">

                <p class="text-sm font-medium text-blue-700">
                    Today
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $todayItems }}
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PROGRESS --}}
        {{-- ========================================================= --}}

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h3 class="font-bold text-gray-900">
                        Timeline Progress
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $completedItems }} of {{ $totalItems }} timeline items completed.
                    </p>

                </div>

                <span class="text-lg font-bold text-indigo-600">
                    {{ number_format($completionPercentage, 1) }}%
                </span>

            </div>


            <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-indigo-500 transition-all"
                    style="width: {{ min(100, max(0, $completionPercentage)) }}%"
                ></div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TIMELINE --}}
        {{-- ========================================================= --}}

        <div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="border-b border-gray-200 px-6 py-5">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Wedding Timeline
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Your wedding events arranged chronologically.
                        </p>

                    </div>


                    @if ($totalItems > 0)

                        <span class="text-sm text-gray-400">
                            {{ $totalItems }}
                            {{ $totalItems === 1 ? 'item' : 'items' }}
                        </span>

                    @endif

                </div>

            </div>


            @if ($groupedTimeline->count())

                <div class="p-6">

                    <div class="space-y-8">

                        @foreach ($groupedTimeline as $date => $items)

                            <div>

                                {{-- DATE HEADER --}}

                                <div class="mb-4 flex items-center gap-3">

                                    <div class="h-3 w-3 rounded-full bg-indigo-500"></div>

                                    <h4 class="font-bold text-gray-900">
                                        {{ $formatter->date(\Carbon\Carbon::parse($date)) }}
                                    </h4>

                                </div>


                                {{-- TIMELINE LINE --}}

                                <div class="ml-1 border-l-2 border-indigo-100 pl-6">

                                    <div class="space-y-4">

                                        @foreach ($items as $item)

                                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">

                                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">


                                                    {{-- ================================================= --}}
                                                    {{-- ITEM INFORMATION --}}
                                                    {{-- ================================================= --}}

                                                    <div class="min-w-0">

                                                        <div class="flex flex-wrap items-center gap-2">

                                                            <h5
                                                                class="font-semibold
                                                                {{ $item->status === 'completed'
                                                                    ? 'text-gray-400 line-through'
                                                                    : 'text-gray-900' }}"
                                                            >
                                                                {{ $item->title }}
                                                            </h5>


                                                            {{-- PRIORITY --}}

                                                            @if ($item->priority === 'high')

                                                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                                    High
                                                                </span>

                                                            @elseif ($item->priority === 'medium')

                                                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                                    Medium
                                                                </span>

                                                            @else

                                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                                    Low
                                                                </span>

                                                            @endif


                                                            {{-- STATUS --}}

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


                                                            {{-- DATE STATUS --}}

                                                            @if ($item->is_today)

                                                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                                    Today
                                                                </span>

                                                            @elseif ($item->is_past)

                                                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                                    Overdue
                                                                </span>

                                                            @endif

                                                        </div>


                                                        {{-- TIME / LOCATION / CATEGORY --}}

                                                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">

                                                            @if ($item->start_time)

                                                                <span>

                                                                    🕐

                                                                    {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}

                                                                    @if ($item->end_time)

                                                                        -
                                                                        {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}

                                                                    @endif

                                                                </span>

                                                            @endif


                                                            @if ($item->location)

                                                                <span>
                                                                    📍 {{ $item->location }}
                                                                </span>

                                                            @endif


                                                            <span>
                                                                {{ ucfirst($item->category) }}
                                                            </span>

                                                        </div>


                                                        {{-- DESCRIPTION --}}

                                                        @if ($item->description)

                                                            <p class="mt-3 text-sm text-gray-600">
                                                                {{ $item->description }}
                                                            </p>

                                                        @endif

                                                    </div>


                                                    {{-- ================================================= --}}
                                                    {{-- ACTION BUTTONS --}}
                                                    {{-- ================================================= --}}

                                                    <div class="flex shrink-0 items-center gap-2">

                                                        {{-- EDIT --}}

                                                        <button
                                                            type="button"
                                                            onclick="openEditTimelineModal({{ $item->id }})"
                                                            class="inline-flex h-9 min-w-[70px] items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                                        >
                                                            Edit
                                                        </button>


                                                        {{-- DELETE --}}

                                                        <form
                                                            method="POST"
                                                            action="{{ route('wedding.timeline.destroy', $item) }}"
                                                            onsubmit="return confirm('Delete this timeline item?');"
                                                            class="m-0 flex"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="inline-flex h-9 min-w-[70px] items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                                            >
                                                                Delete
                                                            </button>

                                                        </form>

                                                    </div>

                                                </div>


                                                {{-- ================================================= --}}
                                                {{-- EDIT MODAL --}}
                                                {{-- ================================================= --}}

                                                <div
                                                    id="edit-timeline-{{ $item->id }}"
                                                    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
                                                    onclick="if(event.target === this) closeEditTimelineModal({{ $item->id }})"
                                                >

                                                    <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl">

                                                        {{-- MODAL HEADER --}}

                                                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                                                            <div>

                                                                <p class="text-sm font-medium text-indigo-600">
                                                                    Wedding Timeline
                                                                </p>

                                                                <h3 class="mt-1 text-lg font-bold text-gray-900">
                                                                    Edit Timeline Item
                                                                </h3>

                                                                <p class="mt-1 text-sm text-gray-500">
                                                                    Update the event details.
                                                                </p>

                                                            </div>


                                                            <button
                                                                type="button"
                                                                onclick="closeEditTimelineModal({{ $item->id }})"
                                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                                            >
                                                                ✕
                                                            </button>

                                                        </div>


                                                        {{-- MODAL FORM --}}

                                                        <form
                                                            method="POST"
                                                            action="{{ route('wedding.timeline.update', $item) }}"
                                                            class="grid gap-5 p-6 md:grid-cols-2"
                                                        >

                                                            @csrf

                                                            @method('PATCH')


                                                            {{-- TITLE --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Title
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    name="title"
                                                                    value="{{ $item->title }}"
                                                                    required
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                            </div>


                                                            {{-- DATE --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Date
                                                                </label>

                                                                <input
                                                                    type="date"
                                                                    name="event_date"
                                                                    value="{{ $item->event_date->format('Y-m-d') }}"
                                                                    required
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                            </div>


                                                            {{-- START TIME --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Start Time
                                                                </label>

                                                                <input
                                                                    type="time"
                                                                    name="start_time"
                                                                    value="{{ $item->start_time }}"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                            </div>


                                                            {{-- END TIME --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    End Time
                                                                </label>

                                                                <input
                                                                    type="time"
                                                                    name="end_time"
                                                                    value="{{ $item->end_time }}"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                            </div>


                                                            {{-- LOCATION --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Location
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    name="location"
                                                                    value="{{ $item->location }}"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                            </div>


                                                            {{-- CATEGORY --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Category
                                                                </label>

                                                                <select
                                                                    name="category"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                                    <option value="preparation" @selected($item->category === 'preparation')>
                                                                        Preparation
                                                                    </option>

                                                                    <option value="ceremony" @selected($item->category === 'ceremony')>
                                                                        Ceremony
                                                                    </option>

                                                                    <option value="reception" @selected($item->category === 'reception')>
                                                                        Reception
                                                                    </option>

                                                                    <option value="meeting" @selected($item->category === 'meeting')>
                                                                        Meeting
                                                                    </option>

                                                                    <option value="payment" @selected($item->category === 'payment')>
                                                                        Payment
                                                                    </option>

                                                                    <option value="appointment" @selected($item->category === 'appointment')>
                                                                        Appointment
                                                                    </option>

                                                                    <option value="general" @selected($item->category === 'general')>
                                                                        General
                                                                    </option>

                                                                </select>

                                                            </div>


                                                            {{-- STATUS --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Status
                                                                </label>

                                                                <select
                                                                    name="status"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                                    <option value="planned" @selected($item->status === 'planned')>
                                                                        Planned
                                                                    </option>

                                                                    <option value="in_progress" @selected($item->status === 'in_progress')>
                                                                        In Progress
                                                                    </option>

                                                                    <option value="completed" @selected($item->status === 'completed')>
                                                                        Completed
                                                                    </option>

                                                                </select>

                                                            </div>


                                                            {{-- PRIORITY --}}

                                                            <div>

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Priority
                                                                </label>

                                                                <select
                                                                    name="priority"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >

                                                                    <option value="low" @selected($item->priority === 'low')>
                                                                        Low
                                                                    </option>

                                                                    <option value="medium" @selected($item->priority === 'medium')>
                                                                        Medium
                                                                    </option>

                                                                    <option value="high" @selected($item->priority === 'high')>
                                                                        High
                                                                    </option>

                                                                </select>

                                                            </div>


                                                            {{-- DESCRIPTION --}}

                                                            <div class="md:col-span-2">

                                                                <label class="block text-sm font-semibold text-gray-700">
                                                                    Description
                                                                </label>

                                                                <textarea
                                                                    name="description"
                                                                    rows="3"
                                                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                >{{ $item->description }}</textarea>

                                                            </div>


                                                            {{-- BUTTONS --}}

                                                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                                                                <button
                                                                    type="button"
                                                                    onclick="closeEditTimelineModal({{ $item->id }})"
                                                                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                                                >
                                                                    Cancel
                                                                </button>

                                                                <button
                                                                    type="submit"
                                                                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                                                >
                                                                    Save Changes
                                                                </button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                {{-- EMPTY STATE --}}

                <div class="px-6 py-14 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-3xl">
                        📅
                    </div>

                    <h3 class="mt-5 font-bold text-gray-900">
                        No timeline items yet
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                        Start organizing your wedding schedule by adding your first timeline item.
                    </p>

                    <button
                        type="button"
                        onclick="openAddTimelineModal()"
                        class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        <span class="text-lg leading-none">
                            +
                        </span>

                        Add First Timeline Item
                    </button>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- ADD TIMELINE MODAL --}}
{{-- ================================================================ --}}

<div
    id="add-timeline-modal"
    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
    onclick="if(event.target === this) closeAddTimelineModal()"
>

    <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl">


        {{-- MODAL HEADER --}}

        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

            <div>

                <p class="text-sm font-medium text-indigo-600">
                    Wedding Timeline
                </p>

                <h3 class="mt-1 text-lg font-bold text-gray-900">
                    Add Timeline Item
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Add an event, appointment, preparation, payment, or wedding-day activity.
                </p>

            </div>


            <button
                type="button"
                onclick="closeAddTimelineModal()"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
            >
                ✕
            </button>

        </div>


        {{-- MODAL FORM --}}

        <form
            method="POST"
            action="{{ route('wedding.timeline.store') }}"
            class="grid gap-5 p-6 md:grid-cols-2"
        >

            @csrf


            {{-- TITLE --}}

            <div>

                <label
                    for="timeline_title"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Title
                </label>

                <input
                    type="text"
                    name="title"
                    id="timeline_title"
                    value="{{ old('title') }}"
                    required
                    placeholder="e.g. Final Dress Fitting"
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- DATE --}}

            <div>

                <label
                    for="timeline_event_date"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Date
                </label>

                <input
                    type="date"
                    name="event_date"
                    id="timeline_event_date"
                    value="{{ old('event_date') }}"
                    required
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- START TIME --}}

            <div>

                <label
                    for="timeline_start_time"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Start Time
                </label>

                <input
                    type="time"
                    name="start_time"
                    id="timeline_start_time"
                    value="{{ old('start_time') }}"
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- END TIME --}}

            <div>

                <label
                    for="timeline_end_time"
                    class="block text-sm font-semibold text-gray-700"
                >
                    End Time
                </label>

                <input
                    type="time"
                    name="end_time"
                    id="timeline_end_time"
                    value="{{ old('end_time') }}"
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- LOCATION --}}

            <div>

                <label
                    for="timeline_location"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Location
                </label>

                <input
                    type="text"
                    name="location"
                    id="timeline_location"
                    value="{{ old('location') }}"
                    placeholder="e.g. Bridal Studio"
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- CATEGORY --}}

            <div>

                <label
                    for="timeline_category"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Category
                </label>

                <select
                    name="category"
                    id="timeline_category"
                    required
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option
                        value="preparation"
                        @selected(old('category', 'preparation') === 'preparation')
                    >
                        Preparation
                    </option>

                    <option
                        value="ceremony"
                        @selected(old('category') === 'ceremony')
                    >
                        Ceremony
                    </option>

                    <option
                        value="reception"
                        @selected(old('category') === 'reception')
                    >
                        Reception
                    </option>

                    <option
                        value="meeting"
                        @selected(old('category') === 'meeting')
                    >
                        Meeting
                    </option>

                    <option
                        value="payment"
                        @selected(old('category') === 'payment')
                    >
                        Payment
                    </option>

                    <option
                        value="appointment"
                        @selected(old('category') === 'appointment')
                    >
                        Appointment
                    </option>

                    <option
                        value="general"
                        @selected(old('category') === 'general')
                    >
                        General
                    </option>

                </select>

            </div>


            {{-- STATUS --}}

            <div>

                <label
                    for="timeline_status"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Status
                </label>

                <select
                    name="status"
                    id="timeline_status"
                    required
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option
                        value="planned"
                        @selected(old('status', 'planned') === 'planned')
                    >
                        Planned
                    </option>

                    <option
                        value="in_progress"
                        @selected(old('status') === 'in_progress')
                    >
                        In Progress
                    </option>

                    <option
                        value="completed"
                        @selected(old('status') === 'completed')
                    >
                        Completed
                    </option>

                </select>

            </div>


            {{-- PRIORITY --}}

            <div>

                <label
                    for="timeline_priority"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Priority
                </label>

                <select
                    name="priority"
                    id="timeline_priority"
                    required
                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option
                        value="low"
                        @selected(old('priority') === 'low')
                    >
                        Low
                    </option>

                    <option
                        value="medium"
                        @selected(old('priority', 'medium') === 'medium')
                    >
                        Medium
                    </option>

                    <option
                        value="high"
                        @selected(old('priority') === 'high')
                    >
                        High
                    </option>

                </select>

            </div>


            {{-- DESCRIPTION --}}

            <div class="md:col-span-2">

                <label
                    for="timeline_description"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Description
                    <span class="font-normal text-gray-400">
                        (Optional)
                    </span>
                </label>

                <textarea
                    name="description"
                    id="timeline_description"
                    rows="3"
                    placeholder="Add details about this timeline item..."
                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description') }}</textarea>

            </div>


            {{-- BUTTONS --}}

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                <button
                    type="button"
                    onclick="closeAddTimelineModal()"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Add Timeline Item
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ================================================================ --}}
{{-- MODAL JAVASCRIPT --}}
{{-- ================================================================ --}}

<script>

    function openAddTimelineModal() {

        const modal = document.getElementById('add-timeline-modal');

        if (!modal) return;

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {

            const input = document.getElementById('timeline_title');

            if (input) {
                input.focus();
            }

        }, 100);

    }


    function closeAddTimelineModal() {

        const modal = document.getElementById('add-timeline-modal');

        if (!modal) return;

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    function openEditTimelineModal(itemId) {

        const modal = document.getElementById('edit-timeline-' + itemId);

        if (!modal) return;

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

    }


    function closeEditTimelineModal(itemId) {

        const modal = document.getElementById('edit-timeline-' + itemId);

        if (!modal) return;

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    // Close all modals using ESC

    document.addEventListener('keydown', function(event) {

        if (event.key !== 'Escape') {
            return;
        }


        // Close Add Modal

        closeAddTimelineModal();


        // Close Edit Modals

        document.querySelectorAll('[id^="edit-timeline-"]').forEach(function(modal) {

            modal.classList.add('hidden');

        });


        document.body.classList.remove('overflow-hidden');

    });

</script>

</x-app-layout>
