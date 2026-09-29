<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlaceholderImageController extends Controller
{
    /**
     * Return a simple SVG placeholder image based on the category name.
     *
     * @param  string  $category
     * @return Response
     */
    public function show(Request $request, $category)
    {
        // Decode inputs
        $category = urldecode($category);
        $label = $request->query('label', null);

        $seed = $label ? $label : $category;
        $safeSeed = htmlspecialchars($seed, ENT_QUOTES, 'UTF-8');

        // Derive background color from seed (product name preferred)
        $hash = md5($safeSeed);
        $bg = '#'.substr($hash, 0, 6);

        // Choose a contrasting text color based on luminance
        $r = hexdec(substr($bg, 1, 2));
        $g = hexdec(substr($bg, 3, 2));
        $b = hexdec(substr($bg, 5, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $textColor = $luminance > 0.6 ? '#111111' : '#ffffff';

        // Try to pick a custom SVG icon based on category first, then label
        $searchSource = mb_strtolower(trim(($category ?? '').' '.($label ?? '')));

        // SVG snippet library (use {fill} placeholder for the icon color)
        // Replaced with more descriptive vector icons centered in the canvas.
        $icons = [
            // coffee & beverage
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

            // food & bakery
            'roti' => '<g transform="translate(10,10)">'
                .'<ellipse cx="26" cy="26" rx="16" ry="10" fill="{fill}" />'
                .'<path d="M14 22c6-6 28-6 34 0" stroke="#ffffff" stroke-width="1.8" fill="none" opacity="0.12" />'
                .'</g>',
            'kue' => '<g transform="translate(10,10)">'
                .'<rect x="12" y="18" width="28" height="14" rx="6" fill="{fill}" />'
                .'<path d="M18 18c4-8 20-8 28 0" fill="#ffffff" opacity="0.14" />'
                .'</g>',
            'gorengan' => '<g transform="translate(10,10)">'.'<ellipse cx="26" cy="26" rx="16" ry="10" fill="{fill}" />'.'<path d="M20 20c6-6 18-6 24 0" stroke="#ffffff" stroke-width="1.6" fill="none" opacity="0.12"/>'.'</g>',

            // savory
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

            // produce & groceries
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

            // fashion & accessories
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

            // crafts & other
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
            'souvenir' => '<g transform="translate(10,8)">'.'<rect x="14" y="12" width="20" height="20" rx="3" fill="{fill}" />'.'<path d="M24 12v20" stroke="#ffffff" stroke-width="1.6" />'.'</g>',
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
            // Compose SVG using the icon snippet
            $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 120 80" preserveAspectRatio="xMidYMid meet">
  <rect width="100%" height="100%" fill="{$bg}" />
  {$iconSvg}
  <text x="50%" y="78%" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial" fill="{$textColor}" text-anchor="middle" font-size="8">{$title}</text>
</svg>
SVG;
        } else {
            // Fallback to initials text
            $sourceForInitials = $label ?: $category;
            $parts = preg_split('/\s+/', trim($sourceForInitials));
            $initials = '';
            for ($i = 0; $i < min(2, count($parts)); $i++) {
                $initials .= mb_substr($parts[$i], 0, 1);
            }
            $initials = mb_strtoupper($initials ?: mb_substr($sourceForInitials, 0, 2));

            $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 120 80" preserveAspectRatio="xMidYMid meet">
  <rect width="100%" height="100%" fill="{$bg}" />
  <text x="50%" y="50%" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial" fill="{$textColor}" text-anchor="middle" font-size="28" font-weight="700">{$initials}</text>
  <text x="50%" y="78%" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial" fill="{$textColor}" text-anchor="middle" font-size="8">{$title}</text>
</svg>
SVG;
        }

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
}
