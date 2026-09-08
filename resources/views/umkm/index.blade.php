@extends('layouts.app')

@section('title', 'Direktori Resmi UMKM Kutai Timur — 18 Kecamatan Terdata')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-14 border-b border-emerald-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex items-center gap-2 text-xs text-emerald-300 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <span class="text-white font-semibold">Direktori UMKM</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-400/30 text-xs text-emerald-200 mb-3 font-semibold">
                    <i class="fa-solid fa-database text-emerald-400"></i>
                    <span>Basis Data Terpadu Diskop & UMKM Kutai Timur</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Direktori Usaha & Produk Unggulan
                </h1>
                <p class="text-sm sm:text-base text-slate-300 mt-2 leading-relaxed">
                    Jelajahi profil ribuan pelaku usaha mikro, kecil, dan menengah di 18 kecamatan Kabupaten Kutai Timur. Lengkap dengan koordinat lokasi dan status verifikasi legalitas dinas.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('peta.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold text-white transition flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-300"></i> Buka Peta Full
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-xs font-bold text-slate-950 transition shadow-md shadow-amber-950/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-plus-circle"></i> Daftarkan Usaha
                </a>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter & Search Bar (Clean Corporate with Glass Panel) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm mb-8" data-reveal>
        <form action="{{ route('umkm.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
            <!-- Search Keyword (Span 4) -->
            <div class="lg:col-span-4 relative flex items-center">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha, produk, amplang..." class="w-full pl-9 pr-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:bg-white focus:outline-none text-slate-900 font-medium">
            </div>

            <!-- Filter Kecamatan (Span 3) -->
            <div class="lg:col-span-3 relative flex items-center">
                <i class="fa-solid fa-location-dot text-slate-400 absolute left-3.5 text-xs"></i>
                <select name="kecamatan" class="w-full pl-9 pr-8 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:bg-white focus:outline-none appearance-none text-slate-700 font-medium cursor-pointer">
                    <option value="">Semua Kecamatan (18)</option>
                    @foreach($daftarKecamatan as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 text-xs pointer-events-none"></i>
            </div>

            <!-- Filter Kategori (Span 3) -->
            <div class="lg:col-span-3 relative flex items-center">
                <i class="fa-solid fa-tag text-slate-400 absolute left-3.5 text-xs"></i>
                <select name="kategori" class="w-full pl-9 pr-8 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:bg-white focus:outline-none appearance-none text-slate-700 font-medium cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 text-xs pointer-events-none"></i>
            </div>

            <!-- Submit Button (Span 2) -->
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="flex-grow py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                    <i class="fa-solid fa-filter text-xs"></i> Terapkan
                </button>
                @if(request()->hasAny(['q', 'kecamatan', 'kategori', 'sort', 'status_klaim']))
                    <a href="{{ route('umkm.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs rounded-xl transition flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters & Result Count -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6 text-xs text-slate-500" data-reveal>
        <div>
            Menampilkan <span class="font-bold text-slate-900">{{ $umkmList->total() }}</span> unit usaha terdaftar
        </div>
        <div class="flex items-center gap-2">
            <span>Urutkan:</span>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-emerald-700 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Terbaru</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('sort') == 'rating' ? 'bg-emerald-700 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Rating</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'populer']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('sort') == 'populer' ? 'bg-emerald-700 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">Terpopuler</a>
        </div>
    </div>

    <!-- UMKM Grid Cards -->
    @if($umkmList->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-reveal>
            @foreach($umkmList as $umkm)
                <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden hover:shadow-xl hover:border-emerald-500/50 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Card Banner with Gradient -->
                        <div class="h-36 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 p-4 relative flex flex-col justify-between text-white">
                            <div class="flex justify-between items-start">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 backdrop-blur-md border border-white/25 truncate max-w-[140px]">
                                    {{ $umkm->kategori?->nama ?? 'Komoditas' }}
                                </span>
                                @if($umkm->status_klaim === 'terverifikasi')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-xs">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/90 text-white flex items-center gap-1">
                                        <i class="fa-solid fa-database text-[9px]"></i> Dinas
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[11px] text-amber-300 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-star text-amber-400"></i> {{ number_format($umkm->rating, 1) }}
                                    <span class="text-slate-400 font-normal">({{ $umkm->jumlah_review }} ulasan)</span>
                                </span>
                                <h3 class="text-sm font-black text-white leading-snug line-clamp-1 mt-0.5 group-hover:text-emerald-300 transition">{{ $umkm->nama_usaha }}</h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2.5">
                            <div class="text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Kec. {{ $umkm->kecamatan }}</span>
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $umkm->deskripsi ?: 'Usaha lokal Kabupaten Kutai Timur terdata dalam pangkalan data resmi Diskop & UKM.' }}
                            </p>
                            <p class="text-[11px] text-slate-400 truncate pt-1">
                                <i class="fa-solid fa-map-pin text-slate-300 mr-1"></i> {{ $umkm->alamat }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-5 pt-0 border-t border-slate-100 flex items-center justify-between gap-2 mt-2">
                        <a href="{{ route('umkm.show', $umkm->slug) }}" class="flex-grow py-2 text-center text-xs font-bold rounded-xl bg-slate-100 hover:bg-emerald-700 hover:text-white text-slate-700 transition">
                            Lihat Profil
                        </a>
                        @if($umkm->status_klaim !== 'terverifikasi')
                            <a href="{{ route('klaim.create', $umkm->slug) }}" class="py-2 px-3 text-xs font-bold rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 transition" title="Klaim Usaha Ini">
                                <i class="fa-solid fa-certificate"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-12">
            {{ $umkmList->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 p-8 space-y-4" data-reveal>
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-store-slash"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada data UMKM yang cocok</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">Silakan coba ganti kata kunci pencarian atau bersihkan filter kecamatan dan kategori.</p>
            <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-xs font-bold rounded-xl hover:bg-emerald-800 transition">
                Reset Filter Pencarian
            </a>
        </div>
    @endif
</div>
@endsection
