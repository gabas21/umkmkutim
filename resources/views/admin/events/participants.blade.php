@extends('layouts.admin')

@section('title', 'Peserta Event — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">Kelola Event</p>
            <h1 class="mt-1 text-xl font-black text-slate-900">Peserta: {{ $event->title }}</h1>
        </div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke event
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-[10px] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4">UMKM</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal Daftar</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($participants as $participant)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $participant->umkm?->nama_usaha ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400">ID UMKM: {{ $participant->umkm_id }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                    @if($participant->status === 'registered') bg-emerald-100 text-emerald-800
                                    @elseif($participant->status === 'attended') bg-sky-100 text-sky-800
                                    @else bg-rose-100 text-rose-800 @endif">
                                    {{ ucfirst($participant->status) }}
                                </span>
                            </td>
                            <td class="p-4 font-mono text-[11px]">{{ $participant->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.events.participants.status', [$event->id, $participant->id]) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="w-36 px-2 py-1.5 text-[11px] bg-slate-50 border border-slate-200 rounded-lg focus:outline-none">
                                        <option value="registered" {{ $participant->status === 'registered' ? 'selected' : '' }}>Registered</option>
                                        <option value="attended" {{ $participant->status === 'attended' ? 'selected' : '' }}>Attended</option>
                                        <option value="cancelled" {{ $participant->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-bold rounded-lg hover:bg-emerald-700">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Belum ada peserta yang mendaftar pada event ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
