<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleShare;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request, string $day = ""): Response | RedirectResponse
    {
        $schedule_selected = auth()->user()->schedules()->first();

        return $this->schedule($request, $schedule_selected, $day);
    }

    public function schedule(Request $request, ?Schedule $schedule, string $day = ""): Response | RedirectResponse
    {
        $schedule_selected = $schedule;
        if ($schedule_selected) {
            $time = Carbon::today(config("app.timezone", "Asia/Ho_Chi_Minh"));

            if (!empty($day)) {
                $time = Carbon::createFromFormat("!Y-m-d", $day, config("app.timezone", "Asia/Ho_Chi_Minh"));
            }

            $nextWeek = $time->copy()->addDays(7);

            $schedules = [];
            $tmp = $schedule_selected->details()->where(function($query) use ($nextWeek, $time) {
                $query->where(function($query) use ($nextWeek, $time) {
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
                });
            })->get();
            $maxADay = $schedule_selected->numOfClassPeriodsPerDay;

            $date = $time->copy();
            for ($j = 0; $j < 7; ++$j) {
                $schedules[$j] = [];
                for ($i = 1; $i <= $maxADay; $i++) {
                    $schedules[$j][$i] = [
                        "label" => "",
                        "title" => "",
                        "rowspan" => 1,
                        "onl" => false,
                        "teacher" => "",
                        "style" => [],
                        "is_makeUp_class" => false
                    ];
                    foreach ($tmp as $schedule) {
                        if (in_array($date->format("w")+1, $schedule->dateOfWeek) && $schedule->start == $i && $date->greaterThanOrEqualTo($schedule->from) && $date->lessThanOrEqualTo($schedule->to)) {
                            $schedules[$j][$i] = [
                                "label" => $schedule->subject_id,
                                "title" => $schedule->subject->name,
                                "rowspan" => $schedule->end - $schedule->start + 1,
                                "onl" => $schedule->type === "ONLINE",
                                "teacher" => $schedule->subject->teacher->name,
                                "style" => [
                                    "color" => $schedule->subject->color_foreground,
                                    "background" => $schedule->subject->color_background
                                ],
                                "is_makeUp_class" => $schedule->is_makeUp_class
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
                'today' => date('Y-m-d'),
                'firstSchedule' => fn () => auth()->user()->schedules()->first()->schedule_id
            ]);
        } else {
            if (auth()->user()->schedules()->count()) {
                return abort(404);
            } else {
                return redirect(route('schedule.index'))->with("flash", [
                    "swal" => [
                        "data" => [
                            "icon" => "warning",
                            "title" => "You don't have any schedule",
                            "text" => "Please add a new schedule.",
                            "confirmButtonText" => "Add"
                        ]
                    ]
                ]);
            }
        }
    }

    public function share(Request $request, ScheduleShare $scheduleShare, string $day = ""): Response | RedirectResponse
    {
        $schedule_selected = $scheduleShare->schedule;
        if ($schedule_selected) {
            $time = Carbon::today(config("app.timezone", "Asia/Ho_Chi_Minh"));

            if (!empty($day)) {
                $time = Carbon::createFromFormat("!Y-m-d", $day, config("app.timezone", "Asia/Ho_Chi_Minh"));
            }

            $nextWeek = $time->copy()->addDays(7);

            $schedules = [];
            $tmp = $schedule_selected->details()->where(function($query) use ($nextWeek, $time) {
                $query->where(function($query) use ($nextWeek, $time) {
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
                });
            })->get();
            $maxADay = $schedule_selected->numOfClassPeriodsPerDay;

            $date = $time->copy();
            for ($j = 0; $j < 7; ++$j) {
                $schedules[$j] = [];
                for ($i = 1; $i <= $maxADay; $i++) {
                    $schedules[$j][$i] = [
                        "label" => "",
                        "title" => "",
                        "rowspan" => 1,
                        "onl" => false,
                        "teacher" => "",
                        "style" => [],
                        "is_makeUp_class" => false
                    ];
                    foreach ($tmp as $schedule) {
                        if (in_array($date->format("w")+1, $schedule->dateOfWeek) && $schedule->start == $i && $date->greaterThanOrEqualTo($schedule->from) && $date->lessThanOrEqualTo($schedule->to)) {
                            $schedules[$j][$i] = [
                                "label" => $schedule->subject_id,
                                "title" => $schedule->subject->name,
                                "rowspan" => $schedule->end - $schedule->start + 1,
                                "onl" => $schedule->type === "ONLINE",
                                "teacher" => $schedule->subject->teacher->name,
                                "style" => [
                                    "color" => $schedule->subject->color_foreground,
                                    "background" => $schedule->subject->color_background
                                ],
                                "is_makeUp_class" => $schedule->is_makeUp_class
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

            return Inertia::render('Share', [
                'schedule_share' => fn () => $scheduleShare,
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
            return abort(404);
        }
    }
}
