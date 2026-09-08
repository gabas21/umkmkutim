@extends('layouts.app')

@section('title', 'Bazar & Pameran UMKM Kutai Timur — Jadwal & Pendaftaran Lapak Resmi')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-14 border-b border-emerald-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex items-center gap-2 text-xs text-emerald-300 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <span class="text-white font-semibold">Bazar & Pameran Usaha</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Bazar & Gelar Dagang UMKM
                </h1>
                <p class="text-sm sm:text-base text-slate-300 mt-2 leading-relaxed">
                    Pameran resmi, festival kuliner daerah, dan expo produk unggulan binaan Pemerintah Kabupaten Kutai Timur. Fasilitasi stand gratis bagi UMKM terverifikasi.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                    <strong class="text-white text-base">{{ $totalUpcoming }}</strong> Event Dibuka
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-8 bg-white border-b border-slate-200 sticky top-20 z-30 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('bazar.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto no-scrollbar">
                <a href="{{ route('bazar.index', ['status' => 'upcoming', 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'upcoming' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Akan Datang & Berlangsung ({{ $totalUpcoming }})
                </a>
                <a href="{{ route('bazar.index', ['status' => 'selesai', 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'selesai' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Arsip Event ({{ $totalSelesai }})
                </a>
                <a href="{{ route('bazar.index', ['status' => 'all', 'q' => request('q')]) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'all' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Semua Status
                </a>
            </div>

            <!-- Search input -->
            <div class="w-full md:w-80 relative flex items-center">
                <input type="hidden" name="status" value="{{ $status }}">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau lokasi bazar..." class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white text-slate-900">
                @if(request('q'))
                    <a href="{{ route('bazar.index', ['status' => $status]) }}" class="absolute right-3 text-slate-400 hover:text-slate-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- List Event Bazar -->
<section class="py-12 bg-slate-50 min-h-[500px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($bazars->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal>
                @foreach($bazars as $bazar)
                    <div class="bg-white rounded-3xl border border-slate-200 hover:border-emerald-500/50 hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        <!-- Top Header with gradient & date -->
                        <div class="relative p-6 bg-gradient-to-br from-slate-900 to-[#022c22] text-white">
                            <div class="flex justify-between items-start gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $bazar->status === 'upcoming' ? 'bg-amber-400 text-slate-950' : ($bazar->status === 'ongoing' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-300') }}">
                                    {{ $bazar->status === 'upcoming' ? 'Pendaftaran Dibuka' : ($bazar->status === 'ongoing' ? 'Sedang Berlangsung' : 'Selesai') }}
                                </span>
                                <span class="text-xs text-slate-300">
                                    <i class="fa-solid fa-location-dot text-amber-400 text-[11px] mr-1"></i> Kec. {{ $bazar->kecamatan }}
                                </span>
                            </div>

                            <h3 class="text-lg font-black text-white leading-snug group-hover:text-amber-300 transition">
                                <a href="{{ route('bazar.show', $bazar->slug) }}">{{ $bazar->nama_bazar }}</a>
                            </h3>

                            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-slate-300">
                                <span><i class="fa-regular fa-calendar text-emerald-400 mr-1.5"></i> {{ $bazar->tanggal_mulai->format('d M') }} – {{ $bazar->tanggal_selesai->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $bazar->deskripsi }}
                                </p>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                                    <p class="font-semibold text-slate-800 flex items-center gap-1.5">
                                        <i class="fa-solid fa-map-pin text-emerald-600"></i> {{ $bazar->lokasi }}
                                    </p>
                                    <p class="text-slate-500 text-[11px]">{{ $bazar->alamat_lengkap ?: 'Kawasan Sangatta, Kutai Timur' }}</p>
                                </div>
                            </div>

                            <!-- Quota Indicator -->
                            <div class="pt-2">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Kapasitas Peserta:</span>
                                    <span class="font-bold {{ $bazar->sisa_kuota > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                        Sisa {{ $bazar->sisa_kuota }} dari {{ $bazar->kuota_peserta }} Lapak
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    @php
                                        $terisi = max(0, $bazar->kuota_peserta - $bazar->sisa_kuota);
                                        $pct = $bazar->kuota_peserta > 0 ? min(100, round(($terisi / $bazar->kuota_peserta) * 100)) : 0;
                                    @endphp
                                    <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                            <a href="{{ route('bazar.show', $bazar->slug) }}" class="flex-1 py-2.5 text-center rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition">
                                Detail Informasi
                            </a>
                            @if(in_array($bazar->status, ['upcoming', 'ongoing']) && $bazar->sisa_kuota > 0)
                                <a href="{{ route('bazar.show', $bazar->slug) }}#daftar" class="flex-1 py-2.5 text-center rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-sm transition">
                                    Daftar Booth &rarr;
                                </a>
                            @else
                                <span class="flex-1 py-2.5 text-center rounded-xl text-xs font-semibold text-slate-400 bg-slate-200/80 cursor-not-allowed">
                                    Pendaftaran Ditutup
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $bazars->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 p-8" data-reveal>
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Event Bazar Sesuai Kriteria</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Coba ubah kata kunci pencarian atau ganti status filter untuk melihat event lainnya.
                </p>
                <a href="{{ route('bazar.index') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
