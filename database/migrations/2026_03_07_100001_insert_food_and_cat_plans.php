<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('plans')->upsert(
            [
                ['slug'=>'food-basico',   'name'=>'Basico',    'blueprint'=>'food','price_usd'=>9.00, 'products_limit'=>50, 'services_limit'=>0,'images_limit'=>6, 'color_palettes'=>1,'whatsapp_numbers'=>1,'show_dollar_rate'=>false,'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
                ['slug'=>'food-semestral','name'=>'Semestral', 'blueprint'=>'food','price_usd'=>39.00,'products_limit'=>100,'services_limit'=>0,'images_limit'=>12,'color_palettes'=>3,'whatsapp_numbers'=>1,'show_dollar_rate'=>true, 'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
                ['slug'=>'food-anual',    'name'=>'Anual',     'blueprint'=>'food','price_usd'=>69.00,'products_limit'=>150,'services_limit'=>0,'images_limit'=>18,'color_palettes'=>5,'whatsapp_numbers'=>2,'show_dollar_rate'=>true, 'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
                ['slug'=>'cat-basico',    'name'=>'Basico',    'blueprint'=>'cat', 'price_usd'=>9.00, 'products_limit'=>20, 'services_limit'=>0,'images_limit'=>1, 'color_palettes'=>1,'whatsapp_numbers'=>1,'show_dollar_rate'=>false,'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
                ['slug'=>'cat-semestral', 'name'=>'Semestral', 'blueprint'=>'cat', 'price_usd'=>39.00,'products_limit'=>100,'services_limit'=>0,'images_limit'=>3, 'color_palettes'=>3,'whatsapp_numbers'=>1,'show_dollar_rate'=>false,'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
                ['slug'=>'cat-anual',     'name'=>'Anual',     'blueprint'=>'cat', 'price_usd'=>69.00,'products_limit'=>999,'services_limit'=>0,'images_limit'=>6, 'color_palettes'=>5,'whatsapp_numbers'=>1,'show_dollar_rate'=>false,'analytics_level'=>'basic','seo_level'=>'basic','created_at'=>$now,'updated_at'=>$now],
            ],
            uniqueBy: ['slug'],
            update: ['name','blueprint','price_usd','products_limit','services_limit','images_limit','color_palettes','whatsapp_numbers','show_dollar_rate','analytics_level','seo_level','updated_at']
        );
    }

    public function down(): void
    {
        DB::table('plans')->whereIn('slug', [
            'food-basico','food-semestral','food-anual',
            'cat-basico','cat-semestral','cat-anual',
        ])->whereNotExists(function ($query) {
            $query->select(DB::raw(1))->from('tenants')->whereColumn('tenants.plan_id','plans.id');
        })->delete();
    }
};