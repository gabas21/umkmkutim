@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke berita
        </a>
    </div>

    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-72 w-full object-cover sm:h-96">
        @endif

        <div class="p-6 sm:p-8">
            <div class="mb-4 text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">
                {{ $news->created_at->translatedFormat('d M Y') }}
            </div>
            <h1 class="text-3xl font-black leading-tight text-slate-900 sm:text-4xl">{{ $news->title }}</h1>

            <div class="mt-6 prose max-w-none prose-slate prose-headings:font-black prose-a:text-emerald-600 prose-img:rounded-2xl">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>
    </article>

    @if($recentNews->isNotEmpty())
        <div class="mt-12">
            <h2 class="mb-5 text-2xl font-black text-slate-900">Berita Lainnya</h2>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach($recentNews as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 items-center justify-center bg-slate-100 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">News</div>
                        @endif
                        <div class="p-4">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">
                                {{ $item->created_at->translatedFormat('d M Y') }}
                            </div>
                            <h3 class="mt-2 text-base font-black leading-snug text-slate-900">{{ $item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
