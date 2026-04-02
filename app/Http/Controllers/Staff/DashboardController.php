<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAttendance;
use App\Models\SchoolSetting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $myAttendance = PersonnelAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        return view('staff.dashboard', compact('myAttendance', 'schoolSettings'));
    }
}
