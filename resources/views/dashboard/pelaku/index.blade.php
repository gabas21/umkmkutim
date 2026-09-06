@extends('layouts.app')

@section('title', 'Dashboard Pelaku Usaha — UMKM Kutim')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Portal Pemilik Usaha</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-1">Halo, {{ $pelaku->nama }}!</h1>
                <p class="text-xs sm:text-sm text-slate-400">Kelola status klaim kepemilikan dan profil bisnis UMKM Anda di Kutai Timur.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('umkm.create-mandiri') }}" class="px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus-circle"></i> Daftarkan Usaha Baru
                </a>
                <a href="{{ route('umkm.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold text-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Klaim dari Data Dinas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- Stat Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $myUmkmList->count() }}</div>
                <div class="text-xs text-slate-500 font-medium">Usaha Terverifikasi</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $klaimList->where('status', 'menunggu')->count() }}</div>
                <div class="text-xs text-slate-500 font-medium">Klaim Menunggu Review</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ number_format($totalViews) }}</div>
                <div class="text-xs text-slate-500 font-medium">Total Dilihat Calon Pembeli</div>
            </div>
        </div>
    </div>

    <!-- Section: Usaha yang Siap Dikelola -->
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i> Usaha yang Anda Kelola (Terverifikasi)
            </h2>
        </div>

        @if($myUmkmList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myUmkmList as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $item->kategori?->nama }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Terverifikasi
                                </span>
                            </div>
                            <h3 class="font-black text-slate-900 text-base">{{ $item->nama_usaha }}</h3>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-emerald-600"></i> Kec. {{ $item->kecamatan }}
                            </p>
                            <p class="text-xs text-slate-600 line-clamp-2 mt-2 leading-relaxed">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                            <a href="{{ route('umkm.show', $item->slug) }}" class="flex-grow py-2 text-center text-xs font-bold rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Lihat Publik
                            </a>
                            <a href="{{ route('dashboard.pelaku.edit', $item->id) }}" class="py-2 px-3 text-xs font-bold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada usaha yang terverifikasi</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Cari data nama usaha Anda yang sudah masuk di sistem pendataan dinas lalu ajukan klaim kepemilikan.</p>
                <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Usaha untuk Diklaim
                </a>
            </div>
        @endif
    </div>

    <!-- Section: Riwayat Pengajuan Klaim -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-file-invoice text-slate-600"></i> Riwayat Pengajuan Klaim Kepemilikan
        </h2>

        @if($klaimList->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-4">Tanggal Diajukan</th>
                                <th class="p-4">Nama UMKM</th>
                                <th class="p-4">Kecamatan</th>
                                <th class="p-4">Status Verifikasi</th>
                                <th class="p-4">Catatan Verifikator</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($klaimList as $k)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 font-mono text-slate-500">{{ $k->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-4 font-bold text-slate-900">{{ $k->umkm?->nama_usaha ?? 'Data dihapus' }}</td>
                                    <td class="p-4">{{ $k->umkm?->kecamatan }}</td>
                                    <td class="p-4">
                                        @if($k->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-hourglass-half"></i> Menunggu Review
                                            </span>
                                        @elseif($k->status === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-circle-check"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-500 max-w-xs">
                                        {{ $k->catatan_admin ?: '-' }}
                                    </td>
                                    <td class="p-4 text-right">
                                        @if($k->umkm)
                                            <a href="{{ route('umkm.show', $k->umkm->slug) }}" class="text-emerald-600 font-bold hover:underline">
                                                Lihat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-xs text-slate-500">Anda belum pernah mengajukan klaim usaha.</p>
        @endif
    </div>
</div>
@endsection
