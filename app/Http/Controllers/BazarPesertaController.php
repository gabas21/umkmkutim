<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use App\Models\BazarPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BazarPesertaController extends Controller
{
    public function store(Request $request, $slug)
    {
        $bazar = Bazar::where('slug', $slug)->firstOrFail();

        if (in_array($bazar->status, ['closed', 'selesai'])) {
            return back()->with('error', 'Pendaftaran bazar ini telah ditutup.');
        }

        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nama_usaha' => 'required|string|max:255',
            'kategori_produk' => 'required|string|max:100',
            'nomor_hp' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',
            'deskripsi_produk' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pelakuUsahaId = null;
        if (Auth::guard('pelaku_usaha')->check()) {
            $pelakuUsahaId = Auth::guard('pelaku_usaha')->id();
        }

        // Check if already registered with same phone
        $existing = BazarPeserta::where('bazar_id', $bazar->id)
            ->where('nomor_hp', $request->nomor_hp)
            ->first();

        if ($existing) {
            return back()->with('warning', 'Nomor telepon ini sudah terdaftar sebagai calon peserta untuk event ini.');
        }

        BazarPeserta::create([
            'bazar_id' => $bazar->id,
            'pelaku_usaha_id' => $pelakuUsahaId,
            'nama_pemilik' => $request->nama_pemilik,
            'nama_usaha' => $request->nama_usaha,
            'kategori_produk' => $request->kategori_produk,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'deskripsi_produk' => $request->deskripsi_produk,
            'catatan' => $request->catatan,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pendaftaran berhasil dikirim! Tim Diskop & UMKM Kutim akan menghubungi Anda melalui WhatsApp untuk verifikasi stand/lapak.');
    }
}
