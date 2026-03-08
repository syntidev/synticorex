<?php

declare(strict_types=1);

// Fuente de verdad de planes. Para cambiar precios o límites:
// editar este archivo y ejecutar:
// php artisan db:seed --class=PlansSeeder

// PENDIENTE: migración para hacer nullable products_limit e images_limit — ver D.8
// Además, images_limit es unsignedTinyInteger (máx 255); cat-semestral necesita 300 →
// la migración D.8 también debe cambiar images_limit a unsignedSmallInteger.

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Fuente de verdad de los 9 planes del sistema.
     * Idempotente: usa updateOrCreate con slug como clave única.
     * Al finalizar elimina cualquier plan cuyo slug no pertenezca a este conjunto.
     */
    public function run(): void
    {
        $plans = [

            // ----------------------------------------------------------------
            // STUDIO
            // ----------------------------------------------------------------

            [
                'slug'                  => 'studio-oportunidad',
                'blueprint'             => 'studio',
                'name'                  => 'Oportunidad',
                'price_usd'             => 99.00,
                'products_limit'        => 20,
                'services_limit'        => 3,
                'images_limit'          => 20,   // 1 foto × 20 productos
                'color_palettes'        => 10,
                'social_networks_limit' => 2,
                'show_dollar_rate'      => true,
                'show_header_top'       => false,
                'show_about_section'    => false,
                'show_payment_methods'  => true,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'basic',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => false,
            ],

            [
                'slug'                  => 'studio-crecimiento',
                'blueprint'             => 'studio',
                'name'                  => 'Crecimiento',
                'price_usd'             => 149.00,
                'products_limit'        => 50,
                'services_limit'        => 6,
                'images_limit'          => 50,   // 1 foto × 50 productos
                'color_palettes'        => 17,
                'social_networks_limit' => null, // todas
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => true,
                'show_payment_methods'  => true,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'medium',
                'seo_level'             => 'medium',
                'whatsapp_numbers'      => 2,
                'whatsapp_hour_filter'  => true,
            ],

            [
                'slug'                  => 'studio-vision',
                'blueprint'             => 'studio',
                'name'                  => 'Visión',
                'price_usd'             => 199.00,
                'products_limit'        => null, // ilimitado — requiere D.8
                'services_limit'        => 9,
                'images_limit'          => null, // ilimitado — requiere D.8
                'color_palettes'        => 17,
                'social_networks_limit' => null,
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => true,
                'show_payment_methods'  => true,
                'show_faq'              => true,
                'show_cta_special'      => true,
                'analytics_level'       => 'advanced',
                'seo_level'             => 'advanced',
                'whatsapp_numbers'      => 2,
                'whatsapp_hour_filter'  => true,
            ],

            // ----------------------------------------------------------------
            // FOOD
            // ----------------------------------------------------------------

            [
                'slug'                  => 'food-basico',
                'blueprint'             => 'food',
                'name'                  => 'Básico',
                'price_usd'             => 9.00,
                'products_limit'        => 50,   // ítems en lista
                'services_limit'        => 0,
                'images_limit'          => 6,    // fotos de categoría
                'color_palettes'        => 5,
                'social_networks_limit' => 1,
                'show_dollar_rate'      => false,
                'show_header_top'       => false,
                'show_about_section'    => false,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'basic',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => false,
            ],

            [
                'slug'                  => 'food-semestral',
                'blueprint'             => 'food',
                'name'                  => 'Semestral',
                'price_usd'             => 39.00,
                'products_limit'        => 100,
                'services_limit'        => 0,
                'images_limit'          => 12,
                'color_palettes'        => 10,
                'social_networks_limit' => null,
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => false,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'basic',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => true,
            ],

            [
                'slug'                  => 'food-anual',
                'blueprint'             => 'food',
                'name'                  => 'Anual',
                'price_usd'             => 69.00,
                'products_limit'        => 150,
                'services_limit'        => 0,
                'images_limit'          => 18,
                'color_palettes'        => 17,
                'social_networks_limit' => null,
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => true,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'medium',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => true,
            ],

            // ----------------------------------------------------------------
            // CAT
            // ----------------------------------------------------------------

            [
                'slug'                  => 'cat-basico',
                'blueprint'             => 'cat',
                'name'                  => 'Básico',
                'price_usd'             => 9.00,
                'products_limit'        => 20,
                'services_limit'        => 0,
                'images_limit'          => 20,   // 1 foto × 20 productos
                'color_palettes'        => 5,
                'social_networks_limit' => 1,
                'show_dollar_rate'      => false,
                'show_header_top'       => false,
                'show_about_section'    => false,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'basic',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => false,
            ],

            [
                'slug'                  => 'cat-semestral',
                'blueprint'             => 'cat',
                'name'                  => 'Semestral',
                'price_usd'             => 39.00,
                'products_limit'        => 100,
                'services_limit'        => 0,
                'images_limit'          => 255,  // intención: 300 (3×100) — limitado a 255 hasta D.8
                'color_palettes'        => 10,
                'social_networks_limit' => null,
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => false,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'basic',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => false,
            ],

            [
                'slug'                  => 'cat-anual',
                'blueprint'             => 'cat',
                'name'                  => 'Anual',
                'price_usd'             => 69.00,
                'products_limit'        => null, // ilimitado — requiere D.8
                'services_limit'        => 0,
                'images_limit'          => null, // ilimitado (6 fotos × ilimitado) — requiere D.8
                'color_palettes'        => 17,
                'social_networks_limit' => null,
                'show_dollar_rate'      => true,
                'show_header_top'       => true,
                'show_about_section'    => true,
                'show_payment_methods'  => false,
                'show_faq'              => false,
                'show_cta_special'      => false,
                'analytics_level'       => 'medium',
                'seo_level'             => 'basic',
                'whatsapp_numbers'      => 1,
                'whatsapp_hour_filter'  => false,
            ],

        ];

        $canonicalSlugs = array_column($plans, 'slug');

        foreach ($plans as $data) {
            Plan::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // Eliminar cualquier plan obsoleto que no forme parte del catálogo canónico.
        // No se trunca: se preserva la integridad referencial de tenants activos
        // cuyos plan_id no correspondan a slugs obsoletos.
        Plan::whereNotIn('slug', $canonicalSlugs)->delete();
    }
}