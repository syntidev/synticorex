<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\CopilotTools;

use App\Models\Tenant;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Tools\Request;

class ListTenantsTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista tenants con paginación. Filtra por status (active, frozen, archived) opcionalmente.';
    }

    /** @return array<string, Type> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'status' => $schema->string()->description('Filtro de estado: active, frozen, archived')->nullable(),
            'limit'  => $schema->integer()->description('Máximo de resultados')->nullable(),
        ];
    }

    public function handle(Request $request): string
    {
        $query = Tenant::with('plan')->latest();

        if ($status = $request['status'] ?? null) {
            $query->where('status', $status);
        }

        $tenants = $query->limit($request['limit'] ?? 10)->get();

        if ($tenants->isEmpty()) {
            return 'No se encontraron tenants.';
        }

        return $tenants->map(fn ($t) =>
            "#{$t->id} {$t->business_name} | {$t->subdomain} | Plan: {$t->plan?->name} | Estado: {$t->status} | Vence: " . ($t->subscription_ends_at?->format('d/m/Y') ?? 'N/A')
        )->implode("\n");
    }
}
