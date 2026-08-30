<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'currency_symbol',
        'date_format',
        'week_starts_on',
        'dashboard_view',
        'show_wedding_dashboard',
        'show_finance_dashboard',
    ];

    protected function casts(): array
    {
        return [
            'show_wedding_dashboard' => 'boolean',
            'show_finance_dashboard' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}