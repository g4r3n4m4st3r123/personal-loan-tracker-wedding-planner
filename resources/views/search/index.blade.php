<x-app-layout>

    <x-slot name="header">

        <div>

            <p class="text-sm font-medium text-indigo-600">
                Global Search
            </p>

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                Search Everything
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Find loans, income, expenses, wedding tasks, guests, vendors, and more.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div
            class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8"
            x-data="globalSearch()"
        >


            {{-- ========================================================= --}}
            {{-- SEARCH BOX --}}
            {{-- ========================================================= --}}

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                <label
                    for="global-search"
                    class="block text-sm font-semibold text-slate-700"
                >
                    Search
                </label>


                <div class="relative mt-2">

                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-lg text-slate-400">
                        🔎
                    </span>


                    <input
                        type="search"
                        id="global-search"
                        x-model="query"
                        @input="handleInput"
                        autocomplete="off"
                        placeholder="Start typing..."
                        class="block w-full rounded-xl border-slate-300 py-3 pl-11 pr-12 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />


                    {{-- Loading --}}

                    <div
                        x-show="loading"
                        x-cloak
                        class="absolute right-4 top-1/2 -translate-y-1/2"
                    >

                        <svg
                            class="h-5 w-5 animate-spin text-indigo-500"
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
                        @click="clearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg px-2 py-1 text-sm text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                    >
                        ✕
                    </button>

                </div>


                <p class="mt-3 text-xs text-slate-400">
                    Results appear automatically as you type.
                </p>

            </section>


            {{-- ========================================================= --}}
            {{-- LIVE RESULTS --}}
            {{-- ========================================================= --}}

            <section
                class="mt-6"
                x-show="query.length > 0"
                x-cloak
            >

                {{-- Result Header --}}

                <div class="mb-4 flex items-center justify-between gap-4">

                    <div>

                        <p class="text-sm font-medium text-indigo-600">
                            Live Results
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            Results
                        </h3>

                    </div>


                    <span
                        x-show="!loading"
                        class="text-sm text-slate-400"
                    >
                        <span x-text="resultCount"></span>

                        <span x-text="resultCount === 1 ? 'result' : 'results'"></span>
                    </span>

                </div>


                {{-- Results --}}

                <div
                    x-show="results.length > 0"
                    x-cloak
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                >

                    <div class="divide-y divide-slate-100">

                        <template
                            x-for="result in results"
                            :key="result.type + '-' + result.title + '-' + result.url"
                        >

                            <a
                                :href="result.url"
                                class="block p-5 transition hover:bg-slate-50 sm:p-6"
                            >

                                <div class="flex items-start gap-4">

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xl"
                                        x-text="result.icon"
                                    ></div>


                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h4
                                                class="font-semibold text-slate-900"
                                                x-text="result.title"
                                            ></h4>


                                            <span
                                                class="rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-indigo-700"
                                                x-text="result.type"
                                            ></span>


                                            <span
                                                class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-500"
                                                x-text="result.category"
                                            ></span>

                                        </div>


                                        <p
                                            x-show="result.description"
                                            class="mt-1 text-sm text-slate-500"
                                            x-text="result.description"
                                        ></p>


                                        <p class="mt-2 text-xs font-semibold text-indigo-600">
                                            Open →
                                        </p>

                                    </div>

                                </div>

                            </a>

                        </template>

                    </div>

                </div>


                {{-- No results --}}

                <div
                    x-show="!loading && searched && results.length === 0"
                    x-cloak
                    class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-14 text-center"
                >

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">
                        🔎
                    </div>

                    <h3 class="mt-5 font-bold text-slate-900">
                        No results found
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Nothing matched your search.
                        Try another keyword.
                    </p>

                </div>


                {{-- Initial Search --}}

                <div
                    x-show="!searched && !loading"
                    x-cloak
                    class="rounded-3xl bg-slate-900 px-6 py-12 text-center text-white shadow-sm"
                >

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-3xl">
                        🔎
                    </div>

                    <h3 class="mt-5 text-xl font-bold">
                        Searching...
                    </h3>

                    <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-300">
                        Start typing to search your finance and wedding records.
                    </p>

                </div>

            </section>


            {{-- ========================================================= --}}
            {{-- DEFAULT STATE --}}
            {{-- ========================================================= --}}

            <section
                x-show="query.length === 0"
                class="mt-6 rounded-3xl bg-slate-900 px-6 py-12 text-center text-white shadow-sm sm:px-10"
            >

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-3xl">
                    🔎
                </div>

                <h3 class="mt-5 text-xl font-bold">
                    Search your Personal Hub
                </h3>

                <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-300">
                    Search across your finance records and wedding planner
                    in one place.
                </p>

                <div class="mt-6 flex flex-wrap justify-center gap-2">

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        💳 Loans
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        💰 Income
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        💸 Expenses
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        👥 Guests
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        🏪 Vendors
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        📋 Tasks
                    </span>

                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                        📅 Timeline
                    </span>

                </div>

            </section>

        </div>

    </div>


    <script>

        function globalSearch() {

            return {

                query: @json($query),

                results: [],

                resultCount: 0,

                loading: false,

                searched: false,

                debounceTimer: null,

                abortController: null,


                init() {

                    if (this.query.length > 0) {
                        this.performSearch();
                    }

                },


                handleInput() {

                    clearTimeout(
                        this.debounceTimer
                    );


                    this.results = [];

                    this.resultCount = 0;

                    this.searched = false;


                    if (this.query.trim() === '') {

                        this.loading = false;

                        return;
                    }


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
                                    signal: this.abortController.signal
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
                        | Prevent Old Results From Replacing New Query
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

                    }
                    catch (error) {

                        if (
                            error.name !==
                            'AbortError'
                        ) {

                            console.error(
                                'Global search error:',
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

                    this.searched = false;

                    this.loading = false;

                    document
                        .getElementById(
                            'global-search'
                        )
                        ?.focus();

                }

            };

        }

    </script>

</x-app-layout>