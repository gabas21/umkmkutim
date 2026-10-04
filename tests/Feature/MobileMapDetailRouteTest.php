<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MobileMapDetailRouteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mobile_map_points_link_to_mobile_detail_page(): void
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

        $response = $this->getJson(route('api.umkm.viewport', [
            'zoom' => 15,
            'sw_lat' => -1,
            'sw_lng' => 117.5,
            'ne_lat' => 0,
            'ne_lng' => 118,
        ]));

        $response->assertOk();
        $response->assertJsonFragment([
            'url' => route('preview.mobile.umkm.detail', ['slug' => $umkm->slug]),
        ]);
    }
}
