<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Alert;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Carbon\Carbon;

/**
 * Class CalendarController
 *
 * @ling https://github.com/spatie/icalendar-generator
 * @package App\Http\Controllers\Calendar
 */
class CalendarController extends Controller
{
    public function index(string $hash)
    {
        // get flowData
        $flow = Flow::whereHash($hash)->firstOrFail();


        $flowData = FlowsData::whereFlowId($flow->id)
            ->whereNotNull('due_date')
            ->get();

        // generate events for calendar
        $events = array();
        foreach ($flowData as $item) {
            $events[] = Event::create()
                ->name($item->rule_reference)
                ->description(url('/user/flows#').rawurlencode($item->rule_reference))
                ->startsAt($item->due_date)
                ->endsAt($item->due_date)
//                    ->transparent()
//                    ->createdAt(Carbon::today())
//                ->alert(Alert::date(
//                    $alertTwoWeek = Carbon::parse($item->due_date)->addWeeks(-2),
//                    "The due-date for $item->rule_reference expires on $item->due_date"
//                ))
//                ->alert(Alert::date(
//                    $alertOneWeek = Carbon::parse($item->due_date)->addWeeks(-1),
//                    "The due-date for $item->rule_reference expires on $item->due_date"
//                ))
//                ->alert(Alert::date(
//                    $alertOneDay = Carbon::parse($item->due_date)->addDays(-1),
//                    "The due-date for $item->rule_reference expires on $item->due_date"
//                ))
                ->fullDay();
        }

        // create calendar with events
        $calendar = Calendar::create()
            ->name($flow->company->name .": ".$flow->title)
            ->refreshInterval(30)
            ->event($events);
//            ->get();

        // feed
        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8')
            ->header('Content-Disposition', 'attachment; filename="calendar.ics"');

//        // export
//        return response($calendar->get(), 200, [
//            'Content-Type' => 'text/calendar',
//            'Content-Disposition' => 'attachment; filename="calendar.ics"',
//            'charset' => 'utf-8',
//        ]);
    }
}
