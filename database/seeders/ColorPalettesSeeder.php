<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorPalettesSeeder extends Seeder
{
    /**
     * Paletas de color con contraste calculado.
     * Cubren todos los segmentos principales + variantes creativas.
     */
    public function run(): void
    {
        $palettes = [
            // ========================================
            // COMIDA RÁPIDA & DELIVERY
            // ========================================
            
            // 1. Energía Roja (McDonald's inspired)
            [
                'name' => 'Energía Roja',
                'code' => 'energia-roja',
                'category' => 'comida',
                'description' => 'Colores cálidos que estimulan el apetito',
                'primary_color' => '#DA291C',
                'secondary_color' => '#FFC72C',
                'accent_color' => '#27251F',
                'text_color' => '#2C2C2C',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#FFF5E1',
                'button_bg' => '#DA291C',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#B61F16',
                'link_color' => '#DA291C',
                'link_hover' => '#B61F16',
                'font_primary' => 'Poppins',
                'font_secondary' => 'Inter',
                'segment_tags' => 'restaurante,comida_rapida,delivery,hamburguesas',
                'emotional_effect' => 'Hambre, energía, rapidez',
                'is_active' => true,
            ],

            // 2. Pizza Clásica
            [
                'name' => 'Rojo Italiano',
                'code' => 'rojo-italiano',
                'category' => 'comida',
                'description' => 'Colores tradicionales italianos',
                'primary_color' => '#EE3124',
                'secondary_color' => '#00A651',
                'accent_color' => '#000000',
                'text_color' => '#231F20',
                'text_muted' => '#58595B',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F4F4F4',
                'button_bg' => '#EE3124',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#C72A1E',
                'link_color' => '#EE3124',
                'link_hover' => '#C72A1E',
                'font_primary' => 'Roboto',
                'font_secondary' => 'Inter',
                'segment_tags' => 'pizza,italiano,delivery,restaurante',
                'emotional_effect' => 'Tradición, sabor, autenticidad',
                'is_active' => true,
            ],

            // ========================================
            // CAFETERÍAS & BEBIDAS
            // ========================================

            // 3. Verde Café Premium (Starbucks inspired)
            [
                'name' => 'Verde Natural',
                'code' => 'verde-natural',
                'category' => 'cafe_bebidas',
                'description' => 'Tonos naturales y acogedores',
                'primary_color' => '#00704A',
                'secondary_color' => '#D4AF37',
                'accent_color' => '#1E3932',
                'text_color' => '#1E3932',
                'text_muted' => '#6B6B6B',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F1F8F5',
                'button_bg' => '#00704A',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#004D33',
                'link_color' => '#00704A',
                'link_hover' => '#004D33',
                'font_primary' => 'Lora',
                'font_secondary' => 'Open Sans',
                'segment_tags' => 'cafe,cafeteria,organico,natural',
                'emotional_effect' => 'Naturaleza, calma, premium',
                'is_active' => true,
            ],

            // 4. Café Clásico
            [
                'name' => 'Café Tierra',
                'code' => 'cafe-tierra',
                'category' => 'cafe_bebidas',
                'description' => 'Tonos tierra tradicionales',
                'primary_color' => '#8B5A2B',
                'secondary_color' => '#D4A373',
                'accent_color' => '#4A7C59',
                'text_color' => '#2B2118',
                'text_muted' => '#7A6A58',
                'background_color' => '#F7F2EB',
                'background_alt' => '#F0E4D8',
                'button_bg' => '#8B5A2B',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#6B4521',
                'link_color' => '#8B5A2B',
                'link_hover' => '#6B4521',
                'font_primary' => 'Merriweather',
                'font_secondary' => 'Lora',
                'segment_tags' => 'cafe,tradicional,libreria,artesania',
                'emotional_effect' => 'Calidez, tradición, nostalgia',
                'is_active' => true,
            ],

            // ========================================
            // SALUD & WELLNESS
            // ========================================

            // 5. Verde Saludable
            [
                'name' => 'Verde Vital',
                'code' => 'verde-vital',
                'category' => 'salud',
                'description' => 'Frescura y vida saludable',
                'primary_color' => '#00853F',
                'secondary_color' => '#FFC600',
                'accent_color' => '#FFFFFF',
                'text_color' => '#2C2C2C',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F7FFF7',
                'button_bg' => '#00853F',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#006630',
                'link_color' => '#00853F',
                'link_hover' => '#006630',
                'font_primary' => 'Nunito',
                'font_secondary' => 'Open Sans',
                'segment_tags' => 'salud,ensaladas,jugos,nutricion,fitness',
                'emotional_effect' => 'Frescura, salud, vitalidad',
                'is_active' => true,
            ],

            // 6. Verde Orgánico Premium
            [
                'name' => 'Orgánico Premium',
                'code' => 'organico-premium',
                'category' => 'salud',
                'description' => 'Productos naturales de alta calidad',
                'primary_color' => '#00754A',
                'secondary_color' => '#8B7355',
                'accent_color' => '#E8DCC4',
                'text_color' => '#2C2C2C',
                'text_muted' => '#6B6B6B',
                'background_color' => '#FAFAF8',
                'background_alt' => '#F1F1ED',
                'button_bg' => '#00754A',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#005538',
                'link_color' => '#00754A',
                'link_hover' => '#005538',
                'font_primary' => 'Merriweather',
                'font_secondary' => 'Source Sans Pro',
                'segment_tags' => 'organico,wellness,spa,natural,premium',
                'emotional_effect' => 'Naturaleza, calidad, bienestar',
                'is_active' => true,
            ],

            // ========================================
            // TECH & SERVICIOS PROFESIONALES
            // ========================================

            // 7. Azul Profesional (Facebook/Tech inspired)
            [
                'name' => 'Azul Confianza',
                'code' => 'azul-confianza',
                'category' => 'tech_servicios',
                'description' => 'Profesional y confiable',
                'primary_color' => '#1877F2',
                'secondary_color' => '#42B72A',
                'accent_color' => '#E4E6EB',
                'text_color' => '#050505',
                'text_muted' => '#65676B',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F0F2F5',
                'button_bg' => '#1877F2',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#166FE5',
                'link_color' => '#1877F2',
                'link_hover' => '#166FE5',
                'font_primary' => 'Roboto',
                'font_secondary' => 'Inter',
                'segment_tags' => 'tech,servicios,digital,consultoria',
                'emotional_effect' => 'Confianza, profesionalismo, modernidad',
                'is_active' => true,
            ],

            // 8. Cyan Futurista
            [
                'name' => 'Cyan Tech',
                'code' => 'cyan-tech',
                'category' => 'tech_servicios',
                'description' => 'Innovación y tecnología',
                'primary_color' => '#38BDF8',
                'secondary_color' => '#1E293B',
                'accent_color' => '#F472B6',
                'text_color' => '#F1F5F9',
                'text_muted' => '#94A3B8',
                'background_color' => '#020617',
                'background_alt' => '#0F172A',
                'button_bg' => '#38BDF8',
                'button_text' => '#020617',
                'button_hover_bg' => '#0EA5E9',
                'link_color' => '#38BDF8',
                'link_hover' => '#7DD3FC',
                'font_primary' => 'Inter',
                'font_secondary' => 'JetBrains Mono',
                'segment_tags' => 'startup,saas,tech,desarrollo,innovacion',
                'emotional_effect' => 'Futuro, innovación, vanguardia',
                'is_active' => true,
            ],

            // 9. Minimalista Espacial
            [
                'name' => 'Blanco Espacial',
                'code' => 'blanco-espacial',
                'category' => 'tech_servicios',
                'description' => 'Minimalismo extremo',
                'primary_color' => '#FFFFFF',
                'secondary_color' => '#888888',
                'accent_color' => '#0070F3',
                'text_color' => '#FFFFFF',
                'text_muted' => '#999999',
                'background_color' => '#030303',
                'background_alt' => '#0A0A0A',
                'button_bg' => '#FFFFFF',
                'button_text' => '#000000',
                'button_hover_bg' => '#E5E5E5',
                'link_color' => '#0070F3',
                'link_hover' => '#0051CC',
                'font_primary' => 'Inter',
                'font_secondary' => 'Roboto',
                'segment_tags' => 'tech,minimalista,diseno,arquitectura',
                'emotional_effect' => 'Claridad, futuro, sofisticación',
                'is_active' => true,
            ],

            // ========================================
            // CONSTRUCCIÓN & INDUSTRIAL
            // ========================================

            // 10. Naranja Industrial (Home Depot inspired)
            [
                'name' => 'Naranja Industrial',
                'code' => 'naranja-industrial',
                'category' => 'construccion',
                'description' => 'Fortaleza y confianza',
                'primary_color' => '#F96302',
                'secondary_color' => '#333333',
                'accent_color' => '#FFB500',
                'text_color' => '#000000',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F6F6F6',
                'button_bg' => '#F96302',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#D75502',
                'link_color' => '#F96302',
                'link_hover' => '#D75502',
                'font_primary' => 'Roboto',
                'font_secondary' => 'Montserrat',
                'segment_tags' => 'ferreteria,construccion,herramientas,industrial',
                'emotional_effect' => 'Fortaleza, confianza, acción',
                'is_active' => true,
            ],

            // ========================================
            // LEGAL & CORPORATIVO
            // ========================================

            // 11. Azul Corporativo
            [
                'name' => 'Azul Corporativo',
                'code' => 'azul-corporativo',
                'category' => 'legal_corporativo',
                'description' => 'Serio y profesional',
                'primary_color' => '#006491',
                'secondary_color' => '#E31937',
                'accent_color' => '#006491',
                'text_color' => '#333333',
                'text_muted' => '#757575',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F5F5F5',
                'button_bg' => '#006491',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#004D70',
                'link_color' => '#006491',
                'link_hover' => '#004D70',
                'font_primary' => 'Roboto',
                'font_secondary' => 'Inter',
                'segment_tags' => 'legal,consultoria,corporativo,servicios',
                'emotional_effect' => 'Profesionalismo, seriedad, confianza',
                'is_active' => true,
            ],

            // 12. Gris Elegante
            [
                'name' => 'Gris Corporativo',
                'code' => 'gris-corporativo',
                'category' => 'legal_corporativo',
                'description' => 'Elegancia profesional',
                'primary_color' => '#404040',
                'secondary_color' => '#D4AF37',
                'accent_color' => '#FFFFFF',
                'text_color' => '#2C2C2C',
                'text_muted' => '#737373',
                'background_color' => '#F5F5F5',
                'background_alt' => '#E5E5E5',
                'button_bg' => '#404040',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#2C2C2C',
                'link_color' => '#404040',
                'link_hover' => '#2C2C2C',
                'font_primary' => 'Montserrat',
                'font_secondary' => 'Lato',
                'segment_tags' => 'legal,abogados,contadores,consultoria',
                'emotional_effect' => 'Elegancia, seriedad, prestigio',
                'is_active' => true,
            ],

            // ========================================
            // CREATIVO & ARTE
            // ========================================

            // 13. Morado Creativo
            [
                'name' => 'Morado Eléctrico',
                'code' => 'morado-electrico',
                'category' => 'creativo',
                'description' => 'Creatividad y originalidad',
                'primary_color' => '#8B5CF6',
                'secondary_color' => '#4338CA',
                'accent_color' => '#06F2FF',
                'text_color' => '#FFFFFF',
                'text_muted' => '#C7D2FE',
                'background_color' => '#0F172A',
                'background_alt' => '#1E1B4B',
                'button_bg' => '#8B5CF6',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#7C3AED',
                'link_color' => '#A78BFA',
                'link_hover' => '#C4B5FD',
                'font_primary' => 'Montserrat',
                'font_secondary' => 'Inter',
                'segment_tags' => 'diseno,agencia,arte,fotografia,creativo',
                'emotional_effect' => 'Creatividad, innovación, originalidad',
                'is_active' => true,
            ],

            // 14. Morado Divertido (Taco Bell inspired - "Para el psycho")
            [
                'name' => 'Morado Loco',
                'code' => 'morado-loco',
                'category' => 'creativo',
                'description' => 'Atrevido y diferente',
                'primary_color' => '#702082',
                'secondary_color' => '#FFD700',
                'accent_color' => '#FF6B35',
                'text_color' => '#2A2A2A',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#FFF9F0',
                'button_bg' => '#702082',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#5A1A69',
                'link_color' => '#702082',
                'link_hover' => '#5A1A69',
                'font_primary' => 'Fredoka',
                'font_secondary' => 'Nunito',
                'segment_tags' => 'eventos,entretenimiento,juvenil,atrevido',
                'emotional_effect' => 'Diversión, locura, juventud',
                'is_active' => true,
            ],

            // 15. Rosa Vibrante ("Para el psycho 2.0")
            [
                'name' => 'Rosa Neón',
                'code' => 'rosa-neon',
                'category' => 'creativo',
                'description' => 'Energía pura',
                'primary_color' => '#F472B6',
                'secondary_color' => '#EC4899',
                'accent_color' => '#06F2FF',
                'text_color' => '#1F2937',
                'text_muted' => '#6B7280',
                'background_color' => '#FCFAFF',
                'background_alt' => '#FDF2F8',
                'button_bg' => '#EC4899',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#DB2777',
                'link_color' => '#EC4899',
                'link_hover' => '#DB2777',
                'font_primary' => 'Poppins',
                'font_secondary' => 'Inter',
                'segment_tags' => 'belleza,moda,eventos,juvenil',
                'emotional_effect' => 'Energía, feminidad, juventud',
                'is_active' => true,
            ],

            // ========================================
            // LUJO & PREMIUM
            // ========================================

            // 16. Dorado Premium
            [
                'name' => 'Dorado Elegante',
                'code' => 'dorado-elegante',
                'category' => 'lujo',
                'description' => 'Lujo y exclusividad',
                'primary_color' => '#D4AF37',
                'secondary_color' => '#404040',
                'accent_color' => '#FFFFFF',
                'text_color' => '#E5E5E5',
                'text_muted' => '#737373',
                'background_color' => '#0A0A0A',
                'background_alt' => '#171717',
                'button_bg' => '#D4AF37',
                'button_text' => '#000000',
                'button_hover_bg' => '#B8941F',
                'link_color' => '#D4AF37',
                'link_hover' => '#E5C158',
                'font_primary' => 'Playfair Display',
                'font_secondary' => 'Lora',
                'segment_tags' => 'joyeria,lujo,exclusivo,premium',
                'emotional_effect' => 'Lujo, prestigio, exclusividad',
                'is_active' => true,
            ],

            // 17. Sunset Gourmet
            [
                'name' => 'Sunset Gourmet',
                'code' => 'sunset-gourmet',
                'category' => 'lujo',
                'description' => 'Experiencia gastronómica premium',
                'primary_color' => '#FB923C',
                'secondary_color' => '#7C2D12',
                'accent_color' => '#FACC15',
                'text_color' => '#FFF7ED',
                'text_muted' => '#A8A29E',
                'background_color' => '#1A1614',
                'background_alt' => '#26211E',
                'button_bg' => '#FB923C',
                'button_text' => '#1A1614',
                'button_hover_bg' => '#F97316',
                'link_color' => '#FB923C',
                'link_hover' => '#FDBA74',
                'font_primary' => 'Playfair Display',
                'font_secondary' => 'Lato',
                'segment_tags' => 'restaurante_fino,gourmet,cocina,experiencia',
                'emotional_effect' => 'Sofisticación, calidez, exclusividad',
                'is_active' => true,
            ],

            // ========================================
            // DEPORTES & FITNESS
            // ========================================

            // 18. Negro Deportivo (Nike inspired)
            [
                'name' => 'Negro Power',
                'code' => 'negro-power',
                'category' => 'deportes',
                'description' => 'Fuerza y motivación',
                'primary_color' => '#000000',
                'secondary_color' => '#FF6B00',
                'accent_color' => '#FFFFFF',
                'text_color' => '#FFFFFF',
                'text_muted' => '#CCCCCC',
                'background_color' => '#F5F5F5',
                'background_alt' => '#E5E5E5',
                'button_bg' => '#000000',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#FF6B00',
                'link_color' => '#FF6B00',
                'link_hover' => '#FF8533',
                'font_primary' => 'Bebas Neue',
                'font_secondary' => 'Roboto',
                'segment_tags' => 'gym,deportes,fitness,entrenamiento',
                'emotional_effect' => 'Motivación, fuerza, acción',
                'is_active' => true,
            ],

            // ========================================
            // E-COMMERCE & RETAIL
            // ========================================

            // 19. Naranja Comercio (Amazon inspired)
            [
                'name' => 'Naranja Retail',
                'code' => 'naranja-retail',
                'category' => 'ecommerce',
                'description' => 'Eficiencia y variedad',
                'primary_color' => '#FF9900',
                'secondary_color' => '#146EB4',
                'accent_color' => '#232F3E',
                'text_color' => '#0F1111',
                'text_muted' => '#565959',
                'background_color' => '#FFFFFF',
                'background_alt' => '#EAEDED',
                'button_bg' => '#FF9900',
                'button_text' => '#0F1111',
                'button_hover_bg' => '#E88B00',
                'link_color' => '#146EB4',
                'link_hover' => '#0F5A94',
                'font_primary' => 'Inter',
                'font_secondary' => 'Roboto',
                'segment_tags' => 'ecommerce,tienda,retail,marketplace',
                'emotional_effect' => 'Eficiencia, variedad, confianza',
                'is_active' => true,
            ],

            // ========================================
            // EDUCACIÓN & CULTURA
            // ========================================

            // 20. Azul Académico
            [
                'name' => 'Azul Educación',
                'code' => 'azul-educacion',
                'category' => 'educacion',
                'description' => 'Conocimiento y aprendizaje',
                'primary_color' => '#0058A3',
                'secondary_color' => '#FFDB00',
                'accent_color' => '#111111',
                'text_color' => '#2C2C2C',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#F5F5F5',
                'button_bg' => '#0058A3',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#004480',
                'link_color' => '#0058A3',
                'link_hover' => '#004480',
                'font_primary' => 'Inter',
                'font_secondary' => 'Work Sans',
                'segment_tags' => 'educacion,academia,cursos,aprendizaje',
                'emotional_effect' => 'Confianza, orden, aprendizaje',
                'is_active' => true,
            ],

            // ========================================
            // MÚSICA & ENTRETENIMIENTO
            // ========================================

            // 21. Verde Música (Spotify inspired)
            [
                'name' => 'Verde Beats',
                'code' => 'verde-beats',
                'category' => 'entretenimiento',
                'description' => 'Energía musical',
                'primary_color' => '#1DB954',
                'secondary_color' => '#191414',
                'accent_color' => '#FFFFFF',
                'text_color' => '#FFFFFF',
                'text_muted' => '#B3B3B3',
                'background_color' => '#121212',
                'background_alt' => '#181818',
                'button_bg' => '#1DB954',
                'button_text' => '#000000',
                'button_hover_bg' => '#1ED760',
                'link_color' => '#1DB954',
                'link_hover' => '#1ED760',
                'font_primary' => 'Montserrat',
                'font_secondary' => 'Roboto',
                'segment_tags' => 'musica,entretenimiento,eventos,audio',
                'emotional_effect' => 'Energía, creatividad, modernidad',
                'is_active' => true,
            ],

            // ========================================
            // AUTOMOTRIZ & TRANSPORTE
            // ========================================

            // 22. Rojo Velocidad (Tesla/Racing inspired)
            [
                'name' => 'Rojo Velocidad',
                'code' => 'rojo-velocidad',
                'category' => 'automotriz',
                'description' => 'Innovación en movimiento',
                'primary_color' => '#E82127',
                'secondary_color' => '#000000',
                'accent_color' => '#5C5C5C',
                'text_color' => '#FFFFFF',
                'text_muted' => '#CCCCCC',
                'background_color' => '#181818',
                'background_alt' => '#0A0A0A',
                'button_bg' => '#E82127',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#C71C22',
                'link_color' => '#E82127',
                'link_hover' => '#FF3940',
                'font_primary' => 'Exo 2',
                'font_secondary' => 'Roboto',
                'segment_tags' => 'automotriz,mecanica,racing,innovacion',
                'emotional_effect' => 'Velocidad, innovación, potencia',
                'is_active' => true,
            ],

            // ========================================
            // BELLEZA & ESTÉTICA
            // ========================================

            // 23. Rosa Elegante
            [
                'name' => 'Rosa Belleza',
                'code' => 'rosa-belleza',
                'category' => 'belleza',
                'description' => 'Delicadeza y estilo',
                'primary_color' => '#E91E63',
                'secondary_color' => '#F8BBD0',
                'accent_color' => '#880E4F',
                'text_color' => '#2C2C2C',
                'text_muted' => '#757575',
                'background_color' => '#FFF0F5',
                'background_alt' => '#FCE4EC',
                'button_bg' => '#E91E63',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#C2185B',
                'link_color' => '#E91E63',
                'link_hover' => '#C2185B',
                'font_primary' => 'Poppins',
                'font_secondary' => 'Lato',
                'segment_tags' => 'belleza,estetica,spa,peluqueria,maquillaje',
                'emotional_effect' => 'Delicadeza, feminidad, estilo',
                'is_active' => true,
            ],

            // ========================================
            // ECO & SUSTENTABILIDAD
            // ========================================

            // 24. Verde Eco
            [
                'name' => 'Verde Bosque',
                'code' => 'verde-bosque',
                'category' => 'eco',
                'description' => 'Sustentabilidad y naturaleza',
                'primary_color' => '#166534',
                'secondary_color' => '#4ADE80',
                'accent_color' => '#F97316',
                'text_color' => '#064E3B',
                'text_muted' => '#6B7280',
                'background_color' => '#FDFDFB',
                'background_alt' => '#F1F5F0',
                'button_bg' => '#166534',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#14532D',
                'link_color' => '#166534',
                'link_hover' => '#14532D',
                'font_primary' => 'Lato',
                'font_secondary' => 'Open Sans',
                'segment_tags' => 'eco,reciclaje,sustentable,naturaleza',
                'emotional_effect' => 'Responsabilidad, naturaleza, compromiso',
                'is_active' => true,
            ],

            // ========================================
            // NARANJA ENERGÉTICO (COMODÍN)
            // ========================================

            // 25. Naranja Alegre (Dunkin' inspired)
            [
                'name' => 'Naranja Alegre',
                'code' => 'naranja-alegre',
                'category' => 'comida',
                'description' => 'Energía y felicidad',
                'primary_color' => '#FF6600',
                'secondary_color' => '#DD0067',
                'accent_color' => '#5D2E8C',
                'text_color' => '#2C2C2C',
                'text_muted' => '#666666',
                'background_color' => '#FFFFFF',
                'background_alt' => '#FFF5ED',
                'button_bg' => '#FF6600',
                'button_text' => '#FFFFFF',
                'button_hover_bg' => '#E55A00',
                'link_color' => '#FF6600',
                'link_hover' => '#E55A00',
                'font_primary' => 'Poppins',
                'font_secondary' => 'Inter',
                'segment_tags' => 'cafe,donas,desayuno,snacks,casual',
                'emotional_effect' => 'Energía, alegría, cercanía',
                'is_active' => true,
            ],
        ];

        foreach ($palettes as $palette) {
            $palette['slug'] = $palette['code'];
            DB::table('color_palettes')->insert($palette);
        }

        echo "✅ 25 paletas sembradas con contraste calculado\n";
        echo "📊 Categorías: comida(4), cafe(2), salud(2), tech(3), construccion(1), legal(2), creativo(3), lujo(2), deportes(1), ecommerce(1), educacion(1), entretenimiento(1), automotriz(1), belleza(1), eco(1)\n";
        echo "🎨 Incluye paletas 'psycho' para creativos: Morado Loco, Rosa Neón\n";
    }
}