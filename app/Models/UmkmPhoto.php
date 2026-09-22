<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'umkm_id',
        'image_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
