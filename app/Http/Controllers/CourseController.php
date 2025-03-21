<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $courses = Course::all();
            return view('admin.courses.index', compact('courses'));
        } elseif (auth()->user()->hasRole('trainer')) {
            $trainer = auth()->user();
            $courses = $trainer->mainCourses; // Ottieni solo i corsi associati a questo trainer
            return view('trainer.courses.index', compact('courses'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Recupera solo gli utenti con ruolo "trainer"
        $trainers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        return view('admin.courses.create', compact('trainers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:courses|max:255',
            'duration' => 'nullable|date_format:H:i',
            'state' => 'required',
            'course_start' => 'nullable|date_format:H:i',
            'course_end' => 'nullable|date_format:H:i',
            'price' => 'nullable|numeric',
            'level' => 'nullable',
            'days' => 'nullable',
            'teacher_id' => 'nullable|exists:users,id',
            'trainers' => 'nullable|array',
            'trainers.*' => 'exists:users,id',
        ]);

        // Crea il corso con il trainer principale
        $course = Course::create($request->except('trainers'));

        // Assegna i trainer aggiuntivi alla tabella pivot
        if ($request->has('trainers')) {
            $course->trainers()->sync($request->trainers);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Nuovo corso aggiunto con successo!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // Recupera tutti gli utenti che hanno il ruolo "trainer"
        $trainers = User::whereHas('roles', function ($query) {
            $query->where('name', 'trainer');
        })->get();

        return view('admin.courses.edit', compact('course', 'trainers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|max:255',
            'duration' => 'nullable|date_format:H:i',
            'state' => 'required',
            'course_start' => 'nullable|date_format:H:i',
            'course_end' => 'nullable|date_format:H:i',
            'price' => 'nullable|numeric',
            'level' => 'nullable',
            'days' => 'nullable',
            'teacher_id' => 'nullable|exists:users,id',
            'trainers' => 'nullable|array',
            'trainers.*' => 'exists:users,id',
        ]);

        // Aggiorna il corso
        $course->update($request->except('trainers'));

        // Aggiorna i trainer aggiuntivi
        if ($request->has('trainers')) {
            $course->trainers()->sync($request->trainers);
        }

        return redirect()->route('courses.index')->with('success', 'Corso aggiornato con successo!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
