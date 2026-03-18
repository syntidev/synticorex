<?php
use App\Models\Product;

$product = Product::where('tenant_id', 17)
    ->where('name', 'like', '%Mochila%')
    ->first();

if ($product) {
    $oldUrl = $product->image_url;
    $product->update(['image_url' => null]);
    echo "✓ Mochila URL cleared\n";
    echo "  From: {$oldUrl}\n";
    echo "  To: NULL\n";
} else {
    echo "✗ Mochila NOT FOUND\n";
}
