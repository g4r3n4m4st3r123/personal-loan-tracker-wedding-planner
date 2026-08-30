<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingSeating extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'wedding_table_id',
        'wedding_guest_id',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(
            WeddingTable::class,
            'wedding_table_id'
        );
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(
            WeddingGuest::class,
            'wedding_guest_id'
        );
    }
}