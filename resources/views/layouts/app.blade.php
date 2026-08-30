<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ config('app.name', 'Personal Finance & Wedding Planner') }}
    </title>

    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
        rel="stylesheet"
    />

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="m-0 min-h-screen bg-slate-50 p-0 font-sans antialiased text-slate-800">

    <div
        x-data="{ sidebarOpen: false }"
        class="m-0 min-h-screen p-0"
    >

        {{-- ========================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ========================================================= --}}

        @include('layouts.navigation')


        {{-- ========================================================= --}}
        {{-- MAIN APPLICATION AREA --}}
        {{-- ========================================================= --}}

        <div class="lg:pl-72">

            {{-- ===================================================== --}}
            {{-- TOP BAR --}}
            {{-- ===================================================== --}}

            <header
                class="sticky top-0 z-50 m-0 border-b border-slate-200 bg-white/95 p-0 shadow-sm backdrop-blur"
            >

                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">

                    {{-- Mobile Menu --}}
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden"
                        aria-label="Open navigation"
                    >

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />

                        </svg>

                    </button>


                    {{-- Desktop Title --}}
                    <div class="hidden lg:block">

                        <p class="text-sm font-medium text-slate-500">
                            Personal Finance & Wedding Planner
                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- RIGHT SIDE --}}
                    {{-- ================================================= --}}

                    <div class="ml-auto flex items-center gap-3">


                    {{-- ================================================= --}}
                    {{-- GLOBAL LIVE SEARCH --}}
                    {{-- ================================================= --}}

                    <div
                        class="relative hidden sm:block"
                        x-data="headerGlobalSearch()"
                        @keydown.escape.window="closeSearch()"
                    >

                        {{-- Search Button / Input --}}

                        <div class="relative w-64 lg:w-72">

                            <span
                                class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
                            >
                                🔎
                            </span>


                            <input
                                type="search"
                                x-model="query"
                                @input="handleInput"
                                @focus="open = query.trim().length > 0"
                                autocomplete="off"
                                placeholder="Search..."
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-indigo-300 focus:bg-white focus:ring-indigo-500"
                            >


                            {{-- Loading Spinner --}}

                            <div
                                x-show="loading"
                                x-cloak
                                class="absolute right-3 top-1/2 -translate-y-1/2"
                            >

                                <svg
                                    class="h-4 w-4 animate-spin text-indigo-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >

                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                    ></path>

                                </svg>

                            </div>


                            {{-- Clear --}}

                            <button
                                type="button"
                                x-show="query.length > 0 && !loading"
                                x-cloak
                                @click="clearSearch()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg px-2 py-1 text-xs text-slate-400 transition hover:bg-slate-200 hover:text-slate-600"
                            >
                                ✕
                            </button>

                        </div>


                        {{-- ================================================= --}}
                        {{-- LIVE SEARCH DROPDOWN --}}
                        {{-- ================================================= --}}

                        <div
                            x-show="open && query.trim().length > 0"
                            x-cloak
                            @click.outside="closeSearch()"
                            x-transition
                            class="absolute right-0 z-[200] mt-2 w-[22rem] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl lg:w-[26rem]"
                        >

                            {{-- Header --}}

                            <div class="border-b border-slate-100 px-4 py-3">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Search Results
                                        </p>

                                        <p
                                            x-show="!loading"
                                            class="mt-0.5 text-xs text-slate-500"
                                        >
                                            <span x-text="resultCount"></span>
                                            <span x-text="resultCount === 1 ? 'result' : 'results'"></span>
                                        </p>

                                    </div>


                                    <a
                                        href="{{ route('search.index') }}"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                                        @click="closeSearch()"
                                    >
                                        Advanced Search →
                                    </a>

                                </div>

                            </div>


                            {{-- Loading --}}

                            <div
                                x-show="loading"
                                x-cloak
                                class="px-5 py-8 text-center"
                            >

                                <svg
                                    class="mx-auto h-6 w-6 animate-spin text-indigo-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >

                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                    ></path>

                                </svg>

                                <p class="mt-3 text-xs text-slate-400">
                                    Searching...
                                </p>

                            </div>


                            {{-- Results --}}

                            <div
                                x-show="!loading && results.length > 0"
                                x-cloak
                                class="max-h-[24rem] overflow-y-auto"
                            >

                                <template
                                    x-for="result in results"
                                    :key="result.type + '-' + result.title + '-' + result.url"
                                >

                                    <a
                                        :href="result.url"
                                        @click="closeSearch()"
                                        class="block border-b border-slate-100 px-4 py-3.5 transition last:border-b-0 hover:bg-slate-50"
                                    >

                                        <div class="flex items-start gap-3">

                                            {{-- Icon --}}

                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-base"
                                                x-text="result.icon"
                                            ></div>


                                            {{-- Content --}}

                                            <div class="min-w-0 flex-1">

                                                <div class="flex items-center gap-2">

                                                    <p
                                                        class="truncate text-sm font-semibold text-slate-800"
                                                        x-text="result.title"
                                                    ></p>

                                                    <span
                                                        class="shrink-0 rounded-full bg-indigo-50 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-indigo-600"
                                                        x-text="result.type"
                                                    ></span>

                                                </div>


                                                <p
                                                    x-show="result.description"
                                                    class="mt-0.5 truncate text-xs text-slate-500"
                                                    x-text="result.description"
                                                ></p>

                                            </div>


                                            <span class="mt-1 text-xs text-slate-300">
                                                →
                                            </span>

                                        </div>

                                    </a>

                                </template>

                            </div>


                            {{-- No Results --}}

                            <div
                                x-show="!loading && searched && results.length === 0"
                                x-cloak
                                class="px-5 py-8 text-center"
                            >

                                <div class="text-2xl">
                                    🔎
                                </div>

                                <p class="mt-2 text-sm font-semibold text-slate-700">
                                    No results found
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Try another keyword.
                                </p>

                            </div>


                            {{-- Initial State --}}

                            <div
                                x-show="!loading && !searched && query.trim().length > 0"
                                x-cloak
                                class="px-5 py-8 text-center"
                            >

                                <p class="text-sm font-semibold text-slate-700">
                                    Start typing to search
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Search loans, guests, vendors, tasks, expenses, and more.
                                </p>

                            </div>


                            {{-- Footer --}}

                            <div class="border-t border-slate-100 bg-slate-50 px-4 py-2.5">

                                <a
                                    href="{{ route('search.index') }}"
                                    @click="closeSearch()"
                                    class="block text-center text-xs font-semibold text-indigo-600 transition hover:text-indigo-800"
                                >
                                    View all search results →
                                </a>

                            </div>

                        </div>

                    </div>

                        {{-- ================================================= --}}
                        {{-- NOTIFICATIONS --}}
                        {{-- ================================================= --}}



                        <div
                            x-data="{ open: false }"
                            class="relative"
                        >

                            {{-- Bell --}}
                            <button
                                type="button"
                                @click="open = !open"
                                @keydown.escape.window="open = false"
                                class="relative rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                                aria-label="Notifications"
                                :aria-expanded="open.toString()"
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
                                        stroke-width="1.8"
                                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0"
                                    />

                                </svg>


                                {{-- Unread Counter --}}
                                @if ($headerUnreadNotifications > 0)

                                    <span
                                        class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white ring-2 ring-white"
                                    >
                                        {{ $headerUnreadNotifications > 9
                                            ? '9+'
                                            : $headerUnreadNotifications }}
                                    </span>

                                @endif

                            </button>


                            {{-- Click Outside --}}
                            <div
                                x-show="open"
                                x-cloak
                                @click.outside="open = false"
                                x-transition
                                class="absolute right-0 z-[100] mt-3 w-[22rem] origin-top-right overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:w-[25rem]"
                            >

                                {{-- Dropdown Header --}}
                                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4">

                                    <div>

                                        <h3 class="text-sm font-bold text-slate-900">
                                            Notifications
                                        </h3>

                                        <p class="mt-0.5 text-xs text-slate-400">
                                            {{ $headerUnreadNotifications }}
                                            unread notification{{ $headerUnreadNotifications === 1 ? '' : 's' }}
                                        </p>

                                    </div>


                                    @if ($headerUnreadNotifications > 0)

                                        <form
                                            method="POST"
                                            action="{{ route('notifications.read-all') }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                Mark all as read
                                            </button>

                                        </form>

                                    @endif

                                </div>


                                {{-- Notification List --}}
                                <div class="max-h-[28rem] overflow-y-auto">

                                    @forelse ($headerNotifications as $notification)

                                        @php

                                            $typeClasses = match ($notification->type) {

                                                'danger' =>
                                                    'bg-rose-50 text-rose-600',

                                                'warning' =>
                                                    'bg-amber-50 text-amber-600',

                                                'wedding' =>
                                                    'bg-pink-50 text-pink-600',

                                                'vendor' =>
                                                    'bg-emerald-50 text-emerald-600',

                                                'loan' =>
                                                    'bg-blue-50 text-blue-600',

                                                'payment' =>
                                                    'bg-indigo-50 text-indigo-600',

                                                'expense' =>
                                                    'bg-rose-50 text-rose-600',

                                                'salary' =>
                                                    'bg-emerald-50 text-emerald-600',

                                                default =>
                                                    'bg-slate-100 text-slate-600',
                                            };


                                            $icon = match ($notification->type) {

                                                'wedding' => '💍',

                                                'vendor' => '🧑‍💼',

                                                'loan' => '💳',

                                                'payment' => '💰',

                                                'expense' => '💸',

                                                'salary' => '💵',

                                                'danger' => '⚠️',

                                                'warning' => '⚠️',

                                                default => '🔔',
                                            };

                                        @endphp


                                        <div
                                            class="border-b border-slate-100 last:border-b-0 transition hover:bg-slate-50
                                            {{ is_null($notification->read_at)
                                                ? 'bg-indigo-50/40'
                                                : 'bg-white' }}"
                                        >

                                            <div class="flex gap-3 px-4 py-4">


                                                {{-- Icon --}}
                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $typeClasses }}"
                                                >

                                                    <span class="text-lg">
                                                        {{ $icon }}
                                                    </span>

                                                </div>


                                                {{-- Content --}}
                                                <div class="min-w-0 flex-1">

                                                    <div class="flex items-start justify-between gap-3">

                                                        <div class="min-w-0">

                                                            <div class="flex flex-wrap items-center gap-2">

                                                                <p class="text-sm font-semibold text-slate-800">
                                                                    {{ $notification->title }}
                                                                </p>


                                                                @if (is_null($notification->read_at))

                                                                    <span class="rounded-full bg-indigo-600 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-white">
                                                                        New
                                                                    </span>

                                                                @endif

                                                            </div>


                                                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                                                {{ $notification->message }}
                                                            </p>

                                                        </div>


                                                        <span class="shrink-0 text-[10px] text-slate-400">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </span>

                                                    </div>


                                                    <div class="mt-3 flex flex-wrap items-center gap-3">

                                                        @if ($notification->url)

                                                            <form
                                                                method="POST"
                                                                action="{{ route('notifications.read', $notification) }}"
                                                            >

                                                                @csrf

                                                                <button
                                                                    type="submit"
                                                                    class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-800 hover:underline"
                                                                >
                                                                    View Details →
                                                                </button>

                                                            </form>

                                                        @elseif (is_null($notification->read_at))

                                                            <form
                                                                method="POST"
                                                                action="{{ route('notifications.read', $notification) }}"
                                                            >

                                                                @csrf

                                                                <button
                                                                    type="submit"
                                                                    class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-800 hover:underline"
                                                                >
                                                                    Mark as Read
                                                                </button>

                                                            </form>

                                                        @endif

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @empty

                                        <div class="px-5 py-10 text-center">

                                            <div class="text-3xl">
                                                ✅
                                            </div>

                                            <p class="mt-3 text-sm font-semibold text-slate-700">
                                                You're all caught up
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                No important notifications right now.
                                            </p>

                                        </div>

                                    @endforelse

                                </div>


                                {{-- Footer --}}
                                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">

                                    <a
                                        href="{{ route('notifications.index') }}"
                                        class="block text-center text-xs font-semibold text-indigo-600 transition hover:text-indigo-800"
                                    >
                                        View All Notifications →
                                    </a>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- USER PROFILE --}}
                        {{-- ================================================= --}}

                        <a
                            href="{{ route('profile.edit') }}"
                            class="hidden items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-50 sm:flex"
                        >

                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </div>


                            <div class="text-left">

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ Auth::user()->name }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Account
                                </p>

                            </div>

                        </a>

                    </div>

                </div>

            </header>


            {{-- ========================================================= --}}
            {{-- MAIN CONTENT --}}
            {{-- ========================================================= --}}

            <main class="min-h-[calc(100vh-4rem)]">

                @isset($header)

                    <div class="border-b border-slate-200 bg-white">

                        <div class="px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>

                    </div>

                @endisset


                <div class="px-4 py-6 sm:px-6 lg:px-8">

                    {{ $slot }}

                </div>

            </main>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- SCRIPTS --}}
    {{-- ============================================================= --}}

    <script>

        function headerGlobalSearch() {

            return {

                query: '',

                results: [],

                resultCount: 0,

                loading: false,

                searched: false,

                open: false,

                debounceTimer: null,

                abortController: null,


                handleInput() {

                    clearTimeout(
                        this.debounceTimer
                    );


                    this.results = [];

                    this.resultCount = 0;

                    this.searched = false;


                    const value =
                        this.query.trim();


                    if (!value) {

                        this.loading = false;

                        this.open = false;

                        return;
                    }


                    this.open = true;


                    this.debounceTimer = setTimeout(() => {

                        this.performSearch();

                    }, 300);

                },


                async performSearch() {

                    const searchQuery =
                        this.query.trim();


                    if (!searchQuery) {
                        return;
                    }


                    if (this.abortController) {

                        this.abortController.abort();

                    }


                    this.abortController =
                        new AbortController();


                    this.loading = true;

                    this.open = true;


                    try {

                        const response =
                            await fetch(
                                `{{ route('search.live') }}?q=${encodeURIComponent(searchQuery)}`,
                                {
                                    method: 'GET',

                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },

                                    signal:
                                        this.abortController.signal
                                }
                            );


                        if (!response.ok) {

                            throw new Error(
                                'Search request failed.'
                            );
                        }


                        const data =
                            await response.json();


                        /*
                        |--------------------------------------------------------------------------
                        | Ignore old request results
                        |--------------------------------------------------------------------------
                        */

                        if (
                            searchQuery !==
                            this.query.trim()
                        ) {
                            return;
                        }


                        this.results =
                            data.results || [];


                        this.resultCount =
                            data.count || 0;


                        this.searched = true;

                        this.open = true;

                    }
                    catch (error) {

                        if (
                            error.name !==
                            'AbortError'
                        ) {

                            console.error(
                                'Header search error:',
                                error
                            );

                            this.results = [];

                            this.resultCount = 0;

                            this.searched = true;
                        }

                    }
                    finally {

                        if (
                            searchQuery ===
                            this.query.trim()
                        ) {

                            this.loading = false;
                        }

                    }

                },


                clearSearch() {

                    clearTimeout(
                        this.debounceTimer
                    );


                    if (this.abortController) {

                        this.abortController.abort();

                    }


                    this.query = '';

                    this.results = [];

                    this.resultCount = 0;

                    this.loading = false;

                    this.searched = false;

                    this.open = false;

                },


                closeSearch() {

                    this.open = false;

                }

            };

        }

    </script>


</body>

</html>