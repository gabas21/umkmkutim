<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKunjungan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kunjungan';

    protected $fillable = [
        'umkm_id',
        'tanggal',
        'jumlah_dilihat',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_dilihat' => 'integer',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    /**
     * Increment kunjungan harian UMKM secara atomik
     */
    public static function catatKunjungan($umkmId, $tanggal = null)
    {
        $tgl = $tanggal ?: now()->toDateString();
        $record = static::firstOrCreate(
            ['umkm_id' => $umkmId, 'tanggal' => $tgl],
            ['jumlah_dilihat' => 0]
        );
        $record->increment('jumlah_dilihat');

        // Update juga counter global di tabel umkm
        Umkm::where('id', $umkmId)->increment('jumlah_dilihat');

        return $record;
    }
}
