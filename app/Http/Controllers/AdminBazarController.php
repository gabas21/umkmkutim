<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBazarController extends Controller
{
    public function index(Request $request)
    {
        $query = Bazar::withCount('peserta');

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where('nama_bazar', 'like', $keyword);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bazars = $query->latest('tanggal_mulai')->paginate(10)->withQueryString();

        return view('admin.bazar.index', compact('bazars'));
    }

    public function create()
    {
        $daftarKecamatan = $this->daftarKecamatan();
        return view('admin.bazar.create', compact('daftarKecamatan'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $baseSlug = Str::slug($validated['nama_bazar']);
        $slug = $baseSlug;
        $counter = 1;
        while (Bazar::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('banner')) {
            $validated['banner_url'] = $request->file('banner')->store('bazar/banner', 'public');
        }
        unset($validated['banner']);

        Bazar::create($validated);

        return redirect()->route('admin.bazar.index')->with('success', 'Event bazar baru berhasil dibuat.');
    }

    public function edit($id)
    {
        $bazar = Bazar::findOrFail($id);
        $daftarKecamatan = $this->daftarKecamatan();
        return view('admin.bazar.edit', compact('bazar', 'daftarKecamatan'));
    }

    public function update(Request $request, $id)
    {
        $bazar = Bazar::findOrFail($id);
        $validated = $this->validated($request);

        if ($request->hasFile('banner')) {
            if ($bazar->banner_url && Storage::disk('public')->exists($bazar->banner_url)) {
                Storage::disk('public')->delete($bazar->banner_url);
            }
            $validated['banner_url'] = $request->file('banner')->store('bazar/banner', 'public');
        }
        unset($validated['banner']);

        $bazar->update($validated);

        return redirect()->route('admin.bazar.index')->with('success', 'Event bazar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bazar = Bazar::findOrFail($id);

        if ($bazar->banner_url && Storage::disk('public')->exists($bazar->banner_url)) {
            Storage::disk('public')->delete($bazar->banner_url);
        }

        $bazar->delete();

        return redirect()->route('admin.bazar.index')->with('success', 'Event bazar berhasil dihapus beserta data pesertanya.');
    }

    private function daftarKecamatan(): array
    {
        return [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_bazar' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:100',
            'alamat_lengkap' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota_peserta' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,closed,selesai',
            'penyelenggara' => 'nullable|string|max:255',
            'kontak_person' => 'nullable|string|max:100',
            'fasilitas' => 'nullable|string',
            'banner' => 'nullable|image|max:3072',
        ]);
    }
}
