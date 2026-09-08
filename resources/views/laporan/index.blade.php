@extends('layouts.app')

@section('title', 'Laporan UMKM — Data Statistik & Analisis Kabupaten Kutai Timur')

@section('content')
<main class="overflow-x-hidden w-full max-w-full bg-[#f8fafc] text-slate-900 pb-24">
    <!-- Header Banner & Title -->
    <section class="bg-white border-b border-slate-200/80 pt-10 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                        <a href="{{ route('home') }}" class="hover:text-emerald-700 transition">Beranda</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-emerald-800 font-bold">Laporan UMKM</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight">
                        Laporan UMKM
                    </h1>
                    <p class="text-sm sm:text-base text-slate-600 mt-1 font-normal">
                        Data statistik dan analisis UMKM Kabupaten Kutai Timur
                    </p>

                    <div class="inline-flex items-center gap-2 mt-3 text-xs font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Terakhir diperbarui: <strong>{{ $lastUpdated }}</strong></span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-print text-slate-500"></i>
                        <span>Cetak Laporan</span>
                    </button>
                    <a href="{{ route('survey.create') }}" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-emerald-900/20 active:scale-95">
                        <i class="fa-solid fa-star text-amber-300"></i>
                        <span>Isi Survey Layanan</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Top 4 Executive KPI Cards -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Total UMKM -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                {{ number_format($totalUmkm) }}
                            </div>
                            <div class="text-xs font-bold text-slate-500 mt-1">Total UMKM</div>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shadow-xs border border-emerald-100">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs text-emerald-700 font-semibold">
                        <i class="fa-solid fa-arrow-trend-up text-[11px]"></i>
                        <span>+0% dari bulan lalu</span>
                    </div>
                </div>

                <!-- Card 2: UMKM Terverifikasi -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                {{ number_format($totalTerverifikasi) }}
                            </div>
                            <div class="text-xs font-bold text-slate-500 mt-1">UMKM Terverifikasi</div>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg shadow-xs border border-teal-100">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs text-teal-700 font-semibold">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        <span>{{ $persenTerverifikasi }}% dari total</span>
                    </div>
                </div>

                <!-- Card 3: Kecamatan Terjangkau -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                {{ $totalKecamatan }}
                            </div>
                            <div class="text-xs font-bold text-slate-500 mt-1">Kecamatan Terjangkau</div>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg shadow-xs border border-blue-100">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs text-blue-700 font-semibold">
                        <i class="fa-solid fa-globe text-[11px]"></i>
                        <span>Wilayah coverage (18 Kec.)</span>
                    </div>
                </div>

                <!-- Card 4: Sektor Usaha -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                {{ $totalSektor }}
                            </div>
                            <div class="text-xs font-bold text-slate-500 mt-1">Sektor Usaha</div>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg shadow-xs border border-amber-100">
                            <i class="fa-solid fa-shapes"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs text-amber-700 font-semibold">
                        <i class="fa-solid fa-tag text-[11px]"></i>
                        <span>Jenis sektor terdaftar</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dual Aggregate Panels (Statistik Keuangan & Statistik Ketenagakerjaan) -->
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Panel 1: Statistik Keuangan -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-coins text-white"></i>
                        </div>
                        <h2 class="text-base font-black tracking-wide">Statistik Keuangan</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">Total Revenue Tahunan</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                Rp {{ number_format($totalRevenueTahunan, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">Rata-rata Pendapatan / Bulan</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                Rp {{ number_format($avgPendapatanBulanan, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-vault"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">Rata-rata Nilai Aset</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                Rp {{ number_format($avgNilaiAset, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Panel 2: Statistik Ketenagakerjaan -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-users-gear text-white"></i>
                        </div>
                        <h2 class="text-base font-black tracking-wide">Statistik Ketenagakerjaan</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">Total Tenaga Kerja</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                {{ $totalTenagaKerja }} Orang
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">Rata-rata Karyawan / UMKM</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                {{ $avgKaryawan }} Orang
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-base">
                                    <i class="fa-solid fa-building-user"></i>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700">UMKM dengan Karyawan</span>
                            </div>
                            <span class="text-sm sm:text-base font-black text-slate-900">
                                {{ $umkmDenganKaryawan }} UMKM
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart Row 1: Donut Charts (Distribusi Skala Usaha & Status Perizinan) -->
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Donut Chart 1: Skala Usaha -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">Distribusi Skala Usaha</h3>
                                <p class="text-xs text-slate-500">Mikro, Kecil, Menengah</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Skala Usaha</span>
                    </div>

                    <div class="relative py-6 flex items-center justify-center h-[260px]">
                        <canvas id="skalaDonutChart"></canvas>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-around text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#0284c7]"></span>
                            <span class="font-semibold text-slate-700">Mikro ({{ $skalaMikro }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span>
                            <span class="font-semibold text-slate-700">Kecil ({{ $skalaKecil }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                            <span class="font-semibold text-slate-700">Menengah ({{ $skalaMenengah }})</span>
                        </div>
                    </div>
                </div>

                <!-- Donut Chart 2: Status Perizinan -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">Status Perizinan</h3>
                                <p class="text-xs text-slate-500">Draft, Diajukan, Terverifikasi, Ditolak</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Verifikasi NIB</span>
                    </div>

                    <div class="relative py-6 flex items-center justify-center h-[260px]">
                        <canvas id="perizinanDonutChart"></canvas>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-around gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#64748b]"></span>
                            <span class="font-semibold text-slate-700">Draft / Lapangan ({{ $statusPerizinan['draft'] }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span>
                            <span class="font-semibold text-slate-700">Diajukan ({{ $statusPerizinan['diajukan'] }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#059669]"></span>
                            <span class="font-semibold text-slate-700">Terverifikasi ({{ $statusPerizinan['terverifikasi'] }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart Row 2: Horizontal Bar Charts (Distribusi per Kecamatan & Sektor Usaha) -->
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bar Chart 1: Distribusi per Kecamatan -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">Distribusi per Kecamatan</h3>
                                <p class="text-xs text-slate-500">Top 10 kecamatan dengan UMKM terbanyak</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            {{ $totalKecamatan }} Kecamatan
                        </span>
                    </div>

                    <div class="relative h-[360px] w-full">
                        <canvas id="kecamatanBarChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart 2: Distribusi per Sektor Usaha -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-shapes"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">Distribusi per Sektor Usaha</h3>
                                <p class="text-xs text-slate-500">Top 10 sektor dengan UMKM terbanyak</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                            {{ $totalSektor }} Sektor
                        </span>
                    </div>

                    <div class="relative h-[360px] w-full">
                        <canvas id="sektorBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Table 1: UMKM Terbaru -->
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">UMKM Terbaru</h3>
                            <p class="text-xs text-slate-500">10 UMKM yang baru terdaftar di pangkalan data</p>
                        </div>
                    </div>
                    <a href="{{ route('umkm.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                        <span>Lihat Semua Direktori</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Nama UMKM</th>
                                <th scope="col" class="px-6 py-3.5">Pemilik</th>
                                <th scope="col" class="px-6 py-3.5">Kecamatan</th>
                                <th scope="col" class="px-6 py-3.5">Sektor</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Skala</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentUmkm as $item)
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        <a href="{{ route('umkm.show', $item->slug) }}" class="hover:text-emerald-700 transition">
                                            {{ $item->nama_usaha }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $item->telepon ? 'Pelaku Usaha Mandiri' : 'Pengrajin / Pelaku Usaha' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-slate-400 text-[10px]"></i>
                                            {{ $item->kecamatan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                        {{ $item->kategori?->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                                            Mikro
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($item->status_klaim === 'terverifikasi')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Terverifikasi
                                            </span>
                                        @elseif($item->status_klaim === 'menunggu_verifikasi')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                Diajukan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                Draft / Lapangan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-400 whitespace-nowrap">
                                        {{ $item->created_at ? $item->created_at->format('d M Y') : '10 Nov 2024' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada data UMKM terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Table 2: UMKM Berpendapatan Tertinggi (Empty State) -->
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">UMKM Berpendapatan Tertinggi</h3>
                            <p class="text-xs text-slate-500">Top 10 UMKM berdasarkan pendapatan bulanan</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Laporan Finansial</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">#</th>
                                <th scope="col" class="px-6 py-3.5">Nama UMKM</th>
                                <th scope="col" class="px-6 py-3.5">Pemilik</th>
                                <th scope="col" class="px-6 py-3.5">Sektor</th>
                                <th scope="col" class="px-6 py-3.5">Kecamatan</th>
                                <th scope="col" class="px-6 py-3.5">Pendapatan / Bulan</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Karyawan</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Empty State Card Matching User Reference -->
                <div class="py-16 px-6 text-center max-w-lg mx-auto space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-full bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <h4 class="text-base font-extrabold text-slate-900">Data Pendapatan Belum Tersedia</h4>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Data pendapatan bulanan UMKM belum dikumpulkan. Silakan hubungi pelaku usaha untuk melengkapi informasi pendapatan bulanan, nilai aset, dan jumlah karyawan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Rekapitulasi Indeks Kepuasan Masyarakat (IKM) -->
    <section class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                            Penjaminan Mutu Layanan
                        </span>
                        <h2 class="text-2xl font-black text-slate-900 mt-1">Indeks Kepuasan Masyarakat (IKM)</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            Berdasarkan masukan langsung dari {{ $totalSurvey }} responden masyarakat dan pelaku usaha Kutai Timur
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Skor Agregat: {{ number_format($indeksRataRata, 1) }} / 5.0 ({{ $indeksPersen }}%)
                        </span>
                        <a href="{{ route('survey.create') }}" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-xs font-bold text-white transition shadow-xs">
                            Isi Survey &rarr;
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kemudahan Akses</div>
                        <div class="text-xl font-black text-emerald-700">{{ number_format($avgKemudahan, 1) }} / 5.0</div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ ($avgKemudahan / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kecepatan Respon</div>
                        <div class="text-xl font-black text-emerald-700">{{ number_format($avgKecepatan, 1) }} / 5.0</div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ ($avgKecepatan / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Keramahan Petugas</div>
                        <div class="text-xl font-black text-emerald-700">{{ number_format($avgKeramahan, 1) }} / 5.0</div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ ($avgKeramahan / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kemanfaatan Program</div>
                        <div class="text-xl font-black text-emerald-700">{{ number_format($avgKemanfaatan, 1) }} / 5.0</div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ ($avgKemanfaatan / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<!-- Chart.js CDN for Award-Winning Interactive Visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Global Chart Defaults
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Donut Chart: Distribusi Skala Usaha (Mikro, Kecil, Menengah)
        const ctxSkala = document.getElementById('skalaDonutChart');
        if (ctxSkala) {
            new Chart(ctxSkala, {
                type: 'doughnut',
                data: {
                    labels: ['Mikro', 'Kecil', 'Menengah'],
                    datasets: [{
                        data: [{{ $skalaMikro }}, {{ $skalaKecil }}, {{ $skalaMenengah }}],
                        backgroundColor: ['#0284c7', '#f59e0b', '#10b981'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // 2. Donut Chart: Status Perizinan (Draft, Diajukan, Terverifikasi, Ditolak)
        const ctxPerizinan = document.getElementById('perizinanDonutChart');
        if (ctxPerizinan) {
            new Chart(ctxPerizinan, {
                type: 'doughnut',
                data: {
                    labels: ['Draft / Lapangan', 'Diajukan', 'Terverifikasi', 'Ditolak'],
                    datasets: [{
                        data: [
                            {{ $statusPerizinan['draft'] }}, 
                            {{ $statusPerizinan['diajukan'] }}, 
                            {{ $statusPerizinan['terverifikasi'] }}, 
                            {{ $statusPerizinan['ditolak'] }}
                        ],
                        backgroundColor: ['#64748b', '#f59e0b', '#059669', '#f43f5e'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // 3. Horizontal Bar Chart: Distribusi per Kecamatan (Top 10)
        const ctxKecamatan = document.getElementById('kecamatanBarChart');
        if (ctxKecamatan) {
            const kecLabels = @json($top10Kecamatan->pluck('kecamatan'));
            const kecData = @json($top10Kecamatan->pluck('total'));
            const kecColors = [
                '#ef4444', '#f97316', '#f59e0b', '#84cc16', 
                '#10b981', '#06b6d4', '#0ea5e9', '#3b82f6', 
                '#6366f1', '#8b5cf6'
            ];

            new Chart(ctxKecamatan, {
                type: 'bar',
                data: {
                    labels: kecLabels,
                    datasets: [{
                        data: kecData,
                        backgroundColor: kecColors.slice(0, kecLabels.length),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' } }
                        }
                    }
                }
            });
        }

        // 4. Horizontal Bar Chart: Distribusi per Sektor Usaha (Top 10)
        const ctxSektor = document.getElementById('sektorBarChart');
        if (ctxSektor) {
            const sektorLabels = @json($top10Kategori->pluck('nama'));
            const sektorData = @json($top10Kategori->pluck('umkm_count'));
            const sektorColors = [
                '#ef4444', '#f97316', '#f59e0b', '#10b981', 
                '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1'
            ];

            new Chart(ctxSektor, {
                type: 'bar',
                data: {
                    labels: sektorLabels,
                    datasets: [{
                        data: sektorData,
                        backgroundColor: sektorColors.slice(0, sektorLabels.length),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
