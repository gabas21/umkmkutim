@extends('layouts.app')

@section('title', $pelatihan->judul . ' — Pelatihan UMKM Kutai Timur')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-12 border-b border-emerald-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex items-center gap-2 text-xs text-emerald-300 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <a href="{{ route('pelatihan.index') }}" class="hover:underline">Pelatihan & Bimtek</a>
            <span>/</span>
            <span class="text-white font-semibold truncate">{{ $pelatihan->judul }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $pelatihan->mode === 'online' ? 'bg-blue-100 text-blue-900' : ($pelatihan->mode === 'hybrid' ? 'bg-purple-100 text-purple-900' : 'bg-emerald-100 text-emerald-900') }}">
                Mode {{ ucfirst($pelatihan->mode) }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950">
                100% GRATIS
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight max-w-4xl">
            {{ $pelatihan->judul }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-2 flex items-center gap-2">
            <i class="fa-solid fa-building-columns text-emerald-400"></i>
            <span>Penyelenggara: {{ $pelatihan->penyelenggara }}</span>
        </p>
    </div>
</section>

<!-- Content Grid -->
<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Curriculum & Details (Span 7) -->
            <div class="lg:col-span-7 space-y-6" data-reveal="left">
                <!-- Deskripsi & Info -->
                <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-emerald-600"></i>
                            <span>Deskripsi Program</span>
                        </h2>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $pelatihan->deskripsi }}
                        </p>
                    </div>

                    <!-- Materi / Silabus Ringkas -->
                    @if($pelatihan->materi_ringkas)
                        <div class="pt-5 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-emerald-600"></i>
                                <span>Pokok Materi Pembahasan</span>
                            </h3>
                            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-xs text-emerald-950 whitespace-pre-line font-medium leading-relaxed">
                                {{ $pelatihan->materi_ringkas }}
                            </div>
                        </div>
                    @endif

                    <!-- Jadwal & Lokasi -->
                    <div class="pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jadwal Acara</span>
                            <p class="text-sm font-bold text-slate-900">
                                {{ $pelatihan->tanggal_mulai->format('d M Y') }}
                            </p>
                            <span class="text-xs text-slate-500">
                                {{ $pelatihan->tanggal_mulai->format('H:i') }} – {{ $pelatihan->tanggal_selesai->format('H:i') }} WITA
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tempat / Media</span>
                            <p class="text-sm font-bold text-slate-900">{{ $pelatihan->lokasi }}</p>
                            <span class="text-xs text-slate-500">
                                @if($pelatihan->mode !== 'offline' && $pelatihan->link_zoom)
                                    Link Zoom akan dibagikan via grup WhatsApp peserta
                                @else
                                    Kawasan Bukit Pelangi Sangatta
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Instruktur & Syarat -->
                    @if($pelatihan->instruktur || $pelatihan->syarat_peserta)
                        <div class="pt-5 border-t border-slate-100 space-y-3">
                            @if($pelatihan->instruktur)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Fasilitator / Pemateri:</h4>
                                        <p class="text-xs text-slate-600">{{ $pelatihan->instruktur }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($pelatihan->syarat_peserta)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-id-card-clip"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Persyaratan Peserta:</h4>
                                        <p class="text-xs text-slate-600 leading-relaxed">{{ $pelatihan->syarat_peserta }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Form Registrasi (Span 5) -->
            <div class="lg:col-span-5" id="daftar" data-reveal="right">
                <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-lg sticky top-28 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                            Pendaftaran Terbuka
                        </span>
                        <h2 class="text-xl font-black text-slate-900 mt-1">Formulir Peserta Pelatihan</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Sisa kuota: <strong class="text-emerald-700">{{ $pelatihan->sisa_kuota }} orang</strong> dari total {{ $pelatihan->kuota }} peserta
                        </p>
                    </div>

                    @if($pelatihan->sisa_kuota > 0 && in_array($pelatihan->status, ['upcoming', 'ongoing']))
                        @if(Auth::guard('pelaku_usaha')->check())
                            @php
                                $user = Auth::guard('pelaku_usaha')->user();
                                $isRegistered = \App\Models\PelatihanPeserta::where('pelatihan_id', $pelatihan->id)
                                    ->where('pelaku_usaha_id', $user->id)
                                    ->first();
                            @endphp

                            @if($isRegistered)
                                <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-xl">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-emerald-900">Anda Sudah Terdaftar</h3>
                                    <p class="text-xs text-emerald-700 leading-relaxed">
                                        Pendaftaran Anda telah tercatat pada program pelatihan ini. Jadwal pelaksanaan dan link kelas/grup akan dikirim melalui WhatsApp & Email.
                                    </p>
                                    <div class="pt-2 text-[11px] font-semibold text-emerald-800">
                                        Status: <span class="uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-200/80">{{ $isRegistered->status }}</span>
                                    </div>
                                </div>
                            @else
                                <!-- Data Pelaku Usaha Terhubung -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Nama Peserta:</span>
                                        <span class="font-bold text-slate-800">{{ $user->nama }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Nomor WhatsApp:</span>
                                        <span class="font-bold text-slate-800">{{ $user->nomor_telepon ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Email:</span>
                                        <span class="font-bold text-slate-800">{{ $user->email }}</span>
                                    </div>
                                </div>

                                <form action="{{ route('pelatihan.daftar', $pelatihan->slug) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm transition active:scale-[0.98] flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                        <span>Daftar Peserta Pelatihan Sekarang</span>
                                    </button>
                                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                                        * Gratis tanpa dipungut biaya. Biaya ditanggung APBD Pemkab Kutim.
                                    </p>
                                </form>
                            @endif
                        @else
                            <!-- Belum Memiliki Akun / Belum Login -->
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 leading-relaxed">
                                    <div class="flex items-center gap-2 font-bold mb-1 text-amber-800">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span>Petunjuk Pendaftaran Pelatihan</span>
                                    </div>
                                    Pendaftaran pelatihan memerlukan akun Pelaku Usaha UMKM Kutai Timur agar riwayat sertifikasi dan keikutsertaan bimtek tercatat resmi.
                                </div>

                                <!-- Tombol Daftar Akun Terlebih Dahulu -->
                                <a href="{{ route('register') }}" 
                                   class="w-full py-3.5 px-4 rounded-xl font-bold text-xs text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/20 transition active:scale-[0.98] flex items-center justify-center gap-2 text-center">
                                    <i class="fa-solid fa-user-plus"></i>
                                    <span>Belum Punya Akun? Daftar Terlebih Dahulu</span>
                                </a>

                                <!-- Tombol Sudah Punya Akun / Login -->
                                <a href="{{ route('login') }}" 
                                   class="w-full py-3 px-4 rounded-xl font-bold text-xs text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition active:scale-[0.98] flex items-center justify-center gap-2 text-center">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                    <span>Sudah Memiliki Akun? Masuk / Login</span>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-6 text-center rounded-2xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-users-slash text-3xl text-slate-400 mb-2"></i>
                            <h3 class="text-sm font-bold text-slate-800">Kuota Pendaftaran Penuh</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Kuota telah tercapai. Pantau terus halaman pelatihan untuk jadwal gelombang berikutnya.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekomendasi Pelatihan Lainnya -->
        @if($otherPelatihans->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-slate-200" data-reveal>
                <h3 class="text-xl font-extrabold text-slate-900 mb-6">Program Pelatihan Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($otherPelatihans as $op)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition">
                            <span class="text-[11px] font-bold text-emerald-700 uppercase">Mode {{ ucfirst($op->mode) }}</span>
                            <h4 class="text-sm font-bold text-slate-900 mt-1 hover:text-emerald-700">
                                <a href="{{ route('pelatihan.show', $op->slug) }}">{{ $op->judul }}</a>
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-emerald-600"></i> {{ $op->tanggal_mulai->format('d M Y') }}
                            </p>
                            <a href="{{ route('pelatihan.show', $op->slug) }}" class="mt-4 inline-block text-xs font-bold text-emerald-700 hover:text-emerald-800">
                                Detail Pelatihan &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
