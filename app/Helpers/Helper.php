<?php

use App\Models\User;
use App\Models\Course;
use Carbon\Carbon;

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
  }
}
