<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class LandingSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section_key'   => 'hero',
                'section_label' => 'Hero principal',
                'content'       => [
                    'headline'      => 'Tu negocio, online en 24 horas',
                    'subheadline'   => 'Presencia digital profesional para negocios venezolanos',
                    'cta_primary'   => 'Crear mi página gratis',
                    'cta_secondary' => 'Ver demo',
                ],
                'sort_order' => 0,
            ],
            [
                'section_key'   => 'problema',
                'section_label' => 'Problema',
                'content'       => [
                    'headline' => '¿Por qué sigues sin presencia digital?',
                    'puntos'   => ['Caro', 'Complicado', 'Lento'],
                ],
                'sort_order' => 1,
            ],
            [
                'section_key'   => 'planes',
                'section_label' => 'Planes',
                'content'       => [
                    'headline'    => 'Planes que se adaptan a ti',
                    'subheadline' => 'Sin contratos. Sin sorpresas.',
                ],
                'sort_order' => 2,
            ],
            [
                'section_key'   => 'testimonios',
                'section_label' => 'Testimonios',
                'content'       => [
                    'headline' => 'Lo que dicen nuestros clientes',
                ],
                'sort_order' => 3,
            ],
            [
                'section_key'   => 'cta_final',
                'section_label' => 'CTA Final',
                'content'       => [
                    'headline' => 'Empieza hoy',
                    'boton'    => 'Quiero mi página',
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($sections as $section) {
            LandingSection::updateOrCreate(
                ['section_key' => $section['section_key']],
                [
                    'section_label' => $section['section_label'],
                    'content'       => $section['content'],
                    'sort_order'    => $section['sort_order'],
                ]
            );
        }
    }
}
