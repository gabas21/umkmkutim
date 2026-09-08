<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    /**
     * PATCH (performance): controller ini SEBELUMNYA menjalankan
     *   Umkm::active()->withCoordinates()->with('kategori')->orderByDesc('rating')->get()
     * yaitu SELECT seluruh UMKM aktif (bisa puluhan ribu baris) + eager-load kategori,
     * lalu di-map jadi $mapData / $umkmItems -- padahal dua variable itu TIDAK PERNAH
     * dipakai di resources/views/peta/index.blade.php (data peta sepenuhnya datang dari
     * AJAX call ke /api/umkm/clusters, lihat UmkmClusterController).
     *
     * Efeknya: setiap kali ada yang buka halaman /peta, server diam-diam full-scan +
     * hydrate seluruh tabel umkm dulu sebelum ngerender apa-apa. Di skala 1rb data masih
     * "kerasa ringan", tapi di skala 45rb data ini bisa nyumbang ratusan ms - beberapa
     * detik page load TANPA MANFAAT SAMA SEKALI. Query mati ini dihapus total di bawah.
     * Yang tersisa cuma agregat ringan (COUNT) buat header stats.
     */
    public function index(Request $request)
    {
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        $kategoriList = Kategori::withCount(['umkm' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // Cuma COUNT (aggregate, indexed) -- bukan full row fetch.
        $totalUmkm = Umkm::active()->count();
        $totalTerverifikasi = Umkm::where('status_klaim', 'terverifikasi')->count();

        // Koordinat referensi pusat tiap kecamatan untuk fitur auto-pan
        $kecamatanCoords = [
            'Sangatta Utara' => [0.493, 117.545, 13],
            'Sangatta Selatan' => [0.470, 117.550, 13],
            'Bengalon' => [0.725, 117.575, 12],
            'Kongbeng' => [1.173, 116.945, 12],
            'Muara Wahau' => [1.116, 116.890, 12],
            'Sangkulirang' => [0.995, 117.975, 12],
            'Teluk Pandan' => [0.350, 117.430, 12],
            'Rantau Pulung' => [0.610, 117.380, 12],
            'Kaliorang' => [0.835, 117.750, 12],
            'Kaubun' => [0.920, 117.650, 12],
            'Muara Bengkal' => [0.480, 116.640, 11],
            'Muara Ancalong' => [0.550, 116.590, 11],
            'Busang' => [1.020, 116.290, 11],
            'Telen' => [1.130, 116.620, 11],
            'Sandaran' => [1.120, 118.420, 11],
            'Karangan' => [1.320, 117.750, 11],
            'Batu Ampar' => [0.650, 116.880, 11],
            'Long Mesangat' => [0.820, 116.730, 11],
        ];

        return view('peta.index', compact(
            'daftarKecamatan',
            'kategoriList',
            'kecamatanCoords',
            'totalUmkm',
            'totalTerverifikasi'
        ));
    }
}
