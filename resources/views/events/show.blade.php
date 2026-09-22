@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke event
        </a>
    </div>

    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-emerald-50 p-6 sm:p-8">
            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700">{{ strtoupper($event->type ?? 'Event') }}</div>
            <h1 class="mt-3 text-3xl font-black text-slate-900 sm:text-4xl">{{ $event->title }}</h1>
        </div>

        <div class="p-6 sm:p-8">
            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:grid-cols-2">
                <div class="flex items-center gap-3 text-sm text-slate-700">
                    <i class="fa-solid fa-calendar-days text-emerald-600"></i>
                    <span>{{ $event->start_date?->translatedFormat('d M Y') }} - {{ $event->end_date?->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-slate-700">
                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                    <span>{{ $event->location }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-slate-700">
                    <i class="fa-solid fa-users text-emerald-600"></i>
                    <span>{{ $event->participants_count ?? 0 }} peserta</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-slate-700">
                    <i class="fa-solid fa-ticket text-emerald-600"></i>
                    <span>{{ $event->quota ? 'Kuota ' . $event->quota : 'Kuota tidak dibatasi' }}</span>
                </div>
            </div>

            @if($event->status === 'open')
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @auth('pelaku_usaha')
                        <form action="{{ route('events.register', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                                <i class="fa-solid fa-user-plus"></i> Daftar Event
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                            <i class="fa-solid fa-user-plus"></i> Login untuk Daftar
                        </a>
                    @endauth
                </div>
            @else
                <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">
                    Pendaftaran untuk event ini belum dibuka atau sudah ditutup.
                </div>
            @endif

            <div class="mt-8 prose max-w-none prose-slate prose-headings:font-black prose-a:text-emerald-600">
                {!! nl2br(e($event->description)) !!}
            </div>
        </div>
    </article>
</div>
@endsection
