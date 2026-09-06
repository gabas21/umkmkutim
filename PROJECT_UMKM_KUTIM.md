# 📱 WEB UMKM KUTIM — PROJECT BRIEFING (REVISED)

**Status:** Pre-Development — Final Draft  
**Scale:** 14.000 → 45.000 data UMKM  
**Konteks:** Proyek klien (freelance/kontrak)  
**Deadline:** < 1 bulan ⚠️ **SANGAT KETAT**  
**Budget infra:** Kecil (~Rp50-150rb/bulan)

---

## ⚠️ CATATAN PENTING SEBELUM MULAI

Deadline < 1 bulan untuk scope 7 halaman + sistem klaim usaha + verifikasi admin + dua jenis laporan + map 45rb titik data itu **realistis hanya kalau MVP-nya ketat**. Draft ini sudah disusun dengan asumsi:

1. **Fase 1 (MVP, ~3-4 minggu)** = fitur yang WAJIB ada saat serah terima ke klien.
2. **Fase 2 (Backlog)** = fitur yang bisa menyusul setelah delivery pertama, dinego terpisah.

Kalau di tengah jalan terasa ngaret, **potong dari Fase 2 dulu**, bukan dari kualitas fondasi (database, auth, security). Saran saya: komunikasikan ke klien di awal bahwa delivery pertama adalah versi fungsional inti, bukan seluruh fitur sekaligus — ini standar praktik, bukan tanda gagal.

---

## 📋 RINGKASAN KEPUTUSAN KUNCI

| Keputusan | Pilihan | Alasan |
|---|---|---|
| Database | **MySQL 8+** (bukan PostgreSQL) | Kamu sudah expert, hosting lebih fleksibel (Hostinger/Railway), spatial index MySQL 8 cukup untuk skala ini |
| Sumber data | **Import awal (Excel/CSV Dinas) + self-update via klaim** | UMKM bisa "mengklaim" data yang sudah diimport, bukan daftar dobel |
| Verifikasi klaim | **Manual oleh admin** (upload KTP/bukti usaha → approve/reject) | Wajib ada supaya data tidak asal-asalan diklaim orang lain |
| Laporan | **Dua jenis**: (1) analytics per-UMKM untuk pelaku usaha, (2) rekap wilayah untuk admin/Dinas | Sesuai kebutuhan dua audiens berbeda |
| Map | **Leaflet + clustering + custom styling** | Ringan, gratis, familiar, cukup "keren" dengan styling yang tepat |
| Hosting | **Railway (app+DB) atau Hostinger VPS kecil** | Sesuai budget kecil |

---

## 🛠️ TECH STACK (FINAL)

### Frontend
```
- HTML5 + Tailwind CSS
- Alpine.js (interaktivitas ringan)
- Leaflet.js + Leaflet.MarkerCluster + Leaflet.heat
- Axios
```

### Backend
```
- Laravel 11 + Livewire
- MySQL 8+ (dengan spatial data type native)
- Redis (caching — kalau budget mepet, bisa pakai file/database cache dulu, Redis menyusul)
```

### Infrastruktur
```
- Railway ATAU Hostinger VPS (pilih salah satu sesuai budget final)
- Storage foto: local disk dulu (hemat), pindah ke S3/Cloudflare R2 kalau traffic naik
```

---

## 💾 DATABASE SCHEMA (MySQL 8+ — Syntax Diperbaiki & Divalidasi)

### Table: `umkm`
```sql
CREATE TABLE umkm (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_usaha VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  kategori_id INT UNSIGNED NOT NULL,
  deskripsi TEXT NULL,
  alamat TEXT NOT NULL,
  kecamatan VARCHAR(100) NOT NULL,      -- penting untuk rekap laporan wilayah
  kelurahan_desa VARCHAR(100) NULL,
  location POINT SRID 4326 NOT NULL,     -- MySQL 8 spatial type
  telepon VARCHAR(20) NULL,
  email VARCHAR(100) NULL,
  instagram VARCHAR(100) NULL,
  website VARCHAR(255) NULL,
  foto_utama VARCHAR(255) NULL,
  foto_galeri JSON NULL,                 -- array of URLs
  jam_operasional JSON NULL,
  rating DECIMAL(3,2) DEFAULT 0,
  jumlah_review INT UNSIGNED DEFAULT 0,
  jumlah_dilihat INT UNSIGNED DEFAULT 0, -- counter sederhana untuk analytics
  sumber_data ENUM('import', 'mandiri') DEFAULT 'import',
  status_klaim ENUM('belum_diklaim', 'menunggu_verifikasi', 'terverifikasi') DEFAULT 'belum_diklaim',
  status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  SPATIAL INDEX idx_location (location),
  INDEX idx_kategori (kategori_id),
  INDEX idx_kecamatan (kecamatan),
  INDEX idx_status_klaim (status_klaim),
  CONSTRAINT fk_umkm_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);
```
> Catatan: kolom `location` pakai tipe `POINT SRID 4326`. Query jarak pakai `ST_Distance_Sphere()`, bukan PostGIS `ST_DWithin`.

