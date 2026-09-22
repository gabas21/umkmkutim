<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPelatihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelatihan::withCount('peserta');

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where('judul', 'like', $keyword);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pelatihans = $query->latest('tanggal_mulai')->paginate(10)->withQueryString();

        return view('admin.pelatihan.index', compact('pelatihans'));
    }

    public function create()
    {
        return view('admin.pelatihan.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $baseSlug = Str::slug($validated['judul']);
        $slug = $baseSlug;
        $counter = 1;
        while (Pelatihan::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('banner')) {
            $validated['banner_url'] = $request->file('banner')->store('pelatihan/banner', 'public');
        }
        unset($validated['banner']);

        Pelatihan::create($validated);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan baru berhasil dibuat.');
    }

    public function edit($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        return view('admin.pelatihan.edit', compact('pelatihan'));
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $validated = $this->validated($request);

        if ($request->hasFile('banner')) {
            if ($pelatihan->banner_url && Storage::disk('public')->exists($pelatihan->banner_url)) {
                Storage::disk('public')->delete($pelatihan->banner_url);
            }
            $validated['banner_url'] = $request->file('banner')->store('pelatihan/banner', 'public');
        }
        unset($validated['banner']);

        $pelatihan->update($validated);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        if ($pelatihan->banner_url && Storage::disk('public')->exists($pelatihan->banner_url)) {
            Storage::disk('public')->delete($pelatihan->banner_url);
        }

        $pelatihan->delete();

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil dihapus beserta data pesertanya.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'materi_ringkas' => 'nullable|string',
            'penyelenggara' => 'nullable|string|max:255',
            'instruktur' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'mode' => 'required|in:offline,online,hybrid',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'biaya' => 'nullable|numeric|min:0',
            'link_zoom' => 'nullable|url|max:255',
            'status' => 'required|in:upcoming,ongoing,selesai,closed',
            'syarat_peserta' => 'nullable|string',
            'banner' => 'nullable|image|max:3072',
        ]);
    }
}
