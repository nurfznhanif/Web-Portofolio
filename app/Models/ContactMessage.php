<?php
// app/Models/ContactMessage.php - GANTI SELURUH ISI FILE INI

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
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'admin_notes'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'new'
    ];

    // ==========================================
    // SCOPES - YANG DIBUTUHKAN CONTROLLER
    // ==========================================

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pesan yang belum dibaca (status = new)
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope untuk pesan yang sudah dibaca
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope untuk pesan yang sudah dibalas
     */
    public function scopeReplied(Builder $query): Builder
    {
        return $query->where('status', 'replied');
    }

    /**
     * Scope untuk pesan yang diarsipkan
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope untuk pesan dalam rentang waktu tertentu
     */
    public function scopeInTimeframe(Builder $query, string $timeframe): Builder
    {
        switch ($timeframe) {
            case 'today':
                return $query->whereDate('created_at', today());
            case 'week':
                return $query->where('created_at', '>=', now()->subWeek());
            case 'month':
                return $query->where('created_at', '>=', now()->subMonth());
            case 'year':
                return $query->where('created_at', '>=', now()->subYear());
            default:
                return $query; // all time
        }
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

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

    public function getIsArchivedAttribute(): bool
    {
        return $this->status === 'archived';
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Mark message sebagai read
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Mark message sebagai replied
     */
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

    /**
     * Archive message
     */
    public function archive(): bool
    {
        return $this->update(['status' => 'archived']);
    }

    /**
     * Reply to message (alias untuk markAsReplied)
     */
    public function reply(string $replyMessage): bool
    {
        return $this->markAsReplied($replyMessage);
    }

    // ==========================================
    // STATIC METHODS
    // ==========================================

    /**
     * Get jumlah pesan unread
     */
    public static function getUnreadCount(): int
    {
        return static::unread()->count();
    }

    /**
     * Get statistik lengkap
     */
    public static function getStats(): array
    {
        return [
            'total' => static::count(),
            'unread' => static::unread()->count(),
            'read' => static::read()->count(),
            'replied' => static::replied()->count(),
            'archived' => static::archived()->count(),
            'this_week' => static::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => static::where('created_at', '>=', now()->subMonth())->count(),
        ];
    }

    /**
     * Get status counts untuk filter tabs
     */
    public static function getStatusCounts(): array
    {
        return [
            'all' => static::count(),
            'new' => static::unread()->count(),
            'read' => static::read()->count(),
            'replied' => static::replied()->count(),
            'archived' => static::archived()->count(),
        ];
    }
}
