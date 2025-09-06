<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'page',
        'data',
        'ip_address',
        'user_agent',
        'created_at', // Tambahkan ini
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    // Hapus baris ini karena bertentangan
    // public $timestamps = false;

    // Karena hanya ada created_at, tidak ada updated_at
    const UPDATED_AT = null;

    public static function track($eventType, $page = null, $data = null)
    {
        return self::create([
            'event_type' => $eventType,
            'page' => $page,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            // Hapus baris ini, biarkan Laravel handle otomatis
            // 'created_at' => now(),
        ]);
    }
}
