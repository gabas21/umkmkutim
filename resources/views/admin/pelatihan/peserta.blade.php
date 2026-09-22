@extends('layouts.admin')

@section('title', 'Peserta Pelatihan — ' . $pelatihan->judul)

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <a href="{{ route('admin.pelatihan.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pelatihan
        </a>
        <h1 class="text-xl font-black text-slate-900">Peserta: {{ $pelatihan->judul }}</h1>
        <p class="text-xs text-slate-500 mt-1">Kuota {{ $pelatihan->kuota }} peserta &middot; Sisa kuota {{ $pelatihan->sisa_kuota }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Tanggal Daftar</th>
                        <th class="p-4">Peserta & Usaha</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Instansi</th>
                        <th class="p-4">Status Kehadiran</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesertaList as $p)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono text-slate-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $p->nama_peserta }}</span>
                                <span class="text-[11px] text-slate-400">{{ $p->nama_usaha ?: '-' }}</span>
                            </td>
                            <td class="p-4">
                                <div>{{ $p->nomor_hp }}</div>
                                <div class="text-slate-400">{{ $p->email }}</div>
                            </td>
                            <td class="p-4">{{ $p->instansi ?: '-' }}</td>
                            <td class="p-4">
                                @php
                                    $statusColor = match($p->status) {
                                        'hadir' => 'bg-emerald-100 text-emerald-800',
                                        'tidak_hadir' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }}">{{ str_replace('_', ' ', $p->status) }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.pelatihan.peserta.status', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="hadir">
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition">Hadir</button>
                                    </form>
                                    <form action="{{ route('admin.pelatihan.peserta.status', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="tidak_hadir">
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-[11px] transition">Tidak Hadir</button>
                                    </form>
                                </div>
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
