@php
    $isEdit = isset($berita);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Judul Berita <span class="text-rose-500">*</span></label>
        <input type="text" name="judul" required value="{{ old('judul', $isEdit ? $berita->judul : '') }}"
               placeholder="Contoh: Pelatihan Digital Marketing untuk UMKM Kutim 2026"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kategori Konten <span class="text-rose-500">*</span></label>
        <select name="kategori" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach(['berita' => 'Berita', 'pengumuman' => 'Pengumuman', 'tips' => 'Tips & Edukasi'] as $val => $label)
                <option value="{{ $val }}" {{ old('kategori', $isEdit ? $berita->kategori : 'berita') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            <option value="draft" {{ old('status', $isEdit ? $berita->status : 'draft') == 'draft' ? 'selected' : '' }}>Draft (Belum Tayang)</option>
            <option value="published" {{ old('status', $isEdit ? $berita->status : 'draft') == 'published' ? 'selected' : '' }}>Publikasikan Sekarang</option>
        </select>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Gambar Thumbnail</label>
        @if($isEdit && $berita->thumbnail)
            <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="Thumbnail saat ini" class="w-40 h-24 object-cover rounded-xl border border-slate-200 mb-2">
        @endif
        <input type="file" name="thumbnail" accept="image/png,image/jpeg,image/jpg,image/webp"
               class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
        <span class="text-[10px] text-slate-400 block">Format JPG/PNG/WEBP, maksimal 2MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti gambar.' : '' }}</span>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Isi Konten Berita <span class="text-rose-500">*</span></label>
        <textarea name="konten" rows="10" required
                  class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('konten', $isEdit ? $berita->konten : '') }}</textarea>
        <span class="text-[10px] text-slate-400 block">Boleh menggunakan paragraf biasa. Tampilan publik akan merender baris baru secara otomatis.</span>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('admin.berita.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Berita' }}
    </button>
</div>
