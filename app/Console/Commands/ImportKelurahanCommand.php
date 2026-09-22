<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use League\Csv\Reader;
use League\Csv\Writer;

class ImportKelurahanCommand extends Command
{
    protected $signature = 'import:kelurahan {--file= : Path to CSV file} {--dry-run : Do not create records, only report}';

    protected $description = 'Import kelurahan from CSV. CSV headers: name,kecamatan (kecamatan name)';

    public function handle()
    {
        $file = $this->option('file');
        $dry = $this->option('dry-run');

        if (!$file) {
            $this->error('Please provide --file=path/to/file.csv');
            return 1;
        }

        $fs = new Filesystem;
        if (!$fs->exists($file)) {
            $this->error('File not found: '.$file);
            return 1;
        }

        $this->info('Reading CSV: '.$file);

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error('Unable to open file: '.$file);
            return 1;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            $this->error('CSV file appears empty or invalid header');
            fclose($handle);
            return 1;
        }

        $map = [];
        foreach ($header as $i => $h) {
            $map[strtolower(trim($h))] = $i;
        }

        $created = 0;
        $skipped = 0;
        $failures = [];

        while (($row = fgetcsv($handle)) !== false) {
            $name = isset($map['name']) ? trim($row[$map['name']] ?? '') : trim($row[$map['kelurahan']] ?? '');
            $kecName = isset($map['kecamatan']) ? trim($row[$map['kecamatan']] ?? '') : trim($row[$map['kecamatan_name']] ?? '');

            if ($name === '' || $kecName === '') {
                $failures[] = ['name' => $name, 'kecamatan' => $kecName, 'reason' => 'missing_name_or_kecamatan'];
                continue;
            }

            // find kecamatan by normalized name
            $kec = Kecamatan::whereRaw('LOWER(name) = ?', [strtolower($kecName)])->first();
            if (!$kec) {
                // try contains
                $kec = Kecamatan::where('name', 'like', "%{$kecName}%")->first();
            }

            if (!$kec) {
                $failures[] = ['name' => $name, 'kecamatan' => $kecName, 'reason' => 'kecamatan_not_found'];
                continue;
            }

            $exists = Kelurahan::where('kecamatan_id', $kec->id)->whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
            if ($exists) {
                $skipped++;
                continue;
            }

            if (!$dry) {
                Kelurahan::create([
                    'kecamatan_id' => $kec->id,
                    'name' => $name,
                    'geojson' => null,
                ]);
                $created++;
            }
        }

        fclose($handle);

        $this->info("Processed: created={$created}, skipped={$skipped}, failures=".count($failures));

        // write failures report
        if (count($failures) > 0) {
            $outPath = storage_path('app/import_reports/kelurahan_import_failures_'.date('Ymd_His').'.csv');
            // Ensure dir
            $fs->ensureDirectoryExists(dirname($outPath));

            $outHandle = fopen($outPath, 'w+');
            fputcsv($outHandle, ['name', 'kecamatan', 'reason']);
            foreach ($failures as $f) {
                fputcsv($outHandle, [$f['name'], $f['kecamatan'], $f['reason']]);
            }
            fclose($outHandle);

            $this->info('Failure report written to: '.$outPath);
        }

        return 0;
    }
}
