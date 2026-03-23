<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Major;
use App\Models\StudentAttendance;
use App\Models\PersonnelAttendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => ClassRoom::count(),
            'total_majors' => Major::count(),
            'student_attendance_today' => [
                'present' => StudentAttendance::where('date', $today)->where('status', 'present')->count(),
                'absent' => StudentAttendance::where('date', $today)->where('status', 'absent')->count(),
                'sick' => StudentAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => StudentAttendance::where('date', $today)->where('status', 'permission')->count(),
            ],
            'personnel_attendance_today' => [
                'present' => PersonnelAttendance::where('date', $today)->where('status', 'present')->count(),
                'absent' => PersonnelAttendance::where('date', $today)->where('status', 'absent')->count(),
                'sick' => PersonnelAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => PersonnelAttendance::where('date', $today)->where('status', 'permission')->count(),
            ]
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
