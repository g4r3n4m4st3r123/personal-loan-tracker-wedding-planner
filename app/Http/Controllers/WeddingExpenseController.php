<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingBudget;
use App\Models\WeddingExpense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingExpenseController extends Controller
{
    /**
     * Display wedding expenses.
     */
    public function index(): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $expenses = $wedding->expenses()
            ->with('budget')
            ->latest('expense_date')
            ->latest()
            ->get();

        $budgets = $wedding->budgets()
            ->orderBy('category')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Expense Totals
        |--------------------------------------------------------------------------
        */

        $totalExpenses = (float) $expenses->sum('amount');

        $paidExpenses = (float) $expenses
            ->where('payment_status', 'paid')
            ->sum('amount');

        $pendingExpenses = (float) $expenses
            ->where('payment_status', 'pending')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Current Month Expenses
        |--------------------------------------------------------------------------
        */

        $monthStart = now()
            ->startOfMonth()
            ->toDateString();

        $monthEnd = now()
            ->endOfMonth()
            ->toDateString();

        $monthlyExpenses = (float) $wedding->expenses()
            ->whereBetween(
                'expense_date',
                [$monthStart, $monthEnd]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Expense Category Breakdown
        |--------------------------------------------------------------------------
        */

        $categoryTotals = $expenses
            ->groupBy(function ($expense) {
                return $expense->budget?->category
                    ?? 'Uncategorized';
            })
            ->map(function ($items) {
                return (float) $items->sum('amount');
            })
            ->sortDesc();


        return view(
            'wedding.expenses.index',
            compact(
                'wedding',
                'expenses',
                'budgets',
                'totalExpenses',
                'paidExpenses',
                'pendingExpenses',
                'monthlyExpenses',
                'categoryTotals'
            )
        );
    }


    /**
     * Store a new wedding expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        $validated = $request->validate([
            'wedding_budget_id' => [
                'nullable',
                'integer',
                'exists:wedding_budgets,id',
            ],

            'expense_name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Budget Belongs To User's Wedding
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['wedding_budget_id'])) {

            $budgetBelongsToWedding = $wedding->budgets()
                ->where(
                    'id',
                    $validated['wedding_budget_id']
                )
                ->exists();


            if (!$budgetBelongsToWedding) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'wedding_budget_id' =>
                            'The selected budget category is invalid.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Wedding Expense
        |--------------------------------------------------------------------------
        */

        $wedding->expenses()->create([
            'wedding_budget_id' =>
                $validated['wedding_budget_id'] ?? null,

            'expense_name' =>
                $validated['expense_name'],

            'amount' =>
                $validated['amount'],

            'expense_date' =>
                $validated['expense_date'],

            'payment_status' =>
                $validated['payment_status'],

            'payment_method' =>
                $validated['payment_method'] ?? null,

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        return redirect()
            ->route('wedding.expenses')
            ->with(
                'success',
                'Wedding expense added successfully.'
            );
    }


    /**
     * Update a wedding expense.
     */
    public function update(
        Request $request,
        WeddingExpense $expense
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Ownership
        |--------------------------------------------------------------------------
        */

        if (
            $expense->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        $validated = $request->validate([
            'wedding_budget_id' => [
                'nullable',
                'integer',
                'exists:wedding_budgets,id',
            ],

            'expense_name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Budget Belongs To Same Wedding
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['wedding_budget_id'])) {

            $budgetBelongsToWedding = $expense->wedding
                ->budgets()
                ->where(
                    'id',
                    $validated['wedding_budget_id']
                )
                ->exists();


            if (!$budgetBelongsToWedding) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'wedding_budget_id' =>
                            'The selected budget category is invalid.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Expense
        |--------------------------------------------------------------------------
        */

        $expense->update([
            'wedding_budget_id' =>
                $validated['wedding_budget_id'] ?? null,

            'expense_name' =>
                $validated['expense_name'],

            'amount' =>
                $validated['amount'],

            'expense_date' =>
                $validated['expense_date'],

            'payment_status' =>
                $validated['payment_status'],

            'payment_method' =>
                $validated['payment_method'] ?? null,

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        return redirect()
            ->route('wedding.expenses')
            ->with(
                'success',
                'Wedding expense updated successfully.'
            );
    }


    /**
     * Delete a wedding expense.
     */
    public function destroy(
        WeddingExpense $expense
    ): RedirectResponse {

        if (
            $expense->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        $expense->delete();


        return redirect()
            ->route('wedding.expenses')
            ->with(
                'success',
                'Wedding expense deleted successfully.'
            );
    }
}