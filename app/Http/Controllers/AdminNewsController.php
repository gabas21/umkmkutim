<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('content', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $news = $query->latest()->paginate(10)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $validated['slug'] = $this->buildUniqueSlug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated['slug'] = $this->buildUniqueSlug($validated['title'], $news->id);
        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function toggleStatus(News $news)
    {
        if ($news->status === 'published') {
            $news->update(['status' => 'draft']);
            $message = 'Status berita diubah menjadi draft.';
        } else {
            $news->update(['status' => 'published']);
            $message = 'Berita berhasil dipublikasikan.';
        }

        return back()->with('success', $message);
    }

    protected function buildUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $counter = 1;

        while (News::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
