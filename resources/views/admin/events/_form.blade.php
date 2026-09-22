@php
    $isEdit = isset($event);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Judul Event <span class="text-rose-500">*</span></label>
        <input type="text" name="title" required value="{{ old('title', $isEdit ? $event->title : '') }}"
            placeholder="Contoh: Pelatihan Digital Marketing UMKM Kutim"
            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tipe Event <span class="text-rose-500">*</span></label>
        <select name="type" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            @foreach(['workshop' => 'Workshop', 'seminar' => 'Seminar', 'exhibition' => 'Exhibition', 'bazar' => 'Bazar'] as $value => $label)
                <option value="{{ $value }}" {{ old('type', $isEdit ? $event->type : 'workshop') == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Status <span class="text-rose-500">*</span></label>
        <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            <option value="draft" {{ old('status', $isEdit ? $event->status : 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="open" {{ old('status', $isEdit ? $event->status : 'draft') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ old('status', $isEdit ? $event->status : 'draft') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
        <input type="datetime-local" name="start_date" required value="{{ old('start_date', $isEdit && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Tanggal Selesai</label>
        <input type="datetime-local" name="end_date" value="{{ old('end_date', $isEdit && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Lokasi</label>
        <input type="text" name="location" value="{{ old('location', $isEdit ? $event->location : '') }}" placeholder="Contoh: Gedung Expo Kutim" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">Kuota</label>
        <input type="number" min="1" name="quota" value="{{ old('quota', $isEdit ? $event->quota : '') }}" placeholder="Contoh: 150" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    </div>

    <div class="space-y-1.5 sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700">Deskripsi Event</label>
        <textarea name="description" rows="8" class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('description', $isEdit ? $event->description : '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
    <a href="{{ route('admin.events.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Event' }}
    </button>
</div>
