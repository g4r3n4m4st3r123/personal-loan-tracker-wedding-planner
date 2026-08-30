<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingTask;
use App\Models\WeddingVendor;
use App\Models\WeddingSeating;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingDayOfController extends Controller
{
    /**
     * Display the Day-of Wedding Mode.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Wedding
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->first();

        if (!$wedding) {

            return view('wedding.day-of', [
                'wedding' => null,
                'dayOfDate' => now(),
                'todayTimeline' => collect(),
                'todayTasks' => collect(),
                'todayVendors' => collect(),
                'attendingGuests' => 0,
                'pendingGuests' => 0,
                'declinedGuests' => 0,
                'totalGuests' => 0,
                'totalTables' => 0,
                'assignedGuests' => 0,
                'unassignedGuests' => 0,
                'completedTasks' => 0,
                'totalTasks' => 0,
                'completedTodayTasks' => 0,
                'totalTodayTasks' => 0,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Day-of Date
        |--------------------------------------------------------------------------
        |
        | Normally this uses today.
        | For testing, you can use:
        |
        | /wedding/day-of?date=2026-12-12
        |
        */

        $dayOfDate = $request->filled('date')
            ? now()->parse(
                $request->input('date')
            )
            : now();


        /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        |
        | Uses the existing WeddingTimelineItem model and the same
        | wedding->timelineItems() relationship already used by
        | WeddingTimelineController.
        |
        */

        $todayTimeline = $wedding->timelineItems()
            ->whereDate(
                'event_date',
                $dayOfDate->format('Y-m-d')
            )
            ->orderByRaw(
                'CASE WHEN start_time IS NULL THEN 1 ELSE 0 END'
            )
            ->orderBy('start_time')
            ->orderBy('title')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Checklist
        |--------------------------------------------------------------------------
        */

        $allTasks = WeddingTask::where(
            'wedding_id',
            $wedding->id
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Today's Tasks
        |--------------------------------------------------------------------------
        */

        $todayTasks = $allTasks
            ->filter(function ($task) use ($dayOfDate) {

                if (!$task->due_date) {
                    return false;
                }

                return $task->due_date->isSameDay(
                    $dayOfDate
                );

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Checklist Statistics
        |--------------------------------------------------------------------------
        */

        $completedTasks = $allTasks
            ->where(
                'status',
                'completed'
            )
            ->count();


        $totalTasks = $allTasks->count();


        $completedTodayTasks = $todayTasks
            ->where(
                'status',
                'completed'
            )
            ->count();


        $totalTodayTasks = $todayTasks->count();


        /*
        |--------------------------------------------------------------------------
        | Vendors
        |--------------------------------------------------------------------------
        */

        $todayVendors = WeddingVendor::where(
            'wedding_id',
            $wedding->id
        )
            ->whereDate(
                'service_date',
                $dayOfDate->format('Y-m-d')
            )
            ->orderBy('service_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        $guests = $wedding->guests()->get();


        $totalGuests = $guests->count();


        $attendingGuests = $guests
            ->where(
                'rsvp_status',
                'attending'
            )
            ->count();


        $pendingGuests = $guests
            ->where(
                'rsvp_status',
                'pending'
            )
            ->count();


        $declinedGuests = $guests
            ->where(
                'rsvp_status',
                'declined'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Seating
        |--------------------------------------------------------------------------
        */

        $totalTables = $wedding->tables()->count();


        $assignedGuests = WeddingSeating::where(
            'wedding_id',
            $wedding->id
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Unassigned Guests
        |--------------------------------------------------------------------------
        |
        | Only attending guests are expected to have seating assignments.
        |
        */

        $unassignedGuests = max(
            0,
            $attendingGuests - $assignedGuests
        );


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'wedding.day-of',
            compact(
                'wedding',
                'dayOfDate',
                'todayTimeline',
                'todayTasks',
                'todayVendors',
                'attendingGuests',
                'pendingGuests',
                'declinedGuests',
                'totalGuests',
                'totalTables',
                'assignedGuests',
                'unassignedGuests',
                'completedTasks',
                'totalTasks',
                'completedTodayTasks',
                'totalTodayTasks'
            )
        );
    }
}
