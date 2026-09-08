<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use Illuminate\Http\Request;

class BazarController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'upcoming');

        $query = Bazar::withCount(['peserta' => function ($q) {
            $q->whereIn('status', ['pending', 'diterima']);
        }]);

        if ($status === 'upcoming') {
            $query->whereIn('status', ['upcoming', 'ongoing'])->orderBy('tanggal_mulai', 'asc');
        } elseif ($status === 'selesai') {
            $query->whereIn('status', ['closed', 'selesai'])->orderBy('tanggal_mulai', 'desc');
        } else {
            $query->orderBy('tanggal_mulai', 'asc');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_bazar', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%");
            });
        }

        $bazars = $query->paginate(9)->withQueryString();

        $totalUpcoming = Bazar::whereIn('status', ['upcoming', 'ongoing'])->count();
        $totalSelesai = Bazar::whereIn('status', ['closed', 'selesai'])->count();

        return view('bazar.index', compact('bazars', 'status', 'totalUpcoming', 'totalSelesai'));
    }

    public function show($slug)
    {
        $bazar = Bazar::withCount(['peserta' => function ($q) {
            $q->whereIn('status', ['pending', 'diterima']);
        }])->where('slug', $slug)->firstOrFail();

        $pesertaTerdaftar = $bazar->peserta()
            ->where('status', 'diterima')
            ->select('nama_usaha', 'kategori_produk', 'created_at')
            ->latest()
            ->take(12)
            ->get();

        $otherBazars = Bazar::where('id', '!=', $bazar->id)
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        return view('bazar.show', compact('bazar', 'pesertaTerdaftar', 'otherBazars'));
    }
}
