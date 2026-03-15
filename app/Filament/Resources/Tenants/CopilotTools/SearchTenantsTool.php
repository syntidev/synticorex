<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\CopilotTools;

use App\Models\Tenant;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Tools\Request;

class SearchTenantsTool extends BaseTool
{
    public function description(): string
    {
        return 'Busca tenants por nombre de negocio, subdominio o email.';
    }

    /** @return array<string, Type> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Término de búsqueda')->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $q = $request['query'];

        $tenants = Tenant::with('plan')
            ->where('business_name', 'like', "%{$q}%")
            ->orWhere('subdomain', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(10)->get();

        if ($tenants->isEmpty()) {
            return "No se encontraron tenants para '{$q}'.";
        }

        return $tenants->map(fn ($t) =>
            "#{$t->id} {$t->business_name} | {$t->subdomain} | Estado: {$t->status}"
        )->implode("\n");
    }
}
