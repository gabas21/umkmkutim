<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetaFile extends Model
{
    use HasFactory;

    protected $table = 'peta_files';

    protected $fillable = [
        'name',
        'file_path',
        'uploaded_by',
    ];

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class);
    }
}
