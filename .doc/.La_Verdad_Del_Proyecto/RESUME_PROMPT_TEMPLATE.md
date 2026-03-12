# Resume Prompt Template

Usa este prompt al abrir una nueva sesion para continuar sin perder el hilo:

En la sesion anterior cerramos avances y quiero retomar exactamente donde quedamos.
Haz esto en orden:
1) Lee .doc/.La_Verdad_Del_Proyecto/STUDIO_EXECUTION_PLAN.json y .doc/.La_Verdad_Del_Proyecto/CIERRE_OPERATIVO_2026-03-12.md.
2) Valida estado con php scripts/studio_plan_orchestrator.php status --plan=studio.
3) Antes de trabajar una tarea nueva, clasificala con scripts/agent_router.ps1 y respeta la politica: Tenants/arquitectura critica => Opus; medio => Sonnet; simple/docs/scripts => Haiku o auto.
4) Si Studio esta en 100%, propone y ejecuta el siguiente frente en orden (cat o food) con el orquestador, sin saltar pasos.
5) Si hay pendientes, cierralos secuencialmente y marca done.
6) Antes de editar, confirma archivos a tocar; luego aplica cambios, valida errores y deja commit final.
7) No tocar ni incluir .claude/worktrees/condescending-sanderson en commits.
8) Revisar si public/brand/404.svg es un archivo local pendiente o ajeno antes de incluirlo en cualquier commit.

Comandos minimos de control:
- git log --oneline -n 5
- git status --short
- php scripts/studio_plan_orchestrator.php status --plan=studio
- powershell -ExecutionPolicy Bypass -File scripts/agent_router.ps1 -Task "describir tarea" -CurrentAgent sonnet
