# AGENTS.md — SYNTIWEB
# Instrucciones universales para agentes autónomos (Copilot, Claude, Cursor, Gemini)
# Versión: 1.0 | MAR 2026

## PROYECTO

SaaS multitenant Venezuela. Laravel 12 + Preline 4.1.2 + Tailwind 4.2 + Alpine.js 3.4.2.
Ruta local: `C:\laragon\www\synticorex\`

## LEER SIEMPRE PRIMERO

Antes de cualquier acción, leer en este orden:
1. `.github/copilot-instructions.md` — gobernanza y reglas críticas
2. `.github/SYNTIWEB-CONTEXT.md` — arquitectura y contexto de negocio
3. `.github/copilot-workspace.yml` — skills técnicos por dominio

## AGENTES DISPONIBLES

Invocar con `@nombre` según la tarea:

| Agente        | Archivo                              | Cuándo usarlo                              |
|---------------|--------------------------------------|--------------------------------------------|
| @consultant   | `.github/agents/consultant.agent.md` | Antes de ejecutar — análisis y viabilidad  |
| @executor     | `.github/agents/executor.agent.md`   | Implementar algo ya definido               |
| @reviewer     | `.github/agents/reviewer.agent.md`   | Auditar código entregado por @executor     |
| @debugger     | `.github/agents/debugger.agent.md`   | Diagnosticar un error específico           |

## FLUJO RECOMENDADO

```
Tarea ambigua o nueva  →  @consultant primero
Tarea clara            →  @executor directamente
Post-implementación    →  @reviewer
Bug reportado          →  @debugger
```

## CÓMO CONSTRUIR Y TESTEAR

```bash
# Instalar dependencias
composer install
npm install

# Build assets (solo local — nunca Node en servidor)
npm run build

# Servidor de desarrollo
php artisan serve

# Verificar reglas críticas post-refactor
grep -rn "input input-bordered\|label-text\|btn btn-\|FlyonUI\|flyonui\|landing/partials" resources/views/
# Si hay resultados → corregir antes de reportar como completado

# Seeder base de conocimiento IA
php artisan db:seed --class=AiDocSeeder
```

## REGLAS QUE NINGÚN AGENTE PUEDE VIOLAR

- `declare(strict_types=1)` en todo archivo PHP
- `tenant_id` en toda query — sin excepción
- NUNCA `landing/partials/` — usar `landing/sections/`
- NUNCA clases DaisyUI en HTML
- NUNCA FlyonUI instalado o referenciado
- NUNCA logo/colores SyntiWeb en vistas de tenant
- Planes dinámicos desde DB — NUNCA hardcodear precios
