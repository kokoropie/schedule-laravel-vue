<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Schedule\StoreRequest;
use App\Http\Requests\Schedule\UpdateRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response | RedirectResponse
    {
        $this->authorize('viewAny', Schedule::class);

        if (auth()->user()->schedules()->count() > 0) {
            $config = auth()->user()->config;
            if (auth()->user()->schedules()->where("schedule_id", $config["primary_schedule_id"] ?? 0)->doesntExist()) {
                $schedule_selected = auth()->user()->schedules()->first();
            } else {
                $schedule_selected = auth()->user()->schedules()->where("schedule_id", $config["primary_schedule_id"])->first();
            }
            return redirect(route('schedule.show', $schedule_selected));
        }
        return Inertia::render("Schedule", [
            "schedules" => fn () => [],
            "schedule_selected" => fn () => null,
            "schedule_details" => fn () => [],
            "subjects" => fn () => auth()->user()->subjects,
            "numOfClassPeriodsPerDay" => fn () => 0,
            'timeOfEachClassPeriod' => fn () => [],
            "limit" => 0,
            "page" => 0,
            "total_page" => 0,
            "sort" => null,
            "sort_by" => null
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
        $this->authorize('create', Schedule::class);

        $validated = $request->validated();

        $new = auth()->user()->schedules()->create($validated);

        return redirect(route('schedule.show', $new));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Schedule $schedule): Response
    {
        $this->authorize('view', $schedule);

        $sort = strtoupper($request->get('sort', 'DESC'));
        $sort_by = strtolower($request->get('sort_by', 'schedule_detail_id'));
        if ($sort_by == "dateofweek") $sort_by = "dateOfWeek";
        $details = $schedule->details();
        $limit = $request->get('limit', 10);
        if ($limit <= 0) $limit = 10;

        return Inertia::render("Schedule", [
            "schedules" => fn () => auth()->user()->schedules,
            "schedule_selected" => fn () => $schedule,
            "schedule_share" => fn () => $schedule->share,
            "subjects" => fn () => auth()->user()->subjects,
            "numOfClassPeriodsPerDay" => fn () => $schedule->numOfClassPeriodsPerDay,
            'timeOfEachClassPeriod' => fn () => $schedule->timeOfEachClassPeriod,
            "sort" => $sort,
            "sort_by" => $sort_by,
            "schedule_details" => fn () => $details->OrderBy($sort_by, $sort)->paginate($limit)->onEachSide(2)->withQueryString(),
            "primary_schedule_id" => fn () => auth()->user()->config["primary_schedule_id"] ?? 0,
        ]);
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
        $this->authorize('update', $schedule);

        $validated = $request->validated();

        $schedule->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return redirect(route('schedule.index'));
    }

    public function share(Schedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        if ($schedule->share) {
            $schedule->share()->delete();
        } else {
            $schedule->share()->create([]);
        }

        return redirect()->back();
    }

    public function primary(Schedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $user = auth()->user();
        $config = $user->config;
        $config["primary_schedule_id"] = $schedule->schedule_id;
        $user->config = $config;

        return redirect()->back();
    }
}
