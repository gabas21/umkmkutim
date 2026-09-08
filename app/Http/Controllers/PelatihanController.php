<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'upcoming');
        $mode = $request->get('mode');

        $query = Pelatihan::withCount('peserta');

        if ($status === 'upcoming') {
            $query->whereIn('status', ['upcoming', 'ongoing'])->orderBy('tanggal_mulai', 'asc');
        } elseif ($status === 'selesai') {
            $query->whereIn('status', ['selesai', 'closed'])->orderBy('tanggal_mulai', 'desc');
        } else {
            $query->orderBy('tanggal_mulai', 'asc');
        }

        if ($mode && in_array($mode, ['offline', 'online', 'hybrid'])) {
            $query->where('mode', $mode);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('penyelenggara', 'like', "%{$q}%")
                    ->orWhere('instruktur', 'like', "%{$q}%");
            });
        }

        $pelatihans = $query->paginate(9)->withQueryString();

        $totalUpcoming = Pelatihan::whereIn('status', ['upcoming', 'ongoing'])->count();
        $totalSelesai = Pelatihan::whereIn('status', ['selesai', 'closed'])->count();

        return view('pelatihan.index', compact('pelatihans', 'status', 'mode', 'totalUpcoming', 'totalSelesai'));
    }

    public function show($slug)
    {
        $pelatihan = Pelatihan::withCount('peserta')->where('slug', $slug)->firstOrFail();

        $otherPelatihans = Pelatihan::where('id', '!=', $pelatihan->id)
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        return view('pelatihan.show', compact('pelatihan', 'otherPelatihans'));
    }
}
