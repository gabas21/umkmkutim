@extends('layouts.app')

@section('title', 'UMKM KUTIM — Direktori Resmi & Peta Digital Pelaku Usaha Kutai Timur')

@section('content')
<!-- Hero Section -->
<section class="relative hero-mesh-bg text-white overflow-hidden pt-14 pb-24 md:pt-20 md:pb-32 border-b border-emerald-950/60">
    <!-- Ambient Glow Lighting -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-36 left-1/2 -translate-x-1/2 w-[800px] h-[380px] bg-emerald-500/15 rounded-full blur-[140px]"></div>
        <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-[130px]"></div>
        <div class="absolute top-1/3 -left-32 w-80 h-80 bg-teal-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.06)_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-5">
            <!-- Official Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-emerald-950/80 backdrop-blur-md border border-emerald-500/30 text-xs font-semibold text-emerald-300 shadow-lg shadow-black/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
                </span>
                <span>Portal Resmi Diskop & UMKM Kabupaten Kutai Timur</span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-[1.18] text-white">
                Direktori Terpadu & Ekosistem Digital <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-emerald-200 via-teal-100 to-amber-200 bg-clip-text text-transparent">
                    UMKM Kutai Timur
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base text-slate-300/90 max-w-2xl mx-auto leading-relaxed font-normal">
                Temukan puluhan ribu produk unggulan, kuliner khas Sangatta, cinderamata Dayak, dan aneka jasa di 18 kecamatan se-Kabupaten Kutai Timur.
            </p>

            <!-- Floating Search Box -->
            <div class="pt-3 max-w-3xl mx-auto">
                <form action="{{ route('umkm.index') }}" method="GET" class="bg-white/95 backdrop-blur-md p-2 sm:p-2.5 rounded-2xl shadow-2xl shadow-black/50 border border-white/40 flex flex-col md:flex-row gap-2.5 items-stretch text-slate-800 ring-1 ring-black/5">
                    <!-- Search query input -->
                    <div class="relative flex-grow flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-4 text-sm"></i>
                        <input type="text" name="q" placeholder="Cari nama usaha, produk, amplang, batik..." class="w-full pl-11 pr-4 py-3.5 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white bg-slate-50/80 border border-slate-200/80 transition">
                    </div>

                    <!-- Dropdown filter kecamatan -->
                    <div class="relative md:w-60 flex items-center">
                        <i class="fa-solid fa-location-dot text-slate-400 absolute left-4 text-sm"></i>
                        <select name="kecamatan" class="w-full pl-10 pr-9 py-3.5 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white bg-slate-50/80 border border-slate-200/80 appearance-none text-slate-700 transition">
                            <option value="">Semua Kecamatan (18)</option>
                            @foreach($daftarKecamatan as $kec)
                                <option value="{{ $kec }}">{{ $kec }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down text-slate-400 absolute right-4 text-xs pointer-events-none"></i>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="px-7 py-3.5 bg-gradient-to-r from-emerald-700 to-emerald-800 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold text-sm rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-md shadow-emerald-950/20 active:scale-[0.98]">
                        <span>Cari Usaha</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Quick Keyword Tags -->
            <div class="flex flex-wrap justify-center items-center gap-2 pt-2 text-xs text-slate-300">
                <span class="font-semibold text-amber-300/90 flex items-center gap-1.5">
                    <i class="fa-solid fa-fire text-amber-400 text-[11px]"></i> Populer:
                </span>
                <a href="{{ route('umkm.index', ['q' => 'Amplang']) }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-emerald-200 border border-white/10 backdrop-blur-sm transition">Amplang Sangatta</a>
                <a href="{{ route('umkm.index', ['q' => 'Batik']) }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-emerald-200 border border-white/10 backdrop-blur-sm transition">Batik Wakaroros</a>
                <a href="{{ route('umkm.index', ['q' => 'Manik']) }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-emerald-200 border border-white/10 backdrop-blur-sm transition">Anyaman Manik Dayak</a>
                <a href="{{ route('umkm.index', ['q' => 'Madu']) }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-emerald-200 border border-white/10 backdrop-blur-sm transition">Madu Hutan</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Highlight Counter (Overlapping Cards) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xl shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-2xl transition duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center text-xl group-hover:bg-emerald-700 group-hover:text-white transition duration-200">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ number_format($totalUmkm) }}</div>
                <div class="text-xs text-slate-500 font-medium">UMKM Terdata</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xl shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-2xl transition duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center text-xl group-hover:bg-amber-600 group-hover:text-white transition duration-200">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">18</div>
                <div class="text-xs text-slate-500 font-medium">Kecamatan Aktif</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xl shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-2xl transition duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-100 text-sky-700 flex items-center justify-center text-xl group-hover:bg-sky-600 group-hover:text-white transition duration-200">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $totalKategori }}</div>
                <div class="text-xs text-slate-500 font-medium">Sektor Komoditas</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xl shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-2xl transition duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 text-purple-700 flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition duration-200">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ number_format($totalTerverifikasi) }}</div>
                <div class="text-xs text-slate-500 font-medium">Klaim Terverifikasi</div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Industri Grid -->
<section class="py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Jelajahi Usaha</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kategori Usaha & Produk</h2>
                <p class="text-sm text-slate-500 mt-1">Klasifikasi bidang usaha mikro, kecil, dan menengah di Kutai Timur</p>
            </div>
            <a href="{{ route('umkm.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5 group">
                <span>Lihat Semua Direktori</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-200"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($kategoriList as $kat)
                <a href="{{ route('umkm.index', ['kategori' => $kat->id]) }}" class="group bg-white rounded-2xl p-6 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-500/5 transition duration-200 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-600 group-hover:text-white transition duration-200">
                            @if($kat->icon == 'utensils')
                                <i class="fa-solid fa-utensils"></i>
                            @elseif($kat->icon == 'shirt')
                                <i class="fa-solid fa-shirt"></i>
                            @elseif($kat->icon == 'palette')
                                <i class="fa-solid fa-palette"></i>
                            @elseif($kat->icon == 'sprout')
                                <i class="fa-solid fa-seedling"></i>
                            @elseif($kat->icon == 'fish')
                                <i class="fa-solid fa-fish"></i>
                            @elseif($kat->icon == 'briefcase')
                                <i class="fa-solid fa-briefcase"></i>
                            @elseif($kat->icon == 'shopping-bag')
                                <i class="fa-solid fa-bag-shopping"></i>
                            @else
                                <i class="fa-solid fa-spa"></i>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $kat->umkm_count }} usaha</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition text-sm sm:text-base">{{ $kat->nama }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Lihat profil & lokasi usaha &rarr;</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Interactive Map Preview Section (Leaflet + Clustering) -->
