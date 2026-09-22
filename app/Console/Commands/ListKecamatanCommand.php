<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use Illuminate\Console\Command;

class ListKecamatanCommand extends Command
{
    protected $signature = 'umkm:list-kecamatan';
    protected $description = 'List all kecamatan names from master table';

    public function handle(): int
    {
        $kec = Kecamatan::orderBy('name')->get();
        foreach ($kec as $k) {
            $this->line($k->id . '|' . $k->name);
        }
        return Command::SUCCESS;
    }
}
