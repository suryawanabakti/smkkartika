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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_staffs' => \App\Models\Staff::count(),
            'total_classes' => ClassRoom::count(),
            'total_majors' => Major::count(),
            'student_attendance_today' => [
                'present' => StudentAttendance::where('date', $today)->where('status', 'present')->count(),
                'absent' => StudentAttendance::where('date', $today)->where('status', 'absent')->count(),
                'sick' => StudentAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => StudentAttendance::where('date', $today)->where('status', 'permission')->count(),
            ],
            'personnel_attendance_today' => [
                'total' => PersonnelAttendance::where('date', $today)->count(),
                'present' => PersonnelAttendance::where('date', $today)->where('status', 'present')->count(),
                'sick' => PersonnelAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => PersonnelAttendance::where('date', $today)->where('status', 'permission')->count(),
            ]
        ];

        $myAttendance = PersonnelAttendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        $schoolSettings = [
            'latitude' => (float) \App\Models\SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) \App\Models\SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) \App\Models\SchoolSetting::get('school_radius', 200),
        ];

        return view('admin.dashboard', compact('stats', 'myAttendance', 'schoolSettings'));
    }
}
