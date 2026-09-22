<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'geojson',
        'peta_file_id',
    ];

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function petaFile()
    {
        return $this->belongsTo(PetaFile::class);
    }
}
