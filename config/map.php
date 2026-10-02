<?php

$tileProvider = env('MAP_TILE_PROVIDER', 'osm');
$tileKey = env('MAP_TILE_KEY', '');

if (($tileProvider === 'mapbox' || $tileProvider === 'maptiler') && empty($tileKey)) {
    $tileProvider = 'osm';
}

return [
    /*
    |--------------------------------------------------------------------------
    | Map Tile Provider Configuration
    |--------------------------------------------------------------------------
    |
    | provider: 'osm' (default), 'mapbox', or 'maptiler'
    | key: API key for Mapbox or MapTiler
    | style: MapTiler style name (e.g. streets, basic)
    | mapbox_style: Mapbox style id (e.g. mapbox/streets-v11 or username/style-id)
    |
    */

    'provider' => $tileProvider,
    'key' => $tileKey,
    'style' => env('MAP_TILE_STYLE', 'streets'),
    'mapbox_style' => env('MAPBOX_STYLE', 'mapbox/streets-v11'),
];
