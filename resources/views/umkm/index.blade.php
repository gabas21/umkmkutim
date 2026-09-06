@extends('layouts.app')

@section('title', 'Katalog Direktori UMKM — Kabupaten Kutai Timur')

@section('content')
<!-- Header Banner -->
<div class="bg-slate-900 text-white py-12 border-b border-slate-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Pangkalan Data Daerah</span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white">Direktori UMKM Kutai Timur</h1>
            <p class="text-sm sm:text-base text-slate-300">Telusuri seluruh profil usaha mikro, kecil, dan menengah yang terdaftar di 18 kecamatan Kabupaten Kutai Timur.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-8">
        <form action="{{ route('umkm.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3.5 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha, produk, alamat..." class="w-full pl-9 pr-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Filter Kecamatan -->
            <div class="relative">
                <select name="kecamatan" class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700">
                    <option value="">Semua Kecamatan</option>
                    @foreach($daftarKecamatan as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3.5 top-3.5 text-xs pointer-events-none"></i>
            </div>

            <!-- Filter Kategori -->
            <div class="relative">
                <select name="kategori" class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3.5 top-3.5 text-xs pointer-events-none"></i>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter text-xs"></i> Filter
                </button>
                @if(request()->hasAny(['q', 'kecamatan', 'kategori', 'sort', 'status_klaim']))
                    <a href="{{ route('umkm.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm rounded-xl transition flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters & Result Count -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6 text-xs text-slate-500">
        <div>
            Menampilkan <span class="font-bold text-slate-900">{{ $umkmList->total() }}</span> unit usaha terdaftar
        </div>
        <div class="flex items-center gap-2">
            <span>Urutkan:</span>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" class="px-2.5 py-1 rounded-md {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Terbaru</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" class="px-2.5 py-1 rounded-md {{ request('sort') == 'rating' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Rating Tertinggi</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'populer']) }}" class="px-2.5 py-1 rounded-md {{ request('sort') == 'populer' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Terpopuler</a>
        </div>
    </div>

    <!-- UMKM Grid Cards -->
    @if($umkmList->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($umkmList as $umkm)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-emerald-500/50 transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Card Banner -->
                        <div class="h-36 bg-gradient-to-br from-emerald-800 to-slate-900 p-3.5 relative flex flex-col justify-between text-white">
                            <div class="flex justify-between items-start">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-white/20 backdrop-blur-md border border-white/20 truncate max-w-[150px]">
                                    {{ $umkm->kategori?->nama ?? 'Komoditas' }}
                                </span>
                                @if($umkm->status_klaim === 'terverifikasi')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-circle-check"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/90 text-white flex items-center gap-1">
                                        <i class="fa-solid fa-database"></i> Dinas
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[11px] text-amber-300 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-star"></i> {{ number_format($umkm->rating, 1) }}
                                </span>
                                <h3 class="text-sm font-bold text-white leading-snug line-clamp-1 mt-0.5">{{ $umkm->nama_usaha }}</h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-2">
                            <div class="text-xs font-semibold text-emerald-700 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot"></i> Kec. {{ $umkm->kecamatan }}
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $umkm->deskripsi }}
                            </p>
                            <p class="text-[11px] text-slate-400 truncate">
                                {{ $umkm->alamat }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between gap-2 mt-2">
                        <a href="{{ route('umkm.show', $umkm->slug) }}" class="flex-grow py-2 text-center text-xs font-bold rounded-lg bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 transition">
                            Detail Usaha
                        </a>
                        @if($umkm->status_klaim !== 'terverifikasi')
                            <a href="{{ route('klaim.create', $umkm->slug) }}" class="py-2 px-2.5 text-xs font-bold rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 transition" title="Klaim Usaha Ini">
                                <i class="fa-solid fa-certificate"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-10">
            {{ $umkmList->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 p-8 space-y-4">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-store-slash"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada data UMKM yang cocok</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto">Silakan coba ganti kata kunci pencarian atau bersihkan filter kecamatan dan kategori.</p>
            <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition">
                Reset Filter Pencarian
            </a>
        </div>
    @endif
</div>
@endsection
