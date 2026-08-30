<aside
    class="fixed inset-y-0 left-0 z-50 w-72 border-r border-slate-200 bg-white shadow-sm transition-transform duration-300
           -translate-x-full lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen }"
>

    {{-- =============================================================== --}}
    {{-- BRAND --}}
    {{-- =============================================================== --}}

    <div class="flex h-16 items-center border-b border-slate-200 px-6">

        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3"
        >

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">

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
                        d="M3 10.5L12 3l9 7.5M5.5 9.5V21h13V9.5M9 21v-6h6v6"
                    />
                </svg>

            </div>


            <div>

                <h1 class="text-sm font-bold tracking-tight text-slate-900">
                    Personal Hub
                </h1>

                <p class="text-xs text-slate-500">
                    Finance & Wedding
                </p>

            </div>

        </a>

    </div>


    {{-- =============================================================== --}}
    {{-- NAVIGATION --}}
    {{-- =============================================================== --}}

    <div class="flex h-[calc(100vh-4rem)] flex-col overflow-y-auto px-4 py-5">

        <nav class="space-y-6">


            {{-- ======================================================= --}}
            {{-- MAIN --}}
            {{-- ======================================================= --}}

            <div>

                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Main
                </p>


                {{-- Dashboard --}}

                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-slate-900 text-white shadow-sm'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
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
                            d="M3 12l9-9 9 9M5 10v10h14V10"
                        />
                    </svg>

                    Dashboard

                </a>

            </div>


            {{-- ======================================================= --}}
            {{-- FINANCE --}}
            {{-- ======================================================= --}}

            <div>

                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Finance
                </p>


                <div class="space-y-1">


                    {{-- Income --}}

                    <a
                        href="{{ route('income.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('income.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💰
                        </span>

                        Income

                    </a>


                    {{-- Loans --}}

                    <a
                        href="{{ route('loans.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('loans.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💳
                        </span>

                        Loans

                    </a>


                    {{-- Expenses --}}

                    <a
                        href="{{ route('expenses.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('expenses.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💸
                        </span>

                        Expenses

                    </a>


                    {{-- Salary --}}

                    <a
                        href="{{ route('salary.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('salary.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💵
                        </span>

                        Salary

                    </a>


                    {{-- Payments --}}

                    <a
                        href="{{ route('payments.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('payments.*', 'loan-payments.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            🧾
                        </span>

                        Payments

                    </a>

                    <a
                        href="{{ route('debt-free.index') }}"
                        class="{{ request()->routeIs('debt-free.*')
                            ? 'bg-indigo-50 text-indigo-700'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                    >
                        <span class="text-lg">🎯</span>
                        <span>Debt-Free Planner</span>
                    </a>

                </div>

            </div>


            {{-- ======================================================= --}}
            {{-- WEDDING --}}
            {{-- ======================================================= --}}

            <div>

                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Wedding
                </p>


                <div class="space-y-1">


                    {{-- Overview --}}

                    <a
                        href="{{ route('wedding.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.index')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💍
                        </span>

                        Overview

                    </a>


                    {{-- Checklist --}}

                    <a
                        href="{{ route('wedding.checklist') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.checklist*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            📋
                        </span>

                        Checklist

                    </a>


                    {{-- Budget --}}

                    <a
                        href="{{ route('wedding.budget') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.budget*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💰
                        </span>

                        Budget

                    </a>


                    {{-- Expenses --}}

                    <a
                        href="{{ route('wedding.expenses') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.expenses*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            💸
                        </span>

                        Expenses

                    </a>


                    {{-- Guests --}}

                    <a
                        href="{{ route('wedding.guests') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.guests*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            👥
                        </span>

                        Guests

                    </a>


                    {{-- Seating Arrangement --}}

                    <a
                        href="{{ route('wedding.seating') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.seating*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <span class="text-lg">
                            🪑
                        </span>

                        Seating
                    </a>


                    {{-- Documents & Photos --}}

                    <a
                        href="{{ route('wedding.documents') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.documents*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <span class="text-lg">
                            📁
                        </span>

                        Documents
                    </a>


                    {{-- Day-of Wedding Mode --}}

                    <a
                        href="{{ route('wedding.day-of') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.day-of')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <span class="text-lg">
                            💒
                        </span>

                        Day-of Mode
                    </a>


                    {{-- Vendors --}}

                    <a
                        href="{{ route('wedding.vendors') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.vendors*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            🏪
                        </span>

                        Vendors

                    </a>


                    {{-- Timeline --}}

                    <a
                        href="{{ route('wedding.timeline') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.timeline*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            📅
                        </span>

                        Timeline

                    </a>


                    {{-- Calendar --}}

                    <a
                        href="{{ route('wedding.calendar') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.calendar*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            🗓️
                        </span>

                        Calendar

                    </a>

                </div>

            </div>


            {{-- ======================================================= --}}
            {{-- REPORTS --}}
            {{-- ======================================================= --}}

            <div>

                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Reports
                </p>


                <div class="space-y-1">

                    {{-- Reports --}}

                    <a
                        href="{{ route('reports.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('reports.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            📊
                        </span>

                        Reports

                    </a>


                    {{-- Printables --}}

                    <a
                        href="{{ route('wedding.printables', 'complete') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('wedding.printables*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >

                        <span class="text-lg">
                            🖨️
                        </span>

                        Printables

                    </a>

                </div>

            </div>


        </nav>


        {{-- =============================================================== --}}
        {{-- BOTTOM NAVIGATION --}}
        {{-- =============================================================== --}}

        <div class="mt-auto border-t border-slate-200 pt-4">


            {{-- Settings --}}

            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('profile.*')
                    ? 'bg-slate-900 text-white'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
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
                        d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM19.4 15a1.7 1.7 0 00.3 1.9l.1.1-1.7 1.7-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5v.2h-2.4v-.2a1.7 1.7 0 00-1-1.5 1.7 1.7 0 00-1.9.3l-.1.1-1.7-1.7.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H6.8v-2.4H7a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1 1.7-1.7.1.1a1.7 1.7 0 001.9.3 1.7 1.7 0 001.9-.3l.1-.1 1.7 1.7-.1.1a1.7 1.7 0 00-.3 1.9 1.7 1.7 0 001.5 1h.2v2.4H21a1.7 1.7 0 00-1.6 1z"
                    />

                </svg>

                Settings

            </a>


            {{-- Logout --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mt-1"
            >

                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-rose-50 hover:text-rose-600"
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
                            d="M15 12H3m0 0l4-4m-4 4l4 4M21 4v16"
                        />

                    </svg>

                    Log out

                </button>

            </form>

        </div>

    </div>

</aside>


{{-- =============================================================== --}}
{{-- MOBILE OVERLAY --}}
{{-- =============================================================== --}}

<!--
<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"
    style="display: none;"
></div>
-->