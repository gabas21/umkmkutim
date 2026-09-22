@extends('layouts.admin')

@section('title', 'Peserta Bazar — ' . $bazar->nama_bazar)

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
        <div>
            <a href="{{ route('admin.bazar.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Bazar
            </a>
            <h1 class="text-xl font-black text-slate-900">Peserta: {{ $bazar->nama_bazar }}</h1>
            <p class="text-xs text-slate-500 mt-1">Kuota {{ $bazar->kuota_peserta }} lapak &middot; Sisa kuota {{ $bazar->sisa_kuota }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Tanggal Daftar</th>
                        <th class="p-4">Usaha & Pemilik</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Kategori Produk</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesertaList as $p)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono text-slate-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $p->nama_usaha }}</span>
                                <span class="text-[11px] text-slate-400">{{ $p->nama_pemilik }}</span>
                            </td>
                            <td class="p-4">
                                <div>{{ $p->nomor_hp }}</div>
                                <div class="text-slate-400">{{ $p->email ?: '-' }}</div>
                            </td>
                            <td class="p-4">{{ $p->kategori_produk }}</td>
                            <td class="p-4">
                                @php
                                    $statusColor = match($p->status) {
                                        'diterima' => 'bg-emerald-100 text-emerald-800',
                                        'ditolak' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }} capitalize">{{ $p->status }}</span>
                            </td>
                            <td class="p-4 text-right">
                                @if($p->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.bazar.peserta.approve', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition">Terima</button>
                                        </form>
                                        <form action="{{ route('admin.bazar.peserta.reject', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-[11px] transition">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-300 text-[10px] italic">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada peserta yang mendaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $pesertaList->links() }}
        </div>
    </div>
</div>
@endsection
