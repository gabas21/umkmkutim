<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\LaporanKunjungan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        $kategoriList = Kategori::all();

        $query = Umkm::active()->withCoordinates()->with('kategori');

        // Pencarian Nama / Deskripsi
        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_usaha', 'like', $keyword)
                  ->orWhere('deskripsi', 'like', $keyword)
                  ->orWhere('alamat', 'like', $keyword);
            });
        }

        // Filter Kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter Status Klaim
        if ($request->filled('status_klaim')) {
            $query->where('status_klaim', $request->status_klaim);
        }

        // Urutan
        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'rating') {
            $query->orderByDesc('rating');
        } elseif ($sort === 'populer') {
            $query->orderByDesc('jumlah_dilihat');
        } else {
            $query->latest();
        }

        $umkmList = $query->paginate(12)->withQueryString();

        return view('umkm.index', compact('umkmList', 'daftarKecamatan', 'kategoriList'));
    }

    public function show($slug)
    {
        $umkm = Umkm::where('slug', $slug)
            ->withCoordinates()
            ->with(['kategori', 'reviews', 'layanan'])
            ->firstOrFail();

        // Catat kunjungan harian secara atomik
        LaporanKunjungan::catatKunjungan($umkm->id);

        $currentUserClaim = null;
        if (Auth::guard('pelaku_usaha')->check()) {
            $currentUserClaim = $umkm->klaimUsaha()
                ->where('pelaku_usaha_id', Auth::guard('pelaku_usaha')->id())
                ->latest()
                ->first();
        }

        // UMKM Terkait dalam kategori yang sama
        $relatedUmkm = Umkm::active()
            ->where('kategori_id', $umkm->kategori_id)
            ->where('id', '!=', $umkm->id)
            ->withCoordinates()
            ->take(4)
            ->get();

        return view('umkm.show', compact('umkm', 'currentUserClaim', 'relatedUmkm'));
    }

    /**
     * API Endpoint Radius/Nearby Search
     */
    public function nearby(Request $request)
    {
        $lat = (float)$request->get('lat', 0.493);
        $lng = (float)$request->get('lng', 117.545);
        $radius = (int)$request->get('radius', 5000); // 5km default

        $results = Umkm::active()
            ->nearby($lat, $lng, $radius)
            ->with('kategori')
            ->limit(50)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_usaha' => $item->nama_usaha,
                    'slug' => $item->slug,
                    'kecamatan' => $item->kecamatan,
                    'kategori' => $item->kategori?->nama,
                    'lat' => (float)$item->latitude,
                    'lng' => (float)$item->longitude,
                    'jarak_meter' => round($item->jarak_meter),
                    'rating' => (float)$item->rating,
                    'status_klaim' => $item->status_klaim,
                    'url' => route('umkm.show', $item->slug),
                ];
            });

        return response()->json([
            'status' => 'success',
            'count' => $results->count(),
            'data' => $results
        ]);
    }

    /**
     * Tampilan form pendaftaran UMKM baru secara mandiri
     */
    public function createMandiri()
    {
        $kategoriList = Kategori::all();
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        return view('umkm.create-mandiri', compact('kategoriList', 'daftarKecamatan'));
    }

    /**
     * Simpan pendaftaran UMKM baru secara mandiri
     */
    public function storeMandiri(Request $request)
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

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
            'dokumen_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'dokumen_bukti_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'dokumen_ktp.required' => 'Identitas KTP wajib diunggah untuk verifikasi keabsahan pemilik.',
        ]);

        $baseSlug = \Illuminate\Support\Str::slug($validated['nama_usaha']);
        $slug = $baseSlug;
        $counter = 1;
        while (Umkm::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $fotoUtamaPath = null;
        if ($request->hasFile('foto_utama')) {
            $fotoUtamaPath = $request->file('foto_utama')->store('umkm/foto', 'public');
        }

        $lat = (float)$validated['latitude'];
        $lng = (float)$validated['longitude'];

        $umkm = Umkm::create([
            'nama_usaha' => $validated['nama_usaha'],
            'slug' => $slug,
            'kategori_id' => $validated['kategori_id'],
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'kecamatan' => $validated['kecamatan'],
            'kelurahan_desa' => $validated['kelurahan_desa'] ?? null,
            'location' => \Illuminate\Support\Facades\DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)"),
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'website' => $validated['website'] ?? null,
            'foto_utama' => $fotoUtamaPath,
            'sumber_data' => 'mandiri',
            'status_klaim' => 'menunggu_verifikasi',
            'status' => 'active',
        ]);

        // Simpan tiket klaim otomatis terhubung ke akun pelaku usaha
        $pathKtp = $request->file('dokumen_ktp')->store('dokumen/ktp', 'public');
        $pathBukti = null;
        if ($request->hasFile('dokumen_bukti_usaha')) {
            $pathBukti = $request->file('dokumen_bukti_usaha')->store('dokumen/bukti_usaha', 'public');
        }

        \App\Models\KlaimUsaha::create([
            'umkm_id' => $umkm->id,
            'pelaku_usaha_id' => $pelaku->id,
            'dokumen_ktp' => $pathKtp,
            'dokumen_bukti_usaha' => $pathBukti,
            'catatan_pemohon' => 'Pendaftaran UMKM Baru secara mandiri.',
            'status' => 'menunggu',
        ]);

        return redirect()->route('dashboard.pelaku')->with('success', "Pendaftaran UMKM '{$umkm->nama_usaha}' berhasil diajukan! Berkas Anda sedang menunggu verifikasi admin dinas.");
    }
}
