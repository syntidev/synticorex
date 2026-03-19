<?php
$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $t = (array)$t;
    $name = reset($t);
    if (str_contains($name, 'categor') || str_contains($name, 'cat_')) {
        echo $name . "\n";
    }
}

echo "\nCOLUMNAS DE PRODUCTS:\n";
echo implode(', ', \Illuminate\Support\Facades\Schema::getColumnListing('products'));

