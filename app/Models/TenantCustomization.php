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
     *
     * @return array<int, string>
     */
    public function getSectionsOrder(): array
    {
        $defaultOrder = ['hero', 'products', 'services', 'faq', 'cta', 'footer'];
        
        if (empty($this->visual_effects) || !isset($this->visual_effects['sections_order'])) {
            return $defaultOrder;
        }

        return $this->visual_effects['sections_order'];
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
            'hero' => ['variant' => 'fullscreen', 'visible' => true],
            'products' => ['variant' => 'grid3', 'visible' => true, 'border' => 'rounded', 'effect' => 'none', 'spacing' => 'normal'],
            'services' => ['variant' => 'cards', 'visible' => true, 'border' => 'rounded', 'effect' => 'glow', 'spacing' => 'airy'],
            'faq' => ['variant' => 'accordion', 'visible' => true, 'border' => 'pill', 'effect' => 'none', 'spacing' => 'normal'],
            'cta' => ['variant' => 'centered', 'visible' => true, 'border' => 'rounded', 'effect' => 'gradient', 'spacing' => 'airy'],
            'footer' => ['variant' => 'simple', 'visible' => true],
        ];

        $defaultConfig = $defaults[$section] ?? ['visible' => true];

        if (empty($this->visual_effects) || !isset($this->visual_effects['sections_config'][$section])) {
            return $defaultConfig;
        }

        return array_merge($defaultConfig, $this->visual_effects['sections_config'][$section]);
    }

    /**
     * Check if a section is visible.
     *
     * @param string $section
     * @return bool
     */
    public function isSectionVisible(string $section): bool
    {
        $config = $this->getSectionConfig($section);
        return $config['visible'] ?? true;
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
            'sections_order' => ['hero', 'products', 'services', 'faq', 'cta', 'footer'],
            'sections_config' => [],
        ];

        if (!isset($visualEffects['sections_config'])) {
            $visualEffects['sections_config'] = [];
        }

        $currentConfig = $visualEffects['sections_config'][$section] ?? [];
        $visualEffects['sections_config'][$section] = array_merge($currentConfig, $newConfig);

        $this->update(['visual_effects' => $visualEffects]);
    }
}
