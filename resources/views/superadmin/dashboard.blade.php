@extends('layouts.admin')

@section('title', 'Panel Superadmin — UMKM Kutim')

@section('admin-content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Superadmin Control Center</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-1">Panel Superadmin UMKM Kutim</h1>
                <p class="text-xs sm:text-sm text-slate-400">Kelola pengguna, admin, role, dan akses penuh ke seluruh sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-lg bg-emerald-950 border border-emerald-800 text-emerald-300 text-xs font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-user-shield text-emerald-400"></i>
                    <span>{{ Auth::user()->name }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="text-xs text-slate-500 font-medium">Total Pengguna</div>
            <div class="mt-3 text-3xl font-black text-slate-900">{{ $totalUsers }}</div>
            <div class="mt-2 text-xs text-slate-500">Semua akun di sistem</div>
        </div>

        <div class="bg-white rounded-2xl border border-emerald-200 p-6 shadow-sm bg-gradient-to-br from-white to-emerald-50/50">
            <div class="text-xs text-emerald-700 font-bold">Admin & Superadmin</div>
            <div class="mt-3 text-3xl font-black text-emerald-900">{{ $adminUsers }}</div>
            <div class="mt-2 text-xs text-emerald-600">Akun admin yang memiliki akses sistem</div>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-sm bg-gradient-to-br from-white to-amber-50/50">
            <div class="text-xs text-amber-700 font-bold">Superadmin</div>
            <div class="mt-3 text-3xl font-black text-amber-900">{{ $superAdminUsers }}</div>
            <div class="mt-2 text-xs text-amber-600">Akun dengan akses penuh</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Menu Superadmin</h2>
            <div class="mt-5 space-y-3">
                <a href="{{ route('superadmin.users') }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-3 hover:bg-slate-50 transition">
                    <span class="font-semibold text-slate-700">Kelola semua pengguna</span>
                    <i class="fa-solid fa-users text-slate-400"></i>
                </a>
                <a href="{{ route('superadmin.admins') }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-3 hover:bg-slate-50 transition">
                    <span class="font-semibold text-slate-700">Kelola admin</span>
                    <i class="fa-solid fa-user-shield text-slate-400"></i>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-3 hover:bg-slate-50 transition">
                    <span class="font-semibold text-slate-700">Masuk ke admin panel</span>
                    <i class="fa-solid fa-arrow-right text-slate-400"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Catatan akses</h2>
            <ul class="mt-5 space-y-3 text-sm text-slate-600">
                <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i> Superadmin memiliki akses penuh ke sistem.</li>
                <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i> Admin dapat mengelola UMKM dan verifikasi.</li>
                <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i> UMKM hanya mengakses data usaha mereka sendiri.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
