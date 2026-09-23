<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim Mobile Preview</title>
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
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
            touch-action: pan-y;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
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
                <header class="mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="section-label text-[9px] font-semibold text-brand-700">KUTIM</p>
                                <h1 class="text-lg font-black tracking-tight text-slate-900">UMKM Kutim</h1>
                            </div>
                        </div>
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

                <section class="mb-5">
                    <div id="promo-slider" class="flex gap-3 overflow-x-auto pb-1 no-scrollbar snap-x snap-mandatory scroll-smooth">
                        <a href="{{ route('preview.mobile.umkm') }}" class="group block min-w-[90%] snap-center overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-soft">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80" alt="UMKM Lokal" class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </a>
                        <a href="{{ route('preview.mobile.peta') }}" class="group block min-w-[90%] snap-center overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-soft">
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=900&q=80" alt="Peta UMKM" class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </a>
                        <a href="{{ route('preview.mobile.promo') }}" class="group block min-w-[90%] snap-center overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-soft">
                            <img src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=900&q=80" alt="Promo UMKM" class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </a>
                    </div>
                </section>

                @php
                    $homeCategories = $categoryGroups ?? [
                        [
                            'title' => 'Terbaik',
                            'items' => collect($featuredUmkm ?? [])->take(4)->values()->all(),
                        ],
                        [
                            'title' => 'Terdekat',
                            'items' => collect($featuredUmkm ?? [])->take(4)->values()->all(),
                        ],
                    ];
                @endphp

                @foreach ($homeCategories as $section)
                    <section class="mb-5">
                        <div class="mb-3 flex items-center justify-between px-1">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">{{ $section['title'] }}</h3>
                            <a href="#" class="text-[11px] font-semibold text-brand-700">Lihat semua</a>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach($section['items'] as $umkm)
                                @php
                                    $name = $umkm['nama_usaha'] ?? $umkm['nama'] ?? 'UMKM';
                                    $categoryName = $umkm['kategori']['nama'] ?? 'Umum';
                                    $location = $umkm['alamat'] ?? 'Kutim';
                                    $distance = number_format((float) ($umkm['jarak_km'] ?? 3.2), 1, ',', '.').' km';
                                    $rating = number_format((float) ($umkm['rating'] ?? 4.8), 1, '.', '');
                                    $badge = $loop->first ? 'Verified' : ($loop->iteration === 2 ? 'Populer' : 'Baru');
                                @endphp
                                <a href="{{ route('preview.mobile.umkm.detail', ['slug' => $umkm['slug'] ?? 'kopi-lestari']) }}" class="card-soft block overflow-hidden rounded-[24px] transition-transform duration-200 active:scale-[0.98]">
                                    <div class="relative h-28 overflow-hidden">
                                        <img
                                            src="{{ $umkm['foto_utama'] ?? 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=900&q=80' }}"
                                            alt="{{ $name }}"
                                            class="h-full w-full object-cover"
                                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=900&q=80';"
                                        >
                                        <div class="absolute right-3 top-3 rounded-full bg-white px-2 py-1 text-[9px] font-bold text-brand-700 shadow-sm">{{ $badge }}</div>
                                    </div>
                                    <div class="p-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <h4 class="truncate text-sm font-black text-slate-900">{{ $name }}</h4>
                                                <p class="mt-1 text-[10px] text-slate-500">{{ $categoryName }}</p>
                                            </div>
                                            <span class="text-[10px] font-bold text-amber-500">★ {{ $rating }}</span>
                                        </div>
                                        <p class="mt-1 line-clamp-2 text-[10px] text-slate-400">{{ $location }}</p>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-[10px] text-slate-500">{{ $distance }}</span>
                                            <span class="rounded-full bg-brand-600 px-2.5 py-1.5 text-[9px] font-bold text-white shadow-sm">Detail</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endforeach

            </main>

@include('mobile.partials.bottom-nav')
    </div>

    <script>
        (function () {
            const slider = document.getElementById('promo-slider');
            if (!slider) return;

            const cards = [...slider.children];
            if (!cards.length) return;

            let current = 0;
            let autoScrollId = null;
            let isDragging = false;
            let startX = 0;
            let startScrollLeft = 0;

            const goToSlide = (index) => {
                const target = cards[index];
                if (!target) return;

                current = index;
                const nextLeft = target.offsetLeft - slider.offsetLeft;
                slider.style.scrollBehavior = 'smooth';
                slider.scrollTo({
                    left: nextLeft,
                    behavior: 'smooth'
                });
            };

            const step = () => {
                if (isDragging) return;
                current = (current + 1) % cards.length;
                goToSlide(current);
            };

            const startAutoScroll = () => {
                if (autoScrollId) clearInterval(autoScrollId);
                autoScrollId = setInterval(step, 2600);
            };

            const stopAutoScroll = () => {
                if (autoScrollId) clearInterval(autoScrollId);
                autoScrollId = null;
            };

            slider.addEventListener('pointerdown', (event) => {
                isDragging = true;
                startX = event.clientX;
                startScrollLeft = slider.scrollLeft;
                slider.style.scrollBehavior = 'auto';
                slider.setPointerCapture(event.pointerId);
                stopAutoScroll();
            });

            slider.addEventListener('pointermove', (event) => {
                if (!isDragging) return;
                const delta = event.clientX - startX;
                slider.scrollLeft = startScrollLeft - delta;
            });

            slider.addEventListener('pointerup', (event) => {
                isDragging = false;
                slider.releasePointerCapture(event.pointerId);

                const delta = event.clientX - startX;
                const threshold = Math.max(36, slider.clientWidth * 0.14);

                if (Math.abs(delta) > threshold) {
                    current = delta < 0 ? (current + 1) % cards.length : (current - 1 + cards.length) % cards.length;
                    goToSlide(current);
                } else {
                    goToSlide(current);
                }

                startAutoScroll();
            });

            slider.addEventListener('pointerleave', () => {
                if (!isDragging) return;
                isDragging = false;
                goToSlide(current);
                startAutoScroll();
            });

            slider.addEventListener('pointercancel', () => {
                isDragging = false;
                goToSlide(current);
                startAutoScroll();
            });

            slider.style.scrollBehavior = 'smooth';
            startAutoScroll();
        })();
    </script>
</body>
</html>
