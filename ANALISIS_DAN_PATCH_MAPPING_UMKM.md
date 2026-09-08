# Analisis Mapping UMKM (Kutim) + Patch Production-Ready

## TL;DR
Gokil sih, ini bukan project yang "belum mikirin performa" — lu (atau AI sebelumnya) udah bikin arsitektur yang lumayan advanced: server-side grid pre-aggregation, viewport-based spatial query pakai `MBRContains`, cache 10 menit, dan tiering cluster/point berdasarkan zoom level. Ini jauh di atas rata-rata implementasi "taro semua marker di Leaflet" yang biasa dijumpai.

Tapi ada **1 bug fatal** (dead query yang jalan di setiap page load), **1 bug skalabilitas nyata** (query tanpa limit di jalur filter), dan **1 celah caching** (data baru nggak langsung muncul). Semua itu udah gue patch di file-file terlampir.

---

## 1. Gimana Alur Kerja Sekarang (DB → API → FE → Marker → Popup)

```
[MySQL: tabel umkm]
   location POINT SRID 4326 + SPATIAL INDEX
   idx_kategori, idx_kecamatan, idx_status_klaim
        │
        ▼
[umkm_grid_cluster] ← di-refresh TIAP JAM via umkm:refresh-grid (routes/console.php)
   pre-agregasi: ROUND(lat,2), ROUND(lng,2) → jumlah_umkm, terverifikasi_count
        │
        ▼
[GET /api/umkm/clusters] (UmkmClusterController)
   1. zoom ≤ 14 & TANPA filter kategori/q/status → baca umkm_grid_cluster (super cepat, <5ms)
   2. zoom ≤ 14 & ADA filter                     → query umkm langsung + cluster manual di PHP
   3. zoom ≥ 15                                   → titik individual (limit 2000, pakai spatial index)
   Semua response di-cache 10 menit (Cache::remember)
        │
        ▼
[Frontend: peta/index.blade.php]
   MapLibre GL (basemap) + Leaflet (marker layer) + Leaflet.markercluster (mode titik)
   + Leaflet.heat (mode heatmap, opsional)
   fetch() saat map 'moveend'/'zoomend', di-debounce 350ms
   render cluster-badge (divIcon custom) atau pin individual tergantung `res.mode`
```

Titik kuat yang **udah bener** dan jangan diutak-atik:
- **Spatial index MySQL** dipakai beneran lewat `MBRContains(ST_GeomFromText(...), location)` — bukan filter lat/lng manual pakai BETWEEN doang.
- **Grid pre-aggregation** (`umkm_grid_cluster`) bikin request zoom rendah/tanpa filter nggak pernah nyentuh tabel 45rb baris sama sekali.
- **3-tier response mode** (grid-agregat / raw-cluster-manual / titik individual) sesuai rekomendasi standar industri (mirip pendekatan Supercluster/PostGIS ST_ClusterKMeans, cuma versi MySQL-native).
- **Debounce 350ms** di FE + cache key yang di-quantize (`round(lat,2)`) biar viewport yang mirip-mirip hit cache yang sama.
- **`preferCanvas: true`** di Leaflet init — bikin rendering lebih ringan dibanding SVG renderer default.

---

## 2. Bottleneck yang Ketemu

### 🔴 BUG #1 — Dead query full-table di `PetaController::index()` (CRITICAL)
**File asli:** `app/Http/Controllers/PetaController.php`

Controller ini jalanin:
```php
$query = Umkm::active()->withCoordinates()->with('kategori:id,nama,icon');
// + filter kecamatan/kategori/status_klaim/q dari query string
$umkmItems = $query->orderByDesc('rating')->get();
$mapData = $umkmItems->map(function ($item) { ... });
```
lalu ngirim `$mapData` dan `$umkmItems` ke view. **Tapi gue cek `peta/index.blade.php` baris-per-baris — dua variable itu SAMA SEKALI nggak dipake.** Data peta 100% datang dari AJAX call ke `/api/umkm/clusters`. Jadi tiap kali orang buka `/peta`, server nge-fetch + hydrate SEMUA UMKM aktif (bisa 45rb baris + relasi kategori) ke memory PHP, terus... dibuang gitu aja.

