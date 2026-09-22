@php
    $isEdit = isset($bazar);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Nama Event Bazar <span class="text-rose-500">*</span></label>
        <input type="text" name="nama_bazar" required value="{{ old('nama_bazar', $isEdit ? $bazar->nama_bazar : '') }}"
               placeholder="Contoh: Kutim Expo & Gelar Dagang UMKM 2026"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Lokasi <span class="text-rose-500">*</span></label>
        <input type="text" name="lokasi" required value="{{ old('lokasi', $isEdit ? $bazar->lokasi : '') }}"
               placeholder="Contoh: Alun-Alun Sangatta"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kecamatan <span class="text-rose-500">*</span></label>
        <select name="kecamatan" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach($daftarKecamatan as $kec)
                <option value="{{ $kec }}" {{ old('kecamatan', $isEdit ? $bazar->kecamatan : 'Sangatta Utara') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Alamat Lengkap</label>
        <input type="text" name="alamat_lengkap" value="{{ old('alamat_lengkap', $isEdit ? $bazar->alamat_lengkap : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
        <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai', $isEdit ? $bazar->tanggal_mulai->format('Y-m-d') : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal Selesai <span class="text-rose-500">*</span></label>
        <input type="date" name="tanggal_selesai" required value="{{ old('tanggal_selesai', $isEdit ? $bazar->tanggal_selesai->format('Y-m-d') : '') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kuota Peserta <span class="text-rose-500">*</span></label>
        <input type="number" name="kuota_peserta" min="1" required value="{{ old('kuota_peserta', $isEdit ? $bazar->kuota_peserta : 50) }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Status Event <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach(['upcoming' => 'Akan Datang', 'ongoing' => 'Sedang Berlangsung', 'closed' => 'Pendaftaran Ditutup', 'selesai' => 'Selesai'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $isEdit ? $bazar->status : 'upcoming') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Penyelenggara</label>
        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $isEdit ? $bazar->penyelenggara : 'Dinas Koperasi & UKM Kab. Kutai Timur') }}"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kontak Person</label>
        <input type="text" name="kontak_person" value="{{ old('kontak_person', $isEdit ? $bazar->kontak_person : '') }}"
               placeholder="Contoh: 0812xxxxxxx (Ibu Rina)"
               class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Fasilitas untuk Peserta</label>
        <textarea name="fasilitas" rows="3" placeholder="Contoh: Tenda, meja, kursi, listrik gratis"
                  class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('fasilitas', $isEdit ? $bazar->fasilitas : '') }}</textarea>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Banner Event</label>
        @if($isEdit && $bazar->banner_url)
            <img src="{{ asset('storage/' . $bazar->banner_url) }}" alt="Banner saat ini" class="w-40 h-24 object-cover rounded-xl border border-slate-200 mb-2">
        @endif
        <input type="file" name="banner" accept="image/png,image/jpeg,image/jpg,image/webp"
               class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
        <span class="text-[10px] text-slate-400 block">Maksimal 3MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti banner.' : '' }}</span>
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Deskripsi Event <span class="text-rose-500">*</span></label>
        <textarea name="deskripsi" rows="6" required
                  class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('deskripsi', $isEdit ? $bazar->deskripsi : '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('admin.bazar.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Event Bazar' }}
    </button>
</div>
