@extends('layouts.admin')

@section('title', 'Manajemen Pelaku Usaha — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-900">Manajemen Pelaku Usaha</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola akun pemilik usaha, status aktivasi, dan riwayat klaim mereka.</p>
    </div>

    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.pelaku-usaha.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Diblokir</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">Filter</button>
                @if(request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.pelaku-usaha.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama & Kontak</th>
                        <th class="p-4">Bergabung</th>
                        <th class="p-4">Total Klaim</th>
                        <th class="p-4">Usaha Dimiliki</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pelakuList as $pelaku)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $pelaku->nama }}</span>
                                <span class="text-[11px] text-slate-400">{{ $pelaku->email }}</span>
                            </td>
                            <td class="p-4 font-mono text-[11px]">{{ $pelaku->created_at->format('d/m/Y') }}</td>
                            <td class="p-4 font-mono font-semibold">{{ $pelaku->klaim_usaha_count }}</td>
                            <td class="p-4 font-mono font-semibold text-emerald-700">{{ $pelaku->umkm_terverifikasi_count }}</td>
                            <td class="p-4">
                                @php
                                    $statusColor = match($pelaku->status) {
                                        'active' => 'bg-emerald-100 text-emerald-800',
                                        'banned' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColor }} capitalize">{{ $pelaku->status }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pelaku-usaha.show', $pelaku->id) }}" class="p-1.5 text-slate-500 hover:text-emerald-600" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.pelaku-usaha.status', $pelaku->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($pelaku->status === 'banned')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="p-1.5 text-emerald-600 hover:text-emerald-800" title="Aktifkan Kembali"><i class="fa-solid fa-check"></i></button>
                                        @else
                                            <input type="hidden" name="status" value="banned">
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Blokir Akun" onclick="return confirm('Blokir akun ini?')"><i class="fa-solid fa-ban"></i></button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada akun pelaku usaha yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $pelakuList->links() }}
        </div>
    </div>
</div>
@endsection
