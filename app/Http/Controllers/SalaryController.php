<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryPeriod;
use App\Models\LoanPayment;
use App\Services\AvailableFundsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalaryController extends Controller
{
    public function __construct(
        private AvailableFundsService $availableFundsService
    ) {
    }
    /**
     * Display salary management page.
     */
    public function index(): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Salary History
        |--------------------------------------------------------------------------
        */

        $salaries = Salary::where('user_id', $userId)
            ->latest('effective_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Salary Periods
        |--------------------------------------------------------------------------
        */

        $salaryPeriods = SalaryPeriod::where('user_id', $userId)
            ->latest('salary_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Current Salary Period
        |--------------------------------------------------------------------------
        */

        $currentSalary = SalaryPeriod::where('user_id', $userId)
            ->whereDate('salary_date', '<=', now()->toDateString())
            ->latest('salary_date')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Current Period Deductions
        |--------------------------------------------------------------------------
        */

        $currentPeriodDeductions = 0;

        $currentAvailableSalary = 0;

        if ($currentSalary) {

            $currentPeriodDeductions = LoanPayment::where('user_id', $userId)
                ->where('payment_source', 'Salary')
                ->whereDate(
                    'payment_date',
                    '>=',
                    $currentSalary->period_start
                )
                ->whereDate(
                    'payment_date',
                    '<=',
                    $currentSalary->period_end
                )
                ->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | Current Available Salary
            |--------------------------------------------------------------------------
            |
            | Current Salary
            | + Carry-Over
            | - Current Period Deductions
            |
            */

            $currentAvailableSalary = max(
                0,
                (float) $currentSalary->salary_amount
                + (float) $currentSalary->carry_over
                - (float) $currentPeriodDeductions
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Salary Deductions
        |--------------------------------------------------------------------------
        */

        $salaryDeductions = LoanPayment::where('user_id', $userId)
            ->where('payment_source', 'Salary')
            ->with('loan')
            ->latest('payment_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total Salary Received
        |--------------------------------------------------------------------------
        */

        $totalSalaryReceived = SalaryPeriod::where('user_id', $userId)
            ->whereDate('salary_date', '<=', now()->toDateString())
            ->sum('salary_amount');


        /*
        |--------------------------------------------------------------------------
        | Total Loan Deductions
        |--------------------------------------------------------------------------
        */

        $totalLoanDeductions = LoanPayment::where('user_id', $userId)
            ->where('payment_source', 'Salary')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Historical Remaining Salary
        |--------------------------------------------------------------------------
        */

        $totalRemainingSalary = max(
            0,
            (float) $totalSalaryReceived
            - (float) $totalLoanDeductions
        );

        $totalAvailableFunds =
            $this->availableFundsService->availableFunds(
                $userId
            );


        /*
        |--------------------------------------------------------------------------
        | Deduction Percentage
        |--------------------------------------------------------------------------
        */

        $deductionPercentage = $totalSalaryReceived > 0
            ? (
                (
                    (float) $totalLoanDeductions
                    / (float) $totalSalaryReceived
                ) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Salary Health Status
        |--------------------------------------------------------------------------
        */

        if ($deductionPercentage <= 30) {

            $salaryStatus = 'healthy';

        } elseif ($deductionPercentage <= 50) {

            $salaryStatus = 'moderate';

        } else {

            $salaryStatus = 'critical';
        }


        return view('salary.index', compact(
            'salaries',
            'currentSalary',
            'salaryPeriods',
            'salaryDeductions',
            'totalSalaryReceived',
            'totalLoanDeductions',
            'totalRemainingSalary',
            'deductionPercentage',
            'salaryStatus',
            'currentPeriodDeductions',
            'currentAvailableSalary',
            'totalAvailableFunds'
        ));
    }


    /**
     * Display add salary page.
     */
    public function create(): View
    {
        return view('salary.create');
    }


    /**
     * Store a new salary record and automatically create
     * its corresponding salary period.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'effective_date' => [
                'required',
                'date',
            ],

            'salary_type' => [
                'required',
                'in:monthly,semi-monthly,weekly,bi-weekly',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);


        $userId = auth()->id();


        DB::transaction(function () use ($validated, $userId) {

            /*
            |--------------------------------------------------------------------------
            | Create Salary History
            |--------------------------------------------------------------------------
            */

            Salary::create([
                'user_id' => $userId,
                'amount' => $validated['amount'],
                'effective_date' => $validated['effective_date'],
                'salary_type' => $validated['salary_type'],
                'notes' => $validated['notes'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Salary Date
            |--------------------------------------------------------------------------
            */

            $salaryDate = \Carbon\Carbon::parse(
                $validated['effective_date']
            )->startOfDay();


            /*
            |--------------------------------------------------------------------------
            | Salary Type
            |--------------------------------------------------------------------------
            */

            $salaryType = $validated['salary_type'];


            /*
            |--------------------------------------------------------------------------
            | Determine Salary Period
            |--------------------------------------------------------------------------
            */

            switch ($salaryType) {

                case 'monthly':

                    $periodStart = $salaryDate->copy()->startOfMonth();

                    $periodEnd = $salaryDate->copy()->endOfMonth();

                    break;


                case 'semi-monthly':

                    if ($salaryDate->day <= 15) {

                        $periodStart = $salaryDate
                            ->copy()
                            ->startOfMonth();

                        $periodEnd = $salaryDate
                            ->copy()
                            ->startOfMonth()
                            ->addDays(14);

                    } else {

                        $periodStart = $salaryDate
                            ->copy()
                            ->startOfMonth()
                            ->addDays(15);

                        $periodEnd = $salaryDate
                            ->copy()
                            ->endOfMonth();
                    }

                    break;


                case 'weekly':

                    $periodEnd = $salaryDate->copy();

                    $periodStart = $salaryDate
                        ->copy()
                        ->subDays(6);

                    break;


                case 'bi-weekly':

                    $periodEnd = $salaryDate->copy();

                    $periodStart = $salaryDate
                        ->copy()
                        ->subDays(13);

                    break;


                default:

                    $periodStart = $salaryDate->copy();

                    $periodEnd = $salaryDate->copy();

                    break;
            }


            /*
            |--------------------------------------------------------------------------
            | Find Previous Salary Period
            |--------------------------------------------------------------------------
            */

            $previousSalary = SalaryPeriod::where('user_id', $userId)
                ->whereDate(
                    'salary_date',
                    '<',
                    $salaryDate->toDateString()
                )
                ->latest('salary_date')
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Calculate Carry-Over
            |--------------------------------------------------------------------------
            */

            $carryOver = 0;


            if ($previousSalary) {

                $previousDeductions = LoanPayment::where(
                    'user_id',
                    $userId
                )
                    ->where(
                        'payment_method',
                        'Salary'
                    )
                    ->whereDate(
                        'payment_date',
                        '>=',
                        $previousSalary->period_start
                    )
                    ->whereDate(
                        'payment_date',
                        '<=',
                        $previousSalary->period_end
                    )
                    ->sum('amount');


                $carryOver = max(
                    0,
                    (float) $previousSalary->salary_amount
                    + (float) $previousSalary->carry_over
                    - (float) $previousDeductions
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Status
            |--------------------------------------------------------------------------
            */

            $today = now()->startOfDay();

            if ($salaryDate->isAfter($today)) {

                $status = 'upcoming';

            } elseif ($salaryDate->isSameDay($today)) {

                $status = 'current';

            } else {

                $status = 'completed';
            }


            /*
            |--------------------------------------------------------------------------
            | Create Salary Period
            |--------------------------------------------------------------------------
            */

            SalaryPeriod::create([
                'user_id' => $userId,
                'salary_amount' => $validated['amount'],
                'carry_over' => $carryOver,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'salary_date' => $salaryDate->toDateString(),
                'salary_type' => $salaryType,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
            ]);
        });


        return redirect()
            ->route('salary.index')
            ->with(
                'success',
                'Salary added successfully. Previous remaining salary was carried over automatically.'
            );
    }


    /**
     * Store a salary period manually.
     */
    public function storePeriod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'salary_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'period_start' => [
                'required',
                'date',
            ],

            'period_end' => [
                'required',
                'date',
                'after_or_equal:period_start',
            ],

            'salary_date' => [
                'required',
                'date',
            ],

            'salary_type' => [
                'required',
                'in:monthly,semi-monthly,weekly,bi-weekly',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);


        $userId = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Find Previous Salary Period
        |--------------------------------------------------------------------------
        */

        $previousSalary = SalaryPeriod::where('user_id', $userId)
            ->whereDate(
                'salary_date',
                '<',
                $validated['salary_date']
            )
            ->latest('salary_date')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Calculate Carry-Over
        |--------------------------------------------------------------------------
        */

        $carryOver = 0;


        if ($previousSalary) {

            $previousDeductions = LoanPayment::where('user_id', $userId)
                ->where(
                    'payment_method',
                    'Salary'
                )
                ->whereDate(
                    'payment_date',
                    '>=',
                    $previousSalary->period_start
                )
                ->whereDate(
                    'payment_date',
                    '<=',
                    $previousSalary->period_end
                )
                ->sum('amount');


            $carryOver = max(
                0,
                (float) $previousSalary->salary_amount
                + (float) $previousSalary->carry_over
                - (float) $previousDeductions
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Status
        |--------------------------------------------------------------------------
        */

        $today = now()->startOfDay();

        $salaryDate = \Carbon\Carbon::parse(
            $validated['salary_date']
        )->startOfDay();


        if ($salaryDate->isAfter($today)) {

            $validated['status'] = 'upcoming';

        } elseif ($salaryDate->isSameDay($today)) {

            $validated['status'] = 'current';

        } else {

            $validated['status'] = 'completed';
        }


        $validated['user_id'] = $userId;

        $validated['carry_over'] = $carryOver;


        SalaryPeriod::create($validated);


        return redirect()
            ->route('salary.index')
            ->with(
                'success',
                'Salary period added successfully. Previous remaining salary was carried over automatically.'
            );
    }


    /**
     * Delete salary record.
     */
    public function destroy(Salary $salary): RedirectResponse
    {
        if ($salary->user_id !== auth()->id()) {
            abort(403);
        }

        $salary->delete();

        return redirect()
            ->route('salary.index')
            ->with(
                'success',
                'Salary record deleted successfully.'
            );
    }


    /**
     * Delete salary period.
     */
    public function destroyPeriod(
        SalaryPeriod $salaryPeriod
    ): RedirectResponse {

        if ($salaryPeriod->user_id !== auth()->id()) {
            abort(403);
        }

        $salaryPeriod->delete();

        return redirect()
            ->route('salary.index')
            ->with(
                'success',
                'Salary period deleted successfully.'
            );
    }
}