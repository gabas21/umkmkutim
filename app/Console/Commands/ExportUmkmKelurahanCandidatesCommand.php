<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use App\Models\Umkm;

class ExportUmkmKelurahanCandidatesCommand extends Command
{
    protected $signature = 'export:umkm-kelurahan {--out= : Output CSV path relative to storage/app} {--limit= : Limit number of rows to export} {--min-count=1 : Minimum occurrences to include} {--kecamatan_id= : Filter by kecamatan_id}';

    protected $description = 'Export unique kelurahan_desa candidates from umkm table for manual review. Outputs CSV with columns: kecamatan, kelurahan_desa, count';

    public function handle()
    {
        $out = $this->option('out') ?: 'import_reports/umkm_kelurahan_candidates_'.date('Ymd_His').'.csv';
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $minCount = (int) $this->option('min-count');
        $kecamatanId = $this->option('kecamatan_id');

        $this->info("Exporting UMKM kelurahan candidates to: {$out}");

        $query = DB::table((new Umkm)->getTable())
            ->select('kecamatan', 'kelurahan_desa', DB::raw('COUNT(*) as cnt'))
            ->groupBy('kecamatan', 'kelurahan_desa')
            ->havingRaw('cnt >= ?', [$minCount])
            ->orderByDesc('cnt');

        if ($kecamatanId) {
            $query->where('kecamatan_id', $kecamatanId);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $rows = $query->get();

        $fs = new Filesystem();
        $outPath = storage_path('app/'.ltrim($out, '/'));
        $fs->ensureDirectoryExists(dirname($outPath));

        $handle = fopen($outPath, 'w+');
        fputcsv($handle, ['kecamatan', 'kelurahan_desa', 'count']);

        foreach ($rows as $r) {
            fputcsv($handle, [$r->kecamatan, $r->kelurahan_desa, $r->cnt]);
        }

        fclose($handle);

        $this->info('Export completed. File: '.$outPath);
        $this->info('Rows exported: '.count($rows));

        return 0;
    }
}
