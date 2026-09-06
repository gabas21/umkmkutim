<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PelakuUsaha extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pelaku_usaha';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'nomor_telepon',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function klaimUsaha()
    {
        return $this->hasMany(KlaimUsaha::class, 'pelaku_usaha_id');
    }

    public function umkmTerverifikasi()
    {
        return $this->belongsToMany(Umkm::class, 'klaim_usaha', 'pelaku_usaha_id', 'umkm_id')
            ->wherePivot('status', 'disetujui')
            ->withTimestamps();
    }
}
