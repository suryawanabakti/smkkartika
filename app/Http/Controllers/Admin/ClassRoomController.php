<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRoom::with(['major', 'teacher.user']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('major_id') && $request->major_id != '') {
            $query->where('major_id', $request->major_id);
        }

        $classRooms = $query->latest()->paginate(10)->withQueryString();
        $majors = Major::all();

        return view('admin.classrooms.index', compact('classRooms', 'majors'));
    }

    public function create()
    {
        $majors = Major::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.create', compact('majors', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'name' => 'required|string|max:255',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(ClassRoom $classroom)
    {
        $majors = Major::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.edit', compact('classroom', 'majors', 'teachers'));
    }

    public function update(Request $request, ClassRoom $classroom)
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'name' => 'required|string|max:255',
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $classroom)
    {
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
