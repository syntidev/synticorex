<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TecnoFixServicesSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()
            ->where('subdomain', 'tecnofix')
            ->where('is_demo', true)
            ->first();

        if (! $tenant) {
            $this->command?->error("Tenant demo 'tecnofix' no encontrado.");

            return;
        }

        $services = [
            [
                'name' => 'Diagnóstico Técnico',
                'description' => 'Revisión completa de tu equipo en 30 minutos. Identificamos fallas de hardware y software.',
                'icon_name' => 'tabler--device-laptop',
                'cta_text' => 'Solicitar diagnóstico',
            ],
            [
                'name' => 'Reparación de Pantallas',
                'description' => 'Cambio de pantalla para iPhone, Samsung y Xiaomi. Garantía 90 días.',
                'icon_name' => 'tabler--device-mobile',
                'cta_text' => 'Ver disponibilidad',
            ],
            [
                'name' => 'Recuperación de Data',
                'description' => 'Rescatamos tus fotos, documentos y contactos de equipos dañados o formateados.',
                'icon_name' => 'tabler--database',
                'cta_text' => 'Consultar ahora',
            ],
            [
                'name' => 'Mantenimiento Preventivo',
                'description' => 'Limpieza profunda, pasta térmica y optimización de rendimiento para laptops y PC.',
                'icon_name' => 'tabler--tool',
                'cta_text' => 'Agendar servicio',
            ],
            [
                'name' => 'Instalación de Software',
                'description' => 'Windows, Office, antivirus y programas originales con licencia incluida.',
                'icon_name' => 'tabler--download',
                'cta_text' => 'Solicitar instalación',
            ],
            [
                'name' => 'Redes y WiFi',
                'description' => 'Configuración de routers, extensores y redes empresariales en tu local o casa.',
                'icon_name' => 'tabler--wifi',
                'cta_text' => 'Solicitar visita',
            ],
            [
                'name' => 'Venta de Accesorios',
                'description' => 'Cargadores, fundas, cables, audífonos y repuestos originales y compatibles.',
                'icon_name' => 'tabler--shopping-bag',
                'cta_text' => 'Ver productos',
            ],
            [
                'name' => 'Soporte Empresarial',
                'description' => 'Mantenimiento mensual para empresas. Paquetes desde 3 equipos.',
                'icon_name' => 'tabler--building',
                'cta_text' => 'Consultar paquetes',
            ],
        ];

        foreach ($services as $index => $service) {
            Service::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $service['name'],
                ],
                [
                    'description' => $service['description'],
                    'icon_name' => $service['icon_name'],
                    'cta_text' => $service['cta_text'],
                    'position' => $index + 1,
                    'is_active' => true,
                ]
            );
        }

        $count = Service::query()
            ->where('tenant_id', $tenant->id)
            ->count();

        $this->command?->info("TecnoFix Services seeded: {$count} servicios creados.");
    }
}
