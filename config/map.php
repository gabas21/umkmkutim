<?php

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

    'provider' => env('MAP_TILE_PROVIDER', 'osm'),
    'key' => env('MAP_TILE_KEY', ''),
    'style' => env('MAP_TILE_STYLE', 'streets'),
    'mapbox_style' => env('MAPBOX_STYLE', 'mapbox/streets-v11'),
];