### Table: `kategori`
```sql
CREATE TABLE kategori (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  icon VARCHAR(50) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Table: `pelaku_usaha` (akun user pemilik usaha)
```sql
CREATE TABLE pelaku_usaha (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  nomor_telepon VARCHAR(20) NULL,
  email_verified_at TIMESTAMP NULL,
  status ENUM('active', 'pending', 'banned') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_email (email)
);
```
> Satu akun pelaku usaha bisa memiliki >1 UMKM (misal owner beberapa cabang) → makanya relasinya lewat tabel pivot `klaim_usaha`, bukan kolom `umkm_id` langsung di tabel ini.

### Table: `klaim_usaha` (INTI dari alur verifikasi — fitur paling kritis)
```sql
CREATE TABLE klaim_usaha (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  umkm_id BIGINT UNSIGNED NOT NULL,
  pelaku_usaha_id BIGINT UNSIGNED NOT NULL,
  dokumen_ktp VARCHAR(255) NOT NULL,        -- path file upload
  dokumen_bukti_usaha VARCHAR(255) NULL,    -- misal foto plang/NIB, opsional
  catatan_pemohon TEXT NULL,
  status ENUM('menunggu', 'disetujui', 'ditolak') DEFAULT 'menunggu',
  catatan_admin TEXT NULL,
  diverifikasi_oleh BIGINT UNSIGNED NULL,   -- FK ke admin
  diverifikasi_pada TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_status (status),
  INDEX idx_umkm (umkm_id),
  CONSTRAINT fk_klaim_umkm FOREIGN KEY (umkm_id) REFERENCES umkm(id),
  CONSTRAINT fk_klaim_pelaku FOREIGN KEY (pelaku_usaha_id) REFERENCES pelaku_usaha(id)
);
```
**Alur:** UMKM cari nama usaha → klik "Klaim Usaha Ini" → upload KTP + (opsional) bukti usaha → masuk status `menunggu` → admin review di dashboard admin → approve (→ `umkm.status_klaim = terverifikasi`, pelaku usaha dapat akses edit) atau reject (dengan catatan alasan, pemohon bisa upload ulang).

### Table: `berita`
```sql
CREATE TABLE berita (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  konten LONGTEXT NOT NULL,
  thumbnail VARCHAR(255) NULL,
  kategori ENUM('berita', 'pengumuman', 'tips') DEFAULT 'berita',
  status ENUM('draft', 'published') DEFAULT 'draft',
  published_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_published (published_at),
  INDEX idx_kategori (kategori)
);
```

### Table: `layanan_usaha` (hanya untuk UMKM yang sudah terverifikasi)
```sql
CREATE TABLE layanan_usaha (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  umkm_id BIGINT UNSIGNED NOT NULL,
  nama_layanan VARCHAR(255) NOT NULL,
  deskripsi TEXT NULL,
  harga_mulai DECIMAL(15,2) NULL,
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_umkm (umkm_id),
  CONSTRAINT fk_layanan_umkm FOREIGN KEY (umkm_id) REFERENCES umkm(id)
);
```

### Table: `review`
```sql
CREATE TABLE review (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  umkm_id BIGINT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,   -- 1-5, divalidasi di aplikasi (Laravel rule)
  komentar TEXT NULL,
  nama_reviewer VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_umkm (umkm_id),
  CONSTRAINT fk_review_umkm FOREIGN KEY (umkm_id) REFERENCES umkm(id),
  CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)
);
```

### Table: `laporan_kunjungan` (data mentah untuk kedua jenis laporan)
```sql
CREATE TABLE laporan_kunjungan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  umkm_id BIGINT UNSIGNED NOT NULL,
  tanggal DATE NOT NULL,
  jumlah_dilihat INT UNSIGNED DEFAULT 0,   -- agregat harian, bukan log per-klik (hemat storage)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE KEY uq_umkm_tanggal (umkm_id, tanggal),
  INDEX idx_tanggal (tanggal),
  CONSTRAINT fk_kunjungan_umkm FOREIGN KEY (umkm_id) REFERENCES umkm(id)
);
```
> **Laporan pelaku usaha** = query `laporan_kunjungan` per `umkm_id` (grafik views harian/bulanan).  
> **Laporan admin/Dinas** = query agregat `umkm` + `laporan_kunjungan` di-`GROUP BY kecamatan` (jumlah UMKM, kategori terbanyak, rata-rata views per wilayah).

### Contoh Query Nearby (MySQL 8, bukan PostGIS)
```sql
SELECT id, nama_usaha, kecamatan,
  ST_Distance_Sphere(location, POINT(:lng, :lat)) AS jarak_meter
