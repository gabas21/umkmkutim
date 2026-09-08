<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelatihanPeserta extends Model
{
    use HasFactory;

    protected $table = 'pelatihan_pesertas';

    protected $fillable = [
        'pelatihan_id',
        'pelaku_usaha_id',
        'nama_peserta',
        'nama_usaha',
        'email',
        'nomor_hp',
        'instansi',
        'motivasi',
        'status',
    ];

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function pelakuUsaha(): BelongsTo
    {
        return $this->belongsTo(PelakuUsaha::class, 'pelaku_usaha_id');
    }
}
