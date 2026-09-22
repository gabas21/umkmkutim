<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function record(string $module, string $action, ?string $description = null, ?int $userId = null): self
    {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
