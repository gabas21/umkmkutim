@extends('layouts.app')

@section('title', 'Manajemen Slider Hero — Panel Admin Dinas')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Admin
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Manajemen Banner Slider Hero Beranda</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Upload dan atur gambar poster/banner pengumuman resmi pada slider utama halaman depan.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Upload Banner Baru (Left 1 Col) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 h-fit">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-emerald-600"></i> Upload Banner Slider Baru
            </h3>

            <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Judul / Keterangan Banner <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: Kutim Expo & Gelar Dagang 2026" value="{{ old('judul') }}"
                           class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">File Gambar Banner <span class="text-rose-500">*</span></label>
                    <input type="file" name="gambar" required accept="image/png,image/jpeg,image/jpg,image/webp"
                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                    <span class="text-[10px] text-slate-400 block">Rekomendasi rasio horizontal 1350x572 px (PNG, JPG, WEBP maks 4MB)</span>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Tautan Tujuan (Opsional)</label>
                    <input type="url" name="link_url" placeholder="https://dinkop.kutaitimurkab.go.id/..." value="{{ old('link_url') }}"
                           class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <span class="text-[10px] text-slate-400 block">Kosongkan jika banner hanya untuk preview poster</span>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                           class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <span class="text-[10px] text-slate-400 block">Angka lebih kecil akan tampil lebih dulu (0, 1, 2...)</span>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition active:scale-95">
                    Upload & Tayangkan Slider
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Banner (Right 2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">Daftar Banner Slider Terpasang ({{ $slides->count() }})</span>
                <span class="text-slate-400 text-[11px]">* Jika tidak ada banner kustom, slider beranda menampilkan 3 poster default dinas</span>
            </div>

            @if($slides->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-regular fa-images"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Belum Ada Banner Kustom Terupload</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                        Saat ini beranda menggunakan 3 slide poster pengumuman bawaan sistem (Hari Kebangkitan, Kutim Expo, Pelatihan Halal). Silakan upload gambar banner baru melalui form di samping.
                    </p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($slides as $slide)
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-28 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    <img src="{{ str_starts_with($slide->gambar, 'http') ? $slide->gambar : asset('storage/' . $slide->gambar) }}" 
                                         alt="{{ $slide->judul }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $slide->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $slide->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">Urutan: {{ $slide->urutan }}</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $slide->judul }}</h4>
                                    @if($slide->link_url)
                                        <a href="{{ $slide->link_url }}" target="_blank" class="text-[11px] text-emerald-700 hover:underline flex items-center gap-1">
                                            <span>{{ Str::limit($slide->link_url, 45) }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <form action="{{ route('admin.hero-slides.toggle', $slide->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg border text-xs font-semibold {{ $slide->is_active ? 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }} transition">
                                        {{ $slide->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.hero-slides.destroy', $slide->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner slider ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition" 
                                            title="Hapus Banner">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
