<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\KlaimUsaha;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUmkm = Umkm::count();
        $klaimMenunggu = KlaimUsaha::where('status', 'menunggu')->count();
        $umkmTerverifikasi = Umkm::where('status_klaim', 'terverifikasi')->count();
        $totalKategori = Kategori::count();

        // Daftar klaim menunggu review admin
        $pendingKlaimList = KlaimUsaha::where('status', 'menunggu')
            ->with(['umkm', 'pelakuUsaha'])
            ->latest()
            ->paginate(10);

        // Rekap ringkas per kecamatan
        $rekapKecamatan = Umkm::selectRaw('kecamatan, COUNT(*) as total_umkm, SUM(CASE WHEN status_klaim = "terverifikasi" THEN 1 ELSE 0 END) as total_terverifikasi')
            ->groupBy('kecamatan')
            ->orderByDesc('total_umkm')
            ->get();

        return view('admin.dashboard', compact(
            'totalUmkm',
            'klaimMenunggu',
            'umkmTerverifikasi',
            'totalKategori',
            'pendingKlaimList',
            'rekapKecamatan'
        ));
    }

    public function showKlaim($id)
    {
        $klaim = KlaimUsaha::with(['umkm', 'pelakuUsaha'])->findOrFail($id);
        return view('admin.klaim-detail', compact('klaim'));
    }

    public function approveKlaim(Request $request, $id)
    {
        $klaim = KlaimUsaha::findOrFail($id);
        $admin = Auth::user();

        $klaim->update([
            'status' => 'disetujui',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
            'catatan_admin' => $request->catatan_admin ?? 'Dokumen kepemilikan usaha telah diverifikasi dan valid.',
        ]);

        // Tandai UMKM menjadi terverifikasi
        $klaim->umkm()->update([
            'status_klaim' => 'terverifikasi'
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Klaim untuk '{$klaim->umkm->nama_usaha}' berhasil DISETUJUI. Hak akses pengelolaan telah diberikan kepada pemilik.");
    }

    public function rejectKlaim(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ], [
            'catatan_admin.required' => 'Alasan penolakan klaim wajib diisi agar pemohon mengetahui revisi dokumen yang dibutuhkan.'
        ]);

        $klaim = KlaimUsaha::findOrFail($id);
        $admin = Auth::user();

        $klaim->update([
            'status' => 'ditolak',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Kembalikan status UMKM ke belum_diklaim
        $klaim->umkm()->update([
            'status_klaim' => 'belum_diklaim'
        ]);

        return redirect()->route('admin.dashboard')->with('warning', "Klaim untuk '{$klaim->umkm->nama_usaha}' telah DITOLAK dengan catatan revisi.");
    }

    public function showImportForm()
    {
        $totalUmkm = Umkm::count();
        $importUmkmCount = Umkm::where('sumber_data', 'import')->count();

        return view('admin.import', compact('totalUmkm', 'importUmkmCount'));
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'file_csv.required' => 'File CSV wajib diunggah.',
            'file_csv.mimes' => 'Format file harus berupa CSV (.csv atau .txt).',
            'file_csv.max' => 'Ukuran file CSV maksimal 10 MB.',
        ]);

        $path = $request->file('file_csv')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membuka file CSV.');
        }

        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau header tidak valid.');
        }

        $header = array_map(function ($col) {
            return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strtolower($col)));
        }, $header);

        $batch = [];
        $batchSize = 500;
        $totalProcessed = 0;

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) !== count($header)) {
                continue;
            }
            $row = array_combine($header, $data);
            $batch[] = $row;
            $totalProcessed++;

            if (count($batch) >= $batchSize) {
                \App\Jobs\ImportDinasUmkmJob::dispatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            \App\Jobs\ImportDinasUmkmJob::dispatch($batch);
        }

        fclose($handle);

        return redirect()->route('admin.import')->with('success', "Berhasil memproses {$totalProcessed} baris data CSV ke dalam antrean antarmuka sistem!");
    }
}
