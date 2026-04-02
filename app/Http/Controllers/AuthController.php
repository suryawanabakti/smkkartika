<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            }
            if (Auth::user()->isStudent()) {
                return redirect()->route('student.dashboard');
            }
            if (Auth::user()->isStaff()) {
                return redirect()->route('staff.dashboard');
            }
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if (Auth::user()->isTeacher()) {
                return redirect()->intended(route('teacher.dashboard'));
            }

            if (Auth::user()->isStudent()) {
                return redirect()->intended(route('student.dashboard'));
            }

            if (Auth::user()->isStaff()) {
                return redirect()->intended(route('staff.dashboard'));
            }

            // If not authorized for any panel, logout and show error
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses panel manapun.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
