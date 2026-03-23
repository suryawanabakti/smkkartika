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
}
