<?php
// app/Models/Experience.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'organization',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function getDurationAttribute()
    {
        $start = $this->start_date;
        $end = $this->is_current ? Carbon::now() : $this->end_date;

        return $start->diffForHumans($end, true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('start_date', 'desc');
    }
}
