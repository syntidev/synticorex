<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorPalettesSeeder extends Seeder
{
    /**
     * 17 temas oficiales FlyonUI.
     * Los colores reales se aplican vía `data-theme` en el frontend.
     * El tema custom se maneja con CSS variables en frontend, NO en BD.
     *
     * Plan 1 (min_plan_id: 1)  10 temas base:
     *   light, ghibli, gourmet, perplexity, soft, dark, shadcn, vscode, pastel, corporate
     *
     * Plan 2 (min_plan_id: 2)  7 temas premium:
     *   black, luxury, slack, spotify, mintlify, claude, valorant
     */
    public function run(): void
    {
        DB::table('color_palettes')->truncate();

        $palettes = [

            // 
            // PLAN 1  temas base (min_plan_id: 1)
            // 

            [
                'name'             => 'Light',
                'slug'             => 'light',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Ghibli',
                'slug'             => 'ghibli',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Gourmet',
                'slug'             => 'gourmet',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Perplexity',
                'slug'             => 'perplexity',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Soft',
                'slug'             => 'soft',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Dark',
                'slug'             => 'dark',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Shadcn',
                'slug'             => 'shadcn',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'VSCode',
                'slug'             => 'vscode',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Pastel',
                'slug'             => 'pastel',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],
            [
                'name'             => 'Corporate',
                'slug'             => 'corporate',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 1,
                'category'         => null,
            ],

            // 
            // PLAN 2  temas premium (min_plan_id: 2)
            // 

            [
                'name'             => 'Black',
                'slug'             => 'black',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Luxury',
                'slug'             => 'luxury',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Slack',
                'slug'             => 'slack',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Spotify',
                'slug'             => 'spotify',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Mintlify',
                'slug'             => 'mintlify',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Claude',
                'slug'             => 'claude',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
            [
                'name'             => 'Valorant',
                'slug'             => 'valorant',
                'primary_color'    => null,
                'secondary_color'  => null,
                'accent_color'     => null,
                'background_color' => null,
                'text_color'       => null,
                'min_plan_id'      => 2,
                'category'         => null,
            ],
        ];

        DB::table('color_palettes')->insert($palettes);

        $this->command->info(' 17 temas FlyonUI sembrados (10 Plan 1 + 7 Plan 2).');
    }
}
