<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use Illuminate\Http\Request;

class AdminPelatihanPesertaController extends Controller
{
    public function index($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $pesertaList = PelatihanPeserta::where('pelatihan_id', $id)
            ->with('pelakuUsaha')
            ->latest()
            ->paginate(15);

        return view('admin.pelatihan.peserta', compact('pelatihan', 'pesertaList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:terdaftar,hadir,tidak_hadir',
        ]);

        $peserta = PelatihanPeserta::findOrFail($id);
        $peserta->update(['status' => $request->status]);

        return back()->with('success', "Status kehadiran '{$peserta->nama_peserta}' berhasil diperbarui.");
    }
}
