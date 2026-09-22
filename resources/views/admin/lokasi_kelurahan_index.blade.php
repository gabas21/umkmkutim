@extends('layouts.admin')

@section('title', 'Kelola Kelurahan')

@section('admin-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black">Kelola Kelurahan</h1>
            <p class="text-sm text-slate-500">Tambah atau ubah kelurahan dan hubungkan ke kecamatan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Kecamatan</th>
                        <th class="px-4 py-3 font-semibold">Kode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kelurahans as $kel)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $kel->name }}</td>
                            <td class="px-4 py-3">{{ optional($kel->kecamatan)->name }}</td>
                            <td class="px-4 py-3">{{ $kel->code }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-slate-500">Belum ada data kelurahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $kelurahans->links() }}
    </div>
</div>
@endsection
