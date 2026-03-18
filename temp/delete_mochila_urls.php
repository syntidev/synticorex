<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ProductImage;

$deleted = ProductImage::whereHas('product', function($q) {
    $q->where('tenant_id', 17)
      ->where('name', 'like', '%Mochila%');
})
->where('image_filename', 'like', 'https://%')
->delete();

echo "Deleted: {$deleted} images\n";
