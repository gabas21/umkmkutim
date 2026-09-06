@extends('layouts.app')

@section('title', $berita->judul . ' — UMKM Kutai Timur')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-4">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Indeks Berita
        </a>

        <div class="space-y-3">
            <div class="flex items-center gap-3 text-xs">
                <span class="px-2.5 py-0.5 rounded-full font-bold uppercase {{ $berita->kategori == 'pengumuman' ? 'bg-amber-400 text-slate-950' : 'bg-emerald-500 text-white' }}">
                    {{ $berita->kategori }}
                </span>
                <span class="text-slate-400 font-mono">{{ $berita->published_at ? $berita->published_at->format('d F Y, H:i') : '-' }} WITA</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white leading-tight">
                {{ $berita->judul }}
            </h1>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-8">
        <!-- Article Content -->
        <div class="prose prose-emerald max-w-none text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
            {!! $berita->konten !!}
        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>Diterbitkan oleh Humas & Publikasi Dinas Koperasi & UMKM Kab. Kutai Timur</span>
            <a href="{{ route('berita.index') }}" class="text-emerald-600 font-bold hover:underline">
                Lihat Artikel Lainnya &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
