<?php

use App\Http\Controllers\AdminBazarController;
use App\Http\Controllers\AdminBazarPesertaController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminHeroSlideController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminPelakuUsahaController;
use App\Http\Controllers\AdminPelatihanController;
use App\Http\Controllers\AdminPelatihanPesertaController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminSurveyController;
use App\Http\Controllers\AdminUmkmController;
use App\Http\Controllers\Api\KecamatanBoundaryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\UmkmApiController;
use App\Http\Controllers\Api\UmkmClusterController;
use App\Http\Controllers\Api\UmkmMapController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\BazarController;
use App\Http\Controllers\BazarPesertaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KlaimController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PelatihanPesertaController;
use App\Http\Controllers\PelakuAkunController;
use App\Http\Controllers\PelakuBazarController;
use App\Http\Controllers\PelakuLayananController;
use App\Http\Controllers\PelakuPelatihanController;
use App\Http\Controllers\PelakuUsahaDashboardController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\AdminLokasiController;
use App\Http\Controllers\Api\LokasiController as ApiLokasiController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UmkmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/preview/mobile', [HomeController::class, 'mobilePreviewHome'])->name('preview.mobile');
Route::get('/preview/mobile/umkm', [HomeController::class, 'mobilePreviewUmkm'])->name('preview.mobile.umkm');
Route::get('/preview/mobile/umkm/{slug}', [HomeController::class, 'mobilePreviewUmkmDetail'])->name('preview.mobile.umkm.detail');
Route::get('/preview/mobile/peta', [HomeController::class, 'mobilePreviewPeta'])->name('preview.mobile.peta');
Route::get('/preview/mobile/peta/suggest', [HomeController::class, 'mobilePreviewPetaSuggest'])->name('preview.mobile.peta.suggest');
Route::get('/preview/mobile/promo', [HomeController::class, 'mobilePreviewPromo'])->name('preview.mobile.promo');
Route::get('/preview/mobile/akun', [HomeController::class, 'mobilePreviewAkun'])->name('preview.mobile.akun');
Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');
Route::post('/umkm/{slug}/review', [ReviewController::class, 'store'])->name('umkm.review.store');
Route::get('/api/umkm/nearby', [UmkmController::class, 'nearby'])->name('api.umkm.nearby');
Route::get('/api/umkm/viewport', [UmkmMapController::class, 'viewport'])->name('api.umkm.viewport');
Route::get('/api/umkm/clusters', [UmkmClusterController::class, 'index'])->name('api.umkm.clusters');
Route::get('/api/kutim/kecamatan-boundaries', [KecamatanBoundaryController::class, 'index'])->name('api.kutim.boundaries');

// Public API: list kecamatan and kelurahan (no GeoJSON payload)
Route::get('/api/kutim/kecamatans', [ApiLokasiController::class, 'kecamatans'])->name('api.kutim.kecamatans');
Route::get('/api/kutim/kelurahans', [ApiLokasiController::class, 'kelurahans'])->name('api.kutim.kelurahans');

Route::prefix('api')->group(function () {
    Route::get('/kecamatan', [MasterDataController::class, 'kecamatan']);
    Route::get('/kelurahan', [MasterDataController::class, 'kelurahan']);
    Route::get('/kategori-usaha', [MasterDataController::class, 'kategoriUsaha']);

    Route::get('/umkm', [UmkmApiController::class, 'index']);
    Route::get('/public/umkm', [UmkmApiController::class, 'publicIndex']);
    Route::post('/umkm', [UmkmApiController::class, 'store']);
    Route::get('/umkm/{umkm}', [UmkmApiController::class, 'show']);
    Route::put('/umkm/{umkm}', [UmkmApiController::class, 'update']);
    Route::delete('/umkm/{umkm}', [UmkmApiController::class, 'destroy']);

    Route::get('/admin/umkm', [UmkmApiController::class, 'adminIndex']);
    Route::put('/admin/umkm/{umkm}/verify', [UmkmApiController::class, 'verify']);
});

// Modul Bazar & Expo
Route::get('/bazar', [BazarController::class, 'index'])->name('bazar.index');
Route::get('/bazar/{slug}', [BazarController::class, 'show'])->name('bazar.show');
Route::post('/bazar/{slug}/daftar', [BazarPesertaController::class, 'store'])->name('bazar.daftar');

// Modul Pelatihan & Pendampingan
Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/pelatihan/{slug}', [PelatihanController::class, 'show'])->name('pelatihan.show');
Route::post('/pelatihan/{slug}/daftar', [PelatihanPesertaController::class, 'store'])->name('pelatihan.daftar');

// Modul Laporan Transparansi Publik dialihkan ke Beranda (Laporan telah dipindah ke beranda)
Route::redirect('/laporan', '/')->name('laporan.index');

// Modul Survey Kepuasan Layanan
Route::get('/survey', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/register', [EventParticipantController::class, 'store'])->name('events.register');
Route::get('/api/sliders', [\App\Http\Controllers\SliderController::class, 'index'])->name('api.sliders');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Pelaku Usaha
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Admin Dinas
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected: Dashboard Pelaku Usaha (Guard: pelaku_usaha)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:pelaku_usaha')->group(function () {
    Route::get('/dashboard', [PelakuUsahaDashboardController::class, 'index'])->name('dashboard.pelaku');

    // Pendaftaran Mandiri
    Route::get('/umkm-daftar/baru', [UmkmController::class, 'createMandiri'])->name('umkm.create-mandiri');
    Route::post('/umkm-daftar/baru', [UmkmController::class, 'storeMandiri'])->name('umkm.store-mandiri');

    // Klaim Usaha yang sudah ada
    Route::get('/umkm/{slug}/klaim', [KlaimController::class, 'create'])->name('klaim.create');
    Route::post('/umkm/{slug}/klaim', [KlaimController::class, 'store'])->name('klaim.store');

    // Edit Profil Usaha
    Route::get('/dashboard/umkm/{id}/edit', [PelakuUsahaDashboardController::class, 'editUmkm'])->name('dashboard.pelaku.edit');
    Route::put('/dashboard/umkm/{id}', [PelakuUsahaDashboardController::class, 'updateUmkm'])->name('dashboard.pelaku.update');

    // Layanan / Produk Usaha
    Route::get('/dashboard/umkm/{id}/layanan', [PelakuLayananController::class, 'index'])->name('dashboard.pelaku.layanan.index');
    Route::get('/dashboard/umkm/{id}/layanan/create', [PelakuLayananController::class, 'create'])->name('dashboard.pelaku.layanan.create');
    Route::post('/dashboard/umkm/{id}/layanan', [PelakuLayananController::class, 'store'])->name('dashboard.pelaku.layanan.store');
    Route::get('/dashboard/umkm/{id}/layanan/{layananId}/edit', [PelakuLayananController::class, 'edit'])->name('dashboard.pelaku.layanan.edit');
    Route::put('/dashboard/umkm/{id}/layanan/{layananId}', [PelakuLayananController::class, 'update'])->name('dashboard.pelaku.layanan.update');
    Route::delete('/dashboard/umkm/{id}/layanan/{layananId}', [PelakuLayananController::class, 'destroy'])->name('dashboard.pelaku.layanan.destroy');

    // Bazar Saya & Pelatihan Saya
    Route::get('/dashboard/bazar-saya', [PelakuBazarController::class, 'index'])->name('dashboard.pelaku.bazar');
    Route::get('/dashboard/pelatihan-saya', [PelakuPelatihanController::class, 'index'])->name('dashboard.pelaku.pelatihan');

    // Pengaturan Akun
    Route::get('/dashboard/akun', [PelakuAkunController::class, 'edit'])->name('dashboard.pelaku.akun');
    Route::put('/dashboard/akun', [PelakuAkunController::class, 'update'])->name('dashboard.pelaku.akun.update');
    Route::put('/dashboard/akun/password', [PelakuAkunController::class, 'updatePassword'])->name('dashboard.pelaku.akun.password');
});

