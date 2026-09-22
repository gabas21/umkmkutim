<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PelakuAkunController extends Controller
{
    public function edit()
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        return view('dashboard.pelaku.akun', compact('pelaku'));
    }

    public function update(Request $request)
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:100', Rule::unique('pelaku_usaha', 'email')->ignore($pelaku->id)],
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        $pelaku->update($validated);

        return back()->with('success', 'Profil akun berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $request->validate([
            'password_lama' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password_lama.required' => 'Masukkan kata sandi lama Anda.',
        ]);

        if (!Hash::check($request->password_lama, $pelaku->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi lama tidak sesuai.']);
        }

        $pelaku->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
