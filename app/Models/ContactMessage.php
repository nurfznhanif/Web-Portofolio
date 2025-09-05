<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'read_at',
        'replied_at',
        'reply',
        'ip_address'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'new'
    ];

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied(Builder $query): Builder
    {
        return $query->where('status', 'replied');
    }

    // Accessors
    public function getIsUnreadAttribute(): bool
    {
        return $this->status === 'new';
    }

    public function getIsReadAttribute(): bool
    {
        return $this->status === 'read';
    }

    public function getIsRepliedAttribute(): bool
    {
        return $this->status === 'replied';
    }

    // Methods
    public function markAsRead(): bool
    {
        return $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsReplied(string $reply = null): bool
    {
        $data = [
            'status' => 'replied',
            'replied_at' => now()
        ];

        if ($reply) {
            $data['reply'] = $reply;
        }

        return $this->update($data);
    }

    public function reply(string $replyMessage): bool
    {
        return $this->markAsReplied($replyMessage);
    }

    // Static methods
    public static function getUnreadCount(): int
    {
        return static::unread()->count();
    }

    public static function getStats(): array
    {
        return [
            'total' => static::count(),
            'unread' => static::unread()->count(),
            'read' => static::read()->count(),
            'replied' => static::replied()->count(),
            'this_week' => static::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => static::where('created_at', '>=', now()->subMonth())->count(),
        ];
    }
}
