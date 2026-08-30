<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingTimelineItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingTimelineController extends Controller
{
    /**
     * Display wedding timeline.
     */
    public function index(): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $timelineItems = $wedding->timelineItems()
            ->orderBy('event_date')
            ->orderByRaw("CASE WHEN start_time IS NULL THEN 1 ELSE 0 END")
            ->orderBy('start_time')
            ->orderBy('title')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalItems = $timelineItems->count();

        $completedItems = $timelineItems
            ->where('status', 'completed')
            ->count();

        $plannedItems = $timelineItems
            ->where('status', 'planned')
            ->count();

        $inProgressItems = $timelineItems
            ->where('status', 'in_progress')
            ->count();

        $overdueItems = $timelineItems
            ->filter(fn ($item) => $item->is_past)
            ->count();

        $todayItems = $timelineItems
            ->filter(fn ($item) => $item->is_today)
            ->count();

        $upcomingItems = $timelineItems
            ->filter(fn ($item) => $item->is_upcoming)
            ->count();

        $completionPercentage = $totalItems > 0
            ? round(
                ($completedItems / $totalItems) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Group By Date
        |--------------------------------------------------------------------------
        */

        $groupedTimeline = $timelineItems
            ->groupBy(
                fn ($item) => $item->event_date->format('Y-m-d')
            );

        return view(
            'wedding.timeline.index',
            compact(
                'wedding',
                'timelineItems',
                'groupedTimeline',
                'totalItems',
                'completedItems',
                'plannedItems',
                'inProgressItems',
                'overdueItems',
                'todayItems',
                'upcomingItems',
                'completionPercentage'
            )
        );
    }

    /**
     * Store a timeline item.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:start_time',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'in:preparation,ceremony,reception,meeting,payment,appointment,general',
            ],

            'status' => [
                'required',
                'in:planned,in_progress,completed',
            ],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $wedding->timelineItems()->create(
            $validated
        );

        return redirect()
            ->route('wedding.timeline')
            ->with(
                'success',
                'Timeline item added successfully.'
            );
    }

    /**
     * Update a timeline item.
     */
    public function update(
        Request $request,
        WeddingTimelineItem $timelineItem
    ): RedirectResponse {

        if (
            $timelineItem->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:start_time',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'in:preparation,ceremony,reception,meeting,payment,appointment,general',
            ],

            'status' => [
                'required',
                'in:planned,in_progress,completed',
            ],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $timelineItem->update(
            $validated
        );

        return redirect()
            ->route('wedding.timeline')
            ->with(
                'success',
                'Timeline item updated successfully.'
            );
    }

    /**
     * Delete a timeline item.
     */
    public function destroy(
        WeddingTimelineItem $timelineItem
    ): RedirectResponse {

        if (
            $timelineItem->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $timelineItem->delete();

        return redirect()
            ->route('wedding.timeline')
            ->with(
                'success',
                'Timeline item deleted successfully.'
            );
    }
}   