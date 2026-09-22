<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use Illuminate\Console\Command;

class ImportKecamatanFromGeojsonCommand extends Command
{
    protected $signature = 'import:kecamatan-from-geojson {--file= : Path to geojson file relative to public/geojson or absolute} {--dry-run : Do not modify DB, only show summary}';

    protected $description = 'Import or update kecamatans table from a GeoJSON file (default: public/geojson/kutim-kecamatan.json)';

    public function handle()
    {
        $fileOpt = $this->option('file');
        $dry = $this->option('dry-run');

        $path = $fileOpt ? (file_exists($fileOpt) ? $fileOpt : public_path(ltrim($fileOpt, '/'))) : public_path('geojson/kutim-kecamatan.json');

        if (!file_exists($path)) {
            $this->error("GeoJSON file not found: {$path}");
            return 1;
        }

        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        if (!is_array($data) || empty($data['features'])) {
            $this->error('Invalid GeoJSON or no features found');
            return 1;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($data['features'] as $feature) {
            $props = $feature['properties'] ?? [];
            $geom = $feature['geometry'] ?? null;

            $name = trim($props['name'] ?? $props['kecamatan'] ?? '');
            $code = trim($props['kode_kec'] ?? $props['kode'] ?? $props['kode_kc'] ?? '');
            $center = $props['center'] ?? null;

            if ($name === '') {
                $this->line('Skipping feature with empty name');
                $skipped++;
                continue;
            }

            // find by code first
            $kecamatan = null;
            if ($code !== '') {
                $kecamatan = Kecamatan::where('code', $code)->first();
            }

            // fallback: find by normalized name
            if (!$kecamatan) {
                $normalized = mb_strtolower(preg_replace('/[^a-z0-9]+/iu', '', $name));
                $kecamatan = Kecamatan::get()->first(function ($k) use ($normalized) {
                    $n = mb_strtolower(preg_replace('/[^a-z0-9]+/iu', '', $k->name));
                    return $n === $normalized || strpos($n, $normalized) !== false || strpos($normalized, $n) !== false;
                });
            }

            $geojson_store = json_encode([
                'type' => 'Feature',
                'properties' => $props,
                'geometry' => $geom,
            ], JSON_UNESCAPED_UNICODE);

            if ($kecamatan) {
                $kecamatan->name = $name;
                if ($code !== '') $kecamatan->code = $code;
                $kecamatan->geojson = $geojson_store;
                $kecamatan->save();
                $updated++;
                $this->line("Updated kecamatan: {$name} (id={$kecamatan->id})");
            } else {
                if (!$dry) {
                    $k = Kecamatan::create([
                        'name' => $name,
                        'code' => $code ?: null,
                        'geojson' => $geojson_store,
                        'peta_file_id' => null,
                    ]);
                    $created++;
                    $this->line("Created kecamatan: {$name} (id={$k->id})");
                } else {
                    $created++;
                    $this->line("Would create kecamatan: {$name} (dry-run)");
                }
            }
        }

        $this->info("Done. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}");

        return 0;
    }
}
