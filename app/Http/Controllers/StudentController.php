<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;


use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('courses')->get();
        return view('admin.students.index', compact('students'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = null;
        $users = null;

        if (auth()->user()->hasRole('admin')) {
            $users = User::all();
            $courses = Course::all();
            return view('admin.students.create', compact('users', 'courses'));
        } elseif (auth()->user()->hasRole('trainer')) {
            $trainer = auth()->user();
            $courses = $trainer->mainCourses;
            $users = User::all(); // O qualsiasi altra logica per ottenere gli utenti necessari
        }
        return view('trainer.students.create', compact('users', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validazione dei dati
        /*  dd($request->all()); */
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:m,f,other',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'required|email|unique:students,email',
            'medical_certificate_path' => 'nullable|file|mimes:pdf|max:2048',
            'enrollment_date' => 'nullable|date',
            'membership_status' => 'nullable|in:active,inactive,pending',
            'courses' => 'nullable|array', // Deve essere un array di ID
            'courses.*' => 'exists:courses,id', // Ogni ID deve esistere nella tabella `courses`
        ]);

        // Creazione dello studente
        $student = Student::create($request->only([
            'first_name',
            'last_name',
            'birth_date',
            'gender',
            'address',
            'phone_number',
            'email',
            'enrollment_date',
            'membership_status'
        ]));

        // Associare i corsi allo studente (se presenti)
        if ($request->has('courses')) {
            $student->courses()->attach($request->courses);
        }

        // Creare cartella per i documenti dello studente
        $directory = "public/students/{$student->id}";
        Storage::makeDirectory($directory);

        // Gestione del file del certificato medico
        if ($request->hasFile('medical_certificate_path')) {
            $currentYear = date('Y');
            $originalExtension = $request->file('medical_certificate_path')->getClientOriginalExtension();
            $fileName = "{$student->first_name}_{$student->last_name}_certificate_{$currentYear}.{$originalExtension}";
            $filePath = $request->file('medical_certificate_path')->storeAs($directory, $fileName);
            $student->medical_certificate_path = $filePath;
            $student->save(); // Salva il percorso aggiornato
        }
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('students.index')->with('success', 'Nuovo allievo aggiunto.');
        } elseif (auth()->user()->hasRole('trainer')) {
            return redirect()->route('trainer-dashboard.index')->with('success', 'Nuovo allievo aggiunto.');
        }

        // Come fallback, redirigi altrove
        return redirect()->route('home')->with('error', 'Operazione non valida.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['attendances', 'memberships', 'courses']);
        //dd($student->courses);
        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.show', compact('student'));
        } elseif (auth()->user()->hasRole('trainer')) {
            return view('trainer.students.show', compact('student'));
        }
        /* return view('admin.students.show', compact('student')); */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::all();
        $courses = Course::all();
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student', 'users', 'courses'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:m,f,other',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'medical_certificate_path' => 'nullable|file|mimes:pdf|max:2048',
            'enrollment_date' => 'nullable|date',
            'membership_status' => 'nullable|in:active,inactive,pending',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        // Aggiornamento dei campi base
        $student->update($request->except('medical_certificate_path', 'courses'));

        // Aggiornare i corsi associati
        if ($request->has('courses')) {
            $student->courses()->sync($request->courses);
        } else {
            $student->courses()->detach(); // Rimuove tutti i corsi se nessuno è stato selezionato
        }

        // Gestione dell'aggiornamento del certificato medico
        if ($request->hasFile('medical_certificate_path')) {
            $directory = "public/students/{$student->id}";

            // Se esiste un vecchio certificato, lo cancelliamo
            if ($student->medical_certificate_path) {
                Storage::delete($student->medical_certificate_path);
            }

            $currentYear = date('Y');
            $originalExtension = $request->file('medical_certificate_path')->getClientOriginalExtension();
            $fileName = "{$student->first_name}_{$student->last_name}_certificate_{$currentYear}.{$originalExtension}";

            Storage::makeDirectory($directory);
            $filePath = $request->file('medical_certificate_path')->storeAs($directory, $fileName);
            $student->medical_certificate_path = $filePath;
        }

        $student->save();

        return redirect()->route('students.index')->with('success', 'Allievo aggiornato con successo!');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Controlla se lo studente ha un certificato medico e lo elimina
        if ($student->medical_certificate_path) {
            // Ottieni la directory basata sull'ID dello studente
            $directory = "public/{$student->id}";

            // Elimina il certificato medico
            Storage::delete($student->medical_certificate_path);

            // Se la directory è vuota dopo la cancellazione del file, elimina anche la directory
            if (Storage::files($directory) === [] && Storage::directories($directory) === []) {
                Storage::deleteDirectory($directory);
            }
        }

        // Elimina il record dello studente dal database
        $student->delete();

        return redirect()->route('students.index')->with('delete', 'Allievo eliminato con successo!');
    }

    public function attendanceReport()
    {
        $students = Student::with('attendances')->get(); // Carica le presenze
        return view('admin.students.attendance-report', compact('students'));
    }
}
