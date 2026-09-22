<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | Peta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbR2fQvQ4bYk0z0Qq1kFQv6Y1Y6rjz1lV7KzP7Q6q1Y4JVj7k2qZ6Y3n1J6+gk1Z1+3s6K1X1Y2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        .phone-shell { width: min(100%, 390px); max-width: 100%; min-height: 840px; background: #f8faf8; border-radius: 32px; box-shadow: 0 30px 80px rgba(17, 24, 39, 0.12); overflow: hidden; border: 1px solid rgba(17, 24, 39, 0.04); }
        .status-bar { height: 28px; background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); }
        .bottom-nav { background: rgba(255,255,255,0.0); backdrop-filter: blur(12px); border-top: 0; box-shadow: none; z-index: 80; }
        .nav-pill { border-radius: 18px; padding: 8px 6px 10px; transition: all .2s ease; min-height: 72px; }
        .nav-pill.active { color: #111827; }
        .nav-icon { width: 42px; height: 42px; border-radius: 15px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #6b7280; transition: all .2s ease; box-shadow: inset 0 0 0 1px rgba(17,24,39,0.02); }
        .nav-pill.active .nav-icon { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: white; box-shadow: 0 12px 24px rgba(22, 163, 74, 0.26); }
        .nav-label { display: block; margin-top: 6px; font-size: 9px; line-height: 1; letter-spacing: 0.03em; text-transform: uppercase; }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
        .map-surface { background: linear-gradient(180deg, #e4f6ec 0%, #d6efd6 100%); }
        /* Ensure map sits below the bottom navigation and controls */
        #leaflet-map { z-index: 0; }
        nav.bottom-nav { z-index: 90; position: fixed; }
        @media (max-width: 420px) {
            body {
                padding: 0;
            }
            .phone-shell {
                width: 100%;
                max-width: 100%;
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
                border-left: 0;
                border-right: 0;
            }
            .status-bar {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            nav.bottom-nav {
                width: 100%;
                max-width: 100%;
                left: 0;
                transform: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden flex items-center justify-center p-2 sm:p-4 md:p-8">
    <div class="flex w-full justify-center">
        <div class="phone-shell">
            <div class="status-bar flex items-center justify-between px-5 text-[11px] font-semibold text-slate-700">
                <span>09:41</span>
                <div class="flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-2.5 w-5 rounded-full border border-slate-700"></span>
                </div>
            </div>
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
            <nav class="bottom-nav fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[390px] px-3 pb-3 pt-2">
                <div class="grid grid-cols-5 gap-2 rounded-[30px] bg-white px-2 py-2 shadow-[0_18px_40px_rgba(15,23,42,0.12)] border border-slate-200/80">
                    <a href="{{ route('preview.mobile') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg></span><span class="nav-label">Home</span></a>
                    <a href="{{ route('preview.mobile.umkm') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M7 7h10l2 11H5l2-11Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg></span><span class="nav-label">UMKM</span></a>
                    <a href="{{ route('preview.mobile.peta') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.peta') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6.5L9 3l6 3 6-3v13l-6 3-6-3-6 3V6.5z"/><path d="M9 3v13M15 6v11"/></svg></span><span class="nav-label">Peta</span></a>
                    <a href="{{ route('preview.mobile.promo') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.promo') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 8h-1l-2 2"/><path d="M7 8h10v8H7z"/><path d="M8 12h8"/></svg></span><span class="nav-label">Promo</span></a>
                    <a href="{{ route('preview.mobile.akun') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.akun') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="8" r="4"/><path d="M4 20c2-3 5-4 8-4s6 1 8 4"/></svg></span><span class="nav-label">Akun</span></a>
                </div>
            </nav>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const mapEl = document.getElementById('leaflet-map');
            if(!mapEl) return;
            const nearby = {!! json_encode($nearbyUmkm ?? []) !!};
            // determine center
            let center = [0,0];
            if(nearby.length){
                const first = nearby.find(i => i.latitude && i.longitude) || nearby[0];
                center = [parseFloat(first.latitude || first.lat || first.lat_point || 0), parseFloat(first.longitude || first.lng || first.lon || first.long || 0)];
            }
            const map = L.map('leaflet-map', { zoomControl: false }).setView(center, nearby.length ? 13 : 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.control.zoom({ position: 'topright' }).addTo(map);

            // simple locate button
            const locateBtn = L.control({position: 'topright'});
            locateBtn.onAdd = function() {
                const btn = L.DomUtil.create('button', 'rounded-full bg-white p-2 shadow ml-2');
                btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i>';
                btn.style.cursor = 'pointer';
                btn.title = 'Lokasi saya';
                L.DomEvent.on(btn, 'click', function(e){
                    map.locate({setView: true, maxZoom: 16});
                });
                return btn;
            };
            locateBtn.addTo(map);
            map.on('locationerror', function(){ if(window.alert) alert('Tidak dapat menentukan lokasi Anda'); });

            // markers
            nearby.forEach(function(item){
                const lat = parseFloat(item.latitude ?? item.lat ?? 0);
                const lng = parseFloat(item.longitude ?? item.lng ?? 0);
                if(!isFinite(lat) || !isFinite(lng)) return;
                const marker = L.marker([lat,lng]).addTo(map);
                const name = item.nama ?? item.name ?? item.title ?? 'UMKM';
                const category = (item.kategori && item.kategori.nama) ? item.kategori.nama : (item.kategori_name ?? item.category ?? '');
                const popupHtml = '<div style="min-width:180px"><div style="font-weight:700;font-size:14px;margin-bottom:6px;">'+name+'</div><div style="font-size:13px;color:#374151;">'+category+'</div></div>';
                marker.bindPopup(popupHtml);
            });

            // search suggestions: debounce + AJAX
            const searchInput = document.getElementById('search-input');
            const suggBox = document.getElementById('search-suggestions');
            let debounceTimer = null;

            function escapeHtml(s){
                return String(s||'').replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; });
            }

            function renderSuggestions(items){
                if(!suggBox) return;
                if(!items || items.length === 0){ suggBox.style.display = 'none'; suggBox.innerHTML = ''; return; }
                suggBox.style.display = 'block';
                suggBox.innerHTML = items.map(function(it){
                    const lat = it.latitude ?? it.lat ?? '';
                    const lng = it.longitude ?? it.lng ?? '';
                    const name = it.nama_usaha ?? it.nama ?? '';
                    const kategori = (it.kategori && it.kategori.nama) ? it.kategori.nama : (it.kategori_name ?? '');
                    const alamat = it.alamat ?? '';
                    return '<button data-lat="'+lat+'" data-lng="'+lng+'" data-name="'+escapeHtml(name)+'" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b last:border-b-0"><div class="text-sm font-semibold">'+escapeHtml(name)+'</div><div class="text-xs text-slate-500">'+escapeHtml(kategori)+' · '+escapeHtml(alamat)+'</div></button>';
                }).join('');

                Array.from(suggBox.querySelectorAll('button')).forEach(function(btn){
                    btn.addEventListener('click', function(){
                        const lat = parseFloat(this.dataset.lat);
                        const lng = parseFloat(this.dataset.lng);
                        const name = this.dataset.name || '';
                        if(searchInput) searchInput.value = name;
                        suggBox.style.display = 'none';
                        if(isFinite(lat) && isFinite(lng)){
                            map.setView([lat,lng], 16);
                            L.popup({maxWidth:260}).setLatLng([lat,lng]).setContent('<div style="font-weight:700">'+escapeHtml(name)+'</div>').openOn(map);
                        }
                    });
                });
            }

            if(searchInput){
                searchInput.addEventListener('input', function(e){
                    const val = this.value.trim();
                    clearTimeout(debounceTimer);
                    if(val.length < 2){ suggBox.style.display = 'none'; suggBox.innerHTML = ''; return; }
                    debounceTimer = setTimeout(function(){
                        const kategori = document.querySelector('select[name="kategori"]')?.value || '';
                        fetch('{{ route('preview.mobile.peta.suggest') }}?q='+encodeURIComponent(val)+'&kategori='+encodeURIComponent(kategori))
                            .then(function(res){ return res.json(); })
                            .then(function(json){ renderSuggestions(json.data || []); })
                            .catch(function(){ renderSuggestions([]); });
                    }, 300);
                });

                document.addEventListener('click', function(ev){
                    if(!suggBox.contains(ev.target) && ev.target !== searchInput){
                        suggBox.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
