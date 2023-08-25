<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Teacher\StoreRequest;
use App\Http\Requests\Teacher\UpdateRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Teacher::class);

        $sort = strtoupper($request->get('sort', 'ASC'));
        $sort_by = strtolower($request->get('sort_by', 'teacher_id'));
        $limit = $request->get('limit', 10);
        if ($limit <= 0) $limit = 10;
        return Inertia::render("Teacher", [
            "teachers" => fn () => auth()->user()->teachers()->OrderBy($sort_by, $sort)->withCount(['subjects'])->paginate($limit)->onEachSide(2)->withQueryString(),
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
        $this->authorize('create', Teacher::class);

        $validated = $request->validated();

        auth()->user()->teachers()->create($validated);

        return redirect(route('teacher.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Teacher $teacher): RedirectResponse
    {
        $this->authorize('update', $teacher);

        $validated = $request->validated();

        $teacher->update($validated);

        return redirect(route('teacher.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->authorize('delete', $teacher);

        $teacher->delete();

        return redirect(route('teacher.index'));
    }
}
