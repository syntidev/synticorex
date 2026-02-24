<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultVisualEffects = [
            'sections_order' => ['hero', 'products', 'services', 'faq', 'cta', 'footer'],
            'sections_config' => [
                'hero' => [
                    'variant' => 'fullscreen',
                    'visible' => true,
                ],
                'products' => [
                    'variant' => 'grid3',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'none',
                    'spacing' => 'normal',
                ],
                'services' => [
                    'variant' => 'cards',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'glow',
                    'spacing' => 'airy',
                ],
                'faq' => [
                    'variant' => 'accordion',
                    'visible' => true,
                    'border' => 'pill',
                    'effect' => 'none',
                    'spacing' => 'normal',
                ],
                'cta' => [
                    'variant' => 'centered',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'gradient',
                    'spacing' => 'airy',
                ],
                'footer' => [
                    'variant' => 'simple',
                    'visible' => true,
                ],
            ],
        ];

        DB::table('tenant_customization')
            ->whereNull('visual_effects')
            ->update(['visual_effects' => json_encode($defaultVisualEffects)]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the visual_effects field back to NULL
        // Only for rows that have the exact default structure
        $defaultVisualEffects = [
            'sections_order' => ['hero', 'products', 'services', 'faq', 'cta', 'footer'],
            'sections_config' => [
                'hero' => [
                    'variant' => 'fullscreen',
                    'visible' => true,
                ],
                'products' => [
                    'variant' => 'grid3',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'none',
                    'spacing' => 'normal',
                ],
                'services' => [
                    'variant' => 'cards',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'glow',
                    'spacing' => 'airy',
                ],
                'faq' => [
                    'variant' => 'accordion',
                    'visible' => true,
                    'border' => 'pill',
                    'effect' => 'none',
                    'spacing' => 'normal',
                ],
                'cta' => [
                    'variant' => 'centered',
                    'visible' => true,
                    'border' => 'rounded',
                    'effect' => 'gradient',
                    'spacing' => 'airy',
                ],
                'footer' => [
                    'variant' => 'simple',
                    'visible' => true,
                ],
            ],
        ];

        DB::table('tenant_customization')
            ->where('visual_effects', json_encode($defaultVisualEffects))
            ->update(['visual_effects' => null]);
    }
};
