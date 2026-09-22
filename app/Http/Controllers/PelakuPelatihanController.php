<?php

namespace App\Http\Controllers;

use App\Models\PelatihanPeserta;
use Illuminate\Support\Facades\Auth;

class PelakuPelatihanController extends Controller
{
    public function index()
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $pendaftaranList = PelatihanPeserta::where('pelaku_usaha_id', $pelaku->id)
            ->with('pelatihan')
            ->latest()
            ->get();

        return view('dashboard.pelaku.pelatihan-saya', compact('pendaftaranList'));
    }
}
