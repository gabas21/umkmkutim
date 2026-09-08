# 🗺️ IMPLEMENTASI HALAMAN PETA — UMKM KUTIM

**Fokus:** Peta persebaran 45rb data UMKM — akurat, ringan, terbatas area Kutim  
**Prasyarat:** Tabel `umkm` sudah ada & terisi (minimal sebagian data untuk testing)  
**Estimasi:** 2-4 hari kerja

---

## ✅ CHECKLIST SEBELUM MULAI

- [ ] Migration tabel `umkm` sudah jalan, kolom `location` bertipe `POINT SRID 4326`
- [ ] Ada minimal 100-500 data dummy/asli untuk testing (jangan test dengan 0 data)
- [ ] Sudah tahu koordinat boundary Kutim (lihat Langkah 0 di bawah kalau belum)
- [ ] Laravel project sudah jalan (`php artisan serve`)

---

## 📦 LANGKAH 0 — Siapkan Boundary Koordinat Kutim

Kamu butuh 2 titik (Southwest & Northeast) yang membentuk kotak pembatas wilayah Kutim.

**Cara cepat dapetin ini:**
1. Buka [bboxfinder.com](http://bboxfinder.com)
2. Cari & gambar kotak di sekitar wilayah Kabupaten Kutai Timur
3. Copy koordinat yang muncul (format: `minLng, minLat, maxLng, maxLat`)

**Perkiraan awal (VALIDASI ULANG, ini estimasi kasar):**
```
Southwest: lat -0.9, lng 116.8
Northeast: lat  1.2, lng 118.0
```
> ⚠️ Ini perkiraan dari peta umum — cek ulang manual di Google Maps/bboxfinder supaya tidak ada UMKM yang ke-cut di pinggir wilayah.

---

## 📦 LANGKAH 1 — Install Dependency

### Frontend (via NPM)
```bash
npm install leaflet leaflet.markercluster axios
```

### Tambahkan CSS Leaflet di layout utama
```html
<!-- resources/views/layouts/app.blade.php, di dalam <head> -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
```

> **Kenapa CDN untuk CSS?** Lebih ringan di-load browser (cached lintas situs) dibanding bundling manual. JS-nya tetap kita import via NPM untuk kontrol versi yang lebih rapi di build process.

---

## 📦 LANGKAH 2 — Backend: Migration (Kalau Belum Ada)

```bash
php artisan make:migration create_umkm_table
```

```php
// database/migrations/xxxx_create_umkm_table.php
public function up()
{
    DB::statement("
        CREATE TABLE umkm (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nama_usaha VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            kategori_id INT UNSIGNED NULL,
            kecamatan VARCHAR(100) NOT NULL,
            alamat TEXT NULL,
            location POINT SRID 4326 NOT NULL,
            status ENUM('active','inactive','suspended') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            SPATIAL INDEX idx_location (location),
            INDEX idx_kecamatan (kecamatan)
        )
    ");
}

public function down()
{
    Schema::dropIfExists('umkm');
}
```

> **Kenapa raw SQL, bukan Schema Builder biasa?** Laravel Schema Builder support tipe spatial (`$table->point('location', srid: 4326)`) di versi terbaru, tapi `SPATIAL INDEX` kadang perlu ditambahkan manual via raw statement tergantung versi Laravel/driver. Kalau Laravel 11 kamu sudah support penuh, boleh pakai:
```php
Schema::create('umkm', function (Blueprint $table) {
    $table->id();
    $table->string('nama_usaha');
    $table->string('slug')->unique();
    $table->unsignedInteger('kategori_id')->nullable();
    $table->string('kecamatan');
    $table->text('alamat')->nullable();
    $table->point('location', srid: 4326);
    $table->enum('status', ['active','inactive','suspended'])->default('active');
    $table->timestamps();
    $table->spatialIndex('location');
    $table->index('kecamatan');
});
```
Coba cara ini dulu — kalau error di driver MySQL kamu, baru fallback ke raw SQL di atas.

---

## 📦 LANGKAH 3 — Backend: Seeder Data Dummy (Untuk Testing Sebelum Data Asli Masuk)

```bash
php artisan make:seeder UmkmSeeder
```

```php
// database/seeders/UmkmSeeder.php
public function run()
{
    $kecamatanList = ['Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan', 'Muara Wahau'];

    for ($i = 1; $i <= 2000; $i++) {
        // Random point dalam boundary Kutim (sesuaikan dengan Langkah 0)
        $lat = -0.9 + (mt_rand() / mt_getrandmax()) * (1.2 - (-0.9));
        $lng = 116.8 + (mt_rand() / mt_getrandmax()) * (118.0 - 116.8);

        DB::table('umkm')->insert([
            'nama_usaha' => "UMKM Dummy #$i",
            'slug' => "umkm-dummy-$i",
            'kecamatan' => $kecamatanList[array_rand($kecamatanList)],
            'alamat' => 'Alamat contoh ' . $i,
            'location' => DB::raw("ST_SRID(POINT($lng, $lat), 4326)"),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```

```bash
php artisan db:seed --class=UmkmSeeder
```

> Pakai 2000 data dummy dulu untuk development — baru scale-test ke puluhan ribu setelah fitur inti jalan lancar.

---

## 📦 LANGKAH 4 — Backend: Controller & Route

```bash
php artisan make:controller Api/UmkmMapController
```

```php
// app/Http/Controllers/Api/UmkmMapController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmkmMapController extends Controller
{
    public function viewport(Request $request)
    {
        $request->validate([
            'sw_lat' => 'required|numeric',
            'sw_lng' => 'required|numeric',
            'ne_lat' => 'required|numeric',
            'ne_lng' => 'required|numeric',
            'zoom'   => 'required|integer',
        ]);

        $swLat = $request->sw_lat; $swLng = $request->sw_lng;
        $neLat = $request->ne_lat; $neLng = $request->ne_lng;
        $zoom  = (int) $request->zoom;

        $polygon = "POLYGON(($swLng $swLat, $neLng $swLat, $neLng $neLat, $swLng $neLat, $swLng $swLat))";

        $query = DB::table('umkm')
            ->where('status', 'active')
            ->whereRaw('MBRContains(ST_GeomFromText(?, 4326), location)', [$polygon])
            ->select(
                'id',
                'nama_usaha',
                'kecamatan',
                DB::raw('ST_X(location) as lng'),
                DB::raw('ST_Y(location) as lat')
            );

        // Zoom rendah (lihat area luas) → batasi jumlah result
        if ($zoom < 12) {
            $data = $query->limit(500)->get();
        } else {
            $data = $query->limit(2000)->get(); // safety cap, jaga-jaga area padat
        }

        return response()->json($data);
    }
}
```

```php
// routes/api.php
use App\Http\Controllers\Api\UmkmMapController;

Route::get('/umkm/viewport', [UmkmMapController::class, 'viewport']);
```

**Test endpoint dulu sebelum lanjut ke frontend:**
```bash
curl "http://localhost:8000/api/umkm/viewport?sw_lat=-0.9&sw_lng=116.8&ne_lat=1.2&ne_lng=118.0&zoom=10"
```
Pastikan hasilnya JSON array berisi data, bukan error 500.

---

## 📦 LANGKAH 5 — Frontend: Halaman Peta

```php
// routes/web.php
Route::get('/peta', function () {
    return view('peta.index');
});
```

```html
<!-- resources/views/peta/index.blade.php -->
@extends('layouts.app')

@section('content')
<div id="map" style="height: 85vh; width: 100%;"></div>
@endsection

@push('scripts')
<script type="module">
import L from 'leaflet';
import 'leaflet.markercluster';
import axios from 'axios';

// === 1. Boundary Kutim (GANTI dengan koordinat hasil validasi Langkah 0) ===
const kutimBounds = L.latLngBounds(
    [-0.9, 116.8],  // Southwest
    [1.2, 118.0]    // Northeast
);

// === 2. Init Map ===
const map = L.map('map', {
    maxBounds: kutimBounds,
    maxBoundsViscosity: 1.0,
    minZoom: 9,
    maxZoom: 18,
}).fitBounds(kutimBounds);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 18,
}).addTo(map);

// === 3. Marker Cluster Group ===
const markers = L.markerClusterGroup({
    chunkedLoading: true,
    maxClusterRadius: (zoom) => (zoom < 12 ? 80 : zoom < 15 ? 40 : 10),
    disableClusteringAtZoom: 17,
});
map.addLayer(markers);

// === 4. Loading Indicator Sederhana ===
let isLoading = false;
function setLoading(state) {
    isLoading = state;
    const el = document.getElementById('map-loading');
    if (el) el.style.display = state ? 'block' : 'none';
}

// === 5. Fetch Data Sesuai Viewport ===
async function muatDataViewport() {
    if (isLoading) return;
    setLoading(true);

    const b = map.getBounds();
    try {
        const { data } = await axios.get('/api/umkm/viewport', {
            params: {
                sw_lat: b.getSouthWest().lat,
                sw_lng: b.getSouthWest().lng,
                ne_lat: b.getNorthEast().lat,
                ne_lng: b.getNorthEast().lng,
                zoom: map.getZoom(),
            },
        });

        markers.clearLayers();
        data.forEach((u) => {
            const marker = L.marker([u.lat, u.lng]).bindPopup(`
                <strong>${u.nama_usaha}</strong><br>
                ${u.kecamatan}<br>
                <a href="/umkm/${u.id}">Lihat Detail →</a>
            `);
            markers.addLayer(marker);
        });
    } catch (err) {
        console.error('Gagal memuat data peta:', err);
    } finally {
        setLoading(false);
    }
}

// === 6. Debounce supaya tidak spam request saat user drag/zoom cepat ===
let debounceTimer;
map.on('moveend', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(muatDataViewport, 300);
});

// Load data pertama kali
muatDataViewport();
</script>
@endpush
```

---

## 🧪 LANGKAH 6 — Testing Checklist

Jangan skip bagian ini — ini yang menentukan apakah peta beneran ringan atau cuma "kelihatan" ringan pas data masih sedikit.

- [ ] **Zoom out penuh** (lihat seluruh Kutim) → cek di Network tab browser, response `/api/umkm/viewport` harus di bawah 500 items, waktu respon < 1 detik
- [ ] **Zoom in ke satu kecamatan** → marker individual muncul, klik popup → info benar sesuai database
- [ ] **Drag peta cepat berkali-kali** → tidak ada request menumpuk/lag (debounce bekerja)
- [ ] **Coba geser ke luar Kutim** → peta harus "mental balik" (maxBounds bekerja)
- [ ] **Test di Chrome DevTools → Network → Throttling → Slow 3G** → simulasi HP jadul, pastikan tetap usable meski lambat
- [ ] **Cek jumlah marker on-screen** (di Elements/Inspect) tidak pernah lebih dari beberapa ratus DOM node kapanpun
- [ ] **Scale-up data**: ganti seeder ke 20.000-45.000 dummy, ulangi semua test di atas — pastikan query masih < 1-2 detik (kalau lambat, cek apakah `SPATIAL INDEX` benar-benar terpasang: `SHOW INDEX FROM umkm;`)

---

## 🐛 TROUBLESHOOTING UMUM

| Masalah | Kemungkinan Penyebab | Solusi |
|---|---|---|
| Query `/api/umkm/viewport` lambat (>2 detik) di data besar | Spatial index tidak terpasang/tidak kepakai | Cek `EXPLAIN` query, pastikan `SPATIAL INDEX idx_location` ada & digunakan |
| Marker tidak muncul sama sekali | Format `POINT` salah (urutan lng/lat tertukar) | MySQL `POINT(x, y)` = `POINT(longitude, latitude)` — sering ketuker! |
| Peta blank/putih | CSS Leaflet belum ke-load atau container `#map` tidak punya height | Pastikan `<link>` CSS ada di `<head>`, dan div `#map` punya `height` eksplisit |
| Cluster tidak update saat zoom | `markers.clearLayers()` lupa dipanggil sebelum render ulang | Cek urutan kode di `muatDataViewport()` |
| Request menumpuk saat drag cepat | Debounce tidak jalan/timer salah | Pastikan `clearTimeout` dipanggil sebelum `setTimeout` baru |
| Data di luar Kutim ikut muncul | Boundary di Langkah 0 belum akurat, atau data sumber punya baris di luar Kutim | Validasi ulang bounding box, tambahkan filter `kecamatan IN (...)` di query sebagai lapisan kedua |

---

## 📌 Setelah Fitur Ini Selesai

Lanjut ke:
- Filter kategori (tambahkan parameter `kategori_id` ke endpoint viewport)
- Link marker popup ke halaman **Detail Usaha**
- Custom icon marker per kategori (opsional, kalau waktu masih ada)

Jangan dulu kerjakan efek visual "gelap di luar boundary Kutim" (GeoJSON + Turf.js) — itu di Fase 2, bukan blocker untuk MVP.

---

**File ini berdiri sendiri** — dipakai langsung untuk sesi ngoding fitur Peta, tanpa perlu bolak-balik ke dokumen project utama.
