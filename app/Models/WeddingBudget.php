<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'category',
        'planned_amount',
        'actual_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_amount' => 'decimal:2',
            'actual_amount' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(
            WeddingExpense::class,
            'wedding_budget_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Values
    |--------------------------------------------------------------------------
    */

    /**
     * Actual spending is calculated automatically from paid
     * wedding expenses belonging to this budget category.
     */
    public function getActualAmountAttribute($value): float
    {
        if ($this->relationLoaded('expenses')) {
            return (float) $this->expenses
                ->where('payment_status', 'paid')
                ->sum('amount');
        }

        return (float) $this->expenses()
            ->where('payment_status', 'paid')
            ->sum('amount');
    }

    /**
     * Remaining budget.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->planned_amount
            - (float) $this->actual_amount
        );
    }

    /**
     * Budget usage percentage.
     */
    public function getUsagePercentageAttribute(): float
    {
        if ((float) $this->planned_amount <= 0) {
            return 0;
        }

        return min(
            100,
            round(
                (
                    (float) $this->actual_amount
                    / (float) $this->planned_amount
                ) * 100,
                1
            )
        );
    }
}