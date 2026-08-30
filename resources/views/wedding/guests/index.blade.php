<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Wedding Guests
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Manage your guest list, RSVP status, plus-ones, and meal preferences.
                </p>
            </div>


            {{-- HEADER ACTIONS --}}
            <div class="flex flex-wrap gap-2">

                {{-- Add Guest --}}
                <button
                    type="button"
                    onclick="document.getElementById('add-guest-modal').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <span class="text-base">+</span>
                    Add Guest
                </button>

<!--                 {{-- Wedding Overview --}}
                <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    ← Wedding Overview
                </a> -->

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


            {{-- ========================================================= --}}
            {{-- GUEST STATISTICS --}}
            {{-- ========================================================= --}}

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">


                {{-- Total Guests --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Total Guests
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalGuests }}
                    </p>

                </div>


                {{-- Attending --}}
                <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                    <p class="text-sm font-medium text-emerald-700">
                        Attending
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $attendingGuests }}
                    </p>

                </div>


                {{-- Pending --}}
                <div class="rounded-2xl bg-amber-50 p-5 shadow-sm ring-1 ring-amber-200">

                    <p class="text-sm font-medium text-amber-700">
                        Pending
                    </p>

                    <p class="mt-2 text-2xl font-bold text-amber-700">
                        {{ $pendingGuests }}
                    </p>

                </div>


                {{-- Declined --}}
                <div class="rounded-2xl bg-red-50 p-5 shadow-sm ring-1 ring-red-200">

                    <p class="text-sm font-medium text-red-700">
                        Declined
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-700">
                        {{ $declinedGuests }}
                    </p>

                </div>


                {{-- Estimated Headcount --}}
                <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                    <p class="text-sm font-medium text-indigo-700">
                        Estimated Headcount
                    </p>

                    <p class="mt-2 text-2xl font-bold text-indigo-700">
                        {{ $estimatedHeadcount }}
                    </p>

                    <p class="mt-1 text-xs text-indigo-600">
                        Attending + plus-ones
                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SEARCH & FILTER --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <form
                    method="GET"
                    action="{{ route('wedding.guests') }}"
                    class="grid gap-4 md:grid-cols-4"
                >

                    {{-- Search --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-gray-700">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search name, phone, or email"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- RSVP --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700">
                            RSVP
                        </label>

                        <select
                            name="rsvp_status"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="pending"
                                {{ request('rsvp_status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="attending"
                                {{ request('rsvp_status') === 'attending' ? 'selected' : '' }}
                            >
                                Attending
                            </option>

                            <option
                                value="declined"
                                {{ request('rsvp_status') === 'declined' ? 'selected' : '' }}
                            >
                                Declined
                            </option>

                        </select>

                    </div>


                    {{-- Guest Type --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700">
                            Guest Type
                        </label>

                        <select
                            name="guest_type"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                All
                            </option>

                            <option value="bride_side"
                                {{ request('guest_type') === 'bride_side' ? 'selected' : '' }}>
                                Bride Side
                            </option>

                            <option value="groom_side"
                                {{ request('guest_type') === 'groom_side' ? 'selected' : '' }}>
                                Groom Side
                            </option>

                            <option value="family"
                                {{ request('guest_type') === 'family' ? 'selected' : '' }}>
                                Family
                            </option>

                            <option value="friend"
                                {{ request('guest_type') === 'friend' ? 'selected' : '' }}>
                                Friend
                            </option>

                            <option value="colleague"
                                {{ request('guest_type') === 'colleague' ? 'selected' : '' }}>
                                Colleague
                            </option>

                            <option value="other"
                                {{ request('guest_type') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Filter Buttons --}}
                    <div class="flex items-end gap-2 md:col-span-4">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            Filter
                        </button>

                        <a
                            href="{{ route('wedding.guests') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Clear
                        </a>

                    </div>

                </form>

            </div>


            {{-- ========================================================= --}}
            {{-- GUEST LIST --}}
            {{-- ========================================================= --}}

            <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Guest List
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage your guests and RSVP details.
                    </p>

                </div>


                @if ($guests->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Guest
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Type
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        RSVP
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Meal
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Plus One
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($guests as $guest)

                                    <tr class="transition hover:bg-gray-50">


                                        {{-- Guest --}}
                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-900">
                                                {{ $guest->name }}
                                            </p>

                                            @if ($guest->phone)

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $guest->phone }}
                                                </p>

                                            @endif

                                            @if ($guest->email)

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $guest->email }}
                                                </p>

                                            @endif

                                        </td>


                                        {{-- Type --}}
                                        <td class="px-6 py-5 text-sm text-gray-600">

                                            {{ ucwords(str_replace('_', ' ', $guest->guest_type)) }}

                                        </td>


                                        {{-- RSVP --}}
                                        <td class="px-6 py-5">

                                            @if ($guest->rsvp_status === 'attending')

                                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Attending
                                                </span>

                                            @elseif ($guest->rsvp_status === 'declined')

                                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                    Declined
                                                </span>

                                            @else

                                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Pending
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Meal --}}
                                        <td class="px-6 py-5 text-sm text-gray-600">

                                            {{ $guest->meal_preference ?: '—' }}

                                        </td>


                                        {{-- Plus One --}}
                                        <td class="px-6 py-5 text-sm text-gray-600">

                                            {{ $guest->plus_one ? 'Yes' : 'No' }}

                                        </td>


                                        {{-- Actions --}}
                                        <td class="px-6 py-5">

                                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">

                                                {{-- Edit --}}
                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-guest-{{ $guest->id }}').classList.remove('hidden')"
                                                    class="inline-flex h-9 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                                >
                                                    Edit
                                                </button>


                                                {{-- Delete --}}
                                                <form
                                                    method="POST"
                                                    action="{{ route('wedding.guests.destroy', $guest) }}"
                                                    onsubmit="return confirm('Delete this guest?');"
                                                    class="inline-flex"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex h-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>


                                    {{-- ========================================================= --}}
                                    {{-- EDIT GUEST MODAL --}}
                                    {{-- ========================================================= --}}

                                    <div
                                        id="edit-guest-{{ $guest->id }}"
                                        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8"
                                    >

                                        <div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">

                                            {{-- Modal Header --}}
                                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">

                                                <div>

                                                    <h3 class="text-lg font-bold text-gray-900">
                                                        Edit Guest
                                                    </h3>

                                                    <p class="mt-1 text-sm text-gray-500">
                                                        Update guest and RSVP information.
                                                    </p>

                                                </div>

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-guest-{{ $guest->id }}').classList.add('hidden')"
                                                    class="rounded-lg px-3 py-2 text-lg font-semibold text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                                                >
                                                    ✕
                                                </button>

                                            </div>


                                            <form
                                                method="POST"
                                                action="{{ route('wedding.guests.update', $guest) }}"
                                                class="mt-6 grid gap-5 md:grid-cols-2"
                                            >

                                                @csrf

                                                @method('PATCH')


                                                {{-- Name --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Guest Name
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ $guest->name }}"
                                                        required
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                </div>


                                                {{-- Guest Type --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Guest Type
                                                    </label>

                                                    <select
                                                        name="guest_type"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option value="bride_side" {{ $guest->guest_type === 'bride_side' ? 'selected' : '' }}>
                                                            Bride Side
                                                        </option>

                                                        <option value="groom_side" {{ $guest->guest_type === 'groom_side' ? 'selected' : '' }}>
                                                            Groom Side
                                                        </option>

                                                        <option value="family" {{ $guest->guest_type === 'family' ? 'selected' : '' }}>
                                                            Family
                                                        </option>

                                                        <option value="friend" {{ $guest->guest_type === 'friend' ? 'selected' : '' }}>
                                                            Friend
                                                        </option>

                                                        <option value="colleague" {{ $guest->guest_type === 'colleague' ? 'selected' : '' }}>
                                                            Colleague
                                                        </option>

                                                        <option value="other" {{ $guest->guest_type === 'other' ? 'selected' : '' }}>
                                                            Other
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- RSVP --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        RSVP Status
                                                    </label>

                                                    <select
                                                        name="rsvp_status"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option value="pending" {{ $guest->rsvp_status === 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>

                                                        <option value="attending" {{ $guest->rsvp_status === 'attending' ? 'selected' : '' }}>
                                                            Attending
                                                        </option>

                                                        <option value="declined" {{ $guest->rsvp_status === 'declined' ? 'selected' : '' }}>
                                                            Declined
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- Meal --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Meal Preference
                                                    </label>

                                                    <select
                                                        name="meal_preference"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                        <option value="">
                                                            Not specified
                                                        </option>

                                                        <option value="Regular" {{ $guest->meal_preference === 'Regular' ? 'selected' : '' }}>
                                                            Regular
                                                        </option>

                                                        <option value="Vegetarian" {{ $guest->meal_preference === 'Vegetarian' ? 'selected' : '' }}>
                                                            Vegetarian
                                                        </option>

                                                        <option value="Vegan" {{ $guest->meal_preference === 'Vegan' ? 'selected' : '' }}>
                                                            Vegan
                                                        </option>

                                                        <option value="Halal" {{ $guest->meal_preference === 'Halal' ? 'selected' : '' }}>
                                                            Halal
                                                        </option>

                                                        <option value="Kids Meal" {{ $guest->meal_preference === 'Kids Meal' ? 'selected' : '' }}>
                                                            Kids Meal
                                                        </option>

                                                        <option value="Other" {{ $guest->meal_preference === 'Other' ? 'selected' : '' }}>
                                                            Other
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- Phone --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Phone
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="phone"
                                                        value="{{ $guest->phone }}"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                </div>


                                                {{-- Email --}}
                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Email
                                                    </label>

                                                    <input
                                                        type="email"
                                                        name="email"
                                                        value="{{ $guest->email }}"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                </div>


                                                {{-- Plus One --}}
                                                <div class="md:col-span-2">

                                                    <label class="flex items-center gap-3">

                                                        <input
                                                            type="checkbox"
                                                            name="plus_one"
                                                            value="1"
                                                            {{ $guest->plus_one ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        >

                                                        <span class="text-sm font-medium text-gray-700">
                                                            Guest has a plus-one
                                                        </span>

                                                    </label>

                                                </div>


                                                {{-- Notes --}}
                                                <div class="md:col-span-2">

                                                    <label class="block text-sm font-semibold text-gray-700">
                                                        Notes
                                                    </label>

                                                    <textarea
                                                        name="notes"
                                                        rows="3"
                                                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >{{ $guest->notes }}</textarea>

                                                </div>


                                                {{-- Buttons --}}
                                                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('edit-guest-{{ $guest->id }}').classList.add('hidden')"
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

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="text-4xl">
                            👥
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            No guests found
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Click "Add Guest" above to add your first wedding guest.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADD GUEST MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="add-guest-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8"
    >

        <div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Add Guest
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Add someone to your wedding guest list.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="document.getElementById('add-guest-modal').classList.add('hidden')"
                    class="rounded-lg px-3 py-2 text-lg font-semibold text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                >
                    ✕
                </button>

            </div>


            {{-- Add Guest Form --}}
            <form
                method="POST"
                action="{{ route('wedding.guests.store') }}"
                class="mt-6 grid gap-5 md:grid-cols-2"
            >

                @csrf


                {{-- Guest Name --}}
                <div>

                    <label
                        for="modal_name"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Guest Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="modal_name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g. Maria Santos"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Guest Type --}}
                <div>

                    <label
                        for="modal_guest_type"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Guest Type
                    </label>

                    <select
                        name="guest_type"
                        id="modal_guest_type"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="bride_side">
                            Bride Side
                        </option>

                        <option value="groom_side">
                            Groom Side
                        </option>

                        <option value="family">
                            Family
                        </option>

                        <option value="friend">
                            Friend
                        </option>

                        <option value="colleague">
                            Colleague
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>


                {{-- RSVP --}}
                <div>

                    <label
                        for="modal_rsvp_status"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        RSVP Status
                    </label>

                    <select
                        name="rsvp_status"
                        id="modal_rsvp_status"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="pending">
                            Pending
                        </option>

                        <option value="attending">
                            Attending
                        </option>

                        <option value="declined">
                            Declined
                        </option>

                    </select>

                </div>


                {{-- Meal --}}
                <div>

                    <label
                        for="modal_meal_preference"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Meal Preference
                    </label>

                    <select
                        name="meal_preference"
                        id="modal_meal_preference"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Not specified
                        </option>

                        <option value="Regular">
                            Regular
                        </option>

                        <option value="Vegetarian">
                            Vegetarian
                        </option>

                        <option value="Vegan">
                            Vegan
                        </option>

                        <option value="Halal">
                            Halal
                        </option>

                        <option value="Kids Meal">
                            Kids Meal
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>


                {{-- Phone --}}
                <div>

                    <label
                        for="modal_phone"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        id="modal_phone"
                        value="{{ old('phone') }}"
                        placeholder="09XXXXXXXXX"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="modal_email"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="modal_email"
                        value="{{ old('email') }}"
                        placeholder="guest@email.com"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- Plus One --}}
                <div class="md:col-span-2">

                    <label class="flex items-center gap-3">

                        <input
                            type="checkbox"
                            name="plus_one"
                            value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        >

                        <span class="text-sm font-medium text-gray-700">
                            Guest has a plus-one
                        </span>

                    </label>

                </div>


                {{-- Notes --}}
                <div class="md:col-span-2">

                    <label
                        for="modal_notes"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Notes
                        <span class="font-normal text-gray-400">
                            (Optional)
                        </span>
                    </label>

                    <textarea
                        name="notes"
                        id="modal_notes"
                        rows="3"
                        placeholder="Add notes about this guest..."
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('notes') }}</textarea>

                </div>


                {{-- Modal Buttons --}}
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                    <button
                        type="button"
                        onclick="document.getElementById('add-guest-modal').classList.add('hidden')"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Add Guest
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        // Close Add Guest modal when clicking outside
        document.getElementById('add-guest-modal').addEventListener('click', function(event) {

            if (event.target === this) {
                this.classList.add('hidden');
            }

        });


        // Close modals using Escape key
        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                const addModal = document.getElementById('add-guest-modal');

                if (addModal) {
                    addModal.classList.add('hidden');
                }

                document.querySelectorAll('[id^="edit-guest-"]').forEach(function(modal) {
                    modal.classList.add('hidden');
                });

            }

        });

    </script>

</x-app-layout>
