<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'technologies',
        'github_url',
        'demo_url',
        'image',
        'featured'
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean'
    ];
}
