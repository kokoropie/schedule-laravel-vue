<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request, $day = ""): Response | RedirectResponse
    {
        $id = $request->get('id', null);
        if (!$id) {
            $schedule_selected = auth()->user()->schedules()->first();
        } else {
            if (auth()->user()->schedules()->where("schedule_id", $id)->exists()) {
                $schedule_selected = auth()->user()->schedules()->where("schedule_id", $id)->first();
            } else {
                $schedule_selected = auth()->user()->schedules()->first();
            }
        }
        if ($schedule_selected) {
            $time = time();

            if (!empty($day)) {
                $time = strtotime($day);
            }

            $nextWeek = $time + 60*60*24*7;

            $schedules = [];
            $tmp = $schedule_selected->details()->where(function($query) use ($nextWeek, $time) {
                $query->whereDate("from", "<=", date("Y-m-d", $time))
                    ->whereDate("to", ">", date("Y-m-d", $nextWeek));
            })
            ->orWhere(function($query) use ($nextWeek, $time) {
                $query->whereDate('from', ">=", date("Y-m-d", $time))
                    ->whereDate('from', "<", date("Y-m-d", $nextWeek));
            })
            ->orWhere(function($query) use ($nextWeek, $time) {
                $query->whereDate('to', ">=", date("Y-m-d", $time))
                    ->whereDate('to', "<", date("Y-m-d", $nextWeek));
            })->get();
            $maxADay = $schedule_selected->numOfClassPeriodsPerDay;

            for ($j = 0; $j < 7; ++$j) {
                $schedules[$j] = [];
                $date = date("w", $time + 60*60*24*$j);
                for ($i = 1; $i <= $maxADay; ++$i) {
                    $schedules[$j][$i] = [
                        "label" => "",
                        "title" => "",
                        "rowspan" => 1,
                        "onl" => false,
                        "teacher" => "",
                        "style" => []
                    ];
                    foreach ($tmp as $schedule) {
                        if ($schedule->dateOfWeek - 1 == $date && $schedule->start == $i) {
                            $schedules[$j][$i] = [
                                "label" => $schedule->subject_id,
                                "title" => $schedule->subject->name,
                                "rowspan" => $schedule->end - $schedule->start + 1,
                                "onl" => $schedule->type === "ONLINE",
                                "teacher" => $schedule->subject->teacher->name,
                                "style" => [
                                    "color" => $schedule->subject->color_foreground,
                                    "background" => $schedule->subject->color_background
                                ]
                            ];
                            $i = $schedule->end;
                            break;
                        }
                    }
                }
            }

            $curDate = date("w", $time)*1;
            $nameOfDate = [];
            $tmp = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            $j = 0;
            for ($i = $curDate; $i < 7; $i++) {
                $nameOfDate[$i] = [
                    "title" => $tmp[$i],
                    "date" => date("Y-m-d", $time + 60*60*24*($j++))
                ];
            }
            for ($i = 0; $i < $curDate; $i++) {
                $nameOfDate[$i] = [
                    "title" => $tmp[$i],
                    "date" => date("Y-m-d", $time + 60*60*24*($j++))
                ];
            }

            return Inertia::render('Home', [
                'schedules' => fn () => auth()->user()->schedules,
                'schedule_selected' => fn () => $schedule_selected,
                'schedule_details' => fn () => $schedules,
                'date' => $curDate,
                'nameOfDate' => $nameOfDate,
                'maxADay' => $maxADay,
                'timeOfEachClassPeriod' => fn () => $schedule_selected->timeOfEachClassPeriod,
                'day' => date('Y-m-d', $time),
                'prev_day' => date('Y-m-d', $time - 24*60*60),
                'next_day' => date('Y-m-d', $time + 24*60*60),
                'today' => date('Y-m-d')
            ]);
        } else {
            return redirect(route('schedule.index'));
        }
    }
}
