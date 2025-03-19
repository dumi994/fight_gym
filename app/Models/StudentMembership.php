<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMembership extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'month',
        'year',
        'status',
        'reminder_sent'

    ];
    protected $table = 'student_membership';
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
