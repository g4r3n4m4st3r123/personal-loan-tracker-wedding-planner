<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingBudgetController;
use App\Http\Controllers\WeddingExpenseController;
use App\Http\Controllers\WeddingTaskController;
use App\Http\Controllers\WeddingGuestController;
use App\Http\Controllers\WeddingVendorController;
use App\Http\Controllers\WeddingTimelineController;
use App\Http\Controllers\WeddingCalendarController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WeddingSeatingController;
use App\Http\Controllers\WeddingDocumentController;
use App\Http\Controllers\WeddingDayOfController;
use App\Http\Controllers\WeddingPrintableController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DebtFreePlannerController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Loan Payments
    |--------------------------------------------------------------------------
    */

    Route::get('/payments', [LoanPaymentController::class, 'all'])
        ->name('payments.index');

    Route::resource('loans', LoanController::class);

    Route::get('/loans/{loan}/payments', [LoanPaymentController::class, 'index'])
        ->name('loan-payments.index');

    Route::get('/loans/{loan}/payments/create', [LoanPaymentController::class, 'create'])
        ->name('loan-payments.create');

    Route::post('/loans/{loan}/payments', [LoanPaymentController::class, 'store'])
        ->name('loan-payments.store');

    Route::delete('/loan-payments/{payment}', [LoanPaymentController::class, 'destroy'])
        ->name('loan-payments.destroy');


    /*
    |--------------------------------------------------------------------------
    | Salary
    |--------------------------------------------------------------------------
    */

    Route::get('/salary', [SalaryController::class, 'index'])
        ->name('salary.index');

    Route::post('/salary', [SalaryController::class, 'store'])
        ->name('salary.store');

    Route::delete('/salary/{salary}', [SalaryController::class, 'destroy'])
        ->name('salary.destroy');

    Route::post('/salary/period', [SalaryController::class, 'storePeriod'])
        ->name('salary-periods.store');

    Route::delete('/salary/period/{salaryPeriod}', [SalaryController::class, 'destroyPeriod'])
        ->name('salary-periods.destroy');

    Route::get('/salary/create', [SalaryController::class, 'create'])
        ->name('salary.create');


    /*
    |--------------------------------------------------------------------------
    | Income
    |--------------------------------------------------------------------------
    */

    Route::get('/income', [IncomeController::class, 'index'])
        ->name('income.index');

    Route::get('/income/create', [IncomeController::class, 'create'])
        ->name('income.create');

    Route::post('/income', [IncomeController::class, 'store'])
        ->name('income.store');

    Route::delete('/income/{income}', [IncomeController::class, 'destroy'])
        ->name('income.destroy');


    /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */

    Route::get('/expenses', [ExpenseController::class, 'index'])
        ->name('expenses.index');

    Route::get('/expenses/create', [ExpenseController::class, 'create'])
        ->name('expenses.create');

    Route::post('/expenses', [ExpenseController::class, 'store'])
        ->name('expenses.store');

    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])
        ->name('expenses.destroy');


    /*
    |--------------------------------------------------------------------------
    | Wedding Overview
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | This route does NOT use the "wedding" middleware because a user
    | who has no wedding needs to access this page to create one.
    |
    */

    Route::get('/wedding', [WeddingController::class, 'index'])
        ->name('wedding.index');

    Route::post('/wedding', [WeddingController::class, 'store'])
        ->name('wedding.store');

    Route::delete('/wedding', [WeddingController::class, 'destroy'])
        ->name('wedding.destroy');


    /*
    |--------------------------------------------------------------------------
    | Wedding Modules
    |--------------------------------------------------------------------------
    |
    | These routes require the authenticated user to have their own
    | wedding before they can access the module.
    |
    */

    Route::middleware('wedding')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Wedding Checklist
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/checklist', [WeddingTaskController::class, 'index'])
            ->name('wedding.checklist');

        Route::post('/wedding/checklist', [WeddingTaskController::class, 'store'])
            ->name('wedding.checklist.store');

        Route::patch('/wedding/checklist/{task}', [WeddingTaskController::class, 'update'])
            ->name('wedding.checklist.update');

        Route::delete('/wedding/checklist/{task}', [WeddingTaskController::class, 'destroy'])
            ->name('wedding.checklist.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Budget
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/budget', [WeddingBudgetController::class, 'index'])
            ->name('wedding.budget');

        Route::post('/wedding/budget', [WeddingBudgetController::class, 'store'])
            ->name('wedding.budget.store');

        Route::patch('/wedding/budget/{budget}', [WeddingBudgetController::class, 'update'])
            ->name('wedding.budget.update');

        Route::delete('/wedding/budget/{budget}', [WeddingBudgetController::class, 'destroy'])
            ->name('wedding.budget.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Expenses
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/expenses', [WeddingExpenseController::class, 'index'])
            ->name('wedding.expenses');

        Route::post('/wedding/expenses', [WeddingExpenseController::class, 'store'])
            ->name('wedding.expenses.store');

        Route::patch('/wedding/expenses/{expense}', [WeddingExpenseController::class, 'update'])
            ->name('wedding.expenses.update');

        Route::delete('/wedding/expenses/{expense}', [WeddingExpenseController::class, 'destroy'])
            ->name('wedding.expenses.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Guests
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/guests', [WeddingGuestController::class, 'index'])
            ->name('wedding.guests');

        Route::post('/wedding/guests', [WeddingGuestController::class, 'store'])
            ->name('wedding.guests.store');

        Route::patch('/wedding/guests/{guest}', [WeddingGuestController::class, 'update'])
            ->name('wedding.guests.update');

        Route::delete('/wedding/guests/{guest}', [WeddingGuestController::class, 'destroy'])
            ->name('wedding.guests.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Vendors
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/vendors', [WeddingVendorController::class, 'index'])
            ->name('wedding.vendors');

        Route::post('/wedding/vendors', [WeddingVendorController::class, 'store'])
            ->name('wedding.vendors.store');

        Route::patch('/wedding/vendors/{vendor}', [WeddingVendorController::class, 'update'])
            ->name('wedding.vendors.update');

        Route::delete('/wedding/vendors/{vendor}', [WeddingVendorController::class, 'destroy'])
            ->name('wedding.vendors.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Timeline
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/timeline', [WeddingTimelineController::class, 'index'])
            ->name('wedding.timeline');

        Route::post('/wedding/timeline', [WeddingTimelineController::class, 'store'])
            ->name('wedding.timeline.store');

        Route::patch('/wedding/timeline/{timelineItem}', [WeddingTimelineController::class, 'update'])
            ->name('wedding.timeline.update');

        Route::delete('/wedding/timeline/{timelineItem}', [WeddingTimelineController::class, 'destroy'])
            ->name('wedding.timeline.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Calendar
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/calendar', [WeddingCalendarController::class, 'index'])
            ->name('wedding.calendar');


        /*
        |--------------------------------------------------------------------------
        | Wedding Seating
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/seating', [WeddingSeatingController::class, 'index'])
            ->name('wedding.seating');

        Route::post('/wedding/seating/tables', [WeddingSeatingController::class, 'storeTable'])
            ->name('wedding.seating.tables.store');

        Route::patch('/wedding/seating/tables/{table}', [WeddingSeatingController::class, 'updateTable'])
            ->name('wedding.seating.tables.update');

        Route::delete('/wedding/seating/tables/{table}', [WeddingSeatingController::class, 'destroyTable'])
            ->name('wedding.seating.tables.destroy');

        Route::post('/wedding/seating/assign', [WeddingSeatingController::class, 'assignGuest'])
            ->name('wedding.seating.assign');

        Route::delete('/wedding/seating/{seating}', [WeddingSeatingController::class, 'removeGuest'])
            ->name('wedding.seating.remove');


        /*
        |--------------------------------------------------------------------------
        | Wedding Documents
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/documents', [WeddingDocumentController::class, 'index'])
            ->name('wedding.documents');

        Route::post('/wedding/documents', [WeddingDocumentController::class, 'store'])
            ->name('wedding.documents.store');

        Route::get('/wedding/documents/{document}/download', [WeddingDocumentController::class, 'download'])
            ->name('wedding.documents.download');

        Route::delete('/wedding/documents/{document}', [WeddingDocumentController::class, 'destroy'])
            ->name('wedding.documents.destroy');


        /*
        |--------------------------------------------------------------------------
        | Wedding Day-of
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/day-of', [WeddingDayOfController::class, 'index'])
            ->name('wedding.day-of');


        /*
        |--------------------------------------------------------------------------
        | Wedding Printables
        |--------------------------------------------------------------------------
        */

        Route::get('/wedding/printables/{type?}', [WeddingPrintableController::class, 'index'])
            ->name('wedding.printables');

    });


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/reports/finance', [ReportController::class, 'finance'])
        ->name('reports.finance');

    Route::get('/reports/wedding', [ReportController::class, 'wedding'])
        ->name('reports.wedding');


    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    */

    Route::patch('/settings/preferences', [UserSettingController::class, 'update'])
        ->name('settings.preferences.update');


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    Route::get('/search', [SearchController::class, 'index'])
        ->name('search.index');

    Route::get('/search/live', [SearchController::class, 'live'])
        ->name('search.live');


    /*
    |--------------------------------------------------------------------------
    | Debt-Free Planner
    |--------------------------------------------------------------------------
    */

    Route::get('/debt-free-planner', [
        DebtFreePlannerController::class,
        'index',
    ])->name('debt-free.index');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';