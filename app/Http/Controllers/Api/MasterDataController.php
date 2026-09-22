<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function kecamatan(Request $request)
    {
        $query = Kecamatan::query()->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function kelurahan(Request $request)
    {
        $query = Kelurahan::with('kecamatan')->orderBy('name');

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function kategoriUsaha(Request $request)
    {
        $query = Kategori::query()->orderBy('nama');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->paginate($perPage)->withQueryString());
    }
}
