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
        'kelurahan_desa',
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

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithCoordinates($query)
    {
        return $query->selectRaw("umkm.*, ST_Latitude(location) as latitude, ST_Longitude(location) as longitude");
    }

    public function scopeNearby($query, $lat, $lng, $maxMeters = 10000)
    {
        return $query->selectRaw("umkm.*, ST_Latitude(location) as latitude, ST_Longitude(location) as longitude, ST_Distance_Sphere(location, ST_SRID(POINT(?, ?), 4326)) AS jarak_meter", [$lat, $lng])
            ->whereRaw("ST_Distance_Sphere(location, ST_SRID(POINT(?, ?), 4326)) <= ?", [$lat, $lng, $maxMeters])
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
        return DB::raw("ST_SRID(POINT({$lng}, {$lat}), 4326)");
    }
}
