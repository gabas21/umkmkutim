@extends('layouts.app')

@section('title', 'UMKM KUTIM : Ekosistem Resmi Direktori, Layanan, & Laporan UMKM Kutai Timur')

@section('content')
<!-- ========================================================================== -->
<!-- 1. HERO SLIDER BANNER RESMI PEMKAB KUTAI TIMUR                            -->
<!-- ========================================================================== -->
<section class="relative overflow-hidden bg-white border-b border-slate-200/80 pt-6 pb-8 sm:pt-7 sm:pb-10 lg:pt-8 lg:pb-12">


    <!-- Container Utama -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <!-- Header Identitas Portal Terpusat (Ringkas & Mewah) -->
        <div class="text-center max-w-3xl mx-auto mb-4 sm:mb-5">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/90 backdrop-blur-md border border-emerald-500/30 text-xs font-semibold text-emerald-300 shadow-md mb-2 sm:mb-2.5">
                <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-4 w-auto object-contain">
                <span>Pemerintah Kabupaten Kutai Timur</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-slate-300 font-normal">Dinas Koperasi & UKM</span>
            </div>

            <h1 class="text-[26px] sm:text-[38px] lg:text-[50px] font-black tracking-tight text-slate-900 leading-tight">
                <span class="block">UMKM PRO KUTIM</span>
            </h1>
        </div>

        <!-- ====================================================================== -->
        <!-- 1. SLIDER BANNER RESMI PEMKAB KUTIM (FULL-WIDTH 12 KOLOM)             -->
        <!-- ====================================================================== -->
        @php
            $slidesToDisplay = (isset($heroSlides) && $heroSlides->isNotEmpty()) ? $heroSlides->map(function($slide) {
                return [
                    'judul' => $slide->judul,
                    'gambar' => str_starts_with($slide->gambar, 'http') ? $slide->gambar : asset('storage/' . $slide->gambar),
                    'link' => $slide->link_url ?: '#',
                    'fallback' => null,
                ];
            }) : collect([
                [
                    'judul' => 'Selamat Hari Kebangkitan Nasional',
                    'gambar' => 'https://bappelitbangdamahulu.com/storage/sliders/01KS20RNYQGBKTGYGC00VZ1F4R.png',
                    'link' => '#',
                    'fallback' => asset('sample_slider_1.png'),
                ],
                [
                    'judul' => 'Kutim Expo & Gelar Dagang UMKM 2026',
                    'gambar' => asset('slider_bazar_kutim.png'),
                    'link' => '#',
                    'fallback' => null,
                ],
                [
                    'judul' => 'Pelatihan Bisnis Digital & Sertifikasi Halal Gratis 2026',
                    'gambar' => asset('slider_pelatihan_kutim.png'),
                    'link' => '#',
                    'fallback' => null,
                ],
            ]);
        @endphp

        <div class="w-full"
             x-data="{
                 currentSlide: 0,
                 slides: {{ Js::from($slidesToDisplay) }},
                 isModalOpen: false,
                 modalImageSrc: '',
                 autoplayTimer: null,
                 startAutoplay() {
                     this.stopAutoplay();
                     this.autoplayTimer = setInterval(() => {
                         this.nextSlide();
                     }, 5000);
                 },
                 stopAutoplay() {
                     if (this.autoplayTimer) clearInterval(this.autoplayTimer);
                 },
                 nextSlide() {
                     this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                 },
                 prevSlide() {
                     this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                 },
                 goToSlide(idx) {
                     this.currentSlide = idx;
                 },
                 openModal(src) {
                     this.modalImageSrc = src;
                     this.isModalOpen = true;
                 },
                 openCurrentModal() {
                     if (this.slides && this.slides[this.currentSlide]) {
                         this.openModal(this.slides[this.currentSlide].gambar);
                     }
                 }
             }"
             x-init="startAutoplay()"
             @mouseenter="stopAutoplay()"
             @mouseleave="startAutoplay()"
             @keydown.escape.window="isModalOpen = false">

            <!-- Card Slider Frame Selebar Penuh (Megah Seperti Slider Portal Pada Umumnya) -->
            <div class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden glass-action-card p-1 sm:p-1.5 shadow-2xl shadow-emerald-950/70 group border border-white/20">
                
                <!-- Inner Aspect Ratio Container (1350/572 Presisi Banner Resmi) -->
                <div class="relative w-full aspect-[1350/572] rounded-xl sm:rounded-[22px] overflow-hidden bg-slate-950/40">
                    
                    <!-- Shimmer / Loading Placeholder Background -->
                    <div class="absolute inset-0 bg-slate-900/20 z-0"></div>

                    @foreach($slidesToDisplay as $index => $slide)
                    <!-- Slide {{ $index }}: {{ $slide['judul'] }} -->
                    <div class="absolute inset-0 transform-gpu z-10"
                         x-show="currentSlide === {{ $index }}"
                         x-transition:enter="transition-transform duration-[650ms] ease-[cubic-bezier(0.25,1,0.5,1)] z-10"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition-transform duration-[650ms] ease-[cubic-bezier(0.25,1,0.5,1)] z-0"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         style="display: none;">
                        <a href="{{ $slide['link'] }}" 
                           @if($slide['link'] === '#') @click.prevent="openModal('{{ $slide['gambar'] }}')" class="relative flex items-center justify-center w-full h-full cursor-zoom-in group/slide bg-white"
                           @else class="relative flex items-center justify-center w-full h-full group/slide bg-white" target="_blank" @endif>
                            <img src="{{ $slide['gambar'] }}" 
                                 alt="{{ $slide['judul'] }}" 
                                 class="w-full h-full object-contain sm:object-cover object-center transition-transform duration-700 ease-out group-hover/slide:scale-[1.01]" 
                                 loading="lazy" 
                                 @if(!empty($slide['fallback'])) onerror="this.onerror=null; this.src='{{ $slide['fallback'] }}'" @endif>
                        </a>
                    </div>
                    @endforeach

                    <!-- Tombol Navigasi Kiri (Prev) -->
                    <button @click="prevSlide()" 
                            class="absolute left-2.5 sm:left-5 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-slate-950/50 hover:bg-slate-950/80 text-white/80 hover:text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all duration-200 active:scale-90 shadow-xl cursor-pointer hover:scale-105"
                            title="Slide Sebelumnya"
                            aria-label="Slide Sebelumnya">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Tombol Navigasi Kanan (Next) -->
                    <button @click="nextSlide()" 
                            class="absolute right-2.5 sm:right-5 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-slate-950/50 hover:bg-slate-950/80 text-white/80 hover:text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all duration-200 active:scale-90 shadow-xl cursor-pointer hover:scale-105"
                            title="Slide Selanjutnya"
                            aria-label="Slide Selanjutnya">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Tombol Perbesar / Lightbox di Pojok Kanan Atas -->
                    <div class="absolute top-2.5 sm:top-4 right-2.5 sm:right-4 z-20 flex items-center gap-2">
                        <button @click="openCurrentModal()" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-950/60 hover:bg-slate-950/90 text-white/90 hover:text-white backdrop-blur-md border border-white/25 text-xs font-semibold shadow-lg transition-all active:scale-95 cursor-pointer"
                                title="Perbesar Tampilan Slide">
                            <i class="fa-solid fa-expand text-[11px]"></i>
                            <span class="hidden sm:inline">Perbesar</span>
                        </button>
                    </div>

                    <!-- Dot Indicators & Counter di Bawah Tengah -->
                    <div class="absolute bottom-2.5 sm:bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 sm:gap-2.5 bg-slate-950/60 hover:bg-slate-950/80 backdrop-blur-md px-3.5 sm:px-4 py-1.5 rounded-full border border-white/20 shadow-lg transition-all">
                        @for($i = 0; $i < $slidesToDisplay->count(); $i++)
                        <button @click="goToSlide({{ $i }})" 
                                class="h-1.5 sm:h-2 rounded-full transition-all duration-300 focus:outline-none cursor-pointer" 
                                :class="currentSlide === {{ $i }} ? 'bg-emerald-400 w-6 sm:w-8 shadow-xs' : 'bg-white/40 w-1.5 sm:w-2 hover:bg-white/75'"
                                aria-label="Menuju slide {{ $i + 1 }}"></button>
                        @endfor
                        <span class="text-[10px] sm:text-xs font-semibold text-white/80 border-l border-white/20 pl-2 ml-1">
                            <span x-text="currentSlide + 1"></span>/<span>{{ $slidesToDisplay->count() }}</span>
                        </span>
                    </div>

                </div>

            </div>

            <!-- Fullscreen Lightbox Modal (Teleported to Body - Bebas dari Pembatas Filter/Transform) -->
            <template x-teleport="body">
                <div x-show="isModalOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/95 backdrop-blur-md p-4 sm:p-8"
                     x-cloak
                     style="display: none;">
                    
                    <!-- Close Button -->
                    <button @click="isModalOpen = false" 
                            class="fixed top-5 right-5 sm:top-6 sm:right-6 text-white/80 hover:text-white bg-white/10 hover:bg-white/25 rounded-full p-3 transition-all duration-300 z-[100000] cursor-pointer focus:outline-none shadow-2xl border border-white/20"
                            aria-label="Tutup Preview">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Full Image -->
                    <div class="relative max-w-6xl w-full max-h-[92vh] flex items-center justify-center p-2" @click.away="isModalOpen = false">
                        <img :src="modalImageSrc" 
                             class="max-w-full max-h-[88vh] w-auto h-auto object-contain rounded-2xl shadow-2xl ring-1 ring-white/20 select-none" 
                             alt="Pengumuman Full"
                             x-show="isModalOpen"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100" />
                    </div>
                </div>
            </template>

        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 2. LAYANAN MANDIRI & STATISTIK CAPAIAN DAERAH (SINGLE UNIFIED SECTION)     -->
<!-- ========================================================================== -->
<section id="section-kpi-stats" class="relative bg-white border-b border-slate-200/80 py-10 sm:py-14 select-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 sm:space-y-12">
        
        <!-- Bagian 1: Akses Cepat Layanan Mandiri -->
        <div>
            <!-- Header Section Layanan Cepat -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 sm:mb-8" data-reveal>
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1 rounded-full mb-2 shadow-xs">
                        @include('icon-fallback', ['name' => 'bolt-lightning', 'class' => 'text-amber-500 text-[11px]'])
                        <span>Akses Cepat Layanan Mandiri</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                        Layanan Terpadu Pelaku Usaha
                    </h2>
                </div>
                <div class="max-w-lg p-3 sm:px-4 sm:py-2.5 rounded-2xl bg-slate-50/90 border border-slate-200/90 flex items-center gap-3 shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100/80 text-emerald-700 border border-emerald-300/50 flex items-center justify-center shrink-0 text-xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        Akses langsung pendaftaran izin usaha resmi daerah, fasilitasi stan expo, pelatihan gratis bersertifikat, dan evaluasi kepuasan layanan publik.
                    </p>
                </div>
            </div>

            <!-- 4 Tombol Layanan Utama (White Card with Hover Accents) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-4.5 items-stretch" data-reveal>
                
                <!-- Tombol 1: Pendaftaran UMKM & NIB -->
                <a href="{{ route('register') }}" 
                   class="group bg-slate-50/70 hover:bg-white rounded-2xl p-3.5 sm:p-4 xl:p-4.5 border border-slate-200/90 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-950/5 transition-all duration-300 flex items-center justify-between gap-2.5 cursor-pointer hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center text-base shrink-0 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-xs">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-[9.5px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md uppercase tracking-wider border border-emerald-300/60 shadow-xs">OSS & NIB</span>
                            </div>
                            <h2 class="text-sm sm:text-[14px] xl:text-[15px] font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug">
                                Pendaftaran UMKM
                            </h2>
                            <p class="text-[11px] sm:text-xs text-slate-500 group-hover:text-slate-600 transition font-medium mt-0.5 leading-snug line-clamp-2">
                                Registrasi izin usaha resmi daerah
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white group-hover:bg-emerald-600 text-slate-400 group-hover:text-white flex items-center justify-center transition-all duration-300 shrink-0 border border-slate-200 group-hover:border-emerald-600 shadow-xs">
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition duration-200"></i>
                    </div>
                </a>

                <!-- Tombol 2: Pendaftaran Stan Bazar -->
                <a href="{{ route('bazar.index') }}" 
                   class="group bg-slate-50/70 hover:bg-white rounded-2xl p-3.5 sm:p-4 xl:p-4.5 border border-slate-200/90 hover:border-amber-500/50 hover:shadow-lg hover:shadow-amber-950/5 transition-all duration-300 flex items-center justify-between gap-2.5 cursor-pointer hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center text-base shrink-0 group-hover:bg-amber-500 group-hover:text-white group-hover:border-amber-500 group-hover:scale-105 transition-all duration-300 shadow-xs">
                            <i class="fa-solid fa-tent"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-[9.5px] font-bold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-md uppercase tracking-wider border border-amber-300/60 shadow-xs">Expo 2026</span>
                            </div>
                            <h2 class="text-sm sm:text-[14px] xl:text-[15px] font-black text-slate-900 group-hover:text-amber-700 transition leading-snug">
                                Pendaftaran Bazar
                            </h2>
                            <p class="text-[11px] sm:text-xs text-slate-500 group-hover:text-slate-600 transition font-medium mt-0.5 leading-snug line-clamp-2">
                                Fasilitasi stan pameran daerah
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white group-hover:bg-amber-500 text-slate-400 group-hover:text-white flex items-center justify-center transition-all duration-300 shrink-0 border border-slate-200 group-hover:border-amber-500 shadow-xs">
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition duration-200"></i>
                    </div>
                </a>

                <!-- Tombol 3: Pelatihan Usaha -->
                <a href="{{ route('pelatihan.index') }}" 
                   class="group bg-slate-50/70 hover:bg-white rounded-2xl p-3.5 sm:p-4 xl:p-4.5 border border-slate-200/90 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-950/5 transition-all duration-300 flex items-center justify-between gap-2.5 cursor-pointer hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-teal-50 text-teal-600 border border-teal-200/80 flex items-center justify-center text-base shrink-0 group-hover:bg-teal-600 group-hover:text-white group-hover:border-teal-600 group-hover:scale-105 transition-all duration-300 shadow-xs">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-[9.5px] font-bold text-teal-700 bg-teal-100/80 px-2 py-0.5 rounded-md uppercase tracking-wider border border-teal-300/60 shadow-xs">Bimtek Gratis</span>
                            </div>
                            <h2 class="text-sm sm:text-[14px] xl:text-[15px] font-black text-slate-900 group-hover:text-teal-700 transition leading-snug">
                                Pelatihan Usaha
                            </h2>
                            <p class="text-[11px] sm:text-xs text-slate-500 group-hover:text-slate-600 transition font-medium mt-0.5 leading-snug line-clamp-2">
                                Bimtek & sertifikasi halal
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white group-hover:bg-teal-600 text-slate-400 group-hover:text-white flex items-center justify-center transition-all duration-300 shrink-0 border border-slate-200 group-hover:border-teal-600 shadow-xs">
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition duration-200"></i>
                    </div>
                </a>

                <!-- Tombol 4: Survey Kepuasan Masyarakat -->
                <a href="{{ route('survey.create') }}" 
                   class="group bg-slate-50/70 hover:bg-white rounded-2xl p-3.5 sm:p-4 xl:p-4.5 border border-slate-200/90 hover:border-purple-500/50 hover:shadow-lg hover:shadow-purple-950/5 transition-all duration-300 flex items-center justify-between gap-2.5 cursor-pointer hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/80 flex items-center justify-center text-base shrink-0 group-hover:bg-purple-600 group-hover:text-white group-hover:border-purple-600 group-hover:scale-105 transition-all duration-300 shadow-xs">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-[9.5px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md uppercase tracking-wider border border-purple-300/60 shadow-xs">Indeks IKM</span>
                            </div>
                            <h2 class="text-sm sm:text-[14px] xl:text-[15px] font-black text-slate-900 group-hover:text-purple-700 transition leading-snug">
                                Survey Kepuasan
                            </h2>
                            <p class="text-[11px] sm:text-xs text-slate-500 group-hover:text-slate-600 transition font-medium mt-0.5 leading-snug line-clamp-2">
                                Evaluasi mutu pelayanan dinas
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white group-hover:bg-purple-600 text-slate-400 group-hover:text-white flex items-center justify-center transition-all duration-300 shrink-0 border border-slate-200 group-hover:border-purple-600 shadow-xs">
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition duration-200"></i>
                    </div>
                </a>

            </div>
        </div>

        <!-- Bagian 2: Statistik & Perkembangan UMKM Kutai Timur -->
        <div>
            <!-- Section Header KPI -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 sm:mb-7 gap-4" data-reveal>
                <div>
                    <div class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#0F7A5C] bg-[#E4F3EC] px-2.5 py-0.5 rounded-full mb-2">
                        <i class="fa-solid fa-chart-line text-[11px]"></i> Indikator Capaian Daerah
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                        Statistik & Perkembangan UMKM Kutai Timur
                    </h2>
                </div>
                <div class="max-w-lg p-3 sm:px-4 sm:py-2.5 rounded-2xl bg-slate-50/90 border border-slate-200/90 flex items-center gap-3 shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100/80 text-emerald-700 border border-emerald-300/50 flex items-center justify-center shrink-0 text-xs">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        Data terintegrasi satu pintu dari 18 kecamatan binaan Dinas Koperasi dan UKM Kabupaten Kutai Timur.
                    </p>
                </div>
            </div>

            <!-- Grid 4 Kolom Stat Card (Matching Service Card Style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-4.5 items-stretch" data-reveal>
                
                <!-- Card 1: Total UMKM -->
                <div class="group bg-slate-50/70 hover:bg-white rounded-2xl p-4.5 sm:p-5 border border-slate-200/90 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-950/5 transition-all duration-300 flex flex-col justify-between hover:-translate-y-0.5 cursor-default">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-[13px] font-bold text-slate-500 group-hover:text-slate-800 transition">Total UMKM</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center text-sm group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-2xs">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl lg:text-[32px] font-black text-emerald-800 tracking-tight leading-none tabular-nums stat-number" data-target="{{ $totalUmkm }}">
                            {{ number_format($totalUmkm) }}
                        </div>
                        <div class="text-xs text-slate-500 mt-2.5 flex items-center gap-1.5 leading-none">
                            <span class="inline-flex items-center text-[10.5px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md border border-emerald-300/60 shadow-2xs">
                                @include('icon-fallback', ['name' => 'arrow-trend-up', 'class' => 'text-[9px] mr-1']) 12.4%
                            </span>
                            <span class="font-medium text-[11px] text-slate-500">pertumbuhan tahunan</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: UMKM Terverifikasi -->
                <div class="group bg-slate-50/70 hover:bg-white rounded-2xl p-4.5 sm:p-5 border border-slate-200/90 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-950/5 transition-all duration-300 flex flex-col justify-between hover:-translate-y-0.5 cursor-default">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-[13px] font-bold text-slate-500 group-hover:text-slate-800 transition">Terverifikasi Resmi</span>
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 border border-teal-200/80 flex items-center justify-center text-sm group-hover:bg-teal-600 group-hover:text-white group-hover:border-teal-600 group-hover:scale-105 transition-all duration-300 shadow-2xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl lg:text-[32px] font-black text-slate-900 group-hover:text-teal-700 transition tracking-tight leading-none tabular-nums stat-number" data-target="{{ $totalTerverifikasi }}">
                            {{ number_format($totalTerverifikasi) }}
                        </div>
                        <div class="text-xs text-slate-500 mt-2.5 flex items-center gap-1.5 leading-none">
                            <span class="inline-flex items-center text-[10.5px] font-bold text-teal-700 bg-teal-100/80 px-2 py-0.5 rounded-md border border-teal-300/60 shadow-2xs">
                                <i class="fa-solid fa-circle-check text-[9px] mr-1"></i> {{ $persenTerverifikasi }}%
                            </span>
                            <span class="font-medium text-[11px] text-slate-500">legalitas terkonfirmasi</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Kecamatan Terjangkau -->
                <div class="group bg-slate-50/70 hover:bg-white rounded-2xl p-4.5 sm:p-5 border border-slate-200/90 hover:border-amber-500/50 hover:shadow-lg hover:shadow-amber-950/5 transition-all duration-300 flex flex-col justify-between hover:-translate-y-0.5 cursor-default">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-[13px] font-bold text-slate-500 group-hover:text-slate-800 transition">Kecamatan</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center text-sm group-hover:bg-amber-500 group-hover:text-white group-hover:border-amber-500 group-hover:scale-105 transition-all duration-300 shadow-2xs">
                            @include('icon-fallback', ['name' => 'map', 'class' => ''])
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl lg:text-[32px] font-black text-slate-900 group-hover:text-amber-700 transition tracking-tight leading-none tabular-nums stat-number" data-target="18">
                            18
                        </div>
                        <div class="text-xs text-slate-500 mt-2.5 flex items-center gap-1.5 leading-none">
                            <span class="inline-flex items-center text-[10.5px] font-bold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-md border border-amber-300/60 shadow-2xs">
                                100%
                            </span>
                            <span class="font-medium text-[11px] text-slate-500">wilayah Kutim tercakup</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Sektor Usaha -->
                <div class="group bg-slate-50/70 hover:bg-white rounded-2xl p-4.5 sm:p-5 border border-slate-200/90 hover:border-purple-500/50 hover:shadow-lg hover:shadow-purple-950/5 transition-all duration-300 flex flex-col justify-between hover:-translate-y-0.5 cursor-default">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-[13px] font-bold text-slate-500 group-hover:text-slate-800 transition">Sektor Usaha</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/80 flex items-center justify-center text-sm group-hover:bg-purple-600 group-hover:text-white group-hover:border-purple-600 group-hover:scale-105 transition-all duration-300 shadow-2xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl lg:text-[32px] font-black text-slate-900 group-hover:text-purple-700 transition tracking-tight leading-none tabular-nums stat-number" data-target="{{ $totalKategori }}">
                            {{ $totalKategori }}
                        </div>
                        <div class="text-xs text-slate-500 mt-2.5 flex items-center gap-1.5 leading-none">
                            <span class="inline-flex items-center text-[10.5px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md border border-purple-300/60 shadow-2xs">
                                {{ $totalKategori }} Klaster
                            </span>
                            <span class="font-medium text-[11px] text-slate-500">binaan dinas daerah</span>
                        </div>
                    </div>
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
                                @include('icon-fallback', ['name' => 'arrow-trend-up', 'class' => 'text-emerald-600'])
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
                                @include('icon-fallback', ['name' => 'person-digging', 'class' => 'text-amber-600'])
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
<!-- ========================================================================== -->
<!-- ========================================================================== -->
<!-- 6. DISTRIBUSI UMKM KUTAI TIMUR (Merged Kecamatan & Sektor Usaha via Tabs) -->
<!-- ========================================================================== -->
@php
    $maxCount = $kecamatanStats->first()?->total ?: 1;
    $halfCount = (int) ceil($kecamatanStats->count() / 2);
    $col1 = $kecamatanStats->take($halfCount)->values();
    $col2 = $kecamatanStats->slice($halfCount)->values();
    
    // Palet Warna Spektrum Sesuai Referensi Grafik Distribusi Kecamatan
    $kecPalette = [
        '#ef4444', // 1: Merah Terang (Sangatta Utara)
        '#f97316', // 2: Oranye (Sangatta Selatan)
        '#f59e0b', // 3: Amber / Emas (Kaubun)
        '#eab308', // 4: Kuning Emas (Muara Ancalong)
        '#84cc16', // 5: Hijau Muda / Lime (Bengalon)
        '#10b981', // 6: Hijau Emerald (Muara Bengkal)
        '#14b8a6', // 7: Teal (Sangkulirang)
        '#06b6d4', // 8: Cyan (Teluk Pandan)
        '#0ea5e9', // 9: Biru Langit (Kaliorang)
        '#3b82f6', // 10: Biru (Rantau Pulung)
        '#6366f1', // 11: Indigo (Long Mesangat)
        '#8b5cf6', // 12: Ungu (Batu Ampar)
        '#a855f7', // 13: Violet (Karangan)
        '#d946ef', // 14: Fuchsia (Telen)
        '#ec4899', // 15: Merah Muda (Muara Wahau)
        '#f43f5e', // 16: Rose (Busang)
        '#0284c7', // 17: Deep Sky (Kongbeng)
        '#64748b', // 18: Slate (Sandaran)
    ];
@endphp

<section class="py-14 md:py-18 bg-slate-50/70 border-b border-slate-200" x-data="{ activeTab: 'kecamatan' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Tab Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8" data-reveal>
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold mb-2.5">
                    <i class="fa-solid fa-chart-bar text-emerald-600"></i>
                    <span>Statistik Sebaran</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Distribusi Usaha Daerah
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl">
                    Sebaran konsentrasi pelaku UMKM di 18 kecamatan Kabupaten Kutai Timur.
                </p>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center p-1 bg-white rounded-xl border border-slate-200 shadow-xs self-stretch sm:self-auto">
                <button @click="activeTab = 'kecamatan'" 
                        :class="activeTab === 'kecamatan' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 sm:flex-initial px-4 py-2 rounded-lg text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                    @include('icon-fallback', ['name' => 'map-location-dot', 'class' => ''])
                    <span>18 Kecamatan</span>
                </button>
                <button @click="activeTab = 'sektor'" 
                        :class="activeTab === 'sektor' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 sm:flex-initial px-4 py-2 rounded-lg text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Sektor Usaha</span>
                </button>
            </div>
        </div>

        <!-- TAB 1: GRAFIK BATANG 18 KECAMATAN (Palet Warna Spektrum Sesuai Grafik) -->
        <div x-show="activeTab === 'kecamatan'" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">

            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-xs">
                
                <!-- Subheader Info Ringkas -->
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 text-xs text-slate-500">
                    <span class="font-medium">Peringkat jumlah pelaku usaha terdaftar per kecamatan:</span>
                    <span class="font-bold text-slate-800">Total: {{ number_format($totalUmkm) }} UMKM</span>
                </div>

                <!-- 2 Kolom Grafik Batang (9 kiri, 9 kanan) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-3">
                    
                    <!-- Kolom 1 (Peringkat 1 s/d 9) -->
                    <div class="space-y-2.5">
                        @foreach($col1 as $index => $stat)
                            @php
                                $rank = $loop->iteration;
                                $color = $kecPalette[$rank - 1] ?? '#10b981';
                                $pct = round(($stat->total / $maxCount) * 100);
                                $pctTotal = $totalUmkm > 0 ? round(($stat->total / $totalUmkm) * 100, 1) : 0;
                            @endphp
                            <a href="{{ route('umkm.index', ['kecamatan' => $stat->kecamatan]) }}"
                               class="group flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition block"
                               title="Klik untuk filter data Kec. {{ $stat->kecamatan }}">
                                
                                <!-- No Peringkat -->
                                <span class="w-5 text-center text-xs font-bold text-slate-900 shrink-0">
                                    {{ $rank }}
                                </span>

                                <!-- Nama Kecamatan -->
                                <div class="w-36 sm:w-40 shrink-0">
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-slate-950 transition truncate block">
                                        Kec. {{ $stat->kecamatan }}
                                    </span>
                                </div>

                                <!-- Batang Bar Chart Spektrum Warna Referensi -->
                                <div class="flex-1 h-3.5 bg-slate-100 rounded-full overflow-hidden flex items-center">
                                    <div class="h-full rounded-full transition-all duration-500 group-hover:opacity-90"
                                         style="width: {{ max($pct, 3) }}%; background-color: {{ $color }};"></div>
                                </div>

                                <!-- Nilai & Persen -->
                                <div class="text-right shrink-0 w-24 text-xs">
                                    <span class="font-bold text-slate-900 transition">
                                        {{ number_format($stat->total) }}
                                    </span>
                                    <span class="text-[11px] font-semibold text-slate-600 ml-1">
                                        ({{ $pctTotal }}%)
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Kolom 2 (Peringkat 10 s/d 18) -->
                    <div class="space-y-2.5">
                        @foreach($col2 as $index => $stat)
                            @php
                                $rank = $halfCount + $loop->iteration;
                                $color = $kecPalette[$rank - 1] ?? '#64748b';
                                $pct = round(($stat->total / $maxCount) * 100);
                                $pctTotal = $totalUmkm > 0 ? round(($stat->total / $totalUmkm) * 100, 1) : 0;
                            @endphp
                            <a href="{{ route('umkm.index', ['kecamatan' => $stat->kecamatan]) }}"
                               class="group flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition block"
                               title="Klik untuk filter data Kec. {{ $stat->kecamatan }}">
                                
                                <!-- No Peringkat -->
                                <span class="w-5 text-center text-xs font-bold text-slate-900 shrink-0">
                                    {{ $rank }}
                                </span>

                                <!-- Nama Kecamatan -->
                                <div class="w-36 sm:w-40 shrink-0">
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-slate-950 transition truncate block">
                                        Kec. {{ $stat->kecamatan }}
                                    </span>
                                </div>

                                <!-- Batang Bar Chart Spektrum Warna Referensi -->
                                <div class="flex-1 h-3.5 bg-slate-100 rounded-full overflow-hidden flex items-center">
                                    <div class="h-full rounded-full transition-all duration-500 group-hover:opacity-90"
                                         style="width: {{ max($pct, 3) }}%; background-color: {{ $color }};"></div>
                                </div>

                                <!-- Nilai & Persen -->
                                <div class="text-right shrink-0 w-24 text-xs">
                                    <span class="font-bold text-slate-900 transition">
                                        {{ number_format($stat->total) }}
                                    </span>
                                    <span class="text-[11px] font-semibold text-slate-600 ml-1">
                                        ({{ $pctTotal }}%)
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>

                <!-- Footer Tip Simpel -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                    <span>* Seluruh 18 kecamatan terdata lengkap. Klik pada baris kecamatan untuk melihat direktori UMKM terkait.</span>
                    <a href="{{ route('peta.index') }}" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline flex items-center gap-1">
                        <span>Buka Peta Sebaran GIS</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </div>
        </div>

        <!-- TAB 2: DISTRIBUSI SEKTOR USAHA (Simpel & Informatif) -->
        <div x-show="activeTab === 'sektor'" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             style="display: none;">
            
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

            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 text-xs text-slate-500">
                    <span class="font-medium">Sebaran usaha berdasarkan sektor komoditas:</span>
                    <span class="font-bold text-slate-800">{{ $kategoriList->count() }} Sektor Usaha</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($kategoriList->sortByDesc('umkm_count') as $index => $kat)
                        @php
                            $totalSektor = $kat->umkm_count;
                            $pctSektor = $totalUmkm > 0 ? round(($totalSektor / $totalUmkm) * 100, 1) : 0;
                            $ikon = $ikonKategori[$kat->nama] ?? 'fa-store';
                        @endphp
                        <a href="{{ route('umkm.index', ['kategori' => $kat->id]) }}" 
                           class="group bg-slate-50 hover:bg-white rounded-xl p-4 border border-slate-200 hover:border-emerald-500 hover:shadow-xs transition flex flex-col justify-between block">
                            <div>
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-sm mb-2.5 transition">
                                    <i class="fa-solid {{ $ikon }}"></i>
                                </div>
                                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                                    {{ number_format($totalSektor) }}
                                </div>
                                <div class="text-xs font-bold text-slate-800 mt-0.5 leading-snug group-hover:text-emerald-700 transition">
                                    {{ $kat->nama }}
                                </div>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-200/60">
                                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-600 rounded-full transition-all duration-500" 
                                         style="width: {{ min(max($pctSektor * 2.5, 6), 100) }}%"></div>
                                </div>
                                <div class="text-[11px] font-semibold text-slate-500 mt-1 flex justify-between items-center">
                                    <span>Porsi</span>
                                    <span class="text-emerald-700 font-bold">{{ $pctSektor }}%</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 7. DAFTAR UMKM : Tabel Interaktif Lengkap dengan Search & Filter           -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-white border-b border-slate-200/80" 
         x-data="{
             search: '',
             selectedKecamatan: '',
             selectedKategori: '',
             currentPage: 1,
             perPage: 10,
             umkmList: [
                 @foreach($featuredUmkm as $index => $umkm)
                 {
                     id: {{ $umkm->id }},
                     nama: {{ json_encode($umkm->nama_usaha) }},
                     kategori: {{ json_encode($umkm->kategori?->nama ?? 'Umum') }},
                     kecamatan: {{ json_encode($umkm->kecamatan) }},
                     rating: {{ (float)$umkm->rating }},
                     review: {{ (int)$umkm->jumlah_review }},
                     status_klaim: {{ json_encode($umkm->status_klaim) }},
                     url: {{ json_encode(route('umkm.show', $umkm->slug)) }},
                     deskripsi: {{ json_encode(Str::limit($umkm->deskripsi ?? '', 120)) }}
                 },
                 @endforeach
             ],
             get filteredUmkm() {
                 const s = this.search.toLowerCase().trim();
                 return this.umkmList.filter(item => {
                     const matchSearch = !s || 
                         item.nama.toLowerCase().includes(s) || 
                         item.deskripsi.toLowerCase().includes(s);
                     const matchKecamatan = !this.selectedKecamatan || item.kecamatan === this.selectedKecamatan;
                     const matchKategori = !this.selectedKategori || item.kategori === this.selectedKategori;
                     return matchSearch && matchKecamatan && matchKategori;
                 });
             },
             get totalPages() {
                 return Math.max(1, Math.ceil(this.filteredUmkm.length / this.perPage));
             },
             get paginatedUmkm() {
                 const start = (this.currentPage - 1) * this.perPage;
                 return this.filteredUmkm.slice(start, start + this.perPage);
             },
             nextPage() {
                 if (this.currentPage < this.totalPages) {
                     this.currentPage++;
                 }
             },
             prevPage() {
                 if (this.currentPage > 1) {
                     this.currentPage--;
                 }
             },
             goToPage(p) {
                 this.currentPage = p;
             },
             resetFilter() {
                 this.search = '';
                 this.selectedKecamatan = '';
                 this.selectedKategori = '';
                 this.currentPage = 1;
             }
         }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8" data-reveal>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-xs font-bold mb-3">
                    <i class="fa-solid fa-table-list"></i>
                    <span>Tabel Direktori Unggulan</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Daftar UMKM Kutai Timur
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                    Eksplorasi data profil pelaku usaha lokal melalui pencarian cepat dan filter kecamatan serta sektor bisnis.
                </p>
            </div>
            <a href="{{ route('umkm.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 text-xs font-bold transition">
                <span>Buka Seluruh Direktori</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5 mb-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Search Input -->
                <div class="lg:col-span-2 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           x-model="search" 
                           @input="currentPage = 1"
                           placeholder="Cari nama UMKM atau produk..."
                           class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-white border border-slate-300/80 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                </div>

                <!-- Filter Kecamatan (Custom Dropdown Mewah) -->
                <div class="relative" x-data="{ open: false, searchKec: '' }" @click.outside="open = false; searchKec = ''">
                    <button type="button" 
                            @click="open = !open" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-white border text-left text-xs transition-all duration-150 flex items-center justify-between shadow-2xs hover:border-emerald-400 group"
                            :class="selectedKecamatan ? 'border-emerald-500 ring-2 ring-emerald-500/20 text-emerald-950 font-semibold bg-emerald-50/40' : 'border-slate-300/80 text-slate-700 hover:text-slate-900'">
                        <div class="flex items-center gap-2 truncate pr-1">
                            <i class="fa-solid fa-location-dot text-xs transition-colors shrink-0"
                               :class="selectedKecamatan ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'"></i>
                            <span class="truncate" x-text="selectedKecamatan || 'Semua Wilayah Kecamatan'"></span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <template x-if="selectedKecamatan">
                                <span @click.stop="selectedKecamatan = ''; currentPage = 1" 
                                      class="w-4 h-4 rounded-full bg-emerald-200/60 hover:bg-rose-100 hover:text-rose-600 text-emerald-800 inline-flex items-center justify-center text-[9px] transition-colors"
                                      title="Hapus filter">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            </template>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                               :class="open ? 'rotate-180 text-emerald-600' : ''"></i>
                        </div>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150 transform"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute left-0 w-full sm:w-[280px] top-full mt-1.5 z-50 bg-white rounded-2xl shadow-xl border border-slate-200/90 p-2 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Quick Search Input -->
                        <div class="relative mb-2 px-1 pt-0.5">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                            <input type="text" 
                                   x-model="searchKec" 
                                   placeholder="Cari kecamatan..." 
                                   @click.stop
                                   class="w-full pl-8 pr-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
                        </div>

                        <!-- Options List -->
                        <div class="max-h-56 overflow-y-auto space-y-0.5 pr-1 text-xs select-none">
                            <!-- All Option -->
                            <button type="button"
                                    @click="selectedKecamatan = ''; currentPage = 1; open = false; searchKec = ''"
                                    class="w-full px-3 py-2 rounded-xl text-left text-xs flex items-center justify-between transition-colors"
                                    :class="!selectedKecamatan ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 hover:bg-slate-100/80 font-medium'">
                                <div class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-earth-americas text-xs" :class="!selectedKecamatan ? 'text-emerald-600' : 'text-slate-400'"></i>
                                    <span>Semua Wilayah Kecamatan</span>
                                </div>
                                <template x-if="!selectedKecamatan">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                </template>
                            </button>

                            <div class="h-px bg-slate-100 my-1"></div>

                            <!-- List of Kecamatan -->
                            @foreach($daftarKecamatan as $kec)
                            <button type="button"
                                    x-show="!searchKec || '{{ strtolower($kec) }}'.includes(searchKec.toLowerCase().trim())"
                                    @click="selectedKecamatan = '{{ $kec }}'; currentPage = 1; open = false; searchKec = ''"
                                    class="w-full px-3 py-2 rounded-xl text-left text-xs flex items-center justify-between transition-colors group"
                                    :class="selectedKecamatan === '{{ $kec }}' ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 hover:bg-emerald-50/70 hover:text-emerald-900 font-medium'">
                                <div class="flex items-center gap-2.5 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full transition-colors shrink-0"
                                          :class="selectedKecamatan === '{{ $kec }}' ? 'bg-emerald-500' : 'bg-slate-300 group-hover:bg-emerald-400'"></span>
                                    <span class="truncate">{{ $kec }}</span>
                                </div>
                                <template x-if="selectedKecamatan === '{{ $kec }}'">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                </template>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Filter Kategori / Sektor (Custom Dropdown Mewah) -->
                <div class="relative" x-data="{ open: false, searchKat: '' }" @click.outside="open = false; searchKat = ''">
                    <button type="button" 
                            @click="open = !open" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-white border text-left text-xs transition-all duration-150 flex items-center justify-between shadow-2xs hover:border-emerald-400 group"
                            :class="selectedKategori ? 'border-emerald-500 ring-2 ring-emerald-500/20 text-emerald-950 font-semibold bg-emerald-50/40' : 'border-slate-300/80 text-slate-700 hover:text-slate-900'">
                        <div class="flex items-center gap-2 truncate pr-1">
                            <i class="fa-solid fa-tags text-xs transition-colors shrink-0"
                               :class="selectedKategori ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'"></i>
                            <span class="truncate" x-text="selectedKategori || 'Semua Sektor Usaha'"></span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <template x-if="selectedKategori">
                                <span @click.stop="selectedKategori = ''; currentPage = 1" 
                                      class="w-4 h-4 rounded-full bg-emerald-200/60 hover:bg-rose-100 hover:text-rose-600 text-emerald-800 inline-flex items-center justify-center text-[9px] transition-colors"
                                      title="Hapus filter">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            </template>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                               :class="open ? 'rotate-180 text-emerald-600' : ''"></i>
                        </div>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150 transform"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute right-0 w-full sm:w-[280px] top-full mt-1.5 z-50 bg-white rounded-2xl shadow-xl border border-slate-200/90 p-2 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Quick Search Input -->
                        <div class="relative mb-2 px-1 pt-0.5">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                            <input type="text" 
                                   x-model="searchKat" 
                                   placeholder="Cari sektor usaha..." 
                                   @click.stop
                                   class="w-full pl-8 pr-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
                        </div>

                        <!-- Options List -->
                        <div class="max-h-56 overflow-y-auto space-y-0.5 pr-1 text-xs select-none">
                            <!-- All Option -->
                            <button type="button"
                                    @click="selectedKategori = ''; currentPage = 1; open = false; searchKat = ''"
                                    class="w-full px-3 py-2 rounded-xl text-left text-xs flex items-center justify-between transition-colors"
                                    :class="!selectedKategori ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 hover:bg-slate-100/80 font-medium'">
                                <div class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-layer-group text-xs" :class="!selectedKategori ? 'text-emerald-600' : 'text-slate-400'"></i>
                                    <span>Semua Sektor Usaha</span>
                                </div>
                                <template x-if="!selectedKategori">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                </template>
                            </button>

                            <div class="h-px bg-slate-100 my-1"></div>

                            <!-- List of Kategori -->
                            @foreach($kategoriList as $kat)
                            <button type="button"
                                    x-show="!searchKat || '{{ strtolower($kat->nama) }}'.includes(searchKat.toLowerCase().trim())"
                                    @click="selectedKategori = '{{ $kat->nama }}'; currentPage = 1; open = false; searchKat = ''"
                                    class="w-full px-3 py-2 rounded-xl text-left text-xs flex items-center justify-between transition-colors group"
                                    :class="selectedKategori === '{{ $kat->nama }}' ? 'bg-emerald-50 text-emerald-900 font-bold' : 'text-slate-700 hover:bg-emerald-50/70 hover:text-emerald-900 font-medium'">
                                <div class="flex items-center gap-2.5 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full transition-colors shrink-0"
                                          :class="selectedKategori === '{{ $kat->nama }}' ? 'bg-emerald-500' : 'bg-slate-300 group-hover:bg-emerald-400'"></span>
                                    <span class="truncate">{{ $kat->nama }}</span>
                                </div>
                                <template x-if="selectedKategori === '{{ $kat->nama }}'">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                </template>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filter Status & Reset -->
            <div x-show="search || selectedKecamatan || selectedKategori" 
                 x-transition 
                 class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                <span>
                    Ditemukan <strong class="text-emerald-700" x-text="filteredUmkm.length"></strong> UMKM yang sesuai
                </span>
                <button @click="resetFilter()" 
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                    <i class="fa-solid fa-arrow-rotate-left text-[10px]"></i>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/90 shadow-sm bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Usaha / Merek</th>
                        <th class="py-3.5 px-4">Wilayah</th>
                        <th class="py-3.5 px-4">Sektor Usaha</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Rating</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <template x-for="(item, index) in paginatedUmkm" :key="item.id">
                        <tr class="hover:bg-emerald-50/40 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400" x-text="(currentPage - 1) * perPage + index + 1"></td>
                            <td class="py-3.5 px-4">
                                <a :href="item.url" class="font-bold text-slate-900 hover:text-emerald-700 transition block text-sm" x-text="item.nama"></a>
                                <span class="text-[11px] text-slate-400 block line-clamp-1 mt-0.5" x-text="item.deskripsi || 'Produk unggulan Kutai Timur'"></span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-slate-600">
                                    <i class="fa-solid fa-location-dot text-emerald-600 text-[11px]"></i>
                                    <span x-text="item.kecamatan"></span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold text-[11px] border border-emerald-200/60" x-text="item.kategori"></span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <template x-if="item.status_klaim === 'terverifikasi'">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-circle-check text-[9px] text-emerald-600"></i> Terverifikasi
                                    </span>
                                </template>
                                <template x-if="item.status_klaim !== 'terverifikasi'">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                        <i class="fa-regular fa-clock text-[9px]"></i> Terdaftar
                                    </span>
                                </template>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 text-amber-500 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    <span x-text="item.rating.toFixed(1)"></span>
                                    <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + item.review + ')'"></span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a :href="item.url" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[11px] transition shadow-2xs">
                                    <span>Detail</span>
                                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                </a>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <tr x-show="filteredUmkm.length === 0" style="display: none;">
                        <td colspan="7" class="py-12 px-4 text-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Tidak Ditemukan Data UMKM</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                Coba ubah kata kunci pencarian atau reset filter wilayah dan sektor usaha Anda.
                            </p>
                            <button @click="resetFilter()" 
                                    class="mt-3 px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                Reset Semua Filter
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Table Pagination Bar (Geser 10 per 10) -->
            <div x-show="filteredUmkm.length > 0" class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <!-- Info Jumlah Data -->
                <div class="text-slate-500 font-medium">
                    Menampilkan 
                    <strong class="text-slate-800" x-text="(currentPage - 1) * perPage + 1"></strong>
                    sampai 
                    <strong class="text-slate-800" x-text="Math.min(currentPage * perPage, filteredUmkm.length)"></strong>
                    dari 
                    <strong class="text-emerald-700" x-text="filteredUmkm.length"></strong> UMKM
                </div>

                <!-- Kontrol Paginasi Geser 10-10 -->
                <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                    <!-- Tombol Geser Sebelumnya -->
                    <button @click="prevPage()" 
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-100' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 bg-white border border-slate-200 shadow-2xs'"
                            class="px-3 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </button>

                    <!-- Tombol Angka Halaman (1, 2, 3...) -->
                    <div class="flex items-center gap-1">
                        <template x-for="p in totalPages" :key="p">
                            <button @click="goToPage(p)"
                                    :class="currentPage === p ? 'bg-emerald-700 text-white font-black shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 font-semibold'"
                                    class="w-8 h-8 rounded-xl text-xs transition flex items-center justify-center cursor-pointer"
                                    x-text="p">
                            </button>
                        </template>
                    </div>

                    <!-- Tombol Geser Selanjutnya -->
                    <button @click="nextPage()" 
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-100' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 bg-white border border-slate-200 shadow-2xs'"
                            class="px-3 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
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
<!-- 10. LAYANAN TERKAIT & INTEGRASI (Dinkop Kutim & SIGAP)                     -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mb-12" data-reveal>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-xs font-bold mb-3">
                <i class="fa-solid fa-link"></i>
                <span>Sinergi & Integrasi Layanan</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Portal & Layanan Terintegrasi
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Akses cepat menuju portal resmi kedinasan dan sistem pendukung layanan publik Kabupaten Kutai Timur.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <!-- Card 1: Web Dinkop Kutim -->
            <div class="group relative rounded-3xl bg-gradient-to-br from-emerald-950 via-slate-950 to-emerald-900 p-8 text-white shadow-xl overflow-hidden border border-emerald-800/40 flex flex-col justify-between" data-reveal>
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:scale-125 transition duration-700 pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[11px] font-bold uppercase tracking-wider">
                            Portal Resmi Pemerintah
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-xl group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                    </div>

                    <h3 class="text-2xl font-black tracking-tight text-white group-hover:text-emerald-300 transition">
                        Website Dinkop Kutim
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Pusat informasi resmi, regulasi dinas, perizinan pembinaan koperasi, serta program bantuan permodalan dari Dinas Koperasi dan UKM Kabupaten Kutai Timur.
                    </p>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/10">
                    <a href="https://dinkop.kutaitimurkab.go.id/" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition duration-200 shadow-md">
                        <span>Kunjungi Web Dinkop Kutim</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: Tombol SIGAP -->
            <div class="group relative rounded-3xl bg-gradient-to-br from-amber-950 via-slate-950 to-[#2e1d05] p-8 text-white shadow-xl overflow-hidden border border-amber-800/40 flex flex-col justify-between" data-reveal data-reveal-delay="2">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl group-hover:scale-125 transition duration-700 pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 text-[11px] font-bold uppercase tracking-wider">
                            Sistem Layanan Terpadu
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-amber-300 text-xl group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>

                    <h3 class="text-2xl font-black tracking-tight text-white group-hover:text-amber-300 transition">
                        Layanan SIGAP Kutai Timur
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Sistem Informasi & Gerak Cepat pendampingan pelaku usaha, respon kendala lapangan, serta saluran komunikasi tanggap langsung bagi wirausaha daerah.
                    </p>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/10">
                    {{-- TODO: Ganti URL '#' dengan tautan resmi sistem SIGAP setelah tersedia dari pihak dinas --}}
                    <a href="#" 
                       class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-black text-xs transition duration-200 shadow-md">
                        <span>Akses Layanan SIGAP</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ========================================================================== -->
<!-- 11. SOSIAL MEDIA RESMI & KANAL PUBLIK (5 KARTU EXPANDING ACCORDION)        -->
<!-- ========================================================================== -->
<section class="py-16 md:py-20 bg-slate-50 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10" data-reveal>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-xs font-bold mb-3 shadow-xs">
                    <i class="fa-solid fa-hashtag text-emerald-600"></i>
                    <span>Kanal Komunikasi Publik</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Media Sosial Resmi
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed max-w-xl">
                    Ikuti perkembangan kegiatan expo bazar, jadwal pelatihan wirausaha, dan sorotan produk UMKM Kutim di 5 kanal resmi.
                </p>
            </div>
            <span class="hidden md:inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 bg-white px-3 py-1.5 rounded-full border border-slate-200/80 shadow-2xs">
                <i class="fa-solid fa-arrow-pointer text-emerald-600 text-[10px]"></i> Arahkan kursor ke kartu untuk melihat detail
            </span>
        </div>

        <!-- 5 Cards Accordion Layout (Hover Membesar ke Kanan) -->
        <div class="expand-social-container flex flex-col lg:flex-row gap-4 w-full items-stretch" data-reveal>
            
            <!-- Card 1: Instagram -->
            <div class="expand-social-card rounded-3xl p-5 lg:p-6 bg-gradient-to-br from-pink-50/70 via-white to-pink-50/30 border border-pink-200/90 hover:border-pink-500 transition-all duration-300 flex flex-col justify-between group shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#833ab4] via-[#fd1d1d] to-[#fcb045] text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform duration-300 shrink-0">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <span class="social-card-tag px-2.5 py-1 rounded-full bg-pink-100/80 border border-pink-200 text-pink-700 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                            Terpopuler
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-pink-600 block truncate">@dinkop_kutim</span>
                        <h3 class="text-base lg:text-lg font-black text-slate-900 group-hover:text-pink-600 transition truncate">
                            Instagram Resmi Diskop
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">
                            Liputan foto harian, jadwal bazar kecamatan, live report pameran produk, dan pengumuman program bantuan modal usaha.
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-pink-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <span class="text-[11px] font-semibold text-slate-400 hidden xl:group-hover:inline-block truncate">
                        Instagram Feed & Stories
                    </span>
                    <a href="https://www.instagram.com/dinkop_kutim" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white text-xs font-bold transition w-full xl:group-hover:w-auto justify-center shadow-xs shrink-0">
                        <span>Ikuti @dinkop_kutim</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: TikTok -->
            <div class="expand-social-card rounded-3xl p-5 lg:p-6 bg-gradient-to-br from-slate-50/70 via-white to-slate-50/30 border border-slate-200/90 hover:border-slate-900 transition-all duration-300 flex flex-col justify-between group shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform duration-300 shrink-0">
                            <i class="fa-brands fa-tiktok"></i>
                        </div>
                        <span class="social-card-tag px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-800 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                            Viral & Reels
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-800 block truncate">@diskopukm.kutim</span>
                        <h3 class="text-base lg:text-lg font-black text-slate-900 group-hover:text-slate-950 transition truncate">
                            TikTok Kreatif Diskop
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">
                            Cuplikan video reels inspiratif, profil pelaku usaha kreatif, tips wirausaha, dan keseruan bazar expo daerah.
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <span class="text-[11px] font-semibold text-slate-400 hidden xl:group-hover:inline-block truncate">
                        Video Pendek & Tips
                    </span>
                    <a href="https://www.tiktok.com" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-950 hover:bg-slate-800 text-white text-xs font-bold transition w-full xl:group-hover:w-auto justify-center shadow-xs shrink-0">
                        <span>Buka TikTok</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Card 3: YouTube -->
            <div class="expand-social-card rounded-3xl p-5 lg:p-6 bg-gradient-to-br from-red-50/70 via-white to-red-50/30 border border-red-200/90 hover:border-red-600 transition-all duration-300 flex flex-col justify-between group shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform duration-300 shrink-0">
                            <i class="fa-brands fa-youtube"></i>
                        </div>
                        <span class="social-card-tag px-2.5 py-1 rounded-full bg-red-100/80 border border-red-200 text-red-700 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                            Video Resmi
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-red-600 block truncate">YouTube Channel</span>
                        <h3 class="text-base lg:text-lg font-black text-slate-900 group-hover:text-red-600 transition truncate">
                            Diskop UKM Kutim TV
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">
                            Video dokumentasi pelatihan, profil inspiratif pelaku UMKM, tutorial NIB, dan liputan pameran expo daerah.
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-red-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <span class="text-[11px] font-semibold text-slate-400 hidden xl:group-hover:inline-block truncate">
                        Dokumentasi & Tutorial
                    </span>
                    <a href="https://www.youtube.com" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition w-full xl:group-hover:w-auto justify-center shadow-xs shrink-0">
                        <span>Tonton Video</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Card 4: Facebook -->
            <div class="expand-social-card rounded-3xl p-5 lg:p-6 bg-gradient-to-br from-blue-50/70 via-white to-blue-50/30 border border-blue-200/90 hover:border-blue-600 transition-all duration-300 flex flex-col justify-between group shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform duration-300 shrink-0">
                            <i class="fa-brands fa-facebook-f"></i>
                        </div>
                        <span class="social-card-tag px-2.5 py-1 rounded-full bg-blue-100/80 border border-blue-200 text-blue-700 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                            Komunitas
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-blue-600 block truncate">Facebook Page</span>
                        <h3 class="text-base lg:text-lg font-black text-slate-900 group-hover:text-blue-600 transition truncate">
                            Dinas Koperasi Kutim
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">
                            Forum komunitas wirausaha daerah dan rilis resmi agenda dinas bagi warga Kabupaten Kutai Timur.
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-blue-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <span class="text-[11px] font-semibold text-slate-400 hidden xl:group-hover:inline-block truncate">
                        Grup & Fanpage Resmi
                    </span>
                    <a href="https://www.facebook.com/dinkopkutim" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition w-full xl:group-hover:w-auto justify-center shadow-xs shrink-0">
                        <span>Kunjungi Halaman</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Card 5: WhatsApp / Hotline -->
            <div class="expand-social-card rounded-3xl p-5 lg:p-6 bg-gradient-to-br from-emerald-50/70 via-white to-emerald-50/30 border border-emerald-200/90 hover:border-emerald-600 transition-all duration-300 flex flex-col justify-between group shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform duration-300 shrink-0">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <span class="social-card-tag px-2.5 py-1 rounded-full bg-emerald-100/80 border border-emerald-200 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                            Respon Cepat
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-emerald-600 block truncate">Hotline Konsultasi</span>
                        <h3 class="text-base lg:text-lg font-black text-slate-900 group-hover:text-emerald-600 transition truncate">
                            WhatsApp Helpdesk
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">
                            Konsultasi langsung pendampingan legalitas, kendala akun direktori, fasilitasi bazar, dan bantuan dinas.
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-emerald-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <span class="text-[11px] font-semibold text-slate-400 hidden xl:group-hover:inline-block truncate">
                        Helpdesk & Info Langsung
                    </span>
                    <a href="https://wa.me/6281234567890" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition w-full xl:group-hover:w-auto justify-center shadow-xs shrink-0">
                        <span>Chat WhatsApp</span>
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </a>
                </div>
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

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Glassmorphism Ultra-Translucent Service Card System */
    .glass-action-card {
        background: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(14px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(14px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.35), inset 0 1px 0 0 rgba(255, 255, 255, 0.35) !important;
        transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .glass-action-card:hover {
        background: rgba(255, 255, 255, 0.22) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 14px 40px -5px rgba(0, 0, 0, 0.45), inset 0 1px 0 0 rgba(255, 255, 255, 0.6) !important;
        transform: translateY(-2px);
    }

    .tabular-nums {
        font-variant-numeric: tabular-nums !important;
    }

    .social-card-tag {
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
        transition: opacity 250ms ease, transform 250ms ease, max-width 300ms cubic-bezier(0.25, 1, 0.35, 1), padding 250ms ease, margin 250ms ease, border-width 250ms ease !important;
        overflow: hidden !important;
    }

    /* ==========================================================================
       Expanding Social Media Cards Accordion (Membesar / Melebar ke Kanan)
       ========================================================================== */
    @media (min-width: 1024px) {
        .expand-social-container {
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch !important;
            width: 100% !important;
            gap: 1rem !important;
        }

        .expand-social-card {
            flex: 1 1 0% !important;
            min-width: 0 !important;
            transform-origin: left center !important;
            transition: flex 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 350ms cubic-bezier(0.22, 1, 0.36, 1),
                        box-shadow 350ms cubic-bezier(0.22, 1, 0.36, 1),
                        border-color 300ms ease,
                        opacity 300ms ease !important;
            will-change: flex, transform;
            cursor: pointer;
            overflow: hidden !important;
        }

        /* Saat dihover: Kartu melebar dan membesar signifikan ke arah kanan */
        .expand-social-card:hover {
            flex: 2.7 1 0% !important;
            transform: translateY(-4px) !important;
            z-index: 20 !important;
            box-shadow: 0 20px 30px -8px rgba(0, 0, 0, 0.12), 0 8px 12px -6px rgba(0, 0, 0, 0.05) !important;
        }

        /* Kartu lain menyempit halus */
        .expand-social-container:hover .expand-social-card:not(:hover) {
            flex: 0.65 1 0% !important;
            opacity: 0.7 !important;
            filter: grayscale(15%);
        }

        .expand-social-container:hover .expand-social-card:hover {
            opacity: 1 !important;
            filter: none !important;
        }

        /* Hilangkan tag/badge pada kartu-kartu yang TIDAK di-hover */
        .expand-social-container:hover .expand-social-card:not(:hover) .social-card-tag {
            opacity: 0 !important;
            max-width: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
            border-width: 0 !important;
            border-color: transparent !important;
            pointer-events: none !important;
            transform: scale(0.7) !important;
        }

        /* Kartu yang sedang di-hover tetap menampilkan tag secara utuh */
        .expand-social-card:hover .social-card-tag {
            opacity: 1 !important;
            max-width: 140px !important;
            transform: scale(1) !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ----------------------------------------------------------------------
        // 0. Animasi Count-Up Terorkestrasi KPI (IntersectionObserver + easeOutCubic)
        // ----------------------------------------------------------------------
        (function initKpiCountUp() {
            const statsSection = document.getElementById('section-kpi-stats');
            if (!statsSection) return;

            const statElements = statsSection.querySelectorAll('.stat-number');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (prefersReducedMotion) {
                return; // Hormati prefers-reduced-motion: langsung tampilkan angka final
            }

            // Set tampilan awal 0 sebelum animasi dimulai saat masuk viewport
            statElements.forEach(el => {
                el.textContent = '0';
            });

            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

            const animateCountUp = (el, target, delay) => {
                const duration = 1300; // 1300ms sesuai spesifikasi (1200-1400ms)
                setTimeout(() => {
                    const startTime = performance.now();
                    const step = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const eased = easeOutCubic(progress);
                        const currentVal = Math.round(eased * target);
                        
                        el.textContent = currentVal.toLocaleString('en-US');

                        if (progress < 1) {
                            requestAnimationFrame(step);
                        } else {
                            el.textContent = target.toLocaleString('en-US');
                        }
                    };
                    requestAnimationFrame(step);
                }, delay);
            };

            let hasAnimated = false;
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasAnimated) {
                        hasAnimated = true;
                        statElements.forEach((el, index) => {
                            const target = parseInt(el.getAttribute('data-target'), 10) || 0;
                            const delay = index * 90; // Staggered delay ~90ms urutan baca kiri ke kanan
                            animateCountUp(el, target, delay);
                        });
                        obs.disconnect();
                    }
                });
            }, {
                threshold: 0.15
            });

            observer.observe(statsSection);
        })();

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
                scrollWheelZoom: false,
                touchZoom: false,
                doubleClickZoom: false,
                boxZoom: false
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
