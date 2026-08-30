<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingBudget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingBudgetController extends Controller
{
    /**
     * Display the wedding budget page.
     */
    public function index(): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $budgets = $wedding->budgets()
            ->with('expenses')
            ->orderBy('category')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Budget Totals
        |--------------------------------------------------------------------------
        */

        $totalPlanned = (float) $budgets->sum(
            'planned_amount'
        );

        $totalActual = (float) $budgets->sum(
            fn ($budget) => $budget->actual_amount
        );

        $totalRemaining = max(
            0,
            $totalPlanned - $totalActual
        );

        $budgetUsagePercentage = $totalPlanned > 0
            ? min(
                100,
                round(
                    ($totalActual / $totalPlanned) * 100,
                    1
                )
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Overall Wedding Budget
        |--------------------------------------------------------------------------
        */

        $overallWeddingBudget = (float) $wedding->budget;

        $plannedVsWeddingBudgetRemaining = max(
            0,
            $overallWeddingBudget - $totalPlanned
        );

        $actualVsWeddingBudgetRemaining = max(
            0,
            $overallWeddingBudget - $totalActual
        );


        return view(
            'wedding.budget.index',
            compact(
                'wedding',
                'budgets',
                'totalPlanned',
                'totalActual',
                'totalRemaining',
                'budgetUsagePercentage',
                'overallWeddingBudget',
                'plannedVsWeddingBudgetRemaining',
                'actualVsWeddingBudgetRemaining'
            )
        );
    }


    /**
     * Store a new budget category.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Category
        |--------------------------------------------------------------------------
        */

        $existingCategory = $wedding->budgets()
            ->whereRaw(
                'LOWER(category) = ?',
                [
                    strtolower(
                        trim($validated['category'])
                    )
                ]
            )
            ->exists();


        if ($existingCategory) {

            return back()
                ->withInput()
                ->withErrors([
                    'category' =>
                        'This budget category already exists.',
                ]);
        }


        $wedding->budgets()->create([
            'category' => trim(
                $validated['category']
            ),

            'planned_amount' =>
                $validated['planned_amount'],

            'actual_amount' => 0,

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        return redirect()
            ->route('wedding.budget')
            ->with(
                'success',
                'Wedding budget category added successfully.'
            );
    }


    /**
     * Update a budget category.
     */
    public function update(
        Request $request,
        WeddingBudget $budget
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Ownership
        |--------------------------------------------------------------------------
        */

        if (
            $budget->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $duplicateCategory = $budget->wedding
            ->budgets()
            ->whereRaw(
                'LOWER(category) = ?',
                [
                    strtolower(
                        trim($validated['category'])
                    )
                ]
            )
            ->where(
                'id',
                '!=',
                $budget->id
            )
            ->exists();


        if ($duplicateCategory) {

            return back()
                ->withInput()
                ->withErrors([
                    'category' =>
                        'This budget category already exists.',
                ]);
        }


        $budget->update([
            'category' => trim(
                $validated['category']
            ),

            'planned_amount' =>
                $validated['planned_amount'],

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        return redirect()
            ->route('wedding.budget')
            ->with(
                'success',
                'Wedding budget category updated successfully.'
            );
    }


    /**
     * Delete a budget category.
     */
    public function destroy(
        WeddingBudget $budget
    ): RedirectResponse {

        if (
            $budget->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Accidental Deletion
        |--------------------------------------------------------------------------
        |
        | If expenses are connected to this category, don't delete
        | the category until those expenses are reassigned or removed.
        |
        */

        if ($budget->expenses()->exists()) {

            return back()
                ->withErrors([
                    'budget' =>
                        'This budget category cannot be deleted because it has wedding expenses connected to it.',
                ]);
        }


        $budget->delete();


        return redirect()
            ->route('wedding.budget')
            ->with(
                'success',
                'Wedding budget category deleted successfully.'
            );
    }
}