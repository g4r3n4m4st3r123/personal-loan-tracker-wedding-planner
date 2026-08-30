<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Personal Hub
                </p>

                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Notifications
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Stay updated with important finance and wedding reminders.
                </p>

            </div>


            @if ($notifications->count())

                <form
                    method="POST"
                    action="{{ route('notifications.read-all') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        Mark All as Read
                    </button>

                </form>

            @endif

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">


            {{-- Success Message --}}

            @if (session('success'))

                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- Notification List --}}

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                @forelse ($notifications as $notification)

                    <div
                        class="border-b border-slate-100 last:border-b-0
                        {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50/40' }}"
                    >

                        <div class="flex gap-4 p-5 sm:p-6">


                            {{-- Icon --}}

                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                {{ $notification->read_at
                                    ? 'bg-slate-100'
                                    : 'bg-indigo-100' }}"
                            >

                                @if ($notification->type === 'loan')

                                    <span class="text-lg">
                                        💳
                                    </span>

                                @elseif ($notification->type === 'payment')

                                    <span class="text-lg">
                                        💰
                                    </span>

                                @elseif ($notification->type === 'wedding')

                                    <span class="text-lg">
                                        💍
                                    </span>

                                @elseif ($notification->type === 'expense')

                                    <span class="text-lg">
                                        💸
                                    </span>

                                @elseif ($notification->type === 'salary')

                                    <span class="text-lg">
                                        💵
                                    </span>

                                @else

                                    <span class="text-lg">
                                        🔔
                                    </span>

                                @endif

                            </div>


                            {{-- Content --}}

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                                    <div>

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h3
                                                class="text-sm font-bold
                                                {{ $notification->read_at
                                                    ? 'text-slate-800'
                                                    : 'text-slate-900' }}"
                                            >
                                                {{ $notification->title }}
                                            </h3>


                                            @unless ($notification->read_at)

                                                <span class="rounded-full bg-indigo-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">
                                                    New
                                                </span>

                                            @endunless

                                        </div>


                                        <p class="mt-1 text-sm leading-6 text-slate-600">
                                            {{ $notification->message }}
                                        </p>

                                    </div>


                                    <span class="shrink-0 text-xs text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>

                                </div>


                                <div class="mt-4 flex flex-wrap items-center gap-3">

                                    @if ($notification->url)

                                        <form
                                            method="POST"
                                            action="{{ route('notifications.read', $notification) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                                            >
                                                View Details →
                                            </button>

                                        </form>

                                    @elseif (!$notification->read_at)

                                        <form
                                            method="POST"
                                            action="{{ route('notifications.read', $notification) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
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

                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">
                            🔔
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-900">
                            No notifications
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                            You're all caught up. New finance and wedding reminders will appear here.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}

            @if ($notifications->hasPages())

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>