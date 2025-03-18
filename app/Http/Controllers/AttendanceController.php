<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $attendances = Attendance::with('student', 'course')->get();
        $corsi = Course::all(); // Recupera tutti i corsi
        return view('admin.attendances.index', compact('attendances', 'corsi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Visualizza il form per creare una nuova presenza
        $courses = Course::with('students')->get();
        return view('attendances.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(Request $request)
    {
        // Log dei dati ricevuti per debugging
        Log::info('📥 Dati ricevuti:', $request->all());

        $request->validate([
            'attendance' => 'array',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
        ]);

        // Ottieni tutti gli studenti iscritti al corso
        $students = Student::whereHas('courses', function ($query) use ($request) {
            $query->where('courses.id', $request->course_id);
        })->get();

        foreach ($students as $student) {
            $studentId = $student->id;
            $status = $request->attendance[$studentId] ?? 'absent'; // Se non è stato inviato, assume "absent"

            Log::info("✅ Gestione presenza per student_id: $studentId, course_id: {$request->course_id}, status: $status");

            if ($status === 'absent') {
                // Se è assente, elimina il record se esiste
                Attendance::where([
                    'student_id' => $studentId,
                    'course_id' => $request->course_id,
                    'attendance_date' => $request->attendance_date,
                ])->delete();
            } else {
                // Se è presente, crea o aggiorna il record
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'course_id' => $request->course_id,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'status' => $status,
                        'user_id' => auth()->id(),
                    ]
                );
            }
        }

        return response()->json(['message' => '✅ Presenze salvate con successo!'], 200);
    }


    /*  public function store(Request $request)
    {
        try {
            // Log dei dati ricevuti
            \Log::info('Dati ricevuti:', $request->all());

            // Validazione
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'attendance' => 'required|array',
                'attendance.*' => 'in:present,absent',
                'attendance_date' => 'required|date'
            ]);

            $userId = Auth::id(); // Ottieni l'ID dell'utente autenticato

            foreach ($request->attendance as $studentId => $status) {
                \Log::info("Salvataggio presenza per student_id: $studentId, course_id: {$request->course_id}, user_id: $userId, status: $status");

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'course_id' => $request->course_id,
                        'attendance_date' => $request->attendance_date
                    ],
                    [
                        'status' => $status,
                        'user_id' => $userId, // Aggiungi user_id
                    ]
                );
            }

            return response()->json(['message' => 'Presenze salvate con successo!']);
        } catch (\Exception $e) {
            \Log::error('Errore nel salvataggio delle presenze: ' . $e->getMessage());
            return response()->json(['error' => 'Errore nel server: ' . $e->getMessage()], 500);
        }
    }
 */

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
    public function getAttendances($courseId, Request $request)
    {
        $date = $request->query('date');

        // Recupera SOLO gli studenti iscritti al corso selezionato
        $students = \App\Models\Student::whereHas('courses', function ($query) use ($courseId) {
            $query->where('courses.id', $courseId);
        })->get();

        // Recupera SOLO le presenze di quel corso e di quella data
        $attendances = Attendance::where('course_id', $courseId)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id'); // Associa le presenze allo student_id

        return response()->json([
            'students' => $students->map(function ($student) use ($attendances) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'status' => $attendances->has($student->id) ? $attendances[$student->id]->status : 'absent'
                ];
            }),
        ]);
    }

    /*   public function getAttendances($courseId, Request $request)
    {
        $date = $request->query('date', now()->toDateString()); // Data selezionata nel calendario

        // Otteniamo gli studenti iscritti al corso
        $students = Student::whereHas('courses', function ($query) use ($courseId) {
            $query->where('courses.id', $courseId);
        })->get();

        // Recuperiamo le presenze esistenti per quel corso e quella data
        $attendances = Attendance::where('course_id', $courseId)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        // Costruiamo la risposta JSON con lo stato delle presenze
        $studentsData = $students->map(function ($student) use ($attendances) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'status' => $attendances->has($student->id) ? $attendances[$student->id]->status : null
            ];
        });

        return response()->json(['students' => $studentsData]);
    } */


    public function getCourseDetails($courseId)
    {
        $course = Course::with('students')->find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Corso non trovato'], 404);
        }

        $days = explode('|', $course->days); // Converte i giorni in array

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'days' => $days
            ],
            'students' => $course->students
        ]);
    }



    public function getCourses(Request $request)
    {
        $courses = Course::with('students')->where('state', 'active')->get();
        $events = [];
        $dayMapping = [
            'lunedi' => 'Monday',
            'martedi' => 'Tuesday',
            'mercoledi' => 'Wednesday',
            'giovedi' => 'Thursday',
            'venerdi' => 'Friday',
            'sabato' => 'Saturday',
            'domenica' => 'Sunday'
        ];

        $startDate = new DateTime($request->query('start', date('Y-m-d')));
        $endDate = new DateTime($request->query('end', date('Y-m-d', strtotime('+1 month'))));

        foreach ($courses as $course) {
            $days = explode('|', $course->days);
            foreach ($days as $day) {
                $day = trim(strtolower($day));

                if (!isset($dayMapping[$day])) {
                    continue;
                }

                $date = clone $startDate;
                while ($date <= $endDate) {
                    if ($date->format('l') === $dayMapping[$day]) {
                        $events[] = [
                            'id' => $course->id,
                            'title' => $course->title ?? 'Senza Nome',
                            'start' => $date->format('Y-m-d'),
                            'end' => $date->format('Y-m-d'),
                            'allDay' => true,
                            'backgroundColor' => '#007bff',
                            'borderColor' => '#007bff',
                            'description' => $course->description ?? 'Nessuna descrizione',
                            'level' => $course->level ?? 'Non specificato',
                            'students' => $course->students->map(function ($student) {
                                return [
                                    'id' => $student->id,
                                    'name' => $student->first_name . ' ' . $student->last_name
                                ];
                            })->toArray()
                        ];
                    }
                    $date->modify('+1 day');
                }
            }
        }

        return response()->json($events);
    }
    /*    public function getCourses(Request $request)
    {
        $courses = Course::with('students')->where('state', 'active')->get();
        $events = [];
        $dayMapping = [
            'lunedi' => 'Monday',
            'martedi' => 'Tuesday',
            'mercoledi' => 'Wednesday',
            'giovedi' => 'Thursday',
            'venerdi' => 'Friday',
            'sabato' => 'Saturday',
            'domenica' => 'Sunday'
        ];

        $startDate = new DateTime($request->query('start', date('Y-m-d')));
        $endDate = new DateTime($request->query('end', date('Y-m-d', strtotime('+1 month'))));

        foreach ($courses as $course) {
            $days = explode('|', $course->days);
            foreach ($days as $day) {
                $day = trim(strtolower($day));

                if (!isset($dayMapping[$day])) {
                    continue;
                }

                $date = clone $startDate;
                while ($date <= $endDate) {
                    if ($date->format('l') === $dayMapping[$day]) {
                        $events[] = [
                            'id' => $course->id,
                            'title' => $course->title ?? 'Senza Nome',
                            'start' => $date->format('Y-m-d'),
                            'end' => $date->format('Y-m-d'),
                            'allDay' => true,
                            'backgroundColor' => '#007bff',
                            'borderColor' => '#007bff',
                            'description' => $course->description ?? 'Nessuna descrizione',
                            'level' => $course->level ?? 'Non specificato',
                            'students' => $course->students->map(function ($student) {
                                return [
                                    'id' => $student->id,
                                    'name' => $student->first_name . ' ' . $student->last_name
                                ];
                            })->toArray()
                        ];
                    }
                    $date->modify('+1 day');
                }
            }
        }

        return response()->json($events);
    } */
}
