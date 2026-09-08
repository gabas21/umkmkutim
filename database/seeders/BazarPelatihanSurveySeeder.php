<?php

namespace Database\Seeders;

use App\Models\Bazar;
use App\Models\BazarPeserta;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use App\Models\SurveyKepuasan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BazarPelatihanSurveySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Bazars
        $bazar1 = Bazar::updateOrCreate(
            ['slug' => 'kutim-expo-bazar-umkm-nusantara-2026'],
            [
                'nama_bazar' => 'Kutim Expo & Bazar UMKM Nusantara 2026',
                'deskripsi' => 'Pameran akbar dan gelar dagang produk unggulan UMKM binaan Pemerintah Kabupaten Kutai Timur. Menampilkan produk kuliner khas, batik wakaroros, kriya dayak, hasil bumi, dan inovasi industri kreatif dari 18 kecamatan.',
                'lokasi' => 'Alun-Alun Polder Ilham Maulana',
                'kecamatan' => 'Sangatta Utara',
                'alamat_lengkap' => 'Jl. Teluk Lingga, Polder Ilham Maulana, Sangatta Utara, Kutai Timur',
                'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'kuota_peserta' => 60,
                'banner_url' => null,
                'status' => 'upcoming',
                'penyelenggara' => 'Dinas Koperasi, UKM & Perindag Kab. Kutai Timur',
                'kontak_person' => '0812-5567-8901 (Bpk. Rahmadi)',
                'fasilitas' => 'Tenda sarnavil 3x3m, meja display 2 unit, kursi 2 unit, instalasi listrik 450W, kartu identitas peserta resmi.',
            ]
        );

        $bazar2 = Bazar::updateOrCreate(
            ['slug' => 'gelar-dagang-festival-kuliner-khas-kutim'],
            [
                'nama_bazar' => 'Gelar Dagang & Festival Kuliner Khas Kutai Timur',
                'deskripsi' => 'Ajang temu dan promosi aneka jajanan tradisional, olahan amplang tenggiri, madu kelulut, olahan pisang sale, dan kopi robusta Kutai Timur bersama komunitas kuliner daerah.',
                'lokasi' => 'Halaman Utama Gedung Serbaguna Bukit Pelangi',
                'kecamatan' => 'Sangatta Utara',
                'alamat_lengkap' => 'Kawasan Pusat Pemerintahan Bukit Pelangi, Sangatta',
                'tanggal_mulai' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(16)->format('Y-m-d'),
                'kuota_peserta' => 45,
                'banner_url' => null,
                'status' => 'upcoming',
                'penyelenggara' => 'Dinas Koperasi & UMKM Kutai Timur',
                'kontak_person' => '0821-4321-7788 (Ibu Nurul)',
                'fasilitas' => 'Booth semi-kontainer, pasokan air bersih, listrik kompor induksi, panggung hiburan rakyat.',
            ]
        );

        $bazar3 = Bazar::updateOrCreate(
            ['slug' => 'bazar-pesisir-dan-hasil-bahari-sangkulirang'],
            [
                'nama_bazar' => 'Bazar Pesisir & Hasil Bahari Sangkulirang',
                'deskripsi' => 'Fasilitasi promosi produk olahan perikanan tangkap, ikan asin talang, terasi udang sangkulirang, dan kerajinan khas pesisir Teluk Sangkulirang.',
                'lokasi' => 'Taman Wisata Dermaga Sangkulirang',
                'kecamatan' => 'Sangkulirang',
                'alamat_lengkap' => 'Kawasan Dermaga Penyeberangan Sangkulirang Kota',
                'tanggal_mulai' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(27)->format('Y-m-d'),
                'kuota_peserta' => 30,
                'banner_url' => null,
                'status' => 'upcoming',
                'penyelenggara' => 'Diskop UKM bekerjasama dengan Kecamatan Sangkulirang',
                'kontak_person' => '0852-9988-1122 (Bpk. Syarifudin)',
                'fasilitas' => 'Meja lapak terpadu, kanopi peneduh, coolbox kolektif, publikasi media massa daerah.',
            ]
        );

        // Sample Peserta Bazar
        BazarPeserta::firstOrCreate(
            ['bazar_id' => $bazar1->id, 'nomor_hp' => '081234567890'],
            [
                'nama_pemilik' => 'Hj. Siti Marlina',
                'nama_usaha' => 'Amplang Belida Sangatta Barokah',
                'kategori_produk' => 'Kuliner & Makanan Ringan',
                'email' => 'marlina.amplang@gmail.com',
                'deskripsi_produk' => 'Amplang ikan belida khas sangatta renyah gurih tanpa pengawet kemasan 250gr & 500gr',
                'catatan' => 'Membutuhkan colokan listrik untuk pemanas sealer kemasan',
                'status' => 'diterima',
            ]
        );

        BazarPeserta::firstOrCreate(
            ['bazar_id' => $bazar1->id, 'nomor_hp' => '082198765432'],
            [
                'nama_pemilik' => 'Yohanes Lawing',
                'nama_usaha' => 'Anyaman Rotan & Manik Wakaroros Dayak',
                'kategori_produk' => 'Kriya & Kerajinan Tangan',
                'email' => 'lawing.craft@yahoo.com',
                'deskripsi_produk' => 'Tas anjat, topi manik saung dayak, gelang manik corak kutim',
                'catatan' => 'Membawa display maneken kecil',
                'status' => 'diterima',
            ]
        );

        // 2. Seed Pelatihan
        $p1 = Pelatihan::updateOrCreate(
            ['slug' => 'workshop-sertifikasi-halal-gratis-dan-legalitas-nib-2026'],
            [
                'judul' => 'Workshop Sertifikasi Halal Gratis (Self Declare) & Penerbitan NIB RBA',
                'deskripsi' => 'Program pendampingan intensif bagi pelaku usaha olahan makanan dan minuman di Kutai Timur untuk memperoleh Sertifikat Halal resmi BPJPH secara gratis (kuota program Sehati) serta pembuatan Nomor Induk Berusaha (NIB) berbasis risiko OSS.',
                'materi_ringkas' => "1. Alur registrasi OSS-RBA & pemetaan KBLI\n2. Kriteria Sistem Jaminan Produk Halal (SJPH)\n3. Praktik pengisian SiHalal BPJPH & unggah dokumen bahan\n4. Verifikasi & validasi oleh Pendamping PPH Daerah",
                'penyelenggara' => 'Dinas Koperasi & UKM Kab. Kutai Timur bekerjasama dengan Halal Center',
                'instruktur' => 'Drs. H. Mulyadi, M.Si (Auditor Halal & Konsultan PLUT Kutim)',
                'lokasi' => 'Gedung Diklat BKPP, Kawasan Bukit Pelangi Sangatta',
                'mode' => 'hybrid',
                'tanggal_mulai' => Carbon::now()->addDays(3)->setTime(8, 30),
                'tanggal_selesai' => Carbon::now()->addDays(3)->setTime(16, 0),
                'kuota' => 50,
                'biaya' => 0.00,
                'link_zoom' => 'https://zoom.us/j/88921109922?pwd=kutimhalal2026',
                'banner_url' => null,
                'status' => 'upcoming',
                'syarat_peserta' => 'Memiliki KTP Kutai Timur, memiliki produk olahan makanan/minuman non-daging potong sembelihan, membawa laptop/smartphone berkoneksi internet.',
            ]
        );

        $p2 = Pelatihan::updateOrCreate(
            ['slug' => 'akselerasi-digital-marketing-dan-foto-produk-katalog-umkm'],
            [
                'judul' => 'Akselerasi Digital Marketing, Marketplace & Fotografi Produk UMKM',
                'deskripsi' => 'Tingkatkan omzet usaha lokal dengan menguasai fotografi produk komersial menggunakan smartphone, pembuatan video promosi TikTok/Reels, optimasi akun Google Bisnisku, dan manajemen toko marketplace Shopee & Tokopedia.',
                'materi_ringkas' => "1. Dasar komposisi foto produk & pencahayaan sederhana\n2. Editing cepat foto katalog dengan Canva & Snapseed\n3. Copywriting penawaran yang menjual (Hook, Story, Offer)\n4. Setup Google Bisnisku agar muncul di pencarian peta Kutim",
                'penyelenggara' => 'Bidang Pemberdayaan Usaha Mikro Diskop UKM Kutim',
                'instruktur' => 'Dimas Arya Pratama (Digital Strategist & Content Creator Kaltim)',
                'lokasi' => 'Aula Pertemuan Kantor Dinas Koperasi & UKM Bukit Pelangi',
                'mode' => 'offline',
                'tanggal_mulai' => Carbon::now()->addDays(10)->setTime(9, 0),
                'tanggal_selesai' => Carbon::now()->addDays(11)->setTime(15, 30),
                'kuota' => 40,
                'biaya' => 0.00,
                'link_zoom' => null,
                'banner_url' => null,
                'status' => 'upcoming',
                'syarat_peserta' => 'Pelaku usaha aktif, membawa contoh minimal 2 produk fisik untuk sesi praktik foto.',
            ]
        );

        $p3 = Pelatihan::updateOrCreate(
            ['slug' => 'kurasi-kemasan-modern-dan-manajemen-keuangan-usaha-kecil'],
            [
                'judul' => 'Pelatihan Desain Kemasan Modern & Manajemen Keuangan Praktis',
                'deskripsi' => 'Membekali pelaku usaha dengan wawasan pemilihan kemasan food-grade standar ritel modern, informasi label nutrition fact, serta pencatatan arus kas buku kas digital menggunakan aplikasi SIAPIK Bank Indonesia.',
                'materi_ringkas' => "1. Regulasi label pangan kemasan BPOM RI & PIRT\n2. Studi kasus kemasan ramah lingkungan & standing pouch\n3. Pemisahan rekening pribadi vs kas usaha\n4. Menghitung HPP (Harga Pokok Penjualan) yang presisi",
                'penyelenggara' => 'Klinik Bisnis UMKM Diskop Kutim',
                'instruktur' => 'Rina Andriani, SE, M.Ak & Tim Fasilitator Kemasan',
                'lokasi' => 'Ruang Rapat Meranti, Kantor Bupati Kutai Timur',
                'mode' => 'offline',
                'tanggal_mulai' => Carbon::now()->addDays(18)->setTime(8, 30),
                'tanggal_selesai' => Carbon::now()->addDays(18)->setTime(16, 0),
                'kuota' => 35,
                'biaya' => 0.00,
                'link_zoom' => null,
                'banner_url' => null,
                'status' => 'upcoming',
                'syarat_peserta' => 'Pelaku usaha mikro dan kecil berdomisili di Kutai Timur.',
            ]
        );

        // Seed Sample Peserta Pelatihan
        PelatihanPeserta::firstOrCreate(
            ['pelatihan_id' => $p1->id, 'email' => 'budi.santoso@gmail.com'],
            [
                'nama_peserta' => 'Budi Santoso',
                'nama_usaha' => 'Kopi Robusta Sangatta',
                'nomor_hp' => '081345678901',
                'instansi' => 'Kelompok Tani Harapan Maju',
                'motivasi' => 'Ingin segera mengurus sertifikasi halal agar produk kopi bisa masuk supermarket di Sangatta & Balikpapan.',
                'status' => 'terdaftar',
            ]
        );

        PelatihanPeserta::firstOrCreate(
            ['pelatihan_id' => $p1->id, 'email' => 'ani.wijaya@gmail.com'],
            [
                'nama_peserta' => 'Ani Wijaya',
                'nama_usaha' => 'Dapur Kue Maknyus Sangatta',
                'nomor_hp' => '082233445566',
                'instansi' => 'Mandiri',
                'motivasi' => 'Mendapatkan pendampingan NIB dan sertifikat halal self declare.',
                'status' => 'terdaftar',
            ]
        );

        // 3. Seed Sample Survey Responses
        $surveys = [
            [
                'nama_responden' => 'H. Suryadi',
                'pekerjaan' => 'Pelaku Usaha Olahan Ikan',
                'modul' => 'umkm',
                'nilai_kemudahan' => 5,
                'nilai_kecepatan' => 5,
                'nilai_keramahan' => 5,
                'nilai_kemanfaatan' => 5,
                'saran_teks' => 'Portal ini sangat luar biasa, direktori usaha kami kini terdata rapi dan peta lokasinya sangat akurat. Terima kasih Pemkab Kutim!',
            ],
            [
                'nama_responden' => 'Dewi Anggraini',
                'pekerjaan' => 'Pengrajin Batik Sangatta',
                'modul' => 'bazar',
                'nilai_kemudahan' => 5,
                'nilai_kecepatan' => 4,
                'nilai_keramahan' => 5,
                'nilai_kemanfaatan' => 5,
                'saran_teks' => 'Pendaftaran bazar lewat website sangat ringkas, tidak perlu bolak-balik bawa proposal kertas ke kantor dinas lagi.',
            ],
            [
                'nama_responden' => 'Fajar Pratama',
                'pekerjaan' => 'Pemilik Kedai Kopi',
                'modul' => 'pelatihan',
                'nilai_kemudahan' => 5,
                'nilai_kecepatan' => 5,
                'nilai_keramahan' => 5,
                'nilai_kemanfaatan' => 5,
                'saran_teks' => 'Pelatihan digital marketing dan sertifikasi halal sangat bermanfaat. Materi yang diajarkan sangat aplikatif untuk bisnis kami.',
            ],
            [
                'nama_responden' => 'Sri Wahyuni',
                'pekerjaan' => 'Masyarakat Umum / Konsumen',
                'modul' => 'layanan_umum',
                'nilai_kemudahan' => 4,
                'nilai_kecepatan' => 5,
                'nilai_keramahan' => 5,
                'nilai_kemanfaatan' => 5,
                'saran_teks' => 'Desain website sangat bersih, elegan, dan informatif. Mudah mencari oleh-oleh khas Kutim saat keluarga datang berkunjung.',
            ],
        ];

        foreach ($surveys as $s) {
            SurveyKepuasan::create($s);
        }
    }
}
