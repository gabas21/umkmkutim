<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Umkm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapUmkmLocationCommand extends Command
{
    protected $signature = 'umkm:map-locations {--dry-run : Do not write updates to database} {--limit= : Limit number of UMKM to process}';

    protected $description = 'Map existing UMKM kecamatan/kelurahan string fields to master kecamatan_id/kelurahan_id';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int)$this->option('limit') : null;

        $this->info('Mulai mapping lokasi UMKM ke master kecamatan/kelurahan');

        $query = Umkm::query()->where(function ($q) {
            $q->whereNull('kecamatan_id')->orWhereNull('kelurahan_id');
        });

        if ($limit) {
            $query->limit($limit);
        }

        $total = $query->count();
        if ($total === 0) {
            $this->info('Tidak ada record yang perlu diproses.');
            return Command::SUCCESS;
        }

        $this->output->progressStart($total);

        // Preload master data and create normalized map
        $kecamatans = Kecamatan::all();
        $kecamatanMap = [];
        foreach ($kecamatans as $k) {
            $kecamatanMap[$this->normalize($k->name)] = $k;
        }

        $kelurahans = Kelurahan::all();
        $kelurahanMap = [];
        foreach ($kelurahans as $kel) {
            $kelurahanMap[$this->normalize($kel->name)][] = $kel; // multiple kelurahans with same normalized name possible
        }

        $failures = [];
        $updated = 0;

        $cursor = $query->cursor();
        foreach ($cursor as $umkm) {
            $origKec = trim((string)$umkm->kecamatan);
            $origKel = trim((string)$umkm->kelurahan_desa);

            $foundKec = null;
            $foundKel = null;

            // Try match kecamatan by normalized exact
            if ($origKec !== '') {
                $normKec = $this->normalize($origKec);
                if (isset($kecamatanMap[$normKec])) {
                    $foundKec = $kecamatanMap[$normKec];
                } else {
                    // try partial contains
                    foreach ($kecamatans as $k) {
                        if (Str::contains($this->normalize($k->name), $normKec) || Str::contains($normKec, $this->normalize($k->name))) {
                            $foundKec = $k; break;
                        }
                    }
                }
            }

            // Try match kelurahan
            if ($origKel !== '') {
                $normKel = $this->normalize($origKel);
                if (isset($kelurahanMap[$normKel])) {
                    $candidates = $kelurahanMap[$normKel];
                    if ($foundKec) {
                        // prefer kelurahan in same kecamatan
                        foreach ($candidates as $cand) {
                            if ($cand->kecamatan_id == $foundKec->id) {
                                $foundKel = $cand; break;
                            }
                        }
                    }
                    if (!$foundKel) {
                        // take first candidate
                        $foundKel = $candidates[0];
                    }
                } else {
                    // fuzzy search through all kelurahans
                    foreach ($kelurahans as $kel) {
                        if (Str::contains($this->normalize($kel->name), $normKel) || Str::contains($normKel, $this->normalize($kel->name))) {
                            $foundKel = $kel; break;
                        }
                    }
                }
            }

            $note = [];
            if ($foundKec) {
                $note[] = 'kecamatan_matched';
            } else {
                $note[] = 'kecamatan_not_matched';
            }
            if ($foundKel) {
                $note[] = 'kelurahan_matched';
            } else {
                $note[] = 'kelurahan_not_matched';
            }

            if (!$dryRun) {
                $changed = false;
                if ($foundKec && $umkm->kecamatan_id !== $foundKec->id) {
                    $umkm->kecamatan_id = $foundKec->id; $changed = true;
                    // keep human-readable column in sync
                    $umkm->kecamatan = $foundKec->name;
                }
                if ($foundKel && $umkm->kelurahan_id !== $foundKel->id) {
                    $umkm->kelurahan_id = $foundKel->id; $changed = true;
                    $umkm->kelurahan_desa = $foundKel->name;
                }
                if ($changed) {
                    $umkm->save();
                    $updated++;
                }
            }

            if (!$foundKec || !$foundKel) {
                $failures[] = [
                    'umkm_id' => $umkm->id,
                    'nama_usaha' => $umkm->nama_usaha,
                    'kecamatan' => $origKec,
                    'kelurahan_desa' => $origKel,
                    'matched_kecamatan_id' => $foundKec ? $foundKec->id : null,
                    'matched_kelurahan_id' => $foundKel ? $foundKel->id : null,
                    'note' => implode(';', $note),
                ];
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info("Selesai. Updated: {$updated}. Failures: " . count($failures));

        if (!empty($failures)) {
            $timestamp = now()->format('Ymd_His');
            $relPath = "import_reports/umkm_location_mapping_failures_{$timestamp}.csv";
            $fullPath = storage_path('app/' . $relPath);

            // ensure directory
            @mkdir(dirname($fullPath), 0755, true);

            $handle = fopen($fullPath, 'w');
            fputcsv($handle, array_keys($failures[0]));
            foreach ($failures as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);

            $this->info("Daftar kegagalan disimpan di: storage/app/{$relPath}");
        }

        return Command::SUCCESS;
    }

    protected function normalize(string $value): string
    {
        $v = trim(Str::lower($value));
        // remove punctuation and diacritics
        $v = preg_replace('/[\p{P}\p{S}]/u', ' ', $v);
        // collapse spaces
        $v = preg_replace('/\s+/', ' ', $v);
        // remove non-alphanumeric except space
        $v = preg_replace('/[^a-z0-9\s]/u', '', $v);
        return trim($v);
    }
}
