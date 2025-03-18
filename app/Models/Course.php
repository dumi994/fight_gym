<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'duration',
        'state',
        'course_start',
        'course_end',
        'price',
        'level',
        'days',
        'teacher_id'
    ];

    // Relazione molti-a-molti con gli studenti
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'course_id');
    }
    /* public function trainers()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'trainer');
            });
    } */
    public function mainTrainer()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relazione many-to-many con i trainer aggiuntivi
    public function trainers()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
}
