<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('issue_date', 'desc');
    }

    // Accessors
    public function getIsValidAttribute(): bool
    {
        return $this->expiry_date ? $this->expiry_date->isFuture() : true;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date ? $this->expiry_date->isPast() : false;
    }
}
