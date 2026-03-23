<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('student.attendance.index', compact('student', 'attendances', 'month', 'year'));
    }
}
