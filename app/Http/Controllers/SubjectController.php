<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
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
        $sort = strtoupper($request->get('sort', 'ASC'));
        $sort_by = strtolower($request->get('sort_by', 'subject_id'));
        return Inertia::render("Subject", [
            "subjects" => Subject::OrderBy($sort_by, $sort)->get()->load(['teacher']),
            "teachers" => Teacher::all(),
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

        Subject::insert($validated);

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
        $validated = $request->validated();

        $subject->update($validated);

        return redirect(route('subject.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect(route('subject.index'));
    }
}
