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
            $file = Storage::drive("public")->get($path);
            Storage::drive("public")->delete($path);
            foreach ($user->schedules as $schedules) {
                $schedules->delete();
            }
            foreach ($user->teachers as $teacher) {
                $teacher->delete();
            }
            $json = json_decode($file);
            $schedules = [];
            foreach ($json->schedules as $schedule_id => $schedule) {
                $schedules[$schedule_id] = $user->schedules()->create((array) $schedule);
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
                "teachers" => []
            ];
            foreach ($user->schedules as $schedule) {
                $data["schedules"][$schedule->schedule_id] = [
                    "name" => $schedule->name,
                    "numOfClassPeriodsPerDay" => $schedule->numOfClassPeriodsPerDay,
                    "timeOfEachClassPeriod" => $schedule->timeOfEachClassPeriod
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
            return response($data, 200, [
                'Content-Disposition' => 'attachment; filename="'.now().'.json"'
            ]);
        }
        return abort(403);
    }
}
