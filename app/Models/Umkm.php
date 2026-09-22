<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        $pointWkt = sprintf('POINT(%s %s)', $lng, $lat);

        return $query->selectRaw(
            "umkm.*, {$latitudeExpression} as latitude, {$longitudeExpression} as longitude, ST_Distance_Sphere(location, ST_GeomFromText(?, 4326)) AS jarak_meter",
            [$pointWkt]
        )
            ->whereRaw('ST_Distance_Sphere(location, ST_GeomFromText(?, 4326)) <= ?', [$pointWkt, $maxMeters])
            ->orderBy('jarak_meter');
    }

    public function scopeKecamatan($query, $kecamatan)
    {
        if (!empty($kecamatan)) {
            return $query->where('kecamatan', $kecamatan);
        }
        return $query;
    }

    public function scopeKategoriFilter($query, $kategoriId)
    {
        if (!empty($kategoriId)) {
            return $query->where('kategori_id', $kategoriId);
        }
        return $query;
    }

    // Helper static method for Point geometry
    public static function makePoint($lat, $lng)
    {
        return DB::raw("ST_GeomFromText('POINT({$lng} {$lat})', 4326)");
    }

    public static function latitudeExpression(string $column = 'location'): string
    {
        return "ST_Y({$column})";
    }

    public static function longitudeExpression(string $column = 'location'): string
    {
        return "ST_X({$column})";
    }
}
