<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MobileUmkmDetailMapTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mobile_detail_page_displays_location_map_for_umkm_with_coordinates(): void
    {
        $kategori = Kategori::first() ?? Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::create([
            'nama_usaha' => 'Kopi Lestari',
            'slug' => 'kopi-lestari-'.uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'Kopi lokal berkualitas dari Kutim.',
            'alamat' => 'Jl. Mulawarman No. 12, Sangatta',
            'kecamatan' => 'Sangatta Utara',
            'location' => Umkm::makePoint(-0.5, 117.7),
            'status' => 'active',
            'status_klaim' => 'belum_diklaim',
            'sumber_data' => 'mandiri',
        ]);

        $response = $this->get(route('preview.mobile.umkm.detail', ['slug' => $umkm->slug]));

        $response->assertOk();
        $response->assertSee('Lokasi usaha');
        $response->assertSee('google.com/maps');
        $response->assertSee('output=embed');
    }
}
