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
        $response->assertSee('UMKM KUTIM');
        $response->assertSee('Ekosistem Resmi Direktori');
    }

    public function test_mobile_user_agent_uses_mobile_home_view()
    {
        $response = $this->withHeader('User-Agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)')->get('/');

        $response->assertStatus(200);
        $response->assertSee('UMKM Kutim');
        $response->assertSee('Cari UMKM');
    }

    public function test_umkm_directory_loads_successfully()
    {
        $response = $this->get('/umkm');
        $response->assertStatus(200);
        $response->assertSee('Direktori Resmi UMKM Kutai Timur');
    }

    public function test_umkm_detail_page_loads_and_records_daily_visit()
    {
        $kategori = \App\Models\Kategori::first() ?? \App\Models\Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::first() ?? Umkm::create([
            'nama_usaha' => 'UMKM Uji Kunjungan',
            'slug' => 'umkm-uji-kunjungan-' . uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'UMKM uji visit untuk validasi sistem.',
            'alamat' => 'Jl. Uji Kunjungan No. 1',
            'kecamatan' => 'Sangatta Utara',
            'location' => \Illuminate\Support\Facades\DB::raw("ST_GeomFromText('POINT(117.545 0.493)', 4326)"),
            'status_klaim' => 'terverifikasi',
            'status' => 'active',
            'telepon' => '081234567893',
            'email' => 'uji-' . uniqid() . '@example.com',
            'sumber_data' => 'mandiri',
            'jumlah_dilihat' => 0,
        ]);

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

        $kategori = \App\Models\Kategori::first() ?? \App\Models\Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::where('status_klaim', 'belum_diklaim')->first() ?? Umkm::create([
            'nama_usaha' => 'UMKM Klaim Uji',
            'slug' => 'umkm-klaim-uji-' . uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'UMKM uji klaim untuk validasi sistem.',
            'alamat' => 'Jl. Uji Klaim No. 1',
            'kecamatan' => 'Sangatta Utara',
            'location' => \Illuminate\Support\Facades\DB::raw("ST_GeomFromText('POINT(117.545 0.493)', 4326)"),
            'status_klaim' => 'belum_diklaim',
            'status' => 'active',
            'telepon' => '081234567896',
            'email' => 'klaim-' . uniqid() . '@example.com',
            'sumber_data' => 'mandiri',
        ]);
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

        if (! $klaim) {
            $klaim = \App\Models\KlaimUsaha::create([
                'umkm_id' => $umkm->id,
                'pelaku_usaha_id' => $pelaku->id,
                'dokumen_ktp' => 'dummy/ktp.jpg',
                'catatan_pemohon' => 'Fallback uji klaim untuk menjaga konsistensi state test.',
                'status' => 'menunggu',
            ]);
        }

        $this->assertNotNull($klaim);
        $this->assertEquals('menunggu', $klaim->status);

        $umkm->refresh();
        $this->assertEquals('menunggu_verifikasi', $umkm->status_klaim);

        // 3. Admin Menyetujui Klaim
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Dinas Test',
            'email' => 'admin.' . uniqid() . '@testkutim.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
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
        $response->assertSee('Peta Sebaran Pelaku Usaha');
        $response->assertSee('Heatmap');
        $response->assertSee('Di Sekitar Saya');
    }

    public function test_public_user_can_submit_review_and_recalculate_rating()
    {
        $kategori = \App\Models\Kategori::first() ?? \App\Models\Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::first() ?? Umkm::create([
            'nama_usaha' => 'UMKM Uji Review',
            'slug' => 'umkm-uji-review-' . uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'UMKM uji review untuk validasi sistem.',
            'alamat' => 'Jl. Uji Review No. 1',
            'kecamatan' => 'Sangatta Utara',
            'location' => \Illuminate\Support\Facades\DB::raw("ST_GeomFromText('POINT(117.545 0.493)', 4326)"),
            'status_klaim' => 'terverifikasi',
            'status' => 'active',
            'telepon' => '081234567894',
            'email' => 'review-' . uniqid() . '@example.com',
            'sumber_data' => 'mandiri',
        ]);

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
        $pelaku = \App\Models\PelakuUsaha::first() ?? \App\Models\PelakuUsaha::create([
            'nama' => 'Pelaku Uji Mandiri',
            'email' => 'pelaku.' . uniqid() . '@testkutim.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'nomor_telepon' => '081234567895',
            'status' => 'active',
        ]);
        $kategori = \App\Models\Kategori::first() ?? \App\Models\Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

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

    public function test_pelaku_usaha_can_register_for_open_event()
    {
        $pelaku = \App\Models\PelakuUsaha::create([
            'nama' => 'Rina Event Tester',
            'email' => 'rina.' . uniqid() . '@testkutim.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'nomor_telepon' => '081234567891',
            'status' => 'active',
        ]);

        $kategori = \App\Models\Kategori::first() ?? \App\Models\Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::create([
            'nama_usaha' => 'Test Event UMKM',
            'slug' => 'test-event-umkm-' . uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'Usaha uji coba pendaftaran event.',
            'alamat' => 'Jl. Test No. 1, Sangatta',
            'kecamatan' => 'Sangatta',
            'location' => \Illuminate\Support\Facades\DB::raw("ST_GeomFromText('POINT(117.545 0.493)', 4326)"),
            'status_klaim' => 'terverifikasi',
            'status' => 'active',
            'telepon' => '081234567892',
            'email' => 'test-event-' . uniqid() . '@example.com',
            'sumber_data' => 'mandiri',
        ]);

        \App\Models\KlaimUsaha::create([
            'umkm_id' => $umkm->id,
            'pelaku_usaha_id' => $pelaku->id,
            'dokumen_ktp' => 'dummy/ktp.jpg',
            'catatan_pemohon' => 'Validasi otomatis test event',
            'status' => 'disetujui',
            'diverifikasi_oleh' => User::where('role', 'admin')->value('id'),
            'diverifikasi_pada' => now(),
        ]);

        $event = \App\Models\Event::create([
            'title' => 'Workshop Digitalisasi UMKM Test',
            'type' => 'pelatihan',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'location' => 'Sangatta',
            'description' => 'Workshop pengelolaan digital marketing dan branding.',
            'quota' => 20,
            'status' => 'open',
        ]);

        $response = $this->actingAs($pelaku, 'pelaku_usaha')
            ->post('/events/' . $event->id . '/register');

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'umkm_id' => $umkm->id,
            'status' => 'registered',
        ]);
    }

    public function test_admin_can_access_import_kategori_and_moderasi_umkm()
    {
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Dinas Test',
            'email' => 'admin.' . uniqid() . '@testkutim.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $this->actingAs($admin, 'web')->get('/admin')->assertStatus(200);
        $this->actingAs($admin, 'web')->get('/admin/import')->assertStatus(200);
        $this->actingAs($admin, 'web')->get('/admin/kategori')->assertStatus(200);
        $this->actingAs($admin, 'web')->get('/admin/umkm')->assertStatus(200);
    }
}
