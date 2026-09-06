<?php

namespace App\Http\Controllers;

use App\Models\PelakuUsaha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================
    // PELAKU USAHA AUTH
    // ==========================================

    public function showLogin()
    {
        if (Auth::guard('pelaku_usaha')->check()) {
            return redirect()->route('dashboard.pelaku');
        }
        return view('auth.login-pelaku');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('pelaku_usaha')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.pelaku'))->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::guard('pelaku_usaha')->check()) {
            return redirect()->route('dashboard.pelaku');
        }
        return view('auth.register-pelaku');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:pelaku_usaha,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $pelaku = PelakuUsaha::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Auth::guard('pelaku_usaha')->login($pelaku);
        $request->session()->regenerate();

        return redirect()->route('dashboard.pelaku')->with('success', 'Pendaftaran akun berhasil! Selamat datang di Portal UMKM Kutim.');
    }

    // ==========================================
    // ADMIN AUTH
    // ==========================================

    public function showAdminLogin()
    {
        if (Auth::guard('web')->check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login Admin berhasil.');
            }
            Auth::guard('web')->logout();
            return back()->withErrors(['email' => 'Anda tidak memiliki hak akses administrator.']);
        }

        return back()->withErrors([
            'email' => 'Kredensial admin tidak valid.',
        ])->onlyInput('email');
    }

    // ==========================================
    // LOGOUT
    // ==========================================

    public function logout(Request $request)
    {
        if (Auth::guard('pelaku_usaha')->check()) {
            Auth::guard('pelaku_usaha')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
