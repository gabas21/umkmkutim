<?php

namespace App\Http\Controllers;

use App\Models\KlaimUsaha;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KlaimController extends Controller
{
    public function create($slug)
    {
        $umkm = Umkm::where('slug', $slug)->firstOrFail();

        if ($umkm->status_klaim === 'terverifikasi') {
            return redirect()->route('umkm.show', $slug)->with('error', 'Usaha ini sudah berhasil diklaim dan terverifikasi oleh pemilik sah.');
        }

        $pelaku = Auth::guard('pelaku_usaha')->user();

        // Cek apakah sudah pernah mengajukan klaim yang masih pending
        $existingClaim = KlaimUsaha::where('umkm_id', $umkm->id)
            ->where('pelaku_usaha_id', $pelaku->id)
            ->where('status', 'menunggu')
            ->first();

        if ($existingClaim) {
            return redirect()->route('dashboard.pelaku')->with('info', 'Anda sudah mengajukan klaim untuk usaha ini dan sedang menunggu verifikasi admin.');
        }

        return view('klaim.create', compact('umkm'));
    }

    public function store(Request $request, $slug)
    {
        $umkm = Umkm::where('slug', $slug)->firstOrFail();
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $request->validate([
            'dokumen_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'dokumen_bukti_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'catatan_pemohon' => 'nullable|string|max:1000',
        ], [
            'dokumen_ktp.required' => 'Dokumen identitas (KTP) wajib diunggah sebagai bukti kepemilikan.',
            'dokumen_ktp.max' => 'Ukuran file KTP maksimal 3 MB.',
            'dokumen_bukti_usaha.max' => 'Ukuran file bukti usaha maksimal 3 MB.',
        ]);

        $pathKtp = $request->file('dokumen_ktp')->store('dokumen/ktp', 'public');
        $pathBukti = null;
        if ($request->hasFile('dokumen_bukti_usaha')) {
            $pathBukti = $request->file('dokumen_bukti_usaha')->store('dokumen/bukti_usaha', 'public');
        }

        KlaimUsaha::create([
            'umkm_id' => $umkm->id,
            'pelaku_usaha_id' => $pelaku->id,
            'dokumen_ktp' => $pathKtp,
            'dokumen_bukti_usaha' => $pathBukti,
            'catatan_pemohon' => $request->catatan_pemohon,
            'status' => 'menunggu',
        ]);

        // Update status klaim UMKM menjadi menunggu_verifikasi
        $umkm->update(['status_klaim' => 'menunggu_verifikasi']);

        return redirect()->route('dashboard.pelaku')->with('success', "Pengajuan klaim untuk '{$umkm->nama_usaha}' berhasil dikirim! Tim Admin Dinas Koperasi & UMKM akan segera memverifikasi dokumen Anda.");
    }
}
