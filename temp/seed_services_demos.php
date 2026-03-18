<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;

// ══ FITZONE PRO (ID 19) ══
$fit = [
    ['name'=>'Entrenamiento Personal','description'=>'Sesiones 1:1 con entrenador certificado. Plan nutricional y físico personalizado según tus objetivos.','icon_name'=>'barbell','position'=>1],
    ['name'=>'Clases Grupales','description'=>'+20 clases semanales: spinning, funcional, yoga, zumba, boxfit. Para todos los niveles.','icon_name'=>'users','position'=>2],
    ['name'=>'Evaluación Física','description'=>'Medición de composición corporal, índice de masa muscular y plan de acción inicial. Incluida con membresía.','icon_name'=>'activity','position'=>3],
    ['name'=>'Nutrición Deportiva','description'=>'Asesoría con nutricionista especializada en rendimiento y pérdida de peso saludable.','icon_name'=>'apple','position'=>4],
    ['name'=>'Área de Pesas','description'=>'1,200 m² con equipos de última generación. Zona libre, máquinas guiadas y área cardiovascular.','icon_name'=>'bolt','position'=>5],
    ['name'=>'Membresía Corporativa','description'=>'Planes especiales para empresas. Beneficia a tu equipo con salud y bienestar.','icon_name'=>'briefcase','position'=>6],
];
foreach ($fit as $s) Service::create(array_merge($s, ['tenant_id'=>19, 'is_active'=>true]));
echo 'FitZone: ' . Service::where('tenant_id', 19)->count() . ' servicios' . PHP_EOL;

// ══ GESTORÍA 360 (ID 20) ══
$ges = [
    ['name'=>'Registro de Empresas','description'=>'Constitución de C.A., S.R.L. y firmas personales ante el Registro Mercantil. Asesoría completa incluida.','icon_name'=>'building','position'=>1],
    ['name'=>'Trámites Notariales','description'=>'Poderes, autenticaciones, reconocimientos y documentos ante Notaría Pública.','icon_name'=>'certificate','position'=>2],
    ['name'=>'Asesoría Fiscal SENIAT','description'=>'Declaraciones de IVA, ISLR, retenciones y planificación tributaria para personas y empresas.','icon_name'=>'file-invoice','position'=>3],
    ['name'=>'Apostillas y Legalizaciones','description'=>'Documentos para uso en el exterior. Apostilla, legalización consular y traducción oficial.','icon_name'=>'award','position'=>4],
    ['name'=>'Derecho Laboral','description'=>'Contratos de trabajo, liquidaciones, procedimientos ante Inspectoría y LOTTT.','icon_name'=>'shield-check','position'=>5],
    ['name'=>'Asesoría Migratoria','description'=>'Visas, residencias, nacionalidades. Orientación para venezolanos en el exterior y extranjeros en Venezuela.','icon_name'=>'map-pin','position'=>6],
];
foreach ($ges as $s) Service::create(array_merge($s, ['tenant_id'=>20, 'is_active'=>true]));
echo 'Gestoria: ' . Service::where('tenant_id', 20)->count() . ' servicios' . PHP_EOL;

// ══ MEDICENTER (ID 21) ══
$med = [
    ['name'=>'Medicina General','description'=>'Consultas para adultos y niños. Diagnóstico, tratamiento y seguimiento de enfermedades agudas y crónicas.','icon_name'=>'stethoscope','position'=>1],
    ['name'=>'Cardiología','description'=>'Electrocardiograma, ecocardiograma y consulta especializada. Prevención y tratamiento cardiovascular.','icon_name'=>'heart','position'=>2],
    ['name'=>'Pediatría','description'=>'Atención integral del recién nacido al adolescente. Control de crecimiento, vacunas y enfermedades infantiles.','icon_name'=>'user-check','position'=>3],
    ['name'=>'Ginecología','description'=>'Consulta ginecológica, control prenatal, citología y ecografía obstétrica.','icon_name'=>'rosette-discount-check','position'=>4],
    ['name'=>'Nutrición Clínica','description'=>'Planes alimentarios personalizados para pérdida de peso, diabetes, hipertensión y rendimiento deportivo.','icon_name'=>'leaf','position'=>5],
    ['name'=>'Psicología','description'=>'Terapia individual y de pareja. Ansiedad, depresión, manejo del estrés y desarrollo personal.','icon_name'=>'brain','position'=>6],
];
foreach ($med as $s) Service::create(array_merge($s, ['tenant_id'=>21, 'is_active'=>true]));
echo 'MediCenter: ' . Service::where('tenant_id', 21)->count() . ' servicios' . PHP_EOL;
echo 'DONE' . PHP_EOL;
