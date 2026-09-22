@extends('layouts.pelaku')

@section('title', 'Pengaturan Akun — UMKM Kutim')

@section('pelaku-content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div>
        <h1 class="text-xl font-black text-slate-900">Pengaturan Akun</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    <!-- Profil -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Profil</h3>
        <form action="{{ route('dashboard.pelaku.akun.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" required value="{{ old('nama', $pelaku->nama) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email', $pelaku->email) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nomor Telepon / WA</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $pelaku->nomor_telepon) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Password -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Ubah Kata Sandi</h3>
        <form action="{{ route('dashboard.pelaku.akun.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Kata Sandi Lama <span class="text-rose-500">*</span></label>
                <input type="password" name="password_lama" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="6" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="6" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow transition">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
