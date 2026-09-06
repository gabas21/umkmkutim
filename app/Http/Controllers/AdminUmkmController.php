<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Http\Request;

class AdminUmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['kategori', 'klaimAktif.pelakuUsaha']);

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_usaha', 'like', $keyword)
                  ->orWhere('alamat', 'like', $keyword);
            });
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        $umkmList = $query->latest()->paginate(15)->withQueryString();

        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        return view('admin.umkm.index', compact('umkmList', 'daftarKecamatan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $umkm = Umkm::findOrFail($id);
        $umkm->update(['status' => $request->status]);

        return back()->with('success', "Status UMKM '{$umkm->nama_usaha}' berhasil diubah menjadi {$request->status}.");
    }
}