**Dampak nyata di skala 45rb:** ini bisa nyumbang detik-detik page load yang nggak kelihatan manfaatnya sama sekali, plus beban memory PHP-FPM per-request yang nggak perlu (bikin worker gampang OOM/kena limit `memory_limit` kalau traffic lagi rame).

**Fix:** `PetaController.php` (terlampir) — query itu dihapus total, cuma nyisain `count()` buat header stats.

### 🔴 BUG #2 — Query tanpa LIMIT di jalur "filter aktif" (SCALABILITY)
**File asli:** `app/Http/Controllers/Api/UmkmClusterController.php`, cabang `else` (filter kategori/status/pencarian aktif → bukan tabel grid pre-agregat).

```php
$rawPoints = $query->selectRaw(...)->get(); // <- TANPA LIMIT
```
Kalau user search kata umum ("warung", "toko") atau filter kategori luas di zoom rendah/tanpa bbox sempit, ini bisa narik **ribuan-puluhan ribu baris** ke PHP buat di-cluster manual pakai loop. Ini yang bakal "kelihatan ringan" pas testing pake data dummy 2rb (sesuai `IMPLEMENTASI_PETA_UMKM.md`), tapi meledak begitu data asli 45rb masuk dan user iseng search istilah umum.

**Fix:** ditambahin hard cap `MAX_RAW_POINTS = 8000` + flag `truncated` di response, biar FE bisa kasih tau user "hasil dipersempit, coba filter lebih spesifik" — bukan diam-diam motong data tanpa penjelasan.

> Catatan tambahan (nggak gue force-implement karena ubah semantik search): kolom `nama_usaha`/`alamat` di-filter pakai `LIKE '%keyword%'` — leading wildcard bikin index biasa nggak kepake, jadi tetep full-scan pas nyari. Kalau data makin gede dan performa search masih berasa lambat meski udah dibatasin, next step-nya tambahin **FULLTEXT INDEX** + `MATCH ... AGAINST` di dua kolom itu. Gue nggak paksain sekarang karena bakal ubah UX pencarian (fulltext MySQL default minimal 4 karakter per kata & beda relevance).

### 🟡 BUG #3 — Cache nggak nge-invalidate pas ada data baru (STALENESS)
Cache key di `/api/umkm/clusters` nggak terhubung ke versi data. Jadi kalau `umkm:refresh-grid` selesai jalan (baik dari schedule jam-an atau abis import CSV), response lama yang kepalang ke-cache (TTL 10 menit) tetep keserve — user liat data yang belum update sampe cache-nya expired sendiri.

**Fix:** nambahin `umkm_cluster_cache_ver` (integer) yang di-embed ke cache key. `RefreshGridCluster` nge-increment versi ini tiap kali selesai refresh, jadi SEMUA cache lama otomatis "basi" instan tanpa perlu cache driver yang support tagging (aman dipake di file/database/Redis cache sekalipun).

Sekalian gue tambahin: `ImportDinasUmkmCommand` sekarang manggil `umkm:refresh-grid` otomatis kalau dijalanin pakai `--sync` (biar data hasil import langsung muncul di peta, nggak nunggu jadwal tiap jam).

### 🟡 BUG #4 — Nggak ada index di kolom `status` (INDEX GAP)
`Umkm::active()` = `where('status', 'active')` dipanggil di HAMPIR SEMUA query (viewport, cluster, count header) — tapi migration `umkm` cuma punya index buat `kategori_id`, `kecamatan`, `status_klaim`, dan spatial `location`. Kolom `status` sendiri nggak ke-index, padahal dipake sebagai filter utama di mana-mana.

**Fix:** migration baru nambahin `idx_status` + composite `idx_status_kecamatan` (kombinasi filter yang paling sering dipake bareng).

