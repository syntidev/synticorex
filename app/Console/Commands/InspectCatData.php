<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class InspectCatData extends Command
{
    protected $signature = 'cat:inspect';

    protected $description = 'Inspeccionar estructura de datos CAT';

    public function handle(): int
    {
        // Estructura de tabla products
        $this->line('PRODUCTS COLUMNS:');
        $this->line(implode(', ', Schema::getColumnListing('products')));
        $this->newLine();

        // Productos de Nova Store (ID 17)
        $this->line('PRODUCTOS NOVASTORE (tenant_id=17):');
        Product::where('tenant_id', 17)
            ->get()
            ->each(function (Product $p) {
                $img = $p->image_url ?? ($p->image_filename ?? 'sin-img');
                $this->line(sprintf(
                    '%d | %s | cat:%s | sub:%s | imgs:%s',
                    $p->id,
                    $p->name,
                    $p->category_name ?? 'null',
                    $p->subcategory_name ?? 'null',
                    $img
                ));
            });

        $this->newLine();

        // Categorías únicas en Nova Store
        $this->line('CATEGORIAS UNICAS EN NOVASTORE:');
        Product::where('tenant_id', 17)
            ->distinct('category_name')
            ->pluck('category_name')
            ->each(fn ($cat) => $this->line('- ' . ($cat ?? 'NULL')));

        $this->newLine();

        // Subcategorías por categoría
        $this->line('SUBCATEGORIAS UNICAS POR CATEGORIA:');
        $categories = Product::where('tenant_id', 17)
            ->distinct('category_name')
            ->pluck('category_name');
        
        foreach ($categories as $cat) {
            $subs = Product::where('tenant_id', 17)
                ->where('category_name', $cat)
                ->distinct('subcategory_name')
                ->pluck('subcategory_name');
            
            $this->line('  ' . ($cat ?? 'NULL') . ':');
            $subs->each(fn ($sub) => $this->line('    - ' . ($sub ?? 'NULL')));
        }

        return 0;
    }
}
