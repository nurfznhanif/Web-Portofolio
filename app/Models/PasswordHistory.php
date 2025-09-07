<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'password_histories';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'password_hash',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the user that owns the password history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a password matches this history entry.
     */
    public function checkPassword(string $password): bool
    {
        return \Hash::check($password, $this->password_hash);
    }

    /**
     * Create a new password history entry.
     */
    public static function createHistory(User $user, string $password): self
    {
        return self::create([
            'user_id' => $user->id,
            'password_hash' => \Hash::make($password),
            'created_at' => now(),
        ]);
    }

    /**
     * Clean up old password histories (keep only recent ones).
     */
    public static function cleanupOldHistories(User $user, int $keepCount = 5): void
    {
        $oldHistories = self::where('user_id', $user->id)
            ->oldest('created_at')
            ->skip($keepCount)
            ->get();

        foreach ($oldHistories as $history) {
            $history->delete();
        }
    }

    /**
     * Get recent password histories for a user.
     */
    public static function getRecentHistories(User $user, int $count = 5)
    {
        return self::where('user_id', $user->id)
            ->latest('created_at')
            ->take($count)
            ->get();
    }

    /**
     * Check if password was recently used by user.
     */
    public static function isPasswordRecentlyUsed(User $user, string $password, int $checkCount = 5): bool
    {
        $recentHistories = self::getRecentHistories($user, $checkCount);

        return $recentHistories->contains(function ($history) use ($password) {
            return $history->checkPassword($password);
        });
    }
}
