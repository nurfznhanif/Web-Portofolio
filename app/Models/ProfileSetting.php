<?php
// app/Models/ProfileSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSetting extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'name',
        'title',
        'summary',
        'email',
        'phone',
        'location',
        'photo',
        'education',
        'thesis_title',
        'availability',
    ];

    protected $casts = [
        'education' => 'array',
        'availability' => 'array',
    ];

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-avatar.png');
    }
}
