@extends('layouts.app')

@section('title', 'Pendaftaran UMKM Baru — Pemkab Kutai Timur')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard.pelaku') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Pendaftaran UMKM Baru (Mandiri)</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Gunakan formulir ini jika usaha Anda belum terdata di basis data impor dinas.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl space-y-8">
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-900 text-xs space-y-1">
            <span class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-circle-info"></i> Petunjuk Pendaftaran:</span>
            <p>Isi informasi usaha secara akurat dan tentukan titik koordinat toko Anda pada peta interaktif di bawah. Unggah foto KTP pemilik untuk langsung memicu verifikasi keabsahan oleh Admin Dinas Koperasi & UMKM Kutai Timur.</p>
        </div>

        <form action="{{ route('umkm.store-mandiri') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Identitas Usaha -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nama Usaha / Merk Dagang <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" required placeholder="Contoh: Amplang Barokah Sangatta" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kategori Komoditas <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Pilih Kategori...</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Deskripsi Lengkap Usaha & Produk Unggulan</label>
                <textarea name="deskripsi" rows="3" placeholder="Ceritakan produk yang diproduksi, keunikan bahan baku lokal, atau layanan yang disediakan..." class="w-full p-3.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Lokasi Wilayah -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kecamatan di Kutai Timur <span class="text-rose-500">*</span></label>
                    <select name="kecamatan" id="select-kecamatan" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Pilih Kecamatan...</option>
                        @foreach($daftarKecamatan as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Desa / Kelurahan</label>
                    <input type="text" name="kelurahan_desa" value="{{ old('kelurahan_desa') }}" placeholder="Contoh: Teluk Lingga" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">Alamat Lengkap (Jalan, RT/RW, Nomor) <span class="text-rose-500">*</span></label>
                <input type="text" name="alamat" value="{{ old('alamat') }}" required placeholder="Jl. Yos Sudarso II No. 12 RT 04" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Interactive Map Picker -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="block text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-map-pin text-emerald-600"></i> Tentukan Titik Lokasi pada Peta <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-slate-500 italic">Geser pin merah atau klik peta di lokasi usaha Anda</span>
                </div>
                <div id="picker-map" class="h-64 w-full rounded-2xl border border-slate-300 z-10"></div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block font-medium text-slate-500 mb-1">Latitude</label>
                        <input type="number" step="any" name="latitude" id="input-lat" value="{{ old('latitude', '0.4930') }}" required readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-1">Longitude</label>
                        <input type="number" step="any" name="longitude" id="input-lng" value="{{ old('longitude', '117.5450') }}" required readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono">
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Nomor Telepon / WA</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="0812xxxxxxxx" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Email Usaha</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="usaha@gmail.com" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Instagram (tanpa @)</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="namatoko" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Upload Foto Toko & Dokumen KTP -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Foto Utama Usaha / Produk</label>
                    <input type="file" name="foto_utama" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800 cursor-pointer">
                    <span class="text-[10px] text-slate-400 block">JPG, PNG (Maks. 2 MB)</span>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Foto KTP Pemilik Usaha <span class="text-rose-500">*</span></label>
                    <input type="file" name="dokumen_ktp" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    <span class="text-[10px] text-slate-400 block">Wajib untuk verifikasi identitas (JPG, PNG, PDF Maks. 3 MB)</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700">Bukti Pendukung Usaha (Opsional: NIB / Foto Plang / SKU)</label>
                <input type="file" name="dokumen_bukti_usaha" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 flex items-center justify-between gap-4 border-t border-slate-100">
                <a href="{{ route('dashboard.pelaku') }}" class="text-xs text-slate-500 hover:text-slate-700 font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Daftarkan Usaha & Ajukan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = parseFloat(document.getElementById('input-lat').value) || 0.4930;
        const defaultLng = parseFloat(document.getElementById('input-lng').value) || 117.5450;

        const map = L.map('picker-map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('input-lat').value = lat.toFixed(6);
            document.getElementById('input-lng').value = lng.toFixed(6);
        }

        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
@endpush
