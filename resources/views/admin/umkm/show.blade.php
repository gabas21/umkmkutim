@extends('layouts.admin')

@section('title', 'Detail UMKM — Panel Admin')

@section('admin-content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.umkm.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar UMKM
        </a>
        <h1 class="text-2xl sm:text-3xl font-black">Detail UMKM</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1">Tinjau profil usaha, verifikasi status klaim, dan riwayat keputusan admin</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-slate-400">Profil Usaha</p>
                            <h2 class="text-2xl font-black text-slate-900 mt-1">{{ $umkm->nama_usaha }}</h2>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $umkm->status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($umkm->status === 'suspended' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $umkm->status === 'active' ? 'Aktif' : ($umkm->status === 'suspended' ? 'Ditangguhkan' : 'Tidak Aktif') }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $umkm->status_klaim === 'terverifikasi' ? 'bg-blue-100 text-blue-800' : ($umkm->status_klaim === 'menunggu_verifikasi' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $umkm->status_klaim === 'terverifikasi' ? 'Terverifikasi' : ($umkm->status_klaim === 'menunggu_verifikasi' ? 'Menunggu Verifikasi' : 'Belum Diklaim') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-600">
                    <div class="space-y-3">
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Kategori</div>
                            <div class="font-semibold text-slate-900">{{ $umkm->kategori?->nama ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Sumber Data</div>
                            <div class="font-semibold text-slate-900 uppercase">{{ $umkm->sumber_data ?? 'mandiri' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Kecamatan</div>
                            <div class="font-semibold text-slate-900">{{ $umkm->kecamatan?->name ?? $umkm->kecamatan ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Kelurahan</div>
                            <div class="font-semibold text-slate-900">{{ $umkm->kelurahan?->name ?? $umkm->kelurahan_desa ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Alamat</div>
                            <div class="font-medium text-slate-800 leading-relaxed">{{ $umkm->alamat ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Kontak</div>
                            <div class="font-medium text-slate-800">{{ $umkm->telepon ?? '-' }}</div>
                            <div class="font-medium text-slate-800">{{ $umkm->email ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400 mb-1">Website</div>
                            <div class="font-medium text-slate-800">{{ $umkm->website ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4">Riwayat Verifikasi</h3>

                @if($umkm->verifications->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($umkm->verifications()->latest()->get() as $verification)
                            <div class="border border-slate-200 rounded-xl p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold
                                        {{ $verification->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($verification->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $verification->status === 'verified' ? 'Disetujui' : ($verification->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $verification->verified_at ? $verification->verified_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                                <div class="mt-2 text-xs text-slate-600">
                                    <div>Verifikator: {{ $verification->verifiedBy?->name ?? '-' }}</div>
                                    @if($verification->catatan)
                                        <div class="mt-1 text-slate-700">Catatan: {{ $verification->catatan }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="border border-dashed border-slate-200 rounded-xl p-6 text-center text-sm text-slate-400">
                        Belum ada riwayat verifikasi untuk UMKM ini.
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4">Aksi Moderasi</h3>

                <form action="{{ route('admin.umkm.status', $umkm->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500">Status Publikasi</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700">
                        <option value="active" {{ $umkm->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $umkm->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="suspended" {{ $umkm->status === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                        Simpan Status Publikasi
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4">Keputusan Verifikasi</h3>

                <form action="{{ route('admin.umkm.verify', $umkm->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500">Status Klaim</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700">
                        <option value="pending" {{ $umkm->status_klaim === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="verified" {{ $umkm->status_klaim === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="rejected" {{ $umkm->status_klaim === 'belum_diklaim' ? 'selected' : '' }}>Ditolak / Belum Diklaim</option>
                    </select>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-2">Catatan Admin</label>
                        <textarea name="catatan" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700" placeholder="Catatan keputusan verifikasi..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition">
                        Simpan Keputusan Verifikasi
                    </button>
                </form>
            </div>

            @if($umkm->klaimAktif)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-bold text-slate-900 mb-3">Pemilik Terverifikasi</h3>
                    <div class="text-sm text-slate-700">
                        <div class="font-semibold text-slate-900">{{ $umkm->klaimAktif->pelakuUsaha?->nama ?? '-' }}</div>
                        <div class="mt-1">{{ $umkm->klaimAktif->pelakuUsaha?->email ?? '-' }}</div>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
