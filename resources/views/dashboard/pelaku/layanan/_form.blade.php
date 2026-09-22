@php
    $isEdit = isset($layanan);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Nama Layanan / Produk <span class="text-rose-500">*</span></label>
        <input type="text" name="nama_layanan" required value="{{ old('nama_layanan', $isEdit ? $layanan->nama_layanan : '') }}"
               placeholder="Contoh: Nasi Kuning Spesial, Jasa Servis AC"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Harga Mulai (Rp)</label>
        <input type="number" name="harga_mulai" min="0" step="0.01" value="{{ old('harga_mulai', $isEdit ? $layanan->harga_mulai : '') }}"
               placeholder="Kosongkan jika tidak ditentukan"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Status <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            <option value="active" {{ old('status', $isEdit ? $layanan->status : 'active') == 'active' ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
            <option value="inactive" {{ old('status', $isEdit ? $layanan->status : 'active') == 'inactive' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
        </select>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('deskripsi', $isEdit ? $layanan->deskripsi : '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('dashboard.pelaku.layanan.index', $umkm->id) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Layanan' }}
    </button>
</div>
