<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        
        $todayAttendance = StudentAttendance::where('student_id', $student->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $monthlyStats = [
            'present' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'present')
                ->count(),
            'sick' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'sick')
                ->count(),
            'permission' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'permission')
                ->count(),
            'absent' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'absent')
                ->count(),
        ];

        return view('student.dashboard', compact('student', 'todayAttendance', 'monthlyStats'));
    }
}
