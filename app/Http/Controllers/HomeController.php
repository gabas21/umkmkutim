<?php

namespace App\Http\Controllers;

use App\Models\Bazar;
use App\Models\Berita;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\Kategori;
use App\Models\Pelatihan;
use App\Models\SurveyKepuasan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private function hasTable(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function fallbackMobileData(): array
    {
        return [
            'categories' => [
                ['nama' => 'Kuliner', 'icon' => '◍'],
                ['nama' => 'Fashion', 'icon' => '◌'],
                ['nama' => 'Kerajinan', 'icon' => '△'],
                ['nama' => 'Pertanian', 'icon' => '✦'],
                ['nama' => 'Digital', 'icon' => '⬢'],
            ],
            'featuredUmkm' => [
                ['nama_usaha' => 'Kopi Lestari', 'kategori' => ['nama' => 'Kuliner'], 'rating' => 4.8, 'alamat' => 'Sangatta Utara', 'jarak_km' => 3.2, 'slug' => 'kopi-lestari'],
                ['nama_usaha' => 'Anyam Rasa', 'kategori' => ['nama' => 'Kerajinan'], 'rating' => 4.9, 'alamat' => 'Bengalon', 'jarak_km' => 4.8, 'slug' => 'anyam-rasa'],
                ['nama_usaha' => 'Tani Maju', 'kategori' => ['nama' => 'Pertanian'], 'rating' => 4.7, 'alamat' => 'Muara Wahau', 'jarak_km' => 6.1, 'slug' => 'tani-maju'],
            ],
            'promoEvents' => [
                ['title' => 'Pameran UMKM Kutim', 'location' => 'Sangatta', 'start_date' => now()->addDays(4), 'status' => 'open'],
                ['title' => 'Diskon 20% Produk Lokal', 'location' => 'Kota', 'start_date' => now()->addDays(12), 'status' => 'open'],
            ],
            'userProfile' => [
                'name' => 'Ayu Lestari',
                'email' => 'ayulestari@email.com',
            ],
        ];
    }

    private function loadPromoContent(): array
    {
        $fallback = [
            ['title' => 'Pameran UMKM Kutim', 'location' => 'Sangatta', 'start_date' => now()->addDays(4), 'status' => 'open'],
            ['title' => 'Diskon 20% Produk Lokal', 'location' => 'Kota', 'start_date' => now()->addDays(12), 'status' => 'open'],
        ];

        if ($this->hasTable('events')) {
            try {
                $events = Event::query()
                    ->whereIn('status', ['open', 'published'])
                    ->orderBy('start_date')
                    ->limit(2)
                    ->get()
                    ->map(function ($event) {
                        return [
                            'title' => $event->title,
                            'location' => $event->location ?? 'Kutim',
                            'start_date' => $event->start_date,
                            'status' => $event->status,
                        ];
                    })
                    ->toArray();

                if (! empty($events)) {
                    return $events;
                }
            } catch (\Throwable $e) {
                // fall through to berita dataset below
            }
        }

        if ($this->hasTable('berita')) {
            try {
                $berita = Berita::query()
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->orderByDesc('published_at')
                    ->limit(2)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'title' => $item->judul,
                            'location' => 'Kutim',
                            'start_date' => $item->published_at,
                            'status' => $item->status,
                        ];
                    })
                    ->toArray();

                if (! empty($berita)) {
                    return $berita;
                }
            } catch (\Throwable $e) {
                // fall through to fallback
            }
        }

        return $fallback;
    }

    private function formatCategoryIcon($raw)
    {
        $raw = (string) $raw;
        // normalize name (remove fa- prefix if present)
        $name = strtolower(preg_replace('/^(fa[srlb]?-)/', '', $raw));

        // preferred icons: map friendly names to known FA icon names when they differ
        $map = [
            'utensils' => 'utensils',
            'palette' => 'palette',
            'shirt' => 'shirt',
            'sprout' => 'seedling',
            'fish' => 'fish',
            'briefcase' => 'briefcase',
            'shopping-bag' => 'bag-shopping',
            'shopping_bag' => 'bag-shopping',
            'heart-pulse' => 'heart-pulse',
            'heartbeat' => 'heartbeat',
            'store' => 'store',
            'shop' => 'shop',
        ];

        $faName = $map[$name] ?? $name;

        // sanitize: allow only letters, numbers, hyphen
        $faNameSafe = preg_replace('/[^a-z0-9\-]/', '', $faName);

        if ($faNameSafe === '') {
            return '<i class="fa-solid fa-circle"></i>';
        }

        return '<i class="fa-solid fa-' . $faNameSafe . '"></i>';
    }

    private function mobilePreviewData(): array
    {
        try {
            $categories = Kategori::query()
                ->select(['id', 'nama', 'icon'])
                ->orderBy('nama')
                ->limit(5)
                ->get();

            $featuredUmkm = Umkm::query()
                ->where('status', 'active')
                ->with('kategori:id,nama')
                ->orderByDesc('rating')
                ->orderByDesc('jumlah_review')
                ->limit(3)
                ->get()
                ->map(function ($umkm) {
                    return [
                        'id' => $umkm->id,
                        'nama_usaha' => $umkm->nama_usaha,
                        'slug' => $umkm->slug,
                        'kategori' => ['nama' => $umkm->kategori?->nama ?? 'Umum'],
                        'rating' => (float) ($umkm->rating ?? 0),
                        'alamat' => $umkm->alamat ?? $umkm->kecamatan ?? 'Kutim',
                        'jarak_km' => 3.2 + ($umkm->id % 3),
                    ];
                });

            $promoEvents = $this->loadPromoContent();

            $user = Auth::user();

            $mappedCategories = $categories->isNotEmpty() ? $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'nama' => $category->nama,
                    'icon' => $this->formatCategoryIcon($category->icon ?? 'store'),
                ];
            })->toArray() : $this->fallbackMobileData()['categories'];

            return [
                'categories' => $mappedCategories,
                'featuredUmkm' => $featuredUmkm->isNotEmpty() ? $featuredUmkm->toArray() : $this->fallbackMobileData()['featuredUmkm'],
                'promoEvents' => $promoEvents ?: $this->fallbackMobileData()['promoEvents'],
                'userProfile' => [
                    'name' => $user?->name ?? 'Ayu Lestari',
                    'email' => $user?->email ?? 'ayulestari@email.com',
                ],
            ];
        } catch (\Throwable $e) {
            return $this->fallbackMobileData();
        }
    }

    public function mobilePreviewHome()
    {
        return view('mobile.preview', $this->mobilePreviewData());
    }

    public function mobilePreviewUmkm(Request $request)
    {
        $data = $this->mobilePreviewData();
        $selectedCategoryId = $request->input('kategori');
        $searchTerm = trim((string) $request->input('q', ''));

        try {
            $query = Umkm::query()
                ->where('status', 'active')
                ->with('kategori:id,nama,icon');

            if ($searchTerm !== '') {
                $keyword = '%' . $searchTerm . '%';
                $query->where(function ($umkmQuery) use ($keyword, $searchTerm) {
                    $umkmQuery->where('nama_usaha', 'like', $keyword)
                        ->orWhere('deskripsi', 'like', $keyword)
                        ->orWhere('alamat', 'like', $keyword)
                        ->orWhere('kecamatan', 'like', $keyword)
                        ->orWhere('slug', 'like', '%' . Str::slug($searchTerm) . '%');
                });
            }

            if ($selectedCategoryId !== null && $selectedCategoryId !== '') {
                $query->where('kategori_id', $selectedCategoryId);
            }

            $data['featuredUmkm'] = $query
                ->orderByDesc('rating')
                ->orderByDesc('jumlah_review')
                ->limit(8)
                ->get()
                ->map(function ($umkm) {
                    return [
                        'id' => $umkm->id,
                        'nama_usaha' => $umkm->nama_usaha,
                        'slug' => $umkm->slug,
                        'kategori' => ['nama' => $umkm->kategori?->nama ?? 'Umum'],
                        'rating' => (float) ($umkm->rating ?? 0),
                        'alamat' => $umkm->alamat ?? $umkm->kecamatan ?? 'Kutim',
                        'jarak_km' => 2.4 + ($umkm->id % 5),
                    ];
                })
                ->toArray();

            $data['categoryList'] = Kategori::query()
                ->select(['id', 'nama', 'icon'])
                ->orderBy('nama')
                ->get()
                ->map(function ($kategori) {
                    return [
                        'id' => $kategori->id,
                        'nama' => $kategori->nama,
                        'icon' => $this->formatCategoryIcon($kategori->icon ?? 'store'),
                    ];
                })
                ->toArray();

            $data['selectedCategoryId'] = $selectedCategoryId;
            $data['searchTerm'] = $searchTerm;
        } catch (\Throwable $e) {
            $data['featuredUmkm'] = $data['featuredUmkm'] ?? $this->fallbackMobileData()['featuredUmkm'];
            $data['categoryList'] = $data['categories'] ?? $this->fallbackMobileData()['categories'];
            $data['selectedCategoryId'] = $selectedCategoryId;
            $data['searchTerm'] = $searchTerm;
        }

        return view('mobile.umkm', $data);
    }

    public function mobilePreviewUmkmDetail(string $slug)
    {
        $data = $this->mobilePreviewData();

        try {
            $umkm = Umkm::query()
                ->where('slug', $slug)
                ->orWhere('id', $slug)
                ->with('kategori:id,nama')
                ->first();

            if ($umkm) {
                $data['detailUmkm'] = [
                    'nama_usaha' => $umkm->nama_usaha,
                    'slug' => $umkm->slug,
                    'kategori' => ['nama' => $umkm->kategori?->nama ?? 'Umum'],
                    'rating' => (float) ($umkm->rating ?? 4.8),
                    'alamat' => $umkm->alamat ?? $umkm->kecamatan ?? 'Kutim',
                    'kecamatan' => $umkm->kecamatan ?? 'Kutim',
                    'telepon' => $umkm->telepon ?? '+62 812-3456-7890',
                    'deskripsi' => $umkm->deskripsi ?? 'Produk lokal berkualitas dari komunitas UMKM Kabupaten Kutim, siap menjadi pilihan wisata kuliner dan kebutuhan harian masyarakat.',
                    'produk' => $umkm->produk_unggulan ?? ['Kopi lokal', 'Roti', 'Kerajinan tangan'],
                    'foto_utama' => $umkm->foto_utama ?? null,
                ];
            } else {
                $data['detailUmkm'] = [
                    'nama_usaha' => 'Kopi Lestari',
                    'slug' => 'kopi-lestari',
                    'kategori' => ['nama' => 'Kuliner'],
                    'rating' => 4.8,
                    'alamat' => 'Sangatta Utara',
                    'kecamatan' => 'Sangatta Utara',
                    'telepon' => '+62 812-3456-7890',
                    'deskripsi' => 'Produk lokal berkualitas dari komunitas UMKM Kabupaten Kutim, siap menjadi pilihan wisata kuliner dan kebutuhan harian masyarakat.',
                    'produk' => ['Kopi lokal', 'Roti', 'Snack khas'],
                    'foto_utama' => null,
                ];
            }
        } catch (\Throwable $e) {
            $data['detailUmkm'] = [
                'nama_usaha' => 'Kopi Lestari',
                'slug' => 'kopi-lestari',
                'kategori' => ['nama' => 'Kuliner'],
                'rating' => 4.8,
                'alamat' => 'Sangatta Utara',
                'kecamatan' => 'Sangatta Utara',
                'telepon' => '+62 812-3456-7890',
                'deskripsi' => 'Produk lokal berkualitas dari komunitas UMKM Kabupaten Kutim, siap menjadi pilihan wisata kuliner dan kebutuhan harian masyarakat.',
                'produk' => ['Kopi lokal', 'Roti', 'Snack khas'],
                'foto_utama' => null,
            ];
        }

        return view('mobile.umkm-detail', $data);
    }

    public function mobilePreviewPeta(Request $request)
    {
        $data = $this->mobilePreviewData();
        $searchTerm = trim((string) $request->input('q', ''));
        $selectedCategoryId = $request->input('kategori');
        $selectedStatus = $request->input('status', 'active');
        $selectedRadius = (float) $request->input('radius', 0);
        $selectedRating = (float) $request->input('rating', 0);
        $selectedSort = $request->input('sort', 'rating');
        $selectedKecamatan = trim((string) $request->input('kecamatan', ''));
        $defaultLat = (float) $request->input('lat', -0.499);
        $defaultLng = (float) $request->input('lng', 117.153);

        try {
            $query = Umkm::query()
                ->with('kategori:id,nama')
                ->withCoordinates();

            if ($selectedStatus !== null && $selectedStatus !== '' && $selectedStatus !== 'all') {
                $query->where('status', $selectedStatus);
            } elseif ($selectedStatus === 'all') {
                $query->whereIn('status', ['active', 'published', 'verified', 'pending']);
            } else {
                $query->where('status', 'active');
            }

            if ($searchTerm !== '') {
                $keyword = '%' . $searchTerm . '%';
                $query->where(function ($umkmQuery) use ($keyword, $searchTerm) {
                    $umkmQuery->where('nama_usaha', 'like', $keyword)
                        ->orWhere('alamat', 'like', $keyword)
                        ->orWhere('kecamatan', 'like', $keyword)
                        ->orWhere('kelurahan_desa', 'like', $keyword)
                        ->orWhere('deskripsi', 'like', $keyword)
                        ->orWhere('slug', 'like', '%' . Str::slug($searchTerm) . '%');
                });
            }

            if ($selectedCategoryId !== null && $selectedCategoryId !== '') {
                $query->where('kategori_id', $selectedCategoryId);
            }

            if ($selectedKecamatan !== '') {
                $query->where('kecamatan', 'like', '%' . $selectedKecamatan . '%');
            }

            if ($selectedRating > 0) {
                $query->where('rating', '>=', $selectedRating);
            }

            if ($selectedRadius > 0 && $defaultLat !== 0 && $defaultLng !== 0) {
                $radiusMeters = $selectedRadius * 1000;
                $pointWkt = sprintf('POINT(%s %s)', $defaultLng, $defaultLat);

                $query->whereRaw('ST_Distance_Sphere(location, ST_GeomFromText(?, 4326)) <= ?', [$pointWkt, $radiusMeters]);
            }

            $query->when($selectedSort === 'nearest' && $defaultLat !== 0 && $defaultLng !== 0, function ($nearestQuery) use ($defaultLat, $defaultLng) {
                $pointWkt = sprintf('POINT(%s %s)', $defaultLng, $defaultLat);

                return $nearestQuery->selectRaw('umkm.*, ST_Distance_Sphere(location, ST_GeomFromText(?, 4326)) as jarak_meter', [$pointWkt])
                    ->orderByRaw('jarak_meter ASC');
            }, function ($baseQuery) {
                return $baseQuery;
            });

            if ($selectedSort === 'newest') {
                $query->orderByDesc('created_at');
            } elseif ($selectedSort === 'nearest') {
                if ($defaultLat === 0 || $defaultLng === 0) {
                    $query->orderByDesc('rating');
                }
            } else {
                $query->orderByDesc('rating')
                    ->orderByDesc('jumlah_review');
            }

            $data['nearbyUmkm'] = $query
                ->limit(8)
                ->get()
                ->map(function ($umkm) {
                    return [
                        'id' => $umkm->id,
                        'slug' => $umkm->slug,
                        'nama_usaha' => $umkm->nama_usaha,
                        'kategori' => ['nama' => $umkm->kategori?->nama ?? 'Umum'],
                        'rating' => (float) ($umkm->rating ?? 0),
                        'alamat' => $umkm->alamat ?? $umkm->kecamatan ?? 'Kutim',
                        // include coordinates when available (from withCoordinates()/nearby scopes)
                        'latitude' => isset($umkm->latitude) ? (float) $umkm->latitude : null,
                        'longitude' => isset($umkm->longitude) ? (float) $umkm->longitude : null,
                        'jarak_km' => isset($umkm->jarak_meter) ? max(1, round($umkm->jarak_meter / 1000, 1)) : (2.4 + ($umkm->id % 5)),
                    ];
                })
                ->toArray();

            $data['categoryList'] = Kategori::query()
                ->select(['id', 'nama', 'icon'])
                ->orderBy('nama')
                ->get()
                ->map(function ($kategori) {
                    return [
                        'id' => $kategori->id,
                        'nama' => $kategori->nama,
                        'icon' => $this->formatCategoryIcon($kategori->icon ?? 'store'),
                    ];
                })
                ->toArray();

            $data['kecamatanList'] = Umkm::query()
                ->select('kecamatan')
                ->whereNotNull('kecamatan')
                ->where('kecamatan', '!=', '')
                ->distinct()
                ->orderBy('kecamatan')
                ->pluck('kecamatan')
                ->filter()
                ->values()
                ->toArray();

            $data['selectedCategoryId'] = $selectedCategoryId;
            $data['selectedStatus'] = $selectedStatus;
            $data['selectedRadius'] = $selectedRadius;
            $data['selectedRating'] = $selectedRating;
            $data['selectedSort'] = $selectedSort;
            $data['selectedKecamatan'] = $selectedKecamatan;
            $data['searchTerm'] = $searchTerm;
        } catch (\Throwable $e) {
            $data['nearbyUmkm'] = $data['featuredUmkm'] ?? $this->fallbackMobileData()['featuredUmkm'];
            $data['categoryList'] = $data['categories'] ?? $this->fallbackMobileData()['categories'];
            $data['kecamatanList'] = [];
            $data['selectedCategoryId'] = $selectedCategoryId;
            $data['selectedStatus'] = $selectedStatus;
            $data['selectedRadius'] = $selectedRadius;
            $data['selectedRating'] = $selectedRating;
            $data['selectedSort'] = $selectedSort;
            $data['selectedKecamatan'] = $selectedKecamatan;
            $data['searchTerm'] = $searchTerm;
        }

        return view('mobile.peta', $data);
    }

    public function mobilePreviewPetaSuggest(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $kategori = $request->input('kategori');
        $limit = (int) $request->input('limit', 8);

        try {
            $query = Umkm::query()
                ->with('kategori:id,nama')
                ->withCoordinates()
                ->where('status', 'active');

            if ($q !== '') {
                $keyword = '%' . $q . '%';
                $query->where(function ($umkmQuery) use ($keyword, $q) {
                    $umkmQuery->where('nama_usaha', 'like', $keyword)
                        ->orWhere('alamat', 'like', $keyword)
                        ->orWhere('kecamatan', 'like', $keyword)
                        ->orWhere('kelurahan_desa', 'like', $keyword)
                        ->orWhere('deskripsi', 'like', $keyword)
                        ->orWhere('slug', 'like', '%' . Str::slug($q) . '%');
                });
            }

            if (! empty($kategori)) {
                $query->where('kategori_id', $kategori);
            }

            $results = $query->limit($limit)->get()->map(function ($umkm) {
                return [
                    'id' => $umkm->id,
                    'nama_usaha' => $umkm->nama_usaha,
                    'slug' => $umkm->slug,
                    'kategori' => ['nama' => $umkm->kategori?->nama ?? 'Umum'],
                    'latitude' => isset($umkm->latitude) ? (float) $umkm->latitude : null,
                    'longitude' => isset($umkm->longitude) ? (float) $umkm->longitude : null,
                    'alamat' => $umkm->alamat ?? '',
                ];
            })->toArray();

            return response()->json(['data' => $results]);
        } catch (\Throwable $e) {
            return response()->json(['data' => []]);
        }

    }

    public function mobilePreviewPromo()
    {
        $data = $this->mobilePreviewData();

        $promoItems = collect($this->loadPromoContent())
            ->map(function ($item, $index) {
                $date = $item['start_date'] ?? now()->addDays($index + 1);

                return [
                    'title' => $item['title'] ?? 'Promo UMKM',
                    'type' => $index === 0 ? 'ACARA' : 'PROMO',
                    'location' => $item['location'] ?? 'Kutim',
                    'date' => $date instanceof \DateTimeInterface
                        ? \Carbon\Carbon::parse($date)->format('d M Y')
                        : (is_string($date) ? \Carbon\Carbon::parse($date)->format('d M Y') : '12 Sep 2026'),
                    'badge' => $index === 0 ? 'Event' : 'Promo',
                ];
            })
            ->take(3)
            ->values()
            ->toArray();

        $data['promoItems'] = $promoItems ?: [
            ['title' => 'Diskon 20% Produk Lokal', 'type' => 'PROMO', 'location' => 'Sangatta', 'date' => '12 Sep 2026', 'badge' => 'Promo'],
            ['title' => 'Pameran UMKM Kutim', 'type' => 'ACARA', 'location' => 'Kota', 'date' => '20 Sep 2026', 'badge' => 'Event'],
            ['title' => 'Bazar Kuliner Mingguan', 'type' => 'PROMO', 'location' => 'Muara Wahau', 'date' => '27 Sep 2026', 'badge' => 'Setiap minggu'],
        ];

        return view('mobile.promo', $data);
    }

    public function mobilePreviewAkun()
    {
        $data = $this->mobilePreviewData();
        $pelaku = Auth::guard('pelaku_usaha')->user();

        if ($pelaku) {
            $data['userProfile'] = [
                'name' => $pelaku->nama ?? $pelaku->name ?? 'Pelaku Usaha',
                'email' => $pelaku->email ?? 'pelaku@email.com',
            ];
        }

        $data['isLoggedIn'] = (bool) $pelaku;
        $data['stats'] = [
            ['label' => 'Favorit', 'value' => '12'],
            ['label' => 'Promo', 'value' => '08'],
            ['label' => 'Review', 'value' => '24'],
            ['label' => 'Langganan', 'value' => '3'],
        ];

        $data['menuItems'] = [
            ['title' => 'Favorit saya', 'subtitle' => 'UMKM dan promo yang disimpan', 'icon' => '❤'],
            ['title' => 'Notifikasi', 'subtitle' => 'Event dan promo terbaru', 'icon' => '⚑'],
            ['title' => 'Pengaturan', 'subtitle' => 'Kelola profil dan keamanan', 'icon' => '⚙'],
            ['title' => 'Bantuan', 'subtitle' => 'Pusat bantuan dan kebijakan', 'icon' => '?'],
        ];

        return view('mobile.akun', $data);
    }

    public function index(Request $request)
    {
        $daftarKecamatan = [
            'Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan',
            'Rantau Pulung', 'Muara Wahau', 'Kongbeng', 'Muara Bengkal',
            'Muara Ancalong', 'Busang', 'Telen', 'Sandaran',
            'Sangkulirang', 'Kaliorang', 'Kaubun', 'Karangan',
            'Batu Ampar', 'Long Mesangat'
        ];

        $totalUmkm = Umkm::active()->count();
        $totalKategori = Kategori::count();
        $totalTerverifikasi = Umkm::where('status_klaim', 'terverifikasi')->count();
        $persenTerverifikasi = $totalUmkm > 0 ? round(($totalTerverifikasi / $totalUmkm) * 100, 1) : 0;

        // Data status perizinan / klaim untuk donat chart
        $statusKlaimData = Umkm::active()
            ->select('status_klaim', DB::raw('count(*) as total'))
            ->groupBy('status_klaim')
            ->get()
            ->pluck('total', 'status_klaim');

        // Kategori dengan jumlah UMKM
        $kategoriList = Kategori::withCount(['umkm' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // UMKM Unggulan untuk Tabel Beranda dengan Filter, Search, dan Paginasi 10 per halaman (60 item)
        $featuredUmkm = Umkm::active()
            ->withCoordinates()
            ->with('kategori')
            ->orderByDesc('rating')
            ->orderByDesc('jumlah_review')
            ->take(60)
            ->get();

        // Metrik Seluruh Kecamatan untuk Bar Visualisasi
        $kecamatanStats = Umkm::active()
            ->select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->get();
        $maxKecamatan = $kecamatanStats->max('total') ?: 1;

        // Data titik peta untuk preview Leaflet (clustering)
        $mapPoints = Umkm::active()
            ->withCoordinates()
            ->selectRaw(sprintf(
                'id, nama_usaha, slug, kategori_id, kecamatan, rating, foto_utama, %s as latitude, %s as longitude, status_klaim',
                Umkm::latitudeExpression(),
                Umkm::longitudeExpression()
            ))
            ->with('kategori:id,nama,icon')
            ->take(300)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_usaha,
                    'slug' => $item->slug,
                    'kategori' => $item->kategori?->nama ?? 'Umum',
                    'icon' => $item->kategori?->icon ?? 'store',
                    'kecamatan' => $item->kecamatan,
                    'lat' => (float) $item->latitude,
                    'lng' => (float) $item->longitude,
                    'rating' => (float) $item->rating,
                    'status_klaim' => $item->status_klaim,
                    'url' => route('umkm.show', $item->slug),
                ];
            });

        // Slide Banner Hero (Aktif dari DB)
        $heroSlides = HeroSlide::active()->get();

        return view('home', compact(
            'daftarKecamatan',
            'totalUmkm',
            'totalKategori',
            'totalTerverifikasi',
            'persenTerverifikasi',
            'statusKlaimData',
            'kategoriList',
            'featuredUmkm',
            'kecamatanStats',
            'maxKecamatan',
            'mapPoints',
            'heroSlides'
        ));
    }
}
