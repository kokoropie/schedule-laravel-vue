<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Subject\StoreRequest;
use App\Http\Requests\Subject\UpdateRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        \Gate::authorize('viewAny', Subject::class);

        $sort = strtoupper($request->get('sort', 'ASC'));
        $sort_by = strtolower($request->get('sort_by', 'subject_id'));
        $limit = $request->get('limit', 10);
        if ($limit <= 0)
            $limit = 10;
        return Inertia::render("Subject", [
            "subjects" => fn() => auth()->user()->subjects()->OrderBy($sort_by, $sort)->paginate($limit)->onEachSide(2)->withQueryString(),
            "teachers" => fn() => auth()->user()->teachers,
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
        \Gate::authorize('create', Subject::class);

        $validated = $request->validated();

        auth()->user()->subjects()->create($validated);

        return redirect(route('subject.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Subject $subject): RedirectResponse
    {
        \Gate::authorize('update', $subject);

        $validated = $request->validated();

        auth()->user()->subjects()->where('subject_id', $subject->subject_id)->update($validated);

        return redirect(route('subject.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        \Gate::authorize('delete', $subject);

        auth()->user()->subjects()->where('subject_id', $subject->subject_id)->delete();

        return redirect(route('subject.index'));
    }
}
