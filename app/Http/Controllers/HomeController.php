<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Http\Request;

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

        // Kategori dengan jumlah UMKM
        $kategoriList = Kategori::withCount(['umkm' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // UMKM Unggulan (rating tertinggi & terpopuler)
        $featuredUmkm = Umkm::active()
            ->withCoordinates()
            ->with('kategori')
            ->orderByDesc('rating')
            ->orderByDesc('jumlah_review')
            ->take(6)
            ->get();

        // Berita Terbaru
        $latestBerita = Berita::published()->take(3)->get();

        // Data titik peta untuk preview Leaflet (clustering)
        $mapPoints = Umkm::active()
            ->withCoordinates()
            ->selectRaw('id, nama_usaha, slug, kategori_id, kecamatan, rating, foto_utama, ST_X(location) as longitude, ST_Y(location) as latitude, status_klaim')
            ->with('kategori:id,nama,icon')
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
            'kategoriList',
            'featuredUmkm',
            'latestBerita',
            'mapPoints'
        ));
    }
}
