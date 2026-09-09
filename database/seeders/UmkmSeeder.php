<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan kategori sudah ada di database
        if (Kategori::count() === 0) {
            $this->command?->info('Kategori belum tersedia. Menjalankan KategoriSeeder terlebih dahulu...');
            $this->call(KategoriSeeder::class);
        }

        $kategoriMap = Kategori::pluck('id', 'nama')->toArray();
        $defaultKategoriId = Kategori::first()?->id ?? 1;

        // Bersihkan data umkm sebelumnya agar tidak terjadi duplikasi slug
        Schema::disableForeignKeyConstraints();
        DB::table('umkm')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvPath = database_path('seeders/data/umkm_kutai_timur.csv');

        if (file_exists($csvPath)) {
            $this->seedFromCsv($csvPath, $kategoriMap, $defaultKategoriId);
        } else {
            $this->command?->warn("File CSV data tidak ditemukan di {$csvPath}. Menjalankan fallback generator data representatif 18 kecamatan...");
            $this->seedRepresentativeData($kategoriMap, $defaultKategoriId);
        }

        // 2. Perbarui tabel umkm_grid_cluster agar titik dan kluster langsung tampil di peta
        $this->refreshGridCluster();
    }

    /**
     * Seed dari file CSV lengkap (Data Dinas / Geospasial Kutim)
     */
    protected function seedFromCsv(string $csvPath, array $kategoriMap, int $defaultKategoriId): void
    {
        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command?->error("Gagal membuka file CSV: {$csvPath}");
            return;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return;
        }

        $this->command?->info("Memulai seeding data UMKM dari CSV: {$csvPath}...");

        $batch = [];
        $batchSize = 500;
        $totalInserted = 0;
        $usedSlugs = [];
        $slugCounters = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 6) {
                continue;
            }

            // Kolom CSV: nama_usaha, kategori_nama, kecamatan, alamat, latitude, longitude, telepon, pemilik
            $namaUsaha = trim($row[0]);
            $kategoriNama = trim($row[1] ?? '');
            $kecamatan = trim($row[2] ?? 'Sangatta Utara');
            $alamat = trim($row[3] ?? "Jl. Poros {$kecamatan}, Kutai Timur");
            $lat = (float) ($row[4] ?? 0.5051);
            $lng = (float) ($row[5] ?? 117.5398);
            $telepon = !empty($row[6]) ? trim($row[6]) : null;
            $pemilik = !empty($row[7]) ? trim($row[7]) : null;

            if (empty($namaUsaha)) {
                continue;
            }

            // Tentukan kategori_id
            $kategoriId = $kategoriMap[$kategoriNama] ?? $defaultKategoriId;

            // Buat slug unik
            $baseSlug = Str::slug($namaUsaha);
            if (empty($baseSlug)) {
                $baseSlug = 'umkm-kutim-' . ($totalInserted + count($batch) + 1);
            }

            if (isset($usedSlugs[$baseSlug])) {
                $counter = ++$slugCounters[$baseSlug];
                $slug = "{$baseSlug}-{$counter}";
            } else {
                $usedSlugs[$baseSlug] = true;
                $slugCounters[$baseSlug] = 1;
                $slug = $baseSlug;
            }

            // Rating & review bervariasi secara realistis
            $rating = mt_rand(40, 50) / 10;
            $jumlahReview = mt_rand(2, 45);
            $statusKlaim = (mt_rand(1, 100) <= 20) ? 'terverifikasi' : 'belum_diklaim';

            $deskripsi = "Usaha {$namaUsaha}" . ($pemilik ? " dikelola oleh {$pemilik}," : "") . " melayani masyarakat di kawasan {$kecamatan}, Kabupaten Kutai Timur.";

            $batch[] = [
                'nama_usaha'      => $namaUsaha,
                'slug'            => $slug,
                'kategori_id'     => $kategoriId,
                'deskripsi'       => $deskripsi,
                'alamat'          => $alamat,
                'kecamatan'       => $kecamatan,
                'kelurahan_desa'  => null,
                'location'        => DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)"),
                'telepon'         => $telepon,
                'email'           => null,
                'instagram'       => null,
                'website'         => null,
                'foto_utama'      => null,
                'foto_galeri'     => null,
                'jam_operasional' => json_encode([
                    'senin_jumat' => '08:00 - 21:00 WITA',
                    'sabtu_minggu' => '08:00 - 22:00 WITA'
                ]),
                'rating'          => $rating,
                'jumlah_review'   => $jumlahReview,
                'jumlah_dilihat'  => mt_rand(10, 350),
                'sumber_data'     => 'import',
                'status_klaim'    => $statusKlaim,
                'status'          => 'active',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('umkm')->insert($batch);
                $totalInserted += count($batch);
                $batch = [];
                if ($this->command) {
                    $this->command->getOutput()->write(".");
                }
            }
        }

        if (!empty($batch)) {
            DB::table('umkm')->insert($batch);
            $totalInserted += count($batch);
        }

        fclose($handle);
        $this->command?->newLine();
        $this->command?->info("✓ Selesai seeding: {$totalInserted} data UMKM berhasil dimasukkan ke tabel 'umkm'!");
    }

    /**
     * Fallback: Seed data representatif jika CSV tidak disertakan
     */
    protected function seedRepresentativeData(array $kategoriMap, int $defaultKategoriId): void
    {
        $this->command?->info('Men-generate data UMKM representatif untuk 18 kecamatan di Kutai Timur...');

        $kecamatanCoords = [
            'Sangatta Utara'   => [0.5051, 117.5398],
            'Sangatta Selatan' => [0.4697, 117.5300],
            'Bengalon'         => [0.6604, 117.5752],
            'Kongbeng'         => [1.2803, 117.0672],
            'Muara Wahau'      => [1.1191, 116.8794],
            'Sangkulirang'     => [1.0622, 118.0864],
            'Teluk Pandan'     => [0.1840, 117.3162],
            'Rantau Pulung'    => [0.6052, 117.1911],
            'Kaliorang'        => [0.8715, 117.8595],
            'Kaubun'           => [1.0197, 117.7849],
            'Muara Bengkal'    => [0.3662, 116.7802],
            'Muara Ancalong'   => [0.4791, 116.5102],
            'Busang'           => [0.9276, 116.2954],
            'Telen'            => [0.8628, 116.7715],
            'Sandaran'         => [1.0063, 118.4672],
            'Karangan'         => [1.3267, 117.5958],
            'Batu Ampar'       => [0.6700, 116.8975],
            'Long Mesangat'    => [0.5799, 116.7119],
        ];

        $templateUsaha = [
            ['nama' => 'Warung Makan Nasi Kuning Barokah', 'kat' => 'Kuliner & Makanan Khas'],
            ['nama' => 'Kopi Robusta Sangatta Rasa Alam', 'kat' => 'Kuliner & Makanan Khas'],
            ['nama' => 'Amplang Tenggiri Asli Kutim', 'kat' => 'Kuliner & Makanan Khas'],
            ['nama' => 'Batik Wakaroros Khas Kutai Timur', 'kat' => 'Batik & Fashion Khas Kutim'],
            ['nama' => 'Kerajinan Manik & Anyaman Rotan Dayak', 'kat' => 'Kriya & Kerajinan Tradisional'],
            ['nama' => 'Madu Kelulut Hutan Asli', 'kat' => 'Kesehatan & Herbal Dayak'],
            ['nama' => 'Toko Kelontong Berkah Sejahtera', 'kat' => 'Perdagangan & Kelontong'],
            ['nama' => 'Hasil Laut Nelayan Pesisir', 'kat' => 'Kelautan & Perikanan'],
            ['nama' => 'Jasa Percetakan & Fotocopy Mandiri', 'kat' => 'Jasa & Percetakan'],
            ['nama' => 'Grosir Sayur & Buah Organik', 'kat' => 'Agribisnis & Hasil Bumi'],
            ['nama' => 'Ayam Bakar Madu Borneo', 'kat' => 'Kuliner & Makanan Khas'],
            ['nama' => 'Bakso Sapi & Mie Pangsit Mas Joko', 'kat' => 'Kuliner & Makanan Khas'],
            ['nama' => 'Bengkel Motor Berkah Abadi', 'kat' => 'Jasa & Percetakan'],
            ['nama' => 'Pakaian Muslimah & Busana Tenun', 'kat' => 'Batik & Fashion Khas Kutim'],
            ['nama' => 'Kios Buah Segar Poros Trans', 'kat' => 'Agribisnis & Hasil Bumi'],
        ];

        $batch = [];
        $totalInserted = 0;
        $now = now();

        foreach ($kecamatanCoords as $kecamatan => $center) {
            $baseLat = $center[0];
            $baseLng = $center[1];

            // Buat 30 titik untuk tiap kecamatan
            for ($i = 1; $i <= 30; $i++) {
                $template = $templateUsaha[array_rand($templateUsaha)];
                $namaUsaha = "{$template['nama']} {$kecamatan} #{$i}";
                $kategoriId = $kategoriMap[$template['kat']] ?? $defaultKategoriId;

                // Koordinat random di sekitar pusat kecamatan (radius ~2-5 km)
                $latOffset = (mt_rand(-200, 200) / 10000);
                $lngOffset = (mt_rand(-200, 200) / 10000);
                $lat = $baseLat + $latOffset;
                $lng = $baseLng + $lngOffset;

                $slug = Str::slug("{$namaUsaha}-" . Str::random(5));
                $rating = mt_rand(40, 50) / 10;
                $jumlahReview = mt_rand(3, 35);
                $statusKlaim = ($i % 4 === 0) ? 'terverifikasi' : 'belum_diklaim';

                $batch[] = [
                    'nama_usaha'      => $namaUsaha,
                    'slug'            => $slug,
                    'kategori_id'     => $kategoriId,
                    'deskripsi'       => "Usaha {$namaUsaha} beroperasi aktif dan menyediakan produk unggulan daerah di kecamatan {$kecamatan}.",
                    'alamat'          => "Jl. Utama RT 0{$i}, Kecamatan {$kecamatan}, Kutai Timur",
                    'kecamatan'       => $kecamatan,
                    'kelurahan_desa'  => null,
                    'location'        => DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)"),
                    'telepon'         => '08' . mt_rand(1111111111, 9999999999),
                    'email'           => null,
                    'instagram'       => null,
                    'website'         => null,
                    'foto_utama'      => null,
                    'foto_galeri'     => null,
                    'jam_operasional' => json_encode([
                        'senin_jumat' => '08:00 - 21:00 WITA',
                        'sabtu_minggu' => '08:00 - 22:00 WITA'
                    ]),
                    'rating'          => $rating,
                    'jumlah_review'   => $jumlahReview,
                    'jumlah_dilihat'  => mt_rand(15, 200),
                    'sumber_data'     => 'import',
                    'status_klaim'    => $statusKlaim,
                    'status'          => 'active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];

                if (count($batch) >= 200) {
                    DB::table('umkm')->insert($batch);
                    $totalInserted += count($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            DB::table('umkm')->insert($batch);
            $totalInserted += count($batch);
        }

        $this->command?->info("✓ Selesai seeding: {$totalInserted} data UMKM representatif untuk 18 kecamatan berhasil dibuat!");
    }

    /**
     * Refresh tabel agregat umkm_grid_cluster
     */
    protected function refreshGridCluster(): void
    {
        if (!Schema::hasTable('umkm_grid_cluster')) {
            return;
        }

        $this->command?->info('Memperbarui tabel umkm_grid_cluster agar peta langsung menampilkan kluster & titik...');

        DB::statement("TRUNCATE TABLE umkm_grid_cluster");

        DB::statement("
            INSERT INTO umkm_grid_cluster (grid_lat, grid_lng, jumlah_umkm, terverifikasi_count, kecamatan, sample_umkm_id, created_at, updated_at)
            SELECT 
                ROUND(ST_Latitude(location), 2) as grid_lat,
                ROUND(ST_Longitude(location), 2) as grid_lng,
                COUNT(*) as jumlah_umkm,
                SUM(CASE WHEN status_klaim = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi_count,
                SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT kecamatan ORDER BY kecamatan SEPARATOR ', '), ', ', 1) as kecamatan,
                MIN(id) as sample_umkm_id,
                NOW(),
                NOW()
            FROM umkm
            WHERE status = 'active'
            GROUP BY grid_lat, grid_lng
        ");

        // Perbarui versi cache agar browser dan server langsung menyajikan data terbaru
        $newVersion = ((int) Cache::get('umkm_cluster_cache_ver', 1)) + 1;
        Cache::forever('umkm_cluster_cache_ver', $newVersion);

        $totalClusters = DB::table('umkm_grid_cluster')->count();
        $this->command?->info("✓ Grid cluster berhasil diperbarui: {$totalClusters} sel kluster siap dirender di peta!");
    }
}
