<?php

use App\Models\Tenant;
use App\Models\TenantCustomization;
use App\Models\Plan;

// == PLAN ID ==
$planId = Plan::where('slug','studio-vision')->first()->id;
echo 'Plan ID: ' . $planId . PHP_EOL;

// ══════════════════════════════════════
// DEMO 1: FitZone Pro
// ══════════════════════════════════════
$t1 = Tenant::create([
    'user_id'          => 1,
    'business_name'    => 'FitZone Pro',
    'plan_id'          => $planId,
    'subdomain'        => 'fitzonepro',
    'business_segment' => 'Gimnasio & Entrenamiento Personal',
    'slogan'           => 'Tu mejor versión empieza aquí',
    'description'      => 'Gimnasio moderno con entrenadores certificados, clases grupales, nutrición y seguimiento personalizado.',
    'phone'            => '+584141000001',
    'whatsapp_sales'   => '+584141000001',
    'whatsapp_active'  => 'sales',
    'email'            => 'info@fitzonepro.com',
    'address'          => 'Av. Principal, Las Mercedes',
    'city'             => 'Caracas',
    'country'          => 'Venezuela',
    'is_demo'          => true,
    'status'           => 'active',
    'is_open'          => true,
    'business_hours'   => [
        'monday'    => ['open'=>'05:00','close'=>'22:00','active'=>true],
        'tuesday'   => ['open'=>'05:00','close'=>'22:00','active'=>true],
        'wednesday' => ['open'=>'05:00','close'=>'22:00','active'=>true],
        'thursday'  => ['open'=>'05:00','close'=>'22:00','active'=>true],
        'friday'    => ['open'=>'05:00','close'=>'22:00','active'=>true],
        'saturday'  => ['open'=>'07:00','close'=>'18:00','active'=>true],
        'sunday'    => ['open'=>'08:00','close'=>'14:00','active'=>true],
    ],
    'settings' => [
        'business_info' => [
            'faq' => [
                ['question'=>'¿Tienen clases grupales?','answer'=>'Sí, más de 20 clases semanales: spinning, yoga, funcional, zumba y más.'],
                ['question'=>'¿Cómo me inscribo?','answer'=>'Escríbenos por WhatsApp y te guiamos en el proceso. Sin trámites complicados.'],
                ['question'=>'¿Tienen entrenadores personales?','answer'=>'Contamos con 8 entrenadores certificados disponibles para sesiones individuales.'],
                ['question'=>'¿Cuál es el costo de la membresía?','answer'=>'Planes desde 25 USD/mes. Pregunta por nuestras promociones de inscripción.'],
            ],
            'testimonials' => [
                ['text'=>'Perdí 12 kilos en 4 meses con el plan de entrenamiento personalizado. Los entrenadores son increíbles.','author'=>'María G.','rating'=>5],
                ['text'=>'El mejor gimnasio de Caracas. Equipos modernos, ambiente motivador y precios justos.','author'=>'Carlos R.','rating'=>5],
                ['text'=>'Las clases de spinning son adictivas. Llevo 2 años y no lo cambio por nada.','author'=>'Alejandra M.','rating'=>5],
            ],
        ],
    ],
]);
TenantCustomization::create([
    'tenant_id'              => $t1->id,
    'theme_slug'             => 'azul-electrico',
    'hero_main_filename'     => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200',
    'hero_secondary_filename'=> 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200',
    'hero_tertiary_filename' => 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=1200',
    'hero_image_4_filename'  => 'https://images.unsplash.com/photo-1581009137042-c552e485697a?w=1200',
    'hero_image_5_filename'  => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=1200',
    'about_text'             => 'FitZone Pro nació con una misión clara: hacer del fitness algo accesible, motivador y transformador. Contamos con más de 1,200 m² de instalaciones, equipos de última generación y un equipo humano que se apasiona con cada resultado de nuestros miembros.',
    'social_networks'        => ['instagram'=>'fitzonepro.ve','facebook'=>'fitzonepro.ve','tiktok'=>'fitzonepro.ve'],
    'payment_methods'        => ['global'=>['pagoMovil','cash','puntoventa','biopago','zelle','zinli'],'currency'=>['usd']],
    'cta_title'              => '¿Listo para transformarte?',
    'cta_subtitle'           => 'Primera semana de prueba completamente gratis. Sin excusas.',
    'cta_button_text'        => 'Quiero mi prueba gratis',
    'cta_button_link'        => 'https://wa.me/584141000001',
    'content_blocks'         => ['legal_links'=>['enabled'=>false]],
]);
echo 'FitZone Pro: ID ' . $t1->id . PHP_EOL;

