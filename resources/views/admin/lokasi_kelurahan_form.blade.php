@extends('layouts.admin')

@section('title', isset($kelurahan) ? 'Edit Kelurahan' : 'Tambah Kelurahan')

@section('admin-content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-black mb-4">{{ isset($kelurahan) ? 'Edit Kelurahan' : 'Tambah Kelurahan' }}</h1>

    <form action="{{ isset($kelurahan) ? route('admin.lokasi.kelurahan.update', $kelurahan->id) : route('admin.lokasi.kelurahan.store') }}" method="POST">
        @csrf
        @if(isset($kelurahan))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Kecamatan</label>
            <select name="kecamatan_id" required class="mt-1 block w-full border rounded px-3 py-2">
                <option value="">-- Pilih Kecamatan --</option>
                @foreach($kecamatans as $kec)
                    <option value="{{ $kec->id }}" {{ (old('kecamatan_id', $kelurahan->kecamatan_id ?? '') == $kec->id) ? 'selected' : '' }}>{{ $kec->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $kelurahan->name ?? '') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Kode (opsional)</label>
            <input type="text" name="code" value="{{ old('code', $kelurahan->code ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">GeoJSON (opsional)</label>
            <textarea name="geojson" rows="6" class="mt-1 block w-full border rounded px-3 py-2">{{ old('geojson', $kelurahan->geojson ?? '') }}</textarea>
            <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika akan di-upload sebagai file peta mentah.</p>
        </div>

        <div class="flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.lokasi.kelurahan.index') }}" class="btn">Batal</a>
        </div>
    </form>
</div>
@endsection
