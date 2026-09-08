<?php

namespace App\Http\Controllers;

use App\Models\SurveyKepuasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function create()
    {
        return view('survey.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_responden' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:100',
            'modul' => 'required|in:umkm,bazar,pelatihan,layanan_umum',
            'nilai_kemudahan' => 'required|integer|min:1|max:5',
            'nilai_kecepatan' => 'required|integer|min:1|max:5',
            'nilai_keramahan' => 'required|integer|min:1|max:5',
            'nilai_kemanfaatan' => 'required|integer|min:1|max:5',
            'saran_teks' => 'nullable|string|max:1000',
        ]);

        $pelakuUsahaId = null;
        if (Auth::guard('pelaku_usaha')->check()) {
            $pelakuUsahaId = Auth::guard('pelaku_usaha')->id();
        }

        SurveyKepuasan::create([
            'pelaku_usaha_id' => $pelakuUsahaId,
            'nama_responden' => $request->nama_responden ?: 'Anonim (Warga Kutim)',
            'pekerjaan' => $request->pekerjaan ?: 'Masyarakat Umum',
            'modul' => $request->modul,
            'nilai_kemudahan' => $request->nilai_kemudahan,
            'nilai_kecepatan' => $request->nilai_kecepatan,
            'nilai_keramahan' => $request->nilai_keramahan,
            'nilai_kemanfaatan' => $request->nilai_kemanfaatan,
            'saran_teks' => $request->saran_teks,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas partisipasi Anda! Penilaian dan masukan Anda telah kami terima untuk peningkatan mutu layanan Diskop & UMKM Kutai Timur.');
    }
}
