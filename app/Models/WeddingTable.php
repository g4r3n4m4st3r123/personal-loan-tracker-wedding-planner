<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'table_name',
        'capacity',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function seatings(): HasMany
    {
        return $this->hasMany(WeddingSeating::class);
    }

    public function guests()
    {
        return $this->belongsToMany(
            WeddingGuest::class,
            'wedding_seatings'
        );
    }
}