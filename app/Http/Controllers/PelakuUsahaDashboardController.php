<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\KlaimUsaha;
use App\Models\LaporanKunjungan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelakuUsahaDashboardController extends Controller
{
    public function index()
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        // Riwayat pengajuan klaim
        $klaimList = KlaimUsaha::where('pelaku_usaha_id', $pelaku->id)
            ->with(['umkm' => function ($q) {
                $q->withCoordinates()->with('kategori');
            }])
            ->latest()
            ->get();

        // UMKM yang sudah disetujui dan berhak dikelola
        $approvedUmkmIds = $klaimList->where('status', 'disetujui')->pluck('umkm_id');
        $myUmkmList = Umkm::whereIn('id', $approvedUmkmIds)->with('kategori')->get();

        // Agregat statistik kunjungan untuk UMKM milik user
        $totalViews = 0;
        $kunjunganHarian = [];
        if ($approvedUmkmIds->isNotEmpty()) {
            $totalViews = LaporanKunjungan::whereIn('umkm_id', $approvedUmkmIds)->sum('jumlah_dilihat');
            $kunjunganHarian = LaporanKunjungan::whereIn('umkm_id', $approvedUmkmIds)
                ->where('tanggal', '>=', now()->subDays(14)->toDateString())
                ->selectRaw('tanggal, SUM(jumlah_dilihat) as total_view')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
        }

        return view('dashboard.pelaku.index', compact('pelaku', 'klaimList', 'myUmkmList', 'totalViews', 'kunjunganHarian'));
    }

    public function editUmkm($id)
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        // Verifikasi kepemilikan
        $hasApprovedClaim = KlaimUsaha::where('pelaku_usaha_id', $pelaku->id)
            ->where('umkm_id', $id)
            ->where('status', 'disetujui')
            ->exists();

        if (!$hasApprovedClaim) {
            return redirect()->route('dashboard.pelaku')->with('error', 'Anda belum memiliki izin mengelola data UMKM ini.');
        }

        $umkm = Umkm::withCoordinates()->findOrFail($id);
        $kategoriList = Kategori::all();
        // Use master kecamatan table for dropdown
        $daftarKecamatan = \App\Models\Kecamatan::select('id', 'name')->orderBy('name')->get();

        // Prepare initial kelurahan list if umkm has kecamatan_id
        $initialKelurahan = [];
        if (!empty($umkm->kecamatan_id)) {
            $initialKelurahan = \App\Models\Kelurahan::where('kecamatan_id', $umkm->kecamatan_id)->select('id', 'name')->orderBy('name')->get();
        }

        return view('dashboard.pelaku.edit-umkm', compact('umkm', 'kategoriList', 'daftarKecamatan', 'initialKelurahan'));
    }

    public function updateUmkm(Request $request, $id)
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $hasApprovedClaim = KlaimUsaha::where('pelaku_usaha_id', $pelaku->id)
            ->where('umkm_id', $id)
            ->where('status', 'disetujui')
            ->exists();

        if (!$hasApprovedClaim) {
            abort(403);
        }

        $umkm = Umkm::findOrFail($id);

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'alamat' => 'required|string',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'kelurahan_desa' => 'nullable|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'instagram' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'foto_utama' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_utama')) {
            $validated['foto_utama'] = $request->file('foto_utama')->store('umkm/foto', 'public');
        }

        $lat = (float)$validated['latitude'];
        $lng = (float)$validated['longitude'];
        unset($validated['latitude'], $validated['longitude']);

        // Map master ids to string fields for backward compatibility
        if (isset($validated['kecamatan_id']) && $validated['kecamatan_id']) {
            $kec = \App\Models\Kecamatan::find($validated['kecamatan_id']);
            $validated['kecamatan'] = $kec ? $kec->name : $umkm->kecamatan;
        }

        if (isset($validated['kelurahan_id']) && $validated['kelurahan_id']) {
            $kel = \App\Models\Kelurahan::find($validated['kelurahan_id']);
            $validated['kelurahan_desa'] = $kel ? $kel->name : ($validated['kelurahan_desa'] ?? $umkm->kelurahan_desa);
        }

        $umkm->fill($validated);
        // set relation ids explicitly
        if (array_key_exists('kecamatan_id', $validated)) {
            $umkm->kecamatan_id = $validated['kecamatan_id'];
        }
        if (array_key_exists('kelurahan_id', $validated)) {
            $umkm->kelurahan_id = $validated['kelurahan_id'];
        }

        $umkm->location = DB::raw("ST_GeomFromText('POINT({$lng} {$lat})', 4326)");
        $umkm->save();

        return redirect()->route('dashboard.pelaku')->with('success', 'Data profil usaha berhasil diperbarui!');
    }
}
