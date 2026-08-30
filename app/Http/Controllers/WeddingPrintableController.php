<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingPrintableController extends Controller
{
    /**
     * Display printable wedding reports.
     */
    public function index(
        Request $request,
        string $type = 'complete'
    ): View {

        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Allowed Printable Types
        |--------------------------------------------------------------------------
        */

        $allowedTypes = [
            'budget',
            'guests',
            'seating',
            'vendors',
            'checklist',
            'timeline',
            'day-of',
            'complete',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Wedding
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        if (!$wedding) {

            return view(
                'wedding.printables',
                [
                    'wedding' => null,
                    'type' => $type,
                    'budgets' => collect(),
                    'expenses' => collect(),
                    'guests' => collect(),
                    'tables' => collect(),
                    'vendors' => collect(),
                    'tasks' => collect(),
                    'timelineItems' => collect(),
                    'totalWeddingBudget' => 0,
                    'totalPlanned' => 0,
                    'totalActual' => 0,
                    'totalRemaining' => 0,
                    'totalGuests' => 0,
                    'attendingGuests' => 0,
                    'pendingGuests' => 0,
                    'declinedGuests' => 0,
                    'estimatedHeadcount' => 0,
                    'assignedGuests' => 0,
                    'unassignedGuests' => 0,
                    'totalVendors' => 0,
                    'totalAgreedAmount' => 0,
                    'totalAmountPaid' => 0,
                    'totalOutstanding' => 0,
                    'fullyPaidVendors' => 0,
                    'totalTasks' => 0,
                    'completedTasks' => 0,
                    'pendingTasks' => 0,
                    'overdueTasks' => 0,
                    'totalTimelineItems' => 0,
                    'completedTimelineItems' => 0,
                    'dayOfItems' => collect(),
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Budget
        |--------------------------------------------------------------------------
        */

        $budgets = $wedding->budgets()
            ->with('expenses')
            ->orderBy('category')
            ->get();


        $expenses = $wedding->expenses()
            ->with('budget')
            ->orderByDesc('expense_date')
            ->get();


        $totalWeddingBudget = (float) $wedding->budget;

        $totalPlanned = (float) $budgets->sum(
            'planned_amount'
        );

        $totalActual = (float) $budgets->sum(
            'actual_amount'
        );

        $totalRemaining = max(
            0,
            $totalPlanned - $totalActual
        );


        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        $guests = $wedding->guests()
            ->orderBy('name')
            ->get();


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

        $plusOneCount = $guests
            ->where(
                'plus_one',
                true
            )
            ->count();

        $estimatedHeadcount =
            $attendingGuests
            + $plusOneCount;


        /*
        |--------------------------------------------------------------------------
        | Seating
        |--------------------------------------------------------------------------
        */

        $tables = $wedding->tables()
            ->with(
                'seatings.guest'
            )
            ->orderBy('id')
            ->get();


        $assignedGuestIds = $wedding->seatings()
            ->pluck('wedding_guest_id');


        $assignedGuests = $guests
            ->whereIn(
                'id',
                $assignedGuestIds
            )
            ->count();


        $unassignedGuests = max(
            0,
            $attendingGuests - $assignedGuests
        );


        /*
        |--------------------------------------------------------------------------
        | Vendors
        |--------------------------------------------------------------------------
        */

        $vendors = $wedding->vendors()
            ->orderBy('vendor_name')
            ->get();


        $totalVendors = $vendors->count();

        $totalAgreedAmount = (float) $vendors->sum(
            'agreed_amount'
        );

        $totalAmountPaid = (float) $vendors->sum(
            'amount_paid'
        );

        $totalOutstanding = max(
            0,
            $totalAgreedAmount - $totalAmountPaid
        );

        $fullyPaidVendors = $vendors
            ->filter(
                fn ($vendor) =>
                    (float) $vendor->balance <= 0
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Checklist
        |--------------------------------------------------------------------------
        */

        $tasks = $wedding->tasks()
            ->orderByRaw(
                "CASE
                    WHEN status = 'completed' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    ELSE 3
                END"
            )
            ->orderBy('due_date')
            ->get();


        $totalTasks = $tasks->count();

        $completedTasks = $tasks
            ->where(
                'status',
                'completed'
            )
            ->count();

        $pendingTasks = $tasks
            ->where(
                'status',
                'pending'
            )
            ->count();

        $overdueTasks = $tasks
            ->filter(
                fn ($task) =>
                    $task->is_overdue
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

        $timelineItems = $wedding->timelineItems()
            ->orderBy('event_date')
            ->orderByRaw(
                'CASE WHEN start_time IS NULL THEN 1 ELSE 0 END'
            )
            ->orderBy('start_time')
            ->orderBy('title')
            ->get();


        $totalTimelineItems = $timelineItems->count();

        $completedTimelineItems = $timelineItems
            ->where(
                'status',
                'completed'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Day-of Schedule
        |--------------------------------------------------------------------------
        */

        $dayOfItems = $wedding->timelineItems()
            ->whereDate(
                'event_date',
                now()->format('Y-m-d')
            )
            ->orderByRaw(
                'CASE WHEN start_time IS NULL THEN 1 ELSE 0 END'
            )
            ->orderBy('start_time')
            ->orderBy('title')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Printable View
        |--------------------------------------------------------------------------
        */

        return view(
            'wedding.printables',
            compact(
                'wedding',
                'type',
                'budgets',
                'expenses',
                'guests',
                'tables',
                'vendors',
                'tasks',
                'timelineItems',
                'totalWeddingBudget',
                'totalPlanned',
                'totalActual',
                'totalRemaining',
                'totalGuests',
                'attendingGuests',
                'pendingGuests',
                'declinedGuests',
                'estimatedHeadcount',
                'assignedGuests',
                'unassignedGuests',
                'totalVendors',
                'totalAgreedAmount',
                'totalAmountPaid',
                'totalOutstanding',
                'fullyPaidVendors',
                'totalTasks',
                'completedTasks',
                'pendingTasks',
                'overdueTasks',
                'totalTimelineItems',
                'completedTimelineItems',
                'dayOfItems'
            )
        );
    }
}