<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyKepuasan extends Model
{
    use HasFactory;

    protected $table = 'survey_kepuasan';

    protected $fillable = [
        'pelaku_usaha_id',
        'nama_responden',
        'pekerjaan',
        'modul',
        'nilai_kemudahan',
        'nilai_kecepatan',
        'nilai_keramahan',
        'nilai_kemanfaatan',
        'saran_teks',
    ];

    protected $casts = [
        'nilai_kemudahan' => 'integer',
        'nilai_kecepatan' => 'integer',
        'nilai_keramahan' => 'integer',
        'nilai_kemanfaatan' => 'integer',
    ];

    public function pelakuUsaha(): BelongsTo
    {
        return $this->belongsTo(PelakuUsaha::class, 'pelaku_usaha_id');
    }

    public function getRataRataAttribute(): float
    {
        return round(($this->nilai_kemudahan + $this->nilai_kecepatan + $this->nilai_keramahan + $this->nilai_kemanfaatan) / 4, 1);
    }
}
