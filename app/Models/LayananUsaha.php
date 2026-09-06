<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananUsaha extends Model
{
    use HasFactory;

    protected $table = 'layanan_usaha';

    protected $fillable = [
        'umkm_id',
        'nama_layanan',
        'deskripsi',
        'harga_mulai',
        'status',
    ];

    protected $casts = [
        'harga_mulai' => 'decimal:2',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
