<?php

namespace Tests\Feature;

use App\Models\LaporanKunjungan;
use App\Models\Umkm;
use App\Models\User;
use Tests\TestCase;

class UmkmKutimTest extends TestCase
{
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('UMKM Kutai Timur');
        $response->assertSee('Direktori Terpadu');
    }

    public function test_umkm_directory_loads_successfully()
    {
        $response = $this->get('/umkm');
        $response->assertStatus(200);
        $response->assertSee('Direktori UMKM Kutai Timur');
    }

    public function test_umkm_detail_page_loads_and_records_daily_visit()
    {
        $umkm = Umkm::first();
        $this->assertNotNull($umkm);

        $initialViews = $umkm->jumlah_dilihat;

        $response = $this->get('/umkm/' . $umkm->slug);
        $response->assertStatus(200);
        $response->assertSee($umkm->nama_usaha);

        // Verifikasi kunjungan tercatat di database
        $this->assertDatabaseHas('laporan_kunjungan', [
            'umkm_id' => $umkm->id,
            'tanggal' => now()->toDateString(),
        ]);

        $umkm->refresh();
        $this->assertGreaterThan($initialViews, $umkm->jumlah_dilihat);
    }

    public function test_nearby_api_returns_spatial_results()
    {
        // Koordinat Sangatta (0.493, 117.545)
        $response = $this->getJson('/api/umkm/nearby?lat=0.493&lng=117.545&radius=10000');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'count',
            'data' => [
                '*' => ['id', 'nama_usaha', 'slug', 'kecamatan', 'lat', 'lng', 'jarak_meter', 'rating']
            ]
        ]);
        $response->assertJson(['status' => 'success']);
    }

    public function test_login_and_register_pages_are_accessible()
    {
        $this->get('/login')->assertStatus(200)->assertSee('Masuk Pelaku Usaha');
        $this->get('/register')->assertStatus(200)->assertSee('Pendaftaran Akun');
        $this->get('/admin/login')->assertStatus(200)->assertSee('Login Admin Dinas');
    }

    public function test_pelaku_usaha_can_submit_claim_and_admin_can_approve()
    {
        // 1. Buat Pelaku Usaha
        $pelaku = \App\Models\PelakuUsaha::create([
            'nama' => 'H. Ahmad Fauzi',
            'email' => 'ahmad.' . uniqid() . '@testkutim.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'nomor_telepon' => '081234567890',
            'status' => 'active',
        ]);

        $umkm = Umkm::where('status_klaim', 'belum_diklaim')->first();
        $this->assertNotNull($umkm);

        // 2. Submit Klaim
        \Illuminate\Support\Facades\Storage::fake('public');
        $ktpFile = \Illuminate\Http\UploadedFile::fake()->create('ktp_pemilik.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($pelaku, 'pelaku_usaha')
            ->post('/umkm/' . $umkm->slug . '/klaim', [
                'dokumen_ktp' => $ktpFile,
                'catatan_pemohon' => 'Klaim usaha keluarga sejak 2018.',
            ]);

        $response->assertRedirect(route('dashboard.pelaku'));

        $klaim = \App\Models\KlaimUsaha::where('umkm_id', $umkm->id)
            ->where('pelaku_usaha_id', $pelaku->id)
            ->first();

        $this->assertNotNull($klaim);
        $this->assertEquals('menunggu', $klaim->status);

        $umkm->refresh();
        $this->assertEquals('menunggu_verifikasi', $umkm->status_klaim);

        // 3. Admin Menyetujui Klaim
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $approveResponse = $this->actingAs($admin, 'web')
            ->post('/admin/klaim/' . $klaim->id . '/approve', [
                'catatan_admin' => 'Dokumen KTP valid dan terkonfirmasi.',
            ]);

        $approveResponse->assertRedirect(route('admin.dashboard'));

        $klaim->refresh();
        $umkm->refresh();

        $this->assertEquals('disetujui', $klaim->status);
        $this->assertEquals('terverifikasi', $umkm->status_klaim);
    }

    public function test_peta_page_loads_and_displays_map_data()
    {
        $response = $this->get('/peta');
        $response->assertStatus(200);
        $response->assertSee('Peta Sebaran UMKM Kutai Timur');
        $response->assertSee('Mode Heatmap');
        $response->assertSee('Di Sekitar Saya');
    }

    public function test_public_user_can_submit_review_and_recalculate_rating()
    {
        $umkm = Umkm::first();
        $this->assertNotNull($umkm);

        $response = $this->post('/umkm/' . $umkm->slug . '/review', [
            'nama_reviewer' => 'Dewi Lestari',
            'rating' => 5,
            'komentar' => 'Produk sangat berkualitas dan kemasan rapi!',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('review', [
            'umkm_id' => $umkm->id,
            'nama_reviewer' => 'Dewi Lestari',
            'rating' => 5,
        ]);

        $umkm->refresh();
        $this->assertGreaterThan(0, $umkm->jumlah_review);
    }

    public function test_pelaku_usaha_can_register_new_umkm_mandiri()
    {
        $pelaku = \App\Models\PelakuUsaha::first();
        $kategori = \App\Models\Kategori::first();

        \Illuminate\Support\Facades\Storage::fake('public');
        $ktpFile = \Illuminate\Http\UploadedFile::fake()->create('ktp_baru.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($pelaku, 'pelaku_usaha')
            ->post('/umkm-daftar/baru', [
                'nama_usaha' => 'Kopi Robusta Sangatta Mandiri',
                'kategori_id' => $kategori->id,
                'deskripsi' => 'Kopi petik merah lokal berkualitas tinggi.',
                'alamat' => 'Jl. Pendidikan No. 88, Sangatta Utara',
                'kecamatan' => 'Sangatta Utara',
                'latitude' => 0.4935,
                'longitude' => 117.5455,
                'telepon' => '081234567899',
                'dokumen_ktp' => $ktpFile,
            ]);

        $response->assertRedirect(route('dashboard.pelaku'));

        $this->assertDatabaseHas('umkm', [
            'nama_usaha' => 'Kopi Robusta Sangatta Mandiri',
            'sumber_data' => 'mandiri',
            'status_klaim' => 'menunggu_verifikasi',
        ]);
    }

    public function test_admin_can_access_import_kategori_and_moderasi_umkm()
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin, 'web')->get('/admin/import')->assertStatus(200)->assertSee('Import Data Massal Dinas');
        $this->actingAs($admin, 'web')->get('/admin/kategori')->assertStatus(200)->assertSee('Manajemen Sektor');
        $this->actingAs($admin, 'web')->get('/admin/umkm')->assertStatus(200)->assertSee('Moderasi & Pengawasan Data UMKM', false);
    }
}
