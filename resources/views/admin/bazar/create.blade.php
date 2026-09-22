@extends('layouts.admin')

@section('title', 'Buat Event Bazar Baru — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8 space-y-6">
    <div>
        <a href="{{ route('admin.bazar.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Bazar
        </a>
        <h1 class="text-xl font-black text-slate-900">Buat Event Bazar Baru</h1>
        <p class="text-xs text-slate-500 mt-1">Atur jadwal, kuota lapak, dan informasi event untuk calon peserta.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.bazar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('admin.bazar._form')
        </form>
    </div>
</div>
@endsection
