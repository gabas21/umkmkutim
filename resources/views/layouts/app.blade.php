<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Resmi Direktori & Ekosistem Digital UMKM Kabupaten Kutai Timur - Fasilitasi, Promosi, Bazar, Pelatihan, dan Verifikasi Usaha Lokal.">
    <title>@yield('title', 'Portal Direktori UMKM Kabupaten Kutai Timur')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo1.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Leaflet & MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-950: #052e16;
            --green-900: #14532d;
            --green-800: #166534;
            --green-700: #15803d;
            --green-600: #16a34a;
            --green-50: #f0fdf4;
            --slate-50: #f8fafc;
            --slate-900: #0f172a;
            --gold-500: #d97706;
            --gold-400: #f59e0b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-kutim {
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #032b21 100%);
        }

        .hero-mesh-bg {
            background-color: #021f18;
            background-image: 
                radial-gradient(at 15% 15%, rgba(16, 185, 129, 0.22) 0px, transparent 55%),
                radial-gradient(at 85% 20%, rgba(20, 184, 166, 0.16) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(5, 150, 105, 0.22) 0px, transparent 55%),
                radial-gradient(at 90% 85%, rgba(245, 158, 11, 0.08) 0px, transparent 40%);
        }

        .gradient-gold {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        /* Glassmorphism utility presets */
        .glass-panel {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(22, 163, 74, 0.12);
        }

        .glass-card-dark {
            background: rgba(5, 46, 22, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .glass-badge {
            background: rgba(5, 46, 22, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Scroll Reveal System (Native IntersectionObserver) */
        [data-reveal] {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        [data-reveal="left"] {
            transform: translateX(-24px);
        }
        [data-reveal="right"] {
            transform: translateX(24px);
        }
        [data-reveal="scale"] {
            transform: scale(0.96);
        }
        [data-reveal].revealed {
            opacity: 1;
            transform: none;
        }
        [data-reveal-delay="1"].revealed { transition-delay: 0.1s; }
        [data-reveal-delay="2"].revealed { transition-delay: 0.2s; }
        [data-reveal-delay="3"].revealed { transition-delay: 0.3s; }
        [data-reveal-delay="4"].revealed { transition-delay: 0.4s; }

        /* Hide scrollbars for snap pills */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-full text-slate-800 selection:bg-emerald-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Main Navigation Bar (Glassmorphic Sticky) -->
    <header class="sticky top-0 z-40 bg-white/88 backdrop-blur-xl border-b border-emerald-900/10 shadow-[0_2px_20px_rgba(0,0,0,0.03)] transition duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Branding -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-12 w-auto object-contain group-hover:scale-105 transition duration-200 drop-shadow-sm">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-black tracking-tight text-slate-900">UMKM<span class="text-emerald-700">KUTIM</span></span>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-100/90 text-amber-800 border border-amber-300/80 rounded tracking-wider">KUTAI TIMUR</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Ekosistem & Direktori Digital Usaha Daerah</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links (5 Menu Sesuai Brief) -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5">
                    <!-- 1. Home / Beranda -->
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/70 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Beranda
                    </a>

                    <!-- 2. UMKM -->
                    <a href="{{ route('umkm.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('umkm.*') || request()->routeIs('peta.*') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/70 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        UMKM
                    </a>

                    <!-- 3. Bazar -->
                    <a href="{{ route('bazar.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('bazar.*') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/70 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition flex items-center gap-1.5">
                        <span>Bazar</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    </a>

                    <!-- 4. Pelatihan -->
                    <a href="{{ route('pelatihan.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('pelatihan.*') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/70 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Pelatihan
                    </a>

                    <!-- 5. Laporan -->
                    <a href="{{ route('laporan.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('laporan.*') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/70 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Laporan
                    </a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="hidden md:flex items-center gap-2.5">
                    <!-- Daftarkan Usaha Gold CTA Button -->
                    @if(Auth::guard('pelaku_usaha')->check())
                        <a href="{{ route('umkm.create-mandiri') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/15 transition active:scale-95">
                            <i class="fa-solid fa-plus-circle"></i> Daftarkan Usaha
                        </a>
                        <a href="{{ route('dashboard.pelaku') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm transition">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Keluar">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    @elseif(Auth::guard('web')->check() && Auth::user()->isAdmin())
                        <div class="flex items-center gap-1 text-xs">
                            <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 font-bold text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">Klaim</a>
                            <a href="{{ route('admin.import') }}" class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('admin.import') ? 'bg-slate-100 font-bold text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">Import CSV</a>
                            <a href="{{ route('admin.kategori.index') }}" class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'bg-slate-100 font-bold text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">Kategori</a>
                            <a href="{{ route('admin.umkm.index') }}" class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('admin.umkm.*') ? 'bg-slate-100 font-bold text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">Moderasi</a>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Keluar">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/15 transition active:scale-95">
                            <i class="fa-solid fa-store"></i> Daftarkan Usaha
                        </a>
                        <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:text-emerald-700 hover:bg-slate-100 transition">
                            Masuk
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none" aria-label="Menu Utama">
                        <i :class="mobileMenuOpen ? 'fa-solid fa-xmark text-2xl' : 'fa-solid fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu dropdown -->
        <div x-show="mobileMenuOpen" x-transition.opacity.duration.200ms class="lg:hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-1 shadow-xl">
            <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-house w-6 text-emerald-600"></i> Beranda
            </a>
            <a href="{{ route('umkm.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('umkm.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-store w-6 text-emerald-600"></i> UMKM (Direktori & Peta)
            </a>
            <a href="{{ route('bazar.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('bazar.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-tent w-6 text-amber-500"></i> Bazar & Event
            </a>
            <a href="{{ route('pelatihan.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('pelatihan.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-graduation-cap w-6 text-emerald-600"></i> Pelatihan Usaha
            </a>
            <a href="{{ route('laporan.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('laporan.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-chart-pie w-6 text-emerald-600"></i> Laporan Transparansi
            </a>
            <a href="{{ route('survey.create') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-amber-700 hover:bg-amber-50">
                <i class="fa-solid fa-star w-6 text-amber-500"></i> Survey Kepuasan Layanan
            </a>

            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @if(Auth::guard('pelaku_usaha')->check())
                    <a href="{{ route('umkm.create-mandiri') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500">
                        + Daftarkan Usaha Baru
                    </a>
                    <a href="{{ route('dashboard.pelaku') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-emerald-700">
                        Dashboard Usaha Saya
                    </a>
                @elseif(Auth::guard('web')->check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-emerald-800">
                        Panel Admin Dinas
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500">
                        Daftarkan Usaha (Gratis)
                    </a>
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-300 text-slate-700">
                        Masuk Pelaku Usaha
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Global Alerts / Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" data-reveal>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" data-reveal>
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-3 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" data-reveal>
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">
                    {{ session('error') ?? $errors->first() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Rich Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <!-- Branding & Gov Info -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo1.png') }}" alt="Logo Kabupaten Kutai Timur" class="h-11 w-auto object-contain drop-shadow-md">
                        <div>
                            <span class="text-xl font-black text-white">UMKM<span class="text-emerald-400">KUTIM</span></span>
                            <span class="block text-[11px] text-slate-400 uppercase tracking-widest">Kabupaten Kutai Timur</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Platform resmi digitalisasi data, fasilitasi legalitas NIB/Halal, pendaftaran bazar expo, pelatihan wirausaha, serta direktori produk unggulan 18 kecamatan se-Kabupaten Kutai Timur.
                    </p>
                    <div class="pt-2 text-xs text-slate-400 space-y-2">
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                            <span>Kompleks Perkantoran Bukit Pelangi, Sangatta, Kab. Kutai Timur, Kalimantan Timur</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-emerald-500"></i>
                            <span>diskopumkm@kutaitimurkab.go.id</span>
                        </p>
                    </div>
                </div>

                <!-- 5 Modul Utama Navigasi -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Layanan Portal</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Beranda Utama</a></li>
                        <li><a href="{{ route('umkm.index') }}" class="hover:text-emerald-400 transition">Direktori & Peta UMKM</a></li>
                        <li><a href="{{ route('bazar.index') }}" class="hover:text-emerald-400 transition flex items-center gap-1.5"><span>Bazar & Pameran</span> <span class="px-1.5 py-0.2 rounded text-[10px] bg-amber-500/20 text-amber-300 font-bold">Baru</span></a></li>
                        <li><a href="{{ route('pelatihan.index') }}" class="hover:text-emerald-400 transition">Pelatihan & Bimtek</a></li>
                        <li><a href="{{ route('laporan.index') }}" class="hover:text-emerald-400 transition">Laporan Transparansi</a></li>
                        <li><a href="{{ route('survey.create') }}" class="hover:text-amber-400 transition font-semibold text-amber-300">Survey Kepuasan Layanan</a></li>
                    </ul>
                </div>

                <!-- Cakupan Wilayah (18 Kecamatan) -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Cakupan 18 Kecamatan Kutim</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $kecamatans = ['Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan', 'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal', 'Muara Ancalong', 'Busang', 'Telen', 'Sandaran', 'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan', 'Batu Ampar', 'Long Mesangat'];
                        @endphp
                        @foreach($kecamatans as $kec)
                            <a href="{{ route('umkm.index', ['kecamatan' => $kec]) }}" class="px-2.5 py-1 text-xs rounded-lg bg-slate-800 hover:bg-emerald-900/60 hover:text-emerald-300 text-slate-400 transition">
                                {{ $kec }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-800/80 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Dinas Koperasi, Usaha Kecil dan Menengah Kabupaten Kutai Timur. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('laporan.index') }}" class="hover:text-slate-400 transition">Dashboard Publik</a>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-slate-400 transition">Akses Administrator Dinas</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Leaflet & Clustering JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <!-- Global Native IntersectionObserver Scroll Reveal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reveals = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window && reveals.length > 0) {
                const io = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            io.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.08,
                    rootMargin: '0px 0px -30px 0px'
                });

                reveals.forEach(el => io.observe(el));
            } else {
                reveals.forEach(el => el.classList.add('revealed'));
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
