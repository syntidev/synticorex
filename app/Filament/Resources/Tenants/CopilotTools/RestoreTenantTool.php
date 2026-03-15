<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\CopilotTools;

use App\Models\Tenant;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Tools\Request;

class RestoreTenantTool extends BaseTool
{
    public function description(): string
    {
        return 'Restaura un tenant suspendido (cambia status a active) por su ID.';
    }

    /** @return array<string, Type> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'tenant_id' => $schema->integer()->description('ID del tenant a restaurar')->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $tenant = Tenant::find($request['tenant_id']);

        if (!$tenant) {
            return "Tenant #{$request['tenant_id']} no encontrado.";
        }

        if ($tenant->status === 'active') {
            return "Tenant {$tenant->business_name} ya está activo.";
        }

        $tenant->update(['status' => 'active']);

        return "Tenant {$tenant->business_name} ({$tenant->subdomain}) restaurado correctamente.";
    }
}
