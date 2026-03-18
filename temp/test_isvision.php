<?php
declare(strict_types=1);

require 'bootstrap/app.php';

$app = app();
$t = \App\Models\Tenant::find(17);

if ($t) {
    echo $t->isVision() ? 'TRUE' : 'FALSE';
} else {
    echo 'Tenant not found';
}
