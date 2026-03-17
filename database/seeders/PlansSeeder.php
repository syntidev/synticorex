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
        // ----------------------------------------------------------------
        // STUDIO
        // ----------------------------------------------------------------

        Plan::updateOrCreate(['slug' => 'studio-oportunidad'], [
            'blueprint'             => 'studio',
            'name'                  => 'Oportunidad',
            'price_usd'             => 99.00,
            'products_limit'        => 20,
            'services_limit'        => 3,
            'images_limit'          => 20,
            'color_palettes'        => 10,
            'social_networks_limit' => 2,
            'show_dollar_rate'      => false,
            'show_header_top'       => false,
            'show_about_section'    => false,
            'show_payment_methods'  => true,
            'show_faq'              => false,
            'show_cta_special'      => false,
            'analytics_level'       => 'basic',
            'seo_level'             => 'basic',
            'whatsapp_numbers'      => 1,
            'whatsapp_hour_filter'  => false,
        ]);

        Plan::updateOrCreate(['slug' => 'studio-crecimiento'], [
            'blueprint'             => 'studio',
            'name'                  => 'Crecimiento',
            'price_usd'             => 149.00,
            'products_limit'        => 50,
            'services_limit'        => 6,
            'images_limit'          => 50,
            'color_palettes'        => 17,
            'social_networks_limit' => null,
            'show_dollar_rate'      => true,
            'show_header_top'       => true,
            'show_about_section'    => true,
            'show_payment_methods'  => true,
            'show_faq'              => true,
            'show_cta_special'      => false,
            'analytics_level'       => 'medium',
            'seo_level'             => 'medium',
            'whatsapp_numbers'      => 2,
            'whatsapp_hour_filter'  => true,
        ]);

        Plan::updateOrCreate(['slug' => 'studio-vision'], [
            'blueprint'             => 'studio',
            'name'                  => 'Visión',
            'price_usd'             => 199.00,
            'products_limit'        => 200,
            'services_limit'        => 9,
            'images_limit'          => null,
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
        ]);

        // ----------------------------------------------------------------
        // FOOD
        // ----------------------------------------------------------------

        Plan::updateOrCreate(['slug' => 'food-oportunidad'], [
            'blueprint'             => 'food',
            'name'                  => 'Oportunidad',
            'price_usd'             => 9.00,
            'products_limit'        => 50,
            'services_limit'        => 0,
            'images_limit'          => 6,
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
        ]);

        Plan::updateOrCreate(['slug' => 'food-crecimiento'], [
            'blueprint'             => 'food',
            'name'                  => 'Crecimiento',
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
            'analytics_level'       => 'medium',
            'seo_level'             => 'basic',
            'whatsapp_numbers'      => 1,
            'whatsapp_hour_filter'  => true,
        ]);

        Plan::updateOrCreate(['slug' => 'food-vision'], [
            'blueprint'             => 'food',
            'name'                  => 'Visión',
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
        ]);

        // ----------------------------------------------------------------
        // CAT
        // ----------------------------------------------------------------

        Plan::updateOrCreate(['slug' => 'cat-oportunidad'], [
            'blueprint'             => 'cat',
            'name'                  => 'Oportunidad',
            'price_usd'             => 9.00,
            'products_limit'        => 20,
            'services_limit'        => 0,
            'images_limit'          => 20,
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
        ]);

        Plan::updateOrCreate(['slug' => 'cat-crecimiento'], [
            'blueprint'             => 'cat',
            'name'                  => 'Crecimiento',
            'price_usd'             => 39.00,
            'products_limit'        => 100,
            'services_limit'        => 0,
            'images_limit'          => 255,
            'color_palettes'        => 10,
            'social_networks_limit' => null,
            'show_dollar_rate'      => true,
            'show_header_top'       => true,
            'show_about_section'    => false,
            'show_payment_methods'  => false,
            'show_faq'              => false,
            'show_cta_special'      => false,
            'analytics_level'       => 'medium',
            'seo_level'             => 'basic',
            'whatsapp_numbers'      => 1,
            'whatsapp_hour_filter'  => false,
        ]);

        Plan::updateOrCreate(['slug' => 'cat-vision'], [
            'blueprint'             => 'cat',
            'name'                  => 'Visión',
            'price_usd'             => 69.00,
            'products_limit'        => 250,
            'services_limit'        => 0,
            'images_limit'          => null,
            'color_palettes'        => 17,
            'social_networks_limit' => null,
            'show_dollar_rate'      => true,
            'show_header_top'       => true,
            'show_about_section'    => true,
            'show_payment_methods'  => false,
            'show_faq'              => false,
            'show_cta_special'      => false,
            'analytics_level'       => 'advanced',
            'seo_level'             => 'basic',
            'whatsapp_numbers'      => 1,
            'whatsapp_hour_filter'  => false,
        ]);

        $canonicalSlugs = [
            'studio-oportunidad', 'studio-crecimiento', 'studio-vision',
            'food-oportunidad', 'food-crecimiento', 'food-vision',
            'cat-oportunidad', 'cat-crecimiento', 'cat-vision',
        ];

        // Eliminar cualquier plan obsoleto que no forme parte del catálogo canónico,
        // PERO solo si no tiene tenants activos (evita violación de FK).
        // Los planes obsoletos con tenants se preservan para integridad referencial.
        Plan::whereNotIn('slug', $canonicalSlugs)
            ->whereNotExists(function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('tenants')
                    ->whereColumn('tenants.plan_id', 'plans.id');
            })
            ->delete();
    }
}