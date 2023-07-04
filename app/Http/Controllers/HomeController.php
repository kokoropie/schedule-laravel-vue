<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;
use Inertia\Response; 
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Config;

class HomeController extends Controller
{
    public function index($day = ""): Response
    {
        $time = time();

        if (!empty($day)) {
            $time = strtotime($day);
        }

        $nextWeek = $time + 60*60*24*7;

        $schedules = [];
        $tmp = Schedule::where(function($query) use ($nextWeek, $time) {
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
        $numOfClassPeriodsPerDay = Config::find('numOfClassPeriodsPerDay');
        $maxADay = 0;
        if ($numOfClassPeriodsPerDay) {
            $maxADay = json_decode($numOfClassPeriodsPerDay->content)[0];
        }
 
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
                ];
                foreach ($tmp as $schedule) {
                    if ($schedule->dateOfWeek - 1 == $date && $schedule->start == $i) {
                        $schedules[$j][$i] = [
                            "label" => $schedule->subject_id,
                            "title" => $schedule->subject->name,
                            "rowspan" => $schedule->end - $schedule->start + 1,
                            "onl" => $schedule->type === "ONLINE",
                            "teacher" => $schedule->subject->teacher->name,
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
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'schedules' => $schedules,
            'date' => $curDate,
            'nameOfDate' => $nameOfDate,
            'maxADay' => $maxADay,
            'timeOfEachClassPeriod' => json_decode(Config::find('timeOfEachClassPeriod')->content),
            'day' => date('Y-m-d', $time),
            'prev_day' => date('Y-m-d', $time - 24*60*60),
            'next_day' => date('Y-m-d', $time + 24*60*60),
        ]);
    }
}
