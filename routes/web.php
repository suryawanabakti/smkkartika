<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PersonnelAttendanceController;
use App\Http\Controllers\Admin\StudentAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SchoolSettingController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('majors', MajorController::class);
    Route::resource('classrooms', ClassRoomController::class);
    Route::get('/teachers/export/pdf', [TeacherController::class, 'exportPdf'])->name('teachers.pdf');
    Route::resource('teachers', TeacherController::class);
    Route::get('/students/export/pdf', [StudentController::class, 'exportPdf'])->name('students.pdf');
    Route::resource('students', StudentController::class);
    Route::get('/staffs/export/pdf', [StaffController::class, 'exportPdf'])->name('staffs.pdf');
    Route::resource('staffs', StaffController::class);
    Route::resource('admins', AdminController::class);

    Route::get('/attendance/personnel', [PersonnelAttendanceController::class, 'index'])->name('attendance.personnel');
    Route::post('/attendance/personnel', [PersonnelAttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/personnel/checkout', [PersonnelAttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/attendance/personnel/recap/pdf', [PersonnelAttendanceController::class, 'exportPdf'])->name('attendance.personnel.recap.pdf');
    Route::get('/attendance/personnel/recap', [PersonnelAttendanceController::class, 'recap'])->name('attendance.personnel.recap');
    Route::get('/attendance/students', [StudentAttendanceController::class, 'index'])->name('attendance.students');
    Route::get('/attendance/students/recap/pdf', [StudentAttendanceController::class, 'exportPdf'])->name('attendance.students.recap.pdf');
    Route::get('/attendance/students/recap', [StudentAttendanceController::class, 'recap'])->name('attendance.students.recap');

    // School Settings
    Route::get('/settings/school', [SchoolSettingController::class, 'edit'])->name('settings.school');
    Route::put('/settings/school', [SchoolSettingController::class, 'update'])->name('settings.school.update');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/attendance', [\App\Http\Controllers\Teacher\TeacherAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Teacher\TeacherAttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/checkout', [\App\Http\Controllers\Teacher\TeacherAttendanceController::class, 'checkout'])->name('attendance.checkout');

    Route::get('/students', [\App\Http\Controllers\Teacher\StudentAttendanceController::class, 'index'])->name('students.index');
    Route::post('/students', [\App\Http\Controllers\Teacher\StudentAttendanceController::class, 'store'])->name('students.store');
    Route::get('/students/recap', [\App\Http\Controllers\Teacher\StudentAttendanceController::class, 'recap'])->name('students.recap');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance.index');
});

// Staff Routes
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/attendance', [\App\Http\Controllers\Staff\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Staff\AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/checkout', [\App\Http\Controllers\Staff\AttendanceController::class, 'checkout'])->name('attendance.checkout');
});
