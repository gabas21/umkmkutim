@extends('layouts.app')

@section('title', 'UMKM KUTIM : Ekosistem Resmi Direktori, Layanan, & Laporan UMKM Kutai Timur')

@section('content')
<!-- ========================================================================== -->
<!-- 1. SLIDER : 3 Slide Resmi & Dinamis                                       -->
<!-- ========================================================================== -->
<section x-data="{
    activeSlide: 0,
    totalSlides: 3,
    timer: null,
    startAutoPlay() {
        this.timer = setInterval(() => {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        }, 6000);
    },
    stopAutoPlay() {
        if (this.timer) clearInterval(this.timer);
    },
    nextSlide() {
        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
    },
    prevSlide() {
        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
    }
}" 
x-init="startAutoPlay()" 
@mouseenter="stopAutoPlay()" 
@mouseleave="startAutoPlay()"
class="relative min-h-[560px] md:min-h-[640px] overflow-hidden bg-[#021813] border-b border-emerald-950/80 flex items-center">

    <!-- Background Image Tetap & Vignette Gradient -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none z-0">
        <img src="{{ asset('Kantor_Bupati_Kutai_Timur.jpg') }}" 
             alt="Kawasan Pusat Pemerintahan Bukit Pelangi Sangatta - Kutai Timur" 
             class="w-full h-full object-cover object-center brightness-[0.22] contrast-[1.25] saturate-[0.8] scale-105 transform transition-transform duration-1000">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(2,44,34,0.7)_0%,rgba(2,24,19,0.94)_70%,rgba(2,20,16,0.98)_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#021813] via-transparent to-[#021813]/85"></div>
        <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.035)_1px,transparent_1px)] [background-size:32px_32px] opacity-40"></div>
    </div>

    <!-- Container Konten Slider -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 w-full">
        
        <!-- Slide 1: Ekosistem Digital UMKM Kutai Timur -->
        <div x-show="activeSlide === 0" 
             x-transition:enter="transition duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"
             x-transition:enter-start="opacity-0 translate-y-6" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-200" 
             x-transition:leave-end="opacity-0"
             class="text-center max-w-4xl mx-auto">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/90 backdrop-blur-md border border-emerald-500/30 text-xs font-semibold text-emerald-300 shadow-lg mb-6">
                <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-4 w-auto object-contain">
                <span>Pemerintah Kabupaten Kutai Timur</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-slate-300 font-normal">Dinas Koperasi & UKM</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12]">
                Ekosistem Digital <br class="hidden sm:inline">
                UMKM Kutai Timur
            </h1>

            <p class="mt-5 text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl mx-auto font-normal">
                Gerbang terpadu direktori usaha terverifikasi, promosi komoditas unggulan daerah, dan fasilitasi bagi {{ number_format($totalUmkm) }} pelaku usaha di 18 kecamatan.
            </p>

            <div class="mt-9 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="{{ route('register') }}" 
                   class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-sm font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-xl shadow-amber-950/30 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-store text-xs"></i>
                    <span>Daftar UMKM Sekarang</span>
                </a>
                <a href="#maps-sebaran" 
                   class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-md transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-400"></i>
                    <span>Lihat Peta Sebaran</span>
                </a>
            </div>
        </div>

        <!-- Slide 2: Pameran Bazar & Pelatihan Kewirausahaan -->
        <div x-show="activeSlide === 1" 
             x-cloak
             x-transition:enter="transition duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"
             x-transition:enter-start="opacity-0 translate-y-6" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-200" 
             x-transition:leave-end="opacity-0"
             class="text-center max-w-4xl mx-auto">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-950/90 backdrop-blur-md border border-amber-500/30 text-xs font-semibold text-amber-300 shadow-lg mb-6">
                <i class="fa-solid fa-tent text-amber-400"></i>
                <span>Fasilitasi Promosi & Kapasitas Usaha</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12]">
                Bazar, Pelatihan & <br class="hidden sm:inline">
                Fasilitasi Usaha Daerah
            </h1>

            <p class="mt-5 text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl mx-auto font-normal">
                Daftarkan stan bazar expo daerah secara gratis, ikuti pelatihan wirausaha terpadu, dan peroleh legalitas usaha secara transparan.
            </p>

            <div class="mt-9 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="{{ route('bazar.index') }}" 
                   class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-sm font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-xl shadow-amber-950/30 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-tent text-xs"></i>
                    <span>Daftar Stan Bazar</span>
                </a>
                <a href="{{ route('pelatihan.index') }}" 
                   class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-md transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-amber-400"></i>
                    <span>Jadwal Pelatihan</span>
                </a>
            </div>
        </div>

        <!-- Slide 3: Akses Layanan Resmi UMKM (Login) -->
        <div x-show="activeSlide === 2" 
             x-cloak
             x-transition:enter="transition duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"
             x-transition:enter-start="opacity-0 translate-y-6" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-200" 
             x-transition:leave-end="opacity-0"
             class="text-center max-w-4xl mx-auto">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/90 backdrop-blur-md border border-emerald-500/30 text-xs font-semibold text-emerald-300 shadow-lg mb-6">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                <span>Portal Resmi Pelaku Usaha Terdaftar</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12]">
                Akses Layanan Terpadu <br class="hidden sm:inline">
                Pelaku UMKM Kutim
            </h1>

            <p class="mt-5 text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl mx-auto font-normal">
                Kelola profil usaha, perbarui data katalog produk, pantau status verifikasi sertifikasi, dan nikmati pendampingan dinas.
            </p>

            <div class="mt-9 flex flex-col items-center justify-center gap-3">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 w-full sm:w-auto">
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto px-9 py-3.5 rounded-xl text-sm font-black text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-xl shadow-amber-950/30 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Login Sekarang</span>
                    </a>
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-md transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus text-emerald-400"></i>
                        <span>Registrasi Akun Baru</span>
                    </a>
                </div>
                <!-- Keterangan Login Wajib Sesuai Plan -->
                <p class="text-xs text-amber-300/90 font-medium tracking-wide flex items-center gap-1.5 mt-2">
                    <i class="fa-solid fa-circle-info text-[11px]"></i>
                    <span>Login untuk Mengakses Sebuah Layanan UMKM</span>
                </p>
            </div>
        </div>

    </div>

    <!-- Navigasi Arrow Slider -->
    <button @click="prevSlide()" 
            class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-md items-center justify-center transition duration-200 z-20 focus:outline-none"
            aria-label="Slide Sebelumnya">
        <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>
    <button @click="nextSlide()" 
            class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-md items-center justify-center transition duration-200 z-20 focus:outline-none"
            aria-label="Slide Berikutnya">
        <i class="fa-solid fa-chevron-right text-sm"></i>
    </button>

    <!-- Dot Indicators -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
        <template x-for="i in totalSlides" :key="i">
            <button @click="activeSlide = i - 1"
                    :class="activeSlide === (i - 1) ? 'w-8 bg-amber-400' : 'w-2 bg-white/40 hover:bg-white/70'"
                    class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                    :aria-label="'Pindah ke slide ' + i">
            </button>
        </template>
    </div>
