<?php

namespace App\Console\Commands;

use App\Models\Umkm;
use Illuminate\Console\Command;

class ShowSampleUmkmCommand extends Command
{
    protected $signature = 'umkm:sample {limit=10}';
    protected $description = 'Show sample UMKM rows (id, nama_usaha, kecamatan, kelurahan_desa)';

    public function handle(): int
    {
        $limit = (int)$this->argument('limit');
        $rows = Umkm::orderBy('id')->limit($limit)->get(['id','nama_usaha','kecamatan','kelurahan_desa']);
        foreach ($rows as $r) {
            $this->line("{$r->id}|{$r->nama_usaha}|{$r->kecamatan}|{$r->kelurahan_desa}");
        }
        return Command::SUCCESS;
    }
}
