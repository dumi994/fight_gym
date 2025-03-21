<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\User;

use App\Models\Student;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*  $trainers = Trainer::all(); */
        $trainer = auth()->user(); // Supponendo che il trainer sia l'utente loggato
        $courses = $trainer->mainCourses;
        $students = Student::whereHas('courses', function ($query) use ($courses) {
            $query->whereIn('courses.id', $courses->pluck('id'));
        })->get();

        return view('trainer.index', compact('students'));
    }
    /* public function getCoursesForTrainer()
    {
        $trainer = auth()->user();
        $courses = $trainer->mainCourses;
        return response()->json($courses);
    } */
    public function getCoursesForTrainer()
    {
        $trainer = auth()->user();
        $courses = $trainer->mainCourses;

        $events = [];

        foreach ($courses as $course) {
            $days = explode('|', $course->days);
            foreach ($days as $day) {
                $nextDate = $this->getNextDayDate($day);

                if ($nextDate) {
                    $events[] = [
                        'id' => $course->id,
                        'title' => $course->title,
                        'start' => $nextDate->format('Y-m-d'),
                        'backgroundColor' => '#007bff',
                        'borderColor' => '#007bff'
                    ];
                }
            }
        }

        return response()->json($events);
    }

    // Funzione per calcolare la prossima data
    private function getNextDayDate($day)
    {
        $dayMap = [
            'lunedi' => 1,
            'martedi' => 2,
            'mercoledi' => 3,
            'giovedi' => 4,
            'venerdi' => 5,
            'sabato' => 6,
            'domenica' => 0
        ];

        if (!isset($dayMap[$day])) return null;

        $today = now();
        $diff = ($dayMap[$day] - $today->dayOfWeek + 7) % 7;
        return $today->addDays($diff);
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
