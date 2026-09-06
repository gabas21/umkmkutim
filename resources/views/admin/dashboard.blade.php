@extends('layouts.app')

@section('title', 'Panel Administrator Dinas — Verifikasi Klaim UMKM Kutim')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Pusat Kendali Administrasi Daerah</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-1">Panel Verifikasi Diskop & UMKM Kutim</h1>
                <p class="text-xs sm:text-sm text-slate-400">Verifikasi dokumen identitas, kelola klaim kepemilikan, dan pantau rekapitulasi data 18 kecamatan.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-lg bg-emerald-950 border border-emerald-800 text-emerald-300 text-xs font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-user-shield text-emerald-400"></i>
                    <span>{{ Auth::user()->name }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-500 font-medium">Total Database UMKM</div>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUmkm) }}</div>
            <span class="text-[11px] text-slate-400">Target Dinas 45.000</span>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-amber-300 shadow-sm bg-gradient-to-br from-white to-amber-50/50">
            <div class="text-xs text-amber-700 font-bold flex items-center gap-1">
                <i class="fa-solid fa-hourglass-half"></i> Menunggu Verifikasi
            </div>
            <div class="text-3xl font-black text-amber-900 mt-1">{{ $klaimMenunggu }}</div>
            <span class="text-[11px] text-amber-600">Perlu tindak lanjut admin</span>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-emerald-300 shadow-sm bg-gradient-to-br from-white to-emerald-50/50">
            <div class="text-xs text-emerald-700 font-bold flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> Sudah Terverifikasi
            </div>
            <div class="text-3xl font-black text-emerald-900 mt-1">{{ number_format($umkmTerverifikasi) }}</div>
            <span class="text-[11px] text-emerald-600">Pemilik sah aktif</span>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-500 font-medium">Sektor Komoditas</div>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalKategori }}</div>
            <span class="text-[11px] text-slate-400">Kategori Usaha</span>
        </div>
    </div>

    <!-- Section: Permohonan Klaim Menunggu Review -->
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-inbox text-amber-600"></i> Antrean Permohonan Klaim Menunggu Verifikasi ({{ $klaimMenunggu }})
            </h2>
        </div>

        @if($pendingKlaimList->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Usaha yang Diklaim</th>
                                <th class="p-4">Nama Pemohon</th>
                                <th class="p-4">Kontak Pemohon</th>
                                <th class="p-4">Dokumen</th>
                                <th class="p-4 text-right">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pendingKlaimList as $k)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 font-mono text-slate-500">{{ $k->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-4">
                                        <span class="font-bold text-slate-900 text-sm block">{{ $k->umkm?->nama_usaha }}</span>
                                        <span class="text-[11px] text-emerald-700 font-semibold">Kec. {{ $k->umkm?->kecamatan }}</span>
                                    </td>
                                    <td class="p-4 font-bold text-slate-800">{{ $k->pelakuUsaha?->nama }}</td>
                                    <td class="p-4">
                                        <div>{{ $k->pelakuUsaha?->email }}</div>
                                        <div class="text-slate-400">{{ $k->pelakuUsaha?->nomor_telepon ?: '-' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ asset('storage/' . $k->dokumen_ktp) }}" target="_blank" class="px-2 py-1 rounded bg-blue-50 text-blue-700 hover:underline font-semibold text-[11px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-id-card"></i> KTP
                                            </a>
                                            @if($k->dokumen_bukti_usaha)
                                                <a href="{{ asset('storage/' . $k->dokumen_bukti_usaha) }}" target="_blank" class="px-2 py-1 rounded bg-purple-50 text-purple-700 hover:underline font-semibold text-[11px] inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-file"></i> Bukti Usaha
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('admin.klaim.show', $k->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                            <i class="fa-solid fa-clipboard-check"></i> Periksa & Putuskan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $pendingKlaimList->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center space-y-2">
                <i class="fa-solid fa-check-double text-3xl text-emerald-500"></i>
                <h4 class="text-sm font-bold text-slate-800">Semua Berkas Klaim Telah Ditinjau</h4>
                <p class="text-xs text-slate-500">Tidak ada pengajuan klaim baru yang menunggu verifikasi saat ini.</p>
            </div>
        @endif
    </div>

    <!-- Section: Rekap Wilayah per Kecamatan -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-slate-700"></i> Rekapitulasi Data Sebaran per Kecamatan
        </h2>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="p-4">Kecamatan</th>
                            <th class="p-4">Total UMKM Terdata</th>
                            <th class="p-4">Klaim Terverifikasi</th>
                            <th class="p-4">Persentase Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rekapKecamatan as $item)
                            @php
                                $percent = $item->total_umkm > 0 ? round(($item->total_terverifikasi / $item->total_umkm) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $item->kecamatan }}</td>
                                <td class="p-4 font-mono font-semibold">{{ number_format($item->total_umkm) }}</td>
                                <td class="p-4 font-mono font-semibold text-emerald-700">{{ number_format($item->total_terverifikasi) }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-slate-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="font-mono text-[11px] font-bold">{{ $percent }}%</span>
                                    </div>
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
