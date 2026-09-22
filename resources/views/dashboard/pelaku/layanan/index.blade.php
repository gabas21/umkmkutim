@extends('layouts.pelaku')

@section('title', 'Kelola Layanan/Produk — ' . $umkm->nama_usaha)

@section('pelaku-content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <a href="{{ route('dashboard.pelaku.edit', $umkm->id) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Profil Usaha
            </a>
            <h1 class="text-xl font-black text-slate-900">Layanan/Produk: {{ $umkm->nama_usaha }}</h1>
            <p class="text-xs text-slate-500 mt-1">Tampilkan daftar produk atau layanan unggulan usaha Anda di halaman publik.</p>
        </div>
        <a href="{{ route('dashboard.pelaku.layanan.create', $umkm->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah Layanan Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Layanan/Produk</th>
                        <th class="p-4">Harga Mulai</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layananList as $layanan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $layanan->nama_layanan }}</span>
                                <span class="text-[11px] text-slate-400 line-clamp-1">{{ $layanan->deskripsi }}</span>
                            </td>
                            <td class="p-4 font-mono">
                                {{ $layanan->harga_mulai ? 'Rp ' . number_format($layanan->harga_mulai, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-4">
                                @if($layanan->status === 'active')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('dashboard.pelaku.layanan.edit', [$umkm->id, $layanan->id]) }}" class="p-1.5 text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('dashboard.pelaku.layanan.destroy', [$umkm->id, $layanan->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Belum ada layanan/produk yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
