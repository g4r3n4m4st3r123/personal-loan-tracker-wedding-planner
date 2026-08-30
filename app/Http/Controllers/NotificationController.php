<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }

    /**
     * Display the authenticated user's notifications.
     */
    public function index(): View
    {
        $this->notificationService->syncToDatabase();

        $notifications = Notification::where(
            'user_id',
            auth()->id()
        )
            ->latest()
            ->paginate(15);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    /**
     * Mark one notification as read and redirect to its URL.
     */
    public function read(
        Notification $notification
    ): RedirectResponse {

        abort_unless(
            $notification->user_id === auth()->id(),
            403
        );

        if (is_null($notification->read_at)) {

            $notification->update([
                'read_at' => now(),
            ]);
        }

        return $notification->url
            ? redirect($notification->url)
            : redirect()->route(
                'notifications.index'
            );
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Notification::where(
            'user_id',
            auth()->id()
        )
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}