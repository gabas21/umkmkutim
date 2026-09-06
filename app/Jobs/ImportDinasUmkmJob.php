<?php

namespace App\Jobs;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportDinasUmkmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Data baris UMKM yang akan diimport dalam 1 chunk
     */
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function handle(): void
    {
        $kategoriMap = Kategori::pluck('id', 'nama')->toArray();
        $defaultKategoriId = Kategori::first()?->id ?? 1;

        $daftarKecamatanKutim = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        foreach ($this->rows as $row) {
            $namaUsaha = trim($row['nama_usaha'] ?? '');
            if (empty($namaUsaha)) {
                continue;
            }

            // Normalisasi kecamatan
            $kecamatanInput = trim($row['kecamatan'] ?? 'Sangatta Utara');
            $matchedKecamatan = 'Sangatta Utara';
            foreach ($daftarKecamatanKutim as $kec) {
                if (strcasecmp($kec, $kecamatanInput) === 0) {
                    $matchedKecamatan = $kec;
                    break;
                }
            }

            // Resolve kategori
            $kategoriInput = trim($row['kategori'] ?? '');
            $kategoriId = $defaultKategoriId;
            foreach ($kategoriMap as $namaKat => $idKat) {
                if (stripos($kategoriInput, $namaKat) !== false || stripos($namaKat, $kategoriInput) !== false) {
                    $kategoriId = $idKat;
                    break;
                }
            }

            // Koordinat default (area Kutai Timur/Sangatta jika kosong)
            $lat = !empty($row['latitude']) ? (float)$row['latitude'] : (0.49 + (mt_rand(-500, 500) / 10000));
            $lng = !empty($row['longitude']) ? (float)$row['longitude'] : (117.54 + (mt_rand(-500, 500) / 10000));

            // Generate slug unik
            $baseSlug = Str::slug($namaUsaha);
            $slug = $baseSlug;
            $counter = 1;
            while (Umkm::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            Umkm::create([
                'nama_usaha' => $namaUsaha,
                'slug' => $slug,
                'kategori_id' => $kategoriId,
                'deskripsi' => $row['deskripsi'] ?? "Usaha {$namaUsaha} bergerak di bidang komoditas dan layanan lokal masyarakat {$matchedKecamatan}, Kutai Timur.",
                'alamat' => $row['alamat'] ?? "Jl. Poros {$matchedKecamatan}, Kutai Timur",
                'kecamatan' => $matchedKecamatan,
                'kelurahan_desa' => $row['kelurahan_desa'] ?? null,
                'location' => DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)"),
                'telepon' => $row['telepon'] ?? null,
                'email' => $row['email'] ?? null,
                'instagram' => $row['instagram'] ?? null,
                'website' => $row['website'] ?? null,
                'foto_utama' => $row['foto_utama'] ?? null,
                'rating' => isset($row['rating']) ? (float)$row['rating'] : 4.5,
                'jumlah_review' => isset($row['jumlah_review']) ? (int)$row['jumlah_review'] : 0,
                'jumlah_dilihat' => 0,
                'sumber_data' => 'import',
                'status_klaim' => 'belum_diklaim',
                'status' => 'active',
            ]);
        }
    }
}
