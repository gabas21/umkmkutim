@extends('layouts.app')

@section('title', $bazar->nama_bazar . ' — Bazar & Pameran UMKM Kutai Timur')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-b from-[#021f18] to-slate-900 text-white py-12 border-b border-emerald-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex items-center gap-2 text-xs text-emerald-300 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <a href="{{ route('bazar.index') }}" class="hover:underline">Bazar & Pameran</a>
            <span>/</span>
            <span class="text-white font-semibold truncate">{{ $bazar->nama_bazar }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bazar->status === 'upcoming' ? 'bg-amber-400 text-slate-950' : ($bazar->status === 'ongoing' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-300') }}">
                {{ $bazar->status === 'upcoming' ? 'Pendaftaran Dibuka' : ($bazar->status === 'ongoing' ? 'Sedang Berlangsung' : 'Selesai') }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-sm text-emerald-200 border border-white/15">
                <i class="fa-solid fa-location-dot text-amber-400 mr-1"></i> Kec. {{ $bazar->kecamatan }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight">
            {{ $bazar->nama_bazar }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-2 flex items-center gap-2">
            <i class="fa-solid fa-building-columns text-emerald-400"></i>
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
                        <form action="{{ route('bazar.daftar', $bazar->slug) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemilik Usaha *</label>
                                <input type="text" name="nama_pemilik" required 
                                       value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->nama : old('nama_pemilik') }}"
                                       placeholder="Nama lengkap sesuai KTP" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Usaha / Merek Dagang *</label>
                                <input type="text" name="nama_usaha" required 
                                       value="{{ old('nama_usaha') }}"
                                       placeholder="Contoh: Amplang Barokah Sangatta" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Produk yang Dijual *</label>
                                <select name="kategori_produk" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none bg-white">
                                    <option value="">Pilih Kategori Produk</option>
                                    <option value="Kuliner & Makanan Siap Saji">Kuliner & Makanan Siap Saji</option>
                                    <option value="Oleh-oleh & Makanan Olahan">Oleh-oleh & Makanan Olahan (Amplang, Madu, dll)</option>
                                    <option value="Minuman Tradisional & Kopi">Minuman Tradisional & Kopi</option>
                                    <option value="Kriya & Kerajinan Tangan">Kriya & Kerajinan Tangan (Batik, Anyaman, Manik)</option>
                                    <option value="Fashion & Aksesoris">Fashion & Aksesoris</option>
                                    <option value="Agribisnis & Pertanian">Agribisnis & Pertanian</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp *</label>
                                    <input type="text" name="nomor_hp" required 
                                           value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->nomor_telepon : old('nomor_hp') }}"
                                           placeholder="081234567890" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                                    <input type="email" name="email" 
                                           value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->email : old('email') }}"
                                           placeholder="opsional@domain.com" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Ringkas Produk</label>
                                <textarea name="deskripsi_produk" rows="2" placeholder="Uraikan menu/barang yang akan dipajang..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('deskripsi_produk') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Kebutuhan (Listrik, dll)</label>
                                <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Contoh: Butuh colokan listrik 450W untuk blender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-md shadow-amber-950/20 transition active:scale-[0.98]">
                                Kirim Pengajuan Stan Bazar
                            </button>
                            <p class="text-[11px] text-slate-400 text-center">
                                * Panitia Diskop UKM Kutim akan menyeleksi dan menghubungi via WhatsApp.
                            </p>
                        </form>
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
