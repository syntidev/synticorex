<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlyonUIThemesSeeder extends Seeder
{
    /**
     * 32 Temas Oficiales de FlyonUI
     * 
     * Fuente: https://flyonui.com/docs/customization/themes/#list-of-themes
     * 
     * Este seeder NO crea una tabla, solo documenta los temas disponibles.
     * Los temas se aplican via data-theme attribute en HTML root.
     * FlyonUI CSS contiene todas las variables CSS de cada tema.
     */
    public function run(): void
    {
        /**
         * ═══════════════════════════════════════════════════════════════════════
         * TEMAS OFICIALES FLYONUI (32 Total)
         * ═══════════════════════════════════════════════════════════════════════
         * 
         * Uso: <html data-theme="cupcake">
         * 
         * CATEGORÍAS:
         * - Light Default: light
         * - Dark Modes: dark, night, dim, black, dracula, business, synthwave, halloween
         * - Colorful/Vibrant: cupcake, bumblebee, valentine, aqua, lofi, pastel, cyberpunk, acid, lemonade
         * - Nature/Organic: emerald, garden, forest, autumn, coffee, winter
         * - Professional: corporate, luxury, wireframe, nord, sunset
         * - Retro: retro, cmyk
         * - Fantasy: fantasy
         * ═══════════════════════════════════════════════════════════════════════
         */
        
        $themes = [
            // ═══════════════════════════════════════════════════════════════════
            // DEFAULT THEMES
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'light',
                'name' => 'Light',
                'category' => 'default',
                'mode' => 'light',
                'description' => 'Tema claro por defecto - Limpio y profesional',
                'recommended_for' => 'Negocios generales, e-commerce, servicios profesionales',
            ],
            [
                'slug' => 'dark',
                'name' => 'Dark',
                'category' => 'default',
                'mode' => 'dark',
                'description' => 'Tema oscuro por defecto - Moderno y elegante',
                'recommended_for' => 'Tech, gaming, contenido nocturno',
            ],
            
            // ═══════════════════════════════════════════════════════════════════
            // COLORFUL & VIBRANT
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'cupcake',
                'name' => 'Cupcake',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Colores pastel dulces - Rosa y celeste',
                'recommended_for' => 'Pastelerías, dulcerías, productos infantiles, estética',
            ],
            [
                'slug' => 'bumblebee',
                'name' => 'Bumblebee',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Amarillo y negro vibrante - Energético',
                'recommended_for' => 'Delivery rápido, comida, construcción, transporte',
            ],
            [
                'slug' => 'valentine',
                'name' => 'Valentine',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Rosa romántico - Amor y dulzura',
                'recommended_for' => 'Florería, regalos, eventos románticos, spa',
            ],
            [
                'slug' => 'aqua',
                'name' => 'Aqua',
                'category' => 'colorful',
                'mode' => 'dark',
                'description' => 'Azul agua profundo - Fresco y tecnológico',
                'recommended_for' => 'Piscinas, agua, tecnología, salud',
            ],
            [
                'slug' => 'lofi',
                'name' => 'Lofi',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Colores suaves y relajados - Minimalista',
                'recommended_for' => 'Cafeterías, música, arte, diseño',
            ],
            [
                'slug' => 'pastel',
                'name' => 'Pastel',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Paleta pastel completa - Delicado',
                'recommended_for' => 'Moda femenina, bebés, decoración',
            ],
            [
                'slug' => 'cyberpunk',
                'name' => 'Cyberpunk',
                'category' => 'colorful',
                'mode' => 'dark',
                'description' => 'Neón y futurista - Vibrante oscuro',
                'recommended_for' => 'Gaming, tech, entretenimiento nocturno',
            ],
            [
                'slug' => 'acid',
                'name' => 'Acid',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Verde ácido y amarillo - Experimental',
                'recommended_for' => 'Jugos naturales, vegano, alternativo',
            ],
            [
                'slug' => 'lemonade',
                'name' => 'Lemonade',
                'category' => 'colorful',
                'mode' => 'light',
                'description' => 'Amarillo limón fresco - Veraniego',
                'recommended_for' => 'Bebidas, helados, verano, playa',
            ],
            
            // ═══════════════════════════════════════════════════════════════════
            // NATURE & ORGANIC
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'emerald',
                'name' => 'Emerald',
                'category' => 'nature',
                'mode' => 'light',
                'description' => 'Verde esmeralda - Natural y elegante',
                'recommended_for' => 'Orgánico, salud, naturaleza, eco-friendly',
            ],
            [
                'slug' => 'garden',
                'name' => 'Garden',
                'category' => 'nature',
                'mode' => 'light',
                'description' => 'Verde jardín - Fresco y natural',
                'recommended_for' => 'Plantas, jardinería, eco-productos',
            ],
            [
                'slug' => 'forest',
                'name' => 'Forest',
                'category' => 'nature',
                'mode' => 'dark',
                'description' => 'Verde bosque oscuro - Sofisticado',
                'recommended_for' => 'Outdoor, camping, aventura',
            ],
            [
                'slug' => 'autumn',
                'name' => 'Autumn',
                'category' => 'nature',
                'mode' => 'light',
                'description' => 'Colores otoñales - Cálido y acogedor',
                'recommended_for' => 'Cafés, libros, hogar, artesanías',
            ],
            [
                'slug' => 'coffee',
                'name' => 'Coffee',
                'category' => 'nature',
                'mode' => 'light',
                'description' => 'Tonos café y crema - Reconfortante',
                'recommended_for' => 'Cafeterías, chocolatería, panadería',
            ],
            [
                'slug' => 'winter',
                'name' => 'Winter',
                'category' => 'nature',
                'mode' => 'light',
                'description' => 'Azul frío invernal - Limpio y fresco',
                'recommended_for' => 'Aire acondicionado, tecnología, dental',
            ],
            
            // ═══════════════════════════════════════════════════════════════════
            // PROFESSIONAL & BUSINESS
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'corporate',
                'name' => 'Corporate',
                'category' => 'professional',
                'mode' => 'light',
                'description' => 'Azul corporativo - Serio y confiable',
                'recommended_for' => 'Empresas, finanzas, legal, consultoría',
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'category' => 'professional',
                'mode' => 'dark',
                'description' => 'Oscuro corporativo - Premium',
                'recommended_for' => 'Ejecutivos, lujo, tecnología B2B',
            ],
            [
                'slug' => 'luxury',
                'name' => 'Luxury',
                'category' => 'professional',
                'mode' => 'dark',
                'description' => 'Negro y dorado - Exclusivo',
                'recommended_for' => 'Joyería, autos de lujo, inmobiliaria premium',
            ],
            [
                'slug' => 'wireframe',
                'name' => 'Wireframe',
                'category' => 'professional',
                'mode' => 'light',
                'description' => 'Blanco y negro minimalista - Ultraligero',
                'recommended_for' => 'Portafolios, minimalismo, arquitectura',
            ],
            [
                'slug' => 'nord',
                'name' => 'Nord',
                'category' => 'professional',
                'mode' => 'dark',
                'description' => 'Paleta Nórdica - Sobrio y moderno',
                'recommended_for' => 'Tech, desarrollo, diseño, innovación',
            ],
            [
                'slug' => 'sunset',
                'name' => 'Sunset',
                'category' => 'professional',
                'mode' => 'light',
                'description' => 'Naranja y rosa atardecer - Cálido premium',
                'recommended_for' => 'Turismo, hoteles, restaurantes vista',
            ],
            
            // ═══════════════════════════════════════════════════════════════════
            // DARK MODES
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'night',
                'name' => 'Night',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Azul oscuro nocturno - Suave para los ojos',
                'recommended_for' => 'Apps nocturnas, lectura, astronomía',
            ],
            [
                'slug' => 'dim',
                'name' => 'Dim',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Gris oscuro - Menos contraste',
                'recommended_for' => 'Lectura prolongada, streaming',
            ],
            [
                'slug' => 'black',
                'name' => 'Black',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Negro puro - AMOLED friendly',
                'recommended_for' => 'Premium, ahorro batería, fotografía',
            ],
            [
                'slug' => 'dracula',
                'name' => 'Dracula',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Morado oscuro - Vampírico',
                'recommended_for' => 'Gaming, entertainment, misterio',
            ],
            [
                'slug' => 'synthwave',
                'name' => 'Synthwave',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Retro 80s neón - Nostálgico',
                'recommended_for' => 'Música, arte, retro gaming',
            ],
            [
                'slug' => 'halloween',
                'name' => 'Halloween',
                'category' => 'dark',
                'mode' => 'dark',
                'description' => 'Naranja y morado oscuro - Temático',
                'recommended_for' => 'Eventos, terror, octubre',
            ],
            
            // ═══════════════════════════════════════════════════════════════════
            // RETRO & SPECIAL
            // ═══════════════════════════════════════════════════════════════════
            [
                'slug' => 'retro',
                'name' => 'Retro',
                'category' => 'retro',
                'mode' => 'light',
                'description' => 'Estilo vintage - Nostálgico',
                'recommended_for' => 'Vintage, coleccionables, antigüedades',
            ],
            [
                'slug' => 'cmyk',
                'name' => 'CMYK',
                'category' => 'retro',
                'mode' => 'light',
                'description' => 'Colores de impresión - Diseño gráfico',
                'recommended_for' => 'Imprenta, diseño, agencias',
            ],
            [
                'slug' => 'fantasy',
                'name' => 'Fantasy',
                'category' => 'special',
                'mode' => 'light',
                'description' => 'Morado mágico - Fantástico',
                'recommended_for' => 'Juegos, fantasía, magia, esotérico',
            ],
        ];
        
        // Documentar en log (no crear tabla, solo referencia)
        $slugs = collect($themes)->pluck('slug')->implode(', ');
        $this->command->info('✅ 32 Temas FlyonUI documentados: ' . $slugs);
        $this->command->info('📝 Uso: <html data-theme="slug">');
        $this->command->newLine();
        $this->command->info('ℹ️  Este seeder solo documenta. Los temas están en FlyonUI CSS.');
    }
}
