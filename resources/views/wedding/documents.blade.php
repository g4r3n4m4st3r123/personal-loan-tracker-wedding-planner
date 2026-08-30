<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Documents & Photos
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Keep your wedding contracts, receipts, documents, photos, and inspiration in one place.
                </p>

            </div>


            <div class="flex flex-wrap items-center gap-2">

                {{-- ADD FILE --}}

                <button
                    type="button"
                    onclick="openUploadModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >

                    <span class="text-lg leading-none">
                        +
                    </span>

                    Upload File

                </button>


                {{-- WEDDING OVERVIEW --}}

                <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    ← Wedding Overview
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- SUCCESS --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">

                    <p class="font-semibold text-rose-800">
                        Please fix the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-rose-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            @if (!$wedding)

                <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-200">

                    <div class="text-5xl">
                        💍
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-gray-900">
                        Create your wedding first
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Set up your wedding before uploading documents and photos.
                    </p>

                    <a
                        href="{{ route('wedding.index') }}"
                        class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Go to Wedding Overview
                    </a>

                </div>

            @else


                {{-- ===================================================== --}}
                {{-- SUMMARY --}}
                {{-- ===================================================== --}}

                <div class="grid gap-4 sm:grid-cols-3">


                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <p class="text-sm font-medium text-gray-500">
                            Total Files
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $totalFiles }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            All wedding uploads
                        </p>

                    </div>


                    <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                        <p class="text-sm font-medium text-indigo-700">
                            Photos
                        </p>

                        <p class="mt-2 text-2xl font-bold text-indigo-700">
                            {{ $totalPhotos }}
                        </p>

                        <p class="mt-1 text-xs text-indigo-600">
                            Wedding images
                        </p>

                    </div>


                    <div class="rounded-2xl bg-slate-50 p-5 shadow-sm ring-1 ring-slate-200">

                        <p class="text-sm font-medium text-slate-600">
                            Documents
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-800">
                            {{ $totalDocuments }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Contracts, receipts, files
                        </p>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- SEARCH / FILTER --}}
                {{-- ===================================================== --}}

                <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <form
                        method="GET"
                        action="{{ route('wedding.documents') }}"
                        class="grid gap-4 md:grid-cols-4"
                    >

                        <div class="md:col-span-2">

                            <label
                                for="search"
                                class="block text-sm font-semibold text-gray-700"
                            >
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Search file name or description"
                                class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        <div>

                            <label
                                for="category"
                                class="block text-sm font-semibold text-gray-700"
                            >
                                Category
                            </label>

                            <select
                                name="category"
                                id="category"
                                class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    All Categories
                                </option>

                                @foreach ($categories as $category)

                                    <option
                                        value="{{ $category }}"
                                        @selected(request('category') === $category)
                                    >
                                        {{ $category }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                            >
                                Filter
                            </button>

                            <a
                                href="{{ route('wedding.documents') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            >
                                Clear
                            </a>

                        </div>

                    </form>

                </div>


                {{-- ===================================================== --}}
                {{-- FILE LIBRARY --}}
                {{-- ===================================================== --}}

                <div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h3 class="text-lg font-bold text-gray-900">
                            Wedding File Library
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Your wedding documents, photos, contracts, and other important files.
                        </p>

                    </div>


                    @if ($documents->count())

                        <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                            @foreach ($documents as $document)

                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white transition hover:-translate-y-0.5 hover:shadow-md">


                                    {{-- IMAGE PREVIEW --}}

                                    @if ($document->is_image)

                                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">

                                            <img
                                                src="{{ asset('storage/' . $document->file_path) }}"
                                                alt="{{ $document->file_name }}"
                                                class="h-full w-full object-cover"
                                            >

                                        </div>

                                    @else

                                        {{-- DOCUMENT PREVIEW --}}

                                        <div class="flex aspect-[4/3] items-center justify-center bg-slate-50">

                                            <div class="text-center">

                                                <div class="text-5xl">
                                                    📄
                                                </div>

                                                <p class="mt-2 px-4 text-xs font-medium text-gray-400">
                                                    {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                                                </p>

                                            </div>

                                        </div>

                                    @endif


                                    {{-- FILE INFO --}}

                                    <div class="p-4">

                                        <div class="flex items-start justify-between gap-3">

                                            <div class="min-w-0">

                                                <p
                                                    class="truncate font-semibold text-gray-900"
                                                    title="{{ $document->file_name }}"
                                                >
                                                    {{ $document->file_name }}
                                                </p>

                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $document->category }}
                                                    ·
                                                    {{ $document->formatted_size }}
                                                </p>

                                            </div>


                                            <span class="shrink-0 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                {{ $document->category }}
                                            </span>

                                        </div>


                                        @if ($document->description)

                                            <p class="mt-3 line-clamp-2 text-sm text-gray-500">
                                                {{ $document->description }}
                                            </p>

                                        @endif


                                        <div class="mt-4 flex items-center gap-2 border-t border-gray-100 pt-4">

                                            <a
                                                href="{{ asset('storage/' . $document->file_path) }}"
                                                target="_blank"
                                                class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route('wedding.documents.download', $document) }}"
                                                class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700"
                                            >
                                                Download
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('wedding.documents.destroy', $document) }}"
                                                onsubmit="return confirm('Delete this file?');"
                                                class="m-0"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="px-6 py-14 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-3xl">
                                📁
                            </div>

                            <h3 class="mt-5 font-bold text-gray-900">
                                No files yet
                            </h3>

                            <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                                Upload your first wedding contract, receipt, photo, or important document.
                            </p>

                            <button
                                type="button"
                                onclick="openUploadModal()"
                                class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                + Upload First File
                            </button>

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- UPLOAD MODAL --}}
    {{-- ================================================================ --}}

    <div
        id="upload-file-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
        onclick="if(event.target === this) closeUploadModal()"
    >

        <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">


            {{-- MODAL HEADER --}}

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Wedding Planner
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Upload File
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload a photo, contract, receipt, or important wedding document.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeUploadModal()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                >
                    ✕
                </button>

            </div>


            {{-- FORM --}}

            <form
                method="POST"
                action="{{ route('wedding.documents.store') }}"
                enctype="multipart/form-data"
                class="space-y-5 p-6"
            >

                @csrf


                {{-- FILE --}}

                <div>

                    <label
                        for="file"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        File
                    </label>

                    <input
                        type="file"
                        name="file"
                        id="file"
                        required
                        accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx"
                        class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p class="mt-1 text-xs text-gray-400">
                        Maximum file size: 20 MB.
                    </p>

                </div>


                {{-- CATEGORY --}}

                <div>

                    <label
                        for="upload_category"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Category
                    </label>

                    <select
                        name="category"
                        id="upload_category"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="Vendor Contract"
                            @selected(old('category') === 'Vendor Contract')
                        >
                            Vendor Contract
                        </option>

                        <option
                            value="Receipt"
                            @selected(old('category') === 'Receipt')
                        >
                            Receipt
                        </option>

                        <option
                            value="Invoice"
                            @selected(old('category') === 'Invoice')
                        >
                            Invoice
                        </option>

                        <option
                            value="Marriage Document"
                            @selected(old('category') === 'Marriage Document')
                        >
                            Marriage Document
                        </option>

                        <option
                            value="Permit"
                            @selected(old('category') === 'Permit')
                        >
                            Permit
                        </option>

                        <option
                            value="Photo"
                            @selected(old('category') === 'Photo')
                        >
                            Photo
                        </option>

                        <option
                            value="Inspiration"
                            @selected(old('category') === 'Inspiration')
                        >
                            Inspiration
                        </option>

                        <option
                            value="Other"
                            @selected(old('category', 'Other') === 'Other')
                        >
                            Other
                        </option>

                    </select>

                </div>


                {{-- DESCRIPTION --}}

                <div>

                    <label
                        for="upload_description"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Description
                        <span class="font-normal text-gray-400">
                            (Optional)
                        </span>
                    </label>

                    <textarea
                        name="description"
                        id="upload_description"
                        rows="3"
                        placeholder="e.g. Final catering contract signed on June 12"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

                </div>


                {{-- BUTTONS --}}

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                    <button
                        type="button"
                        onclick="closeUploadModal()"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Upload File
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================================ --}}

    <script>

        function openUploadModal() {

            const modal = document.getElementById('upload-file-modal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');

            setTimeout(function () {

                const input = document.getElementById('file');

                if (input) {
                    input.focus();
                }

            }, 100);

        }


        function closeUploadModal() {

            const modal = document.getElementById('upload-file-modal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

        }


        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            closeUploadModal();

        });


        @if ($errors->any())

            document.addEventListener('DOMContentLoaded', function () {

                openUploadModal();

            });

        @endif

    </script>

</x-app-layout>