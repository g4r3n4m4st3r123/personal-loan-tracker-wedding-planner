<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'task_name',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
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

    public function getIsOverdueAttribute(): bool
    {
        if (
            !$this->due_date ||
            $this->status === 'completed'
        ) {
            return false;
        }

        return $this->due_date->isBefore(
            now()->startOfDay()
        );
    }

    public function getIsDueSoonAttribute(): bool
    {
        if (
            !$this->due_date ||
            $this->status === 'completed'
        ) {
            return false;
        }

        $today = now()->startOfDay();

        $sevenDaysFromNow = now()
            ->startOfDay()
            ->addDays(7);

        return $this->due_date->between(
            $today,
            $sevenDaysFromNow
        );
    }
}