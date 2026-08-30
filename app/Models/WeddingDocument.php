<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'file_name',
        'file_path',
        'file_type',
        'category',
        'description',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function getIsImageAttribute(): bool
    {
        return is_string($this->file_type)
            && str_starts_with($this->file_type, 'image/');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size ?? 0;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return number_format(
            $bytes / (1024 * 1024),
            1
        ) . ' MB';
    }
}