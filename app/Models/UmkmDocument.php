<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'umkm_id',
        'nama_dokumen',
        'file_path',
        'jenis_dokumen',
        'status',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
