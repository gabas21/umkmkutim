@extends('layouts.app')

@section('title', 'Peta Sebaran Spasial UMKM — Kabupaten Kutai Timur')

@push('styles')
<style>
    /* Full viewport map split layout */
    .map-container-layout {
        height: calc(100vh - 120px);
        min-height: 600px;
    }
</style>
@endpush

@section('content')
<div class="bg-slate-900 text-white px-4 sm:px-6 lg:px-8 py-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white">GIS SPASIAL</span>
                <h1 class="text-xl font-extrabold text-white">Peta Sebaran UMKM Kutai Timur</h1>
            </div>
            <p class="text-xs text-slate-400 mt-0.5">Eksplorasi geografis titik lokasi pelaku usaha dengan teknologi Marker Clustering & Heatmap</p>
        </div>

        <!-- Action Control Buttons -->
        <div class="flex items-center gap-2">
            <!-- GPS Nearby Button -->
            <button id="btn-gps-nearby" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-emerald-400 hover:text-emerald-300 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-crosshairs"></i>
                <span>Di Sekitar Saya</span>
            </button>

            <!-- Toggle Heatmap / Clusters -->
            <button id="btn-toggle-heat" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-fire"></i>
                <span id="text-heat-toggle">Mode Heatmap</span>
            </button>
        </div>
    </div>
</div>

