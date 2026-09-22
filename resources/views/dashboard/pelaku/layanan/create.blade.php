@extends('layouts.pelaku')

@section('title', 'Tambah Layanan — ' . $umkm->nama_usaha)

@section('pelaku-content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div>
        <a href="{{ route('dashboard.pelaku.layanan.index', $umkm->id) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Layanan
        </a>
        <h1 class="text-xl font-black text-slate-900">Tambah Layanan/Produk Baru</h1>
        <p class="text-xs text-slate-500 mt-1">{{ $umkm->nama_usaha }}</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('dashboard.pelaku.layanan.store', $umkm->id) }}" method="POST" class="space-y-6">
            @csrf
            @include('dashboard.pelaku.layanan._form')
        </form>
    </div>
</div>
@endsection
