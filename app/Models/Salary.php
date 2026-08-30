<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'effective_date',
        'salary_type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'effective_date' => 'date',
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
    | Salary Calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Get total loan payments deducted from this salary.
     */
    public function getTotalDeductionsAttribute(): float
    {
        return (float) LoanPayment::where('user_id', $this->user_id)
            ->where('payment_source', 'Salary')
            ->whereDate('payment_date', '>=', $this->effective_date)
            ->sum('amount');
    }

    /**
     * Get remaining available salary.
     */
    public function getAvailableSalaryAttribute(): float
    {
        return max(
            0,
            (float) $this->amount - $this->total_deductions
        );
    }

    /**
     * Get deduction percentage.
     */
    public function getDeductionPercentageAttribute(): float
    {
        if ((float) $this->amount <= 0) {
            return 0;
        }

        return min(
            100,
            round(
                ($this->total_deductions / (float) $this->amount) * 100,
                2
            )
        );
    }
}