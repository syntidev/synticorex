<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'price_usd',
        'products_limit',
        'services_limit',
        'images_limit',
        'color_palettes',
        'social_networks_limit',
        'show_dollar_rate',
        'show_header_top',
        'show_about_section',
        'show_payment_methods',
        'show_faq',
        'show_cta_special',
        'analytics_level',
        'seo_level',
        'whatsapp_numbers',
        'whatsapp_hour_filter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        ];
    }

    /**
     * Get the tenants for the user.
     *
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
