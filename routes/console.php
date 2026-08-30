<?php

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {

    $notificationService = app(
        NotificationService::class
    );

    User::query()
        ->select('id')
        ->chunkById(
            100,
            function ($users) use (
                $notificationService
            ) {

                foreach ($users as $user) {

                    $notificationService
                        ->syncToDatabase(
                            $user->id
                        );
                }
            }
        );

})->dailyAt('07:00');