<?php

namespace Tests\Unit;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kategori;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmkmApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_umkm_list_supports_search_and_pagination(): void
    {
        $kecamatan = Kecamatan::create([
            'name' => 'Sangatta Utara',
            'code' => 'KUU',
        ]);

        $kelurahan = Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Teluk Lingga',
            'code' => 'TL',
        ]);

        $kategori = Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        Umkm::create([
            'nama_usaha' => 'Kopi Nusantara',
            'slug' => 'kopi-nusantara',
            'kategori_id' => $kategori->id,
            'kecamatan' => $kecamatan->name,
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_desa' => $kelurahan->name,
            'kelurahan_id' => $kelurahan->id,
            'alamat' => 'Jl. Merdeka No. 1',
            'status' => 'active',
            'status_klaim' => 'terverifikasi',
            'location' => \DB::raw("ST_GeomFromText('POINT(117.3731 0.2408)', 4326)"),
        ]);

        $response = $this->getJson('/api/public/umkm?search=Kopi&per_page=1');

        $response->assertOk();
        $response->assertJsonPath('data.0.nama_usaha', 'Kopi Nusantara');
        $response->assertJsonPath('per_page', 1);
    }

    public function test_admin_verify_endpoint_updates_umkm_claim_status(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $kecamatan = Kecamatan::create([
            'name' => 'Sangatta Selatan',
            'code' => 'KUS',
        ]);

        $kategori = Kategori::create([
            'nama' => 'Fashion',
            'slug' => 'fashion',
            'icon' => 'shirt',
        ]);

        $umkm = Umkm::create([
            'nama_usaha' => 'Toko Batik Jaya',
            'slug' => 'toko-batik-jaya',
            'kategori_id' => $kategori->id,
            'kecamatan' => $kecamatan->name,
            'kecamatan_id' => $kecamatan->id,
            'alamat' => 'Jl. Raya',
            'status' => 'active',
            'status_klaim' => 'menunggu_verifikasi',
            'location' => \DB::raw("ST_GeomFromText('POINT(117.3900 0.2500)', 4326)"),
        ]);

        $this->actingAs($user, 'web');

        $response = $this->putJson('/api/admin/umkm/' . $umkm->id . '/verify', [
            'status' => 'verified',
            'catatan' => 'Data valid',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('umkm', [
            'id' => $umkm->id,
            'status_klaim' => 'terverifikasi',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'module' => 'umkm',
            'action' => 'verify',
            'user_id' => $user->id,
        ]);
    }
}
