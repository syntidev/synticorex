<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RenameImagesToStandardPattern extends Command
{
    protected $signature = 'images:standardize-names';

    protected $description = 'Rename all images to standard pattern (product_01.webp, service_01.webp)';

    public function handle(): int
    {
        $this->info('🔄 Renaming images to standard pattern...');

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("\n📦 Processing tenant: {$tenant->business_name} (ID: {$tenant->id})");

            // Process products
            $products = Product::where('tenant_id', $tenant->id)
                ->orderBy('id')
                ->get();

            foreach ($products as $index => $product) {
                if (!$product->image_filename) {
                    continue;
                }

                $newFilename = 'product_' . str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) . '.webp';
                $oldPath = "public/tenants/{$tenant->id}/{$product->image_filename}";
                $newPath = "public/tenants/{$tenant->id}/{$newFilename}";

                // Rename file if it exists
                if (Storage::disk('local')->exists($oldPath)) {
                    try {
                        Storage::disk('local')->move($oldPath, $newPath);
                        $product->update(['image_filename' => $newFilename]);
                        $this->line("  ✅ Product {$product->id}: {$product->name} → {$newFilename}");
                    } catch (\Exception $e) {
                        $this->warn("  ❌ Failed to rename: {$e->getMessage()}");
                    }
                } else {
                    // File doesn't exist, just update DB
                    $product->update(['image_filename' => $newFilename]);
                    $this->line("  📝 Product {$product->id}: Updated filename (file not found) → {$newFilename}");
                }
            }

            // Process services
            $services = Service::where('tenant_id', $tenant->id)
                ->orderBy('id')
                ->get();

            foreach ($services as $index => $service) {
                if (!$service->image_filename) {
                    continue;
                }

                $newFilename = 'service_' . str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) . '.webp';
                $oldPath = "public/tenants/{$tenant->id}/{$service->image_filename}";
                $newPath = "public/tenants/{$tenant->id}/{$newFilename}";

                // Rename file if it exists
                if (Storage::disk('local')->exists($oldPath)) {
                    try {
                        Storage::disk('local')->move($oldPath, $newPath);
                        $service->update(['image_filename' => $newFilename]);
                        $this->line("  ✅ Service {$service->id}: {$service->name} → {$newFilename}");
                    } catch (\Exception $e) {
                        $this->warn("  ❌ Failed to rename: {$e->getMessage()}");
                    }
                } else {
                    // File doesn't exist, just update DB
                    $service->update(['image_filename' => $newFilename]);
                    $this->line("  📝 Service {$service->id}: Updated filename (file not found) → {$newFilename}");
                }
            }
        }

        $this->info("\n✅ Done! All images renamed to standard pattern.");
        return 0;
    }
}
