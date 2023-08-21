<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Schedule\StoreRequest;
use App\Http\Requests\Schedule\UpdateRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $id = $request->get('id', null);
        $sort = strtoupper($request->get('sort', 'DESC'));
        $sort_by = strtolower($request->get('sort_by', 'schedule_detail_id'));
        if ($sort_by == "dateofweek") $sort_by = "dateOfWeek";
        if (!$id) {
            $schedule = auth()->user()->schedules()->first();
        } else {
            if (auth()->user()->schedules()->where("schedule_id", $id)->exists()) {
                $schedule = auth()->user()->schedules()->where("schedule_id", $id)->first();
            } else {
                $schedule = auth()->user()->schedules()->first();
            }
        }
        if ($schedule) {
            $details = $schedule->details();
            $total_row = $details->count();
            $limit = $request->get('limit', 20);
            if ($limit <= 0) $limit = 20;
            $page = $request->get('page', 1);
            if ($page <= 0) $page = 1;
            $total_page = ceil($total_row/$limit);
            if ($page > $total_page) $page = $total_page;
            return Inertia::render("Schedule", [
                "schedules" => fn () => auth()->user()->schedules,
                "schedule_selected" => fn () => $schedule,
                "schedule_details" => fn () => $details->OrderBy($sort_by, $sort)->limit($limit)->skip(($page-1)*$limit)->get()->load(["subject"]),
                "subjects" => fn () => auth()->user()->subjects,
                "numOfClassPeriodsPerDay" => fn () => $schedule->numOfClassPeriodsPerDay,
                'timeOfEachClassPeriod' => fn () => $schedule->timeOfEachClassPeriod,
                "limit" => $limit,
                "page" => $page,
                "total_page" => $total_page,
                "sort" => $sort,
                "sort_by" => $sort_by,
            ]);
        } else {
            return Inertia::render("Schedule", [
                "schedules" => fn () => auth()->user()->schedules,
                "schedule_selected" => fn () => null,
                "schedule_details" => fn () => [],
                "subjects" => fn () => auth()->user()->subjects,
                "numOfClassPeriodsPerDay" => fn () => 0,
                'timeOfEachClassPeriod' => fn () => [],
                "limit" => 0,
                "page" => 0,
                "total_page" => 0,
                "sort" => $sort,
                "sort_by" => $sort_by
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // dd($validated);

        $new = auth()->user()->schedules()->create($validated);

        return redirect(route('schedule.index', ["id" => $new]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validated();

        $schedule->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect(route('schedule.index'));
    }
}
