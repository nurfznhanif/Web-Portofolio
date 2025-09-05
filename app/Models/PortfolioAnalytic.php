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
        'session_id',
        'created_at'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    public static function track($eventType, $page, $data = null, $ip = null)
    {
        return self::create([
            'event_type' => $eventType,
            'page' => $page,
            'data' => $data,
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
        ]);
    }
}
