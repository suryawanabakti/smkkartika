<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $layout = $this->getLayout();
        
        return view('profile.index', compact('user', 'layout'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:L,P'],
            'position' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'],
            'gender' => $validated['gender'],
            'position' => $validated['position'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Also sync to related models if they exist
        if ($user->teacher) {
            $user->teacher->update([
                'nip' => $validated['nip'],
                'gender' => $validated['gender'],
                'position' => $validated['position'],
            ]);
        } elseif ($user->staff) {
            $user->staff->update([
                'nip' => $validated['nip'],
                'gender' => $validated['gender'],
            ]);
        } elseif ($user->student) {
            $user->student->update([
                'gender' => $validated['gender'],
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }

    private function getLayout()
    {
        $user = auth()->user();
        if ($user->isAdmin()) return 'layouts.admin';
        if ($user->isTeacher()) return 'layouts.teacher';
        return 'layouts.app';
    }
}
