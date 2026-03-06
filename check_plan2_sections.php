<?php

// Quick script to check Plan 2 sections

use App\Models\Tenant;

require __DIR__ . '/bootstrap/app.php';

$app = app();
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$plan2 = Tenant::where('plan_id', 2)->with('customization')->first();

if ($plan2 && $plan2->customization) {
    $sections = data_get($plan2->customization->visual_effects, 'sections_order', []);
    $names = array_column($sections, 'name');
    
    echo "Plan 2 Tenant: {$plan2->business_name}\n";
    echo "Sections: " . implode(', ', $names) . "\n";
    echo "Has testimonials: " . (in_array('testimonials', $names) ? 'YES ✓' : 'NO ✗') . "\n";
    
    echo "\nFull sections_order:\n";
    echo json_encode($sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "Plan 2 tenant or customization not found\n";
}
