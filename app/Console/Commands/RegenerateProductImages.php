<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Service;
use App\Services\ProductImageGeneratorService;
use App\Services\ServiceImageGeneratorService;
use Illuminate\Console\Command;

class RegenerateProductImages extends Command
{
    protected $signature = 'images:regenerate';

    protected $description = 'Regenerate all product and service images with new naming pattern';

    public function handle(): int
    {
        $this->info('🎨 Regenerating images with new pattern...');

        $productGenerator = new ProductImageGeneratorService();
        $serviceGenerator = new ServiceImageGeneratorService();

        // Products
        $products = Product::all();
        $productsByTenant = $products->groupBy('tenant_id');

        foreach ($productsByTenant as $tenantId => $tenantProducts) {
            $this->info("\n📦 Generating product images for tenant {$tenantId}:");
            foreach ($tenantProducts as $index => $product) {
                try {
                    $filename = $productGenerator->generateProductImage(
                        $tenantId,
                        $product->name,
                        $product->tenant->business_segment,
                        $index + 1  // Use 1-based index for naming
                    );
                    $product->update(['image_filename' => $filename]);
                    $this->line("  ✅ Product {$product->id} ({$product->name}): {$filename}");
                } catch (\Exception $e) {
                    $this->warn("  ❌ Failed for product {$product->id}: {$e->getMessage()}");
                }
            }
        }

        // Services
        $services = Service::all();
        $servicesByTenant = $services->groupBy('tenant_id');

        foreach ($servicesByTenant as $tenantId => $tenantServices) {
            $this->info("\n🔧 Generating service images for tenant {$tenantId}:");
            foreach ($tenantServices as $index => $service) {
                try {
                    $filename = $serviceGenerator->generateServiceImage(
                        $tenantId,
                        $service->name,
                        $service->tenant->business_segment,
                        $index + 1  // Use 1-based index for naming
                    );
                    $service->update(['image_filename' => $filename]);
                    $this->line("  ✅ Service {$service->id} ({$service->name}): {$filename}");
                } catch (\Exception $e) {
                    $this->warn("  ❌ Failed for service {$service->id}: {$e->getMessage()}");
                }
            }
        }

        $this->info("\n✅ All images regenerated successfully!");
        return 0;
    }
}
