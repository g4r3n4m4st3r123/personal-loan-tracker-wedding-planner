<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\Notification;
use App\Models\Wedding;
use App\Models\WeddingTask;
use App\Models\WeddingVendor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAutomaticNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:generate';

    /**
     * The console command description.
     */
    protected $description = 'Generate automatic finance and wedding notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking automatic notifications...');

        $this->checkLoans();

        $this->checkWeddingTasks();

        $this->checkWeddingVendors();

        $this->checkWeddingDates();

        $this->info('Automatic notification check completed.');

        return self::SUCCESS;
    }

    /**
     * Check upcoming and overdue loans.
     */
    private function checkLoans(): void
    {
        $loans = Loan::whereNotNull('due_date')
            ->whereIn('status', ['active', 'overdue'])
            ->get();

        foreach ($loans as $loan) {

            $today = Carbon::today();

            $dueDate = $loan->due_date->copy()->startOfDay();

            $daysUntilDue = $today->diffInDays($dueDate, false);

            /*
            |--------------------------------------------------------------------------
            | Overdue
            |--------------------------------------------------------------------------
            */

            if ($daysUntilDue < 0) {

                $this->createNotification(
                    userId: $loan->user_id,
                    type: 'loan',
                    title: 'Loan Overdue',
                    message: "{$loan->loan_name} is overdue since {$loan->due_date->format('F d, Y')}.",
                    url: route('loans.show', $loan),
                    uniqueKey: "loan-overdue-{$loan->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Due Today
            |--------------------------------------------------------------------------
            */

            if ($daysUntilDue === 0) {

                $this->createNotification(
                    userId: $loan->user_id,
                    type: 'loan',
                    title: 'Loan Due Today',
                    message: "{$loan->loan_name} is due today.",
                    url: route('loans.show', $loan),
                    uniqueKey: "loan-due-today-{$loan->id}-" . $today->format('Y-m-d')
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 1, 3, and 7 Days Before
            |--------------------------------------------------------------------------
            */

            if (in_array($daysUntilDue, [1, 3, 7])) {

                $dayText = $daysUntilDue === 1
                    ? 'tomorrow'
                    : "in {$daysUntilDue} days";

                $this->createNotification(
                    userId: $loan->user_id,
                    type: 'loan',
                    title: 'Loan Due Soon',
                    message: "{$loan->loan_name} is due {$dayText} on {$loan->due_date->format('F d, Y')}.",
                    url: route('loans.show', $loan),
                    uniqueKey: "loan-due-{$loan->id}-{$daysUntilDue}-" . $today->format('Y-m-d')
                );
            }
        }
    }

    /**
     * Check wedding checklist deadlines.
     */
    private function checkWeddingTasks(): void
    {
        $tasks = WeddingTask::whereNotNull('due_date')
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {

            $today = Carbon::today();

            $dueDate = $task->due_date->copy()->startOfDay();

            $daysUntilDue = $today->diffInDays($dueDate, false);

            /*
            |--------------------------------------------------------------------------
            | Overdue
            |--------------------------------------------------------------------------
            */

            if ($daysUntilDue < 0) {

                $this->createNotification(
                    userId: $task->user_id,
                    type: 'wedding',
                    title: 'Wedding Task Overdue',
                    message: "\"{$task->task_name}\" is overdue since {$task->due_date->format('F d, Y')}.",
                    url: route('wedding.checklist'),
                    uniqueKey: "task-overdue-{$task->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Due Today
            |--------------------------------------------------------------------------
            */

            if ($daysUntilDue === 0) {

                $this->createNotification(
                    userId: $task->user_id,
                    type: 'wedding',
                    title: 'Wedding Task Due Today',
                    message: "\"{$task->task_name}\" is due today.",
                    url: route('wedding.checklist'),
                    uniqueKey: "task-today-{$task->id}-" . $today->format('Y-m-d')
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 1, 3, and 7 Days Before
            |--------------------------------------------------------------------------
            */

            if (in_array($daysUntilDue, [1, 3, 7])) {

                $dayText = $daysUntilDue === 1
                    ? 'tomorrow'
                    : "in {$daysUntilDue} days";

                $this->createNotification(
                    userId: $task->user_id,
                    type: 'wedding',
                    title: 'Wedding Task Due Soon',
                    message: "\"{$task->task_name}\" is due {$dayText}.",
                    url: route('wedding.checklist'),
                    uniqueKey: "task-due-{$task->id}-{$daysUntilDue}-" . $today->format('Y-m-d')
                );
            }
        }
    }

    /**
     * Check vendor service dates and outstanding balances.
     */
    private function checkWeddingVendors(): void
    {
        $vendors = WeddingVendor::whereNotNull('service_date')
            ->where('payment_status', '!=', 'paid')
            ->get();

        foreach ($vendors as $vendor) {

            $today = Carbon::today();

            $serviceDate = $vendor->service_date->copy()->startOfDay();

            $daysUntilService = $today->diffInDays(
                $serviceDate,
                false
            );

            /*
            |--------------------------------------------------------------------------
            | Service Date Passed
            |--------------------------------------------------------------------------
            */

            if ($daysUntilService < 0) {

                $this->createNotification(
                    userId: $vendor->user_id,
                    type: 'vendor',
                    title: 'Vendor Service Date Passed',
                    message: "{$vendor->vendor_name}'s service date was {$vendor->service_date->format('F d, Y')} and still has an outstanding balance.",
                    url: route('wedding.vendors'),
                    uniqueKey: "vendor-past-{$vendor->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Service Today
            |--------------------------------------------------------------------------
            */

            if ($daysUntilService === 0) {

                $this->createNotification(
                    userId: $vendor->user_id,
                    type: 'vendor',
                    title: 'Vendor Service Today',
                    message: "{$vendor->vendor_name} is scheduled for today. Outstanding balance: ₱" .
                        number_format((float) $vendor->balance, 2),
                    url: route('wedding.vendors'),
                    uniqueKey: "vendor-today-{$vendor->id}-" . $today->format('Y-m-d')
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 1, 3, and 7 Days Before
            |--------------------------------------------------------------------------
            */

            if (in_array($daysUntilService, [1, 3, 7])) {

                $dayText = $daysUntilService === 1
                    ? 'tomorrow'
                    : "in {$daysUntilService} days";

                $this->createNotification(
                    userId: $vendor->user_id,
                    type: 'vendor',
                    title: 'Vendor Service Coming Up',
                    message: "{$vendor->vendor_name} has a service {$dayText} on {$vendor->service_date->format('F d, Y')}. Outstanding balance: ₱" .
                        number_format((float) $vendor->balance, 2),
                    url: route('wedding.vendors'),
                    uniqueKey: "vendor-due-{$vendor->id}-{$daysUntilService}-" . $today->format('Y-m-d')
                );
            }
        }
    }

    /**
     * Check the main wedding date.
     */
    private function checkWeddingDates(): void
    {
        $weddings = Wedding::whereNotNull('wedding_date')
            ->get();

        foreach ($weddings as $wedding) {

            $today = Carbon::today();

            $weddingDate = $wedding->wedding_date
                ->copy()
                ->startOfDay();

            $daysUntilWedding = $today->diffInDays(
                $weddingDate,
                false
            );

            /*
            |--------------------------------------------------------------------------
            | Wedding Passed
            |--------------------------------------------------------------------------
            */

            if ($daysUntilWedding < 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Wedding Day
            |--------------------------------------------------------------------------
            */

            if ($daysUntilWedding === 0) {

                $this->createNotification(
                    userId: $wedding->user_id,
                    type: 'wedding',
                    title: 'Wedding Day! 💍',
                    message: "Today is {$wedding->wedding_name}. Wishing you a beautiful wedding day!",
                    url: route('wedding.index'),
                    uniqueKey: "wedding-today-{$wedding->id}-" . $today->format('Y-m-d')
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Countdown Alerts
            |--------------------------------------------------------------------------
            */

            if (in_array($daysUntilWedding, [1, 3, 7, 14, 30])) {

                $dayText = $daysUntilWedding === 1
                    ? 'tomorrow'
                    : "in {$daysUntilWedding} days";

                $this->createNotification(
                    userId: $wedding->user_id,
                    type: 'wedding',
                    title: 'Wedding Countdown',
                    message: "{$wedding->wedding_name} is {$dayText} — {$wedding->wedding_date->format('F d, Y')}.",
                    url: route('wedding.index'),
                    uniqueKey: "wedding-countdown-{$wedding->id}-{$daysUntilWedding}-" . $today->format('Y-m-d')
                );
            }
        }
    }

    /**
     * Create a notification only once for the same unique event.
     */
    private function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        string $url,
        string $uniqueKey
    ): void {
        $exists = Notification::where('user_id', $userId)
            ->where('type', $type)
            ->where('title', $title)
            ->where('message', $message)
            ->where('url', $url)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($exists) {
            return;
        }

        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);

        $this->line("Created: {$title}");
    }
}