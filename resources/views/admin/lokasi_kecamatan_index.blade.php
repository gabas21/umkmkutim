@extends('layouts.admin')

@section('title', 'Kelola Kecamatan')

@section('admin-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black">Kelola Kecamatan</h1>
            <p class="text-sm text-slate-500">Unggah peta GeoJSON atau masukkan data kecamatan secara manual.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Kode</th>
                        <th class="px-4 py-3 font-semibold">Jumlah Kelurahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kecamatans as $kec)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $kec->name }}</td>
                            <td class="px-4 py-3">{{ $kec->code }}</td>
                            <td class="px-4 py-3">{{ $kec->kelurahans_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-slate-500">Belum ada data kecamatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $kecamatans->links() }}
    </div>
</div>
@endsection
