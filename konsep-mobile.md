# Konsep Mobile UI - UMKM Kutim

## 1. Tujuan desain
Membuat tampilan mobile yang terasa modern, cepat, dan familiar seperti aplikasi on-demand seperti Gojek, namun tetap relevan untuk platform UMKM dan komunitas lokal Kutai Timur.

Target utama:
- Mudah dioperasikan dengan satu tangan
- Informasi utama langsung terlihat tanpa scroll panjang yang membingungkan
- Pencarian produk/UMKM cepat dan intuitif
- Nuansa lokal, modern, dan terpercaya
- Cocok untuk pengguna mobile yang mayoritas mengakses lewat ponsel

## 2. Gaya visual yang ingin dibangun
### A. Karakter utama
- Clean, modern, ringan
- Warna dominan hijau, putih, abu muda, dan sedikit highlight kuning/orange
- Sudut kartu membulat besar (radius 18–24px)
- Shadow lembut tapi tidak overdone
- Typography tegas dan mudah dibaca

### B. Referensi feel
- Seperti aplikasi layanan digital yang cepat dan familiar
- Bukan style e-commerce super berat
- Lebih condong ke app shell yang mudah diakses dan terasa “live”

## 3. Prinsip UX mobile
- Prioritaskan informasi paling penting di layar pertama
- Gunakan struktur linear satu kolom
- Tombol besar, mudah tap
- Navigasi utama tetap terlihat di bagian bawah
- Search dan kategori harus langsung bisa diakses
- Aktifitas utama seperti cari UMKM, lihat event, dan promo harus muncul cepat

## 4. Sistem warna
### Palette utama
- Primary Green: #16A34A / #15803D
- Green Dark: #14532D
- White: #FFFFFF
- Background Light: #F5F7F5
- Subtitle / muted: #6B7280
- Border: #E5E7EB
- Accent Yellow: #FBBF24
- Accent Orange: #F59E0B
- Danger Red: #DC2626

### Prinsip penggunaan warna
- Hijau sebagai warna dominan untuk branding utama
- Putih sebagai dasar layout
- Abu muda untuk background section
- Kuning/orange untuk highlight promo dan CTA
- Jangan terlalu banyak warna agar tetap bersih dan premium

## 5. Layout mobile
### A. Struktur umum halaman utama
1. Header sticky
   - logo / nama aplikasi
   - tombol notifikasi
   - tombol profil
2. Search bar besar
   - placeholder: “Cari UMKM, produk, event...”
3. Promo banner / hero
   - kartu banner lebar dengan gambar lokal atau ilustrasi produk
4. Menu layanan utama
   - UMKM
   - Event
   - Berita
   - Promo
   - Lokasi
   - Lainnya
5. Section rekomendasi UMKM
   - list card horizontal atau vertical
6. Event populer
   - card event dengan tanggal dan lokasi
7. Berita dan update
   - card kecil dengan thumbnail
8. Footer / menu bawah

### B. Navigasi bawah (Bottom Navigation)
Menu utama:
- Beranda
- UMKM
- Event
- Berita
- Profil

Tujuan:
- tetap terlihat di layar
- memudahkan akses satu tangan
- konsisten antar halaman

## 6. Komponen penting
### A. Header
- sticky top
- tinggi sekitar 64px
- ikon notifikasi dan profil di kanan
- menggukan lettermark/brand kecil

### B. Search bar
- rounded 18px
- background abu muda
- icon search di kiri
- label singkat dan jelas

### C. Card kategori / layanan
- icon berbentuk rounded square
- label kecil dan jelas
- bisa horizontal scroll

### D. Card UMKM
- gambar cover atas
- nama usaha di bawah
- kategori usaha
- kecamatan/lokasi
- rating atau jumlah pengikut bila ada
- tombol “Lihat Detail” atau CTA kecil

### E. Card event
- tanggal, jam, lokasi
- badge status (gratis / terbatas / populer)
- tombol daftar

### F. Card berita
- thumbnail kiri atau atas
- judul singkat
- meta tanggal / kategori

### G. Floating CTA
- tombol utama seperti “Daftar UMKM” atau “Ikut Event” bisa tampil floating di bawah atau sticky

## 7. Tampilan halaman utama yang disarankan
### Halaman home
- Header
- Search bar
- Banner promo
- Layanan utama horizontal
- UMKM terdekat / populer
- Event terbaru
- Berita terbaru
- Bottom nav

### Halaman UMKM
- tombol filter di atas
- list UMKM stacked
- chip filter: Kategori, Lokasi, Rating
- card UMKM dengan gambar dan info cepat

### Halaman event
- chip status: Semua, Hari Ini, Baru, Favorit
- list event layar penuh
- tombol “Daftar” di setiap card

### Halaman berita
- daftar card berita vertical
- kategori kecil di atas
- thumbnail minimal, judul jelas

## 8. Pola interaksi
- Saat membuka halaman, pengguna langsung melihat content yang relevan
- Semua tombol action memiliki tujuan jelas
- Filter seringkali tampil sebagai chip, bukan dropdown yang terlalu berat
- Tombol CTA utama selalu terlihat dan mudah dijangkau
- Pengguna dapat berpindah antar menu dengan satu tap

## 9. Responsive behavior
### Mobile phone (default)
- layar satu kolom
- card full width
- tombol CTA besar
- sticky bottom nav aktif
- spacing antar section 16–24px

### Tablet kecil / landscape
- bisa mulai menampung dua kolom untuk card kategori atau rekomendasi
- tetap menjaga kecepatan browsing dan ergonomi mobile

## 10. Standar spacing dan ukuran
- padding layar: 16px
- gap antar section: 20–24px
- card radius: 18–24px
- button height: 44–52px
- font heading: 20–28px
- body text: 14–16px
- label kecil: 11–12px

## 11. Tingkat kepercayaan dan keaslian lokal
Agar terasa khas UMKM Kutim dan tidak hanya “app generik”, tambahkan:
- warna dan elemen yang ringan, sederhana, namun mewakili semangat lokal
- potensi penggunaan foto produk lokal, UMKM, pasar, dan event daerah
- label seperti “UMKM Kutim”, “Produk Lokal”, “Event Daerah”, “UMKM Terdekat”
- konten yang terasa dekat dengan komunitas dan ekonomi lokal

## 12. Kesimpulan konsep
Versi mobile yang paling cocok adalah app-style mobile UI dengan karakter:
- cepat
- bersih
- mudah diakses satu tangan
- fokus ke pencarian, rekomendasi, event, dan UMKM lokal
- terasa seperti layanan digital modern, bukan halaman web mobile yang terlalu berat

Implementasi yang paling ideal adalah:
- satu struktur layout utama
- komponen reusable untuk card, banner, kategori, list item
- styling yang di-breakpoint-kan untuk desktop dan mobile
- mobile UI memakai gaya app-shell yang lebih fokus pada usability

## 13. Referensi arah visual
- Gojek-style mobile flow
- bottom navigation
- rounded cards
- search-first layout
- strong CTA buttons
- clean iconography
- compact but informative content blocks

## 14. Catatan desain untuk implementasi selanjutnya
- Desktop dan mobile harus tetap satu sistem desain, bukan dua desain yang berdiri sendiri
- Mobile menjadi prioritas utama karena produk ini lebih cocok di akses ponsel
- Desktop dapat dibuat sebagai ekspansi dari struktur yang sama, lebih lebar dan lebih formal

---

Dokumen ini dibuat sebagai panduan awal sebelum implementasi HTML/CSS/Blade/Tailwind.
