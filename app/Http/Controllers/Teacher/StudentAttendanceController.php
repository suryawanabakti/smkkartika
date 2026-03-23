<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $class = $user->teacher->classRoom;
        $date = Carbon::today()->toDateString();
        
        if (!$class) {
            return view('teacher.students.index', ['students' => collect(), 'class' => null, 'date' => $date]);
        }
        $students = Student::where('class_id', $class->id)
            ->with(['user', 'attendances' => function($q) use ($date) {
                $q->whereDate('date', $date);
            }])
            ->get();

        return view('teacher.students.index', compact('students', 'class', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,sick,permission,absent',
        ]);

        $date = Carbon::today()->toDateString();
        
        foreach ($request->attendance as $item) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'date' => $date
                ],
                [
                    'status' => $item['status']
                ]
            );
        }

        return back()->with('success', 'Kehadiran siswa berhasil diperbarui untuk hari ini.');
    }

    public function recap(Request $request)
    {
        $user = Auth::user();
        $class = $user->teacher->classRoom;
        
        if (!$class) {
            return view('teacher.students.recap', ['students' => collect(), 'class' => null]);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $students = Student::where('class_id', $class->id)
            ->with(['user', 'attendances' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            }])
            ->get();

        return view('teacher.students.recap', compact('students', 'class', 'month', 'year', 'daysInMonth'));
    }
}
