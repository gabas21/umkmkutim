<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanPesertaController extends Controller
{
    public function store(Request $request, $slug)
    {
        $pelatihan = Pelatihan::withCount('peserta')->where('slug', $slug)->firstOrFail();

        if (in_array($pelatihan->status, ['closed', 'selesai'])) {
            return back()->with('error', 'Pendaftaran pelatihan ini telah ditutup.');
        }

        if ($pelatihan->sisa_kuota <= 0) {
            return back()->with('warning', 'Mohon maaf, kuota peserta pelatihan ini sudah penuh.');
        }

        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'nama_usaha' => 'nullable|string|max:255',
            'email' => 'required|email|max:100',
            'nomor_hp' => 'required|string|max:30',
            'instansi' => 'nullable|string|max:255',
            'motivasi' => 'nullable|string|max:1000',
        ]);

        $pelakuUsahaId = null;
        if (Auth::guard('pelaku_usaha')->check()) {
            $pelakuUsahaId = Auth::guard('pelaku_usaha')->id();
        }

        // Check if already registered with same email
        $existing = PelatihanPeserta::where('pelatihan_id', $pelatihan->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            return back()->with('warning', 'Alamat email ini sudah terdaftar sebagai peserta untuk pelatihan ini.');
        }

        PelatihanPeserta::create([
            'pelatihan_id' => $pelatihan->id,
            'pelaku_usaha_id' => $pelakuUsahaId,
            'nama_peserta' => $request->nama_peserta,
            'nama_usaha' => $request->nama_usaha,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'instansi' => $request->instansi,
            'motivasi' => $request->motivasi,
            'status' => 'terdaftar',
        ]);

        return back()->with('success', 'Selamat! Anda berhasil terdaftar pada pelatihan ini. Pengingat jadwal dan instruksi pelaksanaan akan dikirimkan via WhatsApp/Email.');
    }
}
