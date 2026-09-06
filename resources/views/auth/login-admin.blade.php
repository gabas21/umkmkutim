@extends('layouts.app')

@section('title', 'Login Administrator Dinas — UMKM Kutim')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900 text-white rounded-3xl p-8 sm:p-10 border border-slate-800 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600 mx-auto flex items-center justify-center text-white text-2xl shadow-md">
                <i class="fa-solid fa-shield-halved text-amber-300"></i>
            </div>
            <h2 class="text-2xl font-black text-white">Login Admin Dinas</h2>
            <p class="text-xs text-slate-400">Portal verifikasi berkas klaim dan manajemen direktori daerah</p>
        </div>

        <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800 text-[11px] text-emerald-300 space-y-1">
            <span class="font-bold block text-emerald-200"><i class="fa-solid fa-circle-info"></i> Akun Pengujian Dinas:</span>
            <span>Email: <strong>admin@umkmkutim.go.id</strong></span><br>
            <span>Password: <strong>password123</strong></span>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-300">Email Administrator</label>
                <input type="email" name="email" value="{{ old('email', 'admin@umkmkutim.go.id') }}" required class="w-full px-3.5 py-2.5 text-sm bg-slate-800 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-300">Password</label>
                <input type="password" name="password" required value="password123" class="w-full px-3.5 py-2.5 text-sm bg-slate-800 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl transition shadow-lg shadow-emerald-900/40">
                    Masuk ke Panel Verifikasi
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
            Bukan staf dinas? <a href="{{ route('login') }}" class="text-emerald-400 font-semibold hover:underline">Masuk Pelaku Usaha</a>
        </div>
    </div>
</div>
@endsection
