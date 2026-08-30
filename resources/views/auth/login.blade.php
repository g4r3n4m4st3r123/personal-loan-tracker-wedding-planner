<x-guest-layout>

    <div class="min-h-screen bg-slate-50 px-4">

        <div class="mx-auto flex min-h-screen w-full max-w-5xl items-center justify-center">

            <div class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2">

                {{-- ===================================================== --}}
                {{-- LEFT BRAND PANEL --}}
                {{-- ===================================================== --}}

                <div class="hidden bg-indigo-600 p-10 text-white lg:flex lg:flex-col lg:justify-between">

                    <div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-2xl">
                            💰
                        </div>

                        <h1 class="mt-8 text-3xl font-bold leading-tight">
                            Finance.<br>
                            Plans.<br>
                            Wedding.
                        </h1>

                        <p class="mt-5 max-w-sm text-sm leading-6 text-indigo-100">
                            Everything you need to keep your finances and wedding plans organized.
                        </p>

                    </div>


                    <div class="space-y-3 text-sm text-indigo-100">

                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                ✓
                            </span>
                            <span>Track loans and payments</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                ✓
                            </span>
                            <span>Manage your salary and expenses</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                ✓
                            </span>
                            <span>Plan your wedding in one place</span>
                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- LOGIN PANEL --}}
                {{-- ===================================================== --}}

                <div class="p-7 sm:p-10">

                    <div class="mx-auto w-full max-w-md">

                        {{-- Mobile Brand --}}

                        <div class="mb-8 lg:hidden">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-xl">
                                💰
                            </div>

                            <h1 class="mt-4 text-xl font-bold text-slate-900">
                                Finance & Wedding Planner
                            </h1>

                        </div>


                        {{-- Header --}}

                        <div>

                            <p class="text-sm font-semibold text-indigo-600">
                                Welcome back
                            </p>

                            <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                                Sign in
                            </h2>

                        </div>


                        {{-- Session Status --}}

                        <x-auth-session-status
                            class="mt-5"
                            :status="session('status')"
                        />


                        {{-- Error --}}

                        @if ($errors->any())

                            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">

                                <p class="text-sm font-semibold text-rose-700">
                                    Invalid email or password.
                                </p>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- FORM --}}
                        {{-- ================================================= --}}

                        <form
                            method="POST"
                            action="{{ route('login') }}"
                            class="mt-7 space-y-5"
                        >

                            @csrf


                            {{-- Email --}}

                            <div>

                                <x-input-label
                                    for="email"
                                    :value="__('Email')"
                                    class="text-sm font-medium text-slate-700"
                                />

                                <x-text-input
                                    id="email"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                />

                                <x-input-error
                                    :messages="$errors->get('email')"
                                    class="mt-2"
                                />

                            </div>


                            {{-- Password --}}

                            <div>

                                <div class="flex items-center justify-between">

                                    <x-input-label
                                        for="password"
                                        :value="__('Password')"
                                        class="text-sm font-medium text-slate-700"
                                    />

                                    @if (Route::has('password.request'))

                                        <a
                                            href="{{ route('password.request') }}"
                                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                                        >
                                            Forgot?
                                        </a>

                                    @endif

                                </div>

                                <x-text-input
                                    id="password"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                />

                                <x-input-error
                                    :messages="$errors->get('password')"
                                    class="mt-2"
                                />

                            </div>


                            {{-- Remember --}}

                            <div class="flex items-center">

                                <label
                                    for="remember_me"
                                    class="inline-flex items-center"
                                >

                                    <input
                                        id="remember_me"
                                        type="checkbox"
                                        class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        name="remember"
                                    >

                                    <span class="ms-2 text-xs text-slate-500">
                                        Remember me
                                    </span>

                                </label>

                            </div>


                            {{-- Sign In --}}

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Sign In
                            </button>

                        </form>


                        {{-- Register --}}

                        @if (Route::has('register'))

                            <div class="mt-6 text-center">

                                <p class="text-sm text-slate-500">

                                    Don't have an account?

                                    <a
                                        href="{{ route('register') }}"
                                        class="font-semibold text-indigo-600 hover:text-indigo-800"
                                    >
                                        Create account
                                    </a>

                                </p>

                            </div>

                        @endif


                        {{-- Small footer --}}

                        <div class="mt-8 flex items-center justify-center gap-4 text-xs text-slate-400">

                            <span>Loans</span>

                            <span>•</span>

                            <span>Finance</span>

                            <span>•</span>

                            <span>Wedding</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>