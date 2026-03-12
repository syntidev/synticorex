# Agent Routing Policy

## Regla principal

- Todo lo referido a arquitectura Tenants es exclusividad de Opus.
- Tareas menos complejas van por Sonnet.
- Scripts/codigo simples van por Haiku.
- Comentar e indexar: modo auto.

## Matriz rapida

1. Opus:
- Tenants, tenancy, multitenant, middleware de tenant, rutas criticas, auth tenant, aislamiento tenant_id, seguridad tenant.

2. Sonnet:
- Implementaciones medias de UI/Blade, validaciones no criticas, ajustes de flujo no-tenant, pruebas funcionales moderadas.

3. Haiku:
- Scripts simples, mejoras pequenas, docs, comentarios, busquedas, indexado, tareas repetitivas de baja complejidad.

4. Auto:
- Tareas de comentar/indexar y mantenimiento ligero sin riesgo arquitectonico.

## Validador automatizado

Usa el router para validar antes de trabajar:

- powershell -ExecutionPolicy Bypass -File scripts/agent_router.ps1 -Task "describir tarea" -CurrentAgent sonnet

Codigos de salida:

- 0: OK con agente actual.
- 1: Recomendado cambiar de agente.
- 2: Bloqueado por politica. Debes cambiar a Opus.

## Ejemplos

1. Tarea tenant critica:
- Task: "Refactor middleware tenant y dominio custom_domain con domain_verified"
- Resultado esperado: Opus, bloqueo si no estas en Opus.

2. Tarea media:
- Task: "Ajustar footer legal en landing y validar vistas"
- Resultado esperado: Sonnet.

3. Tarea simple:
- Task: "Script para limpiar logs y actualizar docs"
- Resultado esperado: Haiku.
