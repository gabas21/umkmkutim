/**
 * fetch_kutim_kecamatan.js
 * Script resmi untuk mengunduh, memproses, dan mengekspor batas wilayah
 * 18 Kecamatan Kabupaten Kutai Timur sesuai standar BIG / BPS.
 *
 * Output:
 * 1. public/geojson/kutim-kecamatan.json & kutim_kecamatan.geojson
 * 2. kutim_kecamatan_import.sql
 */

import fs from 'fs';
import path from 'path';
import https from 'https';
import http from 'http';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// 1. Algoritma Simplifikasi Geometri (Douglas-Peucker) untuk web performance
function getSqSegDist(p, p1, p2) {
    let x = p1[0], y = p1[1];
    let dx = p2[0] - x, dy = p2[1] - y;

    if (dx !== 0 || dy !== 0) {
        const t = ((p[0] - x) * dx + (p[1] - y) * dy) / (dx * dx + dy * dy);
        if (t > 1) {
            x = p2[0];
            y = p2[1];
        } else if (t > 0) {
            x += dx * t;
            y += dy * t;
        }
    }

    dx = p[0] - x;
    dy = p[1] - y;
    return dx * dx + dy * dy;
}

function simplifyDPStep(points, first, last, sqTolerance, simplified) {
    let maxSqDist = sqTolerance;
    let index;

    for (let i = first + 1; i < last; i++) {
        const sqDist = getSqSegDist(points[i], points[first], points[last]);
        if (sqDist > maxSqDist) {
            index = i;
            maxSqDist = sqDist;
        }
    }

    if (maxSqDist > sqTolerance) {
        if (index - first > 1) simplifyDPStep(points, first, index, sqTolerance, simplified);
        simplified.push(points[index]);
        if (last - index > 1) simplifyDPStep(points, index, last, sqTolerance, simplified);
    }
}

function simplifyRing(ring, tolerance) {
    if (ring.length <= 4) return ring;
    const isClosed = ring[0][0] === ring[ring.length - 1][0] && ring[0][1] === ring[ring.length - 1][1];
    const sqTolerance = tolerance * tolerance;
    const last = ring.length - 1;
    const simplified = [ring[0]];
    simplifyDPStep(ring, 0, last, sqTolerance, simplified);
    simplified.push(ring[last]);
    if (simplified.length < 4) return ring;
    if (isClosed && (simplified[0][0] !== simplified[simplified.length - 1][0] || simplified[0][1] !== simplified[simplified.length - 1][1])) {
        simplified.push(simplified[0]);
    }
    return simplified;
}

function simplifyGeometry(geom, tolerance) {
    if (geom.type === 'Polygon') {
        return {
            type: 'Polygon',
            coordinates: geom.coordinates.map(ring => simplifyRing(ring, tolerance))
        };
    } else if (geom.type === 'MultiPolygon') {
        return {
            type: 'MultiPolygon',
            coordinates: geom.coordinates.map(poly => poly.map(ring => simplifyRing(ring, tolerance)))
        };
    }
    return geom;
}

// 2. Parser WKT ke GeoJSON
function parseCoordinates(coordText) {
    const points = coordText.split(',');
    const coords = [];
    for (let p of points) {
        const parts = p.trim().split(/\s+/);
        if (parts.length >= 2) {
            coords.push([parseFloat(parts[0]), parseFloat(parts[1])]);
        }
    }
    return coords;
}

function parsePolygonRings(polyText) {
    const rings = [];
    let depth = 0;
    let ringStart = -1;
    for (let i = 0; i < polyText.length; i++) {
        if (polyText[i] === '(') {
            depth++;
            if (depth === 1) ringStart = i;
        } else if (polyText[i] === ')') {
            depth--;
            if (depth === 0) {
                rings.push(parseCoordinates(polyText.slice(ringStart + 1, i).trim()));
            }
        }
    }
    return rings;
}

function parseWKT(wkt) {
    const isMulti = wkt.startsWith('MULTIPOLYGON');
    const isPoly = wkt.startsWith('POLYGON');
    if (!isMulti && !isPoly) throw new Error("Format WKT tidak didukung: " + wkt.substring(0, 30));

    let content = wkt.substring(wkt.indexOf('('));

    if (isMulti) {
        content = content.trim().slice(1, -1).trim();
        const polygons = [];
        let depth = 0;
        let polyStart = -1;
        for (let i = 0; i < content.length; i++) {
            if (content[i] === '(') {
                depth++;
                if (depth === 1) polyStart = i;
            } else if (content[i] === ')') {
                depth--;
                if (depth === 0) {
                    polygons.push(parsePolygonRings(content.slice(polyStart + 1, i).trim()));
                }
            }
        }
        return { type: "MultiPolygon", coordinates: polygons };
    } else {
        content = content.trim().slice(1, -1).trim();
        return { type: "Polygon", coordinates: parsePolygonRings(content) };
    }
}

function computeCentroid(geom) {
    let sumLng = 0, sumLat = 0, total = 0;
    function walk(c) {
        if (typeof c[0] === 'number') {
            sumLng += c[0];
            sumLat += c[1];
            total++;
        } else {
            for (let sub of c) walk(sub);
        }
    }
    walk(geom.coordinates);
    return total ? [sumLng / total, sumLat / total] : [0, 0];
}

// Convert WKT with 3D (z=0) to 2D WKT for MySQL ST_GeomFromText
function sanitizeWkt2D(wkt) {
    return wkt.replace(/(\d+\.\d+)\s+(-?\d+\.\d+)\s+0(?=[,\)])/g, '$1 $2');
}

// Fetch helper
function fetchUrl(urlStr) {
    return new Promise((resolve, reject) => {
        const client = urlStr.startsWith('https') ? https : http;
        const req = client.get(urlStr, {
            headers: { 'User-Agent': 'UMKMKutim-BoundaryFetch/1.0' },
            timeout: 30000
        }, (res) => {
            if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
                return resolve(fetchUrl(res.headers.location));
            }
            if (res.statusCode !== 200) {
                return reject(new Error(`HTTP ${res.statusCode}`));
            }
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(data));
        });
        req.on('error', reject);
        req.on('timeout', () => { req.destroy(); reject(new Error('TIMEOUT')); });
    });
}

async function main() {
    console.log("=== Mengunduh Data Batas Resmi Kecamatan Kutai Timur ===");

    const downloadUrl = 'https://server.geoit.dev/vendor/batas-admin/download.php?fid=64.08&column=kode_kk&table=kecamatan';
    let rawJson = null;

    try {
        console.log(`Mengambil data dari: ${downloadUrl}`);
        const text = await fetchUrl(downloadUrl);
        const parsed = JSON.parse(text);
        if (parsed && Array.isArray(parsed.data) && parsed.data.length >= 18) {
            rawJson = parsed;
            console.log(`✓ Berhasil mengunduh ${parsed.data.length} kecamatan dari server resmi!`);
        }
    } catch (e) {
        console.warn(`! Unduhan online gagal (${e.message}), mencoba fallback dari cache lokal...`);
    }

    if (!rawJson) {
        const backupPaths = [
            'C:/Users/ACER/.gemini/antigravity-ide/brain/9e19d5d9-c978-49d5-80da-d89c65772a2e/.system_generated/steps/332/content.md'
        ];
        for (let bp of backupPaths) {
            if (fs.existsSync(bp)) {
                const c = fs.readFileSync(bp, 'utf8');
                const idx = c.indexOf('{"code":200');
                if (idx !== -1) {
                    rawJson = JSON.parse(c.substring(idx));
                    console.log(`✓ Berhasil memuat ${rawJson.data.length} kecamatan dari cache lokal!`);
                    break;
                }
            }
        }
    }

    if (!rawJson || !Array.isArray(rawJson.data)) {
        console.error("Gagal mendapatkan data batas kecamatan!");
        process.exit(1);
    }

    const records = rawJson.data;
    console.log(`\nMemproses ${records.length} data batas kecamatan...`);

    const geojsonFeatures = [];
    const sqlStatements = [
        "-- Batas Resmi 18 Kecamatan Kabupaten Kutai Timur",
        "-- Sumber: Badan Informasi Geospasial (BIG) / BPS",
        "CREATE TABLE IF NOT EXISTS kecamatan_boundaries (",
        "    id INT AUTO_INCREMENT PRIMARY KEY,",
        "    nama_kecamatan VARCHAR(100) NOT NULL,",
        "    nama_kabupaten VARCHAR(100) NOT NULL,",
        "    kode_kecamatan VARCHAR(20) NULL,",
        "    geom GEOMETRY NOT NULL SRID 4326,",
        "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,",
        "    SPATIAL INDEX(geom)",
        ") ENGINE=InnoDB;",
        "",
        "TRUNCATE TABLE kecamatan_boundaries;",
        ""
    ];

    for (const item of records) {
        let rawName = item.kecamatan || item.nama || '';
        let normName = rawName.replace(/^Kecamatan\s+/i, '').trim();

        // Normalisasi nama lokal (Kombeng -> Kongbeng)
        if (normName.toLowerCase() === 'kombeng') {
            normName = 'Kongbeng';
        }

        const rawGeom = parseWKT(item.WKT_GEOMETRY);
        const centroid = computeCentroid(rawGeom);

        // Simplifikasi halus untuk rendering web cepat (akurasi ~22 meter)
        const simplifiedGeom = simplifyGeometry(rawGeom, 0.0002);

        geojsonFeatures.push({
            type: "Feature",
            properties: {
                name: normName,
                kecamatan: normName,
                kode_kec: item.kode_kec || item.KODE_KEC || '',
                kode_kk: "64.08",
                kab_kota: "Kutai Timur",
                provinsi: "Kalimantan Timur",
                center: [
                    parseFloat(centroid[0].toFixed(6)),
                    parseFloat(centroid[1].toFixed(6))
                ],
                source: "official_big_bps"
            },
            geometry: simplifiedGeom
        });

        // Buat SQL 2D WKT
        const cleanWkt2D = sanitizeWkt2D(item.WKT_GEOMETRY);
        const escapedWkt = cleanWkt2D.replace(/'/g, "''");
        sqlStatements.push(
            `INSERT INTO kecamatan_boundaries (nama_kecamatan, nama_kabupaten, kode_kecamatan, geom) ` +
            `VALUES ('${normName}', 'Kutai Timur', '${item.kode_kec || ''}', ST_GeomFromText('${escapedWkt}', 4326, 'axis-order=long-lat'));`
        );

        console.log(`  [✓] ${normName.padEnd(18)} : Kode=${item.kode_kec} | Centroid=[${centroid[1].toFixed(4)}, ${centroid[0].toFixed(4)}]`);
    }

    const geojson = {
        type: "FeatureCollection",
        name: "Batas 18 Kecamatan Kabupaten Kutai Timur",
        crs: {
            type: "name",
            properties: { name: "urn:ogc:def:crs:OGC:1.3:CRS84" }
        },
        features: geojsonFeatures
    };

    const geojsonContent = JSON.stringify(geojson, null, 2);

    // Tulis ke output paths
    const outputs = [
        path.join(__dirname, 'public/geojson/kutim-kecamatan.json'),
        path.join(__dirname, 'kutim_kecamatan.geojson'),
        path.join(__dirname, 'public/data/kutim_kecamatan.geojson'),
    ];

    for (let outPath of outputs) {
        const dir = path.dirname(outPath);
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
        fs.writeFileSync(outPath, geojsonContent, 'utf8');
        console.log(`Disimpan: ${outPath} (${(fs.statSync(outPath).size / 1024).toFixed(1)} KB)`);
    }

    // Tulis SQL file
    const sqlPath = path.join(__dirname, 'kutim_kecamatan_import.sql');
    fs.writeFileSync(sqlPath, sqlStatements.join('\n'), 'utf8');
    console.log(`Disimpan: ${sqlPath} (${(fs.statSync(sqlPath).size / 1024).toFixed(1)} KB)`);

    console.log("\n=== SELESAI! Seluruh 18 batas kecamatan resmi telah siap digunakan ===");
}

main().catch(err => {
    console.error("Error:", err);
    process.exit(1);
});
