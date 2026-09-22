<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query()->where('status', 'published');

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('content', 'like', $term);
            });
        }

        $news = $query->latest()->paginate(9)->withQueryString();

        return view('news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $recentNews = News::where('status', 'published')->where('id', '!=', $news->id)->latest()->take(4)->get();

        return view('news.show', compact('news', 'recentNews'));
    }
}
