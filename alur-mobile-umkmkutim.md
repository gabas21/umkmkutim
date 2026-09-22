# Alur Mobile UMKM Kutim

## 1. Tujuan
Membuat alur mobile yang fokus pada discovery, pencarian, dan interaksi cepat antar pengguna dengan UMKM lokal Kutim. Tampilan mobile harus ringan, cepat, dan familiar seperti aplikasi on-demand, namun tetap relevan untuk kebutuhan UMKM lokal.

## 2. Struktur menu utama
Menu utama yang digunakan:
1. Home
2. UMKM
3. Peta
4. Promo
5. Akun

## 3. Alur utama aplikasi

### A. Alur onboarding / masuk aplikasi
1. User membuka aplikasi
2. Sistem menampilkan splash screen singkat
3. User masuk ke halaman Home
4. Jika user belum login, beberapa fitur masih bisa diakses terbatas
5. Jika user ingin menyimpan favorit atau mendaftar event, sistem mengarahkan ke login/register

### B. Alur pencarian UMKM
1. User masuk ke Home
2. User memakai search bar untuk mencari nama UMKM, produk, atau lokasi
3. Sistem menampilkan hasil pencarian yang relevan
4. User bisa filter hasil berdasarkan:
   - kategori usaha
   - kecamatan/lokasi
   - rating / populer
   - terdekat
5. User memilih salah satu UMKM
6. Sistem masuk ke detail UMKM

### C. Alur melihat UMKM
1. User masuk ke menu UMKM
2. User melihat list UMKM dengan card yang berisi:
   - foto
   - nama usaha
   - kategori
   - lokasi
   - status verifikasi
3. User bisa klik card untuk masuk ke detail usaha
4. Di detail UMKM user bisa melihat:
   - profil usaha
   - produk utama
   - alamat
   - jam operasional
   - kontak / WhatsApp
   - map / arah
   - foto gallery
5. User bisa simpan ke favorit atau bagikan link

### D. Alur melihat lokasi / peta
1. User masuk ke menu Peta
2. Sistem menampilkan peta lokasi UMKM yang tersedia di sekitar user
3. User bisa:
   - zoom in / out
   - melihat marker UMKM
   - filter UMKM per kecamatan atau kategori
4. User memilih marker atau list terdekat
5. Sistem membuka detail UMKM

### E. Alur promo / campaign
1. User masuk ke menu Promo
2. User melihat banner promo atau campaign aktif
3. User melihat daftar promo usaha, event lokal, atau berita singkat
4. User bisa memilih promo/event tertentu
5. Jika promo berupa event, user diarahkan ke detail event
6. Jika promo berbentuk usaha, user diarahkan ke detail UMKM

### F. Alur akun / profil
1. User masuk ke menu Akun
2. Jika belum login, tampil halaman login/register
3. Jika sudah login, tampil profil user:
   - nama
   - email / nomor telepon
   - foto profil
   - favorit UMKM
   - activity history
   - pengaturan
4. User bisa:
   - edit profil
   - lihat UMKM favorit
   - lihat promo yang disimpan
   - logout
   - masuk ke panel UMKM jika role pelaku usaha

## 4. Alur khusus untuk pelaku usaha

### A. Masuk sebagai UMKM
1. Pelaku usaha login ke aplikasi
2. User masuk ke menu Akun
3. Tampil opsi:
   - Profil UMKM saya
   - Edit profil usaha
   - Upload dokumen
   - Kelola produk
   - Lihat promo / event
4. Pelaku usaha dapat update informasi usaha dan kontak
5. Jika belum memiliki UMKM terdaftar, bisa mendaftar baru

### B. Mendaftar UMKM
1. User memilih “Daftar UMKM”
2. Sistem menampilkan form singkat:
   - nama usaha
   - kategori usaha
   - lokasi
   - nomor kontak
   - deskripsi singkat
3. User mengisi form dan upload dokumen pendukung
4. Admin memverifikasi data
5. Status usaha berubah menjadi aktif / pending / rejected

## 5. Alur kegiatan marketing dan promo

### A. Promo UMKM
1. Admin atau pelaku usaha membuat promo baru
2. Promo ditampilkan di menu Promo
3. User melihat promo di home atau promo screen
4. User klik promo dan diarahkan ke detail usaha atau halaman promo

### B. Event komunitas
1. Admin membuat event
2. Event tampil di menu Promo
3. User melihat detail event
4. User klik daftar
5. Sistem memvalidasi keanggotaan dan kuota
6. User menerima konfirmasi pendaftaran

## 6. Alur interaksi user

### A. Menyimpan favorit
1. User klik ikon love pada UMKM atau promo
2. Simpan ke daftar favorit user
3. User bisa lihat favorit di menu Akun

### B. Chat / kontak usaha
1. User klik tombol WhatsApp atau kontak usaha
2. Sistem membuka aplikasi chat / telepon
3. User bisa lanjut komunikasi langsung dengan pemilik UMKM

### C. Arah ke lokasi
1. User klik tombol arah / buka map
2. Sistem membuka peta external dengan lokasi usaha
3. User mendapatkan arah menuju usaha tersebut

## 7. Halaman yang harus dibuat

### Home
- Banner utama
- Search bar
- Kategori cepat
- UMKM terdekat
- UMKM unggulan
- Promo aktif
- CTA utama

### UMKM
- Header dengan search dan filter
- List UMKM
- Filter kategori, kecamatan, status
- Detail UMKM

### Peta
- Map view
- Marker UMKM
- List UMKM sekitar
- Detail marker / usaha

### Promo
- Banner promo utama
- Daftar promo dan campaign aktif
- Promo UMKM
- Event singkat
- Detail promo/event

### Akun
- Login/register
- Profil user
- Favorite
- Pengaturan
- Opsi UMKM jika terdaftar

## 8. Flow utama user

### Flow user umum
1. Buka aplikasi
2. Home -> cari UMKM
3. Pilih UMKM -> lihat detail
4. Lihat lokasi -> buka peta
5. Klik promo -> lihat campaign atau event
6. Login/register jika ingin simpan favorit atau daftar event

### Flow user yang ingin mencari usaha dekat
1. Buka Home
2. Cari “UMKM terdekat”
3. Lihat kartu UMKM dan lokasi
4. Buka map
5. Klik arah ke lokasi

### Flow user yang ingin ikut event
1. Buka menu Promo
2. Pilih event yang tersedia
3. Baca detail event
4. Klik daftar
5. Sistem validasi dan konfirmasi

### Flow pelaku usaha
1. Login ke Akun
2. Daftar UMKM baru atau kelola UMKM lama
3. Update profil usaha dan produk
4. Lihat status verifikasi
5. Buat promo atau event

## 9. Prioritas implementasi
### Prioritas 1
- Home
- UMKM list + detail
- Peta
- Promo list + detail
- Login/register

### Prioritas 2
- Favorite / wishlist
- chat / whatsapp direct
- map direction
- status verifikasi UMKM

### Prioritas 3
- notifikasi
- profil lengkap
- history / activity
- rekomendasi lanjutan

## 10. Kesimpulan
Alur mobile yang paling efektif untuk UMKM Kutim adalah alur discovery-first: user datang, cari usaha, lihat lokasi, lihat promo, lalu login bila memang butuh interaksi lebih lanjut. Dengan menu utama Home, UMKM, Peta, Promo, dan Akun, aplikasi akan terasa ringan, cepat, dan relevan untuk kebutuhan pengguna mobile lokal.
