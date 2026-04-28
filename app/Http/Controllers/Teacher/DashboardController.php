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
        // Personal Attendance Chart data for last 7 days
        $myAttendanceHistory = PersonnelAttendance::where('user_id', $user->id)
            ->where('date', '>=', Carbon::today()->subDays(6))
            ->get()
            ->keyBy(function($item) {
                return $item->date->toDateString();
            });

        $myChartData = [
            'labels' => [],
            'values' => [],
            'statuses' => [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString();
            $myChartData['labels'][] = $date->format('d M');
            
            $dayData = $myAttendanceHistory->get($dateString);
            if (!$dayData) {
                $myChartData['values'][] = 0;
                $myChartData['statuses'][] = 'Tanpa Keterangan';
            } else {
                switch($dayData->status) {
                    case 'present': 
                        $myChartData['values'][] = 1; 
                        $myChartData['statuses'][] = 'Hadir';
                        break;
                    case 'sick': 
                        $myChartData['values'][] = 0.6; 
                        $myChartData['statuses'][] = 'Sakit';
                        break;
                    case 'permission': 
                        $myChartData['values'][] = 0.6; 
                        $myChartData['statuses'][] = 'Izin';
                        break;
                    default: 
                        $myChartData['values'][] = 0;
                        $myChartData['statuses'][] = 'Alfa';
                }
            }
        }

        if ($class) {
            $studentIds = $class->students->pluck('id');
            
            // Existing stats (keeping them in case needed for logic, but view will be cleaned)
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

        return view('teacher.dashboard', compact('myAttendance', 'class', 'classStats', 'schoolSettings', 'myChartData'));
    }
}
