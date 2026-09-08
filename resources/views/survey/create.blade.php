@extends('layouts.app')

@section('title', 'Survey Kepuasan Layanan — Diskop & UKM Kabupaten Kutai Timur')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-12 border-b border-emerald-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-reveal>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950 inline-block mb-3">
            <i class="fa-solid fa-star text-slate-900"></i> Partisipasi Masyarakat
        </span>
        <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
            Survey Kepuasan Layanan Publik
        </h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-xl mx-auto leading-relaxed">
            Bantu kami meningkatkan kualitas pelayanan Dinas Koperasi dan Usaha Kecil Menengah Kabupaten Kutai Timur dengan memberikan penilaian jujur Anda.
        </p>
    </div>
</section>

<!-- Survey Form Card -->
<section class="py-12 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl">
            <form action="{{ route('survey.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Profil Responden -->
                <div class="space-y-4 border-b border-slate-100 pb-6">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-user-check text-emerald-600"></i>
                        <span>1. Informasi Responden</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap (Opsional)</label>
                            <input type="text" name="nama_responden" 
                                   value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->nama : old('nama_responden') }}"
                                   placeholder="Dapat dikosongkan untuk anonimitas" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Profesi / Status</label>
                            <select name="pekerjaan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                                <option value="Pelaku UMKM Kutim">Pelaku UMKM Kutai Timur</option>
                                <option value="Konsumen / Pembeli">Konsumen / Pembeli Produk UMKM</option>
                                <option value="Masyarakat Umum">Masyarakat Umum</option>
                                <option value="PNS / Instansi Pemerintah">ASN / Instansi Pemerintah</option>
                                <option value="Mahasiswa / Pelajar">Mahasiswa / Pelajar</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Layanan yang Dinilai *</label>
                        <select name="modul" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                            <option value="layanan_umum">Layanan Umum Portal & Fasilitasi Dinas</option>
                            <option value="umkm">Direktori & Pendaftaran Mandiri UMKM</option>
                            <option value="bazar">Pendaftaran & Fasilitasi Bazar / Pameran</option>
                            <option value="pelatihan">Bimbingan Teknis & Pelatihan Usaha</option>
                        </select>
                    </div>
                </div>

                <!-- Section 2: Penilaian 4 Dimensi IKM -->
                <div class="space-y-6 border-b border-slate-100 pb-6">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-star text-amber-500"></i>
                        <span>2. Penilaian Mutu Layanan (Skala 1 - 5 Bintang)</span>
                    </h2>

                    <!-- Dimensi 1: Kemudahan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800">1. Kemudahan Akses & Prosedur Layanan</span>
                            <span class="text-[11px] text-slate-400">1 (Sangat Sulit) - 5 (Sangat Mudah)</span>
                        </div>
                        <div class="flex items-center gap-4 pt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                    <input type="radio" name="nilai_kemudahan" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ $i }} <i class="fa-solid fa-star text-amber-400 text-[10px]"></i></span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Dimensi 2: Kecepatan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800">2. Kecepatan Respons & Verifikasi Berkas</span>
                            <span class="text-[11px] text-slate-400">1 (Sangat Lambat) - 5 (Sangat Cepat)</span>
                        </div>
                        <div class="flex items-center gap-4 pt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                    <input type="radio" name="nilai_kecepatan" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ $i }} <i class="fa-solid fa-star text-amber-400 text-[10px]"></i></span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Dimensi 3: Keramahan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800">3. Keramahan & Kompetensi Fasilitator Dinas</span>
                            <span class="text-[11px] text-slate-400">1 (Kurang Ramah) - 5 (Sangat Ramah)</span>
                        </div>
                        <div class="flex items-center gap-4 pt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                    <input type="radio" name="nilai_keramahan" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ $i }} <i class="fa-solid fa-star text-amber-400 text-[10px]"></i></span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Dimensi 4: Kemanfaatan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800">4. Kemanfaatan Program bagi Kemajuan Usaha</span>
                            <span class="text-[11px] text-slate-400">1 (Tidak Bermanfaat) - 5 (Sangat Bermanfaat)</span>
                        </div>
                        <div class="flex items-center gap-4 pt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                    <input type="radio" name="nilai_kemanfaatan" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ $i }} <i class="fa-solid fa-star text-amber-400 text-[10px]"></i></span>
                                </label>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Section 3: Saran & Masukan -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots text-emerald-600"></i>
                        <span>3. Kritik, Saran & Rekomendasi Anda</span>
                    </h2>
                    <div>
                        <textarea name="saran_teks" rows="4" placeholder="Tuliskan masukan atau kendala yang Anda alami saat menggunakan layanan kami..." class="w-full px-4 py-3 rounded-2xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none leading-relaxed"></textarea>
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        &larr; Batal & Kembali ke Laporan
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-xs text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/20 transition active:scale-95">
                        Kirim Penilaian Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
