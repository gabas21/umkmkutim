@extends('layouts.app')

@section('title', $bazar->nama_bazar . ' — Bazar & Pameran UMKM Kutai Timur')

@section('content')
<!-- Header Section -->
<section class="relative overflow-hidden bg-[#021813] text-white py-12 sm:py-16 border-b border-emerald-950/80">
    <!-- Background Image Nyata Bazar (Custom Banner atau Fallback umkm.png) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none z-0">
        <img src="{{ $bazar->banner_url ? (filter_var($bazar->banner_url, FILTER_VALIDATE_URL) ? $bazar->banner_url : asset('storage/' . $bazar->banner_url)) : asset('umkm.png') }}" 
             alt="{{ $bazar->nama_bazar }}" 
             class="w-full h-full object-cover object-center transform scale-105 transition-transform duration-1000">
        
        <div class="absolute inset-0 bg-gradient-to-r from-[#021813] via-[#021813]/85 to-[#021813]/40 md:via-[#021813]/70 md:to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#021813]/90 via-transparent to-[#021813]/40"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 backdrop-blur-md border border-emerald-500/30 text-xs text-emerald-300 mb-3 shadow-xs">
            <a href="{{ route('home') }}" class="hover:underline flex items-center gap-1.5">
                <i class="fa-solid fa-house text-[10px]"></i>
                <span>Beranda</span>
            </a>
            <span class="text-emerald-500">/</span>
            <a href="{{ route('bazar.index') }}" class="hover:underline">Bazar & Pameran</a>
            <span class="text-emerald-500">/</span>
            <span class="text-white font-semibold truncate">{{ $bazar->nama_bazar }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bazar->status === 'upcoming' ? 'bg-amber-400 text-slate-950' : ($bazar->status === 'ongoing' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-300') }}">
                {{ $bazar->status === 'upcoming' ? 'Pendaftaran Dibuka' : ($bazar->status === 'ongoing' ? 'Sedang Berlangsung' : 'Selesai') }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/80 backdrop-blur-md text-emerald-200 border border-emerald-500/30">
                <i class="fa-solid fa-location-dot text-amber-400 mr-1"></i> Kec. {{ $bazar->kecamatan }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.6)]">
            {{ $bazar->nama_bazar }}
        </h1>
        <p class="text-xs sm:text-sm text-emerald-100/90 mt-2 flex items-center gap-2 drop-shadow-xs">
            <i class="fa-solid fa-building-columns text-amber-400"></i>
            <span>Penyelenggara: {{ $bazar->penyelenggara }}</span>
        </p>
    </div>
</section>

<!-- Content Grid -->
<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Details (Span 7) -->
            <div class="lg:col-span-7 space-y-6" data-reveal="left">
                <!-- Deskripsi & Info Event -->
                <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-emerald-600"></i>
                            <span>Tentang Acara Pameran</span>
                        </h2>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $bazar->deskripsi }}
                        </p>
                    </div>

                    <!-- Fasilitas Stand -->
                    <div class="pt-5 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-2.5 flex items-center gap-2">
                            <i class="fa-solid fa-boxes-packing text-emerald-600"></i>
                            <span>Fasilitas yang Disediakan Panitia Dinas</span>
                        </h3>
                        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-xs text-emerald-950 space-y-1.5">
                            <p class="font-medium leading-relaxed">
                                {{ $bazar->fasilitas ?: 'Tenda sarnavil standar Pemkab Kutim, meja display, kursi, pasokan listrik, dan publikasi media resmi.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Jadwal & Lokasi -->
                    <div class="pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</span>
                            <p class="text-sm font-bold text-slate-900">
                                {{ $bazar->tanggal_mulai->format('d M') }} – {{ $bazar->tanggal_selesai->format('d M Y') }}
                            </p>
                            <span class="text-xs text-slate-500">Mulai pukul 09.00 s/d 22.00 WITA</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Acara</span>
                            <p class="text-sm font-bold text-slate-900">{{ $bazar->lokasi }}</p>
                            <span class="text-xs text-slate-500">{{ $bazar->alamat_lengkap ?: 'Kutai Timur' }}</span>
                        </div>
                    </div>

                    @if($bazar->kontak_person)
                        <div class="pt-4 border-t border-slate-100 text-xs text-slate-500 flex items-center gap-2">
                            <i class="fa-solid fa-headset text-emerald-600"></i>
                            <span>Narahubung / Helpdesk: <strong>{{ $bazar->kontak_person }}</strong></span>
                        </div>
                    @endif
                </div>

                <!-- Peserta Terkonfirmasi Preview -->
                <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-store text-emerald-600"></i>
                            <span>Daftar Usaha Terkonfirmasi</span>
                        </h2>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                            {{ $pesertaTerdaftar->count() }} Terdata
                        </span>
                    </div>

                    @if($pesertaTerdaftar->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($pesertaTerdaftar as $p)
                                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/70 flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        <i class="fa-solid fa-shop"></i>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ $p->nama_usaha }}</h4>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $p->kategori_produk }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-500 text-center py-6 bg-slate-50 rounded-2xl">
                            Belum ada stan yang diverifikasi untuk event ini. Jadilah yang pertama mendaftar!
                        </p>
                    @endif
                </div>
            </div>

            <!-- Right Column: Registration Form (Span 5) -->
            <div class="lg:col-span-5" id="daftar" data-reveal="right">
                <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-lg sticky top-28 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                            Pendaftaran Lapak
                        </span>
                        <h2 class="text-xl font-black text-slate-900 mt-1">Formulir Peserta Bazar</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Sisa kuota tersedia: <strong class="text-emerald-700">{{ $bazar->sisa_kuota }} lapak</strong> dari total {{ $bazar->kuota_peserta }}
                        </p>
                    </div>

                    @if(in_array($bazar->status, ['upcoming', 'ongoing']) && $bazar->sisa_kuota > 0)
                        @if(Auth::guard('pelaku_usaha')->check())
                            @php
                                $user = Auth::guard('pelaku_usaha')->user();
                                $isRegistered = \App\Models\BazarPeserta::where('bazar_id', $bazar->id)
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
                                        Pengajuan stan lapak Anda sedang dalam proses verifikasi oleh panitia Dinas Koperasi & UKM. Tim kami akan menghubungi via WhatsApp.
                                    </p>
                                    <div class="pt-2 text-[11px] font-semibold text-emerald-800">
                                        Status: <span class="uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-200/80">{{ $isRegistered->status }}</span>
                                    </div>
                                </div>
                            @else
                                <!-- Data Pelaku Usaha Terhubung -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Akun Terhubung:</span>
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

                                <form action="{{ route('bazar.daftar', $bazar->slug) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 rounded-xl font-black text-xs text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/20 transition active:scale-[0.98] flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-tent"></i>
                                        <span>Daftar Peserta Bazar Sekarang</span>
                                    </button>
                                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                                        * Klik tombol di atas untuk mendaftar stan lapak secara langsung menggunakan profil usaha Anda.
                                    </p>
                                </form>
                            @endif
                        @else
                            <!-- Belum Memiliki Akun / Belum Login -->
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 leading-relaxed">
                                    <div class="flex items-center gap-2 font-bold mb-1 text-amber-800">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span>Petunjuk Pendaftaran Stan</span>
                                    </div>
                                    Pendaftaran peserta bazar memerlukan akun Pelaku Usaha UMKM Kutai Timur agar verifikasi stan dan identitas usaha tercatat resmi di database dinas.
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
                            <i class="fa-solid fa-lock text-3xl text-slate-400 mb-2"></i>
                            <h3 class="text-sm font-bold text-slate-800">Pendaftaran Stan Ditutup</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Kuota pendaftaran telah terpenuhi atau batas waktu registrasi telah berakhir.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekomendasi Event Lainnya -->
        @if($otherBazars->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-slate-200" data-reveal>
                <h3 class="text-xl font-extrabold text-slate-900 mb-6">Agenda Bazar Lainnya di Kutai Timur</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($otherBazars as $ob)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition">
                            <span class="text-[11px] font-bold text-amber-600 uppercase">{{ $ob->kecamatan }}</span>
                            <h4 class="text-sm font-bold text-slate-900 mt-1 hover:text-emerald-700">
                                <a href="{{ route('bazar.show', $ob->slug) }}">{{ $ob->nama_bazar }}</a>
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-emerald-600"></i> {{ $ob->tanggal_mulai->format('d M Y') }}
                            </p>
                            <a href="{{ route('bazar.show', $ob->slug) }}" class="mt-4 inline-block text-xs font-bold text-emerald-700 hover:text-emerald-800">
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
