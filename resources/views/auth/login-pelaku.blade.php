@extends('layouts.app')

@section('title', 'Masuk Akun Pelaku Usaha — UMKM Kutim')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-xl space-y-6">
        <div class="text-center space-y-3">
            <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-16 w-auto mx-auto object-contain drop-shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Masuk Pelaku Usaha</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola profil usaha, ajukan klaim, dan pantau statistik kunjungan produk Anda</p>
            </div>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Kata Sandi (Password)</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-md shadow-emerald-600/20">
                Masuk ke Dashboard Usaha
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500 space-y-2">
            <p>Belum memiliki akun pemilik usaha? <a href="{{ route('register') }}" class="text-emerald-600 font-bold hover:underline">Daftar Sekarang</a></p>
            <p class="text-[11px] text-slate-400">Petugas / Administrator dinas? <a href="{{ route('admin.login') }}" class="text-slate-600 font-medium hover:underline">Login di sini</a></p>
        </div>
    </div>
</div>
@endsection
