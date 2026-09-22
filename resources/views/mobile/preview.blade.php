<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim Mobile Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbR2fQvQ4bYk0z0Qq1kFQv6Y1Y6rjz1lV7KzP7Q6q1Y4JVj7k2qZ6Y3n1J6+gk1Z1+3s6K1X1Y2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        .bottom-nav {
            background: rgba(255, 255, 255, 0.0);
            backdrop-filter: blur(12px);
            border-top: 0;
            box-shadow: none;
        }
        .nav-pill {
            border-radius: 18px;
            padding: 8px 6px 10px;
            transition: all .2s ease;
            min-height: 72px;
        }
        .nav-pill.active {
            color: #111827;
        }
        .nav-icon {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            transition: all .2s ease;
            box-shadow: inset 0 0 0 1px rgba(17,24,39,0.02);
        }
        .nav-pill.active .nav-icon {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
            box-shadow: 0 12px 24px rgba(22, 163, 74, 0.26);
        }
        .nav-label {
            display: block;
            margin-top: 6px;
            font-size: 9px;
            line-height: 1;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .chip {
            background: rgba(255,255,255,0.8);
            border: 1px solid rgba(17,24,39,0.04);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        }
        .card-soft {
            background: rgba(255,255,255,0.88);
            border: 1px solid rgba(17,24,39,0.04);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
        }
        .section-label {
            letter-spacing: 0.12em;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @media (max-width: 420px) {
            body { padding: 0; }
            nav.bottom-nav {
                width: 100%;
                max-width: 100%;
                left: 0;
                transform: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden bg-[#edf4ef]">
    <div class="min-h-screen w-full bg-[#edf4ef]">

            <main class="px-4 pb-24 pt-3">
                <header class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-black shadow-lift text-sm">K</div>
                        <div>
                            <p class="section-label text-[9px] font-semibold text-brand-700">KUTIM</p>
                            <h1 class="text-lg font-black tracking-tight text-slate-900">UMKM Kutim</h1>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center shadow-soft border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-700">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                <path d="M10 21a2 2 0 0 0 4 0"/>
                            </svg>
                        </button>
                        <button class="w-10 h-10 rounded-2xl bg-brand-600 flex items-center justify-center text-white font-bold shadow-lift text-sm">A</button>
                    </div>
                </header>

                <div class="mb-4">
                    <label class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow-soft border border-slate-200 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-500">
                            <circle cx="11" cy="11" r="6"/>
                            <path d="m16 16 5 5"/>
                        </svg>
                        <span class="text-sm text-slate-500">Cari UMKM, produk, atau lokasi…</span>
                    </label>
                </div>

                <section class="mb-5 rounded-[30px] bg-gradient-to-r from-brand-600 via-brand-500 to-emerald-400 p-4 text-white shadow-lift overflow-hidden relative">
                    <div class="absolute -right-6 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute right-6 bottom-0 h-20 w-20 rounded-full bg-brand-900/10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div>
                                <p class="section-label text-[9px] font-semibold text-emerald-100">LOKAL TERBAIK</p>
                                <h2 class="text-[22px] font-black tracking-tight mt-1.5 leading-tight">Usaha unggulan <br>dekat kamu</h2>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/12 ring-1 ring-white/20">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                    <path d="m12 3 2.924 6.202L21 10.25l-5 4.74 1.39 6.01L12 0 6.61 21l1.39-6.01-5-4.74 6.076-1.048L12 3Z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-emerald-50/90 mb-4 max-w-[82%]">Temukan produk lokal, usaha verified, dan promo dari komunitas UMKM Kutim.</p>
                        <div class="flex items-center gap-2">
                            <button class="rounded-full bg-white text-brand-700 font-bold px-4 py-2.5 text-sm shadow-md">Jelajahi</button>
                            <button class="rounded-full border border-white/30 bg-white/10 px-3 py-2 text-[11px] font-semibold text-white backdrop-blur-sm">Lihat peta</button>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Kategori</h3>
                        <a href="#" class="text-[11px] font-semibold text-brand-700">Lihat semua</a>
                    </div>
                    <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                        @forelse($categories ?? [] as $category)
                            @php
                                $rawIcon = $category['icon'] ?? 'store';
                                $iconMap = [
                                    'utensils' => '🍽️',
                                    'palette' => '🎨',
                                    'shirt' => '👕',
                                    'sprout' => '🌱',
                                    'fish' => '🐟',
                                    'briefcase' => '💼',
                                    'shopping-bag' => '🛍️',
                                    'heart-pulse' => '💚',
                                    'store' => '🏪',
                                    'shop' => '🏪',
                                    'default' => '•',
                                ];
                                $icon = $iconMap[strtolower((string) $rawIcon)] ?? (is_numeric($rawIcon) ? '•' : (string) $rawIcon);
                            @endphp
                            <button class="chip rounded-2xl px-3 py-2 min-w-[90px] text-center">
                                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-lg text-brand-700">{!! $icon !!}</div>
                                <p class="text-[11px] font-semibold text-slate-700">{{ $category['nama'] ?? 'Kategori' }}</p>
                            </button>
                        @empty
                            <button class="chip rounded-2xl px-3 py-2 min-w-[90px] text-center">
                                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-lg text-brand-700">🍽️</div>
                                <p class="text-[11px] font-semibold text-slate-700">Kuliner</p>
                            </button>
                        @endforelse
                    </div>
                </section>

                <section class="mb-5">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">UMKM terdekat</h3>
                        <a href="#" class="text-[11px] font-semibold text-brand-700">Lihat semua</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($featuredUmkm ?? [] as $umkm)
                            @php
                                $name = $umkm['nama_usaha'] ?? $umkm['nama'] ?? 'UMKM';
                                $categoryName = $umkm['kategori']['nama'] ?? 'Umum';
                                $location = $umkm['alamat'] ?? 'Kutim';
                                $distance = number_format((float) ($umkm['jarak_km'] ?? 3.2), 1, ',', '.').' km';
                                $rating = number_format((float) ($umkm['rating'] ?? 4.8), 1, '.', '');
                                $badge = $loop->first ? 'Verified' : ($loop->iteration === 2 ? 'Populer' : 'Baru');
                            @endphp
                            <article class="card-soft rounded-[24px] overflow-hidden">
                                <div class="relative h-28 bg-gradient-to-r from-[#f1e8bf] via-[#f7f1e2] to-[#dff3d7] p-3">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.8),transparent_35%)]"></div>
                                    <div class="absolute right-3 top-3 rounded-full bg-white px-2 py-1 text-[9px] font-bold text-brand-700 shadow-sm">{{ $badge }}</div>
                                </div>
                                <div class="p-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="font-black text-slate-900">{{ $name }}</h4>
                                            <p class="text-[11px] text-slate-500 mt-1">{{ $categoryName }} • {{ $location }}</p>
                                        </div>
                                        <span class="text-xs font-bold text-amber-500">★ {{ $rating }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-[11px] text-slate-500">{{ $distance }}</span>
                                        <button class="rounded-full bg-brand-600 px-3 py-1.5 text-[11px] font-bold text-white shadow-sm">Detail</button>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="card-soft rounded-[24px] overflow-hidden">
                                <div class="relative h-28 bg-gradient-to-r from-[#f1e8bf] via-[#f7f1e2] to-[#dff3d7] p-3">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.8),transparent_35%)]"></div>
                                    <div class="absolute right-3 top-3 rounded-full bg-white px-2 py-1 text-[9px] font-bold text-brand-700 shadow-sm">Verified</div>
                                </div>
                                <div class="p-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="font-black text-slate-900">Kopi Lestari</h4>
                                            <p class="text-[11px] text-slate-500 mt-1">Kuliner • Sangatta Utara</p>
                                        </div>
                                        <span class="text-xs font-bold text-amber-500">★ 4.8</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-[11px] text-slate-500">3,2 km</span>
                                        <button class="rounded-full bg-brand-600 px-3 py-1.5 text-[11px] font-bold text-white shadow-sm">Detail</button>
                                    </div>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>

                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Promo & acara</h3>
                        <a href="#" class="text-[11px] font-semibold text-brand-700">Lainnya</a>
                    </div>
                    @forelse($promoEvents ?? [] as $promo)
                        @php
                            $title = $promo['title'] ?? 'Promo UMKM';
                            $location = $promo['location'] ?? 'Kutim';
                            $eventDate = $promo['start_date'] ?? now()->addDays(7);
                            $dateText = \Carbon\Carbon::parse($eventDate)->format('d M Y');
                        @endphp
                        <div class="rounded-[28px] card-soft overflow-hidden border border-emerald-100">
                            <div class="h-28 bg-gradient-to-r from-[#1a9a63] via-[#1b8d5d] to-[#0d5a3d] p-4 flex items-end">
                                <div>
                                    <p class="section-label text-[9px] font-semibold text-emerald-100">EVENT LOKAL</p>
                                    <h4 class="mt-1 text-lg font-black text-white">{{ $title }}</h4>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $dateText }}</span>
                                    <span>{{ $location }}</span>
                                </div>
                                <button class="mt-3 w-full rounded-full bg-brand-600 text-white font-bold py-2.5 text-xs shadow-sm">Daftar sekarang</button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[28px] card-soft overflow-hidden border border-emerald-100">
                            <div class="h-28 bg-gradient-to-r from-[#1a9a63] via-[#1b8d5d] to-[#0d5a3d] p-4 flex items-end">
                                <div>
                                    <p class="section-label text-[9px] font-semibold text-emerald-100">EVENT LOKAL</p>
                                    <h4 class="mt-1 text-lg font-black text-white">Pameran UMKM Kutim</h4>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>12 Sep 2026</span>
                                    <span>Sangatta</span>
                                </div>
                                <button class="mt-3 w-full rounded-full bg-brand-600 text-white font-bold py-2.5 text-xs shadow-sm">Daftar sekarang</button>
                            </div>
                        </div>
                    @endforelse
                </section>
            </main>

@include('mobile.partials.bottom-nav')
    </div>
</body>
</html>
