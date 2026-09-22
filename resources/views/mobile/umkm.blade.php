<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | UMKM</title>
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
        body { background: #edf4ef; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .phone-shell { width: min(100%, 390px); max-width: 100%; min-height: 840px; background: #f8faf8; border-radius: 32px; box-shadow: 0 30px 80px rgba(17, 24, 39, 0.12); overflow: hidden; border: 1px solid rgba(17, 24, 39, 0.04); }
        .status-bar { height: 28px; background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); }
        .bottom-nav { background: rgba(255,255,255,0.0); backdrop-filter: blur(12px); border-top: 0; box-shadow: none; }
        .nav-pill { border-radius: 18px; padding: 8px 6px 10px; transition: all .2s ease; min-height: 72px; }
        .nav-pill.active { color: #111827; }
        .nav-icon { width: 42px; height: 42px; border-radius: 15px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #6b7280; transition: all .2s ease; box-shadow: inset 0 0 0 1px rgba(17,24,39,0.02); }
        .nav-pill.active .nav-icon { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: white; box-shadow: 0 12px 24px rgba(22, 163, 74, 0.26); }
        .nav-label { display: block; margin-top: 6px; font-size: 9px; line-height: 1; letter-spacing: 0.03em; text-transform: uppercase; }
        .chip { background: rgba(255,255,255,0.8); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 8px 18px rgba(15,23,42,0.04); }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @media (max-width: 420px) {
            body {
                padding: 0;
            }
            .phone-shell {
                width: 100%;
                max-width: 100%;
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
                border-left: 0;
                border-right: 0;
            }
            .status-bar {
                padding-left: 1rem;
                padding-right: 1rem;
            }
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
<body class="min-h-screen w-full overflow-x-hidden flex items-center justify-center p-2 sm:p-4 md:p-8">
    <div class="flex w-full justify-center">
        <div class="phone-shell">
            <div class="status-bar flex items-center justify-between px-5 text-[11px] font-semibold text-slate-700">
                <span>09:41</span>
                <div class="flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                    <span class="h-2.5 w-5 rounded-full border border-slate-700"></span>
                </div>
            </div>

            <main class="px-4 pb-24 pt-3">
                <header class="flex items-center justify-between mb-4">
                    <div>
                        <p class="section-label text-[9px] font-semibold text-brand-700">UMKM</p>
                        <h1 class="text-[18px] font-black text-slate-900">Daftar usaha</h1>
                    </div>
                    <button class="w-10 h-10 rounded-2xl bg-white border border-slate-200 shadow-soft flex items-center justify-center text-brand-700 font-bold">↗</button>
                </header>

                <form action="{{ route('preview.mobile.umkm') }}" method="GET" class="mb-4">
                    <div class="flex items-center gap-2">
                        <label class="flex flex-1 items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow-soft border border-slate-200 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-500">
                                <circle cx="11" cy="11" r="6"/>
                                <path d="m16 16 5 5"/>
                            </svg>
                            <input type="text" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari UMKM, produk, atau lokasi…" class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none" />
                        </label>
                        @if(($searchTerm ?? '') !== '' || request('kategori'))
                            <a href="{{ route('preview.mobile.umkm') }}" class="rounded-xl bg-slate-100 px-2.5 py-2 text-[10px] font-semibold text-slate-600">Reset</a>
                        @endif
                    </div>
                </form>

                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Kategori</h3>
                        <span class="text-[11px] font-semibold text-brand-700">{{ count($featuredUmkm ?? []) }} hasil</span>
                    </div>
                    <div class="no-scrollbar flex gap-2 overflow-x-auto pb-1">
                        @php
                            $selectedCategoryId = $selectedCategoryId ?? request('kategori');
                        @endphp
                        @forelse($categoryList ?? ($categories ?? []) as $category)
                            @php
                                $categoryId = $category['id'] ?? null;
                                $categoryName = $category['nama'] ?? 'Kategori';
                                $rawCategoryIcon = $category['icon'] ?? 'store';
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
                                $categoryIcon = $iconMap[strtolower((string) $rawCategoryIcon)] ?? (is_numeric($rawCategoryIcon) ? '•' : (string) $rawCategoryIcon);
                                $queryParams = request()->query();
                                $isActive = (string) ($selectedCategoryId ?? '') === (string) ($categoryId ?? '');
                                unset($queryParams['kategori']);
                                $filteredParams = $isActive ? $queryParams : array_merge($queryParams, ['kategori' => $categoryId]);
                            @endphp
                            <a href="{{ route('preview.mobile.umkm', $filteredParams) }}" class="chip min-w-[96px] rounded-[20px] px-3 py-3 text-left {{ $isActive ? 'ring-2 ring-brand-500 bg-brand-50' : '' }}">
                                <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-xl bg-white text-base text-brand-700 shadow-sm">{!! $categoryIcon !!}</div>
                                <p class="text-sm font-semibold text-slate-800">{{ $categoryName }}</p>
                            </a>
                        @empty
                            @php $fallbackCategories = [['id' => null, 'nama' => 'Kuliner', 'icon' => '◍'], ['id' => null, 'nama' => 'Fashion', 'icon' => '◌'], ['id' => null, 'nama' => 'Kerajinan', 'icon' => '△']]; @endphp
                            @foreach($fallbackCategories as $fallback)
                                <a href="{{ route('preview.mobile.umkm') }}" class="chip min-w-[96px] rounded-[20px] px-3 py-3 text-left">
                                    <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-xl bg-white text-base text-brand-700 shadow-sm">{{ $fallback['icon'] }}</div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $fallback['nama'] }}</p>
                                </a>
                            @endforeach
                        @endforelse
                    </div>
                </section>

                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">UMKM terdekat</h3>
                        <a href="{{ route('preview.mobile.umkm') }}" class="text-[11px] font-semibold text-brand-700">Reset</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($featuredUmkm ?? [] as $umkm)
                            @php
                                $name = $umkm['nama_usaha'] ?? 'Nama usaha';
                                $category = $umkm['kategori']['nama'] ?? 'Umum';
                                $rating = $umkm['rating'] ?? 4.8;
                                $alamat = $umkm['alamat'] ?? 'Kutim';
                                $jarak = $umkm['jarak_km'] ?? 3.2;
                            @endphp
                            <article class="card-soft rounded-[24px] p-3">
                                <div class="flex items-start gap-3">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#f1e8bf] to-[#dff3d7]"></div>
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <h4 class="text-base font-black text-slate-900">{{ $name }}</h4>
                                                <p class="mt-1 text-xs text-slate-500">{{ $category }} • {{ $alamat }}</p>
                                            </div>
                                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700">★ {{ number_format($rating, 1) }}</span>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-xs font-medium text-slate-500">{{ number_format((float) $jarak, 1) }} km</span>
                                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => $umkm['slug'] ?? 'kopi-lestari']) }}" class="rounded-full bg-brand-600 px-4 py-2 text-[11px] font-bold text-white shadow-sm inline-block">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="card-soft rounded-[24px] p-3">
                                <div class="flex items-start gap-3">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#f1e8bf] to-[#dff3d7]"></div>
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <h4 class="text-base font-black text-slate-900">Kopi Lestari</h4>
                                                <p class="mt-1 text-xs text-slate-500">Kuliner • Sangatta Utara</p>
                                            </div>
                                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700">★ 4.8</span>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-xs font-medium text-slate-500">3.2 km</span>
                                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => 'kopi-lestari']) }}" class="rounded-full bg-brand-600 px-4 py-2 text-[11px] font-bold text-white shadow-sm inline-block">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
            </main>

            <nav class="bottom-nav fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[390px] px-3 pb-3 pt-2">
                <div class="grid grid-cols-5 gap-2 rounded-[30px] bg-white px-2 py-2 shadow-[0_18px_40px_rgba(15,23,42,0.12)] border border-slate-200/80">
                    <a href="{{ route('preview.mobile') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                        <span class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M3 10.5 12 3l9 7.5"/>
                                <path d="M5 9.5V20h14V9.5"/>
                            </svg>
                        </span>
                        <span class="nav-label">Home</span>
                    </a>
                    <a href="{{ route('preview.mobile.umkm') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.umkm') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                        <span class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M7 7h10l2 11H5l2-11Z"/>
                                <path d="M9 7V5a3 3 0 0 1 6 0v2"/>
                            </svg>
                        </span>
                        <span class="nav-label">UMKM</span>
                    </a>
                    <a href="{{ route('preview.mobile.peta') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.peta') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                        <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6.5L9 3l6 3 6-3v13l-6 3-6-3-6 3V6.5z"/><path d="M9 3v13M15 6v11"/></svg></span>
                        <span class="nav-label">Peta</span>
                    </a>
                    <a href="{{ route('preview.mobile.promo') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.promo') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                        <span class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M18 8h-1l-2 2"/>
                                <path d="M7 8h10v8H7z"/>
                                <path d="M8 12h8"/>
                            </svg>
                        </span>
                        <span class="nav-label">Promo</span>
                    </a>
                    <a href="{{ route('preview.mobile.akun') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.akun') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                        <span class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 20c2-3 5-4 8-4s6 1 8 4"/>
                            </svg>
                        </span>
                        <span class="nav-label">Akun</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