/*
|--------------------------------------------------------------------------
| Protected: Superadmin Panel (Guard: web, role: superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [SuperAdminController::class, 'updateUserRole'])->name('users.role');
    Route::get('/admins', [SuperAdminController::class, 'admins'])->name('admins');
});

/*
|--------------------------------------------------------------------------
| Protected: Admin Panel Dinas (Guard: web, role: admin,superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/klaim/{id}', [AdminDashboardController::class, 'showKlaim'])->name('klaim.show');
    Route::post('/klaim/{id}/approve', [AdminDashboardController::class, 'approveKlaim'])->name('klaim.approve');
    Route::post('/klaim/{id}/reject', [AdminDashboardController::class, 'rejectKlaim'])->name('klaim.reject');

    // Web CSV Uploader
    Route::get('/import', [AdminDashboardController::class, 'showImportForm'])->name('import');
    Route::post('/import', [AdminDashboardController::class, 'processImport'])->name('import.process');

    // CRUD Kategori
    Route::get('/kategori', [AdminKategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [AdminKategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [AdminKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [AdminKategoriController::class, 'destroy'])->name('kategori.destroy');

    // Moderasi UMKM
    Route::get('/umkm', [AdminUmkmController::class, 'index'])->name('umkm.index');
    Route::get('/umkm/{id}', [AdminUmkmController::class, 'show'])->name('umkm.show');
    Route::patch('/umkm/{id}/status', [AdminUmkmController::class, 'updateStatus'])->name('umkm.status');
    Route::post('/umkm/{id}/verify', [AdminUmkmController::class, 'verify'])->name('umkm.verify');

    // Aktivitas Sistem
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Manajemen Hero Banner Slider
    Route::get('/hero-slides', [AdminHeroSlideController::class, 'index'])->name('hero-slides.index');
    Route::post('/hero-slides', [AdminHeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::post('/hero-slides/{id}/toggle', [AdminHeroSlideController::class, 'toggle'])->name('hero-slides.toggle');
    Route::delete('/hero-slides/{id}', [AdminHeroSlideController::class, 'destroy'])->name('hero-slides.destroy');

    // CRUD Berita & Pengumuman
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');
    Route::post('/berita/{id}/toggle', [AdminBeritaController::class, 'togglePublish'])->name('berita.toggle');

    // CRUD News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');
    Route::post('/news/{news}/toggle', [AdminNewsController::class, 'toggleStatus'])->name('news.toggle');

    // CRUD Event
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/toggle', [AdminEventController::class, 'toggleStatus'])->name('events.toggle');
    Route::get('/events/{event}/participants', [EventParticipantController::class, 'index'])->name('events.participants');
    Route::patch('/events/{event}/participants/{participant}/status', [EventParticipantController::class, 'updateStatus'])->name('events.participants.status');

    // CRUD Bazar & Peserta
    Route::get('/bazar', [AdminBazarController::class, 'index'])->name('bazar.index');
    Route::get('/bazar/create', [AdminBazarController::class, 'create'])->name('bazar.create');
    Route::post('/bazar', [AdminBazarController::class, 'store'])->name('bazar.store');
    Route::get('/bazar/{id}/edit', [AdminBazarController::class, 'edit'])->name('bazar.edit');
    Route::put('/bazar/{id}', [AdminBazarController::class, 'update'])->name('bazar.update');
    Route::delete('/bazar/{id}', [AdminBazarController::class, 'destroy'])->name('bazar.destroy');
    Route::get('/bazar/{id}/peserta', [AdminBazarPesertaController::class, 'index'])->name('bazar.peserta');
    Route::post('/bazar-peserta/{id}/approve', [AdminBazarPesertaController::class, 'approve'])->name('bazar.peserta.approve');
    Route::post('/bazar-peserta/{id}/reject', [AdminBazarPesertaController::class, 'reject'])->name('bazar.peserta.reject');

    // CRUD Pelatihan & Peserta
    Route::get('/pelatihan', [AdminPelatihanController::class, 'index'])->name('pelatihan.index');
    Route::get('/pelatihan/create', [AdminPelatihanController::class, 'create'])->name('pelatihan.create');
    Route::post('/pelatihan', [AdminPelatihanController::class, 'store'])->name('pelatihan.store');
    Route::get('/pelatihan/{id}/edit', [AdminPelatihanController::class, 'edit'])->name('pelatihan.edit');
    Route::put('/pelatihan/{id}', [AdminPelatihanController::class, 'update'])->name('pelatihan.update');
    Route::delete('/pelatihan/{id}', [AdminPelatihanController::class, 'destroy'])->name('pelatihan.destroy');
    Route::get('/pelatihan/{id}/peserta', [AdminPelatihanPesertaController::class, 'index'])->name('pelatihan.peserta');
    Route::post('/pelatihan-peserta/{id}/status', [AdminPelatihanPesertaController::class, 'updateStatus'])->name('pelatihan.peserta.status');

    // Master Data Lokasi (Kecamatan, Kelurahan, Peta)
    // Kecamatan
    Route::get('/lokasi/kecamatan', [AdminLokasiController::class, 'kecamatanIndex'])->name('lokasi.kecamatan.index');
    Route::get('/lokasi/kecamatan/create', [AdminLokasiController::class, 'createKecamatan'])->name('lokasi.kecamatan.create');
    Route::post('/lokasi/kecamatan', [AdminLokasiController::class, 'storeKecamatan'])->name('lokasi.kecamatan.store');
    Route::get('/lokasi/kecamatan/{kecamatan}/edit', [AdminLokasiController::class, 'editKecamatan'])->name('lokasi.kecamatan.edit');
    Route::put('/lokasi/kecamatan/{kecamatan}', [AdminLokasiController::class, 'updateKecamatan'])->name('lokasi.kecamatan.update');
    Route::delete('/lokasi/kecamatan/{kecamatan}', [AdminLokasiController::class, 'destroyKecamatan'])->name('lokasi.kecamatan.destroy');

    // Kelurahan
    Route::get('/lokasi/kelurahan', [AdminLokasiController::class, 'kelurahanIndex'])->name('lokasi.kelurahan.index');
    Route::get('/lokasi/kelurahan/create', [AdminLokasiController::class, 'createKelurahan'])->name('lokasi.kelurahan.create');
    Route::post('/lokasi/kelurahan', [AdminLokasiController::class, 'storeKelurahan'])->name('lokasi.kelurahan.store');
    Route::get('/lokasi/kelurahan/{kelurahan}/edit', [AdminLokasiController::class, 'editKelurahan'])->name('lokasi.kelurahan.edit');
    Route::put('/lokasi/kelurahan/{kelurahan}', [AdminLokasiController::class, 'updateKelurahan'])->name('lokasi.kelurahan.update');
    Route::delete('/lokasi/kelurahan/{kelurahan}', [AdminLokasiController::class, 'destroyKelurahan'])->name('lokasi.kelurahan.destroy');

    // Peta Files
    Route::get('/lokasi/peta', [AdminLokasiController::class, 'petaIndex'])->name('lokasi.peta.index');
    Route::post('/lokasi/peta', [AdminLokasiController::class, 'uploadPeta'])->name('lokasi.peta.upload');
    Route::delete('/lokasi/peta/{peta}', [AdminLokasiController::class, 'destroyPeta'])->name('lokasi.peta.destroy');

    // Import kelurahan via admin UI
    Route::get('/lokasi/kelurahan/import', [AdminLokasiController::class, 'showKelurahanImportForm'])->name('lokasi.kelurahan.import');
    Route::post('/lokasi/kelurahan/import', [AdminLokasiController::class, 'processKelurahanImport'])->name('lokasi.kelurahan.import.process');
    Route::get('/lokasi/kelurahan/import/report/{filename}', [AdminLokasiController::class, 'downloadImportReport'])->name('lokasi.kelurahan.import.report');

    // UMKM mapping UI: show UMKM yang kecamatan sudah ada tapi kelurahan belum, allow admin to assign
    Route::get('/umkm/mapping', [\App\Http\Controllers\UmkmMappingController::class, 'index'])->name('umkm.mapping.index');
    Route::post('/umkm/mapping/{id}/assign', [\App\Http\Controllers\UmkmMappingController::class, 'assign'])->name('umkm.mapping.assign');
    // Create kelurahan + assign in one step (AJAX)
    Route::post('/umkm/mapping/{id}/kelurahan-create', [\App\Http\Controllers\UmkmMappingController::class, 'storeKelurahanAndAssign'])->name('umkm.mapping.kelurahan_create');

    // Manajemen Pelaku Usaha
    Route::get('/pelaku-usaha', [AdminPelakuUsahaController::class, 'index'])->name('pelaku-usaha.index');
    Route::get('/pelaku-usaha/{id}', [AdminPelakuUsahaController::class, 'show'])->name('pelaku-usaha.show');
    Route::patch('/pelaku-usaha/{id}/status', [AdminPelakuUsahaController::class, 'updateStatus'])->name('pelaku-usaha.status');

    // Moderasi Ulasan
    Route::get('/review', [AdminReviewController::class, 'index'])->name('review.index');
    Route::delete('/review/{id}', [AdminReviewController::class, 'destroy'])->name('review.destroy');

    // Survey Kepuasan
    Route::get('/survey', [AdminSurveyController::class, 'index'])->name('survey.index');
});
