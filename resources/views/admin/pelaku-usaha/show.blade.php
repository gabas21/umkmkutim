@extends('layouts.admin')

@section('title', 'Detail Pelaku Usaha — ' . $pelaku->nama)

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
        <div>
            <a href="{{ route('admin.pelaku-usaha.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pelaku Usaha
            </a>
            <h1 class="text-xl font-black text-slate-900">{{ $pelaku->nama }}</h1>
            <p class="text-xs text-slate-500 mt-1">{{ $pelaku->email }} &middot; {{ $pelaku->nomor_telepon ?: 'Tanpa nomor telepon' }}</p>
        </div>
        <form action="{{ route('admin.pelaku-usaha.status', $pelaku->id) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            @if($pelaku->status === 'banned')
                <input type="hidden" name="status" value="active">
                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">Aktifkan Akun</button>
            @else
                <input type="hidden" name="status" value="banned">
                <button type="submit" onclick="return confirm('Blokir akun ini?')" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow transition">Blokir Akun</button>
            @endif
        </form>
    </div>

    <!-- Usaha Dimiliki -->
    <div class="space-y-3">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-store text-emerald-600"></i> Usaha yang Dimiliki ({{ $umkmDimiliki->count() }})</h2>
        @if($umkmDimiliki->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($umkmDimiliki as $umkm)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                        <span class="font-bold text-slate-900 text-sm block">{{ $umkm->nama_usaha }}</span>
                        <span class="text-[11px] text-slate-400">{{ $umkm->kategori?->nama }} &middot; Kec. {{ $umkm->kecamatan }}</span>
                        <a href="{{ route('umkm.show', $umkm->slug) }}" target="_blank" class="mt-2 inline-block text-[11px] font-bold text-emerald-700 hover:underline">Lihat Halaman Publik &rarr;</a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-6 text-center text-xs text-slate-400">
                Belum memiliki usaha yang terverifikasi.
            </div>
        @endif
    </div>

    <!-- Riwayat Klaim -->
    <div class="space-y-3">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-file-invoice text-slate-600"></i> Riwayat Pengajuan Klaim ({{ $klaimList->count() }})</h2>
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Usaha yang Diklaim</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Catatan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($klaimList as $klaim)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-mono">{{ $klaim->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ $klaim->umkm?->nama_usaha }}</td>
                                <td class="p-4">
                                    @php
                                        $statusColor = match($klaim->status) {
                                            'disetujui' => 'bg-emerald-100 text-emerald-800',
                                            'ditolak' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }}">{{ $klaim->status }}</span>
                                </td>
                                <td class="p-4 text-slate-500">{{ $klaim->catatan_admin ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400">Belum pernah mengajukan klaim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
