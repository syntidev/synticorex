<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = [
        'section_key',
        'section_label',
        'content',
        'is_active',
        'sort_order',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'content'   => 'array',
            'is_active' => 'boolean',
        ];
    }

    public static function forKey(string $key): ?self
    {
        return static::where('section_key', $key)->first();
    }
}
