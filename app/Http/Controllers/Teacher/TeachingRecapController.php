<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherSchedule;
use App\Models\TeachingAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeachingRecapController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $dayName = $this->translateDay(now()->format('l'));
        
        $schedules = TeacherSchedule::with('classRoom')
            ->where('teacher_id', $teacher->id)
            ->where('day', $dayName)
            ->get();

        $todayAttendances = TeachingAttendance::where('teacher_id', $teacher->id)
            ->whereDate('date', now()->toDateString())
            ->get()
            ->keyBy('schedule_id');

        return view('teacher.teaching_recap.index', compact('schedules', 'todayAttendances', 'dayName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:teacher_schedules,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $schedule = TeacherSchedule::findOrFail($request->schedule_id);
        $teacher = auth()->user()->teacher;

        // Check if already attended
        $existing = TeachingAttendance::where('teacher_id', $teacher->id)
            ->where('schedule_id', $schedule->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk jam ini.');
        }

        TeachingAttendance::create([
            'teacher_id' => $teacher->id,
            'schedule_id' => $schedule->id,
            'date' => now()->toDateString(),
            'subject' => $schedule->subject,
            'class_id' => $schedule->class_id,
            'check_in_time' => now()->toTimeString(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Absensi mengajar berhasil dicatat.');
    }

    private function translateDay($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $days[$day] ?? $day;
    }
}
