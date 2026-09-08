<?php

namespace App\Console\Commands;

use App\Jobs\ImportDinasUmkmJob;
use Illuminate\Console\Command;

class ImportDinasUmkmCommand extends Command
{
    protected $signature = 'umkm:import-dinas {file : Path file CSV yang akan diimport} {--sync : Jalankan langsung tanpa queue}';
    protected $description = 'Import data UMKM Dinas dari file CSV secara batch dan aman';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("Membuka file: {$filePath}");
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Gagal membuka file CSV.");
            return Command::FAILURE;
        }

        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            $this->error("Header CSV kosong.");
            fclose($handle);
            return Command::FAILURE;
        }

        $header = array_map(function ($col) {
            return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strtolower($col)));
        }, $header);

        $batch = [];
        $batchSize = 500;
        $totalRows = 0;
        $sync = $this->option('sync');

        $this->output->progressStart();

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) !== count($header)) {
                continue;
            }

            $row = array_combine($header, $data);
            $batch[] = $row;
            $totalRows++;

            if (count($batch) >= $batchSize) {
                if ($sync) {
                    (new ImportDinasUmkmJob($batch))->handle();
                } else {
                    ImportDinasUmkmJob::dispatch($batch);
                }
                $batch = [];
                $this->output->progressAdvance($batchSize);
            }
        }

        if (!empty($batch)) {
            if ($sync) {
                (new ImportDinasUmkmJob($batch))->handle();
            } else {
                ImportDinasUmkmJob::dispatch($batch);
            }
            $this->output->progressAdvance(count($batch));
        }

        $this->output->progressFinish();
        fclose($handle);

        $modeText = $sync ? 'secara langsung (synchronous)' : 'ke antrean Queue background';
        $this->info("Berhasil memproses {$totalRows} baris data {$modeText}.");

        // PATCH: kalau mode --sync (data langsung masuk DB, bukan lewat queue async),
        // refresh grid cluster + bump cache version LANGSUNG di sini supaya data baru
        // kelihatan di peta seketika, bukan nunggu jadwal umkm:refresh-grid jalan tiap
        // jam (routes/console.php). Kalau mode queue (non-sync), job masih jalan async
        // di background jadi refresh grid tetap ngikut jadwal biasa -- itu memang
        // trade-off yang wajar biar import besar nggak nge-block command ini.
        if ($sync && $totalRows > 0) {
            $this->info('Mode --sync terdeteksi, menjalankan umkm:refresh-grid supaya peta langsung update...');
            $this->call('umkm:refresh-grid');
        }

        return Command::SUCCESS;
    }
}
