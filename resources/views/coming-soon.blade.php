<x-app-layout>

    <x-slot name="header">

        <div>
            <p class="text-sm font-medium text-gray-500">
                {{ $section ?? 'Module' }}
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                {{ $title ?? 'Coming Soon' }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                This module is part of your Personal Hub and will be built next.
            </p>
        </div>

    </x-slot>


    <div class="py-12">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200">

                <div class="text-5xl">
                    🚧
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900">
                    {{ $title ?? 'Coming Soon' }}
                </h3>

                <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-gray-500">
                    This section is already connected to the sidebar.
                    The full functionality will be implemented in the next development phase.
                </p>

                <div class="mt-6">

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Back to Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>