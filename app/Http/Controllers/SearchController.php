<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\Wedding;
use App\Models\WeddingBudget;
use App\Models\WeddingExpense;
use App\Models\WeddingGuest;
use App\Models\WeddingTask;
use App\Models\WeddingVendor;
use App\Models\WeddingTimelineItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Global search page.
     */
    public function index(Request $request): View
    {
        $query = trim(
            (string) $request->input('q', '')
        );

        $results = $this->search(
            auth()->id(),
            $query
        );

        return view(
            'search.index',
            compact(
                'query',
                'results'
            )
        );
    }

    /**
     * AJAX live search.
     */
    public function live(Request $request): JsonResponse
    {
        $query = trim(
            (string) $request->input('q', '')
        );

        $results = $this->search(
            auth()->id(),
            $query
        );

        return response()->json([
            'query' => $query,
            'count' => $results->count(),
            'results' => $results->values(),
        ]);
    }

    /**
     * Perform global search.
     */
    private function search(
        int $userId,
        string $query
    ) {
        $results = collect();

        if ($query === '') {
            return $results;
        }

        $search = '%' . $query . '%';


        /*
        |--------------------------------------------------------------------------
        | LOANS
        |--------------------------------------------------------------------------
        */

        $loans = Loan::where(
            'user_id',
            $userId
        )
            ->where(function ($builder) use ($search) {

                $builder
                    ->where('loan_name', 'like', $search)
                    ->orWhere('lender', 'like', $search)
                    ->orWhere('notes', 'like', $search);
            })
            ->latest()
            ->get();

        foreach ($loans as $loan) {

            $results->push([
                'type' => 'Loan',
                'category' => 'Finance',
                'icon' => '💳',
                'title' => $loan->loan_name,
                'description' => $loan->lender
                    ?: 'Loan account',
                'url' => route('loans.show', $loan),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL INCOME
        |--------------------------------------------------------------------------
        */

        $incomes = Income::where(
            'user_id',
            $userId
        )
            ->where(function ($builder) use ($search) {

                $builder
                    ->where('income_type', 'like', $search)
                    ->orWhere('description', 'like', $search);
            })
            ->latest('income_date')
            ->get();

        foreach ($incomes as $income) {

            $results->push([
                'type' => 'Income',
                'category' => 'Finance',
                'icon' => '💰',
                'title' => $income->income_type,
                'description' => $income->description
                    ?: 'Additional income',
                'url' => route('income.index'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PERSONAL EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::where(
            'user_id',
            $userId
        )
            ->where(function ($builder) use ($search) {

                $builder
                    ->where('category', 'like', $search)
                    ->orWhere('description', 'like', $search);
            })
            ->latest('expense_date')
            ->get();

        foreach ($expenses as $expense) {

            $results->push([
                'type' => 'Expense',
                'category' => 'Finance',
                'icon' => '💸',
                'title' => $expense->category,
                'description' => $expense->description
                    ?: 'Personal expense',
                'url' => route('expenses.index'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | WEDDING
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::where(
            'user_id',
            $userId
        )
            ->where(function ($builder) use ($search) {

                $builder
                    ->where('wedding_name', 'like', $search)
                    ->orWhere('partner_name', 'like', $search)
                    ->orWhere('venue', 'like', $search)
                    ->orWhere('notes', 'like', $search);
            })
            ->first();

        if ($wedding) {

            $results->push([
                'type' => 'Wedding',
                'category' => 'Wedding',
                'icon' => '💍',
                'title' => $wedding->wedding_name,
                'description' => $wedding->venue
                    ?: 'Wedding overview',
                'url' => route('wedding.index'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | USER'S WEDDINGS
        |--------------------------------------------------------------------------
        */

        $weddingIds = Wedding::where(
            'user_id',
            $userId
        )
            ->pluck('id');


        if ($weddingIds->isNotEmpty()) {


            /*
            |--------------------------------------------------------------------------
            | WEDDING BUDGET
            |--------------------------------------------------------------------------
            */

            $budgets = WeddingBudget::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('category', 'like', $search)
                        ->orWhere('notes', 'like', $search);
                })
                ->latest()
                ->get();

            foreach ($budgets as $budget) {

                $results->push([
                    'type' => 'Wedding Budget',
                    'category' => 'Wedding',
                    'icon' => '💰',
                    'title' => $budget->category,
                    'description' => $budget->notes
                        ?: 'Wedding budget category',
                    'url' => route('wedding.budget'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WEDDING EXPENSES
            |--------------------------------------------------------------------------
            */

            $weddingExpenses = WeddingExpense::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('expense_name', 'like', $search)
                        ->orWhere('notes', 'like', $search);
                })
                ->latest('expense_date')
                ->get();

            foreach ($weddingExpenses as $expense) {

                $results->push([
                    'type' => 'Wedding Expense',
                    'category' => 'Wedding',
                    'icon' => '💸',
                    'title' => $expense->expense_name,
                    'description' => $expense->notes
                        ?: 'Wedding expense',
                    'url' => route('wedding.expenses'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WEDDING TASKS
            |--------------------------------------------------------------------------
            */

            $tasks = WeddingTask::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('task_name', 'like', $search)
                        ->orWhere('description', 'like', $search);
                })
                ->latest()
                ->get();

            foreach ($tasks as $task) {

                $results->push([
                    'type' => 'Wedding Task',
                    'category' => 'Wedding',
                    'icon' => '📋',
                    'title' => $task->task_name,
                    'description' => $task->description
                        ?: ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $task->status
                            )
                        ),
                    'url' => route('wedding.checklist'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WEDDING GUESTS
            |--------------------------------------------------------------------------
            */

            $guests = WeddingGuest::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('name', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('guest_type', 'like', $search)
                        ->orWhere('meal_preference', 'like', $search)
                        ->orWhere('notes', 'like', $search);
                })
                ->latest()
                ->get();

            foreach ($guests as $guest) {

                $results->push([
                    'type' => 'Wedding Guest',
                    'category' => 'Wedding',
                    'icon' => '👥',
                    'title' => $guest->name,
                    'description' => ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $guest->guest_type
                        )
                    ),
                    'url' => route('wedding.guests'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WEDDING VENDORS
            |--------------------------------------------------------------------------
            */

            $vendors = WeddingVendor::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('vendor_name', 'like', $search)
                        ->orWhere('service_type', 'like', $search)
                        ->orWhere('contact_person', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('notes', 'like', $search);
                })
                ->latest()
                ->get();

            foreach ($vendors as $vendor) {

                $results->push([
                    'type' => 'Wedding Vendor',
                    'category' => 'Wedding',
                    'icon' => '🏪',
                    'title' => $vendor->vendor_name,
                    'description' => $vendor->service_type,
                    'url' => route('wedding.vendors'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WEDDING TIMELINE
            |--------------------------------------------------------------------------
            */

            $timelineItems = WeddingTimelineItem::whereIn(
                'wedding_id',
                $weddingIds
            )
                ->where(function ($builder) use ($search) {

                    $builder
                        ->where('title', 'like', $search)
                        ->orWhere('location', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhere('category', 'like', $search);
                })
                ->orderBy('event_date')
                ->get();

            foreach ($timelineItems as $item) {

                $results->push([
                    'type' => 'Timeline',
                    'category' => 'Wedding',
                    'icon' => '📅',
                    'title' => $item->title,
                    'description' => $item->location
                        ?: ucfirst($item->category),
                    'url' => route('wedding.timeline'),
                ]);
            }
        }


        return $results;
    }
}