<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait HasBlueprint
{
    /**
     * Get the full blueprint config for this tenant's industry segment.
     *
     * @return array<string, mixed>|null
     */
    public function getBlueprint(): ?array
    {
        $slug = $this->getBlueprintSlug();
        return Config::get("blueprints.{$slug}");
    }

    /**
     * Get a specific key from the blueprint config (dot notation).
     *
     * @param string|null $key
     * @return mixed
     */
    public function blueprintConfig(?string $key = null): mixed
    {
        $blueprint = $this->getBlueprint();

        if (! $blueprint) {
            return null;
        }

        return $key ? data_get($blueprint, $key) : $blueprint;
    }

    /**
     * Check if a specific feature is unlocked for this tenant's plan.
     *
     * @param string $feature
     * @return bool
     */
    public function isFeatureUnlocked(string $feature): bool
    {
        $blueprint = $this->getBlueprint();

        if (! $blueprint) {
            return false;
        }

        $planId = $this->plan_id;
        $features = data_get($blueprint, "feature_limits.{$planId}.features", []);

        return in_array($feature, $features, true);
    }

    /**
     * Get the maximum number of items (products/services) for this tenant's plan + segment.
     * Falls back to plan-level limits if no blueprint is set.
     *
     * @return int
     */
    public function getMaxItems(): int
    {
        // Plan's products_limit is the primary source of truth
        if ($this->plan) {
            $limit = $this->plan->products_limit;
            if ($limit === null) return 9999; // null = ilimitado
            if ($limit > 0)     return (int) $limit;
        }

        $blueprint = $this->getBlueprint();

        if (! $blueprint) {
            return match ((int) $this->plan_id) {
                1 => 6,
                2 => 12,
                3 => 18,
                default => 6,
            };
        }

        return (int) data_get($blueprint, "feature_limits.{$this->plan_id}.max_items", 6);
    }

    /**
     * Get the item label for this blueprint (e.g. "Menú", "Productos", "Trabajos").
     *
     * @return string
     */
    public function getItemLabel(): string
    {
        return $this->blueprintConfig('item_label') ?? 'Productos';
    }

    /**
     * Get the singular item label (e.g. "Plato", "Producto", "Trabajo").
     *
     * @return string
     */
    public function getItemSingular(): string
    {
        return $this->blueprintConfig('item_singular') ?? 'Producto';
    }

    /**
     * Get the Schema.org type for this blueprint.
     *
     * @return string
     */
    public function getSchemaType(): string
    {
        return match($this->getBlueprintSlug()) {
            'food'         => 'Restaurant',
            'retail'       => 'Store',
            'health'       => 'HealthAndBeautyBusiness',
            'professional' => 'ProfessionalService',
            'ondemand'     => 'LocalBusiness',
            'education'    => 'EducationalOrganization',
            'transport'    => 'DeliveryChargeSpecification',
            default        => 'LocalBusiness',
        };
    }

    /**
     * Get the blueprint label (human-readable segment name).
     *
     * @return string
     */
    public function getBlueprintLabel(): string
    {
        return $this->blueprintConfig('label') ?? 'Negocio General';
    }

    /**
     * Get the ordered landing sections for this blueprint.
     * Falls back to default section order if no blueprint is set.
     *
     * @return array<int, string>
     */
    public function getBlueprintSections(): array
    {
        return $this->blueprintConfig('landing_sections') ?? [
            'hero', 'products', 'services', 'contact',
            'payment_methods', 'cta',
        ];
    }

    /**
     * Check if a specific info field is required/available for this blueprint.
     *
     * @param string $field
     * @return bool
     */
    public function hasInfoField(string $field): bool
    {
        $fields = $this->blueprintConfig('fields.info') ?? [];

        return in_array($field, $fields, true);
    }

    /**
     * Check if a specific item field is available for this blueprint.
     *
     * @param string $field
     * @return bool
     */
    public function hasItemField(string $field): bool
    {
        $fields = $this->blueprintConfig('fields.items') ?? [];

        return in_array($field, $fields, true);
    }
}
