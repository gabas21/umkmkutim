<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where('judul', 'like', $keyword);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritaList = $query->latest()->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('beritaList'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'kategori' => 'required|in:berita,pengumuman,tips',
            'status' => 'required|in:draft,published',
        ]);

        $baseSlug = Str::slug($validated['judul']);
        $slug = $baseSlug;
        $counter = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('berita/thumbnail', 'public');
        }

        Berita::create([
            'judul' => $validated['judul'],
            'slug' => $slug,
            'konten' => $validated['konten'],
            'thumbnail' => $thumbnailPath,
            'kategori' => $validated['kategori'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'kategori' => 'required|in:berita,pengumuman,tips',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
                Storage::disk('public')->delete($berita->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('berita/thumbnail', 'public');
        }

        if ($validated['status'] === 'published' && $berita->status !== 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
            Storage::disk('public')->delete($berita->thumbnail);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function togglePublish($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->status === 'published') {
            $berita->update(['status' => 'draft', 'published_at' => null]);
            $message = "Berita '{$berita->judul}' dijadikan draft kembali.";
        } else {
            $berita->update(['status' => 'published', 'published_at' => now()]);
            $message = "Berita '{$berita->judul}' berhasil dipublikasikan.";
        }

        return back()->with('success', $message);
    }
}
