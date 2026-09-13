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

        if ($bazar->sisa_kuota <= 0) {
            return back()->with('warning', 'Mohon maaf, kuota lapak bazar ini sudah penuh.');
        }

        if (!Auth::guard('pelaku_usaha')->check()) {
            return redirect()->route('register')->with('warning', 'Silakan buat akun terlebih dahulu untuk mendaftar peserta bazar.');
        }

        $user = Auth::guard('pelaku_usaha')->user();
        $pelakuUsahaId = $user->id;

        // Check if already registered
        $existing = BazarPeserta::where('bazar_id', $bazar->id)
            ->where(function ($q) use ($pelakuUsahaId, $user) {
                $q->where('pelaku_usaha_id', $pelakuUsahaId);
                if (!empty($user->nomor_telepon)) {
                    $q->orWhere('nomor_hp', $user->nomor_telepon);
                }
            })
            ->first();

        if ($existing) {
            return back()->with('warning', 'Anda sudah terdaftar sebagai calon peserta untuk event bazar ini.');
        }

        $umkm = method_exists($user, 'umkmTerverifikasi') ? $user->umkmTerverifikasi()->first() : null;
        $namaPemilik = $request->input('nama_pemilik', $user->nama);
        $namaUsaha = $request->input('nama_usaha', $umkm ? $umkm->nama_usaha : ('Usaha ' . $user->nama));
        $kategoriProduk = $request->input('kategori_produk', $umkm && $umkm->kategori ? $umkm->kategori->nama : 'Kuliner & Aneka Produk');
        $nomorHp = $request->input('nomor_hp', $user->nomor_telepon ?: '081200000000');
        $email = $request->input('email', $user->email);
        $deskripsi = $request->input('deskripsi_produk', $umkm ? $umkm->deskripsi : 'Partisipasi stan Bazar UMKM Kutai Timur');
        $catatan = $request->input('catatan', null);

        BazarPeserta::create([
            'bazar_id' => $bazar->id,
            'pelaku_usaha_id' => $pelakuUsahaId,
            'nama_pemilik' => $namaPemilik,
            'nama_usaha' => $namaUsaha,
            'kategori_produk' => $kategoriProduk,
            'nomor_hp' => $nomorHp,
            'email' => $email,
            'deskripsi_produk' => $deskripsi,
            'catatan' => $catatan,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pendaftaran berhasil dikirim! Tim Diskop & UMKM Kutim akan menghubungi Anda melalui WhatsApp untuk verifikasi stand/lapak.');
    }
}
