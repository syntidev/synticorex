<?php
declare(strict_types=1);

// Imágenes a descargar por tenant
$images = [
    13 => [ // SINTIBURGUER
        'cat_arepas.webp' => 'https://images.unsplash.com/photo-1599040569104-8a82a5b88e28?w=800',
        'cat_hamburguesas.webp' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800',
        'cat_hotdogs.webp' => 'https://images.unsplash.com/photo-1612392062422-2e33c91a867e?w=800',
        'cat_pizza.webp' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=800',
        'cat_pepitos.webp' => 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=800',
        'cat_postres.webp' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800',
        'cat_bebidasfrias.webp' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800',
        'cat_bebidasc.webp' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800',
        'cat_licores.webp' => 'https://images.unsplash.com/photo-1608270586620-248524c67de9?w=800',
    ],
    14 => [ // DONAZ
        'cat_donas.webp' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800',
        'cat_tortas.webp' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800',
        'cat_quesillo.webp' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800',
        'cat_galletas.webp' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=800',
        'cat_bebidas.webp' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800',
    ],
];

foreach ($images as $tenantId => $imageMap) {
    $tenantDir = __DIR__ . "/../storage/app/private/tenants/{$tenantId}";
    
    // Crear directorio si no existe
    if (!is_dir($tenantDir)) {
        mkdir($tenantDir, 0755, true);
    }
    
    echo "\n📁 Tenant {$tenantId}:\n";
    
    foreach ($imageMap as $filename => $url) {
        $filepath = "{$tenantDir}/{$filename}";
        
        // Descargar imagen
        $imageData = file_get_contents($url);
        if ($imageData === false) {
            echo "  ❌ {$filename} - Error descargando\n";
            continue;
        }
        
        // Convertir a WebP si es necesario (Unsplash retorna JPEG)
        $image = imagecreatefromstring($imageData);
        if ($image === false) {
            echo "  ❌ {$filename} - Error creando imagen\n";
            continue;
        }
        
        // Resizar a máximo 800px en el lado más largo
        $width = imagesx($image);
        $height = imagesy($image);
        $maxSize = 800;
        
        if ($width > $maxSize || $height > $maxSize) {
            $ratio = min($maxSize / $width, $maxSize / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }
        
        // Guardar como WebP
        if (!imagewebp($image, $filepath, 80)) {
            echo "  ❌ {$filename} - Error guardando WebP\n";
            imagedestroy($image);
            continue;
        }
        
        imagedestroy($image);
        echo "  ✅ {$filename}\n";
    }
}

echo "\n✓ Descarga completada.\n";
