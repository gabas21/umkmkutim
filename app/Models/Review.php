<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';

    protected $fillable = [
        'umkm_id',
        'rating',
        'komentar',
        'nama_reviewer',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
