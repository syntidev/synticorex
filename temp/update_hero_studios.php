<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ══ FITZONE PRO (ID 19) ══
\DB::table('tenant_customization')->where('tenant_id', 19)->update([
    'hero_main_filename'      => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200',
    'hero_secondary_filename' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200',
    'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=1200',
    'hero_image_4_filename'   => 'https://images.unsplash.com/photo-1581009137042-c552e485697a?w=1200',
    'hero_image_5_filename'   => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1200',
]);
echo "FitZone OK\n";

// ══ GESTORÍA 360 (ID 20) ══
\DB::table('tenant_customization')->where('tenant_id', 20)->update([
    'hero_main_filename'      => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200',
    'hero_secondary_filename' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200',
    'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200',
    'hero_image_4_filename'   => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200',
    'hero_image_5_filename'   => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200',
]);
echo "Gestoria OK\n";

// ══ MEDICENTER (ID 21) ══
\DB::table('tenant_customization')->where('tenant_id', 21)->update([
    'hero_main_filename'      => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200',
    'hero_secondary_filename' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1200',
    'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=1200',
    'hero_image_4_filename'   => 'https://images.unsplash.com/photo-1666214280557-f1b5022eb634?w=1200',
    'hero_image_5_filename'   => 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=1200',
]);
echo "MediCenter OK\n";

// Verificar que se actualizaron
echo "\nVERIFICACIÓN:\n";
foreach ([19, 20, 21] as $tid) {
    $r = \DB::table('tenant_customization')->where('tenant_id', $tid)->first();
    $status = $r ? "✓ tenant_id={$tid} img1=" . substr($r->hero_main_filename ?? 'NULL', -30) : "✗ tenant_id={$tid} NO EXISTE";
    echo $status . "\n";
}
