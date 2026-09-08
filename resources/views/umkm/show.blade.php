@extends('layouts.app')

@section('title', $umkm->nama_usaha . ' — Direktori UMKM Kutai Timur')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
        <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-4">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Direktori Usaha
        </a>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-800 text-emerald-200 border border-emerald-700">
                        {{ $umkm->kategori?->nama ?? 'Komoditas' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                        <i class="fa-solid fa-location-dot text-emerald-400"></i> Kec. {{ $umkm->kecamatan }}
                    </span>
                    @if($umkm->status_klaim === 'terverifikasi')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-circle-check"></i> Terverifikasi Resmi
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/90 text-white flex items-center gap-1">
                            <i class="fa-solid fa-database"></i> Data Impor Dinas
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white">{{ $umkm->nama_usaha }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 flex items-center gap-2">
                    <span><i class="fa-solid fa-map-pin text-emerald-400"></i> {{ $umkm->alamat }}</span>
                    <span>&bull;</span>
                    <span class="text-amber-300 font-bold"><i class="fa-solid fa-star"></i> {{ number_format($umkm->rating, 1) }} ({{ $umkm->jumlah_review }} ulasan)</span>
                </p>
            </div>

            <!-- Right Action: Claim / Status -->
            <div class="flex items-center gap-3">
                @if($umkm->status_klaim === 'terverifikasi')
                    <div class="px-4 py-2.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-shield-check text-emerald-400 text-sm"></i>
                        <span>Usaha Resmi Terverifikasi</span>
                    </div>
                @elseif($currentUserClaim && $currentUserClaim->status === 'menunggu')
                    <div class="px-4 py-2.5 rounded-xl bg-amber-950/80 border border-amber-500/40 text-amber-300 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-clock text-amber-400 text-sm"></i>
                        <span>Klaim Anda Sedang Ditinjau Admin</span>
                    </div>
                @else
                    <a href="{{ route('klaim.create', $umkm->slug) }}" class="px-5 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-lg shadow-amber-400/20">
                        <i class="fa-solid fa-certificate"></i> Klaim Usaha Ini
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" data-reveal>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info (Left 2 Cols) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Alert Callout jika belum diklaim -->
            @if($umkm->status_klaim !== 'terverifikasi')
                <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border-l-4 border-amber-500 p-5 rounded-r-2xl border border-amber-200/50">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-sm">Apakah Anda adalah Pemilik Sah Usaha Ini?</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Profil data ini berasal dari pendataan awal dinas dan belum diklaim. Pemilik usaha berhak mengklaim profil ini dengan mengunggah KTP untuk mendapatkan kontrol penuh pembaruan data, galeri produk, dan tanda verifikasi dinas.
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('klaim.create', $umkm->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-lg transition">
                                    <span>Ajukan Klaim Sekarang</span> &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Deskripsi Usaha -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Profil & Deskripsi Usaha</h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $umkm->deskripsi ?: 'Belum ada deskripsi rinci untuk usaha ini.' }}
                </div>
            </div>

            <!-- Titik Lokasi Peta (Leaflet Single View) -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Lokasi Usaha di Peta</h3>
                    <span class="text-xs text-slate-500 font-mono">{{ number_format($umkm->latitude, 4) }}, {{ number_format($umkm->longitude, 4) }}</span>
                </div>
                <div id="single-umkm-map" class="h-80 w-full rounded-xl border border-slate-200 z-10"></div>
                <p class="text-xs text-slate-500 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                    <span>{{ $umkm->alamat }}, Kec. {{ $umkm->kecamatan }}</span>
                </p>
            </div>

            <!-- Ulasan & Rating Pelanggan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Ulasan & Penilaian Pelanggan</h3>
                        <p class="text-xs text-slate-500">Pendapat pembeli dan masyarakat mengenai produk & layanan</p>
                    </div>
                    <div class="flex items-center gap-2 bg-amber-50 px-3.5 py-1.5 rounded-xl border border-amber-200 text-amber-800">
                        <i class="fa-solid fa-star text-amber-500 text-base"></i>
                        <span class="text-lg font-black">{{ number_format($umkm->rating, 1) }}</span>
                        <span class="text-xs text-amber-700 font-medium">/ 5.0 ({{ $umkm->jumlah_review }} ulasan)</span>
                    </div>
                </div>

                <!-- Form Tambah Ulasan -->
                <form action="{{ route('umkm.review.store', $umkm->slug) }}" method="POST" class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                    @csrf
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Tulis Ulasan Baru</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-slate-700">Nama Anda <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_reviewer" required placeholder="Nama pembeli" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-slate-700">Beri Rating Bintang <span class="text-rose-500">*</span></label>
                            <select name="rating" required class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-amber-700 font-bold">
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                <option value="2">⭐⭐ (2 - Kurang)</option>
                                <option value="1">⭐ (1 - Buruk)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-slate-700">Komentar / Pengalaman Pembelian</label>
                        <textarea name="komentar" rows="2" placeholder="Ceritakan kualitas rasa, keramahan penjual, atau keaslian produk..." class="w-full p-2.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>

                <!-- Daftar Ulasan Pelanggan -->
                <div class="space-y-4 pt-2">
                    @forelse($umkm->reviews()->latest()->take(10)->get() as $rev)
                        <div class="p-4 rounded-xl border border-slate-100 bg-white space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">{{ $rev->nama_reviewer ?: 'Pelanggan' }}</span>
                                <div class="text-amber-500 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $rev->komentar ?: 'Memberikan penilaian bintang tanpa ulasan tertulis.' }}</p>
                            <span class="text-[10px] text-slate-400 block">{{ $rev->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            Belum ada ulasan untuk usaha ini. Jadilah yang pertama memberikan ulasan!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Info (Right 1 Col) -->
        <div class="space-y-6">
            <!-- Kotak Kontak & Informasi Legalitas -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Kontak & Legal</h3>

                <ul class="space-y-3.5 text-xs">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Telepon / WhatsApp</span>
                            <span class="font-bold text-slate-800">{{ $umkm->telepon ?: 'Belum dicantumkan' }}</span>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Email Usaha</span>
                            <span class="font-bold text-slate-800">{{ $umkm->email ?: 'Belum dicantumkan' }}</span>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Instagram</span>
                            <span class="font-bold text-slate-800">{{ $umkm->instagram ? '@'.$umkm->instagram : 'Belum dicantumkan' }}</span>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Total Dilihat</span>
                            <span class="font-bold text-slate-800">{{ number_format($umkm->jumlah_dilihat) }} kali</span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- UMKM Terkait -->
            @if($relatedUmkm->count() > 0)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Usaha Sejenis di {{ $umkm->kategori?->nama }}</h3>
                    <div class="space-y-3">
                        @foreach($relatedUmkm as $rel)
                            <a href="{{ route('umkm.show', $rel->slug) }}" class="block p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-1">{{ $rel->nama_usaha }}</h4>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                                    <span>Kec. {{ $rel->kecamatan }}</span>
                                    <span class="text-amber-500 font-semibold"><i class="fa-solid fa-star"></i> {{ number_format($rel->rating, 1) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ (float)$umkm->latitude ?: 0.493 }};
        const lng = {{ (float)$umkm->longitude ?: 117.545 }};

        const map = L.map('single-umkm-map', {
            scrollWheelZoom: false
        }).setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const customIcon = L.divIcon({
            className: 'custom-single-marker',
            html: `<div style="background-color: #059669; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-size: 13px;">
                <i class="fa-solid fa-store"></i>
            </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        L.marker([lat, lng], { icon: customIcon })
            .addTo(map)
            .bindPopup("<b>{{ $umkm->nama_usaha }}</b><br>{{ $umkm->alamat }}")
            .openPopup();
    });
</script>
@endpush
