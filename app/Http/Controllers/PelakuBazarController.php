<?php

namespace App\Http\Controllers;

use App\Models\BazarPeserta;
use Illuminate\Support\Facades\Auth;

class PelakuBazarController extends Controller
{
    public function index()
    {
        $pelaku = Auth::guard('pelaku_usaha')->user();

        $pendaftaranList = BazarPeserta::where('pelaku_usaha_id', $pelaku->id)
            ->with('bazar')
            ->latest()
            ->get();

        return view('dashboard.pelaku.bazar-saya', compact('pendaftaranList'));
    }
}
