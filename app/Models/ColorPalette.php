<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ColorPalette extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * Only uses created_at, not updated_at.
     *
     * @var bool
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'min_plan_id',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_plan_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the minimum plan required for this palette.
     *
     * @return BelongsTo<Plan, $this>
     */
    public function minPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'min_plan_id');
    }

    /**
     * Get the tenants using this color palette.
     *
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'color_palette_id');
    }
}
