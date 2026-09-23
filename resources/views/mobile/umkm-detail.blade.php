<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | Detail UMKM</title>
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
        .bottom-nav { background: rgba(255,255,255,0.0); backdrop-filter: blur(12px); border-top: 0; box-shadow: none; }
        .nav-pill { border-radius: 18px; padding: 8px 6px 10px; transition: all .2s ease; min-height: 72px; }
        .nav-pill.active { color: #111827; }
        .nav-icon { width: 42px; height: 42px; border-radius: 15px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #6b7280; transition: all .2s ease; box-shadow: inset 0 0 0 1px rgba(17,24,39,0.02); }
        .nav-pill.active .nav-icon { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: white; box-shadow: 0 12px 24px rgba(22, 163, 74, 0.26); }
        .nav-label { display: block; margin-top: 6px; font-size: 9px; line-height: 1; letter-spacing: 0.03em; text-transform: uppercase; }
        .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(17,24,39,0.04); box-shadow: 0 14px 32px rgba(15,23,42,0.05); }
        .section-label { letter-spacing: 0.12em; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        @media (max-width: 420px) {
            body { padding: 0; }
            nav.bottom-nav { width: 100%; max-width: 100%; left: 0; transform: none; border-radius: 0; }
        }
    </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden bg-[#edf4ef]">
    <div class="min-h-screen w-full bg-[#edf4ef]">

            <main class="px-4 pb-24 pt-3">
                <header class="mb-4 flex items-center justify-between">
                    <a href="{{ route('preview.mobile.umkm') }}" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-lg font-bold text-slate-700 shadow-soft border border-slate-200">←</a>
                    <div class="text-right">
                        <p class="section-label text-[9px] font-semibold text-brand-700">UMKM</p>
                        <h1 class="text-[18px] font-black text-slate-900">Detail usaha</h1>
                    </div>
                </header>

                @php
                    $detail = $detailUmkm ?? [
                        'nama_usaha' => 'Kopi Lestari',
                        'kategori' => ['nama' => 'Kuliner'],
                        'rating' => 4.8,
                        'alamat' => 'Sangatta Utara',
                        'kecamatan' => 'Sangatta Utara',
                        'telepon' => '+62 812-3456-7890',
                        'deskripsi' => 'Produk lokal berkualitas dari komunitas UMKM Kabupaten Kutim, siap menjadi pilihan wisata kuliner dan kebutuhan harian masyarakat.',
                        'produk' => ['Kopi lokal', 'Roti', 'Snack khas'],
                        'links' => [],
                        'whatsapp_url' => 'https://wa.me/6281234567890',
                    ];
                    $produk = $detail['produk'] ?? ['Kopi lokal', 'Roti', 'Snack khas'];
                    $detailLinks = $detail['links'] ?? [];
                    $whatsAppUrl = $detail['whatsapp_url'] ?? null;
                    $catalogProducts = $detail['catalog_products'] ?? [];
                @endphp

                <section class="mb-4 overflow-hidden rounded-[28px] border border-emerald-100 bg-gradient-to-r from-[#dff5e4] via-[#f7fdf8] to-[#f0ecdb] shadow-soft">
                    <div class="h-44 bg-gradient-to-br from-[#d9f1df] via-[#f6fdf8] to-[#f2e7cb] p-4 flex items-end">
                        <div>
                            <p class="section-label text-[9px] font-semibold text-brand-700">{{ strtoupper($detail['kategori']['nama'] ?? 'UMKM') }}</p>
                            <h2 class="mt-2 text-[26px] font-black tracking-tight text-slate-900">{{ $detail['nama_usaha'] }}</h2>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700">★ {{ number_format((float) ($detail['rating'] ?? 4.8), 1) }}</span>
                            <span class="text-[11px] font-semibold text-slate-500">Verified</span>
                        </div>
                        <p class="text-sm leading-6 text-slate-600">{{ $detail['deskripsi'] }}</p>
                    </div>
                </section>

                <section class="mb-4 card-soft rounded-[24px] p-4">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Informasi</h3>
                    <div class="mt-3 space-y-3 text-sm">
                        <div class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                            <span class="text-slate-500">Lokasi</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $detail['alamat'] }}</span>
                        </div>
                        <div class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                            <span class="text-slate-500">Kecamatan</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $detail['kecamatan'] }}</span>
                        </div>
                        <div class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                            <span class="text-slate-500">Telepon</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $detail['telepon'] }}</span>
                        </div>
                    </div>
                </section>

                <section class="mb-4 card-soft rounded-[24px] p-4">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Produk unggulan</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($produk as $item)
                            <span class="rounded-full bg-brand-50 px-3 py-2 text-[11px] font-semibold text-brand-700">{{ $item }}</span>
                        @endforeach
                    </div>
                </section>

                @if(! empty($catalogProducts))
                    <section class="mb-4 card-soft rounded-[24px] p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Katalog produk</h3>
                            <span class="text-[10px] font-semibold text-brand-700">{{ count($catalogProducts) }} item</span>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            @foreach($catalogProducts as $product)
                                <button
                                    type="button"
                                    class="product-card overflow-hidden rounded-[20px] border border-slate-100 bg-white text-left shadow-sm"
                                    data-name="{{ $product['name'] }}"
                                    data-price="{{ $product['price'] }}"
                                    data-description="{{ $product['description'] }}"
                                    data-image="{{ $product['image'] }}"
                                    data-badge="{{ $product['badge'] }}"
                                >
                                    <div class="relative h-28 overflow-hidden bg-slate-100">
                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';">
                                        <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-1 text-[9px] font-bold text-brand-700 shadow-sm">{{ $product['badge'] }}</span>
                                    </div>
                                    <div class="p-3">
                                        <p class="line-clamp-2 text-sm font-black leading-tight text-slate-900">{{ $product['name'] }}</p>
                                        <p class="mt-2 text-[11px] font-semibold text-brand-700">{{ $product['price'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if(! empty($detailLinks))
                    <section class="mb-4 card-soft rounded-[24px] p-4">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Sosial & toko online</h3>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            @foreach($detailLinks as $link)
                                <a
                                    href="{{ $link['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white px-3 py-3 shadow-sm"
                                >
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $link['icon_background'] }} {{ $link['icon_color'] }}">
                                        <i class="{{ $link['icon'] }}"></i>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Kunjungi</span>
                                        <span class="block truncate text-sm font-semibold text-slate-800">{{ $link['label'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="flex gap-2">
                    @if($whatsAppUrl)
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-full bg-[#25D366] py-3 text-sm font-bold text-white shadow-lift">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>WA</span>
                        </a>
                    @else
                        <div class="flex flex-1 items-center justify-center gap-2 rounded-full bg-slate-300 py-3 text-sm font-bold text-white">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>Nomor belum ada</span>
                        </div>
                    @endif
                    <!-- <button class="flex-1 rounded-full bg-white py-3 text-sm font-bold text-slate-800 border border-slate-200 shadow-soft">Favorit</button> -->
                </div>
            </main>

            <div id="product-modal" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-950/55 px-4 pb-24 pt-6">
                <div class="w-full max-w-md overflow-hidden rounded-[28px] bg-white shadow-2xl">
                    <div class="relative h-52 overflow-hidden bg-slate-100">
                        <img id="product-modal-image" src="{{ asset('umkm.png') }}" alt="Produk UMKM" class="h-full w-full object-cover">
                        <button type="button" id="product-modal-close" class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <span id="product-modal-badge" class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold text-brand-700 shadow-sm">Produk</span>
                    </div>
                    <div class="space-y-3 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 id="product-modal-name" class="text-lg font-black text-slate-900">Produk UMKM</h3>
                                <p id="product-modal-price" class="mt-1 text-sm font-semibold text-brand-700">Rp 0</p>
                            </div>
                        </div>
                        <p id="product-modal-description" class="text-sm leading-6 text-slate-600">Detail produk akan tampil di sini.</p>
                    </div>
                </div>
            </div>
 
@include('mobile.partials.bottom-nav')
    </div>
    <script>
        (function () {
            const modal = document.getElementById('product-modal');
            const closeButton = document.getElementById('product-modal-close');
            const cards = document.querySelectorAll('.product-card');

            if (!modal || !cards.length) {
                return;
            }

            const modalImage = document.getElementById('product-modal-image');
            const modalBadge = document.getElementById('product-modal-badge');
            const modalName = document.getElementById('product-modal-name');
            const modalPrice = document.getElementById('product-modal-price');
            const modalDescription = document.getElementById('product-modal-description');

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            cards.forEach((card) => {
                card.addEventListener('click', () => {
                    modalImage.src = card.dataset.image || '{{ asset('umkm.png') }}';
                    modalImage.alt = card.dataset.name || 'Produk UMKM';
                    modalBadge.textContent = card.dataset.badge || 'Produk';
                    modalName.textContent = card.dataset.name || 'Produk UMKM';
                    modalPrice.textContent = card.dataset.price || 'Rp 0';
                    modalDescription.textContent = card.dataset.description || 'Detail produk belum tersedia.';

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                });
            });

            closeButton.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
