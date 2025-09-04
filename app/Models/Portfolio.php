<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'technologies',
        'challenges',
        'solutions',
        'github_url',
        'images',
        'project_type',
        'project_date'
    ];

    protected $casts = [
        'images' => 'array',
        'project_date' => 'date'
    ];
}
