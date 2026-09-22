<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('umkm');

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('komentar', 'like', $keyword)
                  ->orWhere('nama_reviewer', 'like', $keyword);
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviewList = $query->latest()->paginate(20)->withQueryString();

        return view('admin.review.index', compact('reviewList'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $umkm = $review->umkm;
        $review->delete();

        if ($umkm) {
            $totalReview = $umkm->reviews()->count();
            $avgRating = $umkm->reviews()->avg('rating') ?: 0.00;

            $umkm->update([
                'jumlah_review' => $totalReview,
                'rating' => round($avgRating, 2),
            ]);
        }

        return back()->with('success', 'Ulasan berhasil dihapus dan rating usaha telah diperbarui.');
    }
}
