@extends('layouts.admin')

@section('title', 'Moderasi Ulasan — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-900">Moderasi Ulasan Publik</h1>
        <p class="text-xs text-slate-500 mt-1">Tinjau dan hapus ulasan yang tidak pantas atau spam.</p>
    </div>

    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.review.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-3.5 top-3"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama reviewer atau komentar..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select name="rating" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">Filter</button>
                @if(request()->hasAny(['q', 'rating']))
                    <a href="{{ route('admin.review.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-rotate-left"></i></a>
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
                        <th class="p-4">Usaha</th>
                        <th class="p-4">Reviewer</th>
                        <th class="p-4">Rating</th>
                        <th class="p-4">Komentar</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviewList as $review)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono text-slate-500">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 font-bold text-slate-900">{{ $review->umkm?->nama_usaha ?? '(Usaha dihapus)' }}</td>
                            <td class="p-4">{{ $review->nama_reviewer ?: 'Anonim' }}</td>
                            <td class="p-4">
                                <span class="text-amber-500 font-bold">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star text-[11px]"></i>
                                    @endfor
                                </span>
                            </td>
                            <td class="p-4 max-w-xs truncate">{{ $review->komentar ?: '-' }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada ulasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $reviewList->links() }}
        </div>
    </div>
</div>
@endsection
