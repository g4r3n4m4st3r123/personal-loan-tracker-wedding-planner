<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_amount',
        'carry_over',
        'period_start',
        'period_end',
        'salary_date',
        'salary_type',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'salary_amount' => 'decimal:2',
            'carry_over' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'salary_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Values
    |--------------------------------------------------------------------------
    */

    /**
     * Get total salary-deduction loan payments for this period.
     */
    public function getLoanDeductionsAttribute(): float
    {
        return (float) LoanPayment::where('user_id', $this->user_id)
            ->where('payment_source', 'Salary')
            ->whereDate(
                'payment_date',
                '>=',
                $this->period_start
            )
            ->whereDate(
                'payment_date',
                '<=',
                $this->period_end
            )
            ->sum('amount');
    }

    /**
     * Get the total amount available at the start of this salary period.
     *
     * Current salary + previous salary carry-over.
     */
    public function getStartingAvailableSalaryAttribute(): float
    {
        return max(
            0,
            (float) $this->salary_amount
            + (float) $this->carry_over
        );
    }

    /**
     * Get salary remaining after current-period loan deductions.
     */
    public function getRemainingSalaryAttribute(): float
    {
        return max(
            0,
            $this->starting_available_salary
            - $this->loan_deductions
        );
    }
}