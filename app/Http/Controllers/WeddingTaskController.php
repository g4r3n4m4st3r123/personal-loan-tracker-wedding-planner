<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingTaskController extends Controller
{
    /**
     * Display wedding checklist.
     */
    public function index(): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $tasks = $wedding->tasks()
            ->orderByRaw(
                "CASE
                    WHEN status = 'completed' THEN 3
                    WHEN due_date IS NULL THEN 2
                    ELSE 1
                END"
            )
            ->orderBy('due_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Checklist Statistics
        |--------------------------------------------------------------------------
        */

        $totalTasks = $tasks->count();

        $completedTasks = $tasks
            ->where('status', 'completed')
            ->count();

        $pendingTasks = $tasks
            ->where('status', 'pending')
            ->count();

        $inProgressTasks = $tasks
            ->where('status', 'in_progress')
            ->count();

        $overdueTasks = $tasks
            ->filter(fn ($task) => $task->is_overdue)
            ->count();

        $dueSoonTasks = $tasks
            ->filter(fn ($task) => $task->is_due_soon)
            ->count();

        $completionPercentage = $totalTasks > 0
            ? round(
                ($completedTasks / $totalTasks) * 100,
                1
            )
            : 0;

        return view(
            'wedding.checklist.index',
            compact(
                'wedding',
                'tasks',
                'totalTasks',
                'completedTasks',
                'pendingTasks',
                'inProgressTasks',
                'overdueTasks',
                'dueSoonTasks',
                'completionPercentage'
            )
        );
    }

    /**
     * Store a new checklist task.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $validated = $request->validate([
            'task_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'status' => [
                'required',
                'in:pending,in_progress,completed',
            ],
        ]);

        $wedding->tasks()->create($validated);

        return redirect()
            ->route('wedding.checklist')
            ->with(
                'success',
                'Wedding checklist task added successfully.'
            );
    }

    /**
     * Update a checklist task.
     */
    public function update(
        Request $request,
        WeddingTask $task
    ): RedirectResponse {

        if (
            $task->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'task_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'status' => [
                'required',
                'in:pending,in_progress,completed',
            ],
        ]);

        $task->update($validated);

        return redirect()
            ->route('wedding.checklist')
            ->with(
                'success',
                'Wedding checklist task updated successfully.'
            );
    }

    /**
     * Delete a checklist task.
     */
    public function destroy(
        WeddingTask $task
    ): RedirectResponse {

        if (
            $task->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route('wedding.checklist')
            ->with(
                'success',
                'Wedding checklist task deleted successfully.'
            );
    }
}