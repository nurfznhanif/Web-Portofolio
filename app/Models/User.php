<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_changed_at',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'avatar',
        'two_factor_enabled_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_enabled_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_recovery_codes' => 'json',
    ];

    /**
     * Check if user should update password (older than X days).
     */
    public function shouldUpdatePassword($days = 90): bool
    {
        if (!$this->password_changed_at) {
            return true; // No record of password change
        }

        return $this->password_changed_at->diffInDays(now()) >= $days;
    }

    /**
     * Check if password was recently changed.
     */
    public function passwordRecentlyChanged($hours = 24): bool
    {
        if (!$this->password_changed_at) {
            return false;
        }

        return $this->password_changed_at->diffInHours(now()) <= $hours;
    }

    /**
     * Get password age in days.
     */
    public function getPasswordAgeAttribute(): int
    {
        if (!$this->password_changed_at) {
            return 0;
        }

        return $this->password_changed_at->diffInDays(now());
    }

    /**
     * Update login tracking information.
     */
    public function updateLoginTracking($request): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'last_login_user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $nameParts = explode(' ', $this->name);

        if (count($nameParts) === 1) {
            return strtoupper(substr($nameParts[0], 0, 2));
        }

        return strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    }

    /**
     * Get user's avatar URL or generate one.
     */
    public function getAvatarUrlAttribute(): string
    {
        // If user has uploaded avatar
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate avatar using service like Gravatar or UI Avatars
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) .
            "&color=ffffff&background=6366f1&bold=true&size=128";
    }

    /**
     * Check if user is online (logged in within last X minutes).
     */
    public function isOnline($minutes = 5): bool
    {
        if (!$this->last_login_at) {
            return false;
        }

        return $this->last_login_at->diffInMinutes(now()) <= $minutes;
    }

    /**
     * Check if two-factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_enabled_at) && !is_null($this->two_factor_secret);
    }

    /**
     * Get security score based on password age, login activity, etc.
     */
    public function getSecurityScoreAttribute(): array
    {
        $score = 100;
        $issues = [];

        // Password age check
        if ($this->shouldUpdatePassword(90)) {
            $score -= 30;
            $issues[] = 'Password is older than 90 days';
        }

        // No recent login check
        if (!$this->last_login_at || $this->last_login_at->diffInDays(now()) > 30) {
            $score -= 20;
            $issues[] = 'No recent login activity';
        }

        // Email verification check
        if (!$this->email_verified_at) {
            $score -= 25;
            $issues[] = 'Email not verified';
        }

        // Two-factor authentication check
        if (!$this->hasTwoFactorEnabled()) {
            $score -= 15;
            $issues[] = 'Two-factor authentication not enabled';
        }

        $level = 'high';
        if ($score < 60) {
            $level = 'low';
        } elseif ($score < 80) {
            $level = 'medium';
        }

        return [
            'score' => max(0, $score),
            'level' => $level,
            'issues' => $issues,
        ];
    }

    /**
     * Scope to get users with old passwords.
     */
    public function scopeWithOldPasswords($query, $days = 90)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('password_changed_at')
                ->orWhere('password_changed_at', '<', now()->subDays($days));
        });
    }

    /**
     * Scope to get recently active users.
     */
    public function scopeRecentlyActive($query, $hours = 24)
    {
        return $query->where('last_login_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope to get users with two-factor enabled.
     */
    public function scopeWithTwoFactor($query)
    {
        return $query->whereNotNull('two_factor_enabled_at')
            ->whereNotNull('two_factor_secret');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set password_changed_at when password is updated
        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password_changed_at = now();
            }
        });

        // Set password_changed_at when user is created
        static::creating(function ($user) {
            if ($user->password) {
                $user->password_changed_at = now();
            }
        });
    }
}
