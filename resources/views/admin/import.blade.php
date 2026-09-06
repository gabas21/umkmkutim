@extends('layouts.app')

@section('title', 'Import Data UMKM Dinas — Panel Admin')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Admin
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Import Data Massal Dinas</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Unggah berkas CSV pendataan UMKM dinas secara batch berkekuatan tinggi (hingga 45.000 data)</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Status Data Saat Ini -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">Total UMKM dalam Database</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalUmkm) }}</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">Data Berasal dari Impor Dinas</span>
            <div class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($importUmkmCount) }}</div>
        </div>
    </div>

    <!-- Form Upload CSV -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-emerald-600 text-xl"></i> Unggah Berkas CSV Baru
            </h3>
            <a href="{{ asset('storage/sample_umkm_kutim.csv') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <i class="fa-solid fa-download"></i> Unduh Format Contoh CSV
            </a>
        </div>

        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 text-xs text-slate-600 space-y-2">
            <span class="font-bold text-slate-800 block"><i class="fa-solid fa-circle-check text-emerald-600"></i> Ketentuan Header Kolom CSV:</span>
            <code class="block bg-slate-900 text-emerald-400 p-2.5 rounded-lg text-[11px] overflow-x-auto font-mono">
                nama_usaha,kategori,deskripsi,alamat,kecamatan,kelurahan_desa,latitude,longitude,telepon,email,rating,jumlah_review
            </code>
            <ul class="list-disc list-inside space-y-1 text-slate-500 pt-1 text-[11px]">
                <li>File CSV harus menggunakan pemisah koma (<code>,</code>) dan encoding UTF-8.</li>
                <li>Nama kecamatan akan dicocokkan otomatis dengan 18 kecamatan resmi di Kabupaten Kutai Timur.</li>
                <li>Jika kolom koordinat latitude/longitude kosong, sistem secara otomatis menempatkan titik perkiraan di area kecamatan terkait.</li>
            </ul>
        </div>

        <form action="{{ route('admin.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 text-center hover:border-emerald-500 transition bg-slate-50">
                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-400 mb-3"></i>
                <h4 class="text-sm font-bold text-slate-800">Pilih Berkas CSV dari Komputer</h4>
                <p class="text-xs text-slate-500 mt-1">Maksimal ukuran file 10 MB per berkas</p>
                <input type="file" name="file_csv" required accept=".csv, .txt" class="mt-4 block w-full max-w-sm mx-auto text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" onclick="return confirm('Mulai proses impor data CSV ke basis data?')" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-play"></i> Mulai Proses Impor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