### 🟢 Minor — Leaflet/MapLibre/heat plugin di-load global di layout
`resources/views/layouts/app.blade.php` nge-load Leaflet + MarkerCluster CSS/JS di **setiap halaman**, padahal cuma dipake di 4 page (`home`, `peta`, `umkm/show`, `umkm/create-mandiri`). Nggak gue rewrite filenya (risiko break urutan script di halaman lain kalau nggak liat full context-nya), tapi worth dipindah ke `@push` per-page kalau mau ngirit ~150-200KB asset di halaman-halaman yang nggak butuh (admin panel, bazar, pelatihan, dll).

### 🟢 Minor — heatmap points dihitung terus tiap render walau heatmap nggak aktif
Di `renderMapAndSidebar()`, `heatPoints` di-push terus di dalam loop marker meskipun `isHeatmapActive` false, terus `L.heatLayer(...)` selalu dibikin ulang. Overhead-nya kecil (cuma array push + object construction, bukan DOM), jadi ini di prioritas paling bawah — kalau mau irit dikit, bungkus pembuatan `heatPoints`/`heatLayer` dengan `if (isHeatmapActive || wasEverActivated)`.

---

## 3. Kenapa Nggak Gue Ganti ke Vector Tiles / PostGIS?

Sengaja **nggak** gue rombak ke pendekatan lain (misal migrasi ke PostGIS + `ST_ClusterKMeans`, atau generate vector tiles via `pg_tileserv`/Martin) walau itu teoritis "lebih scalable di atas kertas". Alasannya:

1. Stack sekarang **MySQL + spatial index + grid pre-aggregation** itu **sudah cukup** buat target 50rb data — beban kerja clustering server-side yang lu implementasikan itu O(jumlah grid cell), bukan O(jumlah UMKM), jadi udah flat dari sisi compute begitu grid ke-refresh.
2. Migrasi ke PostGIS = ganti database engine = risiko besar buat project yang udah jalan, effort berminggu-minggu, padahal bottleneck ASLI (dead query, missing index, unbounded raw query) itu murni bug implementasi yang gampang di-fix tanpa ganti arsitektur.
3. Prinsip "jangan optimasi yang cuma keliatan bagus tapi nggak nyelesain masalah" — 45-50rb baris itu skala kecil-menengah buat MySQL modern dengan spatial index yang bener. Vector tiles baru justified kalau lu udah ngomongin jutaan titik atau butuh render di client tanpa clustering (misal choropleth per-titik).

---

## 4. Cara Terapin Patch Ini

```bash
# 1. Ganti file (timpa yang lama)
cp PetaController.php app/Http/Controllers/PetaController.php
cp UmkmClusterController.php app/Http/Controllers/Api/UmkmClusterController.php
cp RefreshGridCluster.php app/Console/Commands/RefreshGridCluster.php
cp ImportDinasUmkmCommand.php app/Console/Commands/ImportDinasUmkmCommand.php

# 2. Migration index baru
cp 2026_09_08_000001_add_umkm_performance_indexes.php database/migrations/
php artisan migrate

# 3. Bersihin cache lama biar cache-versioning mulai dari state fresh
php artisan cache:clear

# 4. (opsional tapi disaranin) jalanin ulang grid refresh manual sekali
php artisan umkm:refresh-grid
```

Nggak ada perubahan di frontend (`peta/index.blade.php`) — semua fix di atas murni backend/query level, jadi UI/UX yang udah ada (heatmap toggle, legenda kecamatan, sidebar list, dst) nggak kesentuh sama sekali.

## 5. Testing Checklist Abis Patch
- [ ] Buka `/peta` → cek Network tab, response time initial page load harusnya turun (nggak ada lagi query `umkm.*` gede di `php artisan telescope`/query log kalau lu pake debugbar)
- [ ] Search kata umum ("toko") di zoom rendah tanpa filter kecamatan → cek response punya `truncated: true` kalau emang kena cap, dan nggak bikin request lemot
- [ ] Jalanin `php artisan umkm:import-dinas file.csv --sync` dengan data baru → cek peta langsung kereflect tanpa nunggu 1 jam
- [ ] `EXPLAIN` query `SELECT * FROM umkm WHERE status='active'` → pastikan `idx_status` kepake (bukan full table scan)
