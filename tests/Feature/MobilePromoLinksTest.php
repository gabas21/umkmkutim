<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MobilePromoLinksTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mobile_promo_uses_umkm_detail_links_when_promo_exists(): void
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
            'deskripsi' => 'Diskon 20% untuk pelanggan harian.',
            'alamat' => 'Jl. Mulawarman No. 12, Sangatta',
            'kecamatan' => 'Sangatta Utara',
            'location' => Umkm::makePoint(-0.5, 117.7),
            'status' => 'active',
            'status_klaim' => 'belum_diklaim',
            'sumber_data' => 'mandiri',
        ]);

        $response = $this->get(route('preview.mobile.promo'));

        $response->assertOk();
        $this->assertStringContainsString('/preview/mobile/umkm/', $response->getContent());
        $this->assertStringContainsString('Lihat', $response->getContent());
    }

    public function test_mobile_umkm_detail_shows_discounted_prices_with_original_strikethrough(): void
    {
        $kategori = Kategori::first() ?? Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::create([
            'nama_usaha' => 'Kopi Lestari',
            'slug' => 'kopi-lestari-discount-'.uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'Diskon 20% untuk pelanggan harian dan gratis ongkir.',
            'alamat' => 'Jl. Mulawarman No. 12, Sangatta',
            'kecamatan' => 'Sangatta Utara',
            'location' => Umkm::makePoint(-0.5, 117.7),
            'status' => 'active',
            'status_klaim' => 'belum_diklaim',
            'sumber_data' => 'mandiri',
        ]);

        $response = $this->get(route('preview.mobile.umkm.detail', ['slug' => $umkm->slug]));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('line-through', $content);
        $this->assertStringContainsString('data-original-price=', $content);
        $this->assertTrue(strpos($content, 'Promo') < strpos($content, 'Katalog produk'), 'Promo harus tampil sebelum katalog produk.');
    }

    public function test_mobile_umkm_detail_shows_promo_banner_before_catalog(): void
    {
        $kategori = Kategori::first() ?? Kategori::create([
            'nama' => 'Kuliner',
            'slug' => 'kuliner',
            'icon' => 'utensils',
        ]);

        $umkm = Umkm::create([
            'nama_usaha' => 'Kopi Lestari',
            'slug' => 'kopi-lestari-detail-'.uniqid(),
            'kategori_id' => $kategori->id,
            'deskripsi' => 'Diskon 20% untuk pelanggan harian dan gratis ongkir.',
            'alamat' => 'Jl. Mulawarman No. 12, Sangatta',
            'kecamatan' => 'Sangatta Utara',
            'location' => Umkm::makePoint(-0.5, 117.7),
            'status' => 'active',
            'status_klaim' => 'belum_diklaim',
            'sumber_data' => 'mandiri',
        ]);

        $response = $this->get(route('preview.mobile.umkm.detail', ['slug' => $umkm->slug]));

        $response->assertOk();
        $content = $response->getContent();
        $promoPosition = strpos($content, 'Promo '.$umkm->nama_usaha);
        $catalogPosition = strpos($content, 'Katalog produk');

        $this->assertNotFalse($promoPosition);
        $this->assertNotFalse($catalogPosition);
        $this->assertTrue($promoPosition < $catalogPosition, 'Promo harus tampil sebelum katalog produk.');
    }
}
