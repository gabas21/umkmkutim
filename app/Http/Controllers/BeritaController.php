<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::published();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                  ->orWhere('konten', 'like', $keyword);
            });
        }

        $beritaList = $query->paginate(9)->withQueryString();

        return view('berita.index', compact('beritaList'));
    }

    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)->published()->firstOrFail();
        $recentBerita = Berita::published()->where('id', '!=', $berita->id)->take(4)->get();

        return view('berita.show', compact('berita', 'recentBerita'));
    }
}
