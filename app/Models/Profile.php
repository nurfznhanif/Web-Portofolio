<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'location',
        'bio',
        'education_degree',
        'education_gpa',
        'education_thesis',
        'github_url',
        'profile_image'
    ];
}
