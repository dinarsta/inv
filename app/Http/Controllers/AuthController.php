<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Buat file resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Simpan log login
            ActivityLog::create([
                'user_id' => Auth::id(),
                'login_at' => Carbon::now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'keterangan' => 'Login berhasil',
            ]);

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

public function logout(Request $request)
{
    $user = Auth::user();

    // ✅ Tambahkan baris baru untuk aktivitas logout
    ActivityLog::create([
        'user_id' => $user->id,
        'logout_at' => Carbon::now(),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'keterangan' => 'Logout',
        'aktivitas_detail' => $user->name . ' telah logout pada ' . now()->format('Y-m-d H:i'),
    ]);

    // Lakukan logout seperti biasa
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // 'role' => 'required|in:admin,readonly',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
