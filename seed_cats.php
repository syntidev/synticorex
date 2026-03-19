<?php
// Nova Store (tenant 17) — crear categorías en tabla cat_categories
$cats = ['Damas', 'Caballeros', 'Electrónicos', 'Accesorios'];
foreach ($cats as $name) {
    DB::table('cat_categories')->insertOrIgnore([
        'tenant_id'  => 17,
        'name'       => $name,
        'parent_id'  => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
echo "Categorías creadas en tabla: " . implode(', ', $cats) . "\n";

// Asignar categorías a productos por nombre
$assignments = [
    'Vestido|Bolso|Zapato' => 'Damas',
    'Camisa|Zapatilla|Reloj' => 'Caballeros',
    'Audífono|Cargador|Funda' => 'Electrónicos',
    'Gafas|Billetera|Mochila' => 'Accesorios',
];

foreach ($assignments as $pattern => $category) {
    $regex = "({$pattern})";
    $updated = DB::table('products')
        ->where('tenant_id', 17)
        ->where(DB::raw("name REGEXP '{$regex}'"), true)
        ->update(['category_name' => $category]);
    echo "Categoría '{$category}': {$updated} productos actualizados\n";
}

echo "\n📋 ESTADO FINAL (tenant 17):\n";
$products = DB::table('products')->where('tenant_id', 17)->get(['id', 'name', 'category_name']);
foreach ($products as $p) {
    echo "  [{$p->id}] {$p->name} | cat: {$p->category_name}\n";
}
