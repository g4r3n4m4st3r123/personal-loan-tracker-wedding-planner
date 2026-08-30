<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Services\AvailableFundsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function __construct(
        private AvailableFundsService $availableFundsService
    ) {
    }

    /**
     * Display income management page.
     */
    public function index(): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Income Records
        |--------------------------------------------------------------------------
        */

        $incomes = Income::where('user_id', $userId)
            ->latest('income_date')
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Income Totals
        |--------------------------------------------------------------------------
        */

        $totalIncome = (float) $incomes->sum('amount');

        $sideIncomeTotal = (float) $incomes
            ->where('income_type', 'Side Income')
            ->sum('amount');

        $freelanceIncomeTotal = (float) $incomes
            ->where('income_type', 'Freelance')
            ->sum('amount');

        $otherIncomeTotal = (float) $incomes
            ->where('income_type', 'Other Income')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Amount Used For Loans
        |--------------------------------------------------------------------------
        */

        $incomeUsedForLoans =
            $this->availableFundsService->totalIncomeFundedPayments(
                $userId
            );


        /*
        |--------------------------------------------------------------------------
        | Available Additional Income
        |--------------------------------------------------------------------------
        */

        $availableIncomeForLoans = max(
            0,
            $totalIncome - $incomeUsedForLoans
        );


        /*
        |--------------------------------------------------------------------------
        | Income Usage Percentage
        |--------------------------------------------------------------------------
        */

        $incomeUsagePercentage =
            $totalIncome > 0
                ? min(
                    100,
                    round(
                        ($incomeUsedForLoans / $totalIncome) * 100,
                        1
                    )
                )
                : 0;


        return view('income.index', compact(
            'incomes',
            'totalIncome',
            'sideIncomeTotal',
            'freelanceIncomeTotal',
            'otherIncomeTotal',
            'incomeUsedForLoans',
            'availableIncomeForLoans',
            'incomeUsagePercentage'
        ));
    }


    /**
     * Display the form for creating a new income record.
     */
    public function create(): View
    {
        return view('income.create');
    }


    /**
     * Store a new income record.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'income_type' => [
                'required',
                'in:Side Income,Freelance,Other Income',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'income_date' => [
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


        Income::create($validated);


        return redirect()
            ->route('income.index')
            ->with(
                'success',
                'Income added successfully.'
            );
    }


    /**
     * Delete an income record.
     */
    public function destroy(
        Income $income
    ): RedirectResponse {

        if (
            $income->user_id !== auth()->id()
        ) {
            abort(403);
        }


        $income->delete();


        return redirect()
            ->route('income.index')
            ->with(
                'success',
                'Income deleted successfully.'
            );
    }
}