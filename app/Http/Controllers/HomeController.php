<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Pelatihan;
use App\Models\SurveyKepuasan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        $totalUmkm = Umkm::active()->count();
        $totalKategori = Kategori::count();
        $totalTerverifikasi = Umkm::where('status_klaim', 'terverifikasi')->count();
        $persenTerverifikasi = $totalUmkm > 0 ? round(($totalTerverifikasi / $totalUmkm) * 100, 1) : 0;

        // Data status perizinan / klaim untuk donat chart
        $statusKlaimData = Umkm::active()
            ->select('status_klaim', DB::raw('count(*) as total'))
            ->groupBy('status_klaim')
            ->get()
            ->pluck('total', 'status_klaim');

        // Kategori dengan jumlah UMKM
        $kategoriList = Kategori::withCount(['umkm' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // UMKM Unggulan (rating tertinggi & terpopuler, 8 item)
        $featuredUmkm = Umkm::active()
            ->withCoordinates()
            ->with('kategori')
            ->orderByDesc('rating')
            ->orderByDesc('jumlah_review')
            ->take(8)
            ->get();

        // Metrik Seluruh Kecamatan untuk Bar Visualisasi
        $kecamatanStats = Umkm::active()
            ->select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->get();
        $maxKecamatan = $kecamatanStats->max('total') ?: 1;

        // Data titik peta untuk preview Leaflet (clustering)
        $mapPoints = Umkm::active()
            ->withCoordinates()
            ->selectRaw('id, nama_usaha, slug, kategori_id, kecamatan, rating, foto_utama, ST_Latitude(location) as latitude, ST_Longitude(location) as longitude, status_klaim')
            ->with('kategori:id,nama,icon')
            ->take(300)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_usaha,
                    'slug' => $item->slug,
                    'kategori' => $item->kategori?->nama ?? 'Umum',
                    'icon' => $item->kategori?->icon ?? 'store',
                    'kecamatan' => $item->kecamatan,
                    'lat' => (float)$item->latitude,
                    'lng' => (float)$item->longitude,
                    'rating' => (float)$item->rating,
                    'status_klaim' => $item->status_klaim,
                    'url' => route('umkm.show', $item->slug),
                ];
            });

        return view('home', compact(
            'daftarKecamatan',
            'totalUmkm',
            'totalKategori',
            'totalTerverifikasi',
            'persenTerverifikasi',
            'statusKlaimData',
            'kategoriList',
            'featuredUmkm',
            'kecamatanStats',
            'maxKecamatan',
            'mapPoints'
        ));
    }
}
