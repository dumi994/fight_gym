<?php

use App\Models\User;
use App\Models\Course;
use Carbon\Carbon;
use App\Models\Student;

function getMonthName($month)
{
  return Carbon::create(null, $month)->locale('it')->translatedFormat('F');
}

function getSidebarData($type)
{

  switch ($type) {
    case 'users':
      $users = User::all();
      return $users;
      break;
    case 'courses':
      $courses = Course::all();
      return $courses;
      break;
    case 'students':
      $students = Student::all();
      return $students;
      break;
    case 'courseStudents':
      $trainer = auth()->user(); // Supponendo che il trainer sia l'utente loggato
      $courses = $trainer->mainCourses;
      $students = Student::whereHas('courses', function ($query) use ($courses) {
        $query->whereIn('courses.id', $courses->pluck('id'));
      })->get();
      return $students;
      break;
    case 'courseTrainer':
      $trainer = auth()->user();
      $courses = $trainer->mainCourses; // Ottieni solo i corsi associati a questo trainer

      return $courses;
      break;
  }
}
