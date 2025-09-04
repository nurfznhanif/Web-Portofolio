<?php
// app/Models/ProfileSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProfileSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'description',
        'category',
        'is_public',
        'sort_order'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Auto-convert JSON values
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->type === 'json' ? json_decode($value, true) : $value,
            set: fn($value) => $this->type === 'json' ? json_encode($value) : $value,
        );
    }

    // Helper method to get setting by key
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_public', true)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method to set setting
    public static function setValue(string $key, $value, string $type = 'text', string $category = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'is_public' => true
            ]
        );
    }

    // Get all settings by category
    public static function getByCategory(string $category)
    {
        return static::where('category', $category)
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('value', 'key');
    }
}

// app/Models/SocialLink.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'url',
        'icon',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('platform');
    }
}

// app/Models/Achievement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_achieved',
        'category',
        'issuer',
        'credential_id',
        'credential_url',
        'image',
        'is_featured',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'date_achieved' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('date_achieved', 'desc');
    }
}

// app/Models/Language.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'proficiency',
        'description',
        'certificate',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'proficiency' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('proficiency', 'desc');
    }

    public function getProficiencyLabelAttribute()
    {
        if ($this->proficiency >= 90) return 'Native';
        if ($this->proficiency >= 75) return 'Fluent';
        if ($this->proficiency >= 50) return 'Intermediate';
        return 'Basic';
    }
}

// app/Models/Interest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'icon',
        'color',
        'is_featured',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}

// app/Models/Certification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issuer',
        'issue_date',
        'expiry_date',
        'credential_id',
        'credential_url',
        'skills',
        'image',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'skills' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('issue_date', 'desc');
    }

    public function getIsValidAttribute()
    {
        return !$this->expiry_date || $this->expiry_date->isFuture();
    }
}

// app/Models/ContactMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'status',
        'admin_notes',
        'read_at',
        'replied_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime'
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsReplied()
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now()
        ]);
    }
}

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
        'referrer',
        'country',
        'city',
        'device_type'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime'
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    public static function track(string $eventType, ?string $page = null, ?array $data = null, ?string $ipAddress = null)
    {
        return static::create([
            'event_type' => $eventType,
            'page' => $page,
            'data' => $data,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
            'created_at' => now()
        ]);
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeByPage($query, $page)
    {
        return $query->where('page', $page);
    }

    public function scopeLastDays($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
