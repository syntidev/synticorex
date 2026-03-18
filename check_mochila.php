<?php
use App\Models\Product;

$product = Product::where('tenant_id', 17)
    ->where('name', 'like', '%Mochila%')
    ->first();

if ($product) {
    echo "=== MOCHILA (TENANT 17) ===\n";
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "image_url: " . ($product->image_url ?? 'NULL') . "\n";
    echo "image_filename: " . ($product->image_filename ?? 'NULL') . "\n";
    echo "Galería images: " . $product->galleryImages()->count() . "\n";
    $product->galleryImages()->get()->each(function($gi, $i) {
        echo "  [{$i}] {$gi->image_filename}\n";
    });
} else {
    echo "Mochila NOT FOUND in tenant 17\n";
}
