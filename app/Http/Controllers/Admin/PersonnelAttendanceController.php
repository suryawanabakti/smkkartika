<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonnelAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PersonnelAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $query = PersonnelAttendance::with('user')
            ->whereDate('date', $date);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(15)->withQueryString();

        return view('admin.attendance.personnel', compact('attendances', 'date'));
    }

    public function recap(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $personnel = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'teacher']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        return view('admin.attendance.personnel_recap', compact('personnel', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        
        $existing = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
        }

        PersonnelAttendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => now(),
            'status' => 'present',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Absensi masuk berhasil dicatat.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        $attendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absensi masuk hari ini.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
        }

        $attendance->update([
            'check_out_time' => now(),
        ]);

        return back()->with('success', 'Absensi pulang berhasil dicatat.');
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $personnel = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'teacher', 'staff']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.attendance.personnel_recap_pdf', compact('personnel', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'))->setPaper('a4', 'landscape');
        return $pdf->download('rekap-kehadiran-pegawai-'.$year.'-'.$month.'.pdf');
    }
}
