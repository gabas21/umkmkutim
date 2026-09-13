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

        if (!Auth::guard('pelaku_usaha')->check()) {
            return redirect()->route('register')->with('warning', 'Silakan buat akun terlebih dahulu untuk mendaftar pelatihan.');
        }

        $user = Auth::guard('pelaku_usaha')->user();
        $pelakuUsahaId = $user->id;

        // Check if already registered
        $existing = PelatihanPeserta::where('pelatihan_id', $pelatihan->id)
            ->where(function ($q) use ($pelakuUsahaId, $user) {
                $q->where('pelaku_usaha_id', $pelakuUsahaId);
                if (!empty($user->email)) {
                    $q->orWhere('email', $user->email);
                }
            })
            ->first();

        if ($existing) {
            return back()->with('warning', 'Anda sudah terdaftar sebagai peserta untuk pelatihan ini.');
        }

        $umkm = method_exists($user, 'umkmTerverifikasi') ? $user->umkmTerverifikasi()->first() : null;
        $namaPeserta = $request->input('nama_peserta', $user->nama);
        $namaUsaha = $request->input('nama_usaha', $umkm ? $umkm->nama_usaha : ('Usaha ' . $user->nama));
        $email = $request->input('email', $user->email);
        $nomorHp = $request->input('nomor_hp', $user->nomor_telepon ?: '081200000000');
        $instansi = $request->input('instansi', $umkm ? ('UMKM ' . $umkm->nama_usaha) : 'Mandiri');
        $motivasi = $request->input('motivasi', 'Mengembangkan usaha dan kapasitas wirausaha mandiri.');

        PelatihanPeserta::create([
            'pelatihan_id' => $pelatihan->id,
            'pelaku_usaha_id' => $pelakuUsahaId,
            'nama_peserta' => $namaPeserta,
            'nama_usaha' => $namaUsaha,
            'email' => $email,
            'nomor_hp' => $nomorHp,
            'instansi' => $instansi,
            'motivasi' => $motivasi,
            'status' => 'terdaftar',
        ]);

        return back()->with('success', 'Selamat! Anda berhasil terdaftar pada pelatihan ini. Pengingat jadwal dan instruksi pelaksanaan akan dikirimkan via WhatsApp/Email.');
    }
}
