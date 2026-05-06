<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherSchedule;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class TeacherScheduleController extends Controller
{
    public function index()
    {
        $schedules = TeacherSchedule::with(['teacher.user', 'classRoom'])->get();
        return view('admin.teacher_schedules.index', compact('schedules'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $classes = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return view('admin.teacher_schedules.create', compact('teachers', 'classes', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required',
            'subject' => 'required',
            'class_id' => 'required|exists:classes,id',
            'period_start' => 'required|integer|min:1|max:11',
            'period_end' => 'required|integer|min:1|max:11|gte:period_start',
        ]);

        TeacherSchedule::create($request->all());

        return redirect()->route('admin.teacher-schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(TeacherSchedule $teacherSchedule)
    {
        $teachers = Teacher::with('user')->get();
        $classes = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return view('admin.teacher_schedules.edit', compact('teacherSchedule', 'teachers', 'classes', 'days'));
    }

    public function update(Request $request, TeacherSchedule $teacherSchedule)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required',
            'subject' => 'required',
            'class_id' => 'required|exists:classes,id',
            'period_start' => 'required|integer|min:1|max:11',
            'period_end' => 'required|integer|min:1|max:11|gte:period_start',
        ]);

        $teacherSchedule->update($request->all());

        return redirect()->route('admin.teacher-schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(TeacherSchedule $teacherSchedule)
    {
        $teacherSchedule->delete();
        return redirect()->route('admin.teacher-schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