FROM umkm
WHERE status = 'active'
  AND ST_Distance_Sphere(location, POINT(:lng, :lat)) <= 5000
ORDER BY jarak_meter
LIMIT 50;
```

---

## 🎯 HALAMAN & FITUR — DIBAGI FASE

### ✅ FASE 1 — MVP (Target: 3-4 Minggu)

| # | Halaman | Fitur Inti yang WAJIB Ada |
|---|---------|---------------------------|
| 1 | **Halaman Depan** | Hero + search, kategori grid, preview map, UMKM unggulan, berita terbaru (versi statis dulu, tanpa personalisasi rumit) |
| 2 | **Peta** | Leaflet + clustering, filter kategori dasar, popup info singkat → link ke detail |
| 3 | **Detail Usaha** | Info lengkap, foto, kontak, tombol "Klaim Usaha Ini" (kalau belum diklaim), review sederhana |
| 4 | **Pendaftaran/Klaim** | Form klaim (bukan pendaftaran baru dari nol) + upload dokumen, form pendaftaran UMKM baru (kalau memang belum ada di data import) |
| 5 | **Pelaku Usaha (Dashboard dasar)** | Login, edit profil UMKM sendiri (setelah diverifikasi), lihat laporan kunjungan sederhana (angka + grafik basic) |
| 6 | **Admin Panel (minimal)** | Approve/reject klaim, CRUD kategori, moderasi data UMKM |
| 7 | **Berita** | List + detail, CMS sederhana untuk admin |

**Portal Layanan** → **digeser ke Fase 2** (lihat alasan di bawah). Alasan: sistem klaim + verifikasi + dashboard dasar saja sudah signifikan effort-nya untuk timeline < 1 bulan. Portal layanan butuh CRUD tambahan + UI marketplace yang kalau dipaksakan akan mengorbankan kualitas fitur inti (klaim & verifikasi, yang justru paling sensitif karena menyangkut data kepemilikan usaha).

---

### 🔜 FASE 2 — Backlog (Setelah MVP Delivery)

- Portal Layanan (marketplace jasa antar-UMKM)
- Laporan admin/Dinas versi lengkap (export PDF, filter tanggal custom, visualisasi peta choropleth per kecamatan)
- Analytics lanjutan pelaku usaha (perbandingan periode, insight otomatis)
- Notifikasi email otomatis (klaim disetujui/ditolak, review baru)
- SEO lanjutan (structured data, sitemap dinamis)
- PWA / offline fallback

---

## 📊 API ENDPOINTS UTAMA (Fase 1)

```
# Public
GET    /api/umkm                      → list + filter kategori/kecamatan
GET    /api/umkm/{slug}                → detail
GET    /api/umkm/nearby                → ST_Distance_Sphere query
GET    /api/umkm/search                → pencarian nama/kategori
GET    /api/kategori
GET    /api/berita
GET    /api/berita/{slug}
POST   /api/umkm/{id}/review

# Klaim Usaha
POST   /api/klaim                      → ajukan klaim + upload dokumen
GET    /api/klaim/status/{id}          → cek status klaim milik sendiri

# Auth
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout

# Dashboard Pelaku Usaha (auth required, hanya UMKM terverifikasi)
GET    /api/dashboard/laporan          → data laporan_kunjungan milik sendiri
PUT    /api/umkm/{id}                  → update profil (hanya milik sendiri)

# Admin (auth + role admin)
GET    /api/admin/klaim                → list klaim menunggu verifikasi
PUT    /api/admin/klaim/{id}/approve
PUT    /api/admin/klaim/{id}/reject
GET    /api/admin/laporan/wilayah      → rekap per kecamatan
POST   /api/admin/umkm/import          → import Excel/CSV data Dinas
```

---

## ⚡ OPTIMASI (Tetap Prioritas Meski Deadline Ketat)

Jangan skip ini walau waktu mepet — justru ini yang bikin web tidak lag di HP jadul:

- [ ] Spatial index di kolom `location` (wajib, tanpa ini query nearby akan lambat di 45rb data)
- [ ] Pagination semua list endpoint (max 50/request)
- [ ] Marker clustering di Leaflet (jangan render 45rb marker mentah-mentah)
- [ ] Lazy load gambar
- [ ] Cache hasil query berat (kategori, featured UMKM) — pakai database cache dulu kalau belum sempat setup Redis
- [ ] Import data Dinas via job queue (Laravel Queue), jangan proses 45rb baris secara synchronous di request HTTP (bakal timeout)

---

## 📈 ROADMAP MINGGUAN (Realistis untuk < 1 Bulan)

### Minggu 1 — Fondasi
```
[ ] Setup Laravel + MySQL + migration semua tabel
[ ] Import script data Dinas (Excel/CSV → database, via queue job)
[ ] Auth dasar (pelaku usaha + admin)
[ ] Halaman depan (versi statis)
```

### Minggu 2 — Fitur Inti
```
[ ] Halaman peta + clustering + filter dasar
[ ] Halaman detail usaha
[ ] Form klaim usaha + upload dokumen
[ ] Admin panel: approve/reject klaim
```

### Minggu 3 — Dashboard & Konten
```
[ ] Dashboard pelaku usaha (edit profil, lihat laporan dasar)
[ ] Modul berita (CRUD admin + tampilan publik)
[ ] Review sederhana
[ ] Testing di koneksi lambat + HP jadul
```

### Minggu 4 — Buffer & Deploy
```
[ ] Bug fixing
[ ] Deploy ke Railway/Hostinger
[ ] Handover dokumentasi ke klien
[ ] Sisakan waktu untuk revisi klien (jangan pakai minggu ke-4 penuh untuk fitur baru)
```

> ⚠️ Kalau di akhir Minggu 2 progress meleset dari checklist di atas, segera komunikasi ke klien untuk perpanjang deadline atau resmikan Portal Layanan masuk Fase 2 — jangan tunggu sampai minggu terakhir baru bilang.

---

## 💻 SETUP LOKAL

```bash
composer create-project laravel/laravel umkm-kutim
cd umkm-kutim
php artisan install:api   # jika pakai Laravel Sanctum untuk API auth

# .env
DB_CONNECTION=mysql
DB_DATABASE=umkm_kutim
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate
npm install && npm run dev
php artisan serve
```

---

## 📝 KEPUTUSAN & ALASAN (Updated)

1. **MySQL, bukan PostgreSQL** — kamu sudah expert, MySQL 8+ punya spatial type native yang cukup untuk radius search di skala 45rb baris, dan hosting lebih fleksibel untuk budget kecil.
2. **Klaim, bukan daftar ulang** — mencegah data dobel dari 14-45rb data import Dinas; alur klaim + verifikasi dokumen adalah fitur paling sensitif jadi masuk prioritas Fase 1.
3. **Portal Layanan digeser ke Fase 2** — supaya fitur inti (klaim, verifikasi, map, dashboard dasar) tidak dikompromikan kualitasnya demi mengejar semua fitur sekaligus dalam < 1 bulan.
4. **`laporan_kunjungan` diagregat harian**, bukan log per-klik — jauh lebih hemat storage untuk 45rb UMKM dan tetap cukup untuk kedua jenis laporan (pelaku usaha & admin/Dinas).

---

**Terakhir diperbarui:** September 2026  
**Status:** Siap mulai development — mulai dari Minggu 1 di atas ✅
