@extends('layouts.admin')

@section('title', isset($kecamatan) ? 'Edit Kecamatan' : 'Tambah Kecamatan')

@section('admin-content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-black mb-4">{{ isset($kecamatan) ? 'Edit Kecamatan' : 'Tambah Kecamatan' }}</h1>

    <form action="{{ isset($kecamatan) ? route('admin.lokasi.kecamatan.update', $kecamatan->id) : route('admin.lokasi.kecamatan.store') }}" method="POST">
        @csrf
        @if(isset($kecamatan))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $kecamatan->name ?? '') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Kode (opsional)</label>
            <input type="text" name="code" value="{{ old('code', $kecamatan->code ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">GeoJSON (opsional)</label>
            <textarea name="geojson" rows="6" class="mt-1 block w-full border rounded px-3 py-2">{{ old('geojson', $kecamatan->geojson ?? '') }}</textarea>
            <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika akan di-upload sebagai file peta mentah.</p>
        </div>

        <div class="flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.lokasi.kecamatan.index') }}" class="btn">Batal</a>
        </div>
    </form>
</div>
@endsection
