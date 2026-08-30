<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingController extends Controller
{
    /**
     * Display the wedding overview.
     */
    public function index(): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        |
        | These are initialized so the Blade view will never encounter an
        | undefined variable, even when no wedding exists yet.
        |
        */

        $daysUntilWedding = null;

        // Guests
        $totalGuests = 0;
        $attendingGuests = 0;
        $pendingGuests = 0;
        $declinedGuests = 0;
        $plusOneCount = 0;
        $estimatedHeadcount = 0;

        // Checklist
        $totalTasks = 0;
        $completedTasks = 0;
        $pendingTasks = 0;
        $inProgressTasks = 0;
        $overdueTasks = 0;
        $checklistCompletionPercentage = 0;

        // Budget
        $weddingBudget = 0;
        $totalPlannedBudget = 0;
        $totalActualWeddingExpenses = 0;
        $weddingBudgetRemaining = 0;
        $weddingBudgetUsagePercentage = 0;

        // Vendors
        $weddingTotalVendors = 0;
        $weddingVendorContracted = 0;
        $weddingVendorPaid = 0;
        $weddingVendorOutstanding = 0;


        if ($wedding) {

            /*
            |--------------------------------------------------------------------------
            | Wedding Budget
            |--------------------------------------------------------------------------
            */

            $weddingBudget = (float) $wedding->budget;


            /*
            |--------------------------------------------------------------------------
            | Wedding Countdown
            |--------------------------------------------------------------------------
            */

            if ($wedding->wedding_date) {

                $daysUntilWedding = now()
                    ->startOfDay()
                    ->diffInDays(
                        $wedding->wedding_date,
                        false
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Guest Statistics
            |--------------------------------------------------------------------------
            */

            $guests = $wedding->guests()
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
            | Checklist Statistics
            |--------------------------------------------------------------------------
            */

            $tasks = $wedding->tasks()
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

            $inProgressTasks = $tasks
                ->where(
                    'status',
                    'in_progress'
                )
                ->count();

            $overdueTasks = $tasks
                ->filter(
                    fn ($task) =>
                        $task->is_overdue
                )
                ->count();

            $checklistCompletionPercentage =
                $totalTasks > 0
                    ? round(
                        (
                            $completedTasks
                            / $totalTasks
                        ) * 100,
                        1
                    )
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Wedding Budget Statistics
            |--------------------------------------------------------------------------
            */

            $budgets = $wedding->budgets()
                ->with('expenses')
                ->get();

            $totalPlannedBudget = (float) $budgets->sum(
                'planned_amount'
            );

            $totalActualWeddingExpenses = (float) $budgets->sum(
                fn ($budget) =>
                    $budget->actual_amount
            );

            $weddingBudgetRemaining = max(
                0,
                $totalPlannedBudget
                - $totalActualWeddingExpenses
            );

            $weddingBudgetUsagePercentage =
                $totalPlannedBudget > 0
                    ? min(
                        100,
                        round(
                            (
                                $totalActualWeddingExpenses
                                / $totalPlannedBudget
                            ) * 100,
                            1
                        )
                    )
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Vendor Statistics
            |--------------------------------------------------------------------------
            */

            $vendors = $wedding->vendors()
                ->get();

            $weddingTotalVendors =
                $vendors->count();

            $weddingVendorContracted =
                (float) $vendors->sum(
                    'agreed_amount'
                );

            $weddingVendorPaid =
                (float) $vendors->sum(
                    'amount_paid'
                );

            $weddingVendorOutstanding = max(
                0,
                $weddingVendorContracted
                - $weddingVendorPaid
            );
        }


        return view(
            'wedding.index',
            compact(
                'wedding',

                'daysUntilWedding',

                // Guests
                'totalGuests',
                'attendingGuests',
                'pendingGuests',
                'declinedGuests',
                'plusOneCount',
                'estimatedHeadcount',

                // Checklist
                'totalTasks',
                'completedTasks',
                'pendingTasks',
                'inProgressTasks',
                'overdueTasks',
                'checklistCompletionPercentage',

                // Budget
                'weddingBudget',
                'totalPlannedBudget',
                'totalActualWeddingExpenses',
                'weddingBudgetRemaining',
                'weddingBudgetUsagePercentage',

                // Vendors
                'weddingTotalVendors',
                'weddingVendorContracted',
                'weddingVendorPaid',
                'weddingVendorOutstanding'
            )
        );
    }


    /**
     * Store or update the user's wedding.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'wedding_name' => [
                'required',
                'string',
                'max:255',
            ],

            'partner_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'wedding_date' => [
                'nullable',
                'date',
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'budget' => [
                'required',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);


        Wedding::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            array_merge(
                $validated,
                [
                    'user_id' => auth()->id(),
                ]
            )
        );


        return redirect()
            ->route('wedding.index')
            ->with(
                'success',
                'Wedding details saved successfully.'
            );
    }


    /**
     * Delete the user's wedding.
     */
    public function destroy(): RedirectResponse
    {
        Wedding::where(
            'user_id',
            auth()->id()
        )->delete();


        return redirect()
            ->route('wedding.index')
            ->with(
                'success',
                'Wedding details deleted successfully.'
            );
    }
}