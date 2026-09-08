@extends('layouts.app')

@section('title', 'UMKM KUTIM — Ekosistem Resmi Direktori, Bazar, & Laporan UMKM Kutai Timur')

@section('content')
<!-- ========================================================================== -->
<!-- 1. HERO — Pengenalan Ekosistem Resmi UMKM Kutai Timur                      -->
<!-- ========================================================================== -->
<section class="relative text-white overflow-hidden py-24 sm:py-32 md:py-36 border-b border-emerald-950/80 bg-[#021813]">
    <!-- Backdrop: Kantor Bupati Kutai Timur (Bukit Pelangi) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
        <img src="{{ asset('Kantor_Bupati_Kutai_Timur.jpg') }}" 
             alt="Kawasan Bukit Pelangi Sangatta - Kantor Bupati Kutai Timur" 
             class="w-full h-full object-cover object-center filter brightness-[0.32] contrast-[1.15] saturate-[0.85] scale-105">
        
        <!-- Multi-layered Washes for Readability -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(2,44,34,0.55)_0%,rgba(2,24,19,0.88)_65%,rgba(2,20,16,0.98)_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#021813] via-transparent to-[#021813]/80"></div>
        <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.04)_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
    </div>

    <!-- Centered Editorial Introduction -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-reveal>
        <!-- Official Authority Seal -->
        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-emerald-950/85 backdrop-blur-md border border-emerald-500/30 text-xs font-semibold text-emerald-300 shadow-lg">
            <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-4.5 w-auto object-contain">
            <span>Pemerintah Kabupaten Kutai Timur</span>
            <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
            <span class="text-slate-300 font-normal">Dinas Koperasi & UKM</span>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12] max-w-5xl mx-auto mt-6">
            Ekosistem Digital & Direktori <br class="hidden sm:inline">
            Pelaku Usaha Kutai Timur
        </h1>

        <!-- Pure Informative Introduction -->
        <p class="text-base sm:text-xl text-emerald-100/90 max-w-3xl mx-auto leading-relaxed font-normal mt-6">
            Gerbang resmi informasi, pemberdayaan, dan kurasi produk unggulan daerah. Menghubungkan potensi ribuan UMKM di 18 kecamatan menuju kemandirian ekonomi Kutai Timur.
        </p>

        <!-- Two Clear Action Pathways -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#pendaftaran-umkm" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-sm font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-xl shadow-amber-950/30 transition active:scale-95 flex items-center justify-center gap-2.5">
                <i class="fa-solid fa-id-card text-xs"></i>
                <span>Pendaftaran & Klaim UMKM</span>
            </a>
            <a href="#peta-sebaran-profil" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-md transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-map-location-dot text-emerald-400"></i>
                <span>Peta Sebaran & Profil Usaha</span>
            </a>
        </div>
    </div>
</section>


<!-- ========================================================================== -->
<!-- 2. PENDAFTARAN UMKM — Onboarding Usaha Baru & Klaim Profil Bisnis          -->
<!-- ========================================================================== -->
<section id="pendaftaran-umkm" class="py-16 md:py-24 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-12" data-reveal>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3 border border-emerald-200">
                <i class="fa-solid fa-store text-emerald-600"></i> Layanan Resmi Pelaku Usaha
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Pendaftaran & Fasilitasi Usaha</h2>
            <p class="text-base text-slate-600 mt-2 leading-relaxed">
                Daftarkan usaha baru atau klaim profil bisnis yang sudah terdata di Dinas Koperasi & UKM untuk mendapatkan legalitas resmi, promosi daerah, dan prioritas fasilitas pemerintah.
            </p>
        </div>

        <!-- Dual Cards: Usaha Baru vs Klaim Usaha -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-reveal>
            <!-- Card 1: Pendaftaran Pelaku Usaha Baru -->
            <div class="rounded-3xl border-2 border-emerald-500/30 bg-gradient-to-br from-emerald-50/70 via-white to-white p-8 sm:p-10 flex flex-col justify-between shadow-lg shadow-emerald-950/5 relative overflow-hidden group hover:border-emerald-500 transition duration-300">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-200/40 rounded-full blur-2xl group-hover:bg-emerald-300/40 transition"></div>

                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-700 text-white shadow-xs">
                            Usaha Belum Terdaftar
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Daftar Pelaku Usaha Baru</h3>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                            Bagi wirausaha dan pengrajin lokal Kutai Timur yang belum pernah terdata di pangkalan data resmi Diskop & UKM.
                        </p>
                    </div>

                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
                            <span>Masuk direktori resmi & peta GIS sebaran UMKM Kutim</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
                            <span>Fasilitasi pembuatan NIB OSS & Sertifikasi Halal gratis</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
                            <span>Akses langsung pendaftaran stan bazar & expo daerah</span>
                        </li>
                    </ul>
                </div>

                <div class="relative z-10 pt-8 mt-6 border-t border-emerald-100">
                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl text-sm font-bold text-white bg-emerald-700 hover:bg-emerald-800 shadow-md shadow-emerald-800/20 active:scale-98 transition">
                        <span>Daftar Akun Usaha Baru Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: Klaim Profil Usaha Terdaftar -->
            <div class="rounded-3xl border border-slate-200 bg-white p-8 sm:p-10 flex flex-col justify-between shadow-md hover:border-amber-400 hover:shadow-xl transition duration-300 relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-amber-100/40 rounded-full blur-2xl group-hover:bg-amber-200/40 transition"></div>

                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            Data Hasil Survei Lapangan
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Klaim Usaha Anda</h3>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                            Sudah pernah disurvei petugas atau nama usaha Anda sudah tampil di peta direktori? Segera klaim kepemilikan Anda.
                        </p>
                    </div>

                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-amber-600 mt-0.5 text-sm"></i>
                            <span>Ambil alih dan kelola akun resmi profil usaha Anda</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-amber-600 mt-0.5 text-sm"></i>
                            <span>Perbarui nomor WhatsApp pemesanan, jam buka, dan foto produk</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-amber-600 mt-0.5 text-sm"></i>
                            <span>Dapatkan lencana centang hijau "Terverifikasi Diskop"</span>
                        </li>
                    </ul>
                </div>

                <div class="relative z-10 pt-8 mt-6 border-t border-slate-100">
                    <a href="{{ route('umkm.index', ['status_klaim' => 'belum_klaim']) }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl text-sm font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md shadow-amber-500/20 active:scale-98 transition">
                        <span>Cari & Klaim Profil Usaha Saya</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3 Alur Pendaftaran Ringkas -->
        <div class="mt-12 p-6 sm:p-8 rounded-2xl bg-slate-50 border border-slate-200" data-reveal>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-800">
                <div class="flex items-start gap-3.5">
                    <span class="w-8 h-8 rounded-full bg-emerald-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0">1</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Registrasi Akun</h4>
                        <p class="text-xs text-slate-500 mt-1">Lengkapi data pemilik, NIK, kontak WhatsApp, dan kata sandi login.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3.5">
                    <span class="w-8 h-8 rounded-full bg-emerald-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0">2</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Input Data Usaha & Lokasi</h4>
                        <p class="text-xs text-slate-500 mt-1">Sematkan koordinat lokasi toko di peta GIS serta unggah foto produk.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3.5">
                    <span class="w-8 h-8 rounded-full bg-emerald-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0">3</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Verifikasi & Fasilitasi</h4>
                        <p class="text-xs text-slate-500 mt-1">Tim Diskop memverifikasi data dan profil langsung aktif di direktori publik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========================================================================== -->
<!-- 3. PETA SEBARAN + PROFIL UMKM — Leaflet GIS & Kurasi Usaha Daerah          -->
<!-- ========================================================================== -->
<section id="peta-sebaran-profil" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8" data-reveal>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full mb-3">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i> Pemetaan Geografis & Kurasi
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Peta Sebaran & Profil UMKM</h2>
                <p class="text-base text-slate-600 mt-1">Eksplorasi titik sebaran usaha di 18 kecamatan beserta profil produk lokal terbaik pilihan masyarakat</p>
            </div>
            <a href="{{ route('umkm.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition flex items-center gap-2 shadow-sm">
                <span>Buka Direktori Lengkap ({{ $totalUmkm }} UMKM)</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Interactive Leaflet Map Preview Container -->
        <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-slate-200 mb-14" data-reveal>
            <div class="px-6 py-4 bg-slate-950 text-white flex flex-wrap justify-between items-center text-xs gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold">GIS Live Map: 18 Kecamatan Kabupaten Kutai Timur</span>
                </div>
                <div class="flex items-center gap-4 text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Terverifikasi Diskop
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Data Lapangan
                    </span>
                </div>
            </div>

            <!-- Leaflet Map Canvas -->
            <div id="leaflet-preview-map" class="h-[480px] w-full z-10"></div>

            <div class="px-6 py-3.5 bg-slate-100 border-t border-slate-200 text-xs text-slate-600 flex flex-wrap justify-between items-center gap-2">
                <span>Klik cluster angka untuk memperbesar peta, atau klik marker toko untuk melihat informasi dan rute.</span>
                <span class="font-semibold text-emerald-800">{{ count($mapPoints) }} Titik Lokasi Aktif</span>
            </div>
        </div>

        <!-- Profil UMKM Unggulan Daerah -->
        <div class="mb-6 flex items-center justify-between" data-reveal>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Profil UMKM Pilihan Daerah</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Produk lokal berperingkat tinggi dengan ulasan terbaik dari masyarakat</p>
            </div>
            <a href="{{ route('umkm.index', ['sort' => 'rating']) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                <span>Lihat Semua Rating</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        @if($featuredUmkm->isNotEmpty())
            @php
                $topUmkm = $featuredUmkm->first();
                $otherFeatured = $featuredUmkm->slice(1, 4);
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Big Featured Hero Card (Span 7) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-reveal="left">
                    <div class="relative h-64 sm:h-72 bg-gradient-to-tr from-[#032b21] via-emerald-900 to-slate-900 p-6 sm:p-8 flex flex-col justify-between text-white overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-60 h-60 bg-emerald-400/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 left-10 w-48 h-48 bg-amber-400/15 rounded-full blur-2xl"></div>

                        <div class="relative z-10 flex justify-between items-start">
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center gap-1.5">
                                <i class="fa-solid fa-award text-amber-300"></i>
                                <span>Pilihan Utama &bull; {{ $topUmkm->kategori?->nama ?? 'Komoditas' }}</span>
                            </span>
                            @if($topUmkm->status_klaim === 'terverifikasi')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-circle-check text-xs"></i> Terverifikasi Diskop
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/90 text-white flex items-center gap-1">
                                    <i class="fa-solid fa-database text-xs"></i> Data Dinas
                                </span>
                            @endif
                        </div>

                        <div class="relative z-10 space-y-2">
                            <div class="flex items-center gap-2 text-amber-300 font-bold text-sm">
                                <div class="flex text-amber-400 text-xs">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <span>{{ number_format($topUmkm->rating, 1) }} ({{ $topUmkm->jumlah_review }} ulasan warga)</span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-black text-white leading-tight drop-shadow-sm">{{ $topUmkm->nama_usaha }}</h3>
                            <p class="text-xs sm:text-sm text-emerald-100/90 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-amber-400"></i>
                                <span>Kecamatan {{ $topUmkm->kecamatan }} &bull; {{ $topUmkm->alamat }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-7 space-y-4 flex-grow flex flex-col justify-between">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $topUmkm->deskripsi ?: 'Usaha lokal unggulan Kutai Timur yang menyediakan produk berkualitas tinggi dengan legalitas terdaftar di Dinas Koperasi dan UKM.' }}
                        </p>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fa-solid fa-id-card text-emerald-600"></i>
                                <span>NIB Terverifikasi OSS</span>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($topUmkm->status_klaim !== 'terverifikasi')
                                    <a href="{{ route('klaim.create', $topUmkm->slug) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 transition">
                                        Klaim Usaha
                                    </a>
                                @endif
                                <a href="{{ route('umkm.show', $topUmkm->slug) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm flex items-center gap-1.5">
                                    <span>Lihat Profil & Peta</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Stacked Cards (Span 5) -->
                <div class="lg:col-span-5 flex flex-col gap-4" data-reveal="right">
                    @foreach($otherFeatured as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                        {{ $item->kategori?->nama ?? 'Usaha' }}
                                    </span>
                                    <span class="text-xs text-amber-600 font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-star text-amber-400 text-[11px]"></i> {{ number_format($item->rating, 1) }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 hover:text-emerald-700 transition leading-snug">
                                    <a href="{{ route('umkm.show', $item->slug) }}">{{ $item->nama_usaha }}</a>
                                </h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    <i class="fa-solid fa-location-dot text-emerald-600 text-[10px] mr-1"></i> Kec. {{ $item->kecamatan }}
                                </p>
                            </div>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400">{{ $item->jumlah_review }} ulasan</span>
                                <a href="{{ route('umkm.show', $item->slug) }}" class="font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                                    <span>Profil Lengkap</span>
                                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>


<!-- ========================================================================== -->
<!-- 4. BAZAR DAN REGISTRASI BAZAR — Agenda Expo & Pendaftaran Stan            -->
<!-- ========================================================================== -->
<section id="bazar-registrasi" class="py-16 md:py-24 bg-white border-b border-slate-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10" data-reveal>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-100 px-3 py-1 rounded-full mb-3">
                    <i class="fa-solid fa-tent text-amber-600"></i> Pameran & Promosi Produk
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Bazar & Registrasi Lapak</h2>
                <p class="text-base text-slate-600 mt-1">Daftarkan stan usaha Anda secara gratis atau kunjungi expo produk unggulan Kutai Timur</p>
            </div>
            <a href="{{ route('bazar.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1.5 group">
                <span>Lihat Seluruh Agenda Bazar</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-200"></i>
            </a>
        </div>

        @if(isset($upcomingBazar) && $upcomingBazar->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-reveal>
                @foreach($upcomingBazar as $bazar)
                    <div class="bg-gradient-to-br from-slate-900 via-[#032b21] to-[#021813] rounded-3xl p-8 text-white relative overflow-hidden shadow-xl border border-emerald-900/50 flex flex-col justify-between group hover:border-emerald-500/60 transition duration-300">
                        <div class="absolute -top-16 -right-16 w-56 h-56 bg-amber-500/15 rounded-full blur-3xl group-hover:bg-amber-500/25 transition"></div>

                        <div class="relative z-10 space-y-5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950 shadow-sm flex items-center gap-1.5">
                                    <i class="fa-solid fa-tent"></i> Kuota Stan Terbuka
                                </span>
                                <span class="text-xs text-emerald-300 font-medium flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>{{ $bazar->tanggal_mulai->format('d M') }} – {{ $bazar->tanggal_selesai->format('d M Y') }}</span>
                                </span>
                            </div>

                            <div>
                                <h3 class="text-2xl font-black text-white leading-snug group-hover:text-amber-300 transition">
                                    <a href="{{ route('bazar.show', $bazar->slug) }}">{{ $bazar->nama_bazar }}</a>
                                </h3>
                                <p class="text-sm text-slate-300 mt-2 line-clamp-2 leading-relaxed font-normal">
                                    {{ $bazar->deskripsi }}
                                </p>
                            </div>

                            <!-- Detail Lokasi & Sisa Kuota Lapak -->
                            <div class="space-y-2.5 text-xs text-slate-300 bg-white/5 rounded-2xl p-4.5 border border-white/10 backdrop-blur-sm">
                                <p class="flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-amber-400 w-4"></i>
                                    <span class="font-medium text-white">{{ $bazar->lokasi }}</span> (Kec. {{ $bazar->kecamatan }})
                                </p>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-users text-emerald-400 w-4"></i>
                                        <span>Kapasitas Booth:</span>
                                    </span>
                                    <strong class="text-amber-300 font-bold">Sisa {{ $bazar->sisa_kuota }} dari {{ $bazar->kuota_peserta }} Lapak</strong>
                                </div>
                                <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ $bazar->persen_terisi }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Registration Action -->
                        <div class="relative z-10 pt-6 mt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                            <span class="text-xs text-slate-400">Penyelenggara: Diskop UKM Kutim</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('bazar.show', $bazar->slug) }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition">
                                    Detail Event
                                </a>
                                <a href="{{ route('bazar.show', $bazar->slug) }}#daftar" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 transition shadow-md shadow-amber-950/30">
                                    Daftar Stan / Lapak &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                <i class="fa-solid fa-calendar-xmark text-3xl text-slate-400 mb-2"></i>
                <p class="text-sm font-semibold text-slate-600">Belum ada agenda bazar yang dibuka minggu ini.</p>
                <a href="{{ route('bazar.index') }}" class="text-xs font-bold text-emerald-700 mt-2 inline-block">Lihat arsip bazar &rarr;</a>
            </div>
        @endif
    </div>
</section>


<!-- ========================================================================== -->
<!-- 5. LAPORAN / DASHBOARD — Transparansi Data UMKM & Capaian Daerah           -->
<!-- ========================================================================== -->
<section id="laporan-dashboard" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10" data-reveal>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full mb-3">
                    <i class="fa-solid fa-chart-pie text-emerald-600"></i> Transparansi Publik
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Dashboard & Laporan Data UMKM</h2>
                <p class="text-base text-slate-600 mt-1">Pantauan komprehensif perkembangan pelaku usaha, legalitas NIB, dan sebaran wilayah di Kutai Timur</p>
            </div>
            <a href="{{ route('laporan.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm flex items-center gap-2">
                <span>Buka Dashboard Analitik Lengkap</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- 4 Key Executive KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10" data-reveal>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total UMKM</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <i class="fa-solid fa-store"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-slate-900">{{ number_format($totalUmkm) }}</div>
                    <div class="text-xs text-slate-500 mt-1">Usaha aktif terdata</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Legalitas NIB</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-emerald-700">{{ $persenTerverifikasi }}%</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $totalTerverifikasi }} usaha terverifikasi</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jangkauan Wilayah</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                        <i class="fa-solid fa-map"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-slate-900">18</div>
                    <div class="text-xs text-slate-500 mt-1">Kecamatan se-Kutim</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Indeks Kepuasan (IKM)</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                        <i class="fa-solid fa-star text-amber-500"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-black text-slate-900">{{ number_format($indeksRataRata, 1) }}<span class="text-base text-slate-400 font-normal">/5.0</span></div>
                    <div class="text-xs text-emerald-700 font-semibold mt-1">Sangat Baik ({{ $indeksPersen }}%)</div>
                </div>
            </div>
        </div>

        <!-- Sebaran Terbanyak per Kecamatan Preview Strip -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm" data-reveal>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Distribusi UMKM Berdasarkan Kecamatan</h3>
                    <p class="text-xs text-slate-500">Konsentrasi pelaku usaha terbesar di Kabupaten Kutai Timur</p>
                </div>
                <span class="text-xs font-semibold text-slate-400">Diperbarui real-time</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kecamatanStats as $stat)
                    @php
                        $percentage = round(($stat->total / $totalUmkm) * 100);
                    @endphp
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="font-bold text-slate-800">Kec. {{ $stat->kecamatan }}</span>
                            <span class="font-extrabold text-emerald-700">{{ $stat->total }} Usaha ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full transition-all duration-500" style="width: {{ max($percentage, 5) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


<!-- ========================================================================== -->
<!-- 6. SURVEY KEPUASAN LAYANAN — Indeks Kepuasan Masyarakat (IKM)              -->
<!-- ========================================================================== -->
<section id="survey-layanan" class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-8 p-4.5 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm flex items-center gap-3 shadow-xs" data-reveal>
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <!-- Left: IKM High-level Authority Highlight -->
            <div class="lg:col-span-5 space-y-6" data-reveal="left">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3 border border-emerald-200">
                        <i class="fa-solid fa-heart-pulse text-emerald-600"></i> Penjaminan Mutu Layanan
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Survey Kepuasan Layanan</h2>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                        Evaluasi publik masyarakat dan pelaku usaha Kutai Timur terhadap transparansi direktori, kemudahan perizinan, dan penyelenggaraan bazar.
                    </p>
                </div>

                <!-- IKM Score Widget -->
                <div class="p-6 rounded-3xl bg-gradient-to-br from-[#021813] to-[#022c22] text-white border border-emerald-900/60 shadow-xl relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Skor IKM Daerah</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/30 border border-emerald-400/40 text-emerald-200">
                            Status: Sangat Puas
                        </span>
                    </div>

                    <div class="flex items-baseline gap-3">
                        <span class="text-5xl font-black text-white">{{ number_format($indeksRataRata, 2) }}</span>
                        <span class="text-xl text-emerald-300/70 font-semibold">/ 5.00</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/10 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 block">Kemudahan Akses</span>
                            <span class="font-bold text-white mt-0.5 block">{{ number_format($avgKemudahan, 1) }} / 5.0</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Kecepatan Layanan</span>
                            <span class="font-bold text-white mt-0.5 block">{{ number_format($avgKecepatan, 1) }} / 5.0</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Keramahan Petugas</span>
                            <span class="font-bold text-white mt-0.5 block">{{ number_format($avgKeramahan, 1) }} / 5.0</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Kemanfaatan Program</span>
                            <span class="font-bold text-white mt-0.5 block">{{ number_format($avgKemanfaatan, 1) }} / 5.0</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-start gap-3">
                    <i class="fa-solid fa-shield-halved text-emerald-700 text-base mt-0.5"></i>
                    <div>
                        <strong class="text-slate-800 font-bold block">Survei Terbuka & Akuntabel</strong>
                        Setiap masukan dicatat langsung ke dalam laporan evaluasi kinerja Dinas Koperasi & UKM Kutai Timur.
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Quick Survey Form -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-lg" data-reveal="right">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Beri Penilaian Anda</h3>
                    <p class="text-xs text-slate-500 mt-1">Hanya butuh 30 detik untuk memberikan aspirasi Anda bagi kemajuan UMKM daerah</p>
                </div>

                <form action="{{ route('survey.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Anda (Opsional)</label>
                            <input type="text" name="nama_responden" placeholder="Contoh: Budi Santoso / Anonim" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pekerjaan / Usaha</label>
                            <input type="text" name="pekerjaan" placeholder="Contoh: Pelaku UMKM / Konsumen" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Layanan yang Dinilai <span class="text-red-500">*</span></label>
                        <select name="modul" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                            <option value="umkm">Direktori & Pencarian Usaha UMKM</option>
                            <option value="bazar">Pendaftaran & Fasilitasi Bazar Lapak</option>
                            <option value="pelatihan">Pendampingan Izin & Legalitas</option>
                            <option value="layanan_umum" selected>Layanan Portal Umum & Informasi</option>
                        </select>
                    </div>

                    <!-- 4 Star Ratings -->
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Kemudahan -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <label class="block text-xs font-bold text-slate-800 mb-2">Kemudahan Mengakses Informasi</label>
                                <div class="flex items-center gap-2">
                                    @for($v = 1; $v <= 5; $v++)
                                        <label class="flex-1 text-center cursor-pointer">
                                            <input type="radio" name="nilai_kemudahan" value="{{ $v }}" {{ $v == 5 ? 'checked' : '' }} class="sr-only peer">
                                            <div class="py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700 transition">
                                                {{ $v }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Kecepatan -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <label class="block text-xs font-bold text-slate-800 mb-2">Kecepatan & Responsivitas Sistem</label>
                                <div class="flex items-center gap-2">
                                    @for($v = 1; $v <= 5; $v++)
                                        <label class="flex-1 text-center cursor-pointer">
                                            <input type="radio" name="nilai_kecepatan" value="{{ $v }}" {{ $v == 5 ? 'checked' : '' }} class="sr-only peer">
                                            <div class="py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700 transition">
                                                {{ $v }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Keramahan -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <label class="block text-xs font-bold text-slate-800 mb-2">Kejelasan Prosedur & Petunjuk</label>
                                <div class="flex items-center gap-2">
                                    @for($v = 1; $v <= 5; $v++)
                                        <label class="flex-1 text-center cursor-pointer">
                                            <input type="radio" name="nilai_keramahan" value="{{ $v }}" {{ $v == 5 ? 'checked' : '' }} class="sr-only peer">
                                            <div class="py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700 transition">
                                                {{ $v }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Kemanfaatan -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <label class="block text-xs font-bold text-slate-800 mb-2">Kemanfaatan bagi Usaha Anda</label>
                                <div class="flex items-center gap-2">
                                    @for($v = 1; $v <= 5; $v++)
                                        <label class="flex-1 text-center cursor-pointer">
                                            <input type="radio" name="nilai_kemanfaatan" value="{{ $v }}" {{ $v == 5 ? 'checked' : '' }} class="sr-only peer">
                                            <div class="py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700 transition">
                                                {{ $v }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Saran & Masukan Konstruktif (Opsional)</label>
                        <textarea name="saran_teks" rows="3" placeholder="Tuliskan harapan atau usulan perbaikan layanan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm transition shadow-md shadow-emerald-800/20 active:scale-98 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Penilaian Survey Layanan</span>
                        </button>
                    </div>
                </form>
            </div>
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

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors | Pemkab Kutai Timur'
        }).addTo(map);

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
