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

        $personnel = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'teacher', 'staff']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        return view('admin.attendance.personnel_recap', compact('personnel', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'));
    }

    public function updateRecap(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,sick,permission,absent',
            'description' => 'nullable|string'
        ]);

        // Check if existing record is 'present'
        $existing = PersonnelAttendance::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->first();

        if ($existing && $existing->status === 'present') {
            return back()->with('error', 'Status Hadir tidak dapat diubah.');
        }

        $attendance = PersonnelAttendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            ['status' => $request->status, 'description' => $request->description]
        );

        return back()->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:present,sick,permission,absent',
            'description' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        $existing = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        // Block attendance if past check-out time
        $minCheckOut = \App\Models\SchoolSetting::get('min_check_out_time', '15:00');
        if (now()->format('H:i') > $minCheckOut) {
            return back()->with('error', 'Gagal absen. Waktu absensi hari ini telah berakhir (sudah lewat jam pulang pukul ' . $minCheckOut . ').');
        }

        $status = $request->status;

        if ($status === 'present') {
            // Radius check for 'present'
            $schoolLat = (float) \App\Models\SchoolSetting::get('school_latitude', -5.1436);
            $schoolLng = (float) \App\Models\SchoolSetting::get('school_longitude', 119.4667);
            $schoolRadius = (int) \App\Models\SchoolSetting::get('school_radius', 200);

            $distance = $this->calculateDistance($request->latitude, $request->longitude, $schoolLat, $schoolLng);

            if ($distance > $schoolRadius) {
                return back()->with('error', 'Gagal absen Hadir. Anda berada di luar radius sekolah.');
            }
        }

        PersonnelAttendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => now(),
            'status' => $status,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Absensi berhasil dicatat.');
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
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

        $personnel = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'teacher', 'staff']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.attendance.personnel_recap_pdf', compact('personnel', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'))->setPaper('a4', 'landscape');
        return $pdf->download('rekap-kehadiran-pegawai-' . $year . '-' . $month . '.pdf');
    }
}
