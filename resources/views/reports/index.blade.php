<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">
                Analytics
            </p>

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                Reports
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Choose which area you want to review.
            </p>
        </div>
    </x-slot>

    <div class="flex flex-wrap gap-2">

        <a
            href="{{ route('wedding.printables', 'complete') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            🖨️ Printables
        </a>

        <a
            href="{{ route('wedding.printables', 'budget') }}"
            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Budget
        </a>

        <a
            href="{{ route('wedding.printables', 'guests') }}"
            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Guest List
        </a>

        <a
            href="{{ route('wedding.printables', 'seating') }}"
            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Seating Chart
        </a>

    </div>

    <div class="py-10">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- Intro --}}
            <div class="mb-8 max-w-2xl">

                <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">
                    Insights
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                    Your reports, simplified.
                </h1>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                    Review your personal finances and wedding planning
                    separately for a cleaner and more focused view.
                </p>

            </div>


            {{-- Report Options --}}
            <div class="grid gap-6 md:grid-cols-2">

                {{-- Finance --}}
                <a
                    href="{{ route('reports.finance') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg"
                >

                    <div class="flex items-start justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">

                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6v12m-3-2.5h6M6.5 9h11M6 19h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>

                        </div>

                        <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-indigo-500">
                            →
                        </span>

                    </div>


                    <div class="mt-7">

                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                            Finance
                        </p>

                        <h3 class="mt-2 text-xl font-bold text-slate-900">
                            Finance Reports
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Track your income, expenses, loan payments,
                            remaining money, and overall debt position.
                        </p>

                    </div>


                    <div class="mt-7 flex flex-wrap gap-2">

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Income
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Expenses
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Loans
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Debt
                        </span>

                    </div>

                </a>


                {{-- Wedding --}}
                <a
                    href="{{ route('reports.wedding') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-rose-200 hover:shadow-lg"
                >

                    <div class="flex items-start justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">

                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 20.5S4 15.7 4 9.5A4.5 4.5 0 018.5 5c1.5 0 2.8.7 3.5 1.8A4.1 4.1 0 0115.5 5 4.5 4.5 0 0120 9.5c0 6.2-8 11-8 11z"
                                />
                            </svg>

                        </div>

                        <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-rose-500">
                            →
                        </span>

                    </div>


                    <div class="mt-7">

                        <p class="text-xs font-bold uppercase tracking-wider text-rose-500">
                            Wedding
                        </p>

                        <h3 class="mt-2 text-xl font-bold text-slate-900">
                            Wedding Reports
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Review your wedding budget, guests, checklist,
                            vendors, and overall planning progress.
                        </p>

                    </div>


                    <div class="mt-7 flex flex-wrap gap-2">

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Budget
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Guests
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Checklist
                        </span>

                        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">
                            Vendors
                        </span>

                    </div>

                </a>

            </div>


            {{-- Bottom note --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">

                <div class="flex items-start gap-3">

                    <div class="mt-0.5 text-slate-400">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="12" cy="12" r="9" />
                            <path
                                stroke-linecap="round"
                                d="M12 10v6m0-9h.01"
                            />
                        </svg>
                    </div>

                    <p class="text-sm leading-6 text-slate-500">
                        Finance and wedding reports are separated so you can
                        focus on one area at a time without unnecessary
                        information on the screen.
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>