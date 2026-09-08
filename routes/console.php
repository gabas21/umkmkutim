<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan pembaruan grid cluster UMKM setiap jam sesuai IMPLEMENTASI_PETA_UMKM_1.md
Schedule::command('umkm:refresh-grid')->hourly();

