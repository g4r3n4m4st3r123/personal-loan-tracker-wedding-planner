<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Seating Arrangement
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Organize your wedding guests by table and manage seating capacity.
                </p>

            </div>


            <div class="flex flex-wrap items-center gap-2">

                <button
                    type="button"
                    onclick="openAddTableModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <span class="text-lg leading-none">
                        +
                    </span>

                    Add Table
                </button>


                <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
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

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
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


            @if (!$wedding)

                {{-- No wedding --}}

                <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-200">

                    <div class="text-5xl">
                        💍
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-gray-900">
                        Create your wedding first
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Set up your wedding before creating a seating arrangement.
                    </p>

                    <a
                        href="{{ route('wedding.index') }}"
                        class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Go to Wedding Overview
                    </a>

                </div>

            @else


                {{-- ===================================================== --}}
                {{-- SUMMARY --}}
                {{-- ===================================================== --}}

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <p class="text-sm font-medium text-gray-500">
                            Total Guests
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $totalGuests }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                        <p class="text-sm font-medium text-emerald-700">
                            Assigned
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-700">
                            {{ $assignedCount }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-amber-50 p-5 shadow-sm ring-1 ring-amber-200">

                        <p class="text-sm font-medium text-amber-700">
                            Unassigned
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-700">
                            {{ $unassignedCount }}
                        </p>

                    </div>


                    <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                        <p class="text-sm font-medium text-indigo-700">
                            Tables
                        </p>

                        <p class="mt-2 text-2xl font-bold text-indigo-700">
                            {{ $totalTables }}
                        </p>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- ASSIGN GUEST --}}
                {{-- ===================================================== --}}

                <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-sm font-medium text-indigo-600">
                                Guest Seating
                            </p>

                            <h3 class="mt-1 text-lg font-bold text-gray-900">
                                Assign a Guest
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Place an attending or pending guest at a table.
                            </p>

                        </div>

                    </div>


                    @if ($unassignedGuests->count() && $tables->count())

                        <form
                            method="POST"
                            action="{{ route('wedding.seating.assign') }}"
                            class="mt-5 grid gap-4 md:grid-cols-3"
                        >

                            @csrf


                            {{-- Guest --}}

                            <div>

                                <label
                                    for="wedding_guest_id"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Guest
                                </label>

                                <select
                                    name="wedding_guest_id"
                                    id="wedding_guest_id"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option value="">
                                        Select guest
                                    </option>

                                    @foreach ($unassignedGuests as $guest)

                                        <option value="{{ $guest->id }}">

                                            {{ $guest->name }}

                                            —
                                            {{ ucfirst($guest->rsvp_status) }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Table --}}

                            <div>

                                <label
                                    for="wedding_table_id"
                                    class="block text-sm font-semibold text-gray-700"
                                >
                                    Table
                                </label>

                                <select
                                    name="wedding_table_id"
                                    id="wedding_table_id"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option value="">
                                        Select table
                                    </option>

                                    @foreach ($tables as $table)

                                        @php
                                            $occupied = $table->seatings->count();
                                        @endphp

                                        @if ($occupied < $table->capacity)

                                            <option value="{{ $table->id }}">

                                                {{ $table->table_name }}

                                                —
                                                {{ $occupied }}/{{ $table->capacity }}

                                                seats

                                            </option>

                                        @endif

                                    @endforeach

                                </select>

                            </div>


                            {{-- Button --}}

                            <div class="flex items-end">

                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                >
                                    Assign Guest
                                </button>

                            </div>

                        </form>


                    @elseif (!$tables->count())

                        <div class="mt-5 rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center">

                            <p class="text-sm font-semibold text-gray-700">
                                No tables created yet.
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Add your first table before assigning guests.
                            </p>

                            <button
                                type="button"
                                onclick="openAddTableModal()"
                                class="mt-4 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                + Add Table
                            </button>

                        </div>


                    @else

                        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-6 text-center">

                            <p class="text-sm font-semibold text-emerald-800">
                                All available guests are assigned.
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">
                                Your current seating assignments are complete.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- ===================================================== --}}
                {{-- TABLES --}}
                {{-- ===================================================== --}}

                <div class="mt-6">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-900">
                            Wedding Tables
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            View table occupancy and assigned guests.
                        </p>

                    </div>


                    @if ($tables->count())

                        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                            @foreach ($tables as $table)

                                @php
                                    $occupied = $table->seatings->count();
                                    $remainingSeats = max(0, $table->capacity - $occupied);
                                    $occupancyPercentage = $table->capacity > 0
                                        ? ($occupied / $table->capacity) * 100
                                        : 0;
                                @endphp


                                <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                                    {{-- Table Header --}}

                                    <div class="border-b border-gray-100 p-5">

                                        <div class="flex items-start justify-between gap-3">

                                            <div>

                                                <h4 class="text-lg font-bold text-gray-900">
                                                    {{ $table->table_name }}
                                                </h4>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ $occupied }} of {{ $table->capacity }} seats occupied
                                                </p>

                                            </div>


                                            <div class="flex items-center gap-1">

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-table-{{ $table->id }}').classList.remove('hidden')"
                                                    class="rounded-lg p-2 text-indigo-600 transition hover:bg-indigo-50"
                                                    title="Edit table"
                                                >
                                                    ✏️
                                                </button>


                                                <form
                                                    method="POST"
                                                    action="{{ route('wedding.seating.tables.destroy', $table) }}"
                                                    onsubmit="return confirm('Delete this table? All guests assigned to this table will become unassigned.');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                                                        title="Delete table"
                                                    >
                                                        🗑️
                                                    </button>

                                                </form>

                                            </div>

                                        </div>


                                        <div class="mt-4">

                                            <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                                <div
                                                    class="h-full rounded-full transition-all
                                                    @if ($occupancyPercentage >= 100)
                                                        bg-rose-500
                                                    @elseif ($occupancyPercentage >= 80)
                                                        bg-amber-500
                                                    @else
                                                        bg-emerald-500
                                                    @endif"
                                                    style="width: {{ min(100, max(0, $occupancyPercentage)) }}%"
                                                ></div>

                                            </div>


                                            <div class="mt-2 flex items-center justify-between text-xs">

                                                <span class="text-gray-400">
                                                    {{ $remainingSeats }} seats remaining
                                                </span>

                                                <span class="font-semibold text-gray-600">
                                                    {{ number_format($occupancyPercentage, 0) }}%
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Guest List --}}

                                    <div class="p-5">

                                        @if ($table->seatings->count())

                                            <div class="space-y-2">

                                                @foreach ($table->seatings as $seating)

                                                    <div class="flex items-center justify-between rounded-xl bg-gray-50 px-3 py-3">

                                                        <div class="min-w-0">

                                                            <p class="truncate text-sm font-semibold text-gray-800">
                                                                {{ $seating->guest->name }}
                                                            </p>

                                                            <p class="mt-0.5 text-xs text-gray-400">
                                                                {{ ucfirst($seating->guest->rsvp_status) }}

                                                                @if ($seating->guest->plus_one)
                                                                    · Plus-one
                                                                @endif
                                                            </p>

                                                        </div>


                                                        <form
                                                            method="POST"
                                                            action="{{ route('wedding.seating.remove', $seating) }}"
                                                            onsubmit="return confirm('Remove this guest from the table?');"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="rounded-lg px-2 py-1 text-xs font-semibold text-red-500 hover:bg-red-50"
                                                            >
                                                                Remove
                                                            </button>

                                                        </form>

                                                    </div>

                                                @endforeach

                                            </div>

                                        @else

                                            <div class="rounded-xl border border-dashed border-gray-200 p-5 text-center">

                                                <p class="text-sm font-semibold text-gray-600">
                                                    No guests assigned
                                                </p>

                                                <p class="mt-1 text-xs text-gray-400">
                                                    Assign guests using the form above.
                                                </p>

                                            </div>

                                        @endif

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- EDIT TABLE MODAL --}}
                                {{-- ================================================= --}}

                                <div
                                    id="edit-table-{{ $table->id }}"
                                    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
                                    onclick="if(event.target === this) this.classList.add('hidden')"
                                >

                                    <div class="mx-auto w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">

                                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                                            <div>

                                                <p class="text-sm font-medium text-indigo-600">
                                                    Seating Arrangement
                                                </p>

                                                <h3 class="mt-1 text-lg font-bold text-gray-900">
                                                    Edit Table
                                                </h3>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="document.getElementById('edit-table-{{ $table->id }}').classList.add('hidden')"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100"
                                            >
                                                ✕
                                            </button>

                                        </div>


                                        <form
                                            method="POST"
                                            action="{{ route('wedding.seating.tables.update', $table) }}"
                                            class="space-y-5 p-6"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Table Name
                                                </label>

                                                <input
                                                    type="text"
                                                    name="table_name"
                                                    value="{{ $table->table_name }}"
                                                    required
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Capacity
                                                </label>

                                                <input
                                                    type="number"
                                                    name="capacity"
                                                    value="{{ $table->capacity }}"
                                                    min="{{ max(1, $occupied) }}"
                                                    max="100"
                                                    required
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                                <p class="mt-1 text-xs text-gray-400">
                                                    Current occupancy: {{ $occupied }}
                                                </p>

                                            </div>


                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Notes
                                                </label>

                                                <textarea
                                                    name="notes"
                                                    rows="3"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm"
                                                >{{ $table->notes }}</textarea>

                                            </div>


                                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-table-{{ $table->id }}').classList.add('hidden')"
                                                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                                                >
                                                    Save Changes
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-2xl bg-white px-6 py-14 text-center shadow-sm ring-1 ring-gray-200">

                            <div class="text-4xl">
                                🪑
                            </div>

                            <h3 class="mt-4 font-bold text-gray-900">
                                No tables yet
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Create your first table to start arranging your guests.
                            </p>

                            <button
                                type="button"
                                onclick="openAddTableModal()"
                                class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                + Add First Table
                            </button>

                        </div>

                    @endif

                </div>


                {{-- ===================================================== --}}
                {{-- UNASSIGNED GUESTS --}}
                {{-- ===================================================== --}}

                @if ($unassignedGuests->count())

                    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="mb-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Unassigned Guests
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                These guests have not yet been assigned to a table.
                            </p>

                        </div>


                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach ($unassignedGuests as $guest)

                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                                    <div class="flex items-start justify-between gap-3">

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $guest->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ ucwords(str_replace('_', ' ', $guest->guest_type)) }}
                                            </p>

                                        </div>


                                        @if ($guest->rsvp_status === 'attending')

                                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                Attending
                                            </span>

                                        @elseif ($guest->rsvp_status === 'pending')

                                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                Pending
                                            </span>

                                        @else

                                            <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                Declined
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- ADD TABLE MODAL --}}
    {{-- ================================================================ --}}

    <div
        id="add-table-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
        onclick="if(event.target === this) closeAddTableModal()"
    >

        <div class="mx-auto w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">


            {{-- HEADER --}}

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Seating Arrangement
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Add Table
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Create a table for your wedding guests.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeAddTableModal()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100"
                >
                    ✕
                </button>

            </div>


            {{-- FORM --}}

            <form
                method="POST"
                action="{{ route('wedding.seating.tables.store') }}"
                class="space-y-5 p-6"
            >

                @csrf


                {{-- Table Name --}}

                <div>

                    <label
                        for="table_name"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Table Name
                    </label>

                    <input
                        type="text"
                        name="table_name"
                        id="table_name"
                        value="{{ old('table_name') }}"
                        required
                        placeholder="e.g. Table 1"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Capacity --}}

                <div>

                    <label
                        for="capacity"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Table Capacity
                    </label>

                    <input
                        type="number"
                        name="capacity"
                        id="capacity"
                        value="{{ old('capacity', 8) }}"
                        min="1"
                        max="100"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p class="mt-1 text-xs text-gray-400">
                        Maximum number of guests this table can accommodate.
                    </p>

                </div>


                {{-- Notes --}}

                <div>

                    <label
                        for="table_notes"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Notes
                        <span class="font-normal text-gray-400">
                            (Optional)
                        </span>
                    </label>

                    <textarea
                        name="notes"
                        id="table_notes"
                        rows="3"
                        placeholder="e.g. Bride's family"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('notes') }}</textarea>

                </div>


                {{-- BUTTONS --}}

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                    <button
                        type="button"
                        onclick="closeAddTableModal()"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Add Table
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================================ --}}

    <script>

        function openAddTableModal() {

            const modal = document.getElementById('add-table-modal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');


            setTimeout(function () {

                const input = document.getElementById('table_name');

                if (input) {
                    input.focus();
                }

            }, 100);

        }


        function closeAddTableModal() {

            const modal = document.getElementById('add-table-modal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

        }


        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }


            closeAddTableModal();


            document
                .querySelectorAll('[id^="edit-table-"]')
                .forEach(function (modal) {

                    modal.classList.add('hidden');

                });


            document.body.classList.remove('overflow-hidden');

        });


        @if ($errors->any())

            document.addEventListener('DOMContentLoaded', function () {

                openAddTableModal();

            });

        @endif

    </script>

</x-app-layout>