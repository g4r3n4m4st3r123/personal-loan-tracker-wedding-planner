<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            {{-- ========================================================= --}}
            {{-- PAGE TITLE --}}
            {{-- ========================================================= --}}

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Wedding Planner
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    Wedding Checklist
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Keep track of the tasks you need to complete before your wedding.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- HEADER ACTIONS --}}
            {{-- ========================================================= --}}

            <div class="flex flex-wrap items-center gap-2">

                {{-- ADD TASK --}}
                <button
                    type="button"
                    onclick="openAddTaskModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >

                    <span class="text-lg leading-none">
                        +
                    </span>

                    Add Task

                </button>


<!--                 {{-- WEDDING OVERVIEW --}}
                <a
                    href="{{ route('wedding.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    ← Wedding Overview
                </a> -->

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERROR MESSAGE --}}
            {{-- ========================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <p class="font-semibold text-red-800">
                        Please fix the following:
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


            {{-- ========================================================= --}}
            {{-- PLANNING PROGRESS HEADER --}}
            {{-- ========================================================= --}}

            <div>

                <p class="text-sm font-medium text-indigo-600">
                    Planning Progress
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900">
                    Wedding Tasks
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Organize everything you need to accomplish before the big day.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- STATISTICS --}}
            {{-- ========================================================= --}}

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">


                {{-- TOTAL --}}

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Total Tasks
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalTasks }}
                    </p>

                </div>


                {{-- COMPLETED --}}

                <div class="rounded-2xl bg-emerald-50 p-5 shadow-sm ring-1 ring-emerald-200">

                    <p class="text-sm font-medium text-emerald-700">
                        Completed
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $completedTasks }}
                    </p>

                </div>


                {{-- IN PROGRESS --}}

                <div class="rounded-2xl bg-amber-50 p-5 shadow-sm ring-1 ring-amber-200">

                    <p class="text-sm font-medium text-amber-700">
                        In Progress
                    </p>

                    <p class="mt-2 text-2xl font-bold text-amber-700">
                        {{ $inProgressTasks }}
                    </p>

                </div>


                {{-- OVERDUE --}}

                <div class="rounded-2xl bg-red-50 p-5 shadow-sm ring-1 ring-red-200">

                    <p class="text-sm font-medium text-red-700">
                        Overdue
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-700">
                        {{ $overdueTasks }}
                    </p>

                </div>


                {{-- COMPLETION --}}

                <div class="rounded-2xl bg-indigo-50 p-5 shadow-sm ring-1 ring-indigo-200">

                    <p class="text-sm font-medium text-indigo-700">
                        Completion
                    </p>

                    <p class="mt-2 text-2xl font-bold text-indigo-700">
                        {{ number_format($completionPercentage, 1) }}%
                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- PROGRESS --}}
            {{-- ========================================================= --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h3 class="font-bold text-gray-900">
                            Checklist Progress
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $completedTasks }} of {{ $totalTasks }} tasks completed.
                        </p>

                    </div>

                    <span class="text-lg font-bold text-indigo-600">
                        {{ number_format($completionPercentage, 1) }}%
                    </span>

                </div>


                <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100">

                    <div
                        class="h-full rounded-full bg-indigo-500 transition-all duration-500"
                        style="width: {{ min(100, max(0, $completionPercentage)) }}%"
                    ></div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TASK LIST --}}
            {{-- ========================================================= --}}

            <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">


                {{-- TASK LIST HEADER --}}

                <div class="border-b border-gray-200 px-6 py-5">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Wedding Tasks
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Your wedding planning checklist.
                            </p>

                        </div>


                        @if ($tasks->count())

                            <span class="text-sm text-gray-400">

                                {{ $tasks->count() }}

                                {{ $tasks->count() === 1 ? 'task' : 'tasks' }}

                            </span>

                        @endif

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- TASKS EXIST --}}
                {{-- ===================================================== --}}

                @if ($tasks->count())

                    <div class="divide-y divide-gray-100">

                        @foreach ($tasks as $task)

                            <div class="p-6 transition hover:bg-gray-50/50">

                                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


                                    {{-- ================================================= --}}
                                    {{-- TASK INFORMATION --}}
                                    {{-- ================================================= --}}

                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2">


                                            {{-- TASK NAME --}}

                                            <h4
                                                class="font-semibold
                                                {{ $task->status === 'completed'
                                                    ? 'text-gray-400 line-through'
                                                    : 'text-gray-900' }}"
                                            >
                                                {{ $task->task_name }}
                                            </h4>


                                            {{-- PRIORITY --}}

                                            @if ($task->priority === 'high')

                                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    High
                                                </span>

                                            @elseif ($task->priority === 'medium')

                                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    Medium
                                                </span>

                                            @else

                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                    Low
                                                </span>

                                            @endif


                                            {{-- STATUS --}}

                                            @if ($task->status === 'completed')

                                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    Completed
                                                </span>

                                            @elseif ($task->status === 'in_progress')

                                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                    In Progress
                                                </span>

                                            @else

                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                    Pending
                                                </span>

                                            @endif


                                            {{-- DUE STATUS --}}

                                            @if ($task->is_overdue)

                                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    Overdue
                                                </span>

                                            @elseif ($task->is_due_soon)

                                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                    Due Soon
                                                </span>

                                            @endif

                                        </div>


                                        {{-- DESCRIPTION --}}

                                        @if ($task->description)

                                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                                {{ $task->description }}
                                            </p>

                                        @endif


                                        {{-- DUE DATE --}}

                                        @if ($task->due_date)

                                            <p class="mt-2 text-xs font-medium text-gray-400">
                                                Due {{ $formatter->date($task->due_date) }}
                                            </p>

                                        @endif

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- ACTION BUTTONS --}}
                                    {{-- ================================================= --}}

                                    <div class="flex shrink-0 items-center gap-2">


                                        {{-- EDIT --}}

                                        <button
                                            type="button"
                                            onclick="openEditTaskModal({{ $task->id }})"
                                            class="inline-flex h-9 min-w-[70px] items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                        >
                                            Edit
                                        </button>


                                        {{-- DELETE --}}

                                        <form
                                            method="POST"
                                            action="{{ route('wedding.checklist.destroy', $task) }}"
                                            onsubmit="return confirm('Delete this checklist task?');"
                                            class="m-0 flex"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex h-9 min-w-[70px] items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </div>


                                {{-- ===================================================== --}}
                                {{-- EDIT TASK MODAL --}}
                                {{-- ===================================================== --}}

                                <div
                                    id="edit-task-{{ $task->id }}"
                                    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
                                    onclick="if(event.target === this) closeEditTaskModal({{ $task->id }})"
                                >

                                    <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">


                                        {{-- MODAL HEADER --}}

                                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                                            <div>

                                                <p class="text-sm font-medium text-indigo-600">
                                                    Wedding Checklist
                                                </p>

                                                <h3 class="mt-1 text-lg font-bold text-gray-900">
                                                    Edit Checklist Task
                                                </h3>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Update the task details.
                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="closeEditTaskModal({{ $task->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                            >
                                                ✕
                                            </button>

                                        </div>


                                        {{-- MODAL FORM --}}

                                        <form
                                            method="POST"
                                            action="{{ route('wedding.checklist.update', $task) }}"
                                            class="grid gap-5 p-6 md:grid-cols-2"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            {{-- TASK --}}

                                            <div>

                                                <label
                                                    for="edit_task_name_{{ $task->id }}"
                                                    class="block text-sm font-semibold text-gray-700"
                                                >
                                                    Task
                                                </label>

                                                <input
                                                    type="text"
                                                    name="task_name"
                                                    id="edit_task_name_{{ $task->id }}"
                                                    value="{{ $task->task_name }}"
                                                    required
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >

                                            </div>


                                            {{-- DUE DATE --}}

                                            <div>

                                                <label
                                                    for="edit_due_date_{{ $task->id }}"
                                                    class="block text-sm font-semibold text-gray-700"
                                                >
                                                    Due Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="due_date"
                                                    id="edit_due_date_{{ $task->id }}"
                                                    value="{{ $task->due_date?->format('Y-m-d') }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >

                                            </div>


                                            {{-- PRIORITY --}}

                                            <div>

                                                <label
                                                    for="edit_priority_{{ $task->id }}"
                                                    class="block text-sm font-semibold text-gray-700"
                                                >
                                                    Priority
                                                </label>

                                                <select
                                                    name="priority"
                                                    id="edit_priority_{{ $task->id }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >

                                                    <option
                                                        value="low"
                                                        @selected($task->priority === 'low')
                                                    >
                                                        Low
                                                    </option>

                                                    <option
                                                        value="medium"
                                                        @selected($task->priority === 'medium')
                                                    >
                                                        Medium
                                                    </option>

                                                    <option
                                                        value="high"
                                                        @selected($task->priority === 'high')
                                                    >
                                                        High
                                                    </option>

                                                </select>

                                            </div>


                                            {{-- STATUS --}}

                                            <div>

                                                <label
                                                    for="edit_status_{{ $task->id }}"
                                                    class="block text-sm font-semibold text-gray-700"
                                                >
                                                    Status
                                                </label>

                                                <select
                                                    name="status"
                                                    id="edit_status_{{ $task->id }}"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >

                                                    <option
                                                        value="pending"
                                                        @selected($task->status === 'pending')
                                                    >
                                                        Pending
                                                    </option>

                                                    <option
                                                        value="in_progress"
                                                        @selected($task->status === 'in_progress')
                                                    >
                                                        In Progress
                                                    </option>

                                                    <option
                                                        value="completed"
                                                        @selected($task->status === 'completed')
                                                    >
                                                        Completed
                                                    </option>

                                                </select>

                                            </div>


                                            {{-- DESCRIPTION --}}

                                            <div class="md:col-span-2">

                                                <label
                                                    for="edit_description_{{ $task->id }}"
                                                    class="block text-sm font-semibold text-gray-700"
                                                >
                                                    Description
                                                </label>

                                                <textarea
                                                    name="description"
                                                    id="edit_description_{{ $task->id }}"
                                                    rows="3"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >{{ $task->description }}</textarea>

                                            </div>


                                            {{-- MODAL BUTTONS --}}

                                            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                                                <button
                                                    type="button"
                                                    onclick="closeEditTaskModal({{ $task->id }})"
                                                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                                >
                                                    Save Changes
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                @else


                    {{-- ================================================= --}}
                    {{-- EMPTY STATE --}}
                    {{-- ================================================= --}}

                    <div class="px-6 py-14 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-3xl">
                            📋
                        </div>

                        <h3 class="mt-5 font-bold text-gray-900">
                            No checklist tasks yet
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                            Start organizing your wedding by adding your first planning task.
                        </p>

                        <button
                            type="button"
                            onclick="openAddTaskModal()"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >

                            <span class="text-lg leading-none">
                                +
                            </span>

                            Add First Task

                        </button>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- ADD TASK MODAL --}}
    {{-- ================================================================ --}}

    <div
        id="add-task-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 px-4 py-8 backdrop-blur-sm"
        onclick="if(event.target === this) closeAddTaskModal()"
    >

        <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">


            {{-- ========================================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ========================================================= --}}

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <p class="text-sm font-medium text-indigo-600">
                        Wedding Checklist
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Add Checklist Task
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Add something you need to complete for your wedding.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeAddTaskModal()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                >
                    ✕
                </button>

            </div>


            {{-- ========================================================= --}}
            {{-- MODAL FORM --}}
            {{-- ========================================================= --}}

            <form
                method="POST"
                action="{{ route('wedding.checklist.store') }}"
                class="grid gap-5 p-6 md:grid-cols-2"
            >

                @csrf


                {{-- TASK --}}

                <div>

                    <label
                        for="task_name"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Task
                    </label>

                    <input
                        type="text"
                        name="task_name"
                        id="task_name"
                        value="{{ old('task_name') }}"
                        required
                        placeholder="e.g. Book wedding photographer"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- DUE DATE --}}

                <div>

                    <label
                        for="due_date"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date') }}"
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                {{-- PRIORITY --}}

                <div>

                    <label
                        for="priority"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Priority
                    </label>

                    <select
                        name="priority"
                        id="priority"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="low"
                            @selected(old('priority') === 'low')
                        >
                            Low
                        </option>

                        <option
                            value="medium"
                            @selected(old('priority', 'medium') === 'medium')
                        >
                            Medium
                        </option>

                        <option
                            value="high"
                            @selected(old('priority') === 'high')
                        >
                            High
                        </option>

                    </select>

                </div>


                {{-- STATUS --}}

                <div>

                    <label
                        for="status"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="pending"
                            @selected(old('status', 'pending') === 'pending')
                        >
                            Pending
                        </option>

                        <option
                            value="in_progress"
                            @selected(old('status') === 'in_progress')
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            @selected(old('status') === 'completed')
                        >
                            Completed
                        </option>

                    </select>

                </div>


                {{-- DESCRIPTION --}}

                <div class="md:col-span-2">

                    <label
                        for="description"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Description

                        <span class="font-normal text-gray-400">
                            (Optional)
                        </span>

                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Add notes about this task..."
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

                </div>


                {{-- MODAL BUTTONS --}}

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 md:col-span-2">

                    <button
                        type="button"
                        onclick="closeAddTaskModal()"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Add Task
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- MODAL JAVASCRIPT --}}
    {{-- ================================================================ --}}

    <script>

        function openAddTaskModal() {

            const modal = document.getElementById('add-task-modal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');


            setTimeout(() => {

                const input = document.getElementById('task_name');

                if (input) {
                    input.focus();
                }

            }, 100);

        }


        function closeAddTaskModal() {

            const modal = document.getElementById('add-task-modal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

        }


        function openEditTaskModal(taskId) {

            const modal = document.getElementById('edit-task-' + taskId);

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');

        }


        function closeEditTaskModal(taskId) {

            const modal = document.getElementById('edit-task-' + taskId);

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

        }


        // ================================================================
        // CLOSE MODAL WITH ESC KEY
        // ================================================================

        document.addEventListener('keydown', function(event) {

            if (event.key !== 'Escape') {
                return;
            }


            closeAddTaskModal();


            document
                .querySelectorAll('[id^="edit-task-"]')
                .forEach(function(modal) {

                    modal.classList.add('hidden');

                });


            document.body.classList.remove('overflow-hidden');

        });

    </script>

</x-app-layout>