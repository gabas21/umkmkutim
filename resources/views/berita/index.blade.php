@extends('layouts.app')

@section('title', 'Kabar Berita & Agenda UMKM Kutai Timur')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Pusat Informasi & Publikasi</span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white">Berita, Pelatihan & Agenda UMKM</h1>
            <p class="text-sm sm:text-base text-slate-300">Dapatkan informasi terkini mengenai program bantuan, sertifikasi halal gratis, pelatihan pemasaran digital, dan bazar daerah Kabupaten Kutai Timur.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Filter Kategori Berita -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('berita.index') }}" class="px-4 py-2 rounded-xl {{ !request('kategori') ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Artikel
        </a>
        <a href="{{ route('berita.index', ['kategori' => 'pengumuman']) }}" class="px-4 py-2 rounded-xl {{ request('kategori') == 'pengumuman' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Pengumuman Dinas
        </a>
        <a href="{{ route('berita.index', ['kategori' => 'berita']) }}" class="px-4 py-2 rounded-xl {{ request('kategori') == 'berita' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Kabar Daerah
        </a>
        <a href="{{ route('berita.index', ['kategori' => 'tips']) }}" class="px-4 py-2 rounded-xl {{ request('kategori') == 'tips' ? 'bg-emerald-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Tips Wirausaha
        </a>
    </div>

    <!-- Berita List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($beritaList as $b)
            <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition duration-200 flex flex-col justify-between">
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="px-2.5 py-0.5 rounded-full font-bold uppercase {{ $b->kategori == 'pengumuman' ? 'bg-amber-100 text-amber-800' : ($b->kategori == 'tips' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                            {{ $b->kategori }}
                        </span>
                        <span>{{ $b->published_at ? $b->published_at->format('d M Y') : '-' }}</span>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 hover:text-emerald-600 transition leading-snug">
                        <a href="{{ route('berita.show', $b->slug) }}">{{ $b->judul }}</a>
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                        {{ strip_tags($b->konten) }}
                    </p>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <a href="{{ route('berita.show', $b->slug) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        <span>Baca Rilis Lengkap</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $beritaList->links() }}
    </div>
</div>
@endsection
