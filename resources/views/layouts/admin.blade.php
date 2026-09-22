@extends('layouts.app')

@section('title', $title ?? 'Panel Admin Dinas — UMKM Kutim')

@section('content')
@php
    $adminNavGroups = [
        [
            'label' => 'Utama',
            'items' => [
                ['route' => 'admin.dashboard', 'is' => 'admin.dashboard', 'icon' => 'gauge-high', 'label' => 'Dashboard'],
            ],
        ],
        [
            'label' => 'Data UMKM',
            'items' => [
                ['route' => 'admin.umkm.index', 'is' => 'admin.umkm.*', 'icon' => 'store', 'label' => 'UMKM'],
                ['route' => 'admin.pelaku-usaha.index', 'is' => 'admin.pelaku-usaha.*', 'icon' => 'user-tie', 'label' => 'Pelaku Usaha'],
                ['route' => 'admin.kategori.index', 'is' => 'admin.kategori.*', 'icon' => 'tags', 'label' => 'Kategori'],
                ['route' => 'admin.review.index', 'is' => 'admin.review.*', 'icon' => 'star-half-stroke', 'label' => 'Ulasan'],
                ['route' => 'admin.activity-logs.index', 'is' => 'admin.activity-logs.*', 'icon' => 'clock-rotate-left', 'label' => 'Aktivitas'],
            ],
        ],
        [
            'label' => 'Program & Konten',
            'items' => [
                ['route' => 'admin.berita.index', 'is' => 'admin.berita.*', 'icon' => 'newspaper', 'label' => 'Berita'],
                ['route' => 'admin.news.index', 'is' => 'admin.news.*', 'icon' => 'newspaper', 'label' => 'News'],
                ['route' => 'admin.events.index', 'is' => 'admin.events.*', 'icon' => 'calendar-days', 'label' => 'Event'],
                ['route' => 'admin.bazar.index', 'is' => 'admin.bazar.*', 'icon' => 'store-alt-slash', 'label' => 'Bazar / Expo'],
                ['route' => 'admin.pelatihan.index', 'is' => 'admin.pelatihan.*', 'icon' => 'chalkboard-user', 'label' => 'Pelatihan'],
                ['route' => 'admin.hero-slides.index', 'is' => 'admin.hero-slides.*', 'icon' => 'images', 'label' => 'Banner Hero'],
            ],
        ],
        [
            'label' => 'Umpan Balik & Data',
            'items' => [
                ['route' => 'admin.survey.index', 'is' => 'admin.survey.*', 'icon' => 'clipboard-question', 'label' => 'Survey Kepuasan'],
                ['route' => 'admin.import', 'is' => 'admin.import*', 'icon' => 'file-import', 'label' => 'Import Data'],
            ],
        ],
    ];
@endphp

<div class="bg-slate-100 min-h-[calc(100vh-5rem)]">
    <div class="max-w-[100rem] mx-auto lg:flex lg:items-start">

        <!-- Sidebar -->
        <aside class="lg:w-64 shrink-0 bg-slate-900 lg:min-h-[calc(100vh-5rem)] lg:sticky lg:top-20">
            <div class="p-5 border-b border-slate-800">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Panel Kendali</span>
                <p class="text-sm font-black text-white mt-1">Diskop &amp; UMKM Kutim</p>
                <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ Auth::guard('web')->user()->name ?? 'Administrator' }}</p>
            </div>

            <nav class="p-3 space-y-5 text-xs">
                @foreach($adminNavGroups as $group)
                    <div>
                        <p class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $group['label'] }}</p>
                        <div class="space-y-0.5">
                            @foreach($group['items'] as $item)
                                <a href="{{ route($item['route']) }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-semibold transition {{ request()->routeIs($item['is']) ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <i class="fa-solid fa-{{ $item['icon'] }} w-4 text-center {{ request()->routeIs($item['is']) ? 'text-white' : 'text-emerald-500' }}"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="p-3 mt-2 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl font-semibold text-rose-300 hover:bg-rose-950/60 hover:text-rose-200 transition text-xs">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                        <span>Keluar dari Panel</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow min-w-0">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection
