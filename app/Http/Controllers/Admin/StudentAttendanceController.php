<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $query = StudentAttendance::with(['student.user', 'student.classRoom.major'])
            ->whereDate('date', $date);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('class_id') && $request->class_id != '') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(20)->withQueryString();
        $classRooms = ClassRoom::with('major')->get();

        return view('admin.attendance.students', compact('attendances', 'classRooms', 'date'));
    }

    public function recap(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $classId = $request->get('class_id');
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $query = Student::with('user', 'classRoom.major');
        if ($classId) {
            $query->where('class_id', $classId);
        }
        $students = $query->get();

        $attendanceData = StudentAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['student_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $classRooms = ClassRoom::with('major')->get();

        return view('admin.attendance.students_recap', compact('students', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate', 'classRooms'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $classId = $request->get('class_id');
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $query = Student::with('user', 'classRoom.major');
        if ($classId) {
            $query->where('class_id', $classId);
        }
        $students = $query->get();

        $attendanceData = StudentAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['student_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.attendance.students_recap_pdf', compact('students', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'))->setPaper('a4', 'landscape');
        return $pdf->download('rekap-kehadiran-siswa-'.$year.'-'.$month.'.pdf');
    }
}
