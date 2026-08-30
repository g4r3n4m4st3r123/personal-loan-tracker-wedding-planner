<x-app-layout>

<x-slot name="header">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-gray-500">
                Wedding Planner
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                Wedding Vendors
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your wedding suppliers, bookings, payments, and service dates.
            </p>
        </div>


        {{-- Header Actions --}}
        <div class="flex flex-wrap gap-2">

            {{-- Add Vendor --}}
            <button
                type="button"
                onclick="document.getElementById('add-vendor-modal').classList.remove('hidden')"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + Add Vendor
            </button>

<!--             {{-- Wedding Overview --}}
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
        {{-- ERRORS --}}
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
        {{-- SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">


            {{-- Total Vendors --}}

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <p class="text-sm font-medium text-gray-500">
                    Total Vendors
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    {{ $totalVendors }}
                </p>

            </div>


            {{-- Total Contracted --}}

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <p class="text-sm font-medium text-gray-500">
                    Total Contracted
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-600">
                    {{ $formatter->money($totalAgreedAmount) }}
                </p>

            </div>


            {{-- Total Paid --}}

            <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                <p class="text-sm font-medium text-emerald-700">
                    Total Paid
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-700">
                    {{ $formatter->money($totalAmountPaid) }}
                </p>

            </div>


            {{-- Outstanding --}}

            <div class="rounded-2xl bg-amber-50 p-5 shadow-sm ring-1 ring-amber-200">

                <p class="text-sm font-medium text-amber-700">
                    Outstanding
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-700">
                    {{ $formatter->money($totalOutstanding) }}
                </p>

            </div>


            {{-- Fully Paid --}}

            <div class="rounded-2xl bg-slate-50 p-5 shadow-sm ring-1 ring-slate-200">

                <p class="text-sm font-medium text-slate-500">
                    Fully Paid Vendors
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ $fullyPaidVendors }}
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ADD VENDOR MODAL --}}
        {{-- ========================================================= --}}

        <div
            id="add-vendor-modal"
            class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8"
        >

            <div class="mx-auto max-w-3xl rounded-2xl bg-white shadow-2xl">


                {{-- Modal Header --}}

                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900">
                            Add Vendor
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Record a supplier or service provider for your wedding.
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="document.getElementById('add-vendor-modal').classList.add('hidden')"
                        class="rounded-lg px-3 py-2 text-lg font-semibold text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                    >
                        ✕
                    </button>

                </div>


                {{-- Modal Body --}}

                <form
                    method="POST"
                    action="{{ route('wedding.vendors.store') }}"
                    class="grid gap-5 p-6 md:grid-cols-2"
                >

                    @csrf


                    {{-- Vendor Name --}}

                    <div>

                        <label
                            for="vendor_name"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Vendor Name
                        </label>

                        <input
                            type="text"
                            name="vendor_name"
                            id="vendor_name"
                            value="{{ old('vendor_name') }}"
                            required
                            placeholder="e.g. Elegant Events Catering"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Service Type --}}

                    <div>

                        <label
                            for="service_type"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Service Type
                        </label>

                        <input
                            type="text"
                            name="service_type"
                            id="service_type"
                            value="{{ old('service_type') }}"
                            required
                            placeholder="e.g. Catering"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Contact Person --}}

                    <div>

                        <label
                            for="contact_person"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Contact Person
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            id="contact_person"
                            value="{{ old('contact_person') }}"
                            placeholder="e.g. Maria Santos"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Phone --}}

                    <div>

                        <label
                            for="phone"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            placeholder="09XXXXXXXXX"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Email --}}

                    <div>

                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="vendor@email.com"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Address --}}

                    <div>

                        <label
                            for="address"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Address
                        </label>

                        <input
                            type="text"
                            name="address"
                            id="address"
                            value="{{ old('address') }}"
                            placeholder="Business address"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Agreed Amount --}}

                    <div>

                        <label
                            for="agreed_amount"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Agreed Amount
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                {{ $formatter->symbol() }}
                            </span>

                            <input
                                type="number"
                                name="agreed_amount"
                                id="agreed_amount"
                                value="{{ old('agreed_amount', 0) }}"
                                min="0"
                                step="0.01"
                                required
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    {{-- Amount Paid --}}

                    <div>

                        <label
                            for="amount_paid"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Amount Paid
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                {{ $formatter->symbol() }}
                            </span>

                            <input
                                type="number"
                                name="amount_paid"
                                id="amount_paid"
                                value="{{ old('amount_paid', 0) }}"
                                min="0"
                                step="0.01"
                                required
                                class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    {{-- Payment Status --}}

                    <div>

                        <label
                            for="payment_status"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Payment Status
                        </label>

                        <select
                            name="payment_status"
                            id="payment_status"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option
                                value="unpaid"
                                @selected(old('payment_status', 'unpaid') === 'unpaid')
                            >
                                Unpaid
                            </option>

                            <option
                                value="partial"
                                @selected(old('payment_status') === 'partial')
                            >
                                Partial
                            </option>

                            <option
                                value="paid"
                                @selected(old('payment_status') === 'paid')
                            >
                                Paid
                            </option>

                        </select>

                        <p class="mt-1 text-xs text-gray-400">
                            Status is automatically corrected from the amount paid.
                        </p>

                    </div>


                    {{-- Booking Date --}}

                    <div>

                        <label
                            for="booking_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Booking Date
                        </label>

                        <input
                            type="date"
                            name="booking_date"
                            id="booking_date"
                            value="{{ old('booking_date') }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Service Date --}}

                    <div>

                        <label
                            for="service_date"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Service Date
                        </label>

                        <input
                            type="date"
                            name="service_date"
                            id="service_date"
                            value="{{ old('service_date') }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    {{-- Notes --}}

                    <div class="md:col-span-2">

                        <label
                            for="notes"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Notes
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            placeholder="Package details, contract notes, inclusions, etc."
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('notes') }}</textarea>

                    </div>


                    {{-- Modal Buttons --}}

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                        <button
                            type="button"
                            onclick="document.getElementById('add-vendor-modal').classList.add('hidden')"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Add Vendor
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SEARCH / FILTER --}}
        {{-- ========================================================= --}}

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <form
                method="GET"
                action="{{ route('wedding.vendors') }}"
                class="grid gap-4 md:grid-cols-4"
            >

                <div class="md:col-span-2">

                    <label class="block text-sm font-semibold text-gray-700">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search vendor or service"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                    >

                </div>


                <div>

                    <label class="block text-sm font-semibold text-gray-700">
                        Service Type
                    </label>

                    <input
                        type="text"
                        name="service_type"
                        value="{{ request('service_type') }}"
                        placeholder="e.g. Catering"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                    >

                </div>


                <div>

                    <label class="block text-sm font-semibold text-gray-700">
                        Payment Status
                    </label>

                    <select
                        name="payment_status"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                    >

                        <option value="">
                            All
                        </option>

                        <option
                            value="unpaid"
                            @selected(request('payment_status') === 'unpaid')
                        >
                            Unpaid
                        </option>

                        <option
                            value="partial"
                            @selected(request('payment_status') === 'partial')
                        >
                            Partial
                        </option>

                        <option
                            value="paid"
                            @selected(request('payment_status') === 'paid')
                        >
                            Paid
                        </option>

                    </select>

                </div>


                <div class="flex items-end gap-2 md:col-span-4">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('wedding.vendors') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Clear
                    </a>

                </div>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- VENDOR LIST --}}
        {{-- ========================================================= --}}

        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="text-lg font-bold text-gray-900">
                    Vendor List
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Your wedding suppliers and service providers.
                </p>

            </div>


            @if ($vendors->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Vendor
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Service
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Agreed
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Paid
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Balance
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Service Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($vendors as $vendor)

                                <tr class="transition hover:bg-gray-50">


                                    {{-- Vendor --}}

                                    <td class="px-6 py-5">

                                        <p class="font-semibold text-gray-900">
                                            {{ $vendor->vendor_name }}
                                        </p>

                                        @if ($vendor->contact_person)

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $vendor->contact_person }}
                                            </p>

                                        @endif

                                        @if ($vendor->phone)

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $vendor->phone }}
                                            </p>

                                        @endif

                                    </td>


                                    {{-- Service --}}

                                    <td class="px-6 py-5 text-sm text-gray-600">
                                        {{ $vendor->service_type }}
                                    </td>


                                    {{-- Agreed --}}

                                    <td class="px-6 py-5 text-right">

                                        <span class="font-semibold text-gray-900">
                                            {{ $formatter->money($vendor->agreed_amount) }}
                                        </span>

                                    </td>


                                    {{-- Paid --}}

                                    <td class="px-6 py-5 text-right">

                                        <span class="font-semibold text-emerald-600">
                                            {{ $formatter->money($vendor->amount_paid) }}
                                        </span>

                                    </td>


                                    {{-- Balance --}}

                                    <td class="px-6 py-5 text-right">

                                        <span
                                            class="font-semibold
                                            {{ $vendor->balance > 0
                                                ? 'text-amber-600'
                                                : 'text-emerald-600' }}"
                                        >
                                            {{ $formatter->money($vendor->balance) }}
                                        </span>

                                    </td>


                                    {{-- Service Date --}}

                                    <td class="px-6 py-5 text-sm text-gray-600">

                                        @if ($vendor->service_date)

                                            <span
                                                class="{{ $vendor->service_date->isPast()
                                                    ? 'text-gray-400'
                                                    : 'text-gray-700' }}"
                                            >
                                                {{ $formatter->date($vendor->service_date) }}
                                            </span>

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td class="px-6 py-5">

                                        @if ($vendor->payment_status === 'paid')

                                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Paid
                                            </span>

                                        @elseif ($vendor->payment_status === 'partial')

                                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                Partial
                                            </span>

                                        @else

                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Unpaid
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="px-6 py-5">

                                        <div class="flex items-center justify-end gap-2 whitespace-nowrap">

                                            <button
                                                type="button"
                                                onclick="document.getElementById('edit-vendor-{{ $vendor->id }}').classList.remove('hidden')"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 hover:bg-indigo-100"
                                            >
                                                Edit
                                            </button>


                                            <form
                                                method="POST"
                                                action="{{ route('wedding.vendors.destroy', $vendor) }}"
                                                onsubmit="return confirm('Delete this vendor?');"
                                                class="inline-flex"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 hover:bg-red-100"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================================================= --}}
                                {{-- EDIT VENDOR MODAL --}}
                                {{-- ================================================= --}}

                                <div
                                    id="edit-vendor-{{ $vendor->id }}"
                                    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/40 px-4 py-8"
                                >

                                    <div class="mx-auto max-w-3xl rounded-2xl bg-white p-6 shadow-xl">


                                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">

                                            <div>

                                                <h3 class="text-lg font-bold text-gray-900">
                                                    Edit Vendor
                                                </h3>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Update vendor details and payment information.
                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="document.getElementById('edit-vendor-{{ $vendor->id }}').classList.add('hidden')"
                                                class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100"
                                            >
                                                ✕
                                            </button>

                                        </div>


                                        <form
                                            method="POST"
                                            action="{{ route('wedding.vendors.update', $vendor) }}"
                                            class="mt-6 grid gap-5 md:grid-cols-2"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            {{-- Vendor Name --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Vendor Name
                                                </label>

                                                <input
                                                    type="text"
                                                    name="vendor_name"
                                                    value="{{ $vendor->vendor_name }}"
                                                    required
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Service Type --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Service Type
                                                </label>

                                                <input
                                                    type="text"
                                                    name="service_type"
                                                    value="{{ $vendor->service_type }}"
                                                    required
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Contact Person --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Contact Person
                                                </label>

                                                <input
                                                    type="text"
                                                    name="contact_person"
                                                    value="{{ $vendor->contact_person }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Phone --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Phone
                                                </label>

                                                <input
                                                    type="text"
                                                    name="phone"
                                                    value="{{ $vendor->phone }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
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
                                                    value="{{ $vendor->email }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Address --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Address
                                                </label>

                                                <input
                                                    type="text"
                                                    name="address"
                                                    value="{{ $vendor->address }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Agreed Amount --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Agreed Amount
                                                </label>

                                                <div class="relative mt-2">

                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                                        {{ $formatter->symbol() }}
                                                    </span>

                                                    <input
                                                        type="number"
                                                        name="agreed_amount"
                                                        value="{{ $vendor->agreed_amount }}"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                        class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm"
                                                    >

                                                </div>

                                            </div>


                                            {{-- Amount Paid --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Amount Paid
                                                </label>

                                                <div class="relative mt-2">

                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                                                        {{ $formatter->symbol() }}
                                                    </span>

                                                    <input
                                                        type="number"
                                                        name="amount_paid"
                                                        value="{{ $vendor->amount_paid }}"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                        class="block w-full rounded-lg border-gray-300 py-3 pl-8 pr-4 shadow-sm"
                                                    >

                                                </div>

                                            </div>


                                            {{-- Payment Status --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Payment Status
                                                </label>

                                                <select
                                                    name="payment_status"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                                    <option
                                                        value="unpaid"
                                                        @selected($vendor->payment_status === 'unpaid')
                                                    >
                                                        Unpaid
                                                    </option>

                                                    <option
                                                        value="partial"
                                                        @selected($vendor->payment_status === 'partial')
                                                    >
                                                        Partial
                                                    </option>

                                                    <option
                                                        value="paid"
                                                        @selected($vendor->payment_status === 'paid')
                                                    >
                                                        Paid
                                                    </option>

                                                </select>

                                            </div>


                                            {{-- Booking Date --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Booking Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="booking_date"
                                                    value="{{ $vendor->booking_date?->format('Y-m-d') }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Service Date --}}

                                            <div>

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Service Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="service_date"
                                                    value="{{ $vendor->service_date?->format('Y-m-d') }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm"
                                                >

                                            </div>


                                            {{-- Notes --}}

                                            <div class="md:col-span-2">

                                                <label class="block text-sm font-semibold text-gray-700">
                                                    Notes
                                                </label>

                                                <textarea
                                                    name="notes"
                                                    rows="3"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm"
                                                >{{ $vendor->notes }}</textarea>

                                            </div>


                                            {{-- Buttons --}}

                                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('edit-vendor-{{ $vendor->id }}').classList.add('hidden')"
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

                        </tbody>

                    </table>

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <div class="text-4xl">
                        🧑‍💼
                    </div>

                    <h3 class="mt-4 font-bold text-gray-900">
                        No vendors yet
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Add your first wedding vendor using the button above.
                    </p>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- UPCOMING SERVICES --}}
        {{-- ========================================================= --}}

        @if ($upcomingVendors->count())

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-gray-900">
                        Upcoming Vendor Services
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Your next scheduled vendor services.
                    </p>

                </div>


                <div class="space-y-3">

                    @foreach ($upcomingVendors as $vendor)

                        <div class="flex items-center justify-between rounded-xl border border-gray-100 p-4">

                            <div>

                                <p class="font-semibold text-gray-900">
                                    {{ $vendor->vendor_name }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $vendor->service_type }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="font-semibold text-indigo-600">
                                    {{ $formatter->date($vendor->service_date) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $formatter->money($vendor->balance) }} outstanding
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif


    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

    // Close Add Vendor modal when clicking outside
    document.getElementById('add-vendor-modal')?.addEventListener('click', function (event) {

        if (event.target === this) {
            this.classList.add('hidden');
        }

    });


    // Close modals with ESC key
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            document.getElementById('add-vendor-modal')?.classList.add('hidden');

            document.querySelectorAll('[id^="edit-vendor-"]').forEach(function (modal) {
                modal.classList.add('hidden');
            });

        }

    });

</script>

</x-app-layout>
