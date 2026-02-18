<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'subdomain',
        'base_domain',
        'custom_domain',
        'domain_verified',
        'business_name',
        'business_segment',
        'slogan',
        'description',
        'phone',
        'whatsapp_sales',
        'whatsapp_support',
        'email',
        'address',
        'city',
        'country',
        'business_hours',
        'is_open',
        'edit_pin',
        'currency_display',
        'color_palette_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'domain_verified' => 'boolean',
            'business_hours' => 'array',
            'settings' => 'array',
            'is_open' => 'boolean',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the tenant.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that the tenant belongs to.
     *
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the color palette that the tenant uses.
     *
     * @return BelongsTo<ColorPalette, $this>
     */
    public function colorPalette(): BelongsTo
    {
        return $this->belongsTo(ColorPalette::class);
    }

    /**
     * Get the products for the tenant.
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the services for the tenant.
     *
     * @return HasMany<Service, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the analytics events for the tenant.
     *
     * @return HasMany<AnalyticsEvent, $this>
     */
    public function analyticsEvents(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    /**
     * Get the invoices for the tenant.
     *
     * @return HasMany<Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the customization for the tenant.
     *
     * @return HasOne<TenantCustomization, $this>
     */
    public function customization(): HasOne
    {
        return $this->hasOne(TenantCustomization::class);
    }
}
