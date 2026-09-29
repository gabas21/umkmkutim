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
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-800 text-right">{{ $detail['alamat'] }}</span>
                                @if(! empty($detail['latitude']) && ! empty($detail['longitude']))
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $detail['latitude'] }},{{ $detail['longitude'] }}" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-[12px] text-emerald-700" aria-label="Buka maps lokasi UMKM">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </a>
                                @endif
                            </div>
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

                @if(! empty($catalogProducts))
                    <section class="mb-4 card-soft rounded-[24px] p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Katalog produk</h3>
                            <span class="text-[10px] font-semibold text-brand-700">{{ count($catalogProducts) }} item</span>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            @foreach($catalogProducts as $product)
                                <div
                                    class="product-card overflow-hidden rounded-[20px] border border-slate-100 bg-white text-left shadow-sm"
                                    data-name="{{ $product['name'] }}"
                                    data-price="{{ $product['price'] }}"
                                    data-description="{{ $product['description'] }}"
                                    data-image="{{ !empty($product['image']) ? $product['image'] : (route('placeholder.image', ['category' => $product['category'] ?? $detail['kategori']['nama'] ?? 'Produk']) . '?label=' . urlencode($product['name'])) }}"
                                    data-badge="{{ $product['badge'] }}"
                                    data-category="{{ $product['category'] ?? 'Produk' }}"
                                >
                                    <div class="relative h-28 overflow-hidden bg-slate-100">
                                        <img src="{{ !empty($product['image']) ? $product['image'] : (route('placeholder.image', ['category' => $product['category'] ?? $detail['kategori']['nama'] ?? 'Produk']) . '?label=' . urlencode($product['name'])) }}" alt="{{ $product['name'] }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('umkm.png') }}';">
                                        <div class="absolute left-2 top-2 flex flex-wrap gap-1">
                                            <span class="rounded-full bg-brand-600/90 px-2 py-1 text-[8px] font-bold text-white shadow-sm">{{ $product['category'] ?? 'Produk' }}</span>
                                            <span class="rounded-full bg-white/90 px-2 py-1 text-[8px] font-bold text-brand-700 shadow-sm">{{ $product['badge'] }}</span>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <p class="line-clamp-2 text-sm font-black leading-tight text-slate-900">{{ $product['name'] }}</p>
                                        <div class="mt-2 space-y-1.5">
                                            <p class="text-[11px] font-semibold text-brand-700">{{ $product['price'] }}</p>
                                            <div class="flex items-center justify-between gap-2 pt-1">
                                                <p class="text-[9px] font-medium text-slate-400">Jumlah</p>
                                                <div class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-1.5 py-1">
                                                    <button type="button" data-action="decrease" data-name="{{ $product['name'] }}" class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-sm font-bold text-slate-700 shadow-sm">-</button>
                                                    <span data-qty-display="{{ $product['name'] }}" class="min-w-[18px] text-center text-[10px] font-bold text-slate-800">0</span>
                                                    <button type="button" data-action="increase" data-name="{{ $product['name'] }}" class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-sm font-bold text-slate-700 shadow-sm">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section id="order-summary" class="mb-4 hidden card-soft rounded-[24px] p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500">Keranjang</h3>
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[9px] font-bold text-emerald-700">Aktif</span>
                        </div>
                        <button id="clear-cart" type="button" class="text-[10px] font-semibold text-slate-500">Reset</button>
                    </div>
                    <div id="cart-items" class="mt-3 space-y-2"></div>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-semibold text-slate-500">Jumlah item</span>
                        <span id="cart-count" class="text-sm font-black text-slate-900">0</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Total</span>
                        <span id="cart-total" class="text-sm font-black text-brand-700">Rp 0</span>
                    </div>
                    <p class="mt-3 text-[10px] leading-relaxed text-slate-500">Catatan: keranjang akan direset bila Anda meninggalkan halaman UMKM ini.</p>
                </section>

                <div class="flex gap-2">
                    @if($whatsAppUrl)
                        <a id="whatsapp-order-btn" href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-full bg-[#25D366] py-3 text-sm font-bold text-white shadow-lift">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>Pesan via WA</span>
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
                        <div class="flex gap-2 pt-1">
                            <button type="button" id="product-modal-add-cart" class="flex-1 rounded-full bg-brand-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20">Tambah ke keranjang</button>
                        </div>
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
            const modalImage = document.getElementById('product-modal-image');
            const modalBadge = document.getElementById('product-modal-badge');
            const modalName = document.getElementById('product-modal-name');
            const modalPrice = document.getElementById('product-modal-price');
            const modalDescription = document.getElementById('product-modal-description');
            const addToCartButton = document.getElementById('product-modal-add-cart');
            const cartSummary = document.getElementById('order-summary');
            const cartItems = document.getElementById('cart-items');
            const cartCount = document.getElementById('cart-count');
            const cartTotal = document.getElementById('cart-total');
            const clearCartButton = document.getElementById('clear-cart');
            const whatsappOrderButton = document.getElementById('whatsapp-order-btn');
            const whatsappBaseUrl = '{{ $whatsAppUrl ?? '' }}';
            const cart = new Map();
            let currentProduct = null;

            const parsePrice = (value) => {
                if (!value) return 0;
                const cleaned = String(value).replace(/[^\d]/g, '');
                return Number(cleaned || 0);
            };

            const formatPrice = (value) => {
                if (!value) return 'Rp 0';
                return value.toString();
            };

            const formatCurrency = (value) => {
                const numericValue = Number(value || 0);
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(numericValue);
            };

            const updateQtyIndicators = () => {
                cards.forEach((card) => {
                    const name = card.dataset.name || '';
                    const value = cart.get(name)?.qty ?? 0;
                    const qtyDisplay = card.querySelector('[data-qty-display]');

                    if (qtyDisplay) {
                        qtyDisplay.textContent = String(value);
                    }
                });
            };

            const buildWhatsAppUrl = () => {
                if (!whatsappBaseUrl || cart.size === 0) {
                    return whatsappBaseUrl || '#';
                }

                const items = Array.from(cart.values());
                const message = [
                    'Halo, saya ingin memesan produk berikut:',
                    ...items.map((item, index) => `${index + 1}. ${item.name} (${item.qty}x - ${item.price})`),
                    '',
                    'Mohon konfirmasi stok dan metode pengiriman.'
                ].join('\n');

                try {
                    const url = new URL(whatsappBaseUrl);
                    url.searchParams.set('text', message);
                    return url.toString();
                } catch (error) {
                    return `${whatsappBaseUrl}?text=${encodeURIComponent(message)}`;
                }
            };

            const renderCart = () => {
                const items = Array.from(cart.values());
                const totalItems = items.reduce((sum, item) => sum + item.qty, 0);
                const totalPrice = items.reduce((sum, item) => {
                    const unitPrice = parsePrice(item.price);
                    return sum + (unitPrice * item.qty);
                }, 0);

                if (!items.length) {
                    cartSummary.classList.add('hidden');
                    cartCount.textContent = '0';
                    cartTotal.textContent = 'Rp 0';
                    if (whatsappOrderButton) {
                        whatsappOrderButton.href = whatsappBaseUrl || '#';
                    }
                    updateQtyIndicators();
                    return;
                }

                cartSummary.classList.remove('hidden');
                cartCount.textContent = String(totalItems);
                cartTotal.textContent = formatCurrency(totalPrice);
                cartItems.innerHTML = items.map((item) => `
                    <div class="flex items-center justify-between gap-3 rounded-[18px] border border-slate-100 bg-slate-50 p-2.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-800">${item.name}</p>
                            <p class="text-[11px] font-semibold text-brand-700">${item.price}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" data-action="decrease" data-name="${item.name}" class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-sm font-bold text-slate-700">-</button>
                            <span class="w-5 text-center text-sm font-bold text-slate-800">${item.qty}</span>
                            <button type="button" data-action="increase" data-name="${item.name}" class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-sm font-bold text-slate-700">+</button>
                        </div>
                    </div>
                `).join('');

                updateQtyIndicators();

                if (whatsappOrderButton) {
                    whatsappOrderButton.href = buildWhatsAppUrl();
                }
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                currentProduct = null;
            };

            const changeCartItem = (name, delta) => {
                const productCard = [...cards].find((card) => (card.dataset.name || '') === name);
                const source = productCard ? {
                    name: productCard.dataset.name || name,
                    price: productCard.dataset.price || 'Rp 0',
                    description: productCard.dataset.description || 'Detail produk belum tersedia.',
                    image: productCard.dataset.image || '{{ asset('umkm.png') }}',
                    badge: productCard.dataset.badge || 'Produk'
                } : { name, price: 'Rp 0', description: 'Detail produk belum tersedia.', image: '{{ asset('umkm.png') }}', badge: 'Produk', category: 'Produk' };

                const current = cart.get(name) ?? { ...source, qty: 0 };
                const nextQty = current.qty + delta;

                if (nextQty <= 0) {
                    cart.delete(name);
                } else {
                    current.qty = nextQty;
                    cart.set(name, current);
                }

                renderCart();
            };

            const addProductToCart = () => {
                if (!currentProduct) {
                    return;
                }

                changeCartItem(currentProduct.name, 1);
                closeModal();
            };

            cards.forEach((card) => {
                card.addEventListener('click', (event) => {
                    if (event.target.closest('[data-action]')) {
                        return;
                    }

                    currentProduct = {
                        name: card.dataset.name || 'Produk UMKM',
                        price: card.dataset.price || 'Rp 0',
                        description: card.dataset.description || 'Detail produk belum tersedia.',
                        image: card.dataset.image || '{{ asset('umkm.png') }}',
                        badge: card.dataset.badge || 'Produk',
                        category: card.dataset.category || card.dataset.badge || 'Produk'
                    };

                    modalImage.src = currentProduct.image;
                    modalImage.alt = currentProduct.name;
                    modalBadge.textContent = currentProduct.category;
                    modalName.textContent = currentProduct.name;
                    modalPrice.textContent = formatPrice(currentProduct.price);
                    modalDescription.textContent = currentProduct.description;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                });
            });

            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-action]');
                if (!button) {
                    return;
                }

                const { action, name } = button.dataset;
                if (!name || !action) {
                    return;
                }

                if (action === 'increase') {
                    changeCartItem(name, 1);
                }

                if (action === 'decrease') {
                    changeCartItem(name, -1);
                }
            });

            addToCartButton.addEventListener('click', addProductToCart);
            clearCartButton.addEventListener('click', () => {
                cart.clear();
                renderCart();
            });

            cartItems.addEventListener('click', (event) => {
                const button = event.target.closest('button[data-action]');
                if (!button) {
                    return;
                }

                const { action, name } = button.dataset;
                if (!name || !action) {
                    return;
                }

                if (action === 'increase') {
                    changeCartItem(name, 1);
                }

                if (action === 'decrease') {
                    changeCartItem(name, -1);
                }
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

            window.addEventListener('beforeunload', (event) => {
                if (cart.size > 0) {
                    event.preventDefault();
                    event.returnValue = 'Keranjang akan direset jika Anda meninggalkan halaman UMKM ini.';
                    return event.returnValue;
                }
            });

            renderCart();
        })();
    </script>
</body>
</html>
