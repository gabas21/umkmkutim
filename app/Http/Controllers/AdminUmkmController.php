<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Http\Request;

class AdminUmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['kategori', 'klaimAktif.pelakuUsaha']);

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_usaha', 'like', $keyword)
                  ->orWhere('alamat', 'like', $keyword);
            });
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        $umkmList = $query->latest()->paginate(15)->withQueryString();

        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        return view('admin.umkm.index', compact('umkmList', 'daftarKecamatan'));
    }

    public function show($id)
    {
        $umkm = Umkm::with([
            'kategori',
            'kecamatan',
            'kelurahan',
            'documents',
            'photos',
            'verifications.verifiedBy',
            'klaimAktif.pelakuUsaha',
        ])->findOrFail($id);

        return view('admin.umkm.show', compact('umkm'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $umkm = Umkm::findOrFail($id);
        $umkm->update(['status' => $request->status]);

        ActivityLog::record(
            'umkm',
            'status_update',
            "Mengubah status publikasi UMKM '{$umkm->nama_usaha}' menjadi {$request->status}.",
            auth()->id()
        );

        return back()->with('success', "Status UMKM '{$umkm->nama_usaha}' berhasil diubah menjadi {$request->status}.");
    }

    public function verify(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected,pending',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $umkm = Umkm::findOrFail($id);
        $statusKlaim = match ($validated['status']) {
            'verified' => 'terverifikasi',
            'rejected' => 'belum_diklaim',
            default => 'menunggu_verifikasi',
        };

        $umkm->update([
            'status_klaim' => $statusKlaim,
        ]);

        $umkm->verifications()->create([
            'verified_by' => auth()->id(),
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
            'verified_at' => now(),
        ]);

        ActivityLog::record(
            'umkm',
            'verify',
            "Memutuskan status klaim UMKM '{$umkm->nama_usaha}' menjadi {$validated['status']} dengan catatan: " . ($validated['catatan'] ?? 'tidak ada catatan'),
            auth()->id()
        );

        $message = $validated['status'] === 'verified'
            ? "Data UMKM '{$umkm->nama_usaha}' berhasil diverifikasi."
            : ($validated['status'] === 'rejected'
                ? "Verifikasi UMKM '{$umkm->nama_usaha}' ditolak dan status klaim dikembalikan ke belum diklaim."
                : "Status verifikasi UMKM '{$umkm->nama_usaha}' dipindahkan ke menunggu verifikasi.");

        return redirect()->route('admin.umkm.show', $umkm->id)->with('success', $message);
    }
}
