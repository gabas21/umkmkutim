<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim</title>
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
            background: linear-gradient(180deg, #f0fdf4 0%, #f8fafc 100%);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 420px) {
            body {
                padding: 0;
            }
            main {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            nav.fixed {
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="min-h-screen w-full bg-[#edf4ef] text-slate-800">
    <div class="min-h-screen w-full bg-[#edf4ef]">
        <header class="sticky top-0 z-30 border-b border-emerald-100 bg-white/90 backdrop-blur-xl">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-sm font-black text-white shadow-lift">
                        K
                    </div>
                    <div>
                        <p class="text-[9px] font-bold tracking-[0.2em] text-emerald-700">KUTIM</p>
                        <h1 class="text-base font-black tracking-tight text-slate-900">UMKM Kutim</h1>
                    </div>
                </div>

                <button class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a2 2 0 11-4 0" />
                    </svg>
                </button>
            </div>
        </header>

        <main class="flex-1 space-y-5 px-4 pb-24 pt-4">
            <div class="rounded-[28px] bg-gradient-to-br from-brand-700 via-brand-600 to-emerald-500 p-4 text-white shadow-lift">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-100">Usaha lokal</p>
                        <h2 class="mt-2 text-2xl font-black leading-tight">Temukan produk terbaik<br>di Kutim</h2>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/25">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 rounded-2xl bg-white/12 p-3 ring-1 ring-white/15">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="6"></circle>
                        <path d="m16 16 5 5"></path>
                    </svg>
                    <span class="text-sm text-emerald-50">Cari UMKM, produk, atau lokasi…</span>
                </div>
            </div>

            <section>
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-500">Kategori</h3>
                    <a href="#" class="text-xs font-bold text-emerald-700">Lihat semua</a>
                </div>
                <div class="scrollbar-hidden flex gap-3 overflow-x-auto pb-1">
                    @foreach (($categories ?? []) as $category)
                        <div class="min-w-[86px] rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-soft">
                            <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-sm text-emerald-700">
                                {!! $category['icon'] ?? '<i class="fa-solid fa-store"></i>' !!}
                            </div>
                            <p class="text-[11px] font-semibold text-slate-700">{{ $category['nama'] ?? 'Umum' }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            @php
                $homeSections = $categoryGroups ?? [
                    ['title' => 'Terbaik', 'items' => collect($featuredUmkm ?? [])->take(4)->values()->all()],
                    ['title' => 'Terdekat', 'items' => collect($featuredUmkm ?? [])->take(4)->values()->all()],
                ];
            @endphp

            @foreach ($homeSections as $section)
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-500">{{ $section['title'] }}</h3>
                        <a href="#" class="text-xs font-bold text-emerald-700">Lainnya</a>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach (($section['items'] ?? []) as $item)
                            <a href="{{ route('preview.mobile.umkm.detail', ['slug' => $item['slug'] ?? 'kopi-lestari']) }}" class="block rounded-[24px] border border-slate-200 bg-white p-3 shadow-soft transition-transform duration-200 active:scale-[0.98]">
                                <div class="flex flex-col gap-3">
                                    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-100 to-amber-100">
                                        <img
                                            src="{{ $item['foto_utama'] ?? asset('umkm.png') }}"
                                            alt="{{ $item['nama_usaha'] ?? 'UMKM' }}"
                                            class="h-20 w-full object-cover"
                                            onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';"
                                        >
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="truncate text-xs font-black text-slate-900">{{ $item['nama_usaha'] ?? 'UMKM Lokal' }}</h4>
                                            <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[9px] font-bold text-amber-800">{{ number_format((float) ($item['rating'] ?? 0), 1) }}</span>
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-500">{{ $item['kategori']['nama'] ?? 'Umum' }}</p>
                                        <p class="mt-0.5 line-clamp-2 text-[10px] text-slate-400">{{ $item['alamat'] ?? 'Kutim' }}</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-[9px] font-semibold text-emerald-700">{{ $item['jarak_km'] ?? '3.2' }} km</span>
                                            <span class="rounded-full bg-brand-600 px-2 py-1 text-[9px] font-bold text-white">Lihat</span>
                                        </div>
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
</body>
</html>
