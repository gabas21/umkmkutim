@extends('layouts.app')

@section('title', 'Daftar Akun Pelaku Usaha — UMKM Kutim')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl gradient-kutim mx-auto flex items-center justify-center text-white text-2xl shadow-md">
                <i class="fa-solid fa-user-plus text-amber-300"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900">Pendaftaran Akun</h2>
            <p class="text-xs text-slate-500">Khusus untuk pemilik dan pelaku usaha mikro, kecil, dan menengah di Kutai Timur</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Nama Lengkap Pemilik Usaha <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Alamat Email Aktif <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Nomor Handphone / WhatsApp</label>
                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="0812xxxxxxxx" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Kata Sandi (Password) <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-md shadow-emerald-600/20">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
