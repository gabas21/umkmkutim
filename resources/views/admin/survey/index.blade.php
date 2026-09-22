@extends('layouts.admin')

@section('title', 'Survey Kepuasan Layanan — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-900">Survey Kepuasan Masyarakat</h1>
        <p class="text-xs text-slate-500 mt-1">Rekap Indeks Kepuasan Masyarakat (IKM) dan masukan warga/pelaku usaha.</p>
    </div>

    <!-- Ringkasan IKM -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] text-slate-500 font-medium">Total Responden</div>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalSurvey) }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] text-slate-500 font-medium">Kemudahan</div>
            <div class="text-2xl font-black text-emerald-700 mt-1">{{ $avgKemudahan }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] text-slate-500 font-medium">Kecepatan</div>
            <div class="text-2xl font-black text-emerald-700 mt-1">{{ $avgKecepatan }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] text-slate-500 font-medium">Keramahan</div>
            <div class="text-2xl font-black text-emerald-700 mt-1">{{ $avgKeramahan }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-emerald-300 shadow-sm bg-gradient-to-br from-white to-emerald-50/50">
            <div class="text-[11px] text-emerald-700 font-bold">Indeks Rata-rata</div>
            <div class="text-2xl font-black text-emerald-900 mt-1">{{ $indeksRataRata }} / 5</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.survey.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <select name="modul" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none sm:col-span-2">
                <option value="">Semua Modul</option>
                <option value="umkm" {{ request('modul') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                <option value="bazar" {{ request('modul') == 'bazar' ? 'selected' : '' }}>Bazar</option>
                <option value="pelatihan" {{ request('modul') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                <option value="layanan_umum" {{ request('modul') == 'layanan_umum' ? 'selected' : '' }}>Layanan Umum</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">Filter</button>
                @if(request()->hasAny(['modul']))
                    <a href="{{ route('admin.survey.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Responden</th>
                        <th class="p-4">Modul</th>
                        <th class="p-4">Nilai (K/C/R/M)</th>
                        <th class="p-4">Rata-rata</th>
                        <th class="p-4">Saran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surveyList as $survey)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono text-slate-500">{{ $survey->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $survey->nama_responden }}</span>
                                <span class="text-[11px] text-slate-400">{{ $survey->pekerjaan }}</span>
                            </td>
                            <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 capitalize">{{ str_replace('_', ' ', $survey->modul) }}</span></td>
                            <td class="p-4 font-mono">{{ $survey->nilai_kemudahan }}/{{ $survey->nilai_kecepatan }}/{{ $survey->nilai_keramahan }}/{{ $survey->nilai_kemanfaatan }}</td>
                            <td class="p-4 font-bold text-emerald-700">{{ $survey->rata_rata }}</td>
                            <td class="p-4 max-w-xs truncate">{{ $survey->saran_teks ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada data survey.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $surveyList->links() }}
        </div>
    </div>
</div>
@endsection
