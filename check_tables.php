<?php
$tables = DB::select('SHOW TABLES');
$key = 'Tables_in_synticorex';
foreach ($tables as $t) {
    $name = $t->$key;
    if (str_contains($name, 'menu') || str_contains($name, 'food') || str_contains($name, 'item') || str_contains($name, 'cat')) {
        echo $name . "\n";
    }
}