</section>


<!-- ========================================================================== -->
<!-- 2. TOMBOL PENDAFTARAN & PERIZINAN : 4 Tombol Layanan Utama                 -->
<!-- ========================================================================== -->
<section class="bg-white border-b border-slate-200/80 py-8 relative z-20 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
            
            <!-- Tombol 1: Pendaftaran UMKM -->
            <a href="{{ route('register') }}" 
               class="group flex flex-col items-center text-center p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-md hover:shadow-emerald-900/5 transition-all duration-300 card-hover"
               data-reveal data-reveal-delay="1">
                <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 mb-3 shadow-2xs">
                    <i class="fa-solid fa-store"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition leading-snug">
                    Pendaftaran UMKM
                </span>
                <span class="text-xs text-slate-500 mt-1">Registrasi & klaim usaha resmi</span>
            </a>

            <!-- Tombol 2: Pendaftaran Bazar -->
            <a href="{{ route('bazar.index') }}" 
               class="group flex flex-col items-center text-center p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-amber-500 hover:shadow-md hover:shadow-amber-900/5 transition-all duration-300 card-hover"
               data-reveal data-reveal-delay="2">
                <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl group-hover:bg-amber-500 group-hover:text-slate-950 transition-all duration-300 mb-3 shadow-2xs">
                    <i class="fa-solid fa-tent"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition leading-snug">
                    Pendaftaran Bazar
                </span>
                <span class="text-xs text-slate-500 mt-1">Registrasi stan pameran expo</span>
            </a>

            <!-- Tombol 3: Pendaftaran Pelatihan -->
            <a href="{{ route('pelatihan.index') }}" 
               class="group flex flex-col items-center text-center p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-blue-500 hover:shadow-md hover:shadow-blue-900/5 transition-all duration-300 card-hover"
               data-reveal data-reveal-delay="3">
                <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 mb-3 shadow-2xs">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition leading-snug">
                    Pendaftaran Pelatihan
                </span>
                <span class="text-xs text-slate-500 mt-1">Peningkatan kapasitas usaha</span>
            </a>

            <!-- Tombol 4: Perizinan UMKM -->
            <a href="{{ route('umkm.create-mandiri') }}" 
               class="group flex flex-col items-center text-center p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-purple-500 hover:shadow-md hover:shadow-purple-900/5 transition-all duration-300 card-hover"
               data-reveal data-reveal-delay="4">
                <div class="w-13 h-13 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 mb-3 shadow-2xs">
                    <i class="fa-solid fa-file-shield"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 group-hover:text-purple-700 transition leading-snug">
                    Perizinan UMKM
                </span>
                <span class="text-xs text-slate-500 mt-1">Fasilitasi legalitas & sertifikasi</span>
            </a>

        </div>
    </div>
</section>


<!-- ========================================================================== -->
<!-- 3. CARD KPI : Total UMKM, Terverifikasi, Kecamatan, Sektor Usaha           -->
<!-- ========================================================================== -->
<section class="py-12 md:py-16 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            
            <!-- KPI 1: Total UMKM -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="1">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total UMKM</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-store"></i>
                    </div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ number_format($totalUmkm) }}</div>
                    <div class="text-xs text-emerald-700 font-semibold mt-1">Usaha aktif terdata</div>
                </div>
            </div>

            <!-- KPI 2: UMKM Terverifikasi -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="2">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">UMKM Terverifikasi</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-teal-800 tracking-tight">{{ number_format($totalTerverifikasi) }}</div>
                    <div class="text-xs text-teal-700 font-semibold mt-1">{{ $persenTerverifikasi }}% legalitas terkonfirmasi</div>
                </div>
            </div>

            <!-- KPI 3: Kecamatan Terjangkau -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="3">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kecamatan Terjangkau</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-map"></i>
                    </div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">18</div>
                    <div class="text-xs text-amber-700 font-semibold mt-1">100% wilayah Kutim tercakup</div>
                </div>
            </div>

            <!-- KPI 4: Sektor Usaha -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="4">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sektor Usaha</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $totalKategori }}</div>
                    <div class="text-xs text-purple-700 font-semibold mt-1">Klaster kategori industri</div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 4. LAPORAN UMKM ACUAN : Statistik Keuangan & Statistik Ketenagakerjaan      -->
<!-- (Acuan Portal Laporan https://ukm.kutaitimurkab.go.id/laporan)              -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mb-12" data-reveal>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Statistik Keuangan & Ketenagakerjaan
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Laporan agregat indikator dampak ekonomi dan penyerapan tenaga kerja UMKM Kabupaten Kutai Timur.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Kolom 1: Statistik Keuangan -->
            <div class="bg-gradient-to-br from-emerald-50/40 via-white to-white rounded-3xl p-6 sm:p-8 border border-emerald-100 shadow-xs card-hover" data-reveal data-reveal-delay="1">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Statistik Keuangan</h3>
                            <p class="text-xs text-slate-500">Estimasi perputaran modal & aset UMKM daerah</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                        Agregat Tahunan
                    </span>
                </div>

                <div class="divide-y divide-slate-100 mt-2">
                    <!-- Metrik 1: Total Revenue Tahunan -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-chart-pie text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">Total Revenue Tahunan</div>
                                <div class="text-xs text-slate-400">Estimasi total omset seluruh pelaku</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-slate-900 tracking-tight">Rp 284,7 M</div>
                            <span class="text-[10px] text-slate-400">(estimasi)</span>
                        </div>
                    </div>

                    <!-- Metrik 2: Rata - Rata Pendapatan/Bulan -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">Rata - Rata Pendapatan/Bulan</div>
                                <div class="text-xs text-slate-400">Rerata omset bulanan per entitas usaha</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-emerald-700 tracking-tight">Rp 22,3 Jt</div>
                            <span class="text-[10px] text-slate-400">/ UMKM (estimasi)</span>
                        </div>
                    </div>

                    <!-- Metrik 3: Rata - Rata Nilai Aset -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-building text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">Rata - Rata Nilai Aset</div>
                                <div class="text-xs text-slate-400">Investasi sarana, peralatan, & tempat</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-slate-900 tracking-tight">Rp 156,8 Jt</div>
                            <span class="text-[10px] text-slate-400">/ UMKM (estimasi)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Statistik Ketenagakerjaan -->
            <div class="bg-gradient-to-br from-amber-50/30 via-white to-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-xs card-hover" data-reveal data-reveal-delay="2">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-900 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Statistik Ketenagakerjaan</h3>
                            <p class="text-xs text-slate-500">Penyerapan sumber daya manusia daerah</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                        Tenaga Kerja Lokal
                    </span>
                </div>

                <div class="divide-y divide-slate-100 mt-2">
                    <!-- Metrik 1: Total Tenaga Kerja -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-person-digging text-amber-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">Total Tenaga Kerja</div>
                                <div class="text-xs text-slate-400">Masyarakat Kutim yang terserap</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-slate-900 tracking-tight">28.942</div>
                            <span class="text-[10px] font-semibold text-slate-500">Orang Pekerja</span>
                        </div>
                    </div>

                    <!-- Metrik 2: Rata - Rata Karyawan / UMKM -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-user-group text-amber-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">Rata - Rata Karyawan / UMKM</div>
                                <div class="text-xs text-slate-400">Rasio tim kerja per unit usaha</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-amber-600 tracking-tight">2,26</div>
                            <span class="text-[10px] font-semibold text-slate-500">Orang / Usaha</span>
                        </div>
                    </div>

                    <!-- Metrik 3: UMKM dengan Karyawan -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-briefcase text-amber-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800">UMKM dengan Karyawan</div>
                                <div class="text-xs text-slate-400">Unit usaha penyedia lowongan kerja</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-slate-900 tracking-tight">6.418</div>
                            <span class="text-[10px] font-semibold text-slate-500">Usaha (50,2%)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 5. GRAPHIC DONAT : Distribusi Skala Usaha & Status Perizinan (Chart.js)    -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mb-12" data-reveal>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Distribusi Skala Usaha & Status Perizinan
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Pemetaan proporsi tingkatan modal usaha serta progres verifikasi perizinan legalitas UMKM Kutai Timur.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Donat 1: Distribusi Skala Usaha (Mikro, Kecil, Menengah) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="1">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">Distribusi Skala Usaha</h3>
                            <p class="text-xs text-slate-500">Mikro, Kecil, dan Menengah</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ number_format($totalUmkm) }} Usaha
                        </span>
                    </div>

                    <!-- Canvas Chart.js -->
                    <div class="relative my-6 flex items-center justify-center min-h-[250px]">
                        <canvas id="donat-skala" class="max-h-[250px]"></canvas>
                    </div>
                </div>

                <!-- Legend Summary -->
                <div class="pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-xs font-bold text-emerald-700">Mikro</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">82.1%</div>
                        <div class="text-[10px] text-slate-400">10.500 Usaha</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-xs font-bold text-amber-600">Kecil</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">16.4%</div>
                        <div class="text-[10px] text-slate-400">2.100 Usaha</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-xs font-bold text-purple-600">Menengah</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">1.5%</div>
                        <div class="text-[10px] text-slate-400">183 Usaha</div>
                    </div>
                </div>
            </div>

            <!-- Donat 2: Status Perizinan (Draft, Diajukan, Terverifikasi, Ditolak) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs card-hover flex flex-col justify-between" data-reveal data-reveal-delay="2">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">Status Perizinan & Klaim</h3>
                            <p class="text-xs text-slate-500">Draft, Diajukan, Terverifikasi, dan Ditolak</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                            {{ $persenTerverifikasi }}% Terverifikasi
                        </span>
                    </div>

                    <!-- Canvas Chart.js -->
                    <div class="relative my-6 flex items-center justify-center min-h-[250px]">
                        <canvas id="donat-perizinan" class="max-h-[250px]"></canvas>
                    </div>
                </div>

                <!-- Legend Summary -->
                <div class="pt-4 border-t border-slate-100 grid grid-cols-4 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-emerald-700">Terverifikasi</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ number_format($totalTerverifikasi) }}</div>
                        <div class="text-[10px] text-slate-400">Dinas UKM</div>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-slate-600">Draft</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ number_format($statusKlaimData['draft'] ?? 20) }}</div>
                        <div class="text-[10px] text-slate-400">Data Awal</div>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-amber-600">Diajukan</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ number_format($statusKlaimData['diajukan'] ?? 10) }}</div>
                        <div class="text-[10px] text-slate-400">Proses Cek</div>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-rose-600">Ditolak</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ number_format($statusKlaimData['ditolak'] ?? 4) }}</div>
                        <div class="text-[10px] text-slate-400">Revisi Data</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 6. DISTRIBUSI PER KECAMATAN : Bar Progress Data Real 18 Wilayah            -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-white border-b border-slate-200/80" x-data="{ showAllKecamatan: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10" data-reveal>
            <div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Distribusi per Kecamatan
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                    Peringkat sebaran konsentrasi pelaku usaha di seluruh 18 wilayah kecamatan Kabupaten Kutai Timur.
                </p>
            </div>
            <button @click="showAllKecamatan = !showAllKecamatan" 
                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-2">
                <span x-text="showAllKecamatan ? 'Tampilkan 6 Teratas Saja' : 'Tampilkan Seluruh 18 Kecamatan'"></span>
                <i class="fa-solid" :class="showAllKecamatan ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
        </div>

        @php
            $maxCount = $kecamatanStats->first()?->total ?: 1;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($kecamatanStats as $index => $stat)
                @php
                    $pct = round(($stat->total / $maxCount) * 100);
                    $pctTotal = round(($stat->total / $totalUmkm) * 100, 1);
                @endphp
                <a href="{{ route('umkm.index', ['kecamatan' => $stat->kecamatan]) }}"
                   x-show="{{ $index }} < 6 || showAllKecamatan" 
                   x-transition 
                   class="p-4 sm:p-5 rounded-2xl bg-slate-50 hover:bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-sm transition-all duration-300 block card-hover" 
                   data-reveal data-reveal-delay="{{ min(($index % 6) + 1, 6) }}">
                    <div class="flex items-center justify-between text-xs mb-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg {{ $index < 3 ? 'bg-amber-400 text-slate-950 font-black' : 'bg-emerald-100 text-emerald-800 font-bold' }} flex items-center justify-center text-[11px]">
                                {{ $index + 1 }}
                            </span>
                            <span class="font-bold text-slate-900 text-sm">Kec. {{ $stat->kecamatan }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-emerald-700 text-sm">{{ number_format($stat->total) }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $pctTotal }}% total</span>
                        </div>
                    </div>
                    <div class="w-full h-2.5 bg-slate-200/80 rounded-full overflow-hidden mt-3">
                        <div class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"
                             style="width: {{ max($pct, 6) }}%"></div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 7. DISTRIBUSI PER SEKTOR USAHA : Grid 8 Klaster Industri Komoditas         -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mb-12" data-reveal>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Distribusi per Sektor Usaha
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Keberagaman 8 klaster komoditas dan sektor bisnis lokal yang menopang perekonomian Kutai Timur.
            </p>
        </div>

        @php
            $ikonKategori = [
                'Kuliner & Makanan Khas'        => 'fa-utensils',
                'Perdagangan & Kelontong'       => 'fa-cart-shopping',
                'Jasa & Percetakan'             => 'fa-print',
                'Agribisnis & Hasil Bumi'       => 'fa-seedling',
                'Batik & Fashion Khas Kutim'    => 'fa-shirt',
                'Kriya & Kerajinan Tradisional' => 'fa-hammer',
                'Kelautan & Perikanan'          => 'fa-fish',
                'Kesehatan & Herbal Dayak'      => 'fa-leaf',
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
            @foreach($kategoriList->sortByDesc('umkm_count') as $index => $kat)
                @php
                    $totalSektor = $kat->umkm_count;
                    $pctSektor = $totalUmkm > 0 ? round(($totalSektor / $totalUmkm) * 100, 1) : 0;
                    $ikon = $ikonKategori[$kat->nama] ?? 'fa-store';
                @endphp
                <a href="{{ route('umkm.index', ['kategori' => $kat->id]) }}" 
                   class="group bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all duration-300 flex flex-col justify-between block card-hover" 
                   data-reveal data-reveal-delay="{{ min(($index % 4) + 1, 4) }}">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 group-hover:bg-emerald-600 text-emerald-700 group-hover:text-white flex items-center justify-center text-lg mb-3 shadow-2xs transition duration-300">
                            <i class="fa-solid {{ $ikon }}"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            {{ number_format($totalSektor) }}
                        </div>
                        <div class="text-xs font-bold text-slate-800 mt-1 leading-snug group-hover:text-emerald-700 transition">
                            {{ $kat->nama }}
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" 
                                 style="width: {{ min(max($pctSektor * 2, 8), 100) }}%"></div>
                        </div>
                        <div class="text-[11px] font-semibold text-slate-400 mt-1.5 flex justify-between items-center">
                            <span>Porsi Usaha</span>
                            <span class="text-emerald-700 font-bold">{{ $pctSektor }}%</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 8. DAFTAR UMKM : 8 Profil Pilihan Kurasi Daerah                            -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mb-12" data-reveal>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Daftar UMKM
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Profil pelaku usaha lokal pilihan dengan reputasi terpercaya dan produk berdaya saing di Kutai Timur.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($featuredUmkm as $index => $umkm)
                <a href="{{ route('umkm.show', $umkm->slug) }}" 
                   class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden card-hover flex flex-col justify-between block shadow-xs" 
                   data-reveal data-reveal-delay="{{ min(($index % 4) + 1, 4) }}">
                    
                    <!-- Header Kartu -->
                    <div class="h-28 bg-gradient-to-br from-[#021813] via-emerald-950 to-[#032b21] p-4 relative flex flex-col justify-between overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-emerald-500/15 rounded-full blur-lg group-hover:scale-125 transition duration-500"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/15 backdrop-blur-md text-emerald-200 border border-white/20 uppercase tracking-wider">
                                {{ $umkm->kategori?->nama ?? 'Usaha' }}
                            </span>
                            @if($umkm->status_klaim === 'terverifikasi')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-xs flex items-center gap-1">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terverifikasi
                                </span>
                            @endif
                        </div>

                        <div class="relative z-10 text-[11px] text-emerald-200/90 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-location-dot text-amber-400 text-[10px]"></i>
                            <span>Kec. {{ $umkm->kecamatan }}</span>
                        </div>
                    </div>

                    <!-- Body Kartu -->
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 group-hover:text-emerald-700 transition">
                                {{ $umkm->nama_usaha }}
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $umkm->deskripsi ?: 'Usaha lokal berdedikasi menyediakan produk dan komoditas berkualitas tinggi di wilayah Kutai Timur.' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1 text-amber-500 font-bold">
                                <i class="fa-solid fa-star text-[11px]"></i>
                                <span>{{ number_format($umkm->rating, 1) }}</span>
                                <span class="text-slate-400 font-normal text-[11px]">({{ $umkm->jumlah_review }})</span>
                            </div>
                            <span class="text-emerald-700 font-bold text-[11px] group-hover:translate-x-1 transition duration-200 flex items-center gap-1">
                                <span>Lihat Profil</span>
                                <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 9. MAPS SEBARAN UMKM DI KUTIM : Leaflet Interactive Geospasial            -->
<!-- ========================================================================== -->
<section id="maps-sebaran" class="py-16 md:py-20 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8" data-reveal>
            <div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Maps Sebaran UMKM di Kutim
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                    Eksplorasi persebaran titik koordinat usaha secara spasial interaktif di seluruh wilayah Kutai Timur.
                </p>
            </div>
            <a href="{{ route('peta.index') }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-bold text-emerald-800 bg-white hover:bg-emerald-50 border border-emerald-200 transition flex items-center gap-2 shadow-2xs">
                <i class="fa-solid fa-expand text-xs"></i>
                <span>Buka Mode Peta Penuh</span>
            </a>
        </div>

        <!-- Frame Map GIS Console -->
        <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-slate-200/90 relative" data-reveal>
            
            <!-- Header Bar GIS Map -->
            <div class="px-5 py-3.5 bg-slate-950 text-white flex flex-wrap justify-between items-center text-xs gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold tracking-wide">GIS Live Map: 18 Kecamatan Kabupaten Kutai Timur</span>
                </div>
                <div class="flex items-center gap-4 text-slate-300 text-[11px]">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Terverifikasi Diskop
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Data Lapangan
                    </span>
                </div>
            </div>

            <!-- Leaflet Map Canvas (Tinggi 540px) -->
            <div id="leaflet-preview-map" class="h-[480px] md:h-[540px] w-full z-10"></div>

            <!-- Footer Bar Status Titik -->
            <div class="px-5 py-3 bg-slate-100 border-t border-slate-200 text-xs text-slate-600 flex flex-wrap justify-between items-center gap-2">
                <span>Klik cluster nomor untuk zoom instan, atau klik marker toko untuk membuka profil usaha.</span>
                <span class="font-semibold text-emerald-800">{{ count($mapPoints) }} Titik Sampel Aktif Dimuat</span>
            </div>
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 10. TOMBOL UNTUK MENGISI SURVEY KEPUASAN MASYARAKAT                       -->
<!-- ========================================================================== -->
<section id="survey-layanan" class="py-16 md:py-24 bg-gradient-to-br from-[#021813] via-[#02241d] to-[#032b21] relative overflow-hidden">
    <!-- Ambient Blur Orbs -->
    <div class="absolute -top-32 -right-32 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-reveal>
        
        <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-4">
            Survey Kepuasan Masyarakat
        </h2>

        <p class="text-emerald-100/80 text-sm sm:text-base max-w-2xl mx-auto mb-10 leading-relaxed font-normal">
            Partisipasi Anda sangat berharga dalam mengevaluasi kemudahan akses direktori, fasilitas bazar, dan pendampingan UMKM Kabupaten Kutai Timur.
        </p>

        <!-- Tombol CTA Utama Isi Survey (Sesuai Plan) -->
        <a href="{{ route('survey.create') }}" 
           class="inline-flex items-center gap-3 px-9 py-4.5 rounded-2xl text-slate-950 font-black text-sm sm:text-base bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-2xl shadow-amber-950/40 transition-all duration-300 active:scale-95">
            <i class="fa-solid fa-paper-plane text-xs"></i>
            <span>Isi Survey Kepuasan Masyarakat</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>

    </div>
</section>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // ----------------------------------------------------------------------
        // 1. Chart Donat: Distribusi Skala Usaha
        // ----------------------------------------------------------------------
        const ctxSkala = document.getElementById('donat-skala');
        if (ctxSkala) {
            new Chart(ctxSkala, {
                type: 'doughnut',
                data: {
                    labels: ['Mikro (82.1%)', 'Kecil (16.4%)', 'Menengah (1.5%)'],
                    datasets: [{
                        data: [10500, 2100, 183],
                        backgroundColor: ['#059669', '#d97706', '#7c3aed'],
                        hoverBackgroundColor: ['#047857', '#b45309', '#6d28d9'],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11,
                                    weight: '600'
                                },
                                padding: 14,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.parsed.toLocaleString('id-ID')} Usaha`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ----------------------------------------------------------------------
        // 2. Chart Donat: Status Perizinan
        // ----------------------------------------------------------------------
        const ctxPerizinan = document.getElementById('donat-perizinan');
        if (ctxPerizinan) {
            const statusRawData = @json($statusKlaimData);
            
            const statusLabels = [];
            const statusValues = [];
            const colorPalette = {
                'terverifikasi': '#059669',
                'draft': '#94a3b8',
                'diajukan': '#d97706',
                'ditolak': '#ef4444'
            };
            const defaultColors = ['#059669', '#94a3b8', '#d97706', '#ef4444'];
            const appliedColors = [];

            let colorIdx = 0;
            for (const [key, val] of Object.entries(statusRawData)) {
                const cleanKey = key.toLowerCase().trim();
                const formattedName = cleanKey.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                statusLabels.push(formattedName);
                statusValues.push(Number(val));
                appliedColors.push(colorPalette[cleanKey] || defaultColors[colorIdx % defaultColors.length]);
                colorIdx++;
            }

            if (statusValues.length === 0) {
                statusLabels.push('Terverifikasi', 'Draft', 'Diajukan', 'Ditolak');
                statusValues.push(12749, 20, 10, 4);
                appliedColors.push('#059669', '#94a3b8', '#d97706', '#ef4444');
            }

            new Chart(ctxPerizinan, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: appliedColors,
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11,
                                    weight: '600'
                                },
                                padding: 14,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.parsed.toLocaleString('id-ID')} Usaha`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ----------------------------------------------------------------------
        // 3. Leaflet Interactive GIS Map dengan MarkerCluster & Spiderfication
        // ----------------------------------------------------------------------
        const mapContainer = document.getElementById('leaflet-preview-map');
        if (mapContainer && typeof L !== 'undefined') {
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
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 6px;">
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
        }

    });
</script>
@endpush
