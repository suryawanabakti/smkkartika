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
        $classStats = [
            'total_students' => 0,
            'present_today' => 0,
            'absent_today' => 0,
        ];
        $chartData = [
            'labels' => [],
            'present' => [],
            'absent' => [],
        ];

        if ($class) {
            $studentIds = $class->students->pluck('id');
            
            // Existing stats
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

            // Chart data for last 7 days
            $attendanceData = StudentAttendance::whereIn('student_id', $studentIds)
                ->where('date', '>=', Carbon::today()->subDays(6))
                ->get()
                ->groupBy(function($item) {
                    return $item->date->toDateString();
                });

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dateString = $date->toDateString();
                $chartData['labels'][] = $date->format('d M');
                
                $dayData = $attendanceData->get($dateString, collect());
                $chartData['present'][] = $dayData->where('status', 'present')->count();
                $chartData['absent'][] = $dayData->whereIn('status', ['absent', 'sick', 'permission'])->count();
            }
        }

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        return view('teacher.dashboard', compact('myAttendance', 'class', 'classStats', 'schoolSettings', 'chartData'));
    }
}
