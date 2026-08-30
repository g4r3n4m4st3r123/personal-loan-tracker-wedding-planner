<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Settings') }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your profile, security, and application preferences.
            </p>
        </div>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            {{-- ========================================================= --}}
            {{-- PROFILE INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    @include(
                        'profile.partials.update-profile-information-form'
                    )

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- PASSWORD --}}
            {{-- ========================================================= --}}

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    @include(
                        'profile.partials.update-password-form'
                    )

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- APPLICATION PREFERENCES --}}
            {{-- ========================================================= --}}

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    <div class="mb-6">

                        <h2 class="text-lg font-medium text-gray-900">
                            Application Preferences
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Customize how your Finance & Wedding Planner works.
                        </p>

                    </div>


                    {{-- Success message --}}

                    @if (session('settings_success'))

                        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4">

                            <p class="text-sm font-medium text-green-800">
                                {{ session('settings_success') }}
                            </p>

                        </div>

                    @endif


                    {{-- Validation errors specifically for settings --}}

                    @if ($errors->any())

                        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">

                            <p class="text-sm font-semibold text-red-800">
                                Please check your application preferences.
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


                    <form
                        method="POST"
                        action="{{ route('settings.preferences.update') }}"
                    >

                        @csrf

                        @method('PATCH')


                        <div class="space-y-6">


                            {{-- ================================================= --}}
                            {{-- CURRENCY --}}
                            {{-- ================================================= --}}

                            <div>

                                <label
                                    for="currency"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Currency
                                </label>

                                <select
                                    name="currency"
                                    id="currency"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option
                                        value="PHP"
                                        @selected($settings->currency === 'PHP')
                                    >
                                        PHP — Philippine Peso
                                    </option>

                                    <option
                                        value="USD"
                                        @selected($settings->currency === 'USD')
                                    >
                                        USD — US Dollar
                                    </option>

                                    <option
                                        value="EUR"
                                        @selected($settings->currency === 'EUR')
                                    >
                                        EUR — Euro
                                    </option>

                                    <option
                                        value="GBP"
                                        @selected($settings->currency === 'GBP')
                                    >
                                        GBP — British Pound
                                    </option>

                                    <option
                                        value="JPY"
                                        @selected($settings->currency === 'JPY')
                                    >
                                        JPY — Japanese Yen
                                    </option>

                                    <option
                                        value="SGD"
                                        @selected($settings->currency === 'SGD')
                                    >
                                        SGD — Singapore Dollar
                                    </option>

                                </select>

                            </div>


                            {{-- ================================================= --}}
                            {{-- CURRENCY SYMBOL --}}
                            {{-- ================================================= --}}

                            <div>

                                <label
                                    for="currency_symbol"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Currency Symbol
                                </label>

                                <input
                                    type="text"
                                    name="currency_symbol"
                                    id="currency_symbol"
                                    value="{{ old('currency_symbol', $settings->currency_symbol) }}"
                                    maxlength="10"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                <p class="mt-1 text-xs text-gray-400">
                                    Example: ₱, $, €, £
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DATE FORMAT --}}
                            {{-- ================================================= --}}

                            <div>

                                <label
                                    for="date_format"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Date Format
                                </label>

                                <select
                                    name="date_format"
                                    id="date_format"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option
                                        value="M d, Y"
                                        @selected($settings->date_format === 'M d, Y')
                                    >
                                        Aug 29, 2026
                                    </option>

                                    <option
                                        value="d M Y"
                                        @selected($settings->date_format === 'd M Y')
                                    >
                                        29 Aug 2026
                                    </option>

                                    <option
                                        value="Y-m-d"
                                        @selected($settings->date_format === 'Y-m-d')
                                    >
                                        2026-08-29
                                    </option>

                                    <option
                                        value="F d, Y"
                                        @selected($settings->date_format === 'F d, Y')
                                    >
                                        August 29, 2026
                                    </option>

                                </select>

                            </div>


                            {{-- ================================================= --}}
                            {{-- WEEK START --}}
                            {{-- ================================================= --}}

                            <div>

                                <label
                                    for="week_starts_on"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Week Starts On
                                </label>

                                <select
                                    name="week_starts_on"
                                    id="week_starts_on"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option
                                        value="monday"
                                        @selected($settings->week_starts_on === 'monday')
                                    >
                                        Monday
                                    </option>

                                    <option
                                        value="sunday"
                                        @selected($settings->week_starts_on === 'sunday')
                                    >
                                        Sunday
                                    </option>

                                </select>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DASHBOARD VIEW --}}
                            {{-- ================================================= --}}

                            <div>

                                <label
                                    for="dashboard_view"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Default Dashboard View
                                </label>

                                <select
                                    name="dashboard_view"
                                    id="dashboard_view"
                                    required
                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option
                                        value="overview"
                                        @selected($settings->dashboard_view === 'overview')
                                    >
                                        Combined Overview
                                    </option>

                                    <option
                                        value="finance"
                                        @selected($settings->dashboard_view === 'finance')
                                    >
                                        Finance Focus
                                    </option>

                                    <option
                                        value="wedding"
                                        @selected($settings->dashboard_view === 'wedding')
                                    >
                                        Wedding Focus
                                    </option>

                                </select>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DASHBOARD SECTIONS --}}
                            {{-- ================================================= --}}

                            <div>

                                <p class="text-sm font-medium text-gray-700">
                                    Dashboard Sections
                                </p>

                                <div class="mt-3 space-y-4">


                                    {{-- Finance --}}

                                    <label class="flex items-start gap-3">

                                        <input
                                            type="hidden"
                                            name="show_finance_dashboard"
                                            value="0"
                                        >

                                        <input
                                            type="checkbox"
                                            name="show_finance_dashboard"
                                            value="1"
                                            @checked($settings->show_finance_dashboard)
                                            class="mt-0.5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        >

                                        <span>

                                            <span class="block text-sm font-medium text-gray-700">
                                                Show Finance section
                                            </span>

                                            <span class="block text-xs text-gray-400">
                                                Display your income, loans, expenses, and financial analytics.
                                            </span>

                                        </span>

                                    </label>


                                    {{-- Wedding --}}

                                    <label class="flex items-start gap-3">

                                        <input
                                            type="hidden"
                                            name="show_wedding_dashboard"
                                            value="0"
                                        >

                                        <input
                                            type="checkbox"
                                            name="show_wedding_dashboard"
                                            value="1"
                                            @checked($settings->show_wedding_dashboard)
                                            class="mt-0.5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        >

                                        <span>

                                            <span class="block text-sm font-medium text-gray-700">
                                                Show Wedding section
                                            </span>

                                            <span class="block text-xs text-gray-400">
                                                Display your wedding budget, guests, checklist, and vendors.
                                            </span>

                                        </span>

                                    </label>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- SAVE --}}
                            {{-- ================================================= --}}

                            <div class="border-t border-gray-100 pt-5">

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Save Preferences
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- DELETE ACCOUNT --}}
            {{-- ========================================================= --}}

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    @include(
                        'profile.partials.delete-user-form'
                    )

                </div>

            </div>


        </div>

    </div>

</x-app-layout>