@extends('layouts.app')

@section('title', 'Edit Profil Usaha — ' . $umkm->nama_usaha)

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard.pelaku') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Kelola Profil Usaha</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Perbarui informasi toko, jam operasional, alamat, dan titik koordinat peta</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl space-y-8">
        <form action="{{ route('dashboard.pelaku.update', $umkm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nama Usaha / Toko <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $umkm->nama_usaha) }}" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kategori Komoditas <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $umkm->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Deskripsi Lengkap Usaha & Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kecamatan <span class="text-rose-500">*</span></label>
                    <select name="kecamatan" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($daftarKecamatan as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $umkm->kecamatan) == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Desa / Kelurahan</label>
                    <input type="text" name="kelurahan_desa" value="{{ old('kelurahan_desa', $umkm->kelurahan_desa) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="alamat" value="{{ old('alamat', $umkm->alamat) }}" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Koordinat Spasial -->
            <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200 space-y-3">
                <span class="block text-xs font-bold text-emerald-900"><i class="fa-solid fa-location-crosshairs"></i> Koordinat Titik Lokasi Peta</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block font-medium text-slate-600 mb-1">Latitude</label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude', $umkm->latitude) }}" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-600 mb-1">Longitude</label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude', $umkm->longitude) }}" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Kontak & Medsos -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nomor Telepon / WA</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $umkm->telepon) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Email Usaha</label>
                    <input type="email" name="email" value="{{ old('email', $umkm->email) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Instagram (tanpa @)</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $umkm->instagram) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('dashboard.pelaku') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
