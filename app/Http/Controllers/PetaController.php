<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Http\Request;

class PetaController extends Controller
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

        $kategoriList = Kategori::withCount(['umkm' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $query = Umkm::active()->withCoordinates()->with('kategori:id,nama,icon');

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_usaha', 'like', $keyword)
                  ->orWhere('alamat', 'like', $keyword);
            });
        }

        $umkmItems = $query->orderByDesc('rating')->get();

        $mapData = $umkmItems->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_usaha,
                'slug' => $item->slug,
                'alamat' => $item->alamat,
                'kecamatan' => $item->kecamatan,
                'kategori' => $item->kategori?->nama ?? 'Umum',
                'icon' => $item->kategori?->icon ?? 'store',
                'lat' => (float)$item->latitude,
                'lng' => (float)$item->longitude,
                'rating' => (float)$item->rating,
                'jumlah_review' => (int)$item->jumlah_review,
                'status_klaim' => $item->status_klaim,
                'url' => route('umkm.show', $item->slug),
            ];
        });

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
            'umkmItems',
            'mapData',
            'kecamatanCoords'
        ));
    }
}
