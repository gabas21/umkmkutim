@extends('layouts.app')

@section('title', 'Pelatihan & Bimbingan Teknis UMKM Kutai Timur — Tingkatkan Kapasitas Usaha Anda')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-14 border-b border-emerald-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex items-center gap-2 text-xs text-emerald-300 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <span class="text-white font-semibold">Pelatihan & Bimtek</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Program Pelatihan & Pendampingan UMKM
                </h1>
                <p class="text-sm sm:text-base text-slate-300 mt-2 leading-relaxed">
                    Bimbingan teknis legalitas NIB OSS, sertifikasi halal BPJPH gratis, akselerasi pemasaran online, dan manajemen keuangan usaha untuk masyarakat Kutai Timur.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                    <strong class="text-white text-base">{{ $totalUpcoming }}</strong> Program Aktif
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-8 bg-white border-b border-slate-200 sticky top-20 z-30 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('pelatihan.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Mode & Status Filter Pills -->
            <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto no-scrollbar">
                <a href="{{ route('pelatihan.index', ['mode' => '', 'status' => $status, 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$mode ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Semua Mode
                </a>
                <a href="{{ route('pelatihan.index', ['mode' => 'offline', 'status' => $status, 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $mode === 'offline' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Tatap Muka (Offline)
                </a>
                <a href="{{ route('pelatihan.index', ['mode' => 'online', 'status' => $status, 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $mode === 'online' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Daring (Online Zoom)
                </a>
                <a href="{{ route('pelatihan.index', ['mode' => 'hybrid', 'status' => $status, 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $mode === 'hybrid' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Hybrid
                </a>
            </div>

            <!-- Search input -->
            <div class="w-full md:w-80 relative flex items-center">
                <input type="hidden" name="mode" value="{{ $mode }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik materi pelatihan..." class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white text-slate-900">
                @if(request('q'))
                    <a href="{{ route('pelatihan.index', ['mode' => $mode, 'status' => $status]) }}" class="absolute right-3 text-slate-400 hover:text-slate-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- List Pelatihan Cards -->
<section class="py-12 bg-slate-50 min-h-[500px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($pelatihans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal>
                @foreach($pelatihans as $p)
                    <div class="bg-white rounded-3xl border border-slate-200 hover:border-emerald-500/50 hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        <div class="p-6 sm:p-7 space-y-4">
                            <!-- Badges Header -->
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2.5 py-0.5 rounded-full font-bold uppercase {{ $p->mode === 'online' ? 'bg-blue-100 text-blue-800' : ($p->mode === 'hybrid' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    <i class="fa-solid fa-laptop-code text-[10px] mr-1"></i> Mode {{ ucfirst($p->mode) }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full font-black text-emerald-800 bg-emerald-100 text-[10px] tracking-wider">
                                    GRATIS (PEMKAB)
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug">
                                <a href="{{ route('pelatihan.show', $p->slug) }}">{{ $p->judul }}</a>
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                {{ $p->deskripsi }}
                            </p>

                            <!-- Meta Info -->
                            <div class="space-y-2 text-xs text-slate-600 pt-3 border-t border-slate-100">
                                <p class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-emerald-600 w-4"></i>
                                    <span>{{ $p->tanggal_mulai->format('d M Y, H:i') }} WITA</span>
                                </p>
                                <p class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-location-dot text-emerald-600 w-4"></i>
                                    <span class="truncate">{{ $p->lokasi }}</span>
                                </p>
                                @if($p->instruktur)
                                    <p class="flex items-center gap-2 truncate text-slate-500 text-[11px]">
                                        <i class="fa-solid fa-user-tie text-amber-500 w-4"></i>
                                        <span class="truncate">Instruktur: {{ $p->instruktur }}</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Kuota Bar -->
                            <div class="pt-2">
                                <div class="flex justify-between text-[11px] font-medium text-slate-500 mb-1">
                                    <span>Kapasitas Peserta:</span>
                                    <span class="font-bold {{ $p->sisa_kuota > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                        {{ $p->persen_terisi }}% (Sisa {{ $p->sisa_kuota }} kursi)
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $p->persen_terisi }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                            <a href="{{ route('pelatihan.show', $p->slug) }}" class="flex-1 py-2.5 text-center rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition">
                                Rincian Materi
                            </a>
                            @if($p->sisa_kuota > 0)
                                <a href="{{ route('pelatihan.show', $p->slug) }}#daftar" class="flex-1 py-2.5 text-center rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm transition">
                                    Daftar Peserta &rarr;
                                </a>
                            @else
                                <span class="flex-1 py-2.5 text-center rounded-xl text-xs font-semibold text-slate-400 bg-slate-200/80 cursor-not-allowed">
                                    Kuota Penuh
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $pelatihans->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 p-8" data-reveal>
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Program Pelatihan Sesuai Filter</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Silakan ganti opsi filter mode atau kata kunci pencarian Anda.
                </p>
                <a href="{{ route('pelatihan.index') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
