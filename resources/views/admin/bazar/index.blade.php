@extends('layouts.admin')

@section('title', 'Kelola Bazar & Expo — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Bazar &amp; Expo UMKM</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola event bazar, kuota lapak, dan verifikasi peserta yang mendaftar.</p>
        </div>
        <a href="{{ route('admin.bazar.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Buat Event Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.bazar.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama event..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                <option value="">Semua Status</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">Filter</button>
                @if(request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.bazar.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Event</th>
                        <th class="p-4">Jadwal</th>
                        <th class="p-4">Kuota / Peserta</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bazars as $bazar)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $bazar->nama_bazar }}</span>
                                <span class="text-[11px] text-slate-400">{{ $bazar->lokasi }}, Kec. {{ $bazar->kecamatan }}</span>
                            </td>
                            <td class="p-4 font-mono text-[11px]">
                                {{ $bazar->tanggal_mulai->format('d/m/Y') }} - {{ $bazar->tanggal_selesai->format('d/m/Y') }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800">{{ $bazar->peserta_count }}</span> / {{ $bazar->kuota_peserta }}
                            </td>
                            <td class="p-4">
                                @php
                                    $statusColor = match($bazar->status) {
                                        'upcoming' => 'bg-blue-100 text-blue-800',
                                        'ongoing' => 'bg-emerald-100 text-emerald-800',
                                        'closed' => 'bg-amber-100 text-amber-800',
                                        default => 'bg-slate-200 text-slate-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }} capitalize">{{ $bazar->status }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('bazar.show', $bazar->slug) }}" target="_blank" class="p-1.5 text-slate-500 hover:text-emerald-600" title="Lihat Publik">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('admin.bazar.peserta', $bazar->id) }}" class="p-1.5 text-purple-600 hover:text-purple-800" title="Kelola Peserta">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                    <a href="{{ route('admin.bazar.edit', $bazar->id) }}" class="p-1.5 text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.bazar.destroy', $bazar->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus event bazar ini beserta seluruh data pesertanya?')">
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
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada event bazar yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $bazars->links() }}
        </div>
    </div>
</div>
@endsection
