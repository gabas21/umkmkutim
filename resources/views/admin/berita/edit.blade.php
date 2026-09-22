@extends('layouts.admin')

@section('title', 'Edit Berita — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Berita
        </a>
        <h1 class="text-xl font-black text-slate-900">Edit Berita</h1>
        <p class="text-xs text-slate-500 mt-1">{{ $berita->judul }}</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.berita._form')
        </form>
    </div>
</div>
@endsection
