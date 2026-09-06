<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $beritaList = [
            [
                'judul' => 'Dinas Koperasi & UMKM Kutai Timur Gelar Fasilitasi Sertifikasi Halal Gratis 2026',
                'konten' => '<p>Dinas Koperasi dan UKM Kabupaten Kutai Timur kembali membuka program bantuan fasilitasi sertifikasi halal gratis (Sehati) bagi para pelaku usaha mikro di wilayah Sangatta dan sekitarnya. Program ini bertujuan mendorong produk olahan khas Kutim tembus pasar ritel modern dan ekspor.</p><p>Pendaftaran dibuka mulai bulan ini melalui portal resmi UMKM Kutai Timur atau dengan mendatangi kantor dinas secara langsung membawa kelengkapan NIB dan KTP.</p>',
                'kategori' => 'pengumuman',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Pameran Sangatta Expo 2026: Ratusan Produk Kerajinan Anyaman dan Kuliner Kutim Laris Manis',
                'konten' => '<p>Ajang pameran Sangatta Expo 2026 kembali menyedot ribuan pengunjung. Produk olahan amplang khas Sangatta, batik motif kelapa sawit dan wakaroros, serta kerajinan manik tradisional Dayak berhasil mencatatkan omzet transaksi yang membanggakan.</p><p>Pemerintah Kabupaten terus berkomitmen memberikan ruang promosi seluas-luasnya bagi para wirausahawan lokal.</p>',
                'kategori' => 'berita',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Tips UMKM: Cara Mengoptimalkan Pemasaran Digital dan Titik Lokasi Usaha di Google Maps & Portal Daerah',
                'konten' => '<p>Bagi pelaku UMKM yang baru merintis, pencatatan titik koordinat usaha yang akurat serta kehadiran di portal resmi daerah sangat krusial. Konsumen kini cenderung mencari rekomendasi usaha terdekat berbasis radius (nearby search).</p><p>Pastikan foto produk berkualitas tajam dan informasi jam operasional selalu diperbarui secara berkala.</p>',
                'kategori' => 'tips',
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($beritaList as $item) {
            Berita::firstOrCreate(
                ['slug' => Str::slug($item['judul'])],
                array_merge($item, ['slug' => Str::slug($item['judul'])])
            );
        }
    }
}
