<?php
$p = App\Models\Product::where('tenant_id', 17)
    ->where('name', 'like', '%Mochila%')
    ->first();
$p->image_url = null;
$p->save();
echo "Done: {$p->image_filename}\n";
