<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminKategoriController extends Controller
{
    public function index()
    {
        $kategoriList = Kategori::withCount('umkm')->orderBy('nama')->get();
        return view('admin.kategori.index', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama',
            'icon' => 'nullable|string|max:50',
        ]);

        Kategori::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'icon' => $validated['icon'] ?: 'store',
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama,' . $id,
            'icon' => 'nullable|string|max:50',
        ]);

        $kategori->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'icon' => $validated['icon'] ?: 'store',
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::withCount('umkm')->findOrFail($id);

        if ($kategori->umkm_count > 0) {
            return back()->with('error', "Kategori tidak dapat dihapus karena masih memiliki {$kategori->umkm_count} UMKM terhubung.");
        }

        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
