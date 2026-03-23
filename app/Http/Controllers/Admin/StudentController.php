<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom.major']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nis', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
        }

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $classRooms = ClassRoom::with('major')->get();

        return view('admin.students.index', compact('students', 'classRooms'));
    }

    public function create()
    {
        $classRooms = ClassRoom::with('major')->get();
        return view('admin.students.create', compact('classRooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|string|max:20|unique:students,nis',
            'class_id' => 'required|exists:class_rooms,id',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($validated) {
            $studentRole = Role::where('name', 'student')->first();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $studentRole->id,
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dibuat.');
    }

    public function edit(Student $student)
    {
        $classRooms = ClassRoom::with('major')->get();
        return view('admin.students.edit', compact('student', 'classRooms'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|max:20|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:class_rooms,id',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $student, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->user->update($userData);

            $student->update([
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            $user->delete();
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
