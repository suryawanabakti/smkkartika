<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nip', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
        }

        $staffs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:staffs,nip',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function() use ($validated) {
            $staffRole = Role::firstOrCreate(['name' => 'staff']);
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $staffRole->id,
            ]);

            Staff::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
            ]);
        });

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dibuat.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->user_id,
            'nip' => 'required|string|max:20|unique:staffs,nip,' . $staff->id,
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function() use ($validated, $staff, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $staff->user->update($userData);

            $staff->update([
                'nip' => $validated['nip'],
            ]);
        });

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        DB::transaction(function() use ($staff) {
            $user = $staff->user;
            $staff->delete();
            $user->delete();
        });

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dihapus.');
    }
}
