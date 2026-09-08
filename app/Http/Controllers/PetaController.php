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
            'Sangatta Utara'   => [0.5051, 117.5398, 13],
            'Sangatta Selatan' => [0.4697, 117.5300, 13],
            'Bengalon'         => [0.6604, 117.5752, 12],
            'Kongbeng'         => [1.2803, 117.0672, 12],
            'Muara Wahau'      => [1.1191, 116.8794, 12],
            'Sangkulirang'     => [1.0622, 118.0864, 12],
            'Teluk Pandan'     => [0.1840, 117.3162, 12],
            'Rantau Pulung'    => [0.6052, 117.1911, 12],
            'Kaliorang'        => [0.8715, 117.8595, 12],
            'Kaubun'           => [1.0197, 117.7849, 12],
            'Muara Bengkal'    => [0.3662, 116.7802, 12],
            'Muara Ancalong'   => [0.4791, 116.5102, 12],
            'Busang'           => [0.9276, 116.2954, 12],
            'Telen'            => [0.8628, 116.7715, 12],
            'Sandaran'         => [1.0063, 118.4672, 12],
            'Karangan'         => [1.3267, 117.5958, 12],
            'Batu Ampar'       => [0.6700, 116.8975, 12],
            'Long Mesangat'    => [0.5799, 116.7119, 12],
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
