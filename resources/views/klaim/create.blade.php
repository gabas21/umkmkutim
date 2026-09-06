@extends('layouts.app')

@section('title', 'Klaim Kepemilikan Usaha — ' . $umkm->nama_usaha)

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('umkm.show', $umkm->slug) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Profil Usaha
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Formulir Klaim Kepemilikan Usaha</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Pengajuan klaim resmi untuk: <strong class="text-amber-300">{{ $umkm->nama_usaha }}</strong></p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl space-y-8">
        <!-- Info Alur Verifikasi -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-emerald-900 space-y-2">
            <div class="flex items-center gap-2 font-bold text-sm text-emerald-800">
                <i class="fa-solid fa-shield-halved"></i> Alur Verifikasi Resmi Dinas Koperasi & UMKM Kutai Timur
            </div>
            <p class="text-xs text-emerald-700 leading-relaxed">
                Untuk mencegah penyalahgunaan data kepemilikan usaha, setiap klaim profil UMKM akan ditinjau secara manual oleh Admin Dinas. Mohon pastikan dokumen identitas (KTP) asli dan jelas terbaca.
            </p>
        </div>

        <form action="{{ route('klaim.store', $umkm->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Ringkasan Usaha yang Diklaim -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Nama Usaha:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $umkm->nama_usaha }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Kecamatan:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $umkm->kecamatan }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Kategori:</span>
                    <span class="font-bold text-slate-800">{{ $umkm->kategori?->nama }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Alamat Terdaftar:</span>
                    <span class="font-bold text-slate-800">{{ $umkm->alamat }}</span>
                </div>
            </div>

            <!-- Upload KTP (Wajib) -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-800">
                    Foto / Scan KTP Asli Pemilik Usaha <span class="text-rose-500">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-emerald-500 transition bg-slate-50">
                    <i class="fa-solid fa-id-card text-3xl text-slate-400 mb-2"></i>
                    <p class="text-xs text-slate-600 font-medium">Unggah file foto KTP (JPG, PNG, atau PDF - Maksimal 3 MB)</p>
                    <input type="file" name="dokumen_ktp" required class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                </div>
                @error('dokumen_ktp')
                    <p class="text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Bukti Usaha (Opsional: NIB / Plang / Surat Keterangan) -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-800">
                    Bukti Pendukung Usaha <span class="text-slate-400 text-xs font-normal">(Opsional - mempercepat verifikasi)</span>
                </label>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-emerald-500 transition bg-slate-50">
                    <i class="fa-solid fa-file-invoice text-3xl text-slate-400 mb-2"></i>
                    <p class="text-xs text-slate-600 font-medium">Foto plang toko, NIB, SKU dari desa, atau foto kegiatan produksi</p>
                    <input type="file" name="dokumen_bukti_usaha" class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800 cursor-pointer">
                </div>
                @error('dokumen_bukti_usaha')
                    <p class="text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan Pemohon -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-800">Catatan Tambahan untuk Verifikator Admin</label>
                <textarea name="catatan_pemohon" rows="3" placeholder="Contoh: Saya adalah pemilik sah sejak 2021. Nomor telepon saya yang aktif saat ini adalah 0812xxxx." class="w-full p-3.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                <a href="{{ route('umkm.show', $umkm->slug) }}" class="text-xs text-slate-500 hover:text-slate-700 font-semibold">
                    Batal dan Kembali
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Berkas Klaim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
