@php
    $isEdit = isset($news);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Judul News <span class="text-rose-500">*</span></label>
        <input type="text" name="title" required value="{{ old('title', $isEdit ? $news->title : '') }}"
            placeholder="Contoh: UMKM Kutim jadi fokus strategi ekspor baru"
            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            <option value="draft" {{ old('status', $isEdit ? $news->status : 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $isEdit ? $news->status : 'draft') == 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ old('status', $isEdit ? $news->status : 'draft') == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Gambar Header</label>
        @if($isEdit && $news->image)
            <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar saat ini" class="w-40 h-24 object-cover rounded-xl border border-slate-200 mb-2">
        @endif
        <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp"
            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
        <span class="text-[10px] text-slate-400 block">Format JPG/PNG/WEBP, maksimal 2MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti gambar.' : '' }}</span>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Isi Konten <span class="text-rose-500">*</span></label>
        <textarea name="content" rows="10" required class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('content', $isEdit ? $news->content : '') }}</textarea>
        <span class="text-[10px] text-slate-400 block">Gunakan paragraf yang jelas agar tampil rapi di halaman publik.</span>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan News' }}
    </button>
</div>
