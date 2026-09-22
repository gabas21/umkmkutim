@extends('layouts.admin')

@section('title', 'Aktivitas Sistem — Panel Admin')

@section('admin-content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-black">Aktivitas Sistem</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Riwayat penggunaan dan tindakan penting yang dilakukan di panel admin</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            <div class="md:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas, modul, atau user..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="relative">
                <select name="module" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none appearance-none">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ $module }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down text-slate-400 absolute right-3 top-3 text-[10px] pointer-events-none"></i>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'module']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 uppercase text-[10px] font-bold text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Pengguna</th>
                        <th class="px-4 py-3">Modul</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 align-top">
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600">{{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $log->user?->name ?? 'System' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-[10px] font-bold text-blue-700">{{ $log->module }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-bold text-emerald-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-4 py-3 max-w-xl text-slate-600">{{ $log->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
