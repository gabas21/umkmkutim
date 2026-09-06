<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            ['nama' => 'Kuliner & Makanan Khas', 'icon' => 'utensils'],
            ['nama' => 'Kriya & Kerajinan Tradisional', 'icon' => 'palette'],
            ['nama' => 'Batik & Fashion Khas Kutim', 'icon' => 'shirt'],
            ['nama' => 'Agribisnis & Hasil Bumi', 'icon' => 'sprout'],
            ['nama' => 'Kelautan & Perikanan', 'icon' => 'fish'],
            ['nama' => 'Jasa & Percetakan', 'icon' => 'briefcase'],
            ['nama' => 'Perdagangan & Kelontong', 'icon' => 'shopping-bag'],
            ['nama' => 'Kesehatan & Herbal Dayak', 'icon' => 'heart-pulse'],
        ];

        foreach ($kategoriList as $item) {
            Kategori::firstOrCreate(
                ['slug' => Str::slug($item['nama'])],
                [
                    'nama' => $item['nama'],
                    'icon' => $item['icon'],
                ]
            );
        }
    }
}
