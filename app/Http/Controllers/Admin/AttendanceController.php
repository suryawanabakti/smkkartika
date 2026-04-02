<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAttendance;
use App\Models\SchoolSetting;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $existing = PersonnelAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
        }

        // Validate distance to school
        $schoolLat = (float) SchoolSetting::get('school_latitude', -5.1436);
        $schoolLng = (float) SchoolSetting::get('school_longitude', 119.4667);
        $schoolRadius = (int) SchoolSetting::get('school_radius', 200);

        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = $this->haversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

        if ($distance > $schoolRadius) {
            return back()->with('error', 'Anda berada di luar radius sekolah (' . round($distance) . 'm, batas: ' . $schoolRadius . 'm).');
        }

        PersonnelAttendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => now(),
            'status' => 'present',
            'latitude' => $userLat,
            'longitude' => $userLng,
        ]);

        return back()->with('success', 'Absensi masuk berhasil.');
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
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absensi masuk hari ini.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
        }

        // Validate distance to school
        $schoolLat = (float) SchoolSetting::get('school_latitude', -5.1436);
        $schoolLng = (float) SchoolSetting::get('school_longitude', 119.4667);
        $schoolRadius = (int) SchoolSetting::get('school_radius', 200);

        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = $this->haversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

        if ($distance > $schoolRadius) {
            return back()->with('error', 'Anda berada di luar radius sekolah (' . round($distance) . 'm, batas: ' . $schoolRadius . 'm).');
        }

        $attendance->update([
            'check_out_time' => now(),
        ]);

        return back()->with('success', 'Absensi pulang berhasil.');
    }

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
