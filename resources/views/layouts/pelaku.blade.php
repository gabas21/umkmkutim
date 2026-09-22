@extends('layouts.app')

@section('title', $title ?? 'Dashboard Pelaku Usaha — UMKM Kutim')

@section('content')
<div>
    <!-- Tab Navigation -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1 overflow-x-auto no-scrollbar py-3 text-xs font-bold">
                <a href="{{ route('dashboard.pelaku') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('dashboard.pelaku') && !request()->routeIs('dashboard.pelaku.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-gauge-high mr-1.5"></i> Dashboard
                </a>
                <a href="{{ route('dashboard.pelaku.bazar') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('dashboard.pelaku.bazar') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-store-alt-slash mr-1.5"></i> Bazar Saya
                </a>
                <a href="{{ route('dashboard.pelaku.pelatihan') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('dashboard.pelaku.pelatihan') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-chalkboard-user mr-1.5"></i> Pelatihan Saya
                </a>
                <a href="{{ route('dashboard.pelaku.akun') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('dashboard.pelaku.akun') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-user-gear mr-1.5"></i> Pengaturan Akun
                </a>
            </nav>
        </div>
    </div>

    @yield('pelaku-content')
</div>
@endsection
