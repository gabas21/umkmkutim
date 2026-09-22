<?php

namespace App\Http\Controllers;

use App\Models\PelakuUsaha;
use Illuminate\Http\Request;

class AdminPelakuUsahaController extends Controller
{
    public function index(Request $request)
    {
        $query = PelakuUsaha::withCount(['klaimUsaha', 'umkmTerverifikasi']);

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', $keyword)
                  ->orWhere('email', 'like', $keyword);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pelakuList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pelaku-usaha.index', compact('pelakuList'));
    }

    public function show($id)
    {
        $pelaku = PelakuUsaha::findOrFail($id);

        $klaimList = $pelaku->klaimUsaha()->with('umkm')->latest()->get();
        $umkmDimiliki = $pelaku->umkmTerverifikasi()->with('kategori')->get();

        return view('admin.pelaku-usaha.show', compact('pelaku', 'klaimList', 'umkmDimiliki'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,pending,banned',
        ]);

        $pelaku = PelakuUsaha::findOrFail($id);
        $pelaku->update(['status' => $request->status]);

        return back()->with('success', "Status akun '{$pelaku->nama}' berhasil diubah menjadi {$request->status}.");
    }
}
