<?php

namespace App\Services;

use App\Models\UserSetting;
use Carbon\Carbon;

class FormattingService
{
    /**
     * Get settings for the currently authenticated user.
     *
     * Falls back to application defaults when there is no
     * authenticated user, such as when running Artisan Tinker.
     */
    public function settings(): UserSetting
    {
        if (auth()->check()) {
            return auth()->user()->appSettings();
        }

        return $this->defaultSettings();
    }

    /**
     * Return default display settings.
     */
    protected function defaultSettings(): UserSetting
    {
        $settings = new UserSetting();

        $settings->currency = 'PHP';
        $settings->currency_symbol = '₱';
        $settings->date_format = 'M d, Y';
        $settings->week_starts_on = 'monday';
        $settings->dashboard_view = 'overview';
        $settings->show_wedding_dashboard = true;
        $settings->show_finance_dashboard = true;

        return $settings;
    }

    /**
     * Format a monetary amount.
     */
    public function money(float|int|string|null $amount): string
    {
        $amount = (float) ($amount ?? 0);

        return $this->settings()->currency_symbol
            . number_format($amount, 2);
    }

    /**
     * Format a date using the selected user preference.
     */
    public function date(
        Carbon|string|null $date
    ): string {
        if (!$date) {
            return '—';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->format(
            $this->settings()->date_format
        );
    }

    /**
     * Get the currency code.
     */
    public function currency(): string
    {
        return $this->settings()->currency;
    }

    /**
     * Get the currency symbol.
     */
    public function symbol(): string
    {
        return $this->settings()->currency_symbol;
    }
}