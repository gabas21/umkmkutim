@extends('layouts.app')

@section('title', 'Berita & Update UMKM Kutim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">Media & Informasi</p>
        <h1 class="mt-2 text-3xl font-black text-slate-900">Berita & Update Terbaru</h1>
    </div>

    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form action="{{ route('news.index') }}" method="GET" class="flex flex-col gap-3 md:flex-row md:items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul berita atau isi konten" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">
                <i class="fa-solid fa-filter mr-2"></i> Cari
            </button>
            @if(request()->has('q'))
                <a href="{{ route('news.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($news as $item)
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-52 w-full object-cover">
                @else
                    <div class="flex h-52 items-center justify-center bg-slate-100 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">No Image</div>
                @endif
                <div class="p-5">
                    <div class="mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">
                        {{ $item->created_at->translatedFormat('d M Y') }}
                    </div>
                    <h2 class="text-lg font-black leading-snug text-slate-900">{{ $item->title }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        {{ Str::limit(strip_tags($item->content), 120) }}
                    </p>
                    <a href="{{ route('news.show', $item->slug) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-emerald-600 hover:text-emerald-700">
                        Baca selengkapnya <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-slate-500">
                Belum ada berita yang dipublikasikan.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $news->links() }}
    </div>
</div>
@endsection
