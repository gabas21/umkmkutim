@extends('layouts.app')

@section('title', 'Pemeriksaan Berkas Klaim — Panel Admin')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Antrean Klaim
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Pemeriksaan Berkas Klaim Usaha</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Verifikasi kecocokan identitas pemohon dengan data UMKM yang terdaftar</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Ringkasan Usaha & Pemohon -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-store text-emerald-600"></i> Data UMKM Terdaftar
            </h3>
            <div class="space-y-1.5 text-xs">
                <div><span class="text-slate-400">Nama Usaha:</span> <strong class="text-slate-900 text-sm block">{{ $klaim->umkm?->nama_usaha }}</strong></div>
                <div><span class="text-slate-400">Kecamatan:</span> <span class="font-bold text-slate-800">{{ $klaim->umkm?->kecamatan }}</span></div>
                <div><span class="text-slate-400">Alamat:</span> <span class="text-slate-700">{{ $klaim->umkm?->alamat }}</span></div>
                <div><span class="text-slate-400">Status Saat Ini:</span> <span class="font-semibold text-amber-700">{{ $klaim->umkm?->status_klaim }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-user text-blue-600"></i> Data Akun Pemohon
            </h3>
            <div class="space-y-1.5 text-xs">
                <div><span class="text-slate-400">Nama Lengkap:</span> <strong class="text-slate-900 text-sm block">{{ $klaim->pelakuUsaha?->nama }}</strong></div>
                <div><span class="text-slate-400">Email:</span> <span class="font-bold text-slate-800">{{ $klaim->pelakuUsaha?->email }}</span></div>
                <div><span class="text-slate-400">Telepon/WA:</span> <span class="text-slate-700">{{ $klaim->pelakuUsaha?->nomor_telepon ?: '-' }}</span></div>
                <div><span class="text-slate-400">Diajukan Pada:</span> <span class="text-slate-700">{{ $klaim->created_at->format('d F Y, H:i') }}</span></div>
            </div>
        </div>
    </div>

    <!-- Catatan Pemohon -->
    @if($klaim->catatan_pemohon)
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-2">
            <h3 class="text-sm font-bold text-slate-900">Catatan dari Pemohon:</h3>
            <p class="text-xs text-slate-600 italic bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed">
                "{{ $klaim->catatan_pemohon }}"
            </p>
        </div>
    @endif

    <!-- Lampiran Dokumen KTP & Bukti Usaha -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
            Dokumen Lampiran Pembuktian
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Dokumen KTP -->
            <div class="space-y-2">
                <span class="block text-xs font-bold text-slate-700">Foto / File KTP Pemilik:</span>
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 p-4 text-center">
                    <i class="fa-solid fa-file-invoice text-4xl text-slate-400 mb-2"></i>
                    <p class="text-xs text-slate-500 mb-3 truncate">{{ basename($klaim->dokumen_ktp) }}</p>
                    <a href="{{ asset('storage/' . $klaim->dokumen_ktp) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka File KTP
                    </a>
                </div>
            </div>

            <!-- Dokumen Bukti Usaha -->
            <div class="space-y-2">
                <span class="block text-xs font-bold text-slate-700">Bukti Pendukung Usaha (NIB / Foto Plang):</span>
                @if($klaim->dokumen_bukti_usaha)
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 p-4 text-center">
                        <i class="fa-solid fa-file-shield text-4xl text-slate-400 mb-2"></i>
                        <p class="text-xs text-slate-500 mb-3 truncate">{{ basename($klaim->dokumen_bukti_usaha) }}</p>
                        <a href="{{ asset('storage/' . $klaim->dokumen_bukti_usaha) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Bukti Usaha
                        </a>
                    </div>
                @else
                    <div class="border border-dashed border-slate-200 rounded-xl p-8 text-center text-xs text-slate-400">
                        Tidak ada berkas bukti tambahan yang diunggah.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Keputusan Verifikasi Admin -->
    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 space-y-6">
        <h3 class="text-base font-bold text-slate-900">Keputusan Tim Verifikator Dinas</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Setujui -->
            <form action="{{ route('admin.klaim.approve', $klaim->id) }}" method="POST" class="bg-white p-5 rounded-xl border border-emerald-300 space-y-4">
                @csrf
                <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i> Setujui Klaim Ini
                </div>
                <p class="text-xs text-slate-500">Klaim akan diverifikasi dan hak akses pengelolaan profil diberikan kepada akun pemohon.</p>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">Catatan Persetujuan (Opsional):</label>
                    <input type="text" name="catatan_admin" placeholder="Dokumen KTP sah dan valid." class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none">
                </div>
                <button type="submit" onclick="return confirm('Apakah Anda yakin dokumen pemohon sudah valid dan menyetujui klaim ini?')" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow">
                    Setujui & Verifikasi Usaha
                </button>
            </form>

            <!-- Form Tolak -->
            <form action="{{ route('admin.klaim.reject', $klaim->id) }}" method="POST" class="bg-white p-5 rounded-xl border border-rose-300 space-y-4">
                @csrf
                <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                    <i class="fa-solid fa-circle-xmark text-rose-600"></i> Tolak Klaim
                </div>
                <p class="text-xs text-slate-500">Berikan alasan penolakan yang jelas agar pemohon dapat melengkapi berkasnya kembali.</p>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">Alasan Penolakan <span class="text-rose-500">*</span>:</label>
                    <input type="text" name="catatan_admin" required placeholder="Foto KTP buram / Nama tidak sesuai." class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none">
                </div>
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak permohonan klaim ini?')" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg transition shadow">
                    Tolak Permohonan Klaim
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
