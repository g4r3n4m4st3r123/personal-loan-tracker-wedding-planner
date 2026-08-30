<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'name',
        'guest_type',
        'rsvp_status',
        'plus_one',
        'meal_preference',
        'phone',
        'email',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'plus_one' => 'boolean',
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

    public function seating(): HasOne
    {
        return $this->hasOne(
            WeddingSeating::class,
            'wedding_guest_id'
        );
    }
}