<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbR2fQvQ4bYk0z0Qq1kFQv6Y1Y6rjz1lV7KzP7Q6q1Y4JVj7k2qZ6Y3n1J6+gk1Z1+3s6K1X1Y2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {'50': '#f0fdf4', '100': '#dcfce7', '500': '#16a34a', '600': '#15803d', '700': '#166534', '900': '#14532d'}, ink: '#111827', mist: '#f5f7f5', muted: '#6b7280', line: '#e5e7eb' }, boxShadow: { soft: '0 12px 30px rgba(15, 23, 42, 0.06)', lift: '0 18px 45px rgba(20, 83, 45, 0.15)' } } } } }
    </script>
    <style>
        body { background: #edf4ef; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .phone-shell { width: min(100%, 390px); min-height: 840px; background: #f8faf8; border-radius: 32px; box-shadow: 0 30px 80px rgba(17, 24, 39, 0.12); overflow: hidden; border: 1px solid rgba(17, 24, 39, 0.04); }
        .status-bar { height: 28px; background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); }
        .bottom-nav { background: rgba(255,255,255,0.0); backdrop-filter: blur(12px); border-top: 0; box-shadow: none; }
        .nav-pill { border-radius: 18px; padding: 8px 6px 10px; transition: all .2s ease; min-height: 72px; }
        .nav-pill.active { color: #111827; }
        .nav-icon { width: 42px; height: 42px; border-radius: 15px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #6b7280; transition: all .2s ease; box-shadow: inset 0 0 0 1px rgba(17,24,39,0.02); }
        .nav-pill.active .nav-icon { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: white; box-shadow: 0 12px 24px rgba(22, 163, 74, 0.26); }
        .nav-label { display: block; margin-top: 6px; font-size: 9px; line-height: 1; letter-spacing: 0.03em; text-transform: uppercase; }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-8">
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
                        <p class="section-label text-[9px] font-semibold text-brand-700">PROMO</p>
                        <h1 class="text-[18px] font-black text-slate-900">Cuan & event</h1>
                    </div>
                    <button class="w-10 h-10 rounded-2xl bg-white border border-slate-200 shadow-soft flex items-center justify-center text-brand-700 font-bold">%</button>
                </header>
                <section class="mb-4 rounded-[28px] bg-gradient-to-r from-brand-600 via-brand-500 to-emerald-400 p-4 text-white shadow-lift overflow-hidden relative">
                    <div class="absolute -right-6 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute right-6 bottom-0 h-20 w-20 rounded-full bg-brand-900/10"></div>
                    <div class="relative">
                        <p class="section-label text-[9px] font-semibold text-emerald-100">KAMPANYE</p>
                        <h2 class="mt-2 text-[22px] font-black tracking-tight leading-tight">Beli lokal,<br>dukung UMKM</h2>
                        <p class="mt-2 max-w-[80%] text-sm text-emerald-50/90">Promo yang makin menarik di Kutim untuk bulan ini.</p>
                    </div>
                </section>
                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Promo aktif</h3>
                        <button class="text-[11px] font-semibold text-brand-700">Lihat semua</button>
                    </div>
                    <div class="space-y-3">
                        @forelse($promoItems ?? [] as $promo)
                            @php
                                $title = $promo['title'] ?? 'Promo UMKM';
                                $type = $promo['type'] ?? 'PROMO';
                                $location = $promo['location'] ?? 'Kutim';
                                $date = $promo['date'] ?? '12 Sep 2026';
                                $badge = $promo['badge'] ?? 'Promo';
                                $gradient = $loop->iteration % 2 === 0 ? 'from-[#f1d7cc] via-[#f8efe8] to-[#ebf4d0]' : 'from-[#d9f1df] via-[#f6fdf8] to-[#f2e7cb]';
                            @endphp
                            <article class="card-soft rounded-[24px] overflow-hidden">
                                <div class="h-28 bg-gradient-to-r {{ $gradient }} p-4 flex items-end">
                                    <div>
                                        <p class="section-label text-[9px] font-semibold text-brand-700">{{ $type }}</p>
                                        <h4 class="mt-1 text-lg font-black text-slate-900">{{ $title }}</h4>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>{{ $date }}</span>
                                        <span>{{ $location }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="rounded-full bg-brand-50 px-2 py-1 text-[10px] font-semibold text-brand-700">{{ $badge }}</span>
                                        <button class="rounded-full bg-brand-600 text-white font-bold px-3 py-2 text-[11px]">Lihat</button>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="card-soft rounded-[24px] overflow-hidden">
                                <div class="h-28 bg-gradient-to-r from-[#d9f1df] via-[#f6fdf8] to-[#f2e7cb] p-4 flex items-end">
                                    <div>
                                        <p class="section-label text-[9px] font-semibold text-brand-700">PROMO</p>
                                        <h4 class="mt-1 text-lg font-black text-slate-900">Diskon 20% produk lokal</h4>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="flex items-center justify-between text-xs text-slate-500"><span>12 Sep 2026</span><span>Sangatta</span></div>
                                    <button class="mt-3 w-full rounded-full bg-brand-600 text-white font-bold py-2.5 text-xs">Lihat promo</button>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
                <section class="mb-4">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Update lokal</h3>
                        <button class="text-[11px] font-semibold text-brand-700">Semua</button>
                    </div>
                    <div class="space-y-3">
                        <article class="card-soft rounded-[20px] p-3 flex gap-3 items-center">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d9f1df] to-[#f9f4d8]"></div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">KOMUNITAS</p>
                                <h4 class="mt-1 text-sm font-black text-slate-900">UMKM Kutim mulai buka stok baru</h4>
                            </div>
                        </article>
                        <article class="card-soft rounded-[20px] p-3 flex gap-3 items-center">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#f5ddcf] to-[#eaf4d9]"></div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">LOKAL</p>
                                <h4 class="mt-1 text-sm font-black text-slate-900">Produk unggulan dari Sangatta masuk market</h4>
                            </div>
                        </article>
                    </div>
                </section>
            </main>
            <nav class="bottom-nav fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[390px] px-3 pb-3 pt-2">
                <div class="grid grid-cols-5 gap-2 rounded-[30px] bg-white px-2 py-2 shadow-[0_18px_40px_rgba(15,23,42,0.12)] border border-slate-200/80">
                    <a href="{{ route('preview.mobile') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg></span><span class="nav-label">Home</span></a>
                    <a href="{{ route('preview.mobile.umkm') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M7 7h10l2 11H5l2-11Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg></span><span class="nav-label">UMKM</span></a>
                    <a href="{{ route('preview.mobile.peta') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.peta') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6.5L9 3l6 3 6-3v13l-6 3-6-3-6 3V6.5z"/><path d="M9 3v13M15 6v11"/></svg></span><span class="nav-label">Peta</span></a>
                    <a href="{{ route('preview.mobile.promo') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.promo') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 8h-1l-2 2"/><path d="M7 8h10v8H7z"/><path d="M8 12h8"/></svg></span><span class="nav-label">Promo</span></a>
                    <a href="{{ route('preview.mobile.akun') }}" class="nav-pill flex flex-col items-center justify-center text-[10px] {{ request()->routeIs('preview.mobile.akun') ? 'active font-bold text-slate-800' : 'font-medium text-slate-500' }}"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="8" r="4"/><path d="M4 20c2-3 5-4 8-4s6 1 8 4"/></svg></span><span class="nav-label">Akun</span></a>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
