<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bazar extends Model
{
    use HasFactory;

    protected $table = 'bazars';

    protected $fillable = [
        'nama_bazar',
        'slug',
        'deskripsi',
        'lokasi',
        'kecamatan',
        'alamat_lengkap',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota_peserta',
        'banner_url',
        'status',
        'penyelenggara',
        'kontak_person',
        'fasilitas',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'kuota_peserta' => 'integer',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(BazarPeserta::class, 'bazar_id');
    }

    public function scopeActiveOrUpcoming($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing'])
                     ->orderBy('tanggal_mulai', 'asc');
    }

    public function getSisaKuotaAttribute(): int
    {
        $terdaftar = $this->peserta()->whereIn('status', ['pending', 'diterima'])->count();
        return max(0, $this->kuota_peserta - $terdaftar);
    }
}
