<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display expense management page.
     */
    public function index(): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Expense Records
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::where('user_id', $userId)
            ->latest('expense_date')
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Current Month
        |--------------------------------------------------------------------------
        */

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();


        /*
        |--------------------------------------------------------------------------
        | Total Expenses
        |--------------------------------------------------------------------------
        */

        $totalExpenses = (float) $expenses->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Monthly Expenses
        |--------------------------------------------------------------------------
        */

        $monthlyExpenses = (float) Expense::where('user_id', $userId)
            ->whereBetween(
                'expense_date',
                [$monthStart, $monthEnd]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Category Totals
        |--------------------------------------------------------------------------
        */

        $categoryTotals = $expenses
            ->groupBy('category')
            ->map(function ($items) {
                return (float) $items->sum('amount');
            })
            ->sortDesc();


        /*
        |--------------------------------------------------------------------------
        | Highest Expense Category
        |--------------------------------------------------------------------------
        */

        $highestCategory = $categoryTotals->keys()->first();

        $highestCategoryAmount = $highestCategory
            ? (float) $categoryTotals->first()
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Monthly Category Totals
        |--------------------------------------------------------------------------
        */

        $monthlyCategoryTotals = Expense::where(
            'user_id',
            $userId
        )
            ->whereBetween(
                'expense_date',
                [$monthStart, $monthEnd]
            )
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return (float) $items->sum('amount');
            })
            ->sortDesc();


        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'monthlyExpenses',
            'categoryTotals',
            'highestCategory',
            'highestCategoryAmount',
            'monthlyCategoryTotals'
        ));
    }

    /**
     * Display add expense page.
     */
    public function create(): View
    {
        return view('expenses.create');
    }


    /**
     * Store a new expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
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

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $validated['user_id'] = auth()->id();


        Expense::create($validated);


        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense added successfully.'
            );
    }


    /**
     * Delete an expense.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }


        $expense->delete();


        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense deleted successfully.'
            );
    }
}