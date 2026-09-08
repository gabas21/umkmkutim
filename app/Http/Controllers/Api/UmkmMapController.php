<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmkmMapController extends Controller
{
    /**
     * Mengambil data UMKM berdasarkan batas pandang (viewport) peta Leaflet.
     * Menggunakan spatial index MBRContains untuk performa cepat pada puluhan ribu data.
     */
    public function viewport(Request $request)
    {
        return app(UmkmClusterController::class)->index($request);
    }
}

