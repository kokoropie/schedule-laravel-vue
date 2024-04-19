<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function import (Request $request): RedirectResponse {
        $user = auth()->user();
        if ($user) {
            Validator::make($request->all(), [
                "file" => "required|file|mimes:json"
            ], [
                "file.required" => "Please choose file",
                "file.file" => "Please choose file",
                "file.mimes" => "Please choose .JSON file"
            ])->validate();
            $path = $request->file("file")->storeAs(
                'upload/' . $user->user_id,
                time() . "." . $request->file("file")->extension(),
                "public"
            );
            if (!$path) {
                return redirect(route('profile.edit'))->with("flash", [
                    "swal" => [
                        "data" => [
                            "icon" => "error",
                            "title" => "Import data failed",
                            "confirmButtonText" => "OK"
                        ]
                    ]
                ]);
            }
            $file = Storage::drive("public")->get($path);
            Storage::drive("public")->delete($path);
            foreach ($user->schedules as $schedules) {
                $schedules->delete();
            }
            foreach ($user->teachers as $teacher) {
                $teacher->delete();
            }
            $json = json_decode($file, false);
            $schedules = [];
            foreach ($json->schedules as $schedule_id => $schedule) {
                $schedules[$schedule_id] = $user->schedules()->create([
                    "name" => $schedule->name,
                    "numOfClassPeriodsPerDay" => $schedule->numOfClassPeriodsPerDay,
                    "timeOfEachClassPeriod" => $schedule->timeOfEachClassPeriod,
                ]);
                if ($schedule->share) {
                    $schedules[$schedule_id]->share()->create([]);
                }
            }
            foreach ($json->teachers as $teacher) {
                $newTeacher = $user->teachers()->create([
                    "name" => $teacher->name
                ]);
                foreach ($teacher->subjects as $subject_id => $subject) {
                    $newSubject = $user->subjects()->create([
                        "subject_id" => $subject_id,
                        "teacher_id" => $newTeacher->teacher_id,
                        "name" => $subject->name,
                        "credits" => $subject->credits,
                        "color_background" => $subject->color_background,
                        "color_foreground" => $subject->color_foreground
                    ]);
                    foreach ($subject->schedules as $schedule) {
                        $schedules[$schedule->schedule_id]->details()->create([
                            "subject_id" => $newSubject->subject_id,
                            "start" => $schedule->start,
                            "end" => $schedule->end,
                            "from" => $schedule->from,
                            "to" => $schedule->to,
                            "dateOfWeek" => $schedule->dateOfWeek,
                            "type" => $schedule->type,
                            "is_makeUp_class" => $schedule->is_makeUp_class,
                        ]);
                    }
                }
            }
            if (isset($json->config)) {
                $config = $json->config;
                if (isset($config->primary_schedule_id)) {
                    $config->primary_schedule_id = $schedules[$config->primary_schedule_id]->schedule_id;
                }
                $user->config = $config;
            } else {
                $user->config = [];
            }

            return redirect(route('profile.edit'))->with("flash", [
                "swal" => [
                    "data" => [
                        "icon" => "success",
                        "title" => "Import data succeed",
                        "confirmButtonText" => "OK"
                    ]
                ]
            ]);
        }
        return abort(403);
    }

    public function export (): Response {
        $user = auth()->user();
        if ($user) {
            $data = [
                "schedules" => [],
                "teachers" => [],
                "config" => (object) []
            ];
            foreach ($user->schedules as $schedule) {
                $data["schedules"][$schedule->schedule_id] = [
                    "name" => $schedule->name,
                    "numOfClassPeriodsPerDay" => $schedule->numOfClassPeriodsPerDay,
                    "timeOfEachClassPeriod" => $schedule->timeOfEachClassPeriod,
                    "share" => $schedule->share()->exists(),
                ];
            }
            foreach ($user->teachers as $teacher) {
                $data["teachers"][$teacher->teacher_id] = [
                    "name" => $teacher->name,
                    "subjects" => []
                ];
                foreach ($teacher->subjects as $subject) {
                    $data["teachers"][$teacher->teacher_id]["subjects"][$subject->subject_id] = [
                        "name" => $subject->name,
                        "credits" => $subject->credits,
                        "color_background" => $subject->color_background,
                        "color_foreground" => $subject->color_foreground,
                        "schedules" => []
                    ];
                    foreach ($subject->schedules as $schedule) {
                        $data["teachers"][$teacher->teacher_id]["subjects"][$subject->subject_id]["schedules"][$schedule->schedule_detail_id] = [
                            "schedule_id" => $schedule->schedule_id,
                            "start" => $schedule->start,
                            "end" => $schedule->end,
                            "from" => $schedule->from,
                            "to" => $schedule->to,
                            "dateOfWeek" => $schedule->dateOfWeek,
                            "type" => $schedule->type,
                            "is_makeUp_class" => $schedule->is_makeUp_class,
                        ];
                    }
                }
            }
            $data["config"] = $user->config;
            $name = parse_url(request()->root())['host'].'-'.now().'.json';
            return response($data, 200, [
                'Content-Disposition' => 'attachment; filename="'.$name.'"'
            ]);
        }
        return abort(403);
    }
}
