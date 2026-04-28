<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nip', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
        }

        $teachers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:L,P',
            'position' => 'nullable|string|max:255',
        ]);

        DB::transaction(function() use ($validated) {
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $teacherRole->id,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'gender' => $validated['gender'],
                'position' => $validated['position'] ?? null,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dibuat.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $teacher->id,
            'password' => 'nullable|string|min:8',
            'gender' => 'required|in:L,P',
            'position' => 'nullable|string|max:255',
        ]);

        DB::transaction(function() use ($validated, $teacher, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $teacher->user->update($userData);

            $teacher->update([
                'nip' => $validated['nip'],
                'gender' => $validated['gender'],
                'position' => $validated['position'] ?? null,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function() use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();
            $user->delete();
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nip', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
        }

        $teachers = $query->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.teachers.pdf', compact('teachers'))->setPaper('a4', 'portrait');
        return $pdf->download('data-guru.pdf');
    }
}
