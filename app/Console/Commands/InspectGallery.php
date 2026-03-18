<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class InspectGallery extends Command
{
    protected $signature = 'gallery:inspect';

    protected $description = 'Inspeccionar tabla y modelo product_gallery_images';

    public function handle(): int
    {
        // Ver si existe la tabla
        $this->line('=== TABLA product_gallery_images ===');
        if (Schema::hasTable('product_gallery_images')) {
            $columns = Schema::getColumnListing('product_gallery_images');
            $this->line('✅ EXISTE: ' . implode(', ', $columns));
        } else {
            $this->line('❌ NO EXISTE');
        }

        $this->newLine();

        // Ver migraciones de gallery
        $this->line('=== MIGRACIONES CON "gallery" ===');
        $files = glob(database_path('migrations/*gallery*'));
        if (!empty($files)) {
            foreach ($files as $file) {
                $this->line('  - ' . basename($file));
            }
        } else {
            $this->line('  ❌ No hay migraciones con "gallery"');
        }

        $this->newLine();

        // Ver si el modelo existe
        $this->line('=== MODELO ProductGalleryImage ===');
        $this->line(class_exists('App\Models\ProductGalleryImage') 
            ? '✅ EXISTE' 
            : '❌ NO EXISTE');

        // Lista de modelos relacionados a producto
        $this->newLine();
        $this->line('=== MODELOS DISPONIBLES EN app/Models ===');
        $modelPath = app_path('Models');
        $files = scandir($modelPath);
        $models = array_filter($files, fn ($f) => str_ends_with($f, '.php') && $f !== '.');
        foreach ($models as $file) {
            $class = 'App\Models\\' . basename($file, '.php');
            if (class_exists($class)) {
                $this->line('  ✓ ' . basename($file, '.php'));
            }
        }

        return 0;
    }
}
