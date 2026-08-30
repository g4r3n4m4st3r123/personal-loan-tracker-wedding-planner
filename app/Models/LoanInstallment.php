<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'amount_due',
        'amount_paid',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount_due' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(
            LoanPayment::class,
            'loan_installment_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Values
    |--------------------------------------------------------------------------
    */

    public function getRemainingAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->amount_due
                - (float) $this->amount_paid
        );
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->remaining_amount > 0
            && $this->due_date
            && $this->due_date->isBefore(
                now()->startOfDay()
            );
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->remaining_amount <= 0;
    }

    public function getIsDueTodayAttribute(): bool
    {
        return $this->remaining_amount > 0
            && $this->due_date
            && $this->due_date->isToday();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->remaining_amount > 0
            && $this->due_date
            && $this->due_date->isAfter(
                now()->startOfDay()
            );
    }
}