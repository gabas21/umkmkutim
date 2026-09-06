<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Resmi Direktori & Ekosistem Digital UMKM Kabupaten Kutai Timur - Fasilitasi, Promosi, dan Verifikasi Usaha Lokal.">
    <title>@yield('title', 'Portal Direktori UMKM Kabupaten Kutai Timur')</title>

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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Refined Deep Regal Emerald Palette */
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
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-full text-slate-800 selection:bg-emerald-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-[#021813] text-emerald-200/90 text-xs py-2 px-4 border-b border-emerald-900/60">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-800/90 text-emerald-100 border border-emerald-700/60">Resmi</span>
                <span>Pemerintah Kabupaten Kutai Timur — Dinas Koperasi, Usaha Kecil dan Menengah</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.login') }}" class="hover:text-white transition flex items-center gap-1.5 opacity-90 hover:opacity-100">
                    <i class="fa-solid fa-lock text-[10px]"></i> Portal Admin Dinas
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Branding -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-800 to-slate-900 border border-emerald-700/40 flex items-center justify-center text-white shadow-md shadow-emerald-950/20 group-hover:scale-105 transition duration-200">
                        <i class="fa-solid fa-store text-lg text-amber-300"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-black tracking-tight text-slate-900">UMKM<span class="text-emerald-700">KUTIM</span></span>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-100/80 text-amber-800 border border-amber-200 rounded">KUTAI TIMUR</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Direktori Terpadu Pelaku Usaha Daerah</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/60 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('umkm.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('umkm.index') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/60 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Direktori Usaha
                    </a>
                    <a href="{{ route('peta.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('peta.index') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/60 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition flex items-center gap-1.5">
                        <i class="fa-solid fa-map-location-dot text-emerald-700"></i> Peta Sebaran
                    </a>
                    <a href="{{ route('berita.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('berita.*') ? 'text-emerald-800 bg-emerald-50/90 border border-emerald-200/60 shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        Berita & Info
                    </a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @if(Auth::guard('pelaku_usaha')->check())
                        <a href="{{ route('dashboard.pelaku') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-emerald-700 to-emerald-800 hover:from-emerald-600 hover:to-emerald-700 shadow-md shadow-emerald-950/20 transition">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard Usaha
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
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-emerald-700 hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-emerald-700 to-emerald-800 hover:from-emerald-600 hover:to-emerald-700 shadow-md shadow-emerald-950/20 hover:shadow-lg transition">
                            <i class="fa-solid fa-id-card"></i> Daftar Pemilik Usaha
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <i :class="mobileMenuOpen ? 'fa-solid fa-xmark text-2xl' : 'fa-solid fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600">Beranda</a>
            <a href="{{ route('umkm.index') }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600">Direktori Usaha</a>
            <a href="{{ route('home') }}#seksi-peta" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600">Peta Sebaran</a>
            <a href="{{ route('berita.index') }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600">Berita & Agenda</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @if(Auth::guard('pelaku_usaha')->check())
                    <a href="{{ route('dashboard.pelaku') }}" class="w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600">Dashboard Usaha Saya</a>
                @elseif(Auth::guard('web')->check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-800">Panel Admin Dinas</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold border border-slate-300 text-slate-700">Masuk Pelaku Usaha</a>
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600">Daftar Akun Baru</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Global Alerts / Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm">
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
                        <div class="w-10 h-10 rounded-lg gradient-kutim flex items-center justify-center text-white">
                            <i class="fa-solid fa-store text-lg text-amber-300"></i>
                        </div>
                        <div>
                            <span class="text-xl font-black text-white">UMKM<span class="text-emerald-400">KUTIM</span></span>
                            <span class="block text-[11px] text-slate-400 uppercase tracking-widest">Kabupaten Kutai Timur</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Platform resmi digitalisasi data, fasilitasi legalitas, klaim kepemilikan usaha, dan promosi produk unggulan UMKM Kabupaten Kutai Timur — Menuju kemandirian ekonomi daerah yang berdaya saing.
                    </p>
                    <div class="pt-2 text-xs text-slate-400 space-y-1.5">
                        <p class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-emerald-500"></i> Kompleks Perkantoran Bukit Pelangi, Sangatta, Kutai Timur, Kaltim</p>
                        <p class="flex items-center gap-2"><i class="fa-solid fa-phone text-emerald-500"></i> (0549) 21xxx / Call Center Diskop & UMKM Kutim</p>
                    </div>
                </div>

                <!-- Navigasi Cepat -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Beranda</a></li>
                        <li><a href="{{ route('umkm.index') }}" class="hover:text-emerald-400 transition">Katalog Direktori</a></li>
                        <li><a href="{{ route('home') }}#seksi-peta" class="hover:text-emerald-400 transition">Peta Geografis</a></li>
                        <li><a href="{{ route('berita.index') }}" class="hover:text-emerald-400 transition">Kabar UMKM & Pelatihan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-amber-400 transition font-semibold">Daftar / Klaim Usaha</a></li>
                    </ul>
                </div>

                <!-- Cakupan Wilayah (18 Kecamatan) -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Cakupan 18 Kecamatan</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $kecamatans = ['Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan', 'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal', 'Muara Ancalong', 'Busang', 'Telen', 'Sandaran', 'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan', 'Batu Ampar', 'Long Mesangat'];
                        @endphp
                        @foreach($kecamatans as $kec)
                            <a href="{{ route('umkm.index', ['kecamatan' => $kec]) }}" class="px-2.5 py-1 text-xs rounded-md bg-slate-800 hover:bg-emerald-900/60 hover:text-emerald-300 text-slate-400 transition">
                                {{ $kec }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-800/80 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Dinas Koperasi, Usaha Kecil dan Menengah Kabupaten Kutai Timur. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span>Versi MVP 1.0 (Fase 1)</span>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-slate-400 transition">Akses Staf & Administrator</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Leaflet & Clustering JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    @stack('scripts')
</body>
</html>
