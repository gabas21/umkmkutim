<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'slug',
        'icon',
    ];

    public function umkm()
    {
        return $this->hasMany(Umkm::class, 'kategori_id');
    }
}
