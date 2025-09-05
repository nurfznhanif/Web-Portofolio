<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'native_name',
        'code',
        'level',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getLevelLabelAttribute(): string
    {
        $levels = [
            'basic' => 'Basic',
            'conversational' => 'Conversational',
            'professional' => 'Professional',
            'native' => 'Native'
        ];

        return $levels[$this->level] ?? 'Unknown';
    }

    public function getLevelPercentageAttribute(): int
    {
        $percentages = [
            'basic' => 25,
            'conversational' => 50,
            'professional' => 75,
            'native' => 100
        ];

        return $percentages[$this->level] ?? 0;
    }
}
