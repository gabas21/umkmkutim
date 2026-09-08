<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminUmkmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\KecamatanBoundaryController;
use App\Http\Controllers\Api\UmkmClusterController;
use App\Http\Controllers\Api\UmkmMapController;
use App\Http\Controllers\BazarController;
use App\Http\Controllers\BazarPesertaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KlaimController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PelatihanPesertaController;
use App\Http\Controllers\PelakuUsahaDashboardController;
use App\Http\Controllers\PetaController;
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
Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');
Route::post('/umkm/{slug}/review', [ReviewController::class, 'store'])->name('umkm.review.store');
Route::get('/api/umkm/nearby', [UmkmController::class, 'nearby'])->name('api.umkm.nearby');
Route::get('/api/umkm/viewport', [UmkmMapController::class, 'viewport'])->name('api.umkm.viewport');
Route::get('/api/umkm/clusters', [UmkmClusterController::class, 'index'])->name('api.umkm.clusters');
Route::get('/api/kutim/kecamatan-boundaries', [KecamatanBoundaryController::class, 'index'])->name('api.kutim.boundaries');


// Modul Bazar & Expo
Route::get('/bazar', [BazarController::class, 'index'])->name('bazar.index');
Route::get('/bazar/{slug}', [BazarController::class, 'show'])->name('bazar.show');
Route::post('/bazar/{slug}/daftar', [BazarPesertaController::class, 'store'])->name('bazar.daftar');

// Modul Pelatihan & Pendampingan
Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/pelatihan/{slug}', [PelatihanController::class, 'show'])->name('pelatihan.show');
Route::post('/pelatihan/{slug}/daftar', [PelatihanPesertaController::class, 'store'])->name('pelatihan.daftar');

// Modul Laporan Transparansi Publik & Dashboard
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// Modul Survey Kepuasan Layanan
Route::get('/survey', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

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
});

/*
|--------------------------------------------------------------------------
| Protected: Admin Panel Dinas (Guard: web, role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::patch('/umkm/{id}/status', [AdminUmkmController::class, 'updateStatus'])->name('umkm.status');
});
