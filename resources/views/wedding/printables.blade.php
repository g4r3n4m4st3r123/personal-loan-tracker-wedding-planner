<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-indigo-600">
                    Wedding Planner
                </p>

                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                    Reports & Printables
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Prepare clean printable copies of your wedding planning information.
                </p>

            </div>


            <div class="flex flex-wrap gap-2">

                <a
                    href="{{ route('reports.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    ← Reports
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    🖨 Print / Save PDF
                </button>

            </div>

        </div>

    </x-slot>


    <div class="py-8 print:py-0">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 print:max-w-none print:px-0">


            @if (!$wedding)

                <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-200">

                    <div class="text-5xl">
                        💍
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-gray-900">
                        No wedding found
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Create your wedding first before generating printables.
                    </p>

                    <a
                        href="{{ route('wedding.index') }}"
                        class="mt-5 inline-flex rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white"
                    >
                        Wedding Overview
                    </a>

                </div>

            @else


                {{-- ===================================================== --}}
                {{-- PRINT HEADER --}}
                {{-- ===================================================== --}}

                <div class="mb-6 rounded-3xl bg-slate-900 p-7 text-white print:mb-8 print:rounded-none">

                    <p class="text-sm font-medium text-indigo-300">
                        Wedding Planner
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight">
                        {{ $wedding->wedding_name }}
                    </h1>


                    @if ($wedding->partner_name)

                        <p class="mt-1 text-lg text-slate-300">
                            With {{ $wedding->partner_name }}
                        </p>

                    @endif


                    <div class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-300">

                        @if ($wedding->wedding_date)

                            <span>
                                📅 {{ $wedding->wedding_date->format('F d, Y') }}
                            </span>

                        @endif


                        @if ($wedding->venue)

                            <span>
                                📍 {{ $wedding->venue }}
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- PRINTABLE SELECTOR --}}
                {{-- ===================================================== --}}

                <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm print:hidden">

                    <div class="flex flex-wrap gap-2">

                        @foreach ([
                            'complete' => 'Complete Planner',
                            'budget' => 'Budget Summary',
                            'guests' => 'Guest List',
                            'seating' => 'Seating Chart',
                            'vendors' => 'Vendor Directory',
                            'checklist' => 'Checklist',
                            'timeline' => 'Timeline',
                            'day-of' => 'Day-of Schedule',
                        ] as $key => $label)

                            <a
                                href="{{ route('wedding.printables', $key) }}"
                                class="rounded-lg border px-4 py-2 text-sm font-semibold transition
                                {{ $type === $key
                                    ? 'border-indigo-600 bg-indigo-600 text-white'
                                    : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50' }}"
                            >
                                {{ $label }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- BUDGET --}}
                {{-- ===================================================== --}}

                @if ($type === 'budget' || $type === 'complete')

                    <section class="print-section rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-indigo-600">
                                Financial Planning
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Wedding Budget Summary
                            </h2>

                        </div>


                        <div class="grid gap-4 sm:grid-cols-4 print:grid-cols-4">

                            <div class="rounded-xl bg-gray-50 p-4">

                                <p class="text-xs text-gray-500">
                                    Total Budget
                                </p>

                                <p class="mt-1 text-lg font-bold">
                                    {{ $formatter->money($totalWeddingBudget) }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-4">

                                <p class="text-xs text-gray-500">
                                    Planned
                                </p>

                                <p class="mt-1 text-lg font-bold">
                                    {{ $formatter->money($totalPlanned) }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-4">

                                <p class="text-xs text-gray-500">
                                    Actual
                                </p>

                                <p class="mt-1 text-lg font-bold text-rose-600">
                                    {{ $formatter->money($totalActual) }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-emerald-50 p-4">

                                <p class="text-xs text-emerald-600">
                                    Remaining
                                </p>

                                <p class="mt-1 text-lg font-bold text-emerald-700">
                                    {{ $formatter->money($totalRemaining) }}
                                </p>

                            </div>

                        </div>


                        @if ($budgets->count())

                            <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">

                                <table class="min-w-full divide-y divide-gray-200">

                                    <thead class="bg-gray-50">

                                        <tr>

                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                                Category
                                            </th>

                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                                Planned
                                            </th>

                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                                Actual
                                            </th>

                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                                Remaining
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-gray-100">

                                        @foreach ($budgets as $budget)

                                            <tr>

                                                <td class="px-4 py-3">

                                                    <p class="font-semibold text-gray-900">
                                                        {{ $budget->category }}
                                                    </p>

                                                    @if ($budget->notes)

                                                        <p class="mt-1 text-xs text-gray-500">
                                                            {{ $budget->notes }}
                                                        </p>

                                                    @endif

                                                </td>

                                                <td class="px-4 py-3 text-right text-sm">
                                                    {{ $formatter->money($budget->planned_amount) }}
                                                </td>

                                                <td class="px-4 py-3 text-right text-sm font-semibold text-rose-600">
                                                    {{ $formatter->money($budget->actual_amount) }}
                                                </td>

                                                <td class="px-4 py-3 text-right text-sm font-semibold text-emerald-600">
                                                    {{ $formatter->money($budget->remaining_amount) }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @endif

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- GUEST LIST --}}
                {{-- ===================================================== --}}

                @if ($type === 'guests' || $type === 'complete')

                    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:page-break-before-auto print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-rose-500">
                                Guest Management
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Guest List
                            </h2>

                        </div>


                        <div class="mb-6 grid gap-4 sm:grid-cols-4">

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="mt-1 text-xl font-bold">{{ $totalGuests }}</p>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-4">
                                <p class="text-xs text-emerald-600">Attending</p>
                                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $attendingGuests }}</p>
                            </div>

                            <div class="rounded-xl bg-amber-50 p-4">
                                <p class="text-xs text-amber-600">Pending</p>
                                <p class="mt-1 text-xl font-bold text-amber-700">{{ $pendingGuests }}</p>
                            </div>

                            <div class="rounded-xl bg-indigo-50 p-4">
                                <p class="text-xs text-indigo-600">Headcount</p>
                                <p class="mt-1 text-xl font-bold text-indigo-700">{{ $estimatedHeadcount }}</p>
                            </div>

                        </div>


                        <div class="overflow-hidden rounded-xl border border-gray-200">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Guest
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Type
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            RSVP
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Meal
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Plus One
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-gray-100">

                                    @foreach ($guests as $guest)

                                        <tr>

                                            <td class="px-4 py-3">

                                                <p class="font-semibold text-gray-900">
                                                    {{ $guest->name }}
                                                </p>

                                                @if ($guest->phone)

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        {{ $guest->phone }}
                                                    </p>

                                                @endif

                                            </td>

                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ ucwords(str_replace('_', ' ', $guest->guest_type)) }}
                                            </td>

                                            <td class="px-4 py-3 text-sm font-semibold">
                                                {{ ucfirst($guest->rsvp_status) }}
                                            </td>

                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $guest->meal_preference ?: '—' }}
                                            </td>

                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $guest->plus_one ? 'Yes' : 'No' }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- SEATING CHART --}}
                {{-- ===================================================== --}}

                @if ($type === 'seating' || $type === 'complete')

                    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-indigo-600">
                                Guest Seating
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Seating Chart
                            </h2>

                        </div>


                        @if ($tables->count())

                            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 print:grid-cols-3">

                                @foreach ($tables as $table)

                                    <div class="rounded-xl border border-gray-200 p-5">

                                        <div class="flex items-center justify-between">

                                            <div>

                                                <h3 class="font-bold text-gray-900">
                                                    {{ $table->table_name }}
                                                </h3>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $table->seatings->count() }}
                                                    / {{ $table->capacity }}
                                                    seats
                                                </p>

                                            </div>

                                            <span class="text-2xl">
                                                🪑
                                            </span>

                                        </div>


                                        <div class="mt-4 space-y-2">

                                            @forelse ($table->seatings as $seating)

                                                <div class="rounded-lg bg-gray-50 px-3 py-2">

                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $seating->guest->name }}
                                                    </p>

                                                    <p class="text-xs text-gray-400">

                                                        {{ ucfirst($seating->guest->rsvp_status) }}

                                                        @if ($seating->guest->plus_one)
                                                            · Plus-one
                                                        @endif

                                                    </p>

                                                </div>

                                            @empty

                                                <p class="text-sm text-gray-400">
                                                    No guests assigned.
                                                </p>

                                            @endforelse

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <p class="text-sm text-gray-500">
                                No seating tables have been created.
                            </p>

                        @endif

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- VENDORS --}}
                {{-- ===================================================== --}}

                @if ($type === 'vendors' || $type === 'complete')

                    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-amber-600">
                                Supplier Management
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Vendor Directory
                            </h2>

                        </div>


                        <div class="mb-6 grid gap-4 sm:grid-cols-4">

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Vendors</p>
                                <p class="mt-1 text-xl font-bold">{{ $totalVendors }}</p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Contracted</p>
                                <p class="mt-1 text-xl font-bold">{{ $formatter->money($totalAgreedAmount) }}</p>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-4">
                                <p class="text-xs text-emerald-600">Paid</p>
                                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $formatter->money($totalAmountPaid) }}</p>
                            </div>

                            <div class="rounded-xl bg-amber-50 p-4">
                                <p class="text-xs text-amber-600">Outstanding</p>
                                <p class="mt-1 text-xl font-bold text-amber-700">{{ $formatter->money($totalOutstanding) }}</p>
                            </div>

                        </div>


                        <div class="overflow-hidden rounded-xl border border-gray-200">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Vendor
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Service
                                        </th>

                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Agreed
                                        </th>

                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Paid
                                        </th>

                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Balance
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Service Date
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-gray-100">

                                    @foreach ($vendors as $vendor)

                                        <tr>

                                            <td class="px-4 py-3">

                                                <p class="font-semibold text-gray-900">
                                                    {{ $vendor->vendor_name }}
                                                </p>

                                                @if ($vendor->contact_person)

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        {{ $vendor->contact_person }}
                                                    </p>

                                                @endif

                                            </td>

                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $vendor->service_type }}
                                            </td>

                                            <td class="px-4 py-3 text-right text-sm">
                                                {{ $formatter->money($vendor->agreed_amount) }}
                                            </td>

                                            <td class="px-4 py-3 text-right text-sm text-emerald-600">
                                                {{ $formatter->money($vendor->amount_paid) }}
                                            </td>

                                            <td class="px-4 py-3 text-right text-sm font-semibold text-amber-600">
                                                {{ $formatter->money($vendor->balance) }}
                                            </td>

                                            <td class="px-4 py-3 text-sm text-gray-600">

                                                {{ $vendor->service_date
                                                    ? $formatter->date($vendor->service_date)
                                                    : '—'
                                                }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- CHECKLIST --}}
                {{-- ===================================================== --}}

                @if ($type === 'checklist' || $type === 'complete')

                    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-emerald-600">
                                Planning Progress
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Wedding Checklist
                            </h2>

                        </div>


                        <div class="mb-6 grid gap-4 sm:grid-cols-4">

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="mt-1 text-xl font-bold">{{ $totalTasks }}</p>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-4">
                                <p class="text-xs text-emerald-600">Completed</p>
                                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $completedTasks }}</p>
                            </div>

                            <div class="rounded-xl bg-amber-50 p-4">
                                <p class="text-xs text-amber-600">Pending</p>
                                <p class="mt-1 text-xl font-bold text-amber-700">{{ $pendingTasks }}</p>
                            </div>

                            <div class="rounded-xl bg-rose-50 p-4">
                                <p class="text-xs text-rose-600">Overdue</p>
                                <p class="mt-1 text-xl font-bold text-rose-700">{{ $overdueTasks }}</p>
                            </div>

                        </div>


                        <div class="space-y-3">

                            @foreach ($tasks as $task)

                                <div class="flex items-start gap-4 rounded-xl border border-gray-200 p-4">

                                    <div class="mt-0.5 text-lg">

                                        @if ($task->status === 'completed')
                                            ✓
                                        @else
                                            □
                                        @endif

                                    </div>


                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <p
                                                class="font-semibold
                                                {{ $task->status === 'completed'
                                                    ? 'text-gray-400 line-through'
                                                    : 'text-gray-900' }}"
                                            >
                                                {{ $task->task_name }}
                                            </p>

                                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>

                                        </div>


                                        @if ($task->due_date)

                                            <p class="mt-1 text-xs text-gray-400">
                                                Due {{ $formatter->date($task->due_date) }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- TIMELINE --}}
                {{-- ===================================================== --}}

                @if ($type === 'timeline' || $type === 'complete')

                    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-indigo-600">
                                Schedule
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-gray-900">
                                Wedding Timeline
                            </h2>

                        </div>


                        @if ($timelineItems->count())

                            <div class="space-y-5">

                                @foreach ($timelineItems as $item)

                                    <div class="grid gap-4 border-b border-gray-100 pb-5 md:grid-cols-[140px_1fr] print:grid-cols-[140px_1fr]">

                                        <div>

                                            <p class="font-bold text-gray-900">
                                                {{ $formatter->date($item->event_date) }}
                                            </p>

                                            @if ($item->start_time)

                                                <p class="mt-1 text-sm text-indigo-600">

                                                    {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}

                                                    @if ($item->end_time)

                                                        -
                                                        {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}

                                                    @endif

                                                </p>

                                            @endif

                                        </div>


                                        <div>

                                            <div class="flex flex-wrap items-center gap-2">

                                                <h3 class="font-semibold text-gray-900">
                                                    {{ $item->title }}
                                                </h3>

                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                </span>

                                            </div>


                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ ucfirst($item->category) }}

                                                @if ($item->location)
                                                    · {{ $item->location }}
                                                @endif
                                            </p>


                                            @if ($item->description)

                                                <p class="mt-2 text-sm text-gray-600">
                                                    {{ $item->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <p class="text-sm text-gray-500">
                                No timeline items available.
                            </p>

                        @endif

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- DAY-OF SCHEDULE --}}
                {{-- ===================================================== --}}

                @if ($type === 'day-of')

                    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm print:border print:border-gray-300 print:shadow-none">

                        <div class="mb-6 border-b border-gray-200 pb-4">

                            <p class="text-sm font-medium text-indigo-600">
                                Wedding Day
                            </p>

                            <h2 class="mt-1 text-2xl font-bold text-gray-900">
                                Day-of Schedule
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ now()->format('l, F d, Y') }}
                            </p>

                        </div>


                        @if ($dayOfItems->count())

                            <div class="space-y-4">

                                @foreach ($dayOfItems as $item)

                                    <div class="flex gap-5 rounded-xl border border-gray-200 p-5">

                                        <div class="w-28 shrink-0">

                                            @if ($item->start_time)

                                                <p class="font-bold text-gray-900">
                                                    {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                                </p>

                                                @if ($item->end_time)

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        -
                                                        {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                                    </p>

                                                @endif

                                            @else

                                                <p class="text-sm text-gray-400">
                                                    Time TBA
                                                </p>

                                            @endif

                                        </div>


                                        <div>

                                            <h3 class="font-bold text-gray-900">
                                                {{ $item->title }}
                                            </h3>

                                            <p class="mt-1 text-sm text-gray-500">

                                                {{ ucfirst($item->category) }}

                                                @if ($item->location)
                                                    · 📍 {{ $item->location }}
                                                @endif

                                            </p>


                                            @if ($item->description)

                                                <p class="mt-2 text-sm text-gray-600">
                                                    {{ $item->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="py-12 text-center">

                                <div class="text-4xl">
                                    📅
                                </div>

                                <p class="mt-4 font-semibold text-gray-900">
                                    No events scheduled today.
                                </p>

                            </div>

                        @endif

                    </section>

                @endif


                {{-- ===================================================== --}}
                {{-- PRINT FOOTER --}}
                {{-- ===================================================== --}}

                <div class="mt-8 border-t border-gray-200 pt-5 text-center text-xs text-gray-400">

                    <p>
                        {{ $wedding->wedding_name }} · Generated {{ now()->format('F d, Y') }}
                    </p>

                </div>


            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- PRINT CSS --}}
    {{-- ================================================================ --}}

    <style>

        @media print {

            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                background: white !important;
            }

            nav,
            aside,
            header,
            footer,
            button,
            a[href],
            .print\:hidden {
                display: none !important;
            }

            .print-section {
                break-inside: avoid;
            }

            table {
                break-inside: auto;
            }

            tr {
                break-inside: avoid;
                break-after: auto;
            }

        }

    </style>

</x-app-layout>