<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'nama_usaha',
        'slug',
        'kategori_id',
        'deskripsi',
        'alamat',
        'kecamatan',
        'kecamatan_id',
        'kelurahan_desa',
        'kelurahan_id',
        'location',
        'telepon',
        'email',
        'instagram',
        'website',
        'foto_utama',
        'foto_galeri',
        'jam_operasional',
        'rating',
        'jumlah_review',
        'jumlah_dilihat',
        'sumber_data',
        'status_klaim',
        'status',
    ];

    protected $hidden = [
        'location',
    ];

    protected $casts = [
        'foto_galeri' => 'array',
        'jam_operasional' => 'array',
        'rating' => 'decimal:2',
        'jumlah_review' => 'integer',
        'jumlah_dilihat' => 'integer',
    ];

    // Relations
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function klaimUsaha()
    {
        return $this->hasMany(KlaimUsaha::class, 'umkm_id');
    }

    public function klaimAktif()
    {
        return $this->hasOne(KlaimUsaha::class, 'umkm_id')->where('status', 'disetujui')->latest();
    }

    public function layanan()
    {
        return $this->hasMany(LayananUsaha::class, 'umkm_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'umkm_id');
    }

    public function laporanKunjungan()
    {
        return $this->hasMany(LaporanKunjungan::class, 'umkm_id');
    }

    public function documents()
    {
        return $this->hasMany(UmkmDocument::class, 'umkm_id');
    }

    public function photos()
    {
        return $this->hasMany(UmkmPhoto::class, 'umkm_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class, 'umkm_id');
    }

    public function latestVerification()
    {
        return $this->hasOne(Verification::class, 'umkm_id')->latestOfMany();
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithCoordinates($query)
    {
        return $query->selectRaw(sprintf(
            'umkm.*, %s as latitude, %s as longitude',
            static::latitudeExpression(),
            static::longitudeExpression()
        ));
    }

    public function scopeNearby($query, $lat, $lng, $maxMeters = 10000)
    {
        $latitudeExpression = static::latitudeExpression();
        $longitudeExpression = static::longitudeExpression();
        $pointExpression = static::geomFromTextExpression();
        $pointWkt = static::pointWkt($lat, $lng);

        return $query->selectRaw(
            "umkm.*, {$latitudeExpression} as latitude, {$longitudeExpression} as longitude, ST_Distance_Sphere(location, {$pointExpression}) AS jarak_meter",
            [$pointWkt]
        )
            ->whereRaw("ST_Distance_Sphere(location, {$pointExpression}) <= ?", [$pointWkt, $maxMeters])
            ->orderBy('jarak_meter');
    }

    public function scopeKecamatan($query, $kecamatan)
    {
        if (! empty($kecamatan)) {
            return $query->where('kecamatan', $kecamatan);
        }

        return $query;
    }

    public function scopeKategoriFilter($query, $kategoriId)
    {
        if (! empty($kategoriId)) {
            return $query->where('kategori_id', $kategoriId);
        }

        return $query;
    }

    /**
     * Build a SRID 4326 point expression for storing in the `location` column.
     */
    public static function makePoint(float|string $lat, float|string $lng): Expression
    {
        return DB::raw(static::geomFromTextExpression("'".static::pointWkt($lat, $lng)."'"));
    }

    /**
     * WKT for a point, always written as "POINT(lng lat)".
     */
    public static function pointWkt(float|string $lat, float|string $lng): string
    {
        return sprintf('POINT(%.8F %.8F)', (float) $lng, (float) $lat);
    }

    /**
     * ST_GeomFromText() for SRID 4326 that reads WKT as "lng lat" on every database.
     *
     * @param  string  $wkt  SQL fragment producing the WKT (a "?" binding by default)
     */
    public static function geomFromTextExpression(string $wkt = '?'): string
    {
        return static::usesGeographicAxisOrder()
            ? "ST_GeomFromText({$wkt}, 4326, 'axis-order=long-lat')"
            : "ST_GeomFromText({$wkt}, 4326)";
    }

    public static function latitudeExpression(string $column = 'location'): string
    {
        return static::usesGeographicAxisOrder() ? "ST_Latitude({$column})" : "ST_Y({$column})";
    }

    public static function longitudeExpression(string $column = 'location'): string
    {
        return static::usesGeographicAxisOrder() ? "ST_Longitude({$column})" : "ST_X({$column})";
    }

    /**
     * MySQL 8+ treats SRID 4326 points as latitude-first, so ST_X/ST_Y are swapped
     * compared to MariaDB / MySQL 5.7.
     */
    protected static function usesGeographicAxisOrder(): bool
    {
        $connection = DB::connection();

        if ($connection->getDriverName() !== 'mysql' || $connection->isMaria()) {
            return false;
        }

        return version_compare($connection->getServerVersion(), '8.0.0', '>=');
    }
}
