<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class UmkmMappingController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $kecamatanId = $request->query('kecamatan_id');
        $perPage = (int) $request->query('per_page', 25);

        $query = Umkm::with('kecamatan')
            ->whereNotNull('kecamatan_id')
            ->whereNull('kelurahan_id');

        if ($kecamatanId) {
            $query->where('kecamatan_id', $kecamatanId);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_usaha', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%")
                    ->orWhere('kelurahan_desa', 'like', "%{$q}%");
            });
        }

        $umkms = $query->orderBy('id')->paginate($perPage)->withQueryString();

        $kecamatans = Kecamatan::orderBy('name')->get();

        return view('admin.umkm_mapping_index', compact('umkms', 'kecamatans', 'kecamatanId', 'q'));
    }

    public function assign(Request $request, $id)
    {
        // allow clearing kelurahan by sending empty/null kelurahan_id
        $request->validate([
            'kelurahan_id' => 'nullable|integer|exists:kelurahans,id',
        ]);

        $umkm = Umkm::findOrFail($id);

        $inputKelId = $request->input('kelurahan_id');

        // If kelurahan_id is null or empty, clear the association
        if (empty($inputKelId)) {
            $umkm->kelurahan_id = null;
            $umkm->save();

            return redirect()->back()->with('success', "UMKM (ID: {$umkm->id}) kelurahan dibersihkan (NULL)");
        }

        $kelurahan = Kelurahan::findOrFail($inputKelId);

        // ensure kelurahan belongs to same kecamatan as the umkm (if umkm has kecamatan_id)
        if ($umkm->kecamatan_id && $kelurahan->kecamatan_id !== $umkm->kecamatan_id) {
            return redirect()->back()->withErrors(['kelurahan_id' => 'Kelurahan tidak berada di kecamatan yang sama.']);
        }

        $umkm->kelurahan_id = $kelurahan->id;
        $umkm->save();

        return redirect()->back()->with('success', "UMKM (ID: {$umkm->id}) berhasil dipetakan ke kelurahan: {$kelurahan->name}");
    }

    /**
     * Create a new Kelurahan under given kecamatan and assign to UMKM (AJAX)
     */
    public function storeKelurahanAndAssign(Request $request, $id)
    {
        $data = $request->validate([
            'kelurahan_name' => 'required|string|max:191',
            'kecamatan_id' => 'required|integer|exists:kecamatans,id',
        ]);

        $umkm = Umkm::findOrFail($id);

        // Create kelurahan (if exists with same name+kecamatan, reuse)
        $kel = Kelurahan::firstOrCreate(
            ['kecamatan_id' => $data['kecamatan_id'], 'name' => $data['kelurahan_name']],
            ['geojson' => null]
        );

        // Ensure kelurahan belongs to same kecamatan as umkm (or set umkm kecamatan if null)
        if (!$umkm->kecamatan_id) {
            $umkm->kecamatan_id = $kel->kecamatan_id;
        } elseif ($umkm->kecamatan_id !== $kel->kecamatan_id) {
            return response()->json(['error' => 'Kelurahan tidak pada kecamatan yang sama dengan UMKM'], 422);
        }

        $umkm->kelurahan_id = $kel->id;
        $umkm->save();

        return response()->json([
            'success' => true,
            'kelurahan' => ['id' => $kel->id, 'name' => $kel->name],
            'umkm_id' => $umkm->id,
        ]);
    }
}
