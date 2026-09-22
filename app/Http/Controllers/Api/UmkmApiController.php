<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UmkmApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['kategori', 'kecamatan', 'kelurahan'])->orderByDesc('id');

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('nama_usaha', 'like', $term)
                    ->orWhere('alamat', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('telepon', 'like', $term);
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('kelurahan_id')) {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function publicIndex(Request $request)
    {
        $query = Umkm::with(['kategori', 'kecamatan', 'kelurahan'])
            ->where('status', 'active')
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $perPage = min((int) $request->input('per_page', 20), 50);

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function adminIndex(Request $request)
    {
        $query = Umkm::with(['kategori', 'kecamatan', 'kelurahan'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $term = '%' . $request->search . '%';
                $q->where('nama_usaha', 'like', $term)
                    ->orWhere('alamat', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_usaha' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'alamat' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:30',
            'website' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
            'status_klaim' => 'nullable|in:belum_diklaim,menunggu_verifikasi,terverifikasi',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $slug = Str::slug($validated['nama_usaha']);
        if (Umkm::where('slug', $slug)->exists()) {
            $slug .= '-' . now()->format('YmdHis');
        }

        $umkm = Umkm::create([
            'nama_usaha' => $validated['nama_usaha'],
            'slug' => $slug,
            'kategori_id' => $validated['kategori_id'],
            'kecamatan_id' => $validated['kecamatan_id'] ?? null,
            'kelurahan_id' => $validated['kelurahan_id'] ?? null,
            'alamat' => $validated['alamat'],
            'email' => $validated['email'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'website' => $validated['website'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'status_klaim' => $validated['status_klaim'] ?? 'belum_diklaim',
            'location' => $validated['latitude'] !== null && $validated['longitude'] !== null
                ? DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)")
                : null,
        ]);

        return response()->json([
            'message' => 'UMKM berhasil dibuat.',
            'data' => $umkm->load(['kategori', 'kecamatan', 'kelurahan']),
        ], 201);
    }

    public function show(Umkm $umkm)
    {
        return response()->json($umkm->load(['kategori', 'kecamatan', 'kelurahan']));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'nama_usaha' => 'sometimes|required|string|max:255',
            'kategori_id' => 'sometimes|required|exists:kategori,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'alamat' => 'sometimes|required|string',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:30',
            'website' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
            'status_klaim' => 'nullable|in:belum_diklaim,menunggu_verifikasi,terverifikasi',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if (isset($validated['nama_usaha'])) {
            $validated['slug'] = Str::slug($validated['nama_usaha']);
        }

        if (isset($validated['latitude'], $validated['longitude'])) {
            $validated['location'] = DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)");
        }

        $umkm->fill($validated);
        $umkm->save();

        return response()->json([
            'message' => 'UMKM berhasil diperbarui.',
            'data' => $umkm->fresh()->load(['kategori', 'kecamatan', 'kelurahan']),
        ]);
    }

    public function destroy(Umkm $umkm)
    {
        $umkm->delete();

        return response()->json([
            'message' => 'UMKM berhasil dihapus.',
        ]);
    }

    public function verify(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        $umkm->update([
            'status_klaim' => $validated['status'] === 'verified' ? 'terverifikasi' : ($validated['status'] === 'rejected' ? 'belum_diklaim' : 'menunggu_verifikasi'),
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

        return response()->json([
            'message' => 'Status verifikasi UMKM berhasil diperbarui.',
            'data' => $umkm->fresh(),
        ]);
    }
}
