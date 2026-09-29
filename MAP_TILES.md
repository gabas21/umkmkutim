Map tile provider configuration and deployment

This document explains how the application selects map tile providers and how to enable/configure them for production. All map settings live in config/map.php and read their defaults from environment variables.

Files
- config/map.php — central configuration for map tile provider
- .env (and .env.example) — environment variables used by config/map.php
- resources/views/mobile/peta.blade.php — client code now reads config('map.*') values

Environment variables (examples)
- MAP_TILE_PROVIDER: 'osm' (default), 'mapbox', or 'maptiler'
- MAP_TILE_KEY: API key for Mapbox or MapTiler (leave empty to use OSM)
- MAP_TILE_STYLE: MapTiler style name (e.g. streets)
- MAPBOX_STYLE: Mapbox style id (e.g. mapbox/streets-v11 or username/style-id)

Example (.env)

# Use MapTiler
MAP_TILE_PROVIDER=maptiler
MAP_TILE_KEY=YOUR_MAPTILER_KEY
MAP_TILE_STYLE=streets

# Or use Mapbox
# MAP_TILE_PROVIDER=mapbox
# MAP_TILE_KEY=pk.xxx
# MAPBOX_STYLE=mapbox/streets-v11

Deployment: using config cache in production

1. Ensure the environment variables above are set in the production environment (or in the server's .env file) BEFORE caching config.
2. On deploy, run the following commands as part of the release process:

   php artisan config:clear
   php artisan config:cache
   php artisan route:cache   # optional, if you use route caching
   php artisan view:clear

Notes and important warnings
- Once you run php artisan config:cache, Laravel will no longer read .env at runtime for config values. Any changes to .env after caching will not be reflected until you re-run php artisan config:cache (or php artisan config:clear).
- Do not call env() directly from application code outside of config files. The repository already centralizes map settings in config/map.php which reads env() only at config compile time.
- If you plan to use Mapbox/MapTiler for production traffic, register and obtain API keys from the provider and pick the appropriate style. OpenStreetMap tile server (tile.openstreetmap.org) forbids high-volume non-personal use — use a paid provider or your own tile server for heavy traffic.

Optional automation (recommended)
- Add the config:cache steps to your deployment script (CI/CD). Example (GitHub Actions / deploy script):

  - name: Clear config
    run: php artisan config:clear
  - name: Cache config
    run: php artisan config:cache

Testing locally
- If you change .env locally, run php artisan config:clear to ensure Blade views pick up the new values while developing.

If you want, I can:
- Add the config cache commands to a deploy script or GitHub Actions workflow.
- Add a short README section or update README.md with this guidance.
- Validate that no other files call env() at runtime (I already checked common occurrences for MAP_TILE_ keys).
