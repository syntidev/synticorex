<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

// ══ TEMAS ══
\DB::table('tenant_customization')->where('tenant_id',19)->update(['theme_slug'=>'negro-impacto']);
\DB::table('tenant_customization')->where('tenant_id',20)->update(['theme_slug'=>'cashmere']);
echo "Temas OK\n";

// ══ PAYMENT METHODS ══
\DB::table('tenant_customization')->where('tenant_id',19)->update(['payment_methods'=>json_encode(['global'=>['pagoMovil','cash','puntoventa','biopago','zelle','zinli'],'currency'=>['usd']])]);
\DB::table('tenant_customization')->where('tenant_id',20)->update(['payment_methods'=>json_encode(['global'=>['pagoMovil','cash','puntoventa','biopago','zelle','zinli','paypal'],'currency'=>['usd','eur']])]);
\DB::table('tenant_customization')->where('tenant_id',21)->update(['payment_methods'=>json_encode(['global'=>['pagoMovil','cash','puntoventa','biopago','zelle','zinli'],'currency'=>['usd']])]);
echo "Payment methods OK\n";

// ══ FITZONE PRO (ID 19) ══
Product::where('tenant_id',19)->delete();
$gymProds = [
    ['name'=>'Membresía Mensual','description'=>'Acceso ilimitado al gym, clases grupales y área cardiovascular. Sin contratos.','price_usd'=>25.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800'],
    ['name'=>'Membresía Trimestral','description'=>'3 meses de acceso full con 15% de descuento. La opción más elegida.','price_usd'=>65.00,'badge'=>'recomendado','compare_price_usd'=>75.00,'is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=800'],
    ['name'=>'Membresía Anual','description'=>'12 meses al precio de 9. Incluye evaluación física semestral y 1 sesión personal.','price_usd'=>220.00,'badge'=>'promo','compare_price_usd'=>300.00,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800'],
    ['name'=>'Pack Entrenamiento Personal x8','description'=>'8 sesiones privadas con entrenador certificado. Horario flexible.','price_usd'=>80.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1581009137042-c552e485697a?w=800'],
    ['name'=>'Pack Entrenamiento Personal x16','description'=>'16 sesiones. Ideal para transformaciones reales. Incluye seguimiento nutricional.','price_usd'=>150.00,'badge'=>'recomendado','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=800'],
    ['name'=>'Clase Suelta Grupal','description'=>'Una clase de spinning, funcional, yoga o zumba sin membresía. Prueba sin compromiso.','price_usd'=>5.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800'],
    ['name'=>'Plan Nutrición 1 Mes','description'=>'Consulta inicial + plan alimentario + 2 seguimientos con nutricionista deportiva.','price_usd'=>35.00,'badge'=>'nuevo','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800'],
    ['name'=>'Evaluación Física Completa','description'=>'Composición corporal, VO2 máx, test de fuerza y movilidad. Informe detallado incluido.','price_usd'=>20.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800'],
    ['name'=>'Membresía Corporativa x5','description'=>'5 membresías mensuales para tu equipo de trabajo. Factura disponible.','price_usd'=>110.00,'badge'=>'nuevo','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1522898467493-49726bf28798?w=800'],
    ['name'=>'Suplementos Proteicos','description'=>'Whey protein, BCAA y creatina de marcas certificadas. Venta en recepción.','price_usd'=>15.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=800'],
];
foreach($gymProds as $i=>$p) Product::create(array_merge($p,['tenant_id'=>19,'is_active'=>true,'position'=>$i+1]));
echo "FitZone: ".Product::where('tenant_id',19)->count()." productos\n";

// ══ GESTORÍA 360 (ID 20) ══
Product::where('tenant_id',20)->delete();
$gesProds = [
    ['name'=>'Registro de C.A.','description'=>'Constitución completa de Compañía Anónima ante Registro Mercantil. Incluye estatutos y acta constitutiva.','price_usd'=>120.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800'],
    ['name'=>'Registro de S.R.L.','description'=>'Sociedad de Responsabilidad Limitada. Proceso completo en 20-30 días hábiles.','price_usd'=>100.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800'],
    ['name'=>'Poder Notarial Simple','description'=>'Redacción y autenticación de poder ante Notaría Pública. Entrega en 48 horas.','price_usd'=>40.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800'],
    ['name'=>'Apostilla de Documentos','description'=>'Legalización para uso internacional. Títulos, actas, certificados. Consultar tiempo según tipo.','price_usd'=>60.00,'badge'=>'popular','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800'],
    ['name'=>'Declaración ISLR Personal','description'=>'Elaboración y presentación de declaración anual de impuesto sobre la renta ante SENIAT.','price_usd'=>45.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800'],
    ['name'=>'Asesoría Migratoria 1h','description'=>'Consulta personalizada sobre visas, residencias y procesos migratorios. Presencial o remota.','price_usd'=>30.00,'badge'=>'nuevo','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800'],
    ['name'=>'Contrato Laboral','description'=>'Redacción de contrato de trabajo a tiempo determinado o indeterminado. Cumple con LOTTT.','price_usd'=>35.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800'],
    ['name'=>'Pack Empresa Completo','description'=>'Registro + RIF + cuenta bancaria + contabilidad primer mes. Todo en un solo paquete.','price_usd'=>250.00,'badge'=>'recomendado','compare_price_usd'=>320.00,'is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800'],
];
foreach($gesProds as $i=>$p) Product::create(array_merge($p,['tenant_id'=>20,'is_active'=>true,'position'=>$i+1]));
echo "Gestoria: ".Product::where('tenant_id',20)->count()." productos\n";

// ══ MEDICENTER (ID 21) ══
Product::where('tenant_id',21)->delete();
$medProds = [
    ['name'=>'Consulta Medicina General','description'=>'Diagnóstico, tratamiento y seguimiento. Adultos y niños. Con o sin cita previa.','price_usd'=>15.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800'],
    ['name'=>'Consulta Cardiología','description'=>'Evaluación cardiovascular completa con electrocardiograma incluido.','price_usd'=>35.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800'],
    ['name'=>'Consulta Pediatría','description'=>'Atención integral del recién nacido al adolescente. Control de crecimiento y vacunas.','price_usd'=>18.00,'badge'=>'popular','is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=800'],
    ['name'=>'Consulta Ginecología','description'=>'Control prenatal, citología, ecografía obstétrica y ginecología general.','price_usd'=>25.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1666214280557-f1b5022eb634?w=800'],
    ['name'=>'Plan Nutrición Clínica','description'=>'Evaluación nutricional + plan personalizado + 2 controles mensuales. Para peso, diabetes e hipertensión.','price_usd'=>40.00,'badge'=>'recomendado','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800'],
    ['name'=>'Sesión Psicología','description'=>'Terapia individual 50 minutos. Ansiedad, depresión, estrés y desarrollo personal.','price_usd'=>20.00,'badge'=>'nuevo','is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=800'],
    ['name'=>'Electrocardiograma','description'=>'ECG de 12 derivaciones con interpretación médica incluida. Resultado inmediato.','price_usd'=>12.00,'badge'=>null,'is_featured'=>false,'image_url'=>'https://images.unsplash.com/photo-1530026186672-2cd00ffc50fe?w=800'],
    ['name'=>'Chequeo Médico Completo','description'=>'Examen físico + laboratorio básico + consulta general. Ideal una vez al año.','price_usd'=>55.00,'badge'=>'popular','compare_price_usd'=>75.00,'is_featured'=>true,'image_url'=>'https://images.unsplash.com/photo-1638202993928-7267aad84c31?w=800'],
];
foreach($medProds as $i=>$p) Product::create(array_merge($p,['tenant_id'=>21,'is_active'=>true,'position'=>$i+1]));
echo "MediCenter: ".Product::where('tenant_id',21)->count()." productos\n";

echo "DONE\n";
