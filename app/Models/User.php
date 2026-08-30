<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function appSettings(): UserSetting
    {
        return $this->settings()->firstOrCreate(
            [],
            [
                'currency' => 'PHP',
                'currency_symbol' => '₱',
                'date_format' => 'M d, Y',
                'week_starts_on' => 'monday',
                'dashboard_view' => 'overview',
                'show_wedding_dashboard' => true,
                'show_finance_dashboard' => true,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function salaryPeriods(): HasMany
    {
        return $this->hasMany(SalaryPeriod::class);
    }

    public function weddings(): HasMany
    {
        return $this->hasMany(Wedding::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * User notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Unread notifications.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)
            ->whereNull('read_at');
    }
}   