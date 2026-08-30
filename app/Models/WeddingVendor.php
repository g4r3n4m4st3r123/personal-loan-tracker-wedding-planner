<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'vendor_name',
        'service_type',
        'contact_person',
        'phone',
        'email',
        'address',
        'agreed_amount',
        'amount_paid',
        'payment_status',
        'booking_date',
        'service_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'agreed_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'booking_date' => 'date',
            'service_date' => 'date',
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

    /*
    |--------------------------------------------------------------------------
    | Calculated Values
    |--------------------------------------------------------------------------
    */

    public function getBalanceAttribute(): float
    {
        return max(
            0,
            (float) $this->agreed_amount
            - (float) $this->amount_paid
        );
    }

    public function getPaymentPercentageAttribute(): float
    {
        if ((float) $this->agreed_amount <= 0) {
            return 0;
        }

        return min(
            100,
            round(
                (
                    (float) $this->amount_paid
                    / (float) $this->agreed_amount
                ) * 100,
                1
            )
        );
    }
}