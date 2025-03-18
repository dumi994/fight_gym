<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use  Hash;
use Spatie\Permission\Models\Role;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name', // Verifica che il ruolo esista
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assegna il ruolo (singolo)
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Utente creato con successo!');
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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string', // Accettiamo un solo ruolo come stringa
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Assicuriamoci che venga passato un array
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Utente aggiornato con successo!');
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
