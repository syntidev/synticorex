param(
    [Parameter(Mandatory = $true)]
    [string]$Task,

    [ValidateSet('opus', 'sonnet', 'haiku', 'auto', '')]
    [string]$CurrentAgent = ''
)

$ErrorActionPreference = 'Stop'

function Test-AnyKeyword {
    param(
        [string]$Text,
        [string[]]$Keywords
    )

    foreach ($k in $Keywords) {
        if ($Text -match [regex]::Escape($k)) {
            return $true
        }
    }

    return $false
}

$normalized = $Task.ToLowerInvariant()

$tenantKeywords = @(
    'tenant',
    'tenancy',
    'multitenant',
    'multitenancy',
    'tenant_id',
    'tenant isolation',
    'identifytenant',
    'custom_domain',
    'domain_verified',
    'tenantrenderercontroller',
    'tenantownership',
    'tenant.owner',
    'routes/web.php',
    'routes/api.php',
    'middleware',
    'auth:sanctum'
)

$highComplexityKeywords = @(
    'arquitectura',
    'architecture',
    'refactor',
    'security',
    'seguridad',
    'migracion',
    'migration',
    'critical',
    'produccion',
    'production',
    'n+1',
    'eager loading',
    'middleware',
    'controller',
    'model',
    'routes',
    'schema',
    'db',
    'database'
)

$mediumKeywords = @(
    'blade',
    'ui',
    'vista',
    'layout',
    'footer',
    'landing',
    'copy',
    'legal',
    'terminos',
    'privacidad',
    'about',
    'test',
    'qa'
)

$simpleKeywords = @(
    'script simple',
    'script',
    'comentario',
    'comment',
    'indexar',
    'index',
    'grep',
    'search',
    'rename',
    'lint',
    'format',
    'docs',
    'documentacion',
    'markdown'
)

$tenantCritical = Test-AnyKeyword -Text $normalized -Keywords $tenantKeywords
$highComplexity = Test-AnyKeyword -Text $normalized -Keywords $highComplexityKeywords
$mediumComplexity = Test-AnyKeyword -Text $normalized -Keywords $mediumKeywords
$simpleWork = Test-AnyKeyword -Text $normalized -Keywords $simpleKeywords

$recommended = 'auto'
$reason = 'Tarea de comentar/indexar o sin complejidad alta detectada.'
$severity = 'info'
$exitCode = 0

if ($tenantCritical) {
    $recommended = 'opus'
    $reason = 'Detectada tarea de arquitectura/seguridad Tenants. Regla: exclusividad Opus.'
    $severity = 'critical'
} elseif ($simpleWork -and -not $highComplexity -and -not $mediumComplexity) {
    $recommended = 'haiku'
    $reason = 'Detectada tarea simple de script/documentacion/comentario/indexado.'
    $severity = 'low'
} elseif ($mediumComplexity -or $highComplexity) {
    $recommended = 'sonnet'
    $reason = 'Detectada tarea de complejidad media/no-tenant.'
    $severity = 'medium'
}

if ($CurrentAgent -ne '') {
    $current = $CurrentAgent.ToLowerInvariant()
    if ($current -ne $recommended) {
        if ($recommended -eq 'opus') {
            $exitCode = 2
        } else {
            $exitCode = 1
        }
    }
}

Write-Host '=== AGENT ROUTER ===' -ForegroundColor Cyan
Write-Host ("Task: {0}" -f $Task)
Write-Host ("Detected severity: {0}" -f $severity)
Write-Host ("Recommended agent: {0}" -f $recommended) -ForegroundColor Yellow
Write-Host ("Reason: {0}" -f $reason)

if ($CurrentAgent -ne '') {
    Write-Host ("Current agent: {0}" -f $CurrentAgent)
    if ($exitCode -eq 0) {
        Write-Host 'Status: OK, puedes continuar con este agente.' -ForegroundColor Green
    } elseif ($exitCode -eq 2) {
        Write-Host 'Status: BLOQUEADO. Debes cambiar a Opus antes de continuar.' -ForegroundColor Red
    } else {
        Write-Host 'Status: Recomendado cambiar de agente para optimizar costo/rendimiento.' -ForegroundColor Magenta
    }
}

exit $exitCode
