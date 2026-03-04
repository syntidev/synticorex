<?php

declare(strict_types=1);

$allThemes = [
    // Originales
    'default', 'harvest', 'retro', 'ocean', 'bubblegum', 'autumn', 'moon', 'cashmere', 'olive',
    // Comida
    'sabor-tradicional', 'fuego-urbano', 'parrilla-moderna', 'casa-latina',
    // Dulces
    'rosa-vainilla', 'pistacho-suave', 'cielo-dulce', 'chocolate-caramelo',
    // Salud
    'azul-confianza', 'verde-calma',
    // Autoridad
    'azul-profesional', 'ejecutivo-oscuro', 'prestigio-clasico',
    // Oficios
    'industrial-pro', 'negro-impacto', 'metal-urbano',
    // Belleza
    'nude-elegante', 'rosa-studio', 'barber-clasico',
    // Fitness
    'fuerza-roja', 'verde-potencia', 'azul-electrico',
    // Educacion
    'azul-academico', 'verde-progreso', 'claro-simple',
];

return [
    'all' => $allThemes,

    'by_plan' => [
        1 => $allThemes,
        2 => $allThemes,
        3 => $allThemes,
    ],
];
