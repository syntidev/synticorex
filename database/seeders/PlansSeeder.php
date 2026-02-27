<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            // PLAN OPORTUNIDAD ($99)
            [
                'slug' => 'oportunidad',
                'name' => 'OPORTUNIDAD',
                'price_usd' => 99.00,
                'products_limit' => 6,
                'services_limit' => 3,
                'images_limit' => 15,
                'color_palettes' => 5,
                'social_networks_limit' => 1,
                'show_dollar_rate' => false,
                'show_header_top' => false,
                'show_about_section' => false,
                'show_payment_methods' => false,
                'show_faq' => false,
                'show_cta_special' => false,
                'analytics_level' => 'basic',
                'seo_level' => 'basic',
                'whatsapp_numbers' => 1,
                'whatsapp_hour_filter' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // PLAN CRECIMIENTO ($149)
            [
                'slug' => 'crecimiento',
                'name' => 'CRECIMIENTO',
                'price_usd' => 149.00,
                'products_limit' => 12,
                'services_limit' => 6,
                'images_limit' => 25,
                'color_palettes' => 10,
                'social_networks_limit' => null,
                'show_dollar_rate' => true,
                'show_header_top' => true,
                'show_about_section' => true,
                'show_payment_methods' => true,
                'show_faq' => false,
                'show_cta_special' => false,
                'analytics_level' => 'medium',
                'seo_level' => 'medium',
                'whatsapp_numbers' => 2,
                'whatsapp_hour_filter' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // PLAN VISIÓN ($199)
            [
                'slug' => 'vision',
                'name' => 'VISIÓN',
                'price_usd' => 199.00,
                'products_limit' => 18,
                'services_limit' => 9,
                'images_limit' => 70,
                'color_palettes' => 20,
                'social_networks_limit' => null,
                'show_dollar_rate' => true,
                'show_header_top' => true,
                'show_about_section' => true,
                'show_payment_methods' => true,
                'show_faq' => true,
                'show_cta_special' => true,
                'analytics_level' => 'advanced',
                'seo_level' => 'advanced',
                'whatsapp_numbers' => 2,
                'whatsapp_hour_filter' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('plans')->insert($plans);
    }
}