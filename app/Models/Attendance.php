<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'user_id',
        'attendance_date',
        'status'
    ];

    /**
     * Relazione con il modello Student (Studente).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relazione con il modello Course (Corso).
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relazione con il modello User (Utente, ovvero allenatore o amministratore).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
