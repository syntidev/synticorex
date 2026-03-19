<?php
$t = DB::table('tenants')->where('subdomain','bellestore')->first();
echo "ID: {$t->id}\n";
$c = DB::table('tenant_customization')->where('tenant_id',$t->id)->first();
echo "hero_main: {$c->hero_main_filename}\n";
echo "hero_2: {$c->hero_secondary_filename}\n";
echo "hero_3: {$c->hero_tertiary_filename}\n";
$prods = DB::table('products')->where('tenant_id',$t->id)->get(['name','category_name','image_url','image_filename']);
foreach ($prods as $p) {
    echo "[{$p->category_name}] {$p->name} | img: " . ($p->image_filename ?: $p->image_url) . "\n";
}
