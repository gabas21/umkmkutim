<?php

namespace App\Http\Controllers;

use App\Models\KlaimUsaha;
use App\Models\LayananUsaha;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelakuLayananController extends Controller
{
    /**
     * Pastikan pelaku usaha yang login benar-benar pemilik sah (klaim disetujui)
     * atas UMKM dengan id tersebut. Mengembalikan model Umkm jika valid.
     */
    private function authorizeUmkm($umkmId): Umkm
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $hasApprovedClaim = KlaimUsaha::where('pelaku_usaha_id', $pelaku->id)
            ->where('umkm_id', $umkmId)
            ->where('status', 'disetujui')
            ->exists();

        if (!$hasApprovedClaim) {
            abort(403, 'Anda tidak memiliki izin mengelola layanan usaha ini.');
        }

        return Umkm::findOrFail($umkmId);
    }

    public function index($id)
    {
        $umkm = $this->authorizeUmkm($id);
        $layananList = LayananUsaha::where('umkm_id', $id)->latest()->get();

        return view('dashboard.pelaku.layanan.index', compact('umkm', 'layananList'));
    }

    public function create($id)
    {
        $umkm = $this->authorizeUmkm($id);

        return view('dashboard.pelaku.layanan.create', compact('umkm'));
    }

    public function store(Request $request, $id)
    {
        $umkm = $this->authorizeUmkm($id);

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_mulai' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        LayananUsaha::create([
            'umkm_id' => $umkm->id,
            'nama_layanan' => $validated['nama_layanan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_mulai' => $validated['harga_mulai'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('dashboard.pelaku.layanan.index', $umkm->id)->with('success', 'Layanan/produk baru berhasil ditambahkan.');
    }

    public function edit($id, $layananId)
    {
        $umkm = $this->authorizeUmkm($id);
        $layanan = LayananUsaha::where('umkm_id', $id)->findOrFail($layananId);

        return view('dashboard.pelaku.layanan.edit', compact('umkm', 'layanan'));
    }

    public function update(Request $request, $id, $layananId)
    {
        $umkm = $this->authorizeUmkm($id);
        $layanan = LayananUsaha::where('umkm_id', $id)->findOrFail($layananId);

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_mulai' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $layanan->update($validated);

        return redirect()->route('dashboard.pelaku.layanan.index', $umkm->id)->with('success', 'Layanan/produk berhasil diperbarui.');
    }

    public function destroy($id, $layananId)
    {
        $umkm = $this->authorizeUmkm($id);
        $layanan = LayananUsaha::where('umkm_id', $id)->findOrFail($layananId);
        $layanan->delete();

        return redirect()->route('dashboard.pelaku.layanan.index', $umkm->id)->with('success', 'Layanan/produk berhasil dihapus.');
    }
}
