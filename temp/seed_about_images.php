<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('tenant_customization')->where('tenant_id', 19)->update([
    'about_image_filename' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200',
]);

DB::table('tenant_customization')->where('tenant_id', 20)->update([
    'about_image_filename' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200',
]);

DB::table('tenant_customization')->where('tenant_id', 21)->update([
    'about_image_filename' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200',
]);

echo "about_image_filename OK para tenants 19, 20, 21\n";
