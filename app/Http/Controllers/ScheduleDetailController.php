<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleDetail\UpdateRequest;
use App\Http\Requests\ScheduleDetail\StoreRequest;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;

class ScheduleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreRequest $request, Schedule $schedule)
    {
        $this->authorize("create", [$schedule, ScheduleDetail::class]);

        $validated = $request->validated();

        if ($validated["from"] === $validated["to"]) {
            $validated["dateOfWeek"] = [date("w", strtotime($validated["from"]))];
        }


        $schedule->details()->create($validated);

        return redirect(route('schedule.show', $schedule));
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleDetail $detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleDetail $detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Schedule $schedule, ScheduleDetail $detail)
    {
        $this->authorize("update", [$schedule, $detail]);

        $validated = $request->validated();

        $detail->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, ScheduleDetail $detail)
    {
        $this->authorize("delete", [$schedule, $detail]);

        $detail->delete();
        return redirect()->back();
    }
}