// ══════════════════════════════════════
// DEMO 2: Gestoría 360
// ══════════════════════════════════════
$t2 = Tenant::create([
    'user_id'          => 1,
    'business_name'    => 'Gestoría 360',
    'plan_id'          => $planId,
    'subdomain'        => 'gestoria360',
    'business_segment' => 'Servicios Legales & Administrativos',
    'slogan'           => 'Tus trámites, nuestro compromiso',
    'description'      => 'Firma multidisciplinaria con abogados, contadores, gestores y asesores fiscales. Trámites, contratos, empresas, migraciones y más.',
    'phone'            => '+584141000002',
    'whatsapp_sales'   => '+584141000002',
    'whatsapp_active'  => 'sales',
    'email'            => 'contacto@gestoria360.com',
    'address'          => 'Torre Empresarial, Piso 8, Chacaíto',
    'city'             => 'Caracas',
    'country'          => 'Venezuela',
    'is_demo'          => true,
    'status'           => 'active',
    'is_open'          => true,
    'business_hours'   => [
        'monday'    => ['open'=>'08:00','close'=>'18:00','active'=>true],
        'tuesday'   => ['open'=>'08:00','close'=>'18:00','active'=>true],
        'wednesday' => ['open'=>'08:00','close'=>'18:00','active'=>true],
        'thursday'  => ['open'=>'08:00','close'=>'18:00','active'=>true],
        'friday'    => ['open'=>'08:00','close'=>'17:00','active'=>true],
        'saturday'  => ['open'=>'09:00','close'=>'13:00','active'=>true],
        'sunday'    => ['open'=>'00:00','close'=>'00:00','active'=>false],
    ],
    'settings' => [
        'business_info' => [
            'faq' => [
                ['question'=>'¿Qué tipo de trámites manejan?','answer'=>'Registro de empresas, contratos, poderes notariales, apostillas, migraciones, declaraciones fiscales, sucesiones y más.'],
                ['question'=>'¿Trabajan con clientes en el exterior?','answer'=>'Sí, atendemos clientes en Venezuela y en la diáspora. Todo se puede gestionar de forma remota.'],
                ['question'=>'¿Cuánto tiempo tarda un registro de empresa?','answer'=>'Entre 15 y 30 días hábiles dependiendo del tipo de sociedad y el Registro Mercantil asignado.'],
                ['question'=>'¿Tienen servicio de asesoría fiscal?','answer'=>'Contamos con contadores públicos y asesores tributarios para declaraciones SENIAT, retenciones y planificación fiscal.'],
            ],
            'testimonials' => [
                ['text'=>'Registraron mi empresa en tiempo récord y me asesoraron en todo el proceso fiscal. Profesionales de verdad.','author'=>'Roberto S.','rating'=>5],
                ['text'=>'Me apostillaron documentos para migración sin que yo tuviera que moverme de casa. Excelente servicio remoto.','author'=>'Valeria P.','rating'=>5],
                ['text'=>'Llevaron todo mi proceso de sucesión con total transparencia. Los recomiendo ampliamente.','author'=>'Dr. Miguel A.','rating'=>5],
            ],
        ],
    ],
]);
TenantCustomization::create([
    'tenant_id'              => $t2->id,
    'theme_slug'             => 'prestigio-clasico',
    'hero_main_filename'     => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200',
    'hero_secondary_filename'=> 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200',
    'hero_tertiary_filename' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200',
    'hero_image_4_filename'  => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200',
    'hero_image_5_filename'  => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200',
    'about_text'             => 'Gestoría 360 reúne a un equipo de profesionales del derecho, la contabilidad y la administración con más de 15 años de experiencia combinada. Creemos que los trámites no deben ser un obstáculo para tu crecimiento personal o empresarial.',
    'social_networks'        => ['instagram'=>'gestoria360.ve','linkedin'=>'gestoria360','facebook'=>'gestoria360.ve'],
    'payment_methods'        => ['global'=>['pagoMovil','cash','puntoventa','zelle','zinli','paypal'],'currency'=>['usd']],
    'cta_title'              => '¿Tienes un trámite pendiente?',
    'cta_subtitle'           => 'Primera consulta sin costo. Cuéntanos tu caso y te orientamos.',
    'cta_button_text'        => 'Consulta gratuita',
    'cta_button_link'        => 'https://wa.me/584141000002',
    'content_blocks'         => ['legal_links'=>['enabled'=>false]],
]);
echo 'Gestoría 360: ID ' . $t2->id . PHP_EOL;