<section id="seksi-peta" class="py-16 bg-slate-100 border-y border-slate-200 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Pemetaan Geografis</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Peta Sebaran Titik UMKM</h2>
                <p class="text-sm text-slate-500 mt-1">Visualisasi koordinat usaha dengan teknologi Leaflet Marker Clustering yang ringan dan cepat</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Terverifikasi
                </span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded bg-slate-200 text-slate-700">
                    <i class="fa-solid fa-clock text-[10px]"></i> Belum Diklaim
                </span>
            </div>
        </div>

        <!-- Map Container Card -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl border border-slate-200">
            <!-- Map Info Bar -->
            <div class="px-5 py-3.5 bg-slate-900 text-white flex flex-wrap justify-between items-center text-xs gap-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-amber-400"></i>
                    <span>Wilayah Kabupaten Kutai Timur (Sangatta, Bengalon, Kongbeng, Sangkulirang, dll)</span>
                </div>
                <div class="text-slate-400">Klik cluster angka atau pin lokasi untuk melihat ringkasan usaha</div>
            </div>

            <!-- Leaflet Map Div -->
            <div id="leaflet-preview-map" class="h-[480px] w-full z-10"></div>
        </div>
    </div>
</section>

<!-- UMKM Unggulan Section -->
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Rekomendasi Terbaik</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">UMKM Unggulan Daerah</h2>
                <p class="text-sm text-slate-500 mt-1">Usaha berperingkat tinggi dengan ulasan terbaik dari pelanggan dan masyarakat</p>
            </div>
            <a href="{{ route('umkm.index', ['sort' => 'rating']) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5 group">
                <span>Lihat Peringkat Lengkap</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-200"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredUmkm as $umkm)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-emerald-500/50 transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Card Header / Image Placeholder -->
                        <div class="h-44 w-full bg-gradient-to-br from-emerald-800 to-slate-900 relative p-4 flex flex-col justify-between text-white">
                            <div class="flex justify-between items-start">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-white">
                                    {{ $umkm->kategori?->nama ?? 'Komoditas' }}
                                </span>
                                @if($umkm->status_klaim === 'terverifikasi')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/90 text-white flex items-center gap-1">
                                        <i class="fa-solid fa-database text-[10px]"></i> Data Dinas
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs text-amber-300 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-star"></i> {{ number_format($umkm->rating, 1) }} ({{ $umkm->jumlah_review }} ulasan)
                                </span>
                                <h3 class="text-lg font-bold text-white mt-1 leading-snug line-clamp-1">{{ $umkm->nama_usaha }}</h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-3">
                            <p class="text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                                <i class="fa-solid fa-location-dot text-emerald-600"></i>
                                <span>Kec. {{ $umkm->kecamatan }}</span>
                            </p>
                            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $umkm->deskripsi }}
                            </p>
                            <div class="text-xs text-slate-400 truncate">
                                <i class="fa-solid fa-map-pin mr-1"></i> {{ $umkm->alamat }}
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-5 pt-0 flex items-center justify-between gap-3 border-t border-slate-100 mt-2">
                        <a href="{{ route('umkm.show', $umkm->slug) }}" class="flex-grow py-2.5 text-center text-xs font-bold rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 transition">
                            Lihat Profil Detail
                        </a>
                        @if($umkm->status_klaim !== 'terverifikasi')
                            <a href="{{ route('klaim.create', $umkm->slug) }}" class="py-2.5 px-3 text-xs font-bold rounded-xl border border-amber-300 text-amber-800 bg-amber-50 hover:bg-amber-100 transition whitespace-nowrap" title="Klaim Usaha Ini">
                                <i class="fa-solid fa-certificate"></i> Klaim
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Banner: Pelaku Usaha Claim / Register -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl hero-mesh-bg border border-emerald-800/60 p-8 sm:p-12 text-white relative overflow-hidden shadow-2xl">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -left-16 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-2xl relative z-10 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950 shadow-sm">
                    <i class="fa-solid fa-award"></i> Fasilitasi Gratis Pemkab Kutim
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-tight text-white">
                    Usaha Anda Berada di Kutai Timur? Klaim & Verifikasi Data Anda Sekarang!
                </h2>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                    Data UMKM yang diimpor dari basis data dinas dapat Anda klaim kepemilikannya dengan mengunggah KTP dan foto usaha. Dapatkan badge terverifikasi resmi dan kelola profil produk Anda secara mandiri.
                </p>

                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 transition shadow-lg shadow-amber-950/20 flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-id-card"></i> Buat Akun & Ajukan Klaim
                    </a>
                    <a href="{{ route('umkm.index') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 border border-white/20 text-white transition flex items-center gap-2 backdrop-blur-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Data Usaha Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita & Pengumuman Dinas Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Pusat Informasi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kabar Dinas & Pelatihan UMKM</h2>
                <p class="text-sm text-slate-500 mt-1">Program bantuan, sertifikasi halal gratis, pelatihan wirausaha, dan pameran daerah</p>
            </div>
            <a href="{{ route('berita.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5 group">
                <span>Lihat Semua Berita</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-200"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBerita as $b)
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition flex flex-col justify-between">
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
                            <span>Baca Selengkapnya</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Koordinat Pusat Kutai Timur (Sangatta / Central Kutim)
        const kutimCenter = [0.55, 117.50];
        const map = L.map('leaflet-preview-map', {
            scrollWheelZoom: false
        }).setView(kutimCenter, 9);

        // Tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors | Pemkab Kutai Timur'
        }).addTo(map);

        // Marker Cluster Group
        const markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        const pointsData = @json($mapPoints);

        pointsData.forEach(function (item) {
            if (!item.lat || !item.lng) return;

            const isVerified = item.status_klaim === 'terverifikasi';
            const markerColor = isVerified ? '#059669' : '#d97706';

            // Custom SVG icon marker
            const customIcon = L.divIcon({
                className: 'custom-leaflet-marker',
                html: `<div style="background-color: ${markerColor}; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px;">
                    <i class="fa-solid fa-store"></i>
                </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14],
                popupAnchor: [0, -14]
            });

            const marker = L.marker([item.lat, item.lng], { icon: customIcon });

            const popupHtml = `
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 220px; padding: 4px;">
                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #059669; margin-bottom: 2px;">
                        ${item.kategori} &bull; ${item.kecamatan}
                    </div>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 6px;">
                        ${item.nama}
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #d97706; font-weight: 600; margin-bottom: 8px;">
                        <i class="fa-solid fa-star"></i> ${item.rating}
                        <span style="font-size: 10px; color: #64748b; font-weight: 400;">(${item.status_klaim === 'terverifikasi' ? 'Terverifikasi' : 'Data Dinas'})</span>
                    </div>
                    <a href="${item.url}" style="display: block; text-align: center; background: #059669; color: white; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none;">
                        Lihat Detail Usaha &rarr;
                    </a>
                </div>
            `;

            marker.bindPopup(popupHtml);
            markers.addLayer(marker);
        });

        map.addLayer(markers);
    });
</script>
@endpush
