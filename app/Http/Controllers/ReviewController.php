<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Umkm;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $slug)
    {
        $umkm = Umkm::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'nama_reviewer' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ], [
            'nama_reviewer.required' => 'Nama pemberi ulasan wajib diisi.',
            'rating.required' => 'Pilih penilaian bintang (1-5).',
        ]);

        Review::create([
            'umkm_id' => $umkm->id,
            'nama_reviewer' => $validated['nama_reviewer'],
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
        ]);

        // Hitung ulang rata-rata rating dan jumlah review pada tabel UMKM
        $totalReview = $umkm->reviews()->count();
        $avgRating = $umkm->reviews()->avg('rating') ?: 0.00;

        $umkm->update([
            'jumlah_review' => $totalReview,
            'rating' => round($avgRating, 2),
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan penilaian Anda berhasil disimpan.');
    }
}
