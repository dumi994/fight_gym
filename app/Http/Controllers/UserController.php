<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'mainCourses', 'trainerCourses'])->get();
        return view('admin.users.index', compact('users'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'days' => 'required',
            'trainers' => 'array', // Deve essere un array
        ]);

        $course = Course::create($request->only(['title', 'level', 'days']));

        // Associa i trainer selezionati al corso
        if ($request->has('trainers')) {
            $course->users()->sync($request->trainers);
        }

        return redirect()->route('courses.index')->with('success', 'Corso creato con successo!');
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
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'days' => 'required',
            'trainers' => 'array',
        ]);

        $course->update($request->only(['title', 'level', 'days']));

        // Aggiorna i trainer associati al corso
        if ($request->has('trainers')) {
            $course->users()->sync($request->trainers);
        } else {
            $course->users()->detach(); // Rimuove tutti i trainer se nessuno è selezionato
        }

        return redirect()->route('courses.index')->with('success', 'Corso aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('delete', 'Utente eliminato con successo.');
    }

    public function assignCourses(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $user = User::findOrFail($request->input('user'));


        // Debug: Verifica i dati
        //dd($user, $request->input('courses'));

        $user->courses()->sync($request->input('courses'));

        return redirect()->back()->with('success', 'Corsi assegnati con successo all\'utente.');
    }
}
