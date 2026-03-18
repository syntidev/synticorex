<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;

class SeedNovaStoreGallery extends Command
{
    protected $signature = 'seed:novastore-gallery {--tenant=17 : tenant_id target}';
    protected $description = 'Seed gallery images for products (default: tenant 17)';

    public function handle(): int
    {
        $tenantId = (int) $this->option('tenant');

        // Tenant 17: Nova Store (3 productos específicos)
        if ($tenantId === 17) {
            return $this->seedNovaStore();
        }

        // Genérico: primer producto del tenant con 2 imágenes fijas
        $product = Product::where('tenant_id', $tenantId)->first();
        if (!$product) {
            $this->error("No se encontró ningún producto para tenant_id={$tenantId}");
            return 1;
        }

        ProductImage::where('product_id', $product->id)->delete();
        ProductImage::create([
            'product_id'     => $product->id,
            'image_filename' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800',
            'position'       => 1,
        ]);
        ProductImage::create([
            'product_id'     => $product->id,
            'image_filename' => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=800',
            'position'       => 2,
        ]);
        $this->info($product->name . ' listo');

        return 0;
    }

    private function seedNovaStore(): int
    {
        $map = [
            'Aud'       => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
                'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800',
            ],
            'Mochila'   => [
                'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800',
                'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800',
            ],
            'Billetera' => [
                'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800',
                'https://images.unsplash.com/photo-1601924921557-45e6dea0a157?w=800',
            ],
        ];

        foreach ($map as $keyword => $imgs) {
            $product = Product::where('tenant_id', 17)
                ->where('name', 'like', '%' . $keyword . '%')
                ->first();

            if ($product) {
                ProductImage::where('product_id', $product->id)->delete();
                foreach ($imgs as $i => $url) {
                    ProductImage::create([
                        'product_id'     => $product->id,
                        'image_filename' => $url,
                        'position'       => $i + 1,
                    ]);
                }
                $this->line($product->name . ' → 2 imgs insertadas');
            } else {
                $this->warn($keyword . ': producto no encontrado');
            }
        }

        $this->info('DONE');
        return 0;
    }
}
