@php
    $isEdit = isset($pelatihan);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Judul Pelatihan <span class="text-rose-500">*</span></label>
        <input type="text" name="judul" required value="{{ old('judul', $isEdit ? $pelatihan->judul : '') }}"
               placeholder="Contoh: Pelatihan Digital Marketing untuk UMKM"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Penyelenggara</label>
        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $isEdit ? $pelatihan->penyelenggara : 'Dinas Koperasi & UKM Kab. Kutai Timur') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Instruktur</label>
        <input type="text" name="instruktur" value="{{ old('instruktur', $isEdit ? $pelatihan->instruktur : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Lokasi <span class="text-rose-500">*</span></label>
        <input type="text" name="lokasi" required value="{{ old('lokasi', $isEdit ? $pelatihan->lokasi : 'Gedung Diklat Bukit Pelangi, Sangatta') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Mode Pelaksanaan <span class="text-rose-500">*</span></label>
        <select name="mode" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach(['offline' => 'Tatap Muka (Offline)', 'online' => 'Daring (Online)', 'hybrid' => 'Hybrid'] as $val => $label)
                <option value="{{ $val }}" {{ old('mode', $isEdit ? $pelatihan->mode : 'offline') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tautan Zoom / Meeting</label>
        <input type="url" name="link_zoom" value="{{ old('link_zoom', $isEdit ? $pelatihan->link_zoom : '') }}"
               placeholder="https://zoom.us/j/..."
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal &amp; Waktu Mulai <span class="text-rose-500">*</span></label>
        <input type="datetime-local" name="tanggal_mulai" required value="{{ old('tanggal_mulai', $isEdit ? $pelatihan->tanggal_mulai->format('Y-m-d\TH:i') : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal &amp; Waktu Selesai <span class="text-rose-500">*</span></label>
        <input type="datetime-local" name="tanggal_selesai" required value="{{ old('tanggal_selesai', $isEdit ? $pelatihan->tanggal_selesai->format('Y-m-d\TH:i') : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kuota Peserta <span class="text-rose-500">*</span></label>
        <input type="number" name="kuota" min="1" required value="{{ old('kuota', $isEdit ? $pelatihan->kuota : 40) }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Biaya (Rp)</label>
        <input type="number" name="biaya" min="0" step="0.01" value="{{ old('biaya', $isEdit ? $pelatihan->biaya : 0) }}"
               placeholder="0 jika gratis"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Status Pelatihan <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach(['upcoming' => 'Akan Datang', 'ongoing' => 'Sedang Berlangsung', 'closed' => 'Pendaftaran Ditutup', 'selesai' => 'Selesai'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $isEdit ? $pelatihan->status : 'upcoming') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Banner Pelatihan</label>
        @if($isEdit && $pelatihan->banner_url)
            <img src="{{ asset('storage/' . $pelatihan->banner_url) }}" alt="Banner saat ini" class="w-40 h-24 object-cover rounded-xl border border-slate-200 mb-2">
        @endif
        <input type="file" name="banner" accept="image/png,image/jpeg,image/jpg,image/webp"
               class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
        <span class="text-[10px] text-slate-400 block">Maksimal 3MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti banner.' : '' }}</span>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Materi Ringkas</label>
        <textarea name="materi_ringkas" rows="3" class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('materi_ringkas', $isEdit ? $pelatihan->materi_ringkas : '') }}</textarea>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Syarat Peserta</label>
        <textarea name="syarat_peserta" rows="3" placeholder="Contoh: Membawa laptop, memiliki usaha aktif"
                  class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('syarat_peserta', $isEdit ? $pelatihan->syarat_peserta : '') }}</textarea>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Deskripsi Pelatihan <span class="text-rose-500">*</span></label>
        <textarea name="deskripsi" rows="6" required
                  class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('deskripsi', $isEdit ? $pelatihan->deskripsi : '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('admin.pelatihan.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pelatihan' }}
    </button>
</div>
