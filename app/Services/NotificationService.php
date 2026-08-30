<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Wedding;
use App\Models\WeddingBudget;
use App\Models\WeddingTask;
use App\Models\WeddingVendor;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Get all live notifications for the authenticated user.
     */
    public function getNotifications(?int $userId = null): Collection
    {
        $userId ??= auth()->id();

        if (!$userId) {
            return collect();
        }

        $notifications = collect();

        $this->addLoanNotifications(
            $notifications,
            $userId
        );

        $this->addWeddingNotifications(
            $notifications,
            $userId
        );

        return $notifications
            ->sort(function ($a, $b) {

                $priorityComparison =
                    ($b['priority'] ?? 0)
                    <=>
                    ($a['priority'] ?? 0);

                if ($priorityComparison !== 0) {
                    return $priorityComparison;
                }

                return Carbon::parse($a['date'])
                    <=> Carbon::parse($b['date']);
            })
            ->values()
            ->take(15);
    }

    /**
     * Loan notifications.
     */
    protected function addLoanNotifications(
        Collection $notifications,
        int $userId
    ): void {
        $today = Carbon::today();

        $loans = Loan::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'overdue'])
            ->whereNotNull('due_date')
            ->get();

        foreach ($loans as $loan) {

            $remaining = (float) ($loan->remaining_balance ?? 0);

            if ($remaining <= 0) {
                continue;
            }

            $dueDate = $loan->due_date instanceof Carbon
                ? $loan->due_date->copy()
                : Carbon::parse($loan->due_date);

            $days = $today->diffInDays($dueDate, false);

            /*
             * OVERDUE
             */
            if ($days < 0) {

                $notifications->push([
                    'id' => 'loan-overdue-' . $loan->id,
                    'type' => 'danger',
                    'icon' => '⚠️',
                    'title' => 'Loan Payment Overdue',
                    'message' => $loan->loan_name .
                        ' is overdue by ' .
                        abs($days) .
                        ' day' .
                        (abs($days) === 1 ? '' : 's') . '.',
                    'amount' => $remaining,
                    'date' => $dueDate,
                    'url' => route('loans.show', $loan),
                    'priority' => 100,
                ]);

                continue;
            }

            /*
             * DUE TODAY
             */
            if ($days === 0) {

                $notifications->push([
                    'id' => 'loan-due-today-' . $loan->id,
                    'type' => 'warning',
                    'icon' => '🔔',
                    'title' => 'Loan Payment Due Today',
                    'message' => $loan->loan_name .
                        ' is due today.',
                    'amount' => (float) ($loan->monthly_payment ?? 0),
                    'date' => $dueDate,
                    'url' => route('loans.show', $loan),
                    'priority' => 95,
                ]);

                continue;
            }

            /*
             * DUE SOON
             */
            if ($days <= 7) {

                $notifications->push([
                    'id' => 'loan-due-soon-' . $loan->id,
                    'type' => 'info',
                    'icon' => '📅',
                    'title' => 'Loan Payment Due Soon',
                    'message' => $loan->loan_name .
                        ' is due in ' .
                        $days .
                        ' day' .
                        ($days === 1 ? '' : 's') . '.',
                    'amount' => (float) ($loan->monthly_payment ?? 0),
                    'date' => $dueDate,
                    'url' => route('loans.show', $loan),
                    'priority' => 80,
                ]);
            }
        }
    }

    /**
     * Wedding notifications.
     */
    protected function addWeddingNotifications(
        Collection $notifications,
        int $userId
    ): void {
        $wedding = Wedding::query()
            ->where('user_id', $userId)
            ->latest('id')
            ->first();

        if (!$wedding) {
            return;
        }

        $today = Carbon::today();

        /*
         * WEDDING DATE
         */
        if ($wedding->wedding_date) {

            $weddingDate = $wedding->wedding_date instanceof Carbon
                ? $wedding->wedding_date->copy()
                : Carbon::parse($wedding->wedding_date);

            $days = $today->diffInDays($weddingDate, false);

            if ($days >= 0 && $days <= 30) {

                if ($days === 0) {
                    $message = 'Your wedding is today! 💍';
                } elseif ($days === 1) {
                    $message = 'Your wedding is tomorrow!';
                } else {
                    $message = 'Your wedding is in ' . $days . ' days.';
                }

                $notifications->push([
                    'id' => 'wedding-date-' . $wedding->id,
                    'type' => 'wedding',
                    'icon' => '💍',
                    'title' => 'Wedding Countdown',
                    'message' => $message,
                    'amount' => null,
                    'date' => $weddingDate,
                    'url' => route('wedding.index'),
                    'priority' => 90,
                ]);
            }
        }

        /*
         * WEDDING TASKS
         */
        $tasks = WeddingTask::query()
            ->where('wedding_id', $wedding->id)
            ->whereNotNull('due_date')
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {

            $dueDate = $task->due_date instanceof Carbon
                ? $task->due_date->copy()
                : Carbon::parse($task->due_date);

            $days = $today->diffInDays($dueDate, false);

            if ($days < 0) {

                $notifications->push([
                    'id' => 'task-overdue-' . $task->id,
                    'type' => 'danger',
                    'icon' => '🚨',
                    'title' => 'Wedding Task Overdue',
                    'message' => $task->task_name .
                        ' is overdue.',
                    'amount' => null,
                    'date' => $dueDate,
                    'url' => route('wedding.checklist'),
                    'priority' => 88,
                ]);

                continue;
            }

            if ($days <= 7) {

                $notifications->push([
                    'id' => 'task-due-soon-' . $task->id,
                    'type' => 'warning',
                    'icon' => '📋',
                    'title' => 'Wedding Task Due Soon',
                    'message' => $task->task_name .
                        ' is due in ' .
                        $days .
                        ' day' .
                        ($days === 1 ? '' : 's') . '.',
                    'amount' => null,
                    'date' => $dueDate,
                    'url' => route('wedding.checklist'),
                    'priority' => 75,
                ]);
            }
        }

        /*
         * WEDDING BUDGET
         */
        $budgets = WeddingBudget::query()
            ->where('wedding_id', $wedding->id)
            ->get();

        foreach ($budgets as $budget) {

            $planned = (float) ($budget->planned_amount ?? 0);

            if ($planned <= 0) {
                continue;
            }

            $actual = \App\Models\WeddingExpense::query()
                ->where('wedding_budget_id', $budget->id)
                ->where('payment_status', 'paid')
                ->sum('amount');

            $usage = ($actual / $planned) * 100;

            if ($usage >= 100) {

                $notifications->push([
                    'id' => 'budget-exceeded-' . $budget->id,
                    'type' => 'danger',
                    'icon' => '💸',
                    'title' => 'Wedding Budget Exceeded',
                    'message' => $budget->category .
                        ' has exceeded its planned budget.',
                    'amount' => $actual,
                    'date' => now(),
                    'url' => route('wedding.budget'),
                    'priority' => 85,
                ]);

            } elseif ($usage >= 80) {

                $notifications->push([
                    'id' => 'budget-warning-' . $budget->id,
                    'type' => 'warning',
                    'icon' => '💰',
                    'title' => 'Wedding Budget Almost Used',
                    'message' => $budget->category .
                        ' has reached ' .
                        number_format($usage, 0) .
                        '% of its planned budget.',
                    'amount' => $actual,
                    'date' => now(),
                    'url' => route('wedding.budget'),
                    'priority' => 70,
                ]);
            }
        }

        /*
         * WEDDING VENDORS
         */
        $vendors = WeddingVendor::query()
            ->where('wedding_id', $wedding->id)
            ->where('payment_status', '!=', 'paid')
            ->get()
            ->filter(function ($vendor) {
                return (float) ($vendor->balance ?? 0) > 0;
            });

        foreach ($vendors as $vendor) {

            $balance = (float) ($vendor->balance ?? 0);

            if ($balance <= 0) {
                continue;
            }

            if ($vendor->service_date) {

                $serviceDate = $vendor->service_date instanceof Carbon
                    ? $vendor->service_date->copy()
                    : Carbon::parse($vendor->service_date);

                $days = $today->diffInDays($serviceDate, false);

                if ($days >= 0 && $days <= 14) {

                    $notifications->push([
                        'id' => 'vendor-payment-' . $vendor->id,
                        'type' => 'vendor',
                        'icon' => '🧑‍💼',
                        'title' => 'Vendor Balance Outstanding',
                        'message' => $vendor->vendor_name .
                            ' has an outstanding balance before the service date.',
                        'amount' => $balance,
                        'date' => $serviceDate,
                        'url' => route('wedding.vendors'),
                        'priority' => 65,
                    ]);
                }
            }
        }
    }


    /**
     * Persist live notifications into the notifications table.
     */
    public function syncToDatabase(?int $userId = null): void
    {
        $userId ??= auth()->id();

        if (!$userId) {
            return;
        }

        $liveNotifications = $this->getNotifications(
            $userId
        );

        foreach ($liveNotifications as $notification) {

            \App\Models\Notification::firstOrCreate(
                [
                    'user_id' => $userId,
                    'type' => $notification['type'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'url' => $notification['url'],
                ],
                [
                    'read_at' => null,
                ]
            );
        }
    }
}