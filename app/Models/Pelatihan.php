<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihans';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'materi_ringkas',
        'penyelenggara',
        'instruktur',
        'lokasi',
        'mode',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'biaya',
        'link_zoom',
        'banner_url',
        'status',
        'syarat_peserta',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'kuota' => 'integer',
        'biaya' => 'decimal:2',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(PelatihanPeserta::class, 'pelatihan_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing'])
                     ->orderBy('tanggal_mulai', 'asc');
    }

    public function getSisaKuotaAttribute(): int
    {
        $terdaftar = $this->peserta()->count();
        return max(0, $this->kuota - $terdaftar);
    }

    public function getPersenTerisiAttribute(): int
    {
        if ($this->kuota <= 0) return 0;
        $terdaftar = $this->peserta()->count();
        return min(100, (int) round(($terdaftar / $this->kuota) * 100));
    }
}
