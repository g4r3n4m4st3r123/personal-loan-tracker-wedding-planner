<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingCalendarController extends Controller
{
    /**
     * Display the wedding calendar.
     */
    public function index(Request $request): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Selected Month
        |--------------------------------------------------------------------------
        */

        $monthInput = $request->input(
            'month',
            now()->format('Y-m')
        );

        try {

            $currentMonth = Carbon::createFromFormat(
                'Y-m',
                $monthInput
            )->startOfMonth();

        } catch (\Throwable $e) {

            $currentMonth = now()->startOfMonth();
        }


        $previousMonth = $currentMonth
            ->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $currentMonth
            ->copy()
            ->addMonth()
            ->format('Y-m');


        /*
        |--------------------------------------------------------------------------
        | Calendar Range
        |--------------------------------------------------------------------------
        */

        $userSettings = auth()->user()->appSettings();

        $weekStart = $userSettings->week_starts_on === 'sunday'
            ? Carbon::SUNDAY
            : Carbon::MONDAY;

        $weekEnd = $weekStart === Carbon::SUNDAY
            ? Carbon::SATURDAY
            : Carbon::SUNDAY;

        $calendarStart = $currentMonth
            ->copy()
            ->startOfWeek($weekStart);

        $calendarEnd = $currentMonth
            ->copy()
            ->endOfMonth()
            ->endOfWeek($weekEnd);


        /*
        |--------------------------------------------------------------------------
        | Calendar Events
        |--------------------------------------------------------------------------
        */

        $calendarEvents = collect();


        /*
        |--------------------------------------------------------------------------
        | Wedding Date
        |--------------------------------------------------------------------------
        */

        if ($wedding->wedding_date) {

            $weddingDate = $wedding->wedding_date;

            if (
                $weddingDate->betweenIncluded(
                    $calendarStart,
                    $calendarEnd
                )
            ) {

                $calendarEvents->push([
                    'id' =>
                        'wedding-' . $wedding->id,

                    'type' =>
                        'wedding',

                    'title' =>
                        $wedding->wedding_name,

                    'date' =>
                        $weddingDate->format('Y-m-d'),

                    'start_time' =>
                        null,

                    'end_time' =>
                        null,

                    'location' =>
                        $wedding->venue,

                    'status' =>
                        'special',

                    'priority' =>
                        'high',

                    'description' =>
                        'Wedding Day',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

        $timelineItems = $wedding
            ->timelineItems()
            ->whereBetween(
                'event_date',
                [
                    $calendarStart->toDateString(),
                    $calendarEnd->toDateString(),
                ]
            )
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();


        foreach ($timelineItems as $item) {

            $calendarEvents->push([
                'id' =>
                    'timeline-' . $item->id,

                'type' =>
                    'timeline',

                'title' =>
                    $item->title,

                'date' =>
                    $item->event_date->format('Y-m-d'),

                'start_time' =>
                    $item->start_time,

                'end_time' =>
                    $item->end_time,

                'location' =>
                    $item->location,

                'status' =>
                    $item->status,

                'priority' =>
                    $item->priority,

                'description' =>
                    $item->description,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Checklist
        |--------------------------------------------------------------------------
        */

        $tasks = $wedding
            ->tasks()
            ->whereNotNull('due_date')
            ->whereBetween(
                'due_date',
                [
                    $calendarStart->toDateString(),
                    $calendarEnd->toDateString(),
                ]
            )
            ->orderBy('due_date')
            ->get();


        foreach ($tasks as $task) {

            $calendarEvents->push([
                'id' =>
                    'task-' . $task->id,

                'type' =>
                    'checklist',

                'title' =>
                    $task->task_name,

                'date' =>
                    $task->due_date->format('Y-m-d'),

                'start_time' =>
                    null,

                'end_time' =>
                    null,

                'location' =>
                    null,

                'status' =>
                    $task->status,

                'priority' =>
                    $task->priority,

                'description' =>
                    $task->description,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vendors
        |--------------------------------------------------------------------------
        */

        $vendors = $wedding
            ->vendors()
            ->whereNotNull('service_date')
            ->whereBetween(
                'service_date',
                [
                    $calendarStart->toDateString(),
                    $calendarEnd->toDateString(),
                ]
            )
            ->orderBy('service_date')
            ->get();


        foreach ($vendors as $vendor) {

            $calendarEvents->push([
                'id' =>
                    'vendor-' . $vendor->id,

                'type' =>
                    'vendor',

                'title' =>
                    $vendor->vendor_name,

                'date' =>
                    $vendor->service_date->format('Y-m-d'),

                'start_time' =>
                    null,

                'end_time' =>
                    null,

                'location' =>
                    null,

                'status' =>
                    $vendor->payment_status,

                'priority' =>
                    'medium',

                'description' =>
                    $vendor->service_type,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Group Events By Date
        |--------------------------------------------------------------------------
        */

        $eventsByDate = $calendarEvents
            ->sortBy(function ($event) {
                return $event['date']
                    . ' '
                    . ($event['start_time'] ?? '00:00:00');
            })
            ->groupBy('date');


        /*
        |--------------------------------------------------------------------------
        | Build Calendar Weeks
        |--------------------------------------------------------------------------
        */

        $calendarDays = collect();

        $date = $calendarStart->copy();


        while ($date->lte($calendarEnd)) {

            $dateKey = $date->format('Y-m-d');

            $calendarDays->push([
                'date' =>
                    $date->copy(),

                'dateKey' =>
                    $dateKey,

                'isCurrentMonth' =>
                    $date->month === $currentMonth->month,

                'isToday' =>
                    $date->isToday(),

                'events' =>
                    $eventsByDate->get(
                        $dateKey,
                        collect()
                    ),
            ]);


            $date->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | Monthly Event Count
        |--------------------------------------------------------------------------
        */

        $monthlyEventCount =
            $calendarEvents->count();


        return view(
            'wedding.calendar.index',
            compact(
                'wedding',
                'currentMonth',
                'previousMonth',
                'nextMonth',
                'calendarDays',
                'calendarEvents',
                'monthlyEventCount',
                'userSettings'
            )
        );
    }
}