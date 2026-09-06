@extends('layouts.app')

@section('title', 'Moderasi Data UMKM — Panel Admin')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Admin
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Moderasi & Pengawasan Data UMKM</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Kontrol status publikasi, cek kepemilikan usaha, dan tinjau profil usaha di seluruh kecamatan</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.umkm.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div class="lg:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha atau alamat..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="relative">
                <select name="kecamatan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none appearance-none">
                    <option value="">Semua Kecamatan</option>
                    @foreach($daftarKecamatan as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-3 text-[10px] pointer-events-none"></i>
            </div>

            <div class="relative">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none appearance-none">
                    <option value="">Semua Status Usaha</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-3 text-[10px] pointer-events-none"></i>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'kecamatan', 'status']))
                    <a href="{{ route('admin.umkm.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Usaha & Kategori</th>
                        <th class="p-4">Wilayah</th>
                        <th class="p-4">Sumber Data</th>
                        <th class="p-4">Kepemilikan</th>
                        <th class="p-4">Status Usaha</th>
                        <th class="p-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($umkmList as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $item->nama_usaha }}</span>
                                <span class="text-[11px] text-slate-400">{{ $item->kategori?->nama }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block">Kec. {{ $item->kecamatan }}</span>
                                <span class="text-[10px] text-slate-400 truncate max-w-xs block">{{ $item->alamat }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->sumber_data == 'mandiri' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ strtoupper($item->sumber_data) }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($item->status_klaim === 'terverifikasi')
                                    <span class="text-emerald-700 font-bold block flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Terverifikasi
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $item->klaimAktif?->pelakuUsaha?->nama }}</span>
                                @elseif($item->status_klaim === 'menunggu_verifikasi')
                                    <span class="text-amber-700 font-bold block flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> Menunggu Review
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum Ada Pemilik</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($item->status === 'active')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @elseif($item->status === 'suspended')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Ditangguhkan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('umkm.show', $item->slug) }}" target="_blank" class="p-1.5 text-slate-500 hover:text-emerald-600" title="Buka Detail">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>

                                    <form action="{{ route('admin.umkm.status', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($item->status === 'active')
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="p-1.5 text-amber-600 hover:text-amber-800" title="Tangguhkan (Suspend)">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="p-1.5 text-emerald-600 hover:text-emerald-800" title="Aktifkan Kembali">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada data UMKM yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $umkmList->links() }}
        </div>
    </div>
</div>
@endsection
