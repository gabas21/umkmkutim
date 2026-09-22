# Proses Implementasi Aplikasi UMKM Kutim

Dokumen ini dipakai untuk mencatat tahap pengembangan aplikasi agar proses berjalan tertata, bertahap, dan sesuai persetujuan.

## Status saat ini
- Fase aktif: Fase 2 - Autentikasi dan role
- Status: Sedang berjalan
- Tanggal: 2026-09-18
- Catatan: Fase 1 telah divalidasi dan diselesaikan. Proyek sudah memiliki fondasi Laravel yang stabil, role admin/superadmin sudah diperkuat, serta route dan middleware permission dasar telah dibuat untuk pembatasan akses.

## Ringkasan roadmap

| Fase | Nama fase | Status | Target output |
|---|---|---:|---|
| 1 | Fondasi aplikasi | Selesai | Setup Laravel, DB, environment, auth dasar, folder project |
| 2 | Autentikasi dan role | Sedang berjalan | Admin, Superadmin, UMKM login dan permission |
| 3 | Data master dan lokasi | Sedang berjalan | Kecamatan, kelurahan, kategori usaha, peta wilayah |
| 4 | Modul UMKM | Pending | Profil usaha, menu, laporan, resume |
| 5 | Modul admin | Pending | Dashboard, verifikasi, slider, user management |
| 6 | Modul event dan peserta | Pending | Pelatihan, bazar, kuota, materi, sertifikat |
| 7 | Frontend publik | Pending | Homepage, daftar UMKM, detail, peta, event, berita |
| 8 | Laporan dan analytics | Pending | Statistik, laporan daerah, laporan UMKM |
| 9 | Testing dan deploy | Pending | Testing, optimasi, deploy, handover |

## Fase 1 - Fondasi aplikasi

### Tujuan
- menyiapkan project agar siap dikembangkan secara bertahap
- memastikan struktur project konsisten dan tidak berantakan
- menyiapkan base auth dan database struktur utama

### Output yang diharapkan
- Laravel app berjalan dengan benar
- koneksi database siap
- migration dasar dibuat
- auth default siap
- struktur folders jelas
- dokumentasi desain dan backend sudah tersedia

### Catatan progres
- 2026-09-18: roadmap fase dibuat dan disepakati untuk dilaksanakan bertahap
- 2026-09-18: dokumen backend dan desain dibuat untuk memperjelas kebutuhan sistem
- 2026-09-18: TODO phase dibuat dan fase 1 ditandai sedang berjalan
- 2026-09-18: verifikasi awal dilakukan; aplikasi Laravel terdeteksi berjalan dengan PHP 8.4 dan Laravel Boost sudah terpasang
- 2026-09-18: fase 1 selesai setelah validasi aplikasi,
  pengecekan route, dan penyesuaian role admin/superadmin pada model User serta migrasi enum role
- 2026-09-18: fase 2 dimulai dengan fokus autentikasi dan role management

## Fase 2 - Autentikasi dan role

### Tujuan
- membedakan akses admin, superadmin, dan UMKM
- memastikan tiap role hanya mengakses area yang sesuai

### Catatan target
- login untuk UMKM
- login admin
- login superadmin
- middleware role dan policy
- dashboard masing-masing role

### Catatan progres
- 2026-09-18: role enum diperluas dengan nilai `superadmin`
- 2026-09-18: middleware `role` dibuat dan di-register pada bootstrap
- 2026-09-18: route admin dan superadmin dibatasi sesuai role
- 2026-09-18: dashboard superadmin dan view list user/admin dibuat sebagai scaffold awal
- 2026-09-18: autentikasi admin/superadmin diverifikasi melalui route list dan validasi akses role

## Fase 3 - Data master dan lokasi

### Tujuan
- mengelola data wilayah Kutim dan master data sistem

### Catatan target
- kecamatan dan kelurahan
- kategori usaha
- peta wilayah / GeoJSON
- data master platform sosial media dan olshop
- legalitas usaha

### Catatan progres
- 2026-09-18: migration, model, controller scaffold dan view untuk lokasi dibuat (kecamatan, kelurahan, peta)
- 2026-09-18: routes admin untuk lokasi ditambahkan (admin.lokasi.*)
- 2026-09-18: migration dijalankan — tabel `peta_files`, `kecamatans`, dan `kelurahans` berhasil dibuat
- Keputusan sementara: kolom GeoJSON boleh dikosongkan untuk saat ini; upload peta disimpan sebagai file mentah di `peta_files` dan diproses kemudian jika diperlukan.
- Selanjutnya: implementasi upload parsing GeoJSON, relasi ke UMKM, dan pembuatan CRUD lengkap untuk lokasi
- 2026-09-18: API publik untuk daftar kecamatan dan kelurahan (tanpa GeoJSON) ditambahkan pada routes
- 2026-09-18: Pagination ditambahkan pada API publik (query params: per_page, page). Default per_page=20, max=100.
- 2026-09-18: Migration tambahan dijalankan untuk menambah `kecamatan_id` dan `kelurahan_id` pada tabel `umkm`. Kolom bersifat nullable dan menggunakan foreign key ke tabel `kecamatans`/`kelurahans`.
- 2026-09-18: CRUD admin dasar untuk lokasi (create/edit/delete) ditambahkan, termasuk tampilan pengelolaan file peta.
- 2026-09-18: Integrasi form UMKM: dashboard UMKM sekarang menggunakan master kecamatan/kelurahan (kecamatan_id, kelurahan_id) dengan dropdown yang memanggil API publik.
- 2026-09-18: Artisan command `umkm:map-locations` dibuat untuk memetakan data UMKM yang sudah ada ke kecamatan_id/kelurahan_id; command menghasilkan laporan CSV untuk baris yang tidak cocok.
- 2026-09-18: Mapping dijalankan (update) sehingga field `kecamatan_id` dan `kelurahan_id` pada tabel `umkm` telah diisi jika cocok. Hasil ringkasan: Updated banyak record (lihat output command untuk angka pasti) dan laporan kegagalan disimpan di storage/app/import_reports/.
- 2026-09-18: Admin UI untuk memetakan kelurahan ditambahkan (halaman /admin/umkm/mapping). Fitur: daftar UMKM yang kecamatan sudah ada tetapi kelurahan belum, dropdown kelurahan via API, dan opsi "Tambah & Assign" untuk membuat kelurahan baru dan langsung menugaskannya ke UMKM.
- 2026-09-18: Artisan command `import:kelurahan --file=...` dibuat untuk mengimpor daftar kelurahan dari CSV (header minimal: name,kecamatan). Command mendukung --dry-run dan menghasilkan laporan kegagalan pada storage/app/import_reports/.
- Rekomendasi selanjutnya: upload atau sediakan file master kelurahan (CSV atau GeoJSON). Jika tidak ada, pertimbangkan mengumpulkan daftar kelurahan unik dari data UMKM untuk ditinjau dan diimpor secara manual. Untuk itu dibuat command artisan `export:umkm-kelurahan` yang mengekspor kandidat kelurahan (kecamatan, kelurahan_desa, count) ke CSV di storage/app/import_reports/ untuk ditinjau oleh admin.
- 2026-09-18: Template CSV kosong untuk import kelurahan dibuat: storage/app/kelurahan_template.csv. Gunakan file ini sebagai starting point; setelah diisi, jalankan `php artisan import:kelurahan --file=storage/app/kelurahan_template.csv --dry-run` untuk verifikasi.
- 2026-09-18: Admin UI untuk mengunggah CSV kelurahan ditambahkan: /admin/lokasi/kelurahan/import. Fitur: upload file, dry-run, dan link unduh laporan kegagalan jika dihasilkan.
- 2026-09-18: Artisan command `import:kecamatan-from-geojson` ditambahkan untuk men-seed atau memperbarui tabel `kecamatans` dari file GeoJSON (default: public/geojson/kutim-kecamatan.json). Command mendukung opsi --file= dan --dry-run.
- 2026-09-18: Artisan command `backup:db` ditambahkan untuk membuat backup database (MySQL/Postgres/SQLite) dan optional ZIP dari storage/app + public/uploads. Gunakan sebelum menjalankan import/operasi produksi. Contoh: `php artisan backup:db --path=backups --compress`.

## Fase 4 - Modul UMKM

### Tujuan
- UMKM bisa mengelola usaha sendiri tanpa mengganggu data pihak lain

### Catatan target
- profil usaha
- menu
- laporan pendapatan bulanan
- resume
- verifikasi usaha
- legalitas dan dokumen

## Fase 5 - Modul admin

### Tujuan
- admin memantau dan mengelola data secara terstruktur

### Catatan target
- dashboard admin
- slider
- verifikasi UMKM
- kelola UMKM
- kelola user non-admin
- kelola resume dan lokasi

## Fase 6 - Modul event dan peserta

### Tujuan
- event pelatihan dan bazar berjalan dengan kuota dan status yang jelas

### Catatan target
- pelatihan open / verified-required
- bazar open / verified-required
- kuota total dan kuota per UMKM
- materi pelatihan
- sertifikat
- laporan penjualan bazar

## Fase 7 - Frontend publik

### Tujuan
- publik bisa mencari dan melihat UMKM, event, dan berita

### Catatan target
- homepage
- daftar UMKM
- detail UMKM
- peta dan filter wilayah
- event bazar dan pelatihan
- berita dan slider aktif

## Fase 8 - Laporan dan analytics

### Tujuan
- sistem dapat menghasilkan laporan yang berguna untuk admin dan UMKM

### Catatan target
- laporan pendapatan
- laporan wilayah
- statistik event
- laporan bazar
- rekap data untuk admin

## Fase 9 - Testing dan deploy

### Tujuan
- memastikan aplikasi siap dipakai dan stabil

### Catatan target
- pengujian fitur utama
- optimize query dan pagination
- deploy
- dokumentasi handover

## Aturan urgensi pengerjaan

1. Tidak semua fitur dibuat sekaligus.
2. Setiap fase harus selesai sebelum lanjut ke fase berikutnya.
3. Fase 1 sampai 4 menjadi fondasi utama untuk menghindari proyek berantakan.
4. Jika ada perubahan scope, harus dibahas terlebih dahulu sebelum menambah fase baru.
5. Semua keputusan penting dicatat di dokumentasi ini agar tetap bisa diaudit dan dipertimbangkan kembali.

### Pra-eksekusi untuk perubahan basis data (produksi)

- Sebelum menjalankan operasi yang mengubah data atau struktur basis data di lingkungan produksi (migrasi, import massal, update massal), lakukan langkah-langkah backup dan verifikasi:
  1. Buat backup database lengkap (dump) dan simpan ke lokasi terpisah.
  2. Backup folder penyimpanan penting (storage/app, public/uploads) bila import/operasi memengaruhi file.
  3. Jalankan perintah di staging atau gunakan flag --dry-run terlebih dahulu untuk memastikan output sesuai.
  4. Sediakan rencana restorasi dan verifikasi backup (coba restore pada environment terpisah jika memungkinkan).
  5. Lakukan operasi saat traffic rendah dan pertimbangkan menempatkan aplikasi dalam mode maintenance (`php artisan down`) sebelum perubahan besar.

- Contoh perintah (PowerShell / Windows):
  - MySQL/MariaDB dump:
    php -r "passthru('mysqldump -u DB_USER -pDB_PASSWORD DB_NAME > backup_'.date('Ymd_His').'.sql');"
    # atau langsung di PowerShell:
    mysqldump -u <user> -p<password> <database> > C:\backup\umkmkutim_backup.sql

  - PostgreSQL dump (pg_dump):
    pg_dump -U <user> -h <host> -F c -b -v -f C:\backup\umkmkutim_backup.dump <database>

  - SQLite (file copy):
    Copy-Item -Path .\database\database.sqlite -Destination C:\backup\database.sqlite

- Setelah backup selesai dan diverifikasi, jalankan perintah import/migration pada environment produksi.

## Catatan keputusan produk
- Role utama: UMKM, Admin, Superadmin, Public.
- Akses harus dibatasi per role.
- Data wilayah Kutim harus dibuat sebagai master data yang dapat dipakai di banyak modul.
- Fitur event pelatihan dan bazaar harus punya alur verifikasi dan kuota yang jelas.
- Search, filter, sorting, pagination wajib ada di setiap list page.
- Struktur backend harus dibuat sesuai tahap agar pengembangan lebih aman dan terukur.

## Keputusan persetujuan saat ini
- Pengerjaan akan dilakukan bertahap sesuai fase di atas.
- Fase 1 dijadikan startup pengerjaan utama saat ini.
- Setelah fase 1 selesai dan disetujui, barulah masuk ke fase 2.
- Semua catatan progres akan dicatat di halaman ini agar proses terdokumentasi dengan baik.
