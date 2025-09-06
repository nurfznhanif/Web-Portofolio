<?php
// app/Models/PortfolioAnalytic.php
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
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    public static function track($eventType, $page = null, $data = null)
    {
        return self::create([
            'event_type' => $eventType,
            'page' => $page,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
