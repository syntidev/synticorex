<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBlueprint;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasBlueprint;
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
        'plan_activated_at',
        'settings',
        'whatsapp_active',
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
            'plan_activated_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the active WhatsApp number based on whatsapp_active setting.
     */
    public function getActiveWhatsapp(): ?string
    {
        return $this->whatsapp_active === 'support'
            ? $this->whatsapp_support
            : $this->whatsapp_sales;
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

    /**
     * Get the branches for the tenant (Plan 3 / VISIÓN only).
     *
     * @return HasMany<TenantBranch, $this>
     */
    public function branches(): HasMany
    {
        return $this->hasMany(TenantBranch::class);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // LIFECYCLE HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Days remaining until subscription expires.
     * Negative means already expired. Returns null when no expiry is set.
     */
    public function daysUntilExpiry(): ?int
    {
        if ($this->subscription_ends_at === null) {
            return null;
        }

        return (int) Carbon::now()->diffInDays($this->subscription_ends_at, false);
    }

    /**
     * True when subscription expires in 30 days or fewer (but has NOT yet expired).
     */
    public function isExpiringSoon(): bool
    {
        $days = $this->daysUntilExpiry();

        return $days !== null && $days >= 0 && $days <= 30;
    }

    /**
     * True when the plan is frozen (expired, within 30-day grace period).
     */
    public function isFrozen(): bool
    {
        return $this->status === 'frozen';
    }

    /**
     * True when the plan is archived (grace period has also expired).
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Days of grace-period remaining for a frozen tenant.
     * Grace ends 30 days after subscription_ends_at.
     * Returns null if tenant is not frozen or has no expiry date.
     */
    public function graceRemainingDays(): ?int
    {
        if (! $this->isFrozen() || $this->subscription_ends_at === null) {
            return null;
        }

        $graceEndsAt = $this->subscription_ends_at->copy()->addDays(30);

        return max(0, (int) Carbon::now()->diffInDays($graceEndsAt, false));
    }

    /**
     * Get available REORDERABLE sections for this tenant's plan (9 secciones dinámicas).
     * Nota: hero y footer NO están incluidas porque se renderizan hardcodeadas
     * fuera del loop dinámico (hero antes de main, footer después de main).
     *
     * @return array<string, array>
     */
    public function getAvailableSections(): array
    {
        // 9 secciones dinámicas reordenables (hero y footer son fijas)
        $allSections = [
            'products'        => ['label' => 'Productos',        'icon' => 'tabler:shopping-cart',      'plan' => 1],
            'services'        => ['label' => 'Servicios',        'icon' => 'tabler:tool',               'plan' => 1],
            'contact'         => ['label' => 'Contacto',         'icon' => 'tabler:map-pin',            'plan' => 1],
            'payment_methods' => ['label' => 'Medios de Pago',   'icon' => 'tabler:credit-card',        'plan' => 1],
            'cta'             => ['label' => 'Llamado a Acción', 'icon' => 'tabler:send',               'plan' => 1],
            'about'           => ['label' => 'Acerca de',        'icon' => 'tabler:info-circle',        'plan' => 2],
            'testimonials'    => ['label' => 'Testimonios',      'icon' => 'tabler:message-star',       'plan' => 2],
            'faq'             => ['label' => 'FAQ',              'icon' => 'tabler:help-circle',        'plan' => 3],
            'branches'        => ['label' => 'Sucursales',       'icon' => 'tabler:building-bank',      'plan' => 3],
        ];

        // Filter by plan access
        $available = [];
        foreach ($allSections as $key => $section) {
            if ($this->customization && $this->customization->canAccessSection($key, $this->plan_id)) {
                $available[$key] = $section;
            }
        }

        return $available;
    }

    /**
     * Si el tenant tiene Plan CRECIMIENTO o superior (por slug).
     */
    public function isAtLeastCrecimiento(): bool
    {
        if (!$this->plan) return false;
        return in_array($this->plan->slug, [
            'crecimiento',
            'vision',
            'food-semestral',
            'food-anual',
            'cat-semestral',
            'cat-anual',
        ]);
    }

    /**
     * Si el tenant tiene Plan VISIÓN (plan_id === 3).
     */
    public function isVision(): bool
    {
        return (int) $this->plan_id === Plan::VISION;
    }

    public function getBlueprintSlug(): string
    {
        // Source of truth: plan.blueprint field from DB
        $planBlueprint = $this->plan?->blueprint;
        if ($planBlueprint !== null && $planBlueprint !== '') {
            return $planBlueprint;
        }

        // Fallback: infer from business_segment (legacy tenants without plan relationship)
        return match(true) {
            in_array($this->business_segment, ['restaurante', 'Restaurante', 'comida', 'Comida', 'Food', 'food', 'Alimentos', 'pizzeria', 'cafetería', 'café']) => 'food',
            in_array($this->business_segment, ['comercio', 'Comercio', 'tienda', 'Tienda', 'retail', 'Retail', 'ropa', 'electrónica']) => 'retail',
            in_array($this->business_segment, ['salud', 'Salud', 'belleza', 'Belleza', 'peluquería', 'spa', 'gimnasio', 'Gimnasio', 'wellness']) => 'health',
            in_array($this->business_segment, ['tecnología', 'Tecnología', 'consultoría', 'Consultoría', 'legal', 'Legal', 'contabilidad', 'profesional', 'Profesional', 'IT']) => 'professional',
            in_array($this->business_segment, ['técnico', 'Técnico', 'mecánico', 'plomero', 'electricista', 'carpintero', 'on-demand', 'freelance']) => 'ondemand',
            in_array($this->business_segment, ['educación', 'Educación', 'academia', 'Academia', 'clases', 'cursos', 'preescolar']) => 'education',
            in_array($this->business_segment, ['transporte', 'Transporte', 'delivery', 'Delivery', 'encomienda', 'mudanza', 'taxi']) => 'transport',
            default => 'professional',
        };
    }

    public function getSeoLevel(): string
    {
        return $this->plan->seo_level ?? 'basic';
    }

    public function getAnalyticsLevel(): string
    {
        return $this->plan->analytics_level ?? 'basic';
    }
}
