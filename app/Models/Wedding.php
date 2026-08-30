<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wedding_name',
        'partner_name',
        'wedding_date',
        'venue',
        'budget',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'budget' => 'decimal:2',
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

    public function budgets(): HasMany
    {
        return $this->hasMany(WeddingBudget::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(WeddingExpense::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeddingTask::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(WeddingGuest::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(WeddingVendor::class);
    }

    public function timelineItems(): HasMany
    {
        return $this->hasMany(WeddingTimelineItem::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(WeddingTable::class);
    }

    public function seatings(): HasMany
    {
        return $this->hasMany(WeddingSeating::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(WeddingDocument::class);
    }
}