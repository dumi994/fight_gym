<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

use App\Models\User;
use App\Models\Student;

use App\Http\Controllers\TrainerController;

use App\Http\Controllers\MembershipController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/api-attendances', [AttendanceController::class, 'getAttendances']);

Route::post('/api-attendances', [AttendanceController::class, 'storeOrUpdate'])->name('api.attendances.storeOrUpdate');

Route::get('/api-attendances/students/{courseId}', [AttendanceController::class, 'getStudents']);

Route::get('/api-attendances/{courseId}', [AttendanceController::class, 'getAttendances']);
Route::get('/api/courses', [AttendanceController::class, 'getCourses']);
Route::get('/course-details/{courseId}', [AttendanceController::class, 'getCourseDetails']);
Route::post('/update-membership', [MembershipController::class, 'update'])->name('membership.update');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () {
        return view('layouts.tpl');
    });
    Route::resource('/dashboard/users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('/dashboard/courses', CourseController::class);
    Route::resource('/dashboard/trainers', TrainerController::class);
    Route::resource('/dashboard/students', StudentController::class);
    Route::post('/dashboard/assign-courses', [UserController::class, 'assignCourses'])->name('assign.courses');
    Route::resource('/dashboard/attendances', AttendanceController::class);
    Route::get('/dashboard/attendances-report', [StudentController::class, 'attendanceReport'])->name('users.attendance_report');

    /* PRESENZE */
    /* Route::resource('attendances', AttendanceController::class); */
    Route::get('/dashboard/membership', function () {
        $students = Student::get();
        return view('admin.membership.index', compact('students'));
    });
});

Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor', function () {
        $user = Auth::user();

        // Recupera i corsi associati all'utente loggato
        $courses = $user->courses;

        return view('instructor.index', compact('courses'));
    });
});
/* Route::get('/', function () {
    return view('welcome');
});
 */
Route::get('/dashboard', function () {
    $students = Student::with(['attendances', 'memberships'])->get();
    //dd($students);
    return view('dashboard', compact('students'));
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
