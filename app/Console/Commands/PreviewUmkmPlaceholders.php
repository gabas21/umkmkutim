<?php

namespace App\Console\Commands;

use App\Models\Umkm;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PreviewUmkmPlaceholders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umkm:preview-placeholders {--save : Save generated SVG placeholders to storage/app/public/placeholders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List UMKM images and generate placeholder SVG previews (optionally save files)';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $save = $this->option('save') ? true : false;

        $this->info('Scanning UMKM records...');

        $umkms = Umkm::with('kategori')->get();

        if ($umkms->isEmpty()) {
            $this->line('No UMKM found.');

            return 0;
        }

        $this->line("ID\tNama Usaha\tKategori\tCurrent Image\tPreview URL\n");

        $publicDir = storage_path('app/public/placeholders');
        if ($save && ! $this->files->isDirectory($publicDir)) {
            $this->files->makeDirectory($publicDir, 0755, true);
        }

        foreach ($umkms as $umkm) {
            $id = $umkm->id;
            $name = $umkm->nama_usaha ?? $umkm->nama ?? '(no-name)';
            $kategori = $umkm->kategori->nama ?? 'Produk';
            $currentImage = $umkm->image ?? '';

            // Generate SVG content using same logic as PlaceholderImageController
            $svg = $this->generateSvgFor($kategori, $name);

            $previewPath = null;
            if ($save) {
                $filename = "umkm_{$id}.svg";
                $full = $publicDir.DIRECTORY_SEPARATOR.$filename;
                $this->files->put($full, $svg);
                $previewPath = url("storage/placeholders/{$filename}");
            } else {
                // Provide dynamic route URL so user can preview in browser without saving
                try {
                    $routeUrl = route('placeholder.image', ['category' => $kategori]);
                    $previewPath = $routeUrl.'?label='.urlencode($name);
                } catch (\Exception $e) {
                    // route may not be available in CLI if url generator not configured
                    $previewPath = '[run with --save to produce files or view dynamic route in browser]';
                }
            }

            $this->line("{$id}\t{$name}\t{$kategori}\t{$currentImage}\t{$previewPath}");
        }

        $this->info('\nDone.');
        if (! $save) {
            $this->line('To create preview files under storage/app/public/placeholders, re-run with --save');
            $this->line('Then run: php artisan storage:link  (if public/storage not linked)');
        }

        return 0;
    }

    protected function generateSvgFor(string $category, ?string $label = null): string
    {
        $label = $label ?? $category;
        $seed = $label;
        $safeSeed = htmlspecialchars($seed, ENT_QUOTES, 'UTF-8');

        $hash = md5($safeSeed);
        $bg = '#'.substr($hash, 0, 6);

        $r = hexdec(substr($bg, 1, 2));
        $g = hexdec(substr($bg, 3, 2));
        $b = hexdec(substr($bg, 5, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $textColor = $luminance > 0.6 ? '#111111' : '#ffffff';

        // Try to pick a custom SVG icon based on category first, then label
        $searchSource = mb_strtolower(trim(($category ?? '').' '.($label ?? '')));

        // Reuse the same richer icon set as PlaceholderImageController
        $icons = [
            'kopi' => '<g transform="translate(10,10)">'
                .'<path d="M20 14c-6 0-10 4-10 10s4 10 10 10 10-4 10-10-4-10-10-10z" fill="{fill}" />'
                .'<path d="M32 24c0 6-6 10-12 10s-12-4-12-10" fill="none" stroke="#ffffff" stroke-width="2" opacity="0.15" />'
                .'<rect x="14" y="6" width="12" height="10" rx="3" fill="#ffffff" opacity="0.12" />'
                .'<path d="M38 10c2 4-2 8-6 8" stroke="#ffffff" stroke-width="2" fill="none" stroke-linecap="round" />'
                .'</g>',
            'kopisusu' => '<g transform="translate(10,10)">'.'<path d="M20 14c-6 0-10 4-10 10s4 10 10 10 10-4 10-10-4-10-10-10z" fill="{fill}" />'.'<rect x="14" y="6" width="12" height="10" rx="3" fill="#ffffff" opacity="0.18" />'.'</g>',
            'teh' => '<g transform="translate(10,10)">'
                .'<path d="M18 10c-4 0-8 3-8 8s4 12 14 12 14-8 14-12-6-8-8-8h-12z" fill="{fill}" />'
                .'<path d="M26 8c0-2 4-4 6-4" stroke="#ffffff" stroke-width="1.5" fill="none" opacity="0.9" />'
                .'</g>',
            'roti' => '<g transform="translate(10,10)">'
                .'<ellipse cx="26" cy="26" rx="16" ry="10" fill="{fill}" />'
                .'<path d="M14 22c6-6 28-6 34 0" stroke="#ffffff" stroke-width="1.8" fill="none" opacity="0.12" />'
                .'</g>',
            'kue' => '<g transform="translate(10,10)">'
                .'<rect x="12" y="18" width="28" height="14" rx="6" fill="{fill}" />'
                .'<path d="M18 18c4-8 20-8 28 0" fill="#ffffff" opacity="0.14" />'
                .'</g>',
            'gorengan' => '<g transform="translate(10,10)">'.'<ellipse cx="26" cy="26" rx="16" ry="10" fill="{fill}" />'.'<path d="M20 20c6-6 18-6 24 0" stroke="#ffffff" stroke-width="1.6" fill="none" opacity="0.12"/>'.'</g>',
            'mie' => '<g transform="translate(10,10)">'
                .'<path d="M10 36c8-12 32-12 40 0" stroke="{fill}" stroke-width="3" fill="none" stroke-linecap="round" />'
                .'<rect x="14" y="34" width="28" height="6" rx="3" fill="{fill}" />'
                .'</g>',
            'sate' => '<g transform="translate(10,6)">'
                .'<rect x="34" y="4" width="4" height="44" rx="2" fill="{fill}" />'
                .'<ellipse cx="22" cy="30" rx="12" ry="8" fill="{fill}" />'
                .'</g>',
            'ikan' => '<g transform="translate(10,10)">'
                .'<ellipse cx="26" cy="26" rx="16" ry="10" fill="{fill}" />'
                .'<polygon points="42,26 54,20 54,32" fill="{fill}" />'
                .'<circle cx="20" cy="24" r="2" fill="#ffffff" />'
                .'</g>',
            'ayam' => '<g transform="translate(10,8)">'
                .'<path d="M12 30c0-10 8-18 18-18s18 8 18 18c0 8-8 12-18 12s-18-4-18-12z" fill="{fill}" />'
                .'<circle cx="40" cy="36" r="3" fill="#ffffff" />'
                .'</g>',
            'buah' => '<g transform="translate(10,10)">'
                .'<circle cx="26" cy="22" r="12" fill="{fill}" />'
                .'<path d="M32 10c-4 2-8 0-10 0" stroke="#ffffff" stroke-width="1.6" fill="none" />'
                .'</g>',
            'sayur' => '<g transform="translate(10,8)">'
                .'<path d="M26 12c-8 4-12 12-12 20 0 0 8-4 12-4s12 4 12 4c0-8-4-16-12-20z" fill="{fill}" />'
                .'</g>',
            'minuman' => '<g transform="translate(10,8)">'
                .'<rect x="22" y="8" width="12" height="28" rx="3" fill="{fill}" />'
                .'<rect x="20" y="36" width="16" height="6" rx="3" fill="{fill}" opacity="0.9" />'
                .'</g>',
            'baju' => '<g transform="translate(10,8)">'
                .'<path d="M12 12l8-6 10 6 8 2v22h-36v-22z" fill="{fill}" />'
                .'</g>',
            'kaos' => '<g transform="translate(10,8)">'
                .'<path d="M12 12l8-6 10 6 8 2v22h-36v-22z" fill="{fill}" />'
                .'</g>',
            'pakaian' => '<g transform="translate(10,8)">'.'<path d="M12 12l8-6 10 6 8 2v22h-36v-22z" fill="{fill}"/>'.'</g>',
            'batik' => '<g transform="translate(10,8)">'.'<path d="M12 12l8-6 10 6 8 2v22h-36v-22z" fill="{fill}"/>'.'</g>',
            'sepatu' => '<g transform="translate(10,8)">'
                .'<rect x="12" y="28" width="32" height="10" rx="4" fill="{fill}" />'
                .'<path d="M44 24c-6-6-20-6-28 0" stroke="#ffffff" stroke-width="1.6" fill="none" />'
                .'</g>',
            'tas' => '<g transform="translate(10,8)">'
                .'<rect x="12" y="12" width="28" height="26" rx="4" fill="{fill}" />'
                .'<path d="M18 12a8 8 0 0 1 16 0" stroke="#ffffff" stroke-width="1.6" fill="none" />'
                .'</g>',
            'perhiasan' => '<g transform="translate(10,8)">'
                .'<circle cx="26" cy="26" r="12" fill="{fill}" />'
                .'<circle cx="32" cy="18" r="3" fill="#ffffff" />'
                .'</g>',
            'souvenir' => '<g transform="translate(10,8)">'
                .'<rect x="14" y="12" width="20" height="20" rx="3" fill="{fill}" />'
                .'<path d="M24 12v20" stroke="#ffffff" stroke-width="1.6" />'
                .'</g>',
            'kerajinan' => '<g transform="translate(10,8)">'
                .'<path d="M20 12l12 0-6 24-12 0z" fill="{fill}" />'
                .'<circle cx="28" cy="24" r="2" fill="#ffffff" />'
                .'</g>',
            'sabun' => '<g transform="translate(10,10)">'
                .'<rect x="14" y="18" width="28" height="12" rx="4" fill="{fill}" />'
                .'<circle cx="22" cy="20" r="1.6" fill="#ffffff" />'
                .'</g>',
        ];

        $iconSvg = null;
        foreach ($icons as $k => $snippet) {
            if (mb_strpos($searchSource, $k) !== false) {
                $iconSvg = str_replace('{fill}', $textColor, $snippet);
                break;
            }
        }

        $title = htmlspecialchars(mb_strimwidth($label ?: $category, 0, 30, ''), ENT_QUOTES, 'UTF-8');

        if ($iconSvg) {
            $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1200\" height=\"800\" viewBox=\"0 0 120 80\" preserveAspectRatio=\"xMidYMid meet\">\n".
                "  <rect width=\"100%\" height=\"100%\" fill=\"{$bg}\" />\n".
                "  {$iconSvg}\n".
                "  <text x=\"50%\" y=\"78%\" font-family=\"-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial\" fill=\"{$textColor}\" text-anchor=\"middle\" font-size=\"8\">{$title}</text>\n".
                "</svg>\n";
        } else {
            $sourceForInitials = $label ?: $category;
            $parts = preg_split('/\s+/', trim($sourceForInitials));
            $initials = '';
            for ($i = 0; $i < min(2, count($parts)); $i++) {
                $initials .= mb_substr($parts[$i], 0, 1);
            }
            $initials = mb_strtoupper($initials ?: mb_substr($sourceForInitials, 0, 2));

            $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1200\" height=\"800\" viewBox=\"0 0 120 80\" preserveAspectRatio=\"xMidYMid meet\">\n".
                "  <rect width=\"100%\" height=\"100%\" fill=\"{$bg}\" />\n".
                "  <text x=\"50%\" y=\"50%\" font-family=\"-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial\" fill=\"{$textColor}\" text-anchor=\"middle\" font-size=\"28\" font-weight=\"700\">{$initials}</text>\n".
                "  <text x=\"50%\" y=\"78%\" font-family=\"-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial\" fill=\"{$textColor}\" text-anchor=\"middle\" font-size=\"8\">{$title}</text>\n".
                "</svg>\n";
        }

        return $svg;
    }
}
