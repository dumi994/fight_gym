<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentMembership;
use App\Models\Course;
use App\Models\Attendance;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'address',
        'phone_number',
        'email',
        'medical_certificate_path',
        'enrollment_date',
        'membership_status',
    ];

    // Relazione molti a molti con i corsi
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
    public function memberships()
    {
        return $this->hasMany(StudentMembership::class, 'student_id');
    }
}
