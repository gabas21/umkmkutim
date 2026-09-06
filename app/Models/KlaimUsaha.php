<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlaimUsaha extends Model
{
    use HasFactory;

    protected $table = 'klaim_usaha';

    protected $fillable = [
        'umkm_id',
        'pelaku_usaha_id',
        'dokumen_ktp',
        'dokumen_bukti_usaha',
        'catatan_pemohon',
        'status',
        'catatan_admin',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi_pada' => 'datetime',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class, 'pelaku_usaha_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
