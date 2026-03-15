<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            ['old_slugs' => ['oportunidad', 'studio-oportunidad'], 'new_slug' => 'studio-oportunidad', 'new_name' => 'Oportunidad', 'blueprint' => 'studio'],
            ['old_slugs' => ['crecimiento', 'studio-crecimiento'], 'new_slug' => 'studio-crecimiento', 'new_name' => 'Crecimiento', 'blueprint' => 'studio'],
            ['old_slugs' => ['vision', 'studio-vision'], 'new_slug' => 'studio-vision', 'new_name' => 'Visión', 'blueprint' => 'studio'],
            ['old_slugs' => ['food-basico', 'food-oportunidad'], 'new_slug' => 'food-oportunidad', 'new_name' => 'Oportunidad', 'blueprint' => 'food'],
            ['old_slugs' => ['food-semestral', 'food-crecimiento'], 'new_slug' => 'food-crecimiento', 'new_name' => 'Crecimiento', 'blueprint' => 'food'],
            ['old_slugs' => ['food-anual', 'food-vision'], 'new_slug' => 'food-vision', 'new_name' => 'Visión', 'blueprint' => 'food'],
            ['old_slugs' => ['cat-basico', 'cat-oportunidad'], 'new_slug' => 'cat-oportunidad', 'new_name' => 'Oportunidad', 'blueprint' => 'cat'],
            ['old_slugs' => ['cat-semestral', 'cat-crecimiento'], 'new_slug' => 'cat-crecimiento', 'new_name' => 'Crecimiento', 'blueprint' => 'cat'],
            ['old_slugs' => ['cat-anual', 'cat-vision'], 'new_slug' => 'cat-vision', 'new_name' => 'Visión', 'blueprint' => 'cat'],
        ];

        foreach ($updates as $index => $update) {
            DB::table('plans')
                ->whereIn('slug', $update['old_slugs'])
                ->update([
                    'slug' => '__plan_normalize_' . $index,
                    'name' => $update['new_name'],
                    'blueprint' => $update['blueprint'],
                ]);
        }

        foreach ($updates as $index => $update) {
            DB::table('plans')
                ->where('slug', '__plan_normalize_' . $index)
                ->update([
                    'slug' => $update['new_slug'],
                    'name' => $update['new_name'],
                    'blueprint' => $update['blueprint'],
                ]);
        }
    }

    public function down(): void
    {
        $revertMap = [
            'studio-oportunidad' => ['slug' => 'oportunidad', 'name' => 'OPORTUNIDAD', 'blueprint' => 'studio'],
            'studio-crecimiento' => ['slug' => 'crecimiento', 'name' => 'CRECIMIENTO', 'blueprint' => 'studio'],
            'studio-vision' => ['slug' => 'vision', 'name' => 'VISIÓN', 'blueprint' => 'studio'],
            'food-oportunidad' => ['slug' => 'food-basico', 'name' => 'Oportunidad', 'blueprint' => 'food'],
            'food-crecimiento' => ['slug' => 'food-semestral', 'name' => 'Crecimiento', 'blueprint' => 'food'],
            'food-vision' => ['slug' => 'food-anual', 'name' => 'Visión', 'blueprint' => 'food'],
            'cat-oportunidad' => ['slug' => 'cat-basico', 'name' => 'Oportunidad', 'blueprint' => 'cat'],
            'cat-crecimiento' => ['slug' => 'cat-semestral', 'name' => 'Crecimiento', 'blueprint' => 'cat'],
            'cat-vision' => ['slug' => 'cat-anual', 'name' => 'Visión', 'blueprint' => 'cat'],
        ];

        foreach ($revertMap as $currentSlug => $revert) {
            DB::table('plans')
                ->where('slug', $currentSlug)
                ->update($revert);
        }
    }
};