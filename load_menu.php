<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$menu = ['categories' => [['id'=>'cat-AREP','nombre'=>'Arepas','foto'=>null,'activo'=>true,'items'=>[['id'=>'item-RP01','nombre'=>'Reina Pepiada','precio'=>4.50,'descripcion'=>'Pollo desmenuzado, aguacate y mayonesa. La clásica venezolana de siempre.','image_path'=>null,'badge'=>'popular','is_featured'=>true,'activo'=>true],['id'=>'item-PA02','nombre'=>'Pabellón','precio'=>5.00,'descripcion'=>'Carne mechada, caraotas negras, tajadas de plátano y queso blanco rallado.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-PO03','nombre'=>'Pelúa','precio'=>4.50,'descripcion'=>'Carne mechada con queso amarillo derretido. Una combinación irresistible.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-DO04','nombre'=>'Dominó','precio'=>4.00,'descripcion'=>'Caraotas negras cremosas y queso blanco. Sencilla, pero perfecta.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-PI05','nombre'=>'Pisca Andina','precio'=>5.50,'descripcion'=>'Pollo guisado, papa, huevo y cilantro. Sabor de montaña en cada mordida.','image_path'=>null,'badge'=>'nuevo','is_featured'=>true,'activo'=>true]]],['id'=>'cat-BEB','nombre'=>'Bebidas','foto'=>null,'activo'=>true,'items'=>[['id'=>'item-PA06','nombre'=>'Papelón con Limón','precio'=>1.50,'descripcion'=>'Bebida tradicional venezolana, dulce y refrescante con toque de limón.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-ME07','nombre'=>'Malta Polar','precio'=>1.00,'descripcion'=>'La malta más querida de Venezuela. Fría y espumosa.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-AG08','nombre'=>'Agua Mineral','precio'=>0.75,'descripcion'=>'Agua fría, 500ml.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true]]],['id'=>'cat-EXT','nombre'=>'Extras','foto'=>null,'activo'=>true,'items'=>[['id'=>'item-QU09','nombre'=>'Porción de Queso','precio'=>1.00,'descripcion'=>'Queso blanco telita extra, cortado en rebanadas.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true],['id'=>'item-QU10','nombre'=>'Tajadas','precio'=>1.25,'descripcion'=>'Plátano maduro frito, crujiente por fuera y dulce por dentro.','image_path'=>null,'badge'=>null,'is_featured'=>false,'activo'=>true]]]]];

Storage::put('tenants/7/menu/menu.json', json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
$v = json_decode(Storage::get('tenants/7/menu/menu.json'), true);
echo "Items en cat-0 (Arepas): " . count($v['categories'][0]['items']) . "\n";
echo "Items en cat-1 (Bebidas): " . count($v['categories'][1]['items']) . "\n";
echo "Items en cat-2 (Extras): " . count($v['categories'][2]['items']) . "\n";
echo "Total categorías: " . count($v['categories']) . "\n";
echo "\n✅ Menú cargado exitosamente en storage/tenants/7/menu/menu.json\n";
