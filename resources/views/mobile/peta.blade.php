<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | Peta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {'50': '#f0fdf4', '100': '#dcfce7', '500': '#16a34a', '600': '#15803d', '700': '#166534', '900': '#14532d'}, ink: '#111827', mist: '#f5f7f5', muted: '#6b7280', line: '#e5e7eb' }, boxShadow: { soft: '0 12px 30px rgba(15, 23, 42, 0.06)', lift: '0 18px 45px rgba(20, 83, 45, 0.15)' } } } } }
    </script>
    <style>
        * { box-sizing: border-box; }
        html, body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        body { background: #edf4ef; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
        .map-surface { background: linear-gradient(180deg, #e4f6ec 0%, #d6efd6 100%); }
        #leaflet-map { z-index: 0; }
    </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden bg-[#edf4ef] text-slate-800">
    <div class="min-h-screen w-full bg-white">
        <main class="px-4 pb-24 pt-3">
                <header class="mb-4 rounded-[26px] border border-emerald-200 bg-gradient-to-r from-emerald-500 via-emerald-400 to-lime-400 p-4 text-white shadow-[0_18px_40px_rgba(16,185,129,0.25)]">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.18em] text-emerald-50">
                                GIS SPASIAL
                            </div>
                            <h1 class="text-[18px] font-black tracking-tight">Peta UMKM</h1>
                        </div>
                        <button class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-emerald-50"><path d="M8 16h8"/><path d="M12 4a7 7 0 0 1 7 7c0 4.5-4.5 10.5-7 11.5-2.5-1-7-7-7-11.5A7 7 0 0 1 12 4Z"/></svg>
                        </button>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-[10px] text-emerald-50/90">
                        <span class="rounded-full border border-white/25 bg-white/10 px-2 py-1 font-semibold">{{ count($nearbyUmkm ?? []) }} titik</span>
                        <span class="rounded-full border border-white/25 bg-white/10 px-2 py-1 font-semibold">Kutim</span>
                    </div>
                </header>

                <form action="{{ route('preview.mobile.peta') }}" method="GET" class="mb-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <label class="flex flex-1 items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-4 py-3 text-slate-500 shadow-[0_10px_30px_rgba(15,23,42,0.08)] relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-emerald-500"><circle cx="11" cy="11" r="6"/><path d="m16 16 5 5"/></svg>
                            <input id="search-input" type="text" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari UMKM, alamat, atau kecamatan" class="w-full bg-transparent text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none" autocomplete="off" />
                            <div id="search-suggestions" class="absolute left-4 right-4 top-full mt-2 bg-white rounded-xl border border-slate-200 shadow-lg overflow-hidden z-50" style="display:none;"></div>
                        </label>
                        @if(($searchTerm ?? '') !== '' || request('kategori') || request('kecamatan'))
                            <a href="{{ route('preview.mobile.peta') }}" class="rounded-xl bg-white px-2.5 py-2 text-[10px] font-semibold text-slate-700 ring-1 ring-emerald-200 shadow-sm">Reset</a>
                        @endif
                    </div>

                    <div class="rounded-[24px] border border-emerald-100 bg-white/95 p-3 shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                        <div class="mb-2">
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Filter lengkap</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-700">
                            <label class="block rounded-2xl border border-emerald-100 bg-emerald-50/60 p-2">
                                <span class="mb-1.5 block text-[9px] font-bold uppercase tracking-[0.12em] text-slate-500">Kategori</span>
                                <select name="kategori" class="w-full bg-transparent text-sm text-slate-800 focus:outline-none">
                                    <option value="" {{ empty(request('kategori')) ? 'selected' : '' }}>Semua</option>
                                    @foreach(($categoryList ?? $categories ?? []) as $category)
                                        @php $categoryId = $category['id'] ?? null; @endphp
                                        <option value="{{ $categoryId }}" {{ (string) (request('kategori') ?? $selectedCategoryId ?? '') === (string) $categoryId ? 'selected' : '' }}>
                                            {{ $category['nama'] ?? 'Kategori' }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block rounded-2xl border border-emerald-100 bg-emerald-50/60 p-2">
                                <span class="mb-1.5 block text-[9px] font-bold uppercase tracking-[0.12em] text-slate-500">Kecamatan</span>
                                <select name="kecamatan" class="w-full bg-transparent text-sm text-slate-800 focus:outline-none">
                                    <option value="" {{ empty(request('kecamatan')) && empty($selectedKecamatan) ? 'selected' : '' }}>Semua kecamatan</option>
                                    @foreach(($kecamatanList ?? []) as $kecamatan)
                                        <option value="{{ $kecamatan }}" {{ (request('kecamatan') ?? $selectedKecamatan ?? '') === $kecamatan ? 'selected' : '' }}>{{ $kecamatan }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="w-full rounded-full bg-emerald-500 px-3 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20">Terapkan</button>
                        </div>
                    </div>
                </form>

                <section class="mb-4 rounded-[12px] overflow-hidden">
                    <div class="flex items-center justify-between px-3 py-2.5">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-700">Live map</span>
                        </div>
                        <button class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Heatmap</button>
                    </div>

                    <!-- Expanded map surface: near full-viewport for Google Maps-like experience -->
                    <div class="map-surface relative p-0 bg-gradient-to-br from-emerald-50 via-lime-50 to-emerald-100" style="height: calc(100vh - 140px);">
                        <!-- Leaflet map container (fills this area) -->
                        <div id="leaflet-map" style="height:100%; width:100%;"></div>
                    </div>

                    <!-- Footer summary removed to present map-only larger view on mobile -->
                </section>

                <!-- UMKM list removed on mobile peta: show map, search and filters only -->
                <div class="mb-6"></div>
            </main>
@include('mobile.partials.bottom-nav')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const mapEl = document.getElementById('leaflet-map');
            if(!mapEl) return;

            // initial center fallback
            const nearby = {!! json_encode($nearbyUmkm ?? []) !!};
            let center = [0, 0];
            if(nearby.length){
                const first = nearby.find(i => i.latitude && i.longitude) || nearby[0];
                center = [parseFloat(first.latitude || first.lat || 0), parseFloat(first.longitude || first.lng || 0)];
            }

            const map = L.map('leaflet-map', { zoomControl: false }).setView(center, nearby.length ? 13 : 6);

            // Configure tile provider via environment variables: MAP_TILE_PROVIDER (osm|mapbox|maptiler), MAP_TILE_KEY, MAP_TILE_STYLE / MAPBOX_STYLE
            const _provider = '{{ config("map.provider", "osm") }}'.toLowerCase();
            const _tileKey = '{{ config("map.key", "") }}';
            const _mapStyle = '{{ config("map.style", "streets") }}';
            const _mapboxStyle = '{{ config("map.mapbox_style", "mapbox/streets-v11") }}';

            let tileLayer;
            let fallbackApplied = false;

            // small on-screen debug panel for mobile (temporary)
            const debugPanel = document.createElement('div');
            debugPanel.id = 'map-debug';
            debugPanel.style.position = 'fixed';
            debugPanel.style.right = '12px';
            debugPanel.style.top = '12px';
            debugPanel.style.zIndex = 99999;
            debugPanel.style.background = 'rgba(0,0,0,0.65)';
            debugPanel.style.color = '#fff';
            debugPanel.style.padding = '8px 10px';
            debugPanel.style.borderRadius = '8px';
            debugPanel.style.fontSize = '12px';
            debugPanel.style.fontFamily = 'sans-serif';
            debugPanel.style.display = 'none';
            document.body.appendChild(debugPanel);

            function debug(msg){
                try{
                    console.log('[MAP-DEBUG]', msg);
                    debugPanel.textContent = String(msg);
                    debugPanel.style.display = 'block';
                    clearTimeout(debug._t);
                    debug._t = setTimeout(function(){ debugPanel.style.display = 'none'; }, 8000);
                } catch(e){ console.log('[MAP-DEBUG-ERR]', e); }
            }

            // helper to create OSM default
            function createOsmLayer(){
                return L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                });
            }

            // decide tile layer based on provider
            try {
                if(_provider === 'mapbox' && _tileKey){
                    // Mapbox styles API
                    const mapboxUrl = 'https://api.mapbox.com/styles/v1/' + _mapboxStyle + '/tiles/{z}/{x}/{y}?access_token=' + _tileKey;
                    tileLayer = L.tileLayer(mapboxUrl, { attribution: '© Mapbox © OpenStreetMap contributors', tileSize: 512, zoomOffset: -1, maxZoom: 20 }).addTo(map);
                } else if(_provider === 'maptiler' && _tileKey){
                    // MapTiler (style names like streets, basic, topo, etc.)
                    const maptilerUrl = 'https://api.maptiler.com/tiles/' + _mapStyle + '/{z}/{x}/{y}.png?key=' + _tileKey;
                    tileLayer = L.tileLayer(maptilerUrl, { attribution: '© MapTiler © OpenStreetMap contributors' }).addTo(map);
                } else {
                    tileLayer = createOsmLayer().addTo(map);
                }
                debug('Tile provider: '+_provider+( _tileKey ? ' (key provided)' : ' (no key)'));
            } catch(e){
                console.warn('Failed to initialize preferred tile layer', e);
                debug('Tile init failed: '+(e && e.message ? e.message : String(e)));
                tileLayer = createOsmLayer().addTo(map);
            }

            // Fallback tile URL (Stamen Terrain) if primary tiles fail or rate-limited
            const fallbackTileUrl = 'https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg';

            // Basic tile error handler: switch once to fallback and notify user
            if(tileLayer && tileLayer.on){
                let tileLoadCount = 0;
                let tileErrorCount = 0;

                tileLayer.on('tileload', function(){
                    tileLoadCount++;
                    if(tileLoadCount % 5 === 0) debug('Tiles loaded: '+tileLoadCount);
                });

                tileLayer.on('tileerror', function(err){
                    tileErrorCount++;
                    console.warn('Tile error', err);
                    debug('Tile error #'+tileErrorCount);
                    if(!fallbackApplied){
                        try { map.removeLayer(tileLayer); } catch(e){}
                        tileLayer = L.tileLayer(fallbackTileUrl, { attribution: 'Map tiles by Stamen, CC BY 3.0 — Map data © OpenStreetMap contributors' }).addTo(map);
                        fallbackApplied = true;
                        debug('Using fallback tiles');
                        showToast('Peta utama bermasalah — menggunakan fallback tiles. Jika sering terjadi, pertimbangkan menggunakan provider berbayar (Mapbox/Maptiler).', 'warning');
                    }
                });
            } else {
                // defensive: if tileLayer has no event support, still ensure fallbackApplied false
                fallbackApplied = false;
                debug('TileLayer has no event support');
            }

            L.control.zoom({ position: 'topright' }).addTo(map);

            // Locate control (simple)
            const locateBtn = L.control({position: 'topright'});
            locateBtn.onAdd = function() {
                const btn = L.DomUtil.create('button', 'rounded-full bg-white p-2 shadow ml-2');
                btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i>';
                btn.style.cursor = 'pointer';
                btn.title = 'Lokasi saya';
                L.DomEvent.on(btn, 'click', function(e){ map.locate({setView: true, maxZoom: 16}); });
                return btn;
            };
            locateBtn.addTo(map);
            map.on('locationerror', function(){ if(window.alert) alert('Tidak dapat menentukan lokasi Anda'); });

            // Layer to hold current markers / clusters
            const dataLayer = L.layerGroup().addTo(map);

            // Keep map of point id -> marker for popup restoration
            const idToMarker = new Map();
            let lastOpenPointId = null;

            // Client-side cache for viewport responses (keyed by rounded bbox+zoom+filters)
            const cache = new Map();
            const CACHE_TTL = 2 * 60 * 1000; // 2 minutes

            function roundCoord(v, precision = 3){ return Math.round(v * Math.pow(10, precision)) / Math.pow(10, precision); }
            function buildCacheKey(sw, ne, zoom, q, kategori){
                return [roundCoord(sw.lat), roundCoord(sw.lng), roundCoord(ne.lat), roundCoord(ne.lng), zoom, q || '', kategori || ''].join('_');
            }

            // Simple debounce
            function debounce(fn, wait){ let t; return function(...args){ clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), wait); }; }

            // Small toast helper
            function showToast(message, type = 'success', duration = 5000){
                let toast = document.getElementById('map-toast');
                if(!toast){
                    toast = document.createElement('div');
                    toast.id = 'map-toast';
                    toast.style.position = 'fixed';
                    toast.style.left = '50%';
                    toast.style.transform = 'translateX(-50%)';
                    toast.style.top = '12px';
                    toast.style.zIndex = 99999;
                    toast.style.padding = '10px 14px';
                    toast.style.borderRadius = '12px';
                    toast.style.fontWeight = '600';
                    toast.style.boxShadow = '0 8px 24px rgba(0,0,0,0.12)';
                    document.body.appendChild(toast);
                }
                toast.style.background = type === 'warning' ? '#fbbf24' : (type === 'error' ? '#ef4444' : '#10b981');
                toast.style.color = '#fff';
                toast.textContent = message;
                toast.style.display = 'block';
                setTimeout(()=>{ toast.style.display = 'none'; }, duration);
            }

            // Render clusters/points response into map
            function renderResponse(resp){
                            // Remember currently open point id so we can re-open after re-render
                            const preservedOpenId = lastOpenPointId;
                            // Clear map storage
                            dataLayer.clearLayers();
                            idToMarker.clear();
                            lastOpenPointId = null;
                            if(!resp || !resp.data) return;

                if(resp.mode === 'points'){
                    resp.data.forEach(p => {
                        if(!p.lat || !p.lng) return;
                        const marker = L.circleMarker([p.lat, p.lng], { radius: 6, color: '#16a34a', fillColor: '#34d399', fillOpacity: 0.9 }).addTo(dataLayer);
                        const imgSrc = p.foto_utama || '{{ asset("umkm.png") }}';
                        const popup = '<div style="min-width:220px;text-align:center;padding:6px 8px">'
                            + '<img src="'+escapeHtml(imgSrc)+'" style="width:100%;max-width:260px;height:140px;object-fit:cover;border-radius:10px;margin-bottom:8px;"/>'
                            + '<div style="font-weight:700;font-size:15px;margin-bottom:6px;color:#111827;">'+escapeHtml(p.nama_usaha || p.nama || 'UMKM')+'</div>'
                            + '<div style="font-size:13px;color:#374151;margin-bottom:10px;">'+escapeHtml(p.kategori || '')+'</div>'
                            + '<div><a href="'+(p.url || '#')+'" style="display:inline-block;padding:8px 12px;background:#10b981;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;">Lihat detail</a></div>'
                        + '</div>';
                        marker.bindPopup(popup);
                        // track popup open/close so we can restore after re-render
                        try{ marker.on('popupopen', function(){ lastOpenPointId = p.id; }); marker.on('popupclose', function(){ if(lastOpenPointId === p.id) lastOpenPointId = null; }); } catch(e){}
                        idToMarker.set(p.id, marker);
                    });

                    // Restore previously open popup if present
                    if(preservedOpenId){
                        const found = idToMarker.get(preservedOpenId);
                        if(found) { found.openPopup(); }
                    }
                                } else if(resp.mode === 'clusters'){
                    resp.data.forEach(c => {
                        if(!c.lat || !c.lng) return;
                        const count = c.count || 0;
                        const radius = Math.min(40, 8 + Math.log10(Math.max(1, count)) * 8);
                        const label = count >= 1000 ? Math.round(count/1000) + 'k' : count.toString();
                        const size = Math.round(Math.min(80, Math.max(24, radius * 2)));
                        const fontSize = Math.max(10, Math.round(size / 3));
                        const html = '<div style="display:flex;align-items:center;justify-content:center;width:'+size+'px;height:'+size+'px;border-radius:50%;background:#10b981;color:#fff;font-weight:700;border:2px solid rgba(255,255,255,0.85);box-shadow:0 6px 14px rgba(16,185,129,0.25);font-size:'+fontSize+'px;">'+label+'</div>';
                        const icon = L.divIcon({ html: html, className: 'cluster-div-icon', iconSize: [size, size], iconAnchor: [Math.round(size/2), Math.round(size/2)] });
                        const marker = L.marker([c.lat, c.lng], { icon }).addTo(dataLayer);
                        const popup = '<div style="min-width:140px"><div style="font-weight:700;font-size:14px;margin-bottom:6px;">'+escapeHtml(c.kecamatan || 'Area')+'</div><div style="font-size:13px;color:#374151;">Titik: '+label+'</div></div>';
                        marker.bindPopup(popup);
                        // click to zoom in if cluster
                        marker.on('click', function(){ if(count > 1){ map.setView([c.lat, c.lng], Math.min(18, map.getZoom() + 2)); } });
                    });
                    if(resp.truncated){ showToast('Hasil dipersempit - tampil sebagian. Coba perbesar tingkat zoom atau batasi filter.', 'warning', 7000); }
                }
            }

            function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; }); }

            async function fetchViewportData(){
                const bounds = map.getBounds();
                const sw = bounds.getSouthWest();
                const ne = bounds.getNorthEast();
                const zoom = map.getZoom();
                const q = document.querySelector('input[name="q"]')?.value || '';
                const kategori = document.querySelector('select[name="kategori"]')?.value || '';

                const cacheKey = buildCacheKey(sw, ne, zoom, q, kategori);
                const now = Date.now();
                if(cache.has(cacheKey)){
                    const entry = cache.get(cacheKey);
                    if(now - entry.ts < CACHE_TTL){ renderResponse(entry.data); return; }
                    cache.delete(cacheKey);
                }

                const params = new URLSearchParams({ sw_lat: sw.lat, sw_lng: sw.lng, ne_lat: ne.lat, ne_lng: ne.lng, zoom: zoom, q: q, kategori: kategori });
                const url = '{{ route('api.umkm.clusters') }}' + '?' + params.toString();

                try{
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if(!res.ok) throw new Error('Network response not ok');
                    const json = await res.json();
                    cache.set(cacheKey, { ts: now, data: json });
                    renderResponse(json);
                } catch(err){
                    console.error('Failed to fetch clusters', err);
                    showToast('Gagal memuat data peta. Coba muat ulang atau periksa koneksi.', 'error', 6000);
                }
            }

            const debouncedFetch = debounce(fetchViewportData, 300);
            map.on('moveend', debouncedFetch);
            map.on('zoomend', debouncedFetch);

            // re-fetch when filters/search applied
            document.querySelectorAll('select[name="kategori"], input[name="q"]').forEach(el => el.addEventListener('change', debouncedFetch));

            // initial load
            fetchViewportData();
        });
    </script>
</body>
</html>
