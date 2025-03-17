<?php

use App\Models\User;
use App\Models\Course;


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