<!-- Main Split Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-4">
        <form action="{{ route('peta.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <!-- Search Keyword -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Filter Kecamatan -->
            <div class="relative">
                <select name="kecamatan" id="select-kecamatan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700">
                    <option value="">Semua Kecamatan (18)</option>
                    @foreach($daftarKecamatan as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-3 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Filter Kategori -->
            <div class="relative">
                <select name="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }} ({{ $kat->umkm_count }})</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-3 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Filter Status & Submit -->
            <div class="flex gap-2">
                <select name="status_klaim" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="terverifikasi" {{ request('status_klaim') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="belum_diklaim" {{ request('status_klaim') == 'belum_diklaim' ? 'selected' : '' }}>Belum Diklaim</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center justify-center">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['q', 'kecamatan', 'kategori', 'status_klaim']))
                    <a href="{{ route('peta.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition flex items-center justify-center" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Map & Sidebar Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 map-container-layout">
        <!-- Sidebar List (4 cols) -->
        <div class="lg:col-span-4 h-full bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">Daftar Titik Usaha</span>
                <span class="px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[11px]">{{ $umkmItems->count() }} titik</span>
            </div>

            <!-- Scrollable list of cards -->
            <div class="flex-grow overflow-y-auto p-3 space-y-2.5 divide-y divide-slate-100" id="sidebar-umkm-list">
                @forelse($umkmItems as $item)
                    <div class="pt-2.5 first:pt-0 cursor-pointer group hover:bg-slate-50 p-2.5 rounded-xl transition border border-transparent hover:border-slate-200"
                         onclick="panToMarker({{ $item->latitude }}, {{ $item->longitude }}, {{ $item->id }})">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                {{ $item->kategori?->nama }}
                            </span>
                            @if($item->status_klaim === 'terverifikasi')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            @endif
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-600 transition leading-snug">
                            {{ $item->nama_usaha }}
                        </h4>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                            <span><i class="fa-solid fa-location-dot text-emerald-600"></i> Kec. {{ $item->kecamatan }}</span>
                            <span class="text-amber-500 font-semibold"><i class="fa-solid fa-star text-[10px]"></i> {{ number_format($item->rating, 1) }}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-1">
                            {{ $item->alamat }}
                        </p>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400 space-y-2">
                        <i class="fa-solid fa-map-location-dot text-2xl text-slate-300"></i>
                        <p>Tidak ada UMKM yang sesuai dengan filter.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Full Map Area (8 cols) -->
        <div class="lg:col-span-8 h-full rounded-2xl overflow-hidden border border-slate-200 shadow-sm relative">
            <div id="full-interactive-map" class="h-full w-full z-10"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet Heat plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    let map, markersCluster, heatLayer;
    let isHeatmapActive = false;
    const markerRegistry = {};

    const points = @json($mapData);
    const kecamatanCoords = @json($kecamatanCoords);

    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Map (Kutai Timur center)
        const initialCenter = [0.55, 117.50];
        const initialZoom = 9;

        map = L.map('full-interactive-map', {
            zoomControl: true
        }).setView(initialCenter, initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap | Diskop & UMKM Kutai Timur',
            maxZoom: 18
        }).addTo(map);

        // Marker Cluster Layer
        markersCluster = L.markerClusterGroup({
            maxClusterRadius: 40,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        // Heatmap data array [lat, lng, intensity]
        const heatPoints = [];

        points.forEach(function (item) {
            if (!item.lat || !item.lng) return;

            heatPoints.push([item.lat, item.lng, 0.8]);

            const isVerified = item.status_klaim === 'terverifikasi';
            const markerColor = isVerified ? '#059669' : '#d97706';

            const customIcon = L.divIcon({
                className: 'custom-map-pin',
                html: `<div style="background-color: ${markerColor}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px;">
                    <i class="fa-solid fa-store"></i>
                </div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15]
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
                        ${item.alamat}
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #d97706; font-weight: 600; margin-bottom: 8px;">
                        <i class="fa-solid fa-star"></i> ${item.rating}
                        <span style="font-size: 10px; color: #64748b; font-weight: 400;">(${isVerified ? 'Terverifikasi' : 'Data Dinas'})</span>
                    </div>
                    <a href="${item.url}" style="display: block; text-align: center; background: #059669; color: white; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none;">
                        Lihat Profil Usaha &rarr;
                    </a>
                </div>
            `;

            marker.bindPopup(popupContent);
            markersCluster.addLayer(marker);
            markerRegistry[item.id] = marker;
        });

        map.addLayer(markersCluster);

        // Siapkan Heat Layer (dimatikan default)
        heatLayer = L.heatLayer(heatPoints, {
            radius: 30,
            blur: 20,
            maxZoom: 14,
            gradient: { 0.2: '#047857', 0.5: '#f59e0b', 0.8: '#dc2626', 1.0: '#7f1d1d' }
        });

        // Toggle Heatmap Button
        const toggleHeatBtn = document.getElementById('btn-toggle-heat');
        const toggleHeatText = document.getElementById('text-heat-toggle');

        toggleHeatBtn.addEventListener('click', function () {
            if (!isHeatmapActive) {
                map.removeLayer(markersCluster);
                map.addLayer(heatLayer);
                toggleHeatText.textContent = 'Mode Pin Cluster';
                toggleHeatBtn.classList.remove('bg-emerald-600');
                toggleHeatBtn.classList.add('bg-amber-600');
                isHeatmapActive = true;
            } else {
                map.removeLayer(heatLayer);
                map.addLayer(markersCluster);
                toggleHeatText.textContent = 'Mode Heatmap';
                toggleHeatBtn.classList.remove('bg-amber-600');
                toggleHeatBtn.classList.add('bg-emerald-600');
                isHeatmapActive = false;
            }
        });

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

                    // Buat marker lokasi pengguna
                    const userIcon = L.divIcon({
                        html: '<div style="background: #2563eb; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(37,99,235,0.8);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    L.marker([userLat, userLng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('<b>Lokasi Anda Saat Ini</b>')
                        .openPopup();

                    map.setView([userLat, userLng], 14);

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
        const selectKecamatan = document.getElementById('select-kecamatan');
        selectKecamatan.addEventListener('change', function () {
            const selected = this.value;
            if (kecamatanCoords[selected]) {
                const [lat, lng, zoom] = kecamatanCoords[selected];
                map.flyTo([lat, lng], zoom, { duration: 1.5 });
            }
        });

        // Jika ada filter kecamatan aktif di URL
        const currentKecamatan = "{{ request('kecamatan') }}";
        if (currentKecamatan && kecamatanCoords[currentKecamatan]) {
            const [lat, lng, zoom] = kecamatanCoords[currentKecamatan];
            map.setView([lat, lng], zoom);
        }
    });

    // Helper fungsi klik sidebar berpindah ke marker
    window.panToMarker = function (lat, lng, id) {
        if (isHeatmapActive) {
            map.flyTo([lat, lng], 14);
            return;
        }

        const marker = markerRegistry[id];
        if (marker) {
            markersCluster.zoomToShowLayer(marker, function () {
                marker.openPopup();
            });
        } else {
            map.flyTo([lat, lng], 14);
        }
    };
</script>
@endpush