// ══════════════════════════════════════
// DEMO 3: MediCenter
// ══════════════════════════════════════
$t3 = Tenant::create([
    'user_id'          => 1,
    'business_name'    => 'MediCenter',
    'plan_id'          => $planId,
    'subdomain'        => 'medicenter',
    'business_segment' => 'Centro Médico Multidisciplinario',
    'slogan'           => 'Tu salud, nuestra prioridad',
    'description'      => 'Centro médico con especialistas en medicina general, cardiología, pediatría, ginecología, nutrición y psicología. Atención personalizada con tecnología moderna.',
    'phone'            => '+584141000003',
    'whatsapp_sales'   => '+584141000003',
    'whatsapp_active'  => 'sales',
    'email'            => 'citas@medicenter.com.ve',
    'address'          => 'Av. Libertador, Centro Médico, Local 12',
    'city'             => 'Caracas',
    'country'          => 'Venezuela',
    'is_demo'          => true,
    'status'           => 'active',
    'is_open'          => true,
    'business_hours'   => [
        'monday'    => ['open'=>'07:00','close'=>'19:00','active'=>true],
        'tuesday'   => ['open'=>'07:00','close'=>'19:00','active'=>true],
        'wednesday' => ['open'=>'07:00','close'=>'19:00','active'=>true],
        'thursday'  => ['open'=>'07:00','close'=>'19:00','active'=>true],
        'friday'    => ['open'=>'07:00','close'=>'18:00','active'=>true],
        'saturday'  => ['open'=>'08:00','close'=>'14:00','active'=>true],
        'sunday'    => ['open'=>'00:00','close'=>'00:00','active'=>false],
    ],
    'settings' => [
        'business_info' => [
            'faq' => [
                ['question'=>'¿Cómo agendo una cita?','answer'=>'Por WhatsApp, llamada o desde nuestra web. Confirmamos en menos de 2 horas en horario de atención.'],
                ['question'=>'¿Qué especialidades tienen?','answer'=>'Medicina general, cardiología, pediatría, ginecología, nutrición clínica, psicología y más. Consulta disponibilidad.'],
                ['question'=>'¿Aceptan seguros médicos?','answer'=>'Trabajamos con las principales aseguradoras del país. Consulta por tu póliza antes de tu cita.'],
                ['question'=>'¿Hacen consultas de emergencia?','answer'=>'Sí, contamos con atención de urgencias de lunes a sábado hasta las 6pm. Sin cita previa.'],
            ],
            'testimonials' => [
                ['text'=>'Llevo a toda mi familia aquí. Los médicos son excelentes, la atención es rápida y el ambiente es muy agradable.','author'=>'Carmen L.','rating'=>5],
                ['text'=>'La Dra. de nutrición me cambió la vida. Perdí 18 kilos con un plan real, sin locuras.','author'=>'Andrés V.','rating'=>5],
                ['text'=>'Atención de primera desde recepción hasta el médico. Se nota que les importa el paciente.','author'=>'Patricia M.','rating'=>5],
            ],
        ],
    ],
]);
TenantCustomization::create([
    'tenant_id'              => $t3->id,
    'theme_slug'             => 'azul-confianza',
    'hero_main_filename'     => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200',
    'hero_secondary_filename'=> 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1200',
    'hero_tertiary_filename' => 'https://images.unsplash.com/photo-1666214280557-f1b5022eb634?w=1200',
    'hero_image_4_filename'  => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=1200',
    'hero_image_5_filename'  => 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=1200',
    'about_text'             => 'MediCenter nació con la convicción de que una atención médica de calidad debe ser accesible para todos. Reunimos especialistas comprometidos con la salud integral de cada paciente, en un espacio moderno y humano.',
    'social_networks'        => ['instagram'=>'medicenter.ve','facebook'=>'medicenter.ve'],
    'payment_methods'        => ['global'=>['pagoMovil','cash','puntoventa','biopago','zelle'],'currency'=>['usd']],
    'cta_title'              => '¿Necesitas una cita?',
    'cta_subtitle'           => 'Agenda hoy. Confirmamos en menos de 2 horas.',
    'cta_button_text'        => 'Agendar cita',
    'cta_button_link'        => 'https://wa.me/584141000003',
    'content_blocks'         => ['legal_links'=>['enabled'=>false]],
]);
echo 'MediCenter: ID ' . $t3->id . PHP_EOL;
echo 'DONE' . PHP_EOL;
