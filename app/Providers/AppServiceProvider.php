<?php

namespace App\Providers;

use App\Models\Notification;
use App\Services\FormattingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {

            if (!auth()->check()) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Global Formatter
            |--------------------------------------------------------------------------
            */

            $view->with(
                'formatter',
                app(FormattingService::class)
            );


            /*
            |--------------------------------------------------------------------------
            | Header Notifications
            |--------------------------------------------------------------------------
            */

            $headerNotifications = Notification::query()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->latest()
                ->take(5)
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Header Unread Count
            |--------------------------------------------------------------------------
            */

            $headerUnreadNotifications = $headerNotifications
                ->whereNull('read_at')
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Share Notification Variables
            |--------------------------------------------------------------------------
            */

            $view->with(
                'headerNotifications',
                $headerNotifications
            );

            $view->with(
                'headerUnreadNotifications',
                $headerUnreadNotifications
            );
        });
    }
}