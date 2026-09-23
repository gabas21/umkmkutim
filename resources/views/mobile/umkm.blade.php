<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                            900: '#14532d'
                        },
                        ink: '#111827',
                        mist: '#f5f7f5',
                        muted: '#6b7280',
                        line: '#e5e7eb'
                    },
                    boxShadow: {
                        soft: '0 12px 30px rgba(15, 23, 42, 0.06)',
                        lift: '0 18px 45px rgba(20, 83, 45, 0.15)'
                    }
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        html, body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        body {
            background: #edf4ef;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .chip { background: rgba(255,255,255,0.8); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 8px 18px rgba(15,23,42,0.04); }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden bg-[#edf4ef] text-slate-800">
    <div class="min-h-screen w-full bg-white">
        <main class="px-4 pb-24 pt-3">
                <header class="flex items-center justify-between mb-4">
                    <div>
                        <p class="section-label text-[9px] font-semibold text-brand-700">UMKM</p>
                        <h1 class="text-[18px] font-black text-slate-900">Daftar usaha</h1>
                    </div>
                    <button class="w-10 h-10 rounded-2xl bg-white border border-slate-200 shadow-soft flex items-center justify-center text-brand-700 font-bold">↗</button>
                </header>

                <form action="{{ route('preview.mobile.umkm') }}" method="GET" class="mb-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <label class="flex flex-1 items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-4 py-3 text-slate-500 shadow-[0_10px_30px_rgba(15,23,42,0.08)] relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-emerald-500"><circle cx="11" cy="11" r="6"/><path d="m16 16 5 5"/></svg>
                            <input type="text" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari UMKM, alamat, atau kecamatan" class="w-full bg-transparent text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none" autocomplete="off" />
                        </label>
                        @if(($searchTerm ?? '') !== '' || request('kategori') || request('kecamatan'))
                            <a href="{{ route('preview.mobile.umkm') }}" class="rounded-xl bg-white px-2.5 py-2 text-[10px] font-semibold text-slate-700 ring-1 ring-emerald-200 shadow-sm">Reset</a>
                        @endif
                    </div>

                    <div class="rounded-[24px] border border-emerald-100 bg-white/95 p-3 shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                        <div class="mb-2">
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Filter lengkap</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-700">
                            <label class="block rounded-2xl border border-emerald-100 bg-emerald-50/60 p-2">
                                <span class="mb-1.5 block text-[9px] font-bold uppercase tracking-[0.12em] text-slate-500">Kategori</span>
                                <select name="kategori" class="w-full bg-transparent text-sm text-slate-800 focus:outline-none">
                                    <option value="" {{ empty(request('kategori')) ? 'selected' : '' }}>Semua</option>
                                    @foreach(($categoryList ?? $categories ?? []) as $category)
                                        @php $categoryId = $category['id'] ?? null; @endphp
                                        <option value="{{ $categoryId }}" {{ (string) (request('kategori') ?? $selectedCategoryId ?? '') === (string) $categoryId ? 'selected' : '' }}>
                                            {{ $category['nama'] ?? 'Kategori' }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block rounded-2xl border border-emerald-100 bg-emerald-50/60 p-2">
                                <span class="mb-1.5 block text-[9px] font-bold uppercase tracking-[0.12em] text-slate-500">Kecamatan</span>
                                <select name="kecamatan" class="w-full bg-transparent text-sm text-slate-800 focus:outline-none">
                                    <option value="" {{ empty(request('kecamatan')) && empty($selectedKecamatan) ? 'selected' : '' }}>Semua kecamatan</option>
                                    @foreach(($kecamatanList ?? []) as $kecamatan)
                                        <option value="{{ $kecamatan }}" {{ (request('kecamatan') ?? $selectedKecamatan ?? '') === $kecamatan ? 'selected' : '' }}>{{ $kecamatan }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="w-full rounded-full bg-emerald-500 px-3 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20">Terapkan</button>
                        </div>
                    </div>
                </form>

                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">UMKM terdekat</h3>
                        <a href="{{ route('preview.mobile.umkm') }}" class="text-[11px] font-semibold text-brand-700">Reset</a>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @forelse($featuredUmkm ?? [] as $umkm)
                            @php
                                $name = $umkm['nama_usaha'] ?? 'Nama usaha';
                                $category = $umkm['kategori']['nama'] ?? 'Umum';
                                $rating = (float) ($umkm['rating'] ?? 4.8);
                                $alamat = $umkm['alamat'] ?? 'Kutim';
                                $jarak = (float) ($umkm['jarak_km'] ?? 3.2);
                                $badgeText = $loop->first ? 'Verified' : ($loop->iteration === 2 ? 'Popular' : 'New');
                                $gradientClass = $loop->first ? 'from-[#e7efe0] via-[#f3f2df] to-[#e7efd8]' : 'from-[#dfeee2] via-[#eef5d4] to-[#e8efda]';
                            @endphp
                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => $umkm['slug'] ?? 'kopi-lestari']) }}" class="block overflow-hidden rounded-[26px] border border-slate-200/80 bg-white shadow-soft transition-transform duration-200 active:scale-[0.98]">
                                <div class="relative h-28 overflow-hidden bg-gradient-to-r {{ $gradientClass }}">
                                    <img
                                        src="{{ $umkm['foto_utama'] ?? asset('umkm.png') }}"
                                        alt="{{ $name }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';"
                                    >
                                    <div class="absolute inset-x-0 top-0 flex justify-end p-2">
                                        <span class="rounded-full border border-slate-200/80 bg-white/70 px-3 py-1 text-[10px] font-semibold text-slate-700 shadow-sm backdrop-blur-sm">{{ $badgeText }}</span>
                                    </div>
                                </div>

                                <div class="px-3 pb-3 pt-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h4 class="text-[15px] font-black leading-tight text-slate-900">{{ $name }}</h4>
                                            <p class="mt-1 text-[10px] text-slate-500">{{ $category }}</p>
                                        </div>
                                        <div class="flex items-center gap-1 whitespace-nowrap text-[10px] font-bold text-amber-500">
                                            <span>★</span>
                                            <span>{{ number_format($rating, 1) }}</span>
                                        </div>
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-[10px] leading-relaxed text-slate-500">{{ $alamat }}</p>

                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-medium text-slate-600">{{ number_format($jarak, 1) }} km</span>
                                        <span class="rounded-full bg-brand-600 px-3 py-1.5 text-[10px] font-bold text-white shadow-sm">Detail</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => 'teh']) }}" class="block overflow-hidden rounded-[26px] border border-slate-200/80 bg-white shadow-soft transition-transform duration-200 active:scale-[0.98]">
                                <div class="relative h-28 overflow-hidden bg-gradient-to-r from-[#e7efe0] via-[#f3f2df] to-[#e7efd8]">
                                    <img src="{{ asset('umkm.png') }}" alt="Teh" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';">
                                    <div class="absolute inset-x-0 top-0 flex justify-end p-2">
                                        <span class="rounded-full border border-slate-200/80 bg-white/70 px-3 py-1 text-[10px] font-semibold text-slate-700 shadow-sm backdrop-blur-sm">Verified</span>
                                    </div>
                                </div>

                                <div class="px-3 pb-3 pt-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h4 class="text-[15px] font-black leading-tight text-slate-900">Teh</h4>
                                            <p class="mt-1 text-[10px] text-slate-500">Perdagangan &amp; Kelontong</p>
                                        </div>
                                        <div class="flex items-center gap-1 whitespace-nowrap text-[10px] font-bold text-amber-500">
                                            <span>★</span>
                                            <span>5.0</span>
                                        </div>
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-[10px] leading-relaxed text-slate-500">Jl Mulawarman</p>

                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-medium text-slate-600">3.2 km</span>
                                        <span class="rounded-full bg-brand-600 px-3 py-1.5 text-[10px] font-bold text-white shadow-sm">Detail</span>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => 'ayam']) }}" class="block overflow-hidden rounded-[26px] border border-slate-200/80 bg-white shadow-soft transition-transform duration-200 active:scale-[0.98]">
                                <div class="relative h-28 overflow-hidden bg-gradient-to-r from-[#dfeee2] via-[#eef5d4] to-[#e8efda]">
                                    <img src="{{ asset('umkm.png') }}" alt="Ayam" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';">
                                    <div class="absolute inset-x-0 top-0 flex justify-end p-2">
                                        <span class="rounded-full border border-slate-200/80 bg-white/70 px-3 py-1 text-[10px] font-semibold text-slate-700 shadow-sm backdrop-blur-sm">Popular</span>
                                    </div>
                                </div>

                                <div class="px-3 pb-3 pt-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h4 class="text-[15px] font-black leading-tight text-slate-900">Ayam</h4>
                                            <p class="mt-1 text-[10px] text-slate-500">Kuliner &amp; Makanan</p>
                                        </div>
                                        <div class="flex items-center gap-1 whitespace-nowrap text-[10px] font-bold text-amber-500">
                                            <span>★</span>
                                            <span>5.0</span>
                                        </div>
                                    </div>

                                    <p class="mt-2 text-[10px] leading-relaxed text-slate-500">JL PHOTOS SANGATTA BONTANG, DESA SANGATTA SELATAN</p>

                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-medium text-slate-600">5.2 km</span>
                                        <span class="rounded-full bg-brand-600 px-3 py-1.5 text-[10px] font-bold text-white shadow-sm">Detail</span>
                                    </div>
                                </div>
                            </a>
                        @endforelse
                    </div>
                </section>
            </main>

@include('mobile.partials.bottom-nav')
    </div>
</body>
</html>
