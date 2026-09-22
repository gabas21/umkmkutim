<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Return list of kecamatan (id, name, code)
     * Optional query: q (search term)
     */
    public function kecamatans(Request $request)
    {
        $q = $request->query('q');
        $perPage = (int) $request->query('per_page', 20);
        $perPage = $perPage > 0 ? min(100, $perPage) : 20;

        $query = Kecamatan::select('id', 'name', 'code')->orderBy('name');

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $list = $query->paginate($perPage)->withQueryString();

        return response()->json($list);
    }

    /**
     * Return list of kelurahan (id, name, code, kecamatan_id)
     * Optional query: kecamatan_id, q (search term)
     */
    public function kelurahans(Request $request)
    {
        $q = $request->query('q');
        $kecId = $request->query('kecamatan_id');
        $perPage = (int) $request->query('per_page', 20);
        $perPage = $perPage > 0 ? min(100, $perPage) : 20;

        $query = Kelurahan::select('id', 'name', 'code', 'kecamatan_id')->orderBy('name');

        if ($kecId) {
            $query->where('kecamatan_id', $kecId);
        }

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $list = $query->paginate($perPage)->withQueryString();

        return response()->json($list);
    }
}
