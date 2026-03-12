param(
    [ValidateSet('studio','cat','food')]
    [string]$Plan = 'studio'
)

$ErrorActionPreference = 'Stop'

Write-Host "=== SESSION RESUME CHECKPOINT ===" -ForegroundColor Cyan
Write-Host "Date: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Host "Plan: $Plan"
Write-Host ""

Write-Host "[1/4] Last commit" -ForegroundColor Yellow
git log --oneline -n 1
Write-Host ""

Write-Host "[2/4] Working tree status" -ForegroundColor Yellow
git status --short
Write-Host ""

Write-Host "[3/4] Orchestrator status for selected plan" -ForegroundColor Yellow
php scripts/studio_plan_orchestrator.php status --plan=$Plan
Write-Host ""

Write-Host "[4/5] Agent routing policy reminder" -ForegroundColor Yellow
Write-Host "- Tenants architecture => Opus only"
Write-Host "- Medium non-tenant work => Sonnet"
Write-Host "- Simple scripts/docs/comments/indexing => Haiku or auto"
Write-Host "- Validate any new task with: powershell -ExecutionPolicy Bypass -File scripts/agent_router.ps1 -Task \"...\" -CurrentAgent sonnet"
Write-Host ""

Write-Host "[5/5] Suggested resume prompt" -ForegroundColor Yellow
Write-Host ""

$PromptLines = @(
"En la sesion anterior cerramos avances y quiero retomar exactamente donde quedamos.",
"Haz esto en orden:",
"1) Lee .doc/.La_Verdad_Del_Proyecto/STUDIO_EXECUTION_PLAN.json y .doc/.La_Verdad_Del_Proyecto/CIERRE_OPERATIVO_2026-03-12.md.",
"2) Valida estado con php scripts/studio_plan_orchestrator.php status --plan=$Plan.",
"3) Antes de trabajar una tarea nueva, clasificala con scripts/agent_router.ps1 y respeta la politica: Tenants/arquitectura critica => Opus; medio => Sonnet; simple/docs/scripts => Haiku o auto.",
"4) Si este plan esta en 100%, propone y ejecuta el siguiente frente en orden con el orquestador, sin saltar pasos.",
"5) Si hay pendientes, cierralos secuencialmente y marca done.",
"6) Antes de editar, confirma archivos a tocar; luego aplica cambios, valida errores y deja commit final.",
"7) No tocar ni incluir .claude/worktrees/condescending-sanderson en commits.",
"8) Revisar si public/brand/404.svg es un archivo local pendiente o ajeno antes de incluirlo en cualquier commit."
)

$PromptLines | ForEach-Object { Write-Host $_ }

Write-Host ""
Write-Host "Done." -ForegroundColor Green
