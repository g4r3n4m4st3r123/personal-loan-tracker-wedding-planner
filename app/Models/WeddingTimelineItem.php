<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingTimelineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'title',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'category',
        'status',
        'priority',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
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
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsTodayAttribute(): bool
    {
        return $this->event_date
            && $this->event_date->isToday();
    }

    public function getIsPastAttribute(): bool
    {
        return $this->event_date
            && $this->event_date->isBefore(
                now()->startOfDay()
            )
            && $this->status !== 'completed';
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->event_date
            && $this->event_date->isAfter(
                now()->startOfDay()
            );
    }
}