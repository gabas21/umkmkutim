# Spesifikasi Backend UMKM dan Role Akses

## 1. Struktur role user

Sistem memiliki 4 role utama:

- UMKM
  - user yang memiliki usaha dan mengelola data usaha miliknya sendiri
  - bisa login ke dashboard UMKM
  - bisa mengelola profil usaha, menu, pendapatan, resume, pendaftaran pelatihan/bazaar
  - tidak bisa mengelola data usaha orang lain

- Public / Konsumen
  - user yang tidak login
  - hanya bisa melihat halaman publik seperti daftar UMKM, detail UMKM, menu, resume, slider, dan event pelatihan/bazaar
  - tidak bisa mengedit data apa pun

- Admin
  - user yang mengelola data operasional sistem
  - bisa mengelola slider, verifikasi UMKM, data UMKM, user non-admin, dan resume
  - tidak bisa mengelola akun superadmin

- Superadmin
  - user dengan akses penuh ke sistem
  - bisa mengelola seluruh user termasuk admin, semua UMKM, seluruh resume, dan konfigurasi sistem

## 2. Tujuan sistem

Sistem ini dibangun untuk membantu:
- UMKM mempromosikan usaha mereka
- konsumen mencari UMKM dan melihat produk/layanan mereka
- admin melakukan validasi dan pengelolaan data usaha
- superadmin memastikan seluruh data dan akses sistem berjalan dengan benar

## 3. Modul utama dan fitur

### 3.1 Modul UMKM

#### Dashboard UMKM
- ringkasan profil usaha
- status verifikasi
- jumlah menu usaha
- jumlah resume
- riwayat pendaftaran pelatihan/bazaar
- laporan pendapatan bulanan
- status akun aktif/inactive

#### Profil usaha
Data profil usaha yang harus disimpan:
- id_umkm
- nama_usaha
- slug
- kategori_usaha
- deskripsi_usaha
- alamat_lengkap
- kecamatan
- kelurahan
- latitude
- longitude
- tanggal_berdiri
- nomor_wa
- email_usaha
- website
- sosial_media
  - instagram
  - facebook
  - tiktok
  - youtube
- olshop
  - shopee
  - tokopedia
  - bukalapak
  - lazada
  - gofood
  - grabfood
  - shopee food
- jumlah_pegawai
- aset_usaha
- legalitas
  - NIB
  - NPWP
  - SIUP
  - IUMK
- bukti_usaha
- foto_profil
- foto_dokumentasi
- status_verifikasi
- status_aktif
- created_at
- updated_at
- user_id pemilik usaha

Catatan:
- Sosial media dan olshop dibuat dalam bentuk list / database relasi agar user tinggal memilih platform lalu mengisi link.
- Contoh format:
  - platform: instagram
  - url: https://instagram.com/....

#### Menu usaha
Setiap menu memiliki:
- id_menu
- umkm_id
- nama_menu
- gambar_menu
- harga
- keterangan
- status_aktif
- created_at
- updated_at

#### Laporan pendapatan
- id_laporan
- umkm_id
- bulan
- tahun
- total_pendapatan
- catatan
- status
- created_at
- updated_at

Tujuan:
- UMKM bisa mencatat pendapatan bulanan
- admin dan superadmin bisa melihat data pendapatan untuk kebutuhan monitoring

#### Pelatihan
- daftar event pelatihan yang dapat diikuti UMKM
- setiap UMKM bisa daftar ke event tertentu
- sistem pelatihan memiliki 2 versi:
  1. pelatihan terbuka tanpa verifikasi
     - UMKM cukup login lalu daftar langsung
     - cocok untuk event umum yang tidak membutuhkan screening
  2. pelatihan dengan verifikasi / persetujuan admin
     - UMKM mendaftar lalu menunggu admin approve/reject
     - cocok untuk pelatihan terbatas, prioritas, atau event berbiaya
- setiap event pelatihan dapat menentukan:
  - kuota total maksimal peserta
  - kuota per UMKM (misalnya 1 peserta per UMKM)
  - apakah verifikasi wajib atau tidak
  - apakah materi dapat diakses setelah pendaftaran
  - apakah sertifikat diberikan setelah selesai
- data event pelatihan:
  - id_pelatihan
  - judul
  - deskripsi
  - tanggal_mulai
  - tanggal_selesai
  - tempat
  - jenis_pendaftaran (open atau verified)
  - kuota_total
  - kuota_terpakai
  - kuota_per_umkm
  - status
  - materi_pelatihan_url atau file_materi
  - sertifikat_tersedia (true/false)
  - created_at
  - updated_at
- data pendaftaran pelatihan:
  - id_pendaftaran
  - umkm_id
  - pelatihan_id
  - status_pendaftaran
  - tanggal_daftar
  - catatan
  - tanggal_verifikasi
  - divalidasi_oleh_admin_id
  - apakah_lulus
  - sertifikat_file
  - created_at
  - updated_at
- materi pelatihan:
  - file materi dapat diunduh atau dibaca setelah UMKM diterima
  - admin dapat upload modul, slide, atau dokumen pelatihan
- sertifikat:
  - diberikan kepada UMKM yang telah mengikuti pelatihan dan selesai
  - status sertifikat: belum_dibuat, dibuat, diunduh

#### Bazaar
- daftar bazaar / event expo / pameran
- UMKM dapat mendaftar setelah login
- sistem bazaar juga memiliki 2 versi:
  1. bazaar terbuka tanpa verifikasi
     - UMKM daftar langsung dan masuk ke daftar peserta
  2. bazaar dengan verifikasi / persetujuan admin
     - daftar masuk ke waiting list lalu admin approve/reject
- semua event bazaar dapat memiliki batas kuota peserta:
  - kuota_total maksimal UMKM yang bisa ikut
  - kuota_per_umkm (misalnya maksimal 1 booth / 1 pendaftaran per UMKM)
  - saat kuota penuh, pendaftaran otomatis ditolak atau masuk waiting list
- setiap bazaar dapat menyiapkan:
  - area booth
  - kebutuhan stand
  - laporan penjualan
  - target omset
- data event bazaar:
  - id_bazaar
  - judul
  - deskripsi
  - tanggal_mulai
  - tanggal_selesai
  - tempat
  - jenis_pendaftaran (open atau verified)
  - kuota_total
  - kuota_terpakai
  - kuota_per_umkm
  - status
  - created_at
  - updated_at
- data pendaftaran bazaar:
  - id_pendaftaran
  - umkm_id
  - bazaar_id
  - status_pendaftaran
  - tanggal_daftar
  - catatan
  - tanggal_verifikasi
  - divalidasi_oleh_admin_id
  - created_at
  - updated_at
- laporan hasil penjualan bazaar:
  - id_laporan_penjualan
  - pendaftaran_bazaar_id
  - umkm_id
  - total_transaksi
  - total_penjualan
  - omset
  - barang_terjual
  - catatan
  - foto_bukti_penjualan
  - status_laporan
  - created_at
  - updated_at
- admin dapat melihat laporan penjualan untuk evaluasi dan rekap bazaar

#### Resume usaha
- data resume yang bisa dipublikasikan
- struktur data:
  - id_resume
  - umkm_id
  - judul
  - isi_resume
  - foto
  - status
  - published_at
  - created_at
  - updated_at

## 3.2 Modul Admin

#### Dashboard Admin
- total UMKM
- total UMKM terverifikasi
- total UMKM pending
- total user UMKM
- total admin
- total resume
- total event pelatihan
- total bazaar
- statistik terbaru

#### Slider
Admin bisa:
- membuat slider baru
- mengaktifkan atau menonaktifkan slider
- mengubah urutan slider
- menghapus slider

Field slider:
- id_slider
- judul
- deskripsi
- gambar
- tombol_teks
- tombol_link
- urutan
- status_aktif
- created_at
- updated_at

#### Verifikasi UMKM
Admin memverifikasi UMKM yang statusnya pending.
- daftar UMKM yang menunggu verifikasi
- lihat dokumen pendukung
- approve / reject
- catatan admin

Field verifikasi:
- id_verifikasi
- umkm_id
- admin_id
- status_verifikasi
- catatan_admin
- tanggal_verifikasi
- created_at

#### Manajemen UMKM
Admin bisa:
- melihat semua data UMKM
- update data UMKM jika diperlukan
- ubah status aktif/inactive
- melihat riwayat verifikasi
- menonaktifkan akun usaha

#### Lokasi Kecamatan & Kelurahan Kutim
Admin harus memiliki fitur manajemen lokasi wilayah untuk kebutuhan peta, filter, dan pengelompokan data.

Fitur yang diperlukan:
- data kecamatan di Kabupaten Kutai Timur
- data kelurahan/desa per kecamatan
- upload file peta wilayah (GeoJSON / shapefile export atau file peta digital yang bisa dipakai frontend)
- menghubungkan data lokasi ke UMKM, bazar, pelatihan, dan modul lain yang membutuhkan wilayah
- mapping dari kecamatan/kelurahan ke koordinat jika diperlukan
- filter wilayah pada data UMKM dan laporan

Field tabel lokasi kecamatan:
- id_kecamatan
- nama_kecamatan
- slug_kecamatan
- kode_kecamatan
- file_peta_kecamatan
- status_aktif
- created_at
- updated_at

Field tabel lokasi_kelurahan:
- id_kelurahan
- kecamatan_id
- nama_kelurahan
- slug_kelurahan
- kode_kelurahan
- file_peta_kelurahan
- geojson_path
- latitude_center
- longitude_center
- status_aktif
- created_at
- updated_at

Field tabel file_peta_wilayah:
- id_peta
- nama_peta
- jenis_peta
- file_path
- url_file
- kecamatan_id
- kelurahan_id
- status
- created_at
- updated_at

Fungsi file peta di frontend:
- digunakan untuk menampilkan batas wilayah di map
- dipakai untuk filter area per kecamatan/kelurahan
- bisa digunakan untuk visualisasi data secara spasial
- digunakan pada halaman peta dan halaman admin untuk memetakan distribusi UMKM per wilayah

Hubungan lokasi:
- setiap UMKM memiliki kecamatan_id dan kelurahan_id
- data bazaar dan pelatihan bisa memiliki wilayah_id / kecamatan_id bila event dibatasi wilayah tertentu
- laporan wilayah akan dihitung berdasarkan kecamatan_id atau kelurahan_id
- filter publik dan admin bisa berdasarkan kecamatan dan kelurahan

#### Manajemen User
Admin bisa mengelola user non-admin.
- melihat semua user UMKM
- update data user
- aktifkan / nonaktifkan akun
- hapus akun jika perlu
- tidak bisa mengelola admin/superadmin

#### Resume
Admin bisa:
- melihat semua resume
- membuat resume
- mengedit resume
- publish / draft / archive

## 3.3 Modul Superadmin

Superadmin memiliki akses penuh terhadap:
- seluruh user (UMKM, admin, superadmin)
- seluruh UMKM
- seluruh resume
- seluruh slider
- seluruh laporan
- konfigurasi/setting sistem
- monitoring keamanan dan akses

Fitur superadmin:
- dashboard superadmin
- kelola admin
- kelola semua user
- kelola semua data usaha
- kelola resume
- manage role / permissions
- monitoring aktivitas sistem

## 3.4 Modul Public / Konsumen

Public user dapat:
- melihat halaman utama
- melihat daftar UMKM
- melihat detail UMKM
- melihat menu usaha
- melihat resume
- melihat slider aktif
- melihat pelatihan dan bazaar
- mendaftar pelatihan/bazaar jika form tersedia

Public user tidak memiliki akun login atau tidak memiliki akses mengedit data.

## 4. Hak akses per role

- UMKM
  - bisa lihat dan edit data miliknya sendiri
  - bisa view data public
  - bisa mendaftar pelatihan/bazaar
  - tidak bisa edit data UMKM lain
  - tidak bisa mengelola admin

- Admin
  - bisa melihat semua UMKM dan user non-admin
  - bisa verifikasi UMKM
  - bisa mengelola slider
  - bisa mengelola resume
  - tidak bisa mengubah superadmin

- Superadmin
  - bisa semua
  - bisa melihat semua data
  - bisa mengelola admin
  - bisa mengelola semua user

- Public
  - hanya read-only ke halaman publik

## 5. Fitur dasar yang harus ada di backend

### 5.1 Search
Setiap list/table harus memiliki fitur pencarian.

Contoh:
- UMKM: cari berdasarkan nama usaha, alamat, kecamatan, email
- User: cari berdasarkan nama, email
- Menu: cari berdasarkan nama menu
- Resume: cari berdasarkan judul
- Event pelatihan/bazaar: cari berdasarkan judul event

Algoritma dasar:
- input keyword dari form
- query menggunakan LIKE atau fulltext jika perlu
- hasil menampilkan data yang sesuai keyword
- jika tidak ada hasil, tampilkan notifikasi "data tidak ditemukan"

### 5.2 Filter
Setiap list/table harus punya filter.

Contoh filter umum:
- status
- kategori usaha
- kecamatan
- tanggal
- bulan
- tahun
- aktif / nonaktif
- approved / pending / rejected

Contoh:
- UMKM filter by status verifikasi dan kecamatan
- User filter by role dan status akun
- Laporan filter by bulan dan tahun
- Resume filter by status publish/draft

### 5.3 Sorting
Setiap tabel harus bisa diurutkan.

Contoh:
- sort by nama usaha ascending/descending
- sort by tanggal terbaru
- sort by total pendapatan tertinggi
- sort by status atau kategori

### 5.4 Pagination
Setiap list data harus memakai pagination.

Standar yang disarankan:
- default 10 data per halaman
- pilih opsi: 10, 25, 50, 100
- tampilkan total jumlah data
- tampilkan halaman aktif
- tombol previous dan next
- saat filter/search aktif, pagination tetap mengikuti hasil filter

### 5.5 Tabel data
Semua list harus ditampilkan dalam format tabel.

Tabel minimal memiliki:
- kolom data utama
- status badge
- aksi
  - view
  - edit
  - delete
  - approve/reject jika modul verifikasi

Contoh tombol aksi:
- Lihat detail
- Edit
- Hapus
- Aktifkan / Nonaktifkan
- Approve / Reject

### 5.6 CRUD
Semua modul utama harus memiliki sistem CRUD.

- Create: tambah data baru
- Read: lihat data list dan detail
- Update: edit data
- Delete: hapus data
- validasi input sebelum simpan
- notifikasi sukses/error
- redirect ke list setelah proses selesai

## 6. Status yang harus didefinisikan dengan jelas

### Status UMKM
- pending
- approved
- rejected
- active
- inactive

### Status User
- active
- inactive
- banned

### Status Slider
- active
- inactive

### Status Resume
- draft
- published
- archived

### Status Pelatihan / Bazaar
- draft
- published
- closed
- cancelled

### Status Pendaftaran Event
- menunggu
- diterima
- ditolak
- batal
- waiting_list
- completed
- attended

### Status Laporan Pendapatan
- draft
- submitted
- approved
- rejected

### Status Verifikasi Event
- open
- verified_required
- approved
- rejected

### Status Laporan Penjualan Bazaar
- draft
- submitted
- approved
- rejected

## 7. Validasi data dan file

Setiap form harus memiliki validasi.

Validasi umum:
- email wajib valid
- nomor WA harus valid
- nama usaha wajib diisi
- alamat wajib diisi
- kategori wajib dipilih
- status harus ada

Validasi file:
- format file: jpg, jpeg, png, pdf
- maksimal ukuran file tertentu (contoh 2MB atau 5MB)
- file optional / required sesuai kebutuhan
- simpan file di folder yang aman dan terstruktur

## 8. Struktur database yang dibutuhkan

### 8.1 Tabel users
- id
- name
- email
- password
- phone
- role
- status
- email_verified_at
- remember_token
- created_at
- updated_at

Role yang umum dipakai:
- umkm
- admin
- superadmin
- public

### 8.2 Tabel umkm
- id
- user_id
- nama_usaha
- slug
- kategori_id
- deskripsi
- alamat_lengkap
- kecamatan_id
- kelurahan_id
- latitude
- longitude
- tanggal_berdiri
- nomor_wa
- email_usaha
- website
- jumlah_pegawai
- aset_usaha
- status_verifikasi
- status_aktif
- foto_profil
- foto_dokumentasi
- created_at
- updated_at

Catatan:
- kolom kecamatan dan kelurahan diganti ke kecamatan_id dan kelurahan_id agar terhubung ke tabel lokasi wilayah
- untuk beberapa modul yang menggunakan lokasi, gunakan relasi ke tabel kecamatan_kutim dan kelurahan_kutim

### 8.3 Tabel kategori_usaha
- id
- nama_kategori
- slug
- created_at
- updated_at

### 8.3a Tabel kecamatan_kutim
- id
- nama_kecamatan
- slug_kecamatan
- kode_kecamatan
- file_peta_kecamatan
- status_aktif
- created_at
- updated_at

### 8.3b Tabel kelurahan_kutim
- id
- kecamatan_id
- nama_kelurahan
- slug_kelurahan
- kode_kelurahan
- file_peta_kelurahan
- geojson_path
- latitude_center
- longitude_center
- status_aktif
- created_at
- updated_at

### 8.3c Tabel file_peta_wilayah
- id
- nama_peta
- jenis_peta
- file_path
- url_file
- kecamatan_id
- kelurahan_id
- status
- created_at
- updated_at

### 8.3d Tabel wilayah_terkait_modul
- id
- modul
- modul_id
- kecamatan_id
- kelurahan_id
- created_at
- updated_at

Catatan:
- tabel ini dipakai untuk modul yang membutuhkan lokasi tertentu, misalnya bazar, pelatihan, event, atau laporan wilayah
- ini memudahkan query filtering berdasarkan wilayah tanpa harus menambah kolom lokasi yang berulang di setiap tabel

### 8.4 Tabel sosial_media
- id
- umkm_id
- platform
- url
- created_at
- updated_at

### 8.5 Tabel olshop
- id
- umkm_id
- platform
- url
- created_at
- updated_at

### 8.6 Tabel legalitas_usaha
- id
- umkm_id
- jenis_legalitas
- nomor_legalitas
- file_legalitas
- status
- created_at
- updated_at

### 8.7 Tabel menu_usaha
- id
- umkm_id
- nama_menu
- gambar_menu
- harga
- keterangan
- status_aktif
- created_at
- updated_at

### 8.8 Tabel laporan_pendapatan
- id
- umkm_id
- bulan
- tahun
- total_pendapatan
- catatan
- status
- created_at
- updated_at

### 8.9 Tabel slider
- id
- judul
- deskripsi
- gambar
- tombol_teks
- tombol_link
- urutan
- status_aktif
- created_at
- updated_at

### 8.10 Tabel verifikasi_umkm
- id
- umkm_id
- admin_id
- status_verifikasi
- catatan_admin
- tanggal_verifikasi
- created_at

### 8.11 Tabel resume
- id
- umkm_id
- judul
- isi_resume
- foto
- status
- published_at
- created_at
- updated_at

### 8.12 Tabel pelatihan
- id
- judul
- deskripsi
- tanggal_mulai
- tanggal_selesai
- tempat
- jenis_pendaftaran
- kuota_total
- kuota_terpakai
- kuota_per_umkm
- status
- file_materi
- materi_url
- sertifikat_tersedia
- created_at
- updated_at

### 8.13 Tabel pendaftaran_pelatihan
- id
- pelatihan_id
- umkm_id
- status_pendaftaran
- tanggal_daftar
- catatan
- tanggal_verifikasi
- divalidasi_oleh_admin_id
- apakah_lulus
- sertifikat_file
- created_at
- updated_at

### 8.14 Tabel materi_pelatihan
- id
- pelatihan_id
- judul_materi
- tipe_file
- file_path
- url_materi
- created_at
- updated_at

### 8.15 Tabel sertifikat_pelatihan
- id
- pendaftaran_pelatihan_id
- umkm_id
- nomor_sertifikat
- file_sertifikat
- status_sertifikat
- tanggal_dibuat
- created_at
- updated_at

### 8.16 Tabel bazaar
- id
- judul
- deskripsi
- tanggal_mulai
- tanggal_selesai
- tempat
- jenis_pendaftaran
- kuota_total
- kuota_terpakai
- kuota_per_umkm
- status
- created_at
- updated_at

### 8.17 Tabel pendaftaran_bazaar
- id
- bazaar_id
- umkm_id
- status_pendaftaran
- tanggal_daftar
- catatan
- tanggal_verifikasi
- divalidasi_oleh_admin_id
- created_at
- updated_at

### 8.18 Tabel laporan_penjualan_bazaar
- id
- pendaftaran_bazaar_id
- umkm_id
- total_transaksi
- total_penjualan
- omset
- barang_terjual
- catatan
- foto_bukti_penjualan
- status_laporan
- created_at
- updated_at

## 9. Relasi antar tabel

- satu user bisa punya banyak UMKM (jika satu akun mewakili beberapa usaha)
- satu UMKM punya banyak menu
- satu UMKM punya banyak resume
- satu UMKM punya banyak laporan pendapatan
- satu UMKM punya banyak pendaftaran pelatihan
- satu UMKM punya banyak pendaftaran bazaar
- satu UMKM punya banyak media sosial dan olshop
- satu UMKM punya satu status verifikasi
- satu admin bisa memverifikasi banyak UMKM
- satu slider bisa diakses publik tanpa login

## 10. Flow bisnis utama

### Flow UMKM mendaftar dan diverifikasi
1. UMKM register akun
2. UMKM mengisi profil usaha
3. UMKM memilih kecamatan dan kelurahan dari data lokasi Kutim
4. UMKM upload bukti usaha dan legalitas
5. Admin melihat data pending
6. Admin approve atau reject
7. Jika approve, UMKM aktif bisa mengelola dashboard
8. Jika reject, UMKM diminta revisi data

### Flow admin mengelola peta wilayah
1. Admin masuk ke menu lokasi
2. Admin menambah atau mengedit kecamatan dan kelurahan Kutim
3. Admin upload file peta wilayah atau GeoJSON
4. Data peta disimpan dan dipakai untuk tampilan frontend
5. Admin menghubungkan wilayah ini ke UMKM / event / laporan

### Flow UMKM menambah menu
1. UMKM login
2. masuk ke menu usaha
3. input nama, harga, foto, deskripsi
4. simpan data
5. data tampil di halaman publik

### Flow laporan pendapatan
1. UMKM login
2. pilih bulan dan tahun
3. input total pendapatan dan catatan
4. simpan
5. data muncul di dashboard dan admin

### Flow pendaftaran pelatihan atau bazaar
#### Versi 1: pendaftaran terbuka tanpa verifikasi
1. UMKM login
2. pilih event pelatihan atau bazaar
3. klik daftar
4. sistem mengecek kuota_total dan kuota_per_umkm
5. jika kuota masih tersedia, pendaftaran otomatis diterima
6. data peserta masuk ke daftar peserta aktif

#### Versi 2: pendaftaran dengan verifikasi admin
1. UMKM login
2. pilih event pelatihan atau bazaar
3. klik daftar
4. status pendaftaran berubah menjadi menunggu
5. admin mengecek kelengkapan data dan kuota peserta
6. admin approve atau reject
7. jika approve, peserta resmi terdaftar
8. jika reject, peserta bisa mengajukan ulang atau dibatalkan

#### Flow pelatihan dengan materi dan sertifikat
1. UMKM diterima di pelatihan
2. sistem memberikan akses materi pelatihan
3. UMKM bisa mengunduh atau membuka materi
4. setelah pelatihan selesai, admin atau sistem memeriksa kehadiran / kelulusan
5. sistem membuat sertifikat
6. UMKM dapat lihat dan unduh sertifikat

#### Flow bazaar dengan laporan penjualan
1. UMKM terdaftar di bazaar
2. UMKM menjalankan penjualan selama event
3. UMKM menginput laporan penjualan
4. sistem menyimpan total transaksi, total penjualan, omset, dan jumlah barang terjual
5. admin meninjau laporan penjualan
6. laporan disetujui atau ditolak
7. hasil rekap dipakai untuk evaluasi bazaar

#### Batas kuota peserta
- setiap event harus punya kuota_total
- setiap event bisa punya kuota_per_umkm
- sistem harus mengecek kuota sebelum pendaftaran dibuat
- jika kuota penuh, sistem menolak pendaftaran atau masuk waiting list sesuai konfigurasi
- default yang disarankan:
  - kuota_total = jumlah peserta yang diizinkan per event
  - kuota_per_umkm = 1 untuk pelatihan, 1 booth untuk bazaar

## 11. Output yang diharapkan dari developer/backend

Developer/backend diharapkan membuat:
- migration database
- model Eloquent
- controller untuk masing-masing modul
- route web atau API
- request validation
- middleware / authorization
- service logic jika diperlukan
- views admin, UMKM, dan public
- CRUD lengkap untuk semua modul utama
- pagination, search, filter, sorting
- upload file dan penyimpanan file
- role access management

## 12. Ringkasan penting agar tidak miss

Hal-hal yang harus selalu ada dalam spesifikasi backend:
- role dan permission jelas
- tabel data lengkap per modul
- field tiap modul detail
- status tiap data jelas
- akses per role dibatasi
- search, filter, sorting, pagination harus ada
- CRUD harus lengkap
- validasi file dan input wajib
- relasi antar data jelas
- flow bisnis jelas

## 13. Versi ringkas untuk keperluan prompt AI

"Bangun backend Laravel untuk sistem UMKM dengan role UMKM, Admin, Superadmin, dan Public. UMKM dapat mengelola profil usaha, menu usaha, laporan pendapatan bulanan, resume, dan daftar pelatihan/bazaar. Admin dapat mengelola slider, verifikasi UMKM, user non-admin, resume, dan data UMKM. Superadmin bisa mengelola seluruh data termasuk admin. Public hanya dapat melihat data publik. Setiap list data harus memiliki search, filter, sorting, dan pagination. Semua CRUD harus lengkap dengan validasi input dan upload file. Gunakan struktur database yang mencakup users, umkm, kategori_usaha, sosial_media, olshop, legalitas_usaha, menu_usaha, laporan_pendapatan, slider, verifikasi_umkm, resume, pelatihan, pendaftaran_pelatihan, bazaar, dan pendaftaran_bazaar. Setiap role memiliki permission yang jelas, dan UMKM hanya bisa mengelola data miliknya sendiri."

## 14. Kesimpulan

Spesifikasi ini sudah cukup rinci untuk memulai pengembangan backend. Yang paling penting adalah memastikan 3 hal berikut selalu masuk ke prompt atau brief developer:
- role dan permission
- struktur database lengkap
- fitur dasar list page dan CRUD

Dengan ketiga hal ini, pengembangan backend tidak akan miss atau ambigu.