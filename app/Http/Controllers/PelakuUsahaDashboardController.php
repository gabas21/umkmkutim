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
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        return view('dashboard.pelaku.edit-umkm', compact('umkm', 'kategoriList', 'daftarKecamatan'));
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
            'kecamatan' => 'required|string|max:100',
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

        $umkm->fill($validated);
        $umkm->location = DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)");
        $umkm->save();

        return redirect()->route('dashboard.pelaku')->with('success', 'Data profil usaha berhasil diperbarui!');
    }
}
