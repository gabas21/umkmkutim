<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('urutan', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'link_url' => 'nullable|url|max:500',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $path = $request->file('gambar')->store('hero_slides', 'public');

        HeroSlide::create([
            'judul' => $request->judul,
            'gambar' => $path,
            'link_url' => $request->link_url,
            'urutan' => $request->urutan ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner slider hero baru berhasil diupload dan diaktifkan!');
    }

    public function toggle($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        $status = $slide->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status slider '{$slide->judul}' berhasil {$status}.");
    }

    public function destroy($id)
    {
        $slide = HeroSlide::findOrFail($id);

        if ($slide->gambar && !str_starts_with($slide->gambar, 'http') && Storage::disk('public')->exists($slide->gambar)) {
            Storage::disk('public')->delete($slide->gambar);
        }

        $slide->delete();

        return back()->with('success', 'Banner slider berhasil dihapus.');
    }
}
