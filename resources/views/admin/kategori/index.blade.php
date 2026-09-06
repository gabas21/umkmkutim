@extends('layouts.app')

@section('title', 'Manajemen Master Kategori — Panel Admin')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Admin
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Manajemen Sektor & Kategori Usaha</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Klasifikasi industri komoditas UMKM di Kabupaten Kutai Timur</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Tambah Kategori (Left 1 Col) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 h-fit">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-emerald-600"></i> Tambah Kategori Baru
            </h3>

            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Pariwisata & Homestay" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Ikon FontAwesome</label>
                    <input type="text" name="icon" placeholder="Contoh: utensils, shirt, palette, fish, sprout" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono">
                    <span class="text-[10px] text-slate-400 block">Nama icon FontAwesome (tanpa awalan fa-)</span>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kategori (Right 2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">Daftar Kategori Aktif ({{ $kategoriList->count() }})</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="p-3.5">Ikon</th>
                            <th class="p-3.5">Nama Kategori</th>
                            <th class="p-3.5">Slug</th>
                            <th class="p-3.5">Jumlah UMKM</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kategoriList as $kat)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                                        <i class="fa-solid fa-{{ $kat->icon ?: 'store' }}"></i>
                                    </div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900">{{ $kat->nama }}</td>
                                <td class="p-3.5 font-mono text-slate-400 text-[11px]">{{ $kat->slug }}</td>
                                <td class="p-3.5 font-mono font-semibold">{{ $kat->umkm_count }} usaha</td>
                                <td class="p-3.5 text-right">
                                    @if($kat->umkm_count == 0)
                                        <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Hapus Kategori">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-[10px] italic">Terhubung data</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
