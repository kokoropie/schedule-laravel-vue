<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Config;
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
        $total_row = Schedule::count();
        $limit = $request->get('limit', 20);
        if ($limit <= 0) $limit = 20;
        $page = $request->get('page', 1);
        if ($page <= 0) $page = 1;
        $total_page = ceil($total_row/$limit);
        if ($page > $total_page) $page = $total_page;
        $sort = strtoupper($request->get('sort', 'DESC'));
        $sort_by = strtolower($request->get('sort_by', 'schedule_id'));
        if ($sort_by == "dateofweek") $sort_by = "dateOfWeek";
        return Inertia::render("Schedule", [
            "schedules" => Schedule::OrderBy($sort_by, $sort)->limit($limit)->skip(($page-1)*$limit)->get()->load(["subject"]),
            "subjects" => Subject::get(),
            "numOfClassPeriodsPerDay" => json_decode(Config::find("numOfClassPeriodsPerDay")->content)[0],
            'timeOfEachClassPeriod' => json_decode(Config::find('timeOfEachClassPeriod')->content),
            "limit" => $limit,
            "page" => $page,
            "total_page" => $total_page,
            "sort" => $sort,
            "sort_by" => $sort_by,
        ]);
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

        Schedule::insert($validated);

        return redirect(route('schedule.index'));
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

        return redirect(route('schedule.index'));
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
