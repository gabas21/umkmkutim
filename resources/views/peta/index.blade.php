@extends('layouts.app')

@section('title', 'Peta Sebaran Spasial UMKM — Kabupaten Kutai Timur')

@push('styles')
<!-- MapLibre GL JS untuk OpenFreeMap vector tiles -->
<link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" />
<style>
    /* Full viewport map split layout */
    .map-container-layout {
        height: calc(100vh - 64px);
        min-height: 580px;
    }
    .leaflet-marker-icon.custom-map-pin {
        transition: transform 0.15s ease;
    }
    .leaflet-marker-icon.custom-map-pin:hover {
        transform: scale(1.2);
    }
    .sidebar-card-active {
        background-color: #ecfdf5;
        border-color: #10b981;
    }

    /* Modern Glassmorphic Cluster Circles */
    .cluster-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        text-align: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        cursor: pointer;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        border: 3px solid rgba(255, 255, 255, 0.95);
        user-select: none;
    }
    .cluster-badge:hover {
        transform: scale(1.18);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.45);
        z-index: 1000 !important;
    }
    .cluster-tier-huge {
        background: radial-gradient(circle at 35% 35%, #059669 0%, #064e3b 80%, #022c22 100%);
        border-color: #6ee7b7;
        box-shadow: 0 0 20px rgba(52, 211, 153, 0.5);
    }
    .cluster-tier-large {
        background: radial-gradient(circle at 35% 35%, #10b981 0%, #047857 80%, #064e3b 100%);
        border-color: #a7f3d0;
    }
    .cluster-tier-medium {
        background: radial-gradient(circle at 35% 35%, #0d9488 0%, #0f766e 80%, #134e4a 100%);
        border-color: #99f6e4;
    }
    .cluster-tier-small {
        background: radial-gradient(circle at 35% 35%, #0284c7 0%, #0369a1 80%, #075985 100%);
        border-color: #bae6fd;
    }
    .cluster-tier-micro {
        background: radial-gradient(circle at 35% 35%, #475569 0%, #334155 80%, #1e293b 100%);
        border-color: #cbd5e1;
    }

    /* Kecamatan boundary tooltip */
    .kec-tooltip {
        background: rgba(15, 23, 42, 0.92);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px;
        color: #f1f5f9;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 10px;
        white-space: nowrap;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        pointer-events: none;
    }
    .kec-tooltip::before { display: none; }

    /* Kecamatan boundary permanent label */
    .kec-label-permanent {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 800;
        color: #0f172a;
        text-shadow: 0 0 4px #ffffff, 0 0 8px #ffffff, 0 0 12px #ffffff;
        white-space: nowrap;
        pointer-events: none;
        text-align: center;
        letter-spacing: 0.02em;
    }

    /* Toggle batas kecamatan aktif */
    .btn-boundary-active {
        background-color: #0284c7 !important;
        color: white !important;
        border-color: #0369a1 !important;
    }

    /* Legend panel kecamatan */
    #kec-legend-panel {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .kec-legend-item {
        cursor: pointer;
        transition: background 0.15s;
    }
    .kec-legend-item:hover {
        background: #f8fafc;
    }
    .kec-legend-item.kec-active {
        background: #f0f9ff;
        border-color: #0284c7 !important;
    }
</style>
@endpush

@section('content')
<main class="bg-gradient-to-r from-slate-900 to-slate-800 text-white border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500 text-slate-950 uppercase tracking-wider">GIS SPASIAL</span>
                <h1 class="text-lg sm:text-xl font-extrabold text-white tracking-tight">Peta Sebaran Pelaku Usaha</h1>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-700/80 border border-slate-600 text-emerald-400 text-xs font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ number_format($totalUmkm) }} UMKM Aktif
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">Peta interaktif {{ $totalUmkm }} titik usaha di 18 kecamatan Kutai Timur</p>
        </div>

        <!-- Action Control Buttons -->
        <div class="flex items-center gap-2">
            <!-- Loading Indicator -->
            <div id="map-loading" class="hidden px-3 py-1.5 rounded-xl bg-slate-800/90 border border-slate-700 text-emerald-400 text-xs font-semibold items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                <span>Memuat...</span>
            </div>

            <!-- GPS Nearby Button -->
            <button id="btn-gps-nearby" class="px-3 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 border border-slate-600 text-emerald-400 hover:text-emerald-300 text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-crosshairs"></i>
                <span class="hidden sm:inline">Di Sekitar Saya</span>
            </button>

            <!-- Toggle Heatmap / Clusters -->
            <button id="btn-toggle-heat" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-fire"></i>
                <span id="text-heat-toggle">Heatmap</span>
            </button>

            <!-- Toggle Batas Kecamatan -->
            <button id="btn-toggle-boundary" class="px-3 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 border border-slate-600 text-slate-300 hover:text-white text-xs font-bold transition flex items-center gap-1.5" title="Tampilkan/Sembunyikan Batas Kecamatan">
                <i class="fa-solid fa-draw-polygon"></i>
                <span class="hidden sm:inline">Batas Kec.</span>
            </button>

            <!-- Reset View -->
            <button id="btn-reset-view" class="px-3 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 border border-slate-600 text-slate-300 hover:text-white text-xs font-bold transition flex items-center gap-1.5" title="Reset ke tampilan awal">
                <i class="fa-solid fa-house-chimney"></i>
            </button>
        </div>
    </div>
</main>

<!-- Main Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-6">
    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl p-3.5 border border-slate-200 shadow-sm mb-3">
        <form id="filter-form" onsubmit="event.preventDefault(); triggerViewportUpdate();" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 text-xs">
            <!-- Search Keyword -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-[9px]"></i>
                <input type="text" id="filter-q" value="{{ request('q') }}" placeholder="Cari nama usaha atau komoditas..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 font-medium text-xs">
            </div>

            <!-- Filter Kecamatan with Auto-Pan -->
            <div class="relative">
                <i class="fa-solid fa-map-location-dot text-slate-400 absolute left-3 top-[9px]"></i>
                <select id="filter-kecamatan" class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700 font-medium text-xs">
                    <option value="">Semua Kecamatan (18)</option>
                    @foreach($daftarKecamatan as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-[9px] text-[10px] pointer-events-none"></i>
            </div>

            <!-- Filter Kategori -->
            <div class="relative">
                <i class="fa-solid fa-shapes text-slate-400 absolute left-3 top-[9px]"></i>
                <select id="filter-kategori" class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700 font-medium text-xs">
                    <option value="">Semua Sektor Usaha</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }} ({{ $kat->umkm_count }})</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-[9px] text-[10px] pointer-events-none"></i>
            </div>

            <!-- Filter Status & Buttons -->
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-certificate text-slate-400 absolute left-3 top-[9px]"></i>
                    <select id="filter-status" class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700 font-medium text-xs">
                        <option value="">Semua Legalitas</option>
                        <option value="terverifikasi" {{ request('status_klaim') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi Diskop</option>
                        <option value="menunggu_verifikasi" {{ request('status_klaim') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="belum_diklaim" {{ request('status_klaim') == 'belum_diklaim' ? 'selected' : '' }}>Data Lapangan</option>
                    </select>
                    <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-[9px] text-[10px] pointer-events-none"></i>
                </div>
                <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center justify-center">
                    <i class="fa-solid fa-filter"></i>
                </button>
                <button type="button" onclick="resetFilters()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition flex items-center justify-center" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Quick-Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
        <div class="bg-white rounded-xl px-3 py-2 border border-slate-200 flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <div class="text-xs font-black text-slate-900">{{ number_format($totalUmkm) }}</div>
                <div class="text-[10px] text-slate-500">Total UMKM</div>
            </div>
        </div>
        <div class="bg-white rounded-xl px-3 py-2 border border-slate-200 flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <div class="text-xs font-black text-slate-900">{{ number_format($totalTerverifikasi) }}</div>
                <div class="text-[10px] text-slate-500">Terverifikasi</div>
            </div>
        </div>
        <div class="bg-white rounded-xl px-3 py-2 border border-slate-200 flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <div class="text-xs font-black text-slate-900">18</div>
                <div class="text-[10px] text-slate-500">Kecamatan</div>
            </div>
        </div>
        <div class="bg-white rounded-xl px-3 py-2 border border-slate-200 flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-shapes"></i>
            </div>
            <div>
                <div class="text-xs font-black text-slate-900" id="stat-visible">—</div>
                <div class="text-[10px] text-slate-500">Tampil di Peta</div>
            </div>
        </div>
    </div>

    <!-- Map & Sidebar Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 map-container-layout">
        <!-- Sidebar List (4 cols) -->
        <div class="lg:col-span-4 h-full bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-slate-800" id="sidebar-title">Daftar Titik Usaha</span>
                    <p class="text-[10px] text-slate-400 mt-0" id="sidebar-subtitle">Area peta saat ini</p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="sidebar-count" class="px-2.5 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800 text-xs">0 titik</span>
                </div>
            </div>

            <!-- Dynamic Legend -->
            <div id="sidebar-legend" class="px-3 py-2 bg-white border-b border-slate-100 flex items-center gap-4 text-[10px] text-slate-500">
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-emerald-600 border-2 border-white shadow-sm"></div>
                    <span>Terverifikasi</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-amber-500 border-2 border-white shadow-sm"></div>
                    <span>Data Lapangan</span>
                </div>
                <div class="ml-auto flex items-center gap-1 text-slate-400">
                    <i class="fa-solid fa-hand-pointer text-[9px]"></i>
                    <span>Klik untuk zoom</span>
                </div>
            </div>

            <!-- Scrollable list of cards -->
            <div class="flex-grow overflow-y-auto p-2 space-y-1.5" id="sidebar-umkm-list" style="scroll-behavior: smooth;">
                <div class="p-8 text-center text-xs text-slate-400 space-y-3">
                    <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fa-solid fa-spinner fa-spin text-xl text-emerald-500"></i>
                    </div>
                    <p>Memuat titik sebaran peta...</p>
                </div>
            </div>

            <!-- Legenda Kecamatan (collapsible) -->
            <div class="border-t border-slate-200">
                <button id="btn-toggle-legend"
                    class="w-full px-3 py-2 bg-slate-50 flex items-center justify-between text-[10px] font-bold text-slate-700 hover:bg-slate-100 transition"
                    onclick="document.getElementById('kec-legend-panel').classList.toggle('hidden'); this.querySelector('i').classList.toggle('fa-rotate-180');">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-draw-polygon text-sky-500 text-[9px]"></i>
                        Legenda Kecamatan
                    </span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-[9px] transition-transform"></i>
                </button>
                <div id="kec-legend-panel" class="grid grid-cols-2 gap-0.5 p-2 bg-white max-h-40 overflow-y-auto">
                    <p class="col-span-2 text-[10px] text-slate-400 p-1">Memuat legenda...</p>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="px-3 py-2 bg-slate-50 border-t border-slate-200 text-[10px] text-slate-500 flex items-center justify-between">
                <span>Geser peta untuk memperbarui daftar</span>
                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-shield-halved text-[9px]"></i>
                    Diskop Kutim
                </span>
            </div>
        </div>

        <!-- Full Map Area (8 cols) -->
        <div class="lg:col-span-8 h-full rounded-2xl overflow-hidden border border-slate-200 shadow-sm relative">
            <!-- Map Attribution Overlay -->
            <div class="absolute top-3 left-10 z-[500] bg-white/95 backdrop-blur-sm rounded-xl px-3 py-1.5 shadow border border-slate-200 text-[10px] text-slate-600 font-semibold flex items-center gap-1.5 pointer-events-none">
                <i class="fa-solid fa-earth-asia text-emerald-600"></i>
                Kutai Timur, Kalimantan Timur
            </div>
            <div id="full-interactive-map" class="h-full w-full z-10"></div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- MapLibre GL JS + Leaflet plugin untuk OpenFreeMap -->
<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>
<script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.22/leaflet-maplibre-gl.js"></script>
<!-- Leaflet Heat plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    let map, clusterLayerGroup, markersCluster, heatLayer;
    let kecamatanLayer = null;      // Layer GeoJSON batas kecamatan
    let kecamatanLabelLayer = null; // Layer label nama kecamatan permanen
    let boundaryVisible = true;     // Status toggle batas kecamatan
    let kecamatanGeoData = null; // Cache data GeoJSON
    let highlightedKec = null;   // Kecamatan yang di-highlight
    let isHeatmapActive = false;
    let isLoading = false;
    let debounceTimer;
    const markerRegistry = {};
    let currentLoadedData = null;

    const kecamatanCoords = @json($kecamatanCoords);

    // Boundary Koordinat Kabupaten Kutai Timur
    const kutimBounds = L.latLngBounds(
        [-0.2, 115.8],  // Southwest
        [1.9, 119.2]    // Northeast
    );

    const totalUmkm = {{ $totalUmkm }};
    const initialCenter = [0.85, 117.30];
    const initialZoom = 8.5;

    document.addEventListener('DOMContentLoaded', function () {
        map = L.map('full-interactive-map', {

            preferCanvas: true,
            zoomControl: true,
            zoomAnimation: true,
            fadeAnimation: true,
            maxBounds: kutimBounds,
            maxBoundsViscosity: 0.85,
            minZoom: 8,
            maxZoom: 16,
            zoomSnap: 0.5,
        }).setView(initialCenter, initialZoom);

        // ============================================================
        // BASE MAP: OpenFreeMap (via MapLibre GL Leaflet)
        // ============================================================
        try {
            L.maplibreGL({
                style: 'https://tiles.openfreemap.org/styles/liberty',
                attribution: '&copy; <a href="https://openfreemap.org" target="_blank">OpenFreeMap</a> &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> | Diskop Kutai Timur',
            }).addTo(map);
        } catch (e) {
            // Fallback: CartoDB Positron jika MapLibre gagal dimuat
            console.warn('MapLibre GL Leaflet gagal, menggunakan fallback tile:', e);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://carto.com/">CARTO</a> | Diskop Kutai Timur',
                subdomains: 'abcd',
                maxZoom: 19,
            }).addTo(map);
        }

        // Layer Group untuk Kluster Lingkaran Server-Side (Zoom < 15)
        clusterLayerGroup = L.layerGroup().addTo(map);

        // Marker Cluster Layer untuk Titik Individual Detail (Zoom >= 15)
        markersCluster = L.markerClusterGroup({
            chunkedLoading: true,
            chunkInterval: 100,
            chunkDelay: 10,
            maxClusterRadius: 35,
            disableClusteringAtZoom: 18,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            animate: true,
        });
        map.addLayer(markersCluster);

        // ============================================================
        // LAYER BATAS KECAMATAN: GeoJSON dengan warna unik per kecamatan
        // ============================================================
        const KECAMATAN_COLORS = {
            'Sangatta Utara':   { fill: '#ef4444', stroke: '#dc2626' },
            'Sangatta Selatan': { fill: '#f97316', stroke: '#ea580c' },
            'Rantau Pulung':    { fill: '#eab308', stroke: '#ca8a04' },
            'Bengalon':         { fill: '#84cc16', stroke: '#65a30d' },
            'Kaliorang':        { fill: '#10b981', stroke: '#059669' },
            'Sangkulirang':     { fill: '#06b6d4', stroke: '#0891b2' },
            'Sandaran':         { fill: '#3b82f6', stroke: '#2563eb' },
            'Karangan':         { fill: '#8b5cf6', stroke: '#7c3aed' },
            'Kaubun':           { fill: '#ec4899', stroke: '#db2777' },
            'Busang':           { fill: '#f43f5e', stroke: '#e11d48' },
            'Long Mesangat':    { fill: '#14b8a6', stroke: '#0d9488' },
            'Muara Bengkal':    { fill: '#a855f7', stroke: '#9333ea' },
            'Muara Wahau':      { fill: '#f59e0b', stroke: '#d97706' },
            'Telen':            { fill: '#22c55e', stroke: '#16a34a' },
            'Kongbeng':         { fill: '#0ea5e9', stroke: '#0284c7' },
            'Batu Ampar':       { fill: '#6366f1', stroke: '#4f46e5' },
            'Teluk Pandan':     { fill: '#d946ef', stroke: '#c026d3' },
            'Muara Ancalong':   { fill: '#64748b', stroke: '#475569' },
        };

        const FALLBACK_PALETTE = [
            { fill: '#ef4444', stroke: '#dc2626' }, { fill: '#f97316', stroke: '#ea580c' },
            { fill: '#eab308', stroke: '#ca8a04' }, { fill: '#84cc16', stroke: '#65a30d' },
            { fill: '#10b981', stroke: '#059669' }, { fill: '#06b6d4', stroke: '#0891b2' },
            { fill: '#3b82f6', stroke: '#2563eb' }, { fill: '#8b5cf6', stroke: '#7c3aed' },
            { fill: '#ec4899', stroke: '#db2777' }, { fill: '#f43f5e', stroke: '#e11d48' },
            { fill: '#14b8a6', stroke: '#0d9488' }, { fill: '#a855f7', stroke: '#9333ea' },
            { fill: '#f59e0b', stroke: '#d97706' }, { fill: '#22c55e', stroke: '#16a34a' },
            { fill: '#0ea5e9', stroke: '#0284c7' }, { fill: '#6366f1', stroke: '#4f46e5' },
            { fill: '#d946ef', stroke: '#c026d3' }, { fill: '#64748b', stroke: '#475569' },
        ];

        function getKecColor(name) {
            if (KECAMATAN_COLORS[name]) return KECAMATAN_COLORS[name];
            // Deterministik fallback berdasarkan nama
            let hash = 0;
            for (let c of name) hash = (hash * 31 + c.charCodeAt(0)) & 0xffffffff;
            return FALLBACK_PALETTE[Math.abs(hash) % FALLBACK_PALETTE.length];
        }

        function updateKecamatanLabelVisibility() {
            if (!kecamatanLabelLayer) return;
            if (boundaryVisible && map && map.getZoom() >= 9) {
                if (!map.hasLayer(kecamatanLabelLayer)) map.addLayer(kecamatanLabelLayer);
            } else {
                if (map && map.hasLayer(kecamatanLabelLayer)) map.removeLayer(kecamatanLabelLayer);
            }
        }

        function buildKecamatanLayer(geojson) {
            if (kecamatanLayer) map.removeLayer(kecamatanLayer);
            if (kecamatanLabelLayer) map.removeLayer(kecamatanLabelLayer);
            kecamatanLabelLayer = L.layerGroup();

            kecamatanLayer = L.geoJSON(geojson, {
                style: function (feature) {
                    const col = getKecColor(feature.properties.kecamatan || feature.properties.name || '');
                    return {
                        color:       col.stroke,
                        fillColor:   col.fill,
                        weight:      3,
                        opacity:     1.0,
                        fillOpacity: 0.12,
                        dashArray:   null,
                    };
                },
                onEachFeature: function (feature, layer) {
                    const name = feature.properties.kecamatan || feature.properties.name || 'Kecamatan';
                    const col  = getKecColor(name);
                    const totalUmkm = feature.properties.total_umkm;

                    // Tooltip hover
                    layer.bindTooltip(
                        `<div style="border-left: 3px solid ${col.stroke}; padding-left:6px;">
                            <b style="color:${col.stroke};">Kec. ${name}</b>
                            ${totalUmkm ? `<div style="font-size:10px; color:#cbd5e1; font-weight:normal; margin-top:1px;">${totalUmkm} UMKM Terdata</div>` : ''}
                         </div>`,
                        { className: 'kec-tooltip', sticky: true, direction: 'top', offset: [0, -6] }
                    );

                    // Label nama permanen di tengah polygon
                    let center = null;
                    if (feature.properties && feature.properties.center) {
                        center = [feature.properties.center[1], feature.properties.center[0]];
                    } else if (layer.getBounds) {
                        center = layer.getBounds().getCenter();
                    }
                    if (center) {
                        const labelMarker = L.marker(center, {
                            icon: L.divIcon({
                                className: 'kec-label-permanent',
                                html: `<span>${name}</span>`,
                                iconSize: [120, 20],
                                iconAnchor: [60, 10],
                            }),
                            interactive: false,
                        });
                        kecamatanLabelLayer.addLayer(labelMarker);
                    }

                    layer.on({
                        mouseover: function (e) {
                            e.target.setStyle({
                                weight: 4,
                                fillOpacity: 0.28,
                                color: col.stroke,
                                dashArray: null,
                            });
                            e.target.bringToFront();
                        },
                        mouseout: function (e) {
                            if (highlightedKec !== name) {
                                kecamatanLayer.resetStyle(e.target);
                            }
                        },
                        click: function (e) {
                            // Auto-filter kecamatan di dropdown
                            const sel = document.getElementById('filter-kecamatan');
                            if (sel) {
                                for (let opt of sel.options) {
                                    if (opt.text.toLowerCase().includes(name.toLowerCase()) ||
                                        name.toLowerCase().includes(opt.text.toLowerCase())) {
                                        sel.value = opt.value;
                                        break;
                                    }
                                }
                                triggerViewportUpdate();
                            }
                            // Highlight di legenda
                            highlightedKec = name;
                            document.querySelectorAll('.kec-legend-item').forEach(el => {
                                el.classList.toggle('kec-active', el.dataset.kec === name);
                            });
                        },
                    });
                },
            });

            if (boundaryVisible) {
                kecamatanLayer.addTo(map);
                kecamatanLayer.bringToBack();
                updateKecamatanLabelVisibility();
            }
        }

        async function loadKecamatanBoundaries() {
            if (kecamatanGeoData) {
                buildKecamatanLayer(kecamatanGeoData);
                return;
            }
            try {
                const r = await fetch('/api/kutim/kecamatan-boundaries');
                if (!r.ok) throw new Error('HTTP ' + r.status);
                kecamatanGeoData = await r.json();
                buildKecamatanLayer(kecamatanGeoData);
                buildKecamatanLegend(kecamatanGeoData);
            } catch (err) {
                console.warn('Gagal memuat batas kecamatan:', err);
            }
        }

        function buildKecamatanLegend(geojson) {
            const panel = document.getElementById('kec-legend-panel');
            if (!panel) return;

            const names = [];
            (geojson.features || []).forEach(f => {
                const n = f.properties.kecamatan || f.properties.name || '';
                if (n && !names.includes(n)) names.push(n);
            });
            names.sort();

            let html = '';
            names.forEach(name => {
                const col = getKecColor(name);
                html += `
                    <div class="kec-legend-item flex items-center gap-1.5 px-2 py-1 rounded-lg border border-transparent text-[10px] font-semibold text-slate-700"
                         data-kec="${name}"
                         onclick="highlightKecamatan('${name.replace(/'/g, "\\'")}')"
                         title="Klik untuk sorot wilayah">
                        <span class="w-3 h-3 rounded-sm flex-shrink-0" style="background:${col.fill}; border: 2px solid ${col.stroke};"></span>
                        <span class="truncate">${name}</span>
                    </div>
                `;
            });

            panel.innerHTML = html || '<p class="text-[10px] text-slate-400 p-2">Memuat legenda...</p>';
        }

        window.highlightKecamatan = function(name) {
            if (!kecamatanLayer) return;
            highlightedKec = name;
            // Reset semua
            kecamatanLayer.eachLayer(l => kecamatanLayer.resetStyle(l));
            // Highlight target
            kecamatanLayer.eachLayer(function(l) {
                const n = l.feature?.properties?.kecamatan || l.feature?.properties?.name || '';
                if (n === name) {
                    const col = getKecColor(name);
                    l.setStyle({ weight: 4, fillOpacity: 0.28, color: col.stroke, dashArray: null });
                    l.bringToFront();
                }
            });
            // Update legenda highlight
            document.querySelectorAll('.kec-legend-item').forEach(el => {
                el.classList.toggle('kec-active', el.dataset.kec === name);
            });
            // Auto-filter kecamatan
            const sel = document.getElementById('filter-kecamatan');
            if (sel) {
                for (let opt of sel.options) {
                    if (opt.text.toLowerCase().includes(name.toLowerCase()) ||
                        name.toLowerCase().includes(opt.text.toLowerCase())) {
                        sel.value = opt.value;
                        break;
                    }
                }
                setTimeout(triggerViewportUpdate, 300);
            }
        };

        // Muat batas kecamatan saat inisialisasi
        loadKecamatanBoundaries();

        // Loading Indicator helper
        function setLoading(state) {
            isLoading = state;
            const el = document.getElementById('map-loading');
            if (el) {
                if (state) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            }
        }

        // Fetch Data Kluster / Viewport dari Server
        async function muatDataViewport() {
            if (isLoading) return;
            setLoading(true);

            const b = map.getBounds();
            const sw = b.getSouthWest();
            const ne = b.getNorthEast();
            const zoom = map.getZoom();

            // Ambil nilai filter toolbar
            const q = document.getElementById('filter-q').value;
            const kecamatan = document.getElementById('filter-kecamatan').value;
            const kategori = document.getElementById('filter-kategori').value;
            const statusKlaim = document.getElementById('filter-status').value;

            const params = new URLSearchParams({
                sw_lat: sw.lat,
                sw_lng: sw.lng,
                ne_lat: ne.lat,
                ne_lng: ne.lng,
                zoom: zoom
            });

            if (q) params.append('q', q);
            if (kecamatan) params.append('kecamatan', kecamatan);
            if (kategori) params.append('kategori', kategori);
            if (statusKlaim) params.append('status_klaim', statusKlaim);

            try {
                const response = await fetch(`/api/umkm/clusters?${params.toString()}`);
                if (!response.ok) throw new Error('Network error');
                const result = await response.json();

                currentLoadedData = result;
                renderMapAndSidebar(result);
            } catch (err) {
                console.error('Gagal memuat data kluster peta:', err);
            } finally {
                setLoading(false);
            }
        }

        window.triggerViewportUpdate = function () {
            clearTimeout(debounceTimer);
            muatDataViewport();
        };

        // Render data kluster atau titik individual ke peta dan sidebar
        function renderMapAndSidebar(res) {
            clusterLayerGroup.clearLayers();
            markersCluster.clearLayers();
            for (const key in markerRegistry) delete markerRegistry[key];

            const heatPoints = [];
            const sidebarContainer = document.getElementById('sidebar-umkm-list');
            const countBadge = document.getElementById('sidebar-count');
            const sidebarTitle = document.getElementById('sidebar-title');
            const sidebarSubtitle = document.getElementById('sidebar-subtitle');
            const sidebarLegend = document.getElementById('sidebar-legend');
            const statVisible = document.getElementById('stat-visible');

            if (statVisible) {
                statVisible.textContent = (res.total_count ?? 0).toLocaleString('id-ID');
            }

            if (!res.data || res.data.length === 0) {
                countBadge.textContent = '0 data';
                sidebarContainer.innerHTML = `
                    <div class="p-8 text-center text-xs text-slate-400 space-y-2">
                        <i class="fa-solid fa-map-location-dot text-2xl text-slate-300"></i>
                        <p>Tidak ada UMKM pada area / filter ini.</p>
                    </div>
                `;
                return;
            }

            // ==============================================================
            // MODE 1: KLUSTER LINGKARAN (Zoom < 15)
            // ==============================================================
            if (res.mode === 'clusters') {
                if (sidebarTitle) sidebarTitle.textContent = 'Daftar Kluster Wilayah';
                if (sidebarSubtitle) sidebarSubtitle.textContent = 'Klik kluster atau tombol untuk perbesar';
                if (countBadge) countBadge.textContent = `${res.cluster_count} kluster (${(res.total_count ?? 0).toLocaleString('id-ID')} UMKM)`;

                if (sidebarLegend) {
                    sidebarLegend.innerHTML = `
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-emerald-600 border border-white shadow-sm"></div>
                            <span>Kluster Padat</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-teal-500 border border-white shadow-sm"></div>
                            <span>Kluster Sedang</span>
                        </div>
                        <div class="ml-auto flex items-center gap-1 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass-plus text-[9px]"></i>
                            <span>Klik untuk zoom in</span>
                        </div>
                    `;
                }

                let sidebarHtml = '';

                // Sort ascending so larger clusters render last (on top) via z-index
                const sortedClusters = [...res.data].sort((a, b) => a.count - b.count);
                // Track placed badges in pixel space for collision avoidance
                const placedBadges = [];

                sortedClusters.forEach(function (c, idx) {
                    if (!c.lat || !c.lng) return;

                    // Tentukan ukuran dan tier styling berdasarkan jumlah UMKM
                    let tier = 'cluster-tier-micro';
                    let size = 34;
                    let fontSize = 11;

                    if (c.count >= 5000) {
                        tier = 'cluster-tier-huge';
                        size = 64;
                        fontSize = 15;
                    } else if (c.count >= 1000) {
                        tier = 'cluster-tier-large';
                        size = 52;
                        fontSize = 13;
                    } else if (c.count >= 200) {
                        tier = 'cluster-tier-medium';
                        size = 44;
                        fontSize = 12;
                    } else if (c.count >= 50) {
                        tier = 'cluster-tier-small';
                        size = 38;
                        fontSize = 11;
                    }

                    // z-index: larger clusters on top
                    const zIndex = 100 + idx;

                    // Weight untuk heatmap
                    const heatWeight = Math.min(Math.max(c.count / 2000, 0.4), 1.0);
                    heatPoints.push([c.lat, c.lng, heatWeight]);

                    // Posisikan badge tepat pada koordinat geografis kluster/wilayah yang sebenarnya
                    const renderLat = c.lat;
                    const renderLng = c.lng;


                    // Format kecamatan label: "Kec. X & Y" style
                    const kecLabel = c.kecamatan.includes(',')
                        ? c.kecamatan.split(',').map(s => s.trim()).join(' & ')
                        : c.kecamatan;

                    const clusterIcon = L.divIcon({
                        className: 'custom-cluster-badge-icon',
                        html: `<div class="cluster-badge ${tier}" style="width: ${size}px; height: ${size}px; z-index: ${zIndex};">
                            <span style="font-size: ${fontSize}px; font-weight: 800; line-height: 1;">${c.label}</span>
                            <span style="font-size: 8px; font-weight: 700; opacity: 0.9; text-transform: uppercase; margin-top: 1px;">UMKM</span>
                        </div>`,
                        iconSize: [size, size],
                        iconAnchor: [size / 2, size / 2],
                        popupAnchor: [0, -(size / 2)]
                    });

                    const marker = L.marker([renderLat, renderLng], { icon: clusterIcon, zIndexOffset: zIndex });

                    const popupContent = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 220px; padding: 4px;">
                            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #059669; margin-bottom: 2px;">
                                <i class="fa-solid fa-shapes"></i> Kluster UMKM
                            </div>
                            <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 4px; line-height: 1.2;">
                                Kec. ${kecLabel}
                            </div>
                            <div style="font-size: 11px; color: #475569; margin-bottom: 6px; line-height: 1.4;">
                                Total: <b style="color: #047857;">${c.count.toLocaleString('id-ID')} UMKM</b><br>
                                Legalitas: <b>${c.terverifikasi_count.toLocaleString('id-ID')}</b> Terverifikasi
                            </div>
                            <button onclick="zoomToCluster(${c.lat}, ${c.lng})" style="width: 100%; border: none; cursor: pointer; text-align: center; background: #059669; color: white; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                Perbesar Wilayah Ini &rarr;
                            </button>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    marker.on('click', function () {
                        map.flyTo([c.lat, c.lng], Math.min(map.getZoom() + 2, 16), { duration: 0.8 });
                    });

                    clusterLayerGroup.addLayer(marker);

                    // Sidebar cluster card (keep original sort: descending by count)
                    const percentVerif = c.count > 0 ? Math.round((c.terverifikasi_count / c.count) * 100) : 100;
                    sidebarHtml += `
                        <div class="cursor-pointer group hover:bg-slate-50 p-3 rounded-2xl transition border border-slate-100 hover:border-emerald-300 hover:shadow-sm"
                             onclick="map.flyTo([${c.lat}, ${c.lng}], Math.min(map.getZoom() + 2, 16), { duration: 0.8 })"
                             data-count="${c.count}">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <span class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-700 transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i> ${kecLabel}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 whitespace-nowrap">
                                    ${c.count.toLocaleString('id-ID')} UMKM
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2">
                                <span class="text-emerald-600 font-semibold flex items-center gap-1 text-[10px]">
                                    <i class="fa-solid fa-shield-halved"></i> ${c.terverifikasi_count.toLocaleString('id-ID')} Terverifikasi (${percentVerif}%)
                                </span>
                                <span class="text-emerald-700 font-bold group-hover:translate-x-0.5 transition inline-flex items-center gap-1 text-[10px]">
                                    Perbesar <i class="fa-solid fa-arrow-right text-[8px]"></i>
                                </span>
                            </div>
                        </div>
                    `;
                });

                // Re-sort sidebar HTML: largest clusters first
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = sidebarHtml;
                const cards = Array.from(tempDiv.children);
                cards.sort((a, b) => parseInt(b.dataset.count || 0) - parseInt(a.dataset.count || 0));
                sidebarHtml = cards.map(el => el.outerHTML).join('');

                sidebarContainer.innerHTML = sidebarHtml;
            }

            // ==============================================================
            // MODE 2: TITIK DETAIL INDIVIDUAL (Zoom >= 15)
            // ==============================================================
            else if (res.mode === 'points') {
                if (sidebarTitle) sidebarTitle.textContent = 'Daftar Titik Usaha';
                if (sidebarSubtitle) sidebarSubtitle.textContent = 'Titik usaha pada batas pandang saat ini';
                if (countBadge) countBadge.textContent = `${res.total_count} titik`;

                if (sidebarLegend) {
                    sidebarLegend.innerHTML = `
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-emerald-600 border-2 border-white shadow-sm"></div>
                            <span>Terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-amber-500 border-2 border-white shadow-sm"></div>
                            <span>Data Lapangan</span>
                        </div>
                        <div class="ml-auto flex items-center gap-1 text-slate-400">
                            <i class="fa-solid fa-hand-pointer text-[9px]"></i>
                            <span>Klik untuk detail</span>
                        </div>
                    `;
                }

                let sidebarHtml = '';

                res.data.forEach(function (item) {
                    if (!item.lat || !item.lng) return;

                    heatPoints.push([item.lat, item.lng, 0.8]);

                    const isVerified = item.status_klaim === 'terverifikasi';
                    const markerColor = isVerified ? '#059669' : '#d97706';

                    const customIcon = L.divIcon({
                        className: 'custom-map-pin',
                        html: `<div style="background-color: ${markerColor}; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px;">
                            <i class="fa-solid fa-store"></i>
                        </div>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 14],
                        popupAnchor: [0, -14]
                    });

                    const marker = L.marker([item.lat, item.lng], { icon: customIcon });

                    const popupContent = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 220px; padding: 4px;">
                            <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #059669; margin-bottom: 2px;">
                                ${item.kategori} &bull; ${item.kecamatan}
                            </div>
                            <div style="font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 4px;">
                                ${item.nama}
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">
                                ${item.alamat || 'Kutai Timur'}
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #d97706; font-weight: 600; margin-bottom: 8px;">
                                <i class="fa-solid fa-star"></i> ${item.rating}
                                <span style="font-size: 10px; color: #64748b; font-weight: 400;">(${isVerified ? 'Terverifikasi' : 'Data Lapangan'})</span>
                            </div>
                            <a href="${item.url}" style="display: block; text-align: center; background: #059669; color: white; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none;">
                                Lihat Profil Usaha &rarr;
                            </a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    markersCluster.addLayer(marker);
                    markerRegistry[item.id] = marker;

                    // Sidebar card HTML
                    sidebarHtml += `
                        <div class="cursor-pointer group hover:bg-slate-50 p-3 rounded-2xl transition border border-slate-100 hover:border-slate-200"
                             onclick="panToMarker(${item.lat}, ${item.lng}, ${item.id})">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                    ${item.kategori}
                                </span>
                                ${isVerified ? '<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800"><i class="fa-solid fa-circle-check"></i></span>' : ''}
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition leading-snug">
                                ${item.nama}
                            </h4>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1.5">
                                <span><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>Kec. ${item.kecamatan}</span>
                                <span class="text-amber-600 font-semibold"><i class="fa-solid fa-star text-[10px] mr-0.5"></i>${item.rating}</span>
                            </div>
                        </div>
                    `;
                });

                sidebarContainer.innerHTML = sidebarHtml;
            }

            // Re-bind heat layer jika sedang aktif
            if (heatLayer) map.removeLayer(heatLayer);
            heatLayer = L.heatLayer(heatPoints, {
                radius: 35,
                blur: 25,
                maxZoom: 16,
                minOpacity: 0.4,
                gradient: { 0.1: '#064e3b', 0.3: '#047857', 0.5: '#f59e0b', 0.75: '#ef4444', 1.0: '#7f1d1d' }
            });
            if (isHeatmapActive) map.addLayer(heatLayer);
        }

        // Global zoom helper for popup button
        window.zoomToCluster = function (lat, lng) {
            map.flyTo([lat, lng], Math.min(map.getZoom() + 2, 16), { duration: 0.8 });
        };

        // Debounce moveend (350ms)
        map.on('moveend', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(muatDataViewport, 350);
        });
        map.on('zoomend', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(muatDataViewport, 350);
            updateKecamatanLabelVisibility();
        });

        // Load data pertama kali
        muatDataViewport();

        // Toggle Heatmap Button
        const toggleHeatBtn = document.getElementById('btn-toggle-heat');
        const toggleHeatText = document.getElementById('text-heat-toggle');

        toggleHeatBtn.addEventListener('click', function () {
            if (!isHeatmapActive) {
                map.removeLayer(clusterLayerGroup);
                map.removeLayer(markersCluster);
                if (heatLayer) map.addLayer(heatLayer);
                toggleHeatText.textContent = 'Mode Pin Cluster';
                toggleHeatBtn.classList.remove('bg-emerald-600');
                toggleHeatBtn.classList.add('bg-amber-600');
                isHeatmapActive = true;
            } else {
                if (heatLayer) map.removeLayer(heatLayer);
                map.addLayer(clusterLayerGroup);
                map.addLayer(markersCluster);
                toggleHeatText.textContent = 'Mode Heatmap';
                toggleHeatBtn.classList.remove('bg-amber-600');
                toggleHeatBtn.classList.add('bg-emerald-600');
                isHeatmapActive = false;
            }
        });

        // Toggle Batas Kecamatan
        const boundaryBtn = document.getElementById('btn-toggle-boundary');
        if (boundaryBtn) {
            boundaryBtn.addEventListener('click', function () {
                boundaryVisible = !boundaryVisible;
                if (boundaryVisible) {
                    if (kecamatanLayer) {
                        kecamatanLayer.addTo(map);
                        kecamatanLayer.bringToBack();
                    }
                    updateKecamatanLabelVisibility();
                    boundaryBtn.classList.add('btn-boundary-active');
                    boundaryBtn.querySelector('span')?.textContent && (boundaryBtn.querySelector('span').textContent = 'Sembunyikan');
                } else {
                    if (kecamatanLayer) map.removeLayer(kecamatanLayer);
                    if (kecamatanLabelLayer) map.removeLayer(kecamatanLabelLayer);
                    boundaryBtn.classList.remove('btn-boundary-active');
                    boundaryBtn.querySelector('span')?.textContent && (boundaryBtn.querySelector('span').textContent = 'Batas Kec.');
                }
            });
            // Default: aktif
            boundaryBtn.classList.add('btn-boundary-active');
        }

        // GPS Geolocation Handler
        const gpsBtn = document.getElementById('btn-gps-nearby');
        gpsBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung geolokasi GPS.');
                return;
            }

            gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    const userIcon = L.divIcon({
                        html: '<div style="background: #2563eb; width: 22px; height: 22px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(37,99,235,0.8);"></div>',
                        iconSize: [22, 22],
                        iconAnchor: [11, 11]
                    });

                    L.marker([userLat, userLng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('<b>Lokasi Anda Saat Ini</b>')
                        .openPopup();

                    map.setView([userLat, userLng], 15);
                    gpsBtn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i> Lokasi Ditemukan';
                },
                function (error) {
                    alert('Gagal mendeteksi lokasi: ' + error.message);
                    gpsBtn.innerHTML = '<i class="fa-solid fa-crosshairs"></i> Di Sekitar Saya';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });

        // Auto-pan saat dropdown kecamatan dipilih
        const selectKecamatan = document.getElementById('filter-kecamatan');
        selectKecamatan.addEventListener('change', function () {
            const selected = this.value;
            if (selected && kecamatanCoords[selected]) {
                const [lat, lng, zoom] = kecamatanCoords[selected];
                map.flyTo([lat, lng], zoom, { duration: 1.0, easeLinearity: 0.5 });
            } else {
                map.flyTo(initialCenter, initialZoom, { duration: 1.0 });
            }
            setTimeout(triggerViewportUpdate, 1200);
        });

        // Reset view button
        document.getElementById('btn-reset-view').addEventListener('click', function () {
            map.flyTo(initialCenter, initialZoom, { duration: 0.8 });
            setTimeout(triggerViewportUpdate, 1000);
        });

        // Filter event listeners
        document.getElementById('filter-kategori').addEventListener('change', triggerViewportUpdate);
        document.getElementById('filter-status').addEventListener('change', triggerViewportUpdate);
    });

    // Helper fungsi klik sidebar berpindah ke marker
    window.panToMarker = function (lat, lng, id) {
        if (isHeatmapActive) {
            map.flyTo([lat, lng], 16);
            return;
        }

        const marker = markerRegistry[id];
        if (marker) {
            markersCluster.zoomToShowLayer(marker, function () {
                marker.openPopup();
            });
        } else {
            map.flyTo([lat, lng], 16);
        }
    };

    // Helper reset filter
    window.resetFilters = function () {
        document.getElementById('filter-q').value = '';
        document.getElementById('filter-kecamatan').value = '';
        document.getElementById('filter-kategori').value = '';
        document.getElementById('filter-status').value = '';
        map.flyTo(initialCenter, initialZoom, { duration: 0.8 });
        setTimeout(triggerViewportUpdate, 900);
    };

</script>
@endpush

