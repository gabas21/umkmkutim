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
                        <form action="{{ route('pelatihan.daftar', $pelatihan->slug) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Peserta *</label>
                                <input type="text" name="nama_peserta" required 
                                       value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->nama : old('nama_peserta') }}"
                                       placeholder="Nama lengkap sesuai KTP" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Usaha / Usaha yang Dirintis</label>
                                <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}"
                                       placeholder="Kosongkan jika baru akan merintis usaha" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Aktif *</label>
                                    <input type="email" name="email" required
                                           value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->email : old('email') }}"
                                           placeholder="nama@email.com" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp *</label>
                                    <input type="text" name="nomor_hp" required 
                                           value="{{ Auth::guard('pelaku_usaha')->check() ? Auth::guard('pelaku_usaha')->user()->nomor_telepon : old('nomor_hp') }}"
                                           placeholder="081234567890" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Asal Lembaga / Komunitas (Opsional)</label>
                                <input type="text" name="instansi" value="{{ old('instansi') }}" placeholder="Contoh: Asosiasi Kuliner Sangatta / Mandiri" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tujuan / Motivasi Mengikuti Pelatihan</label>
                                <textarea name="motivasi" rows="2" placeholder="Apa yang ingin dicapai melalui pelatihan ini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('motivasi') }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm active:scale-[0.98]">
                                Kirim Pendaftaran Pelatihan
                            </button>
                            <p class="text-[11px] text-slate-400 text-center">
                                * Gratis tanpa dipungut biaya. Biaya ditanggung APBD Pemkab Kutim.
                            </p>
                        </form>
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
