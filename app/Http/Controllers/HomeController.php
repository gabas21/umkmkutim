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
            ->take(5)
            ->get();

        // Bazar Terdekat (Upcoming)
        $upcomingBazar = Bazar::withCount(['peserta' => function ($q) {
            $q->whereIn('status', ['pending', 'diterima']);
        }])
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(2)
            ->get();

        // Pelatihan Mendatang
        $upcomingPelatihan = Pelatihan::withCount('peserta')
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        // Berita Terbaru
        $latestBerita = Berita::published()->take(3)->get();

        // Metrik Laporan untuk Dashboard Section
        $kecamatanStats = Umkm::active()
            ->select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->take(6)
            ->get();
        $maxKecamatan = $kecamatanStats->max('total') ?: 1;

        // Metrik Survey Kepuasan untuk Survey Section
        $totalSurvey = SurveyKepuasan::count();
        $avgKemudahan = SurveyKepuasan::avg('nilai_kemudahan') ?: 4.8;
        $avgKecepatan = SurveyKepuasan::avg('nilai_kecepatan') ?: 4.7;
        $avgKeramahan = SurveyKepuasan::avg('nilai_keramahan') ?: 4.9;
        $avgKemanfaatan = SurveyKepuasan::avg('nilai_kemanfaatan') ?: 4.8;
        $indeksRataRata = round(($avgKemudahan + $avgKecepatan + $avgKeramahan + $avgKemanfaatan) / 4, 2);
        $indeksPersen = round(($indeksRataRata / 5) * 100, 1);

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
            'kategoriList',
            'featuredUmkm',
            'upcomingBazar',
            'upcomingPelatihan',
            'latestBerita',
            'kecamatanStats',
            'maxKecamatan',
            'totalSurvey',
            'avgKemudahan',
            'avgKecepatan',
            'avgKeramahan',
            'avgKemanfaatan',
            'indeksRataRata',
            'indeksPersen',
            'mapPoints'
        ));
    }
}
