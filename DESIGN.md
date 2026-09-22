# Design System UMKM KUTIM

Dokumen ini merangkum desain frontend yang sudah diterapkan pada project berdasarkan struktur layout, styling, dan komponen yang ada di Blade view dan Tailwind CSS.

## 1. Tujuan desain

Desain ini bertujuan untuk menampilkan:
- portal resmi pemerintah daerah yang kredibel
- direktori UMKM yang mudah dicari
- ekosistem usaha yang terintegrasi dengan promosi, pelatihan, bazar, dan laporan
- pengalaman yang bersih, modern, dan responsif untuk pengguna publik, UMKM, dan admin

## 2. Gaya visual utama

### A. Personality
- Resmi dan terpercaya
- Modern dan informatif
- Produktif dan berorientasi layanan publik
- Ramah untuk pengguna non-teknis

### B. Visual tone
- Warna dominan hijau mewakili keberlanjutan, pertumbuhan, dan sektor UMKM
- Sentuhan emas memberi kesan prestise dan legitimasi institusional
- Background yang bersih dan ruang yang cukup membuat informasi mudah dibaca

## 3. Identitas warna

### Primary palette
- Emerald 950: #022c22
- Emerald 900: #064e3b
- Emerald 800: #166534
- Emerald 700: #15803d
- Emerald 600: #16a34a
- Emerald 50: #f0fdf4

### Accent palette
- Amber 500: #d97706
- Amber 400: #f59e0b
- Gold warm accent untuk highlight, badge, dan CTA penting

### Neutral palette
- Slate 50: #f8fafc
- Slate 900: #0f172a
- White: #ffffff
- Border: rgba(22, 163, 74, 0.12) dan rgba(255,255,255,0.14)

### Fungsi warna
- Hijau: brand utama, hero, buttons, navigasi aktif
- Putih: latar umum agar bersih dan mudah dibaca
- Emas: penanda penting, badge institusi, highlight event
- Abu gelap: teks utama dan konten serius

## 4. Tipografi

### Font utama
- Plus Jakarta Sans
- Digunakan di seluruh layout utama untuk tampilan modern, formal, dan mudah dibaca

### Hierarki teks
- Judul hero: sangat besar, tebal, tinggi, kuat, padat
- Judul section: bold dan jelas
- Body text: medium weight, readable, cukup ruang antar baris
- Badge: small uppercase / compact label

## 5. Layout system

### A. Struktur umum halaman publik
Pada halaman utama, layout mengikuti pola berikut:
1. Header sticky
2. Hero banner besar
3. Label instansi / badge organisasi
4. CTA utama dan highlight informasi
5. Section kategori usaha
6. Section UMKM unggulan
7. Section peta UMKM
8. Section event (bazar/pelatihan)
9. Section berita / konten
10. Footer

### B. Layout pattern
- Grid responsif berbasis Tailwind
- Card dengan rounded corners, border tipis, dan shadow lembut
- Ruang antar section cukup lebar untuk memudahkan scan informasi
- Banyak section memakai white background dengan ring road/line dividers

## 6. Header dan navigasi

### Header
- sticky top
- background semi-transparan dengan blur
- logo di kiri
- navigasi di tengah/kanan
- item menu seperti:
  - Beranda
  - UMKM
  - Bazar
  - Pelatihan
  - Berita
  - Login / Dashboard

### Desktop nav
- menu ditampilkan rapi dalam satu row
- item aktif memiliki background hijau lembut dan teks gelap/green
- item non-aktif hover berubah ke hijau lembut

### Mobile nav
- menu collapsible dengan tombol hamburger
- tetap menjaga konsistensi branding

## 7. Hero section

Hero section adalah elemen paling kuat dalam desain ini.

### Karakter hero
- background foto atau gambar besar dari instansi atau aktivitas daerah
- overlay hijau gelap agar teks tetap terbaca
- badge institusi di atas heading
- headline besar dengan pesan utama portal
- fokus pada identitas daerah dan layanan UMKM

### Hero behavior
- slider otomatis dengan Alpine.js
- transisi slide halus
- dapat dibuka dalam modal preview saat di klik
- gambar di crop/cover agar konsisten dan estetik

## 8. Komponen utama

### A. Card UMKM
- rounded corners
- border tipis
- image cover atau thumbnail
- judul usaha besar
- kategori atau status kecil
- lokasi dan kontak singkat
- CTA: lihat detail

### B. Card berita
- image top
- judul, excerpt, tanggal
- CTA read more

### C. Card event bazar/pelatihan
- image atau banner event
- judul event
- tanggal dan tempat
- tombol daftar / lihat detail

### D. Badge dan tag
- label status: aktif, pending, approved, rejected, publish, draft
- background berwarna sesuai status
- kontras tinggi agar mudah dibaca

### E. Glass panel
- background semi-transparan
- blur effect
- dipakai untuk badge, topbar, dan panel tertentu

## 9. Interaksi dan animasi

### Scroll reveal
- elemen muncul saat masuk viewport
- efek fade + translate
- sangat membantu agar halaman terasa lebih modern tanpa berlebihan

### Hover state
- card bergerak sedikit naik saat hover
- shadow bertambah untuk menandai interaksi
- tombol dan link berubah warna atau background

### Slider
- autoplay otomatis
- kontrol arrow / dots
- transisi smooth dengan easing tertentu

## 10. Peta dan data geografis

Desain ini juga memuat peta digital berbasis Leaflet.

### Karakteristik
- map ditempatkan sebagai elemen penting dalam portal
- menggunakan cluster marker untuk menahan performa ketika data banyak
- filter dan popup info singkat pada marker UMKM
- pemetaan menjadi bagian utama dari pengalaman pencarian usaha

## 11. Dashboard admin dan pelaku usaha

### Admin dashboard
- layout data-centric
- tabel dengan status badge dan action button
- form layout lebih formal dan rapi
- section ringkasan metrik di bagian atas

### Pelaku dashboard
- fokus pada data usaha sendiri
- panel info profil, menu, laporan, pendaftaran, dan status
- form fields dibagi dengan spacing yang konsisten

## 12. Responsiveness

Semua tampilan dirancang responsif untuk:
- desktop
- tablet
- mobile

### Prinsip responsif
- layout stack ke atas di layar kecil
- navigasi berubah menjadi menu mobile
- card berubah menjadi satu kolom bila ruang sempit
- hero tetap nyaman dibaca di layar kecil

## 13. Design system yang sudah berjalan

### Spacing scale
- padding section: 4, 6, 8, 12, 16, 20
- rounded: sm, md, xl, 2xl, 3xl
- shadow focus: soft, modern, minimal

### Border style
- border tipis
- rounded moderate
- separator lembut antar section

### Buttons
- primary: hijau solid
- secondary: putih dengan border hijau lembut
- warning: amber untuk highlight event atau promo
- danger: merah untuk delete / reject / aksi kritis

## 14. Prinsip UX yang terlihat di design

- informasi utama muncul di hero dan section pertama
- pengguna dapat menemukan usaha, event, dan berita dengan cepat
- konten dibagi dengan jelas dan tidak terlalu padat
- desain tetap formal sesuai instansi pemerintah
- brand local identity kuat karena penggunaan elemen Kutai Timur dan logo pemerintah

## 15. Kesimpulan

Desain yang sudah ada menggabungkan tiga hal utama:
1. Portal pemerintah daerah yang kredibel
2. Direktori UMKM yang fungsional
3. Platform komunitas promosi dan training

Secara keseluruhan, desain ini terlihat modern, rapi, berorientasi data, dan sesuai kebutuhan sistem UMKM daerah yang berskala publik.

## 16. Saran pengembangan desain selanjutnya

Untuk perkembangan berikutnya, desain dapat lebih konsisten jika:
- semua halaman menggunakan desain block/section yang seragam
- status badge dibuat template yang sama di semua modul
- table action button dibuat standar di seluruh admin panel
- form input diberi style yang konsisten di semua modul
- membuat component library dasar (button, card, badge, input, modal, table, pagination)

Dengan begitu, seluruh frontend akan lebih mudah dipelihara dan konsisten dari segi branding maupun UX.
