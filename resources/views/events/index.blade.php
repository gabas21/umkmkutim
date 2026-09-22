@extends('layouts.app')

@section('title', 'Event & Kegiatan UMKM Kutim')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">Agenda & Kegiatan</p>
        <h1 class="mt-2 text-3xl font-black text-slate-900">Event UMKM Kutai Timur</h1>
    </div>

    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form action="{{ route('events.index') }}" method="GET" class="flex flex-col gap-3 md:flex-row md:items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event, lokasi, atau deskripsi" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>
            <div class="relative min-w-[180px]">
                <select name="type" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-10 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="">Semua tipe</option>
                    <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="exhibition" {{ request('type') == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
                    <option value="bazar" {{ request('type') == 'bazar' ? 'selected' : '' }}>Bazar</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">
                <i class="fa-solid fa-filter mr-2"></i> Cari
            </button>
            @if(request()->hasAny(['q', 'type']))
                <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($events as $event)
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="bg-emerald-50 p-5">
                    <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700">{{ strtoupper($event->type ?? 'Event') }}</div>
                    <h2 class="mt-2 text-xl font-black text-slate-900">{{ $event->title }}</h2>
                </div>
                <div class="p-5">
                    <div class="mb-3 flex items-center gap-2 text-sm text-slate-600">
                        <i class="fa-solid fa-calendar-days text-emerald-600"></i>
                        <span>{{ $event->start_date?->translatedFormat('d M Y') }} - {{ $event->end_date?->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="mb-3 flex items-center gap-2 text-sm text-slate-600">
                        <i class="fa-solid fa-location-dot text-emerald-600"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                    <p class="text-sm leading-6 text-slate-600">
                        {{ Str::limit(strip_tags($event->description), 120) }}
                    </p>
                    <div class="mt-5 flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                            {{ $event->status ?? 'open' }}
                        </span>
                        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 hover:text-emerald-700">
                            Detail <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-slate-500">
                Belum ada event yang dipublikasikan.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $events->links() }}
    </div>
</div>
@endsection
