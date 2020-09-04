<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Carbon\Carbon;


class CalendarController extends Controller
{
    public function index(Flow $flow)
    {
        // Documentation
        // https://github.com/spatie/icalendar-generator

        // get flowData
        $flowData = FlowsData::whereFlowId($flow->id)
            ->whereNotNull('due_date')
            ->get();

        // generate events for calendar
        $events = array();
        foreach ($flowData as $item) {
            $events[] = Event::create($item->rule_reference)
                ->startsAt($item->due_date)
                ->endsAt($item->due_date)
//                    ->transparent()
//                    ->createdAt(Carbon::today())
                ->fullDay();
        }

        // create calendar with events
        $calendar = Calendar::create()
            ->name($flow->company->name .": ".$flow->title)
//            ->description($flow->description)
            ->refreshInterval(1)
            ->event($events);
//            ->get();

        // feed
        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');

//        // export
//        return response($calendar->get(), 200, [
//            'Content-Type' => 'text/calendar',
//            'Content-Disposition' => 'attachment; filename="calendar.ics"',
//            'charset' => 'utf-8',
//        ]);
    }
}
