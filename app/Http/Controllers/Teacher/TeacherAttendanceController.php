<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAttendance;
use App\Models\SchoolSetting;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $attendances = PersonnelAttendance::where('user_id', $user->id)
            ->latest('date')
            ->paginate(10);
            
        $todayAttendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        $workHours = [
            'min_check_in' => SchoolSetting::get('min_check_in_time', '07:00'),
            'min_check_out' => SchoolSetting::get('min_check_out_time', '15:00'),
        ];

        return view('teacher.attendance.index', compact('attendances', 'todayAttendance', 'schoolSettings', 'workHours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $existing = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
        }

        // Validate minimum check-in time
        $minCheckIn = SchoolSetting::get('min_check_in_time', '07:00');
        $now = now();
        $minTime = Carbon::today()->setTimeFromTimeString($minCheckIn);
        
        if ($now->lt($minTime)) {
            return back()->with('error', 'Belum waktunya absen masuk. Jam masuk minimal pukul ' . $minCheckIn . ' WITA.');
        }

        // Validate distance to school
        $schoolLat = (float) SchoolSetting::get('school_latitude', -5.1436);
        $schoolLng = (float) SchoolSetting::get('school_longitude', 119.4667);
        $schoolRadius = (int) SchoolSetting::get('school_radius', 200);

        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = $this->haversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

        if ($distance > $schoolRadius) {
            return back()->with('error', 'Anda berada di luar radius sekolah (' . round($distance) . 'm dari sekolah, batas: ' . $schoolRadius . 'm). Silakan mendekat ke area sekolah.');
        }

        PersonnelAttendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => $now,
            'status' => 'present',
            'latitude' => $userLat,
            'longitude' => $userLng,
        ]);

        return back()->with('success', 'Absensi masuk berhasil dicatat pukul ' . $now->format('H:i') . '. Jarak: ' . round($distance) . 'm.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::user();
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

        // Validate minimum check-out time
        $minCheckOut = SchoolSetting::get('min_check_out_time', '15:00');
        $now = now();
        $minTime = Carbon::today()->setTimeFromTimeString($minCheckOut);

        if ($now->lt($minTime)) {
            return back()->with('error', 'Belum waktunya absen pulang. Jam pulang minimal pukul ' . $minCheckOut . ' WITA.');
        }

        // Validate distance to school
        $schoolLat = (float) SchoolSetting::get('school_latitude', -5.1436);
        $schoolLng = (float) SchoolSetting::get('school_longitude', 119.4667);
        $schoolRadius = (int) SchoolSetting::get('school_radius', 200);

        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = $this->haversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

        if ($distance > $schoolRadius) {
            return back()->with('error', 'Anda berada di luar radius sekolah (' . round($distance) . 'm, batas: ' . $schoolRadius . 'm). Silakan mendekat ke area sekolah.');
        }

        $attendance->update([
            'check_out_time' => $now,
        ]);

        return back()->with('success', 'Absensi pulang berhasil dicatat pukul ' . $now->format('H:i') . '. Jarak: ' . round($distance) . 'm.');
    }

    /**
     * Calculate distance between two GPS coordinates using Haversine formula.
     * Returns distance in meters.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
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
}
