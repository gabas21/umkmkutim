<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BazarPeserta extends Model
{
    use HasFactory;

    protected $table = 'bazar_pesertas';

    protected $fillable = [
        'bazar_id',
        'pelaku_usaha_id',
        'nama_pemilik',
        'nama_usaha',
        'kategori_produk',
        'nomor_hp',
        'email',
        'deskripsi_produk',
        'catatan',
        'status',
    ];

    public function bazar(): BelongsTo
    {
        return $this->belongsTo(Bazar::class, 'bazar_id');
    }

    public function pelakuUsaha(): BelongsTo
    {
        return $this->belongsTo(PelakuUsaha::class, 'pelaku_usaha_id');
    }
}
