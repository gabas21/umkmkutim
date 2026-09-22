@extends('layouts.pelaku')

@section('title', 'Pelatihan Saya — UMKM Kutim')

@section('pelaku-content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-900">Pelatihan Saya</h1>
        <p class="text-xs text-slate-500 mt-1">Riwayat dan status keikutsertaan Anda pada program pelatihan & pendampingan.</p>
    </div>

    @if($pendaftaranList->count() > 0)
        <div class="space-y-4">
            @foreach($pendaftaranList as $p)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @php
                                $statusColor = match($p->status) {
                                    'hadir' => 'bg-emerald-100 text-emerald-800',
                                    'tidak_hadir' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }}">{{ str_replace('_', ' ', $p->status) }}</span>
                            <span class="text-[11px] text-slate-400">Daftar {{ $p->created_at->format('d/m/Y') }}</span>
                        </div>
                        <h3 class="font-black text-slate-900 text-base">{{ $p->pelatihan?->judul ?? '(Pelatihan dihapus)' }}</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            <i class="fa-solid fa-location-dot text-emerald-600"></i>
                            {{ $p->pelatihan?->lokasi }}
                            @if($p->pelatihan)
                                &middot; {{ $p->pelatihan->tanggal_mulai->format('d/m/Y H:i') }} &middot; <span class="capitalize">{{ $p->pelatihan->mode }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-600 mt-2">Nama peserta terdaftar: <strong>{{ $p->nama_peserta }}</strong></p>
                    </div>
                    @if($p->pelatihan)
                        <a href="{{ route('pelatihan.show', $p->pelatihan->slug) }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition whitespace-nowrap">
                            Lihat Pelatihan
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center space-y-3">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800">Anda belum mengikuti pelatihan apa pun</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Jelajahi jadwal pelatihan terbaru untuk meningkatkan kapasitas usaha Anda.</p>
            <a href="{{ route('pelatihan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition">
                <i class="fa-solid fa-magnifying-glass"></i> Lihat Jadwal Pelatihan
            </a>
        </div>
    @endif
</div>
@endsection
