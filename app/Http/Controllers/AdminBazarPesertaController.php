<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use App\Models\BazarPeserta;
use Illuminate\Http\Request;

class AdminBazarPesertaController extends Controller
{
    public function index($id)
    {
        $bazar = Bazar::findOrFail($id);
        $pesertaList = BazarPeserta::where('bazar_id', $id)
            ->with('pelakuUsaha')
            ->latest()
            ->paginate(15);

        return view('admin.bazar.peserta', compact('bazar', 'pesertaList'));
    }

    public function approve(Request $request, $id)
    {
        $peserta = BazarPeserta::findOrFail($id);
        $peserta->update(['status' => 'diterima']);

        return back()->with('success', "Peserta '{$peserta->nama_usaha}' berhasil diterima sebagai peserta bazar.");
    }

    public function reject(Request $request, $id)
    {
        $peserta = BazarPeserta::findOrFail($id);
        $peserta->update(['status' => 'ditolak']);

        return back()->with('warning', "Peserta '{$peserta->nama_usaha}' ditolak dari daftar peserta bazar.");
    }
}
