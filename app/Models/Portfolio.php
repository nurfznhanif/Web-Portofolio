<?php
// app/Models/Portfolio.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_type',
        'project_date',
        'technologies',
        'challenges',
        'solutions',
        'github_url',
        'live_url',
        'images',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'project_date' => 'date',
        'images' => 'array',
        'is_featured' => 'boolean',
    ];

    public function getTechnologiesArrayAttribute()
    {
        return explode(', ', $this->technologies);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('project_date', 'desc');
    }
}
