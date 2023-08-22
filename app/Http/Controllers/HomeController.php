<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

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
            $time = Carbon::today(config("app.timezone", "Asia/Ho_Chi_Minh"));

            if (!empty($day)) {
                $time = Carbon::createFromFormat("!Y-m-d", $day, config("app.timezone", "Asia/Ho_Chi_Minh"));
            }

            $nextWeek = $time->copy()->addDays(7);

            $schedules = [];
            $tmp = $schedule_selected->details()->where(function($query) use ($nextWeek, $time) {
                $query->whereDate("from", "<=", $time)
                    ->whereDate("to", ">", $nextWeek);
            })
            ->orWhere(function($query) use ($nextWeek, $time) {
                $query->whereDate('from', ">=", $time)
                    ->whereDate('from', "<", $nextWeek);
            })
            ->orWhere(function($query) use ($nextWeek, $time) {
                $query->whereDate('to', ">=", $time)
                    ->whereDate('to', "<", $nextWeek);
            })->get();
            $maxADay = $schedule_selected->numOfClassPeriodsPerDay;

            $date = $time->copy();
            for ($j = 0; $j < 7; ++$j) {
                $schedules[$j] = [];
                // $date->addDay();
                // $date = $time->copy()->addDays($j);
                for ($i = 1; $i <= $maxADay; $i++) {
                    $schedules[$j][$i] = [
                        "label" => "",
                        "title" => "",
                        "rowspan" => 1,
                        "onl" => false,
                        "teacher" => "",
                        "style" => []
                    ];
                    foreach ($tmp as $schedule) {
                        if ($schedule->dateOfWeek - 1 == $date->format("w") && $schedule->start == $i && $date->greaterThanOrEqualTo($schedule->from) && $date->lessThanOrEqualTo($schedule->to)) {
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
                $date->addDay();
            }

            $nameOfDate = [];
            $tmp = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            // $j = 0;
            $curDate = $time->copy();
            for ($i = $time->format("w"); $i < 7; $i++) {
                $nameOfDate[$i] = [
                    "title" => $tmp[$i],
                    "date" => $curDate->format("Y-m-d")
                ];
                $curDate->addDay();
            }
            for ($i = 0; $i < $time->format("w"); $i++) {
                $nameOfDate[$i] = [
                    "title" => $tmp[$i],
                    "date" => $curDate->format("Y-m-d")
                ];
                $curDate->addDay();
            }

            return Inertia::render('Home', [
                'schedules' => fn () => auth()->user()->schedules,
                'schedule_selected' => fn () => $schedule_selected,
                'schedule_details' => fn () => $schedules,
                'date' => $time->format("w")*1,
                'nameOfDate' => $nameOfDate,
                'maxADay' => $maxADay,
                'timeOfEachClassPeriod' => fn () => $schedule_selected->timeOfEachClassPeriod,
                'day' => $time->format("Y-m-d"),
                'prev_day' => $time->copy()->subDay()->format("Y-m-d"),
                'next_day' => $time->copy()->addDay()->format("Y-m-d"),
                'today' => date('Y-m-d')
            ]);
        } else {
            return redirect(route('schedule.index'));
        }
    }
}
