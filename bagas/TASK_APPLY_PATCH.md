# TASK: Terapkan Patch Performa Peta UMKM

## Konteks
Folder `bagas/` berisi patch production-ready untuk memperbaiki 4 bug (2 critical, 2 medium) pada sistem peta UMKM Kutim. Semua file patch sudah siap — tugasmu hanya **menyalin, menggantikan file yang tepat, dan menjalankan perintah** di bawah ini secara berurutan.

Baca `bagas/ANALISIS_DAN_PATCH_MAPPING_UMKM.md` untuk memahami konteks teknis setiap patch sebelum mengerjakannya.

---

## Checklist Tugas

### STEP 1 — Ganti `PetaController.php`
**Bug yang di-fix:** Dead query full-table-scan di setiap page load `/peta` yang mengambil semua UMKM aktif ke memory PHP padahal datanya tidak pernah dipakai di view (data peta 100% dari AJAX).

- **Sumber patch:** `bagas/PetaController.php`
- **Tujuan:** `app/Http/Controllers/PetaController.php`

**Yang harus dilakukan:**
1. Buka `app/Http/Controllers/PetaController.php` (file asli)
2. Bandingkan dengan `bagas/PetaController.php`
3. Timpa `app/Http/Controllers/PetaController.php` dengan isi dari `bagas/PetaController.php`
4. Pastikan namespace, use statements, dan semua import tetap benar

---

### STEP 2 — Ganti `UmkmClusterController.php`
**Bug yang di-fix:**
- Cabang filter aktif tidak punya LIMIT -> bisa narik puluhan ribu baris ke PHP
- Cache key tidak terhubung ke versi data -> data baru tidak langsung muncul setelah refresh

- **Sumber patch:** `bagas/UmkmClusterController.php`
- **Tujuan:** `app/Http/Controllers/Api/UmkmClusterController.php`

**Yang harus dilakukan:**
1. Buka `app/Http/Controllers/Api/UmkmClusterController.php` (file asli)
2. Bandingkan dengan `bagas/UmkmClusterController.php`
3. Timpa `app/Http/Controllers/Api/UmkmClusterController.php` dengan isi dari `bagas/UmkmClusterController.php`
4. Pastikan namespace, use statements, dan semua import tetap benar

---

### STEP 3 — Ganti `RefreshGridCluster.php`
**Bug yang di-fix:** Setelah `umkm:refresh-grid` jalan, response cache lama masih ke-serve sampai TTL 10 menit habis — user melihat data yang belum terupdate.

- **Sumber patch:** `bagas/RefreshGridCluster.php`
- **Tujuan:** `app/Console/Commands/RefreshGridCluster.php`

**Yang harus dilakukan:**
1. Buka `app/Console/Commands/RefreshGridCluster.php` (file asli)
2. Bandingkan dengan `bagas/RefreshGridCluster.php`
3. Timpa `app/Console/Commands/RefreshGridCluster.php` dengan isi dari `bagas/RefreshGridCluster.php`
4. Pastikan namespace, use statements, dan semua import tetap benar

---

### STEP 4 — Ganti `ImportDinasUmkmCommand.php`
**Bug yang di-fix:** Mode `--sync` tidak otomatis refresh grid cluster setelah import selesai, sehingga data baru di peta baru muncul setelah jadwal cron 1 jam berjalan.

- **Sumber patch:** `bagas/ImportDinasUmkmCommand.php`
- **Tujuan:** `app/Console/Commands/ImportDinasUmkmCommand.php`

**Yang harus dilakukan:**
1. Buka `app/Console/Commands/ImportDinasUmkmCommand.php` (file asli)
2. Bandingkan dengan `bagas/ImportDinasUmkmCommand.php`
3. Timpa `app/Console/Commands/ImportDinasUmkmCommand.php` dengan isi dari `bagas/ImportDinasUmkmCommand.php`
4. Pastikan namespace, use statements, dan semua import tetap benar

---

### STEP 5 — Copy Migration Baru
**Bug yang di-fix:** Kolom `status` pada tabel `umkm` tidak punya index, padahal `Umkm::active()` dipanggil di hampir semua query -> full table scan tiap request.

- **Sumber patch:** `bagas/2026_09_08_000001_add_umkm_performance_indexes.php`
- **Tujuan:** `database/migrations/2026_09_08_000001_add_umkm_performance_indexes.php`

**Yang harus dilakukan:**
1. Salin file `bagas/2026_09_08_000001_add_umkm_performance_indexes.php` ke folder `database/migrations/`
2. Jangan modifikasi isi file migration — sudah final

---

### STEP 6 — Jalankan Perintah Artisan

Setelah semua file di-copy, jalankan perintah berikut secara berurutan:

```
php artisan migrate
php artisan cache:clear
php artisan umkm:refresh-grid
```

---

## Verifikasi Setelah Patch

1. Jalankan `php artisan about` atau buka `/peta` di browser — pastikan tidak ada error
2. Buka `/peta`, cek Network tab — tidak ada request besar selain ke `/api/umkm/clusters`
3. Search kata umum "toko" di zoom rendah — response JSON dari `/api/umkm/clusters` harusnya ada `"truncated": true` jika data melebihi 8000 baris
4. Jalankan `php artisan umkm:refresh-grid` lalu langsung hit `/api/umkm/clusters` — response harusnya fresh

---

## Catatan Penting

- TIDAK ADA perubahan di frontend — semua patch ini murni backend. File `resources/views/peta/index.blade.php` TIDAK perlu disentuh.
- Jika file asli tidak ditemukan (misal belum ada), buat file baru di path tujuan dengan isi dari `bagas/`.
- Kolom `nama_usaha`/`alamat` masih pakai `LIKE '%keyword%'` (sengaja tidak diubah di patch ini karena mengubah semantik pencarian). Ini bisa jadi improvement terpisah dengan FULLTEXT INDEX.

---

## Summary File yang Perlu Diubah

| No | File Sumber (bagas/)                                   | File Tujuan                                              | Aksi  |
|----|--------------------------------------------------------|----------------------------------------------------------|-------|
| 1  | PetaController.php                                     | app/Http/Controllers/PetaController.php                  | Timpa |
| 2  | UmkmClusterController.php                              | app/Http/Controllers/Api/UmkmClusterController.php       | Timpa |
| 3  | RefreshGridCluster.php                                 | app/Console/Commands/RefreshGridCluster.php              | Timpa |
| 4  | ImportDinasUmkmCommand.php                             | app/Console/Commands/ImportDinasUmkmCommand.php          | Timpa |
| 5  | 2026_09_08_000001_add_umkm_performance_indexes.php     | database/migrations/                                     | Copy  |
