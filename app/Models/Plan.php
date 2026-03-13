<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    /** @var int Plan OPORTUNIDAD (básico) */
    public const OPORTUNIDAD = 1;

    /** @var int Plan CRECIMIENTO (intermedio) */
    public const CRECIMIENTO = 2;

    /** @var int Plan VISIÓN (premium) */
    public const VISION = 3;

    /** @var list<string> */
    protected $fillable = [
        'slug',
        'blueprint',
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

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'show_dollar_rate'     => 'boolean',
            'show_header_top'      => 'boolean',
            'show_about_section'   => 'boolean',
            'show_payment_methods' => 'boolean',
            'show_faq'             => 'boolean',
            'show_cta_special'     => 'boolean',
            'whatsapp_hour_filter' => 'boolean',
            'price_usd'            => 'decimal:2',
        ];
    }

    /** @return HasMany<Tenant, $this> */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
