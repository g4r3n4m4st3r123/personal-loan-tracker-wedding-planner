<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'wedding_budget_id',
        'expense_name',
        'amount',
        'expense_date',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
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

    public function budget(): BelongsTo
    {
        return $this->belongsTo(
            WeddingBudget::class,
            'wedding_budget_id'
        );
    }
}