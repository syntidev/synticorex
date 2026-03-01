<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantCustomization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenant_customization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'logo_filename',
        'hero_filename',
        'theme_slug',          // FlyonUI official theme (light, dark, cupcake, etc.)
        'social_networks',
        'payment_methods',
        'faq_items',
        'cta_title',
        'cta_subtitle',
        'cta_button_text',
        'cta_button_link',
        'visual_effects',
        'about_text',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'social_networks' => 'array',
            'payment_methods' => 'array',
            'faq_items' => 'array',
            'visual_effects' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tenant that owns the customization.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the sections order from visual_effects.
     * Normalizes format to ensure consistency across all plans.
     *
     * @return array
     */
    public function getSectionsOrder(): array
    {
        $order = $this->visual_effects['sections_order'] ?? [];

        // Si no hay orden guardado, devolver secciones por defecto
        if (empty($order)) {
            $order = [
                ['name' => 'products',        'visible' => true, 'order' => 0],
                ['name' => 'services',        'visible' => true, 'order' => 1],
                ['name' => 'contact',         'visible' => true, 'order' => 2],
                ['name' => 'payment_methods', 'visible' => true, 'order' => 3],
                ['name' => 'cta',             'visible' => true, 'order' => 4],
                ['name' => 'about',           'visible' => true, 'order' => 5],
                ['name' => 'testimonials',    'visible' => true, 'order' => 6],
                ['name' => 'faq',             'visible' => true, 'order' => 7],
                ['name' => 'branches',        'visible' => true, 'order' => 8],
            ];
        }
        
        // Normalizar formato: convertir strings a arrays si es necesario
        return collect($order)->map(function($section) {
            // Si es string, convertir a formato array
            if (is_string($section)) {
                return [
                    'name' => $section,
                    'visible' => true,
                    'order' => 0
                ];
            }
            // Si ya es array, asegurar que tiene todas las keys
            return [
                'name' => $section['name'] ?? '',
                'visible' => $section['visible'] ?? true,
                'order' => $section['order'] ?? 0
            ];
        })->toArray();
    }

    /**
     * Get configuration for a specific section, merged with defaults.
     *
     * @param string $section
     * @return array<string, mixed>
     */
    public function getSectionConfig(string $section): array
    {
        $defaults = [
            'hero'            => ['variant' => 'fullscreen',  'visible' => true],
            'products'        => ['variant' => 'grid3',       'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'services'        => ['variant' => 'cards',       'visible' => true, 'border' => 'rounded', 'effect' => 'glow',     'spacing' => 'airy'],
            'about'           => ['variant' => 'split',       'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'contact'         => ['variant' => 'map',         'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'payment_methods' => ['variant' => 'grid',        'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'testimonials'    => ['variant' => 'carousel',    'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'faq'             => ['variant' => 'accordion',   'visible' => true, 'border' => 'pill',    'effect' => 'none',     'spacing' => 'normal'],
            'branches'        => ['variant' => 'cards',       'visible' => true, 'border' => 'rounded', 'effect' => 'none',     'spacing' => 'normal'],
            'cta'             => ['variant' => 'centered',    'visible' => true, 'border' => 'rounded', 'effect' => 'gradient', 'spacing' => 'airy'],
            'footer'          => ['variant' => 'simple',      'visible' => true],
        ];

        $defaultConfig = $defaults[$section] ?? ['visible' => true];

        if (empty($this->visual_effects) || !isset($this->visual_effects['sections_config'][$section])) {
            return $defaultConfig;
        }

        return array_merge($defaultConfig, $this->visual_effects['sections_config'][$section]);
    }

    /**
     * Check if a section is visible (from sections_order).
     * Single source of truth for visibility.
     *
     * @param string $section
     * @return bool
     */
    public function isSectionVisible(string $section): bool
    {
        $order = $this->getSectionsOrder();
        $sectionData = collect($order)->firstWhere('name', $section);
        return $sectionData['visible'] ?? true;
    }

    /**
     * Check if tenant can access a section based on plan.
     *
     * @param string $section
     * @param int $planId
     * @return bool
     */
    public function canAccessSection(string $section, int $planId): bool
    {
        $planRequirements = [
            'hero'            => 1,
            'products'        => 1,
            'services'        => 1,
            'contact'         => 1,
            'payment_methods' => 1,
            'cta'             => 1,
            'footer'          => 1,
            'about'           => 2,
            'testimonials'    => 2,
            'faq'             => 3,
            'branches'        => 3,
        ];

        $requiredPlan = $planRequirements[$section] ?? 1;
        return $planId >= $requiredPlan;
    }

    /**
     * Update configuration for a specific section.
     *
     * @param string $section
     * @param array<string, mixed> $newConfig
     * @return void
     */
    public function updateSectionConfig(string $section, array $newConfig): void
    {
        $visualEffects = $this->visual_effects ?? [
            'sections_order' => [],
            'sections_config' => [],
        ];

        if (!isset($visualEffects['sections_config'])) {
            $visualEffects['sections_config'] = [];
        }

        $currentConfig = $visualEffects['sections_config'][$section] ?? [];
        $visualEffects['sections_config'][$section] = array_merge($currentConfig, $newConfig);

        $this->update(['visual_effects' => $visualEffects]);
    }

    /**
     * Sync sections_config with sections_order visibility.
     *
     * @return void
     */
    public function syncSectionsConfig(): void
    {
        $visualEffects = $this->visual_effects ?? [];
        $order = $this->getSectionsOrder();

        if (!isset($visualEffects['sections_config'])) {
            $visualEffects['sections_config'] = [];
        }

        foreach ($order as $section) {
            $name = $section['name'];
            if (!isset($visualEffects['sections_config'][$name])) {
                $visualEffects['sections_config'][$name] = [];
            }
            $visualEffects['sections_config'][$name]['visible'] = $section['visible'];
        }

        $this->update(['visual_effects' => $visualEffects]);
    }
}
