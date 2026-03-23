<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAttendance;
use App\Models\StudentAttendance;
use App\Models\SchoolSetting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $myAttendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $class = $user->teacher->classRoom;
        $classStats = null;

        if ($class) {

            $studentIds = $class->students->pluck('id');
            $classStats = [
                'total_students' => $class->students->count(),
                'present_today' => StudentAttendance::whereIn('student_id', $studentIds)
                    ->whereDate('date', $today)
                    ->where('status', 'present')
                    ->count(),
                'absent_today' => StudentAttendance::whereIn('student_id', $studentIds)
                    ->whereDate('date', $today)
                    ->whereIn('status', ['absent', 'sick', 'permission'])
                    ->count(),
            ];
        }

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        return view('teacher.dashboard', compact('myAttendance', 'class', 'classStats', 'schoolSettings'));
    }
}
