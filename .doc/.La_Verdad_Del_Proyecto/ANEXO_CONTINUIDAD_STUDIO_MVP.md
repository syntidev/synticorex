# ANEXO DE CONTINUIDAD — STUDIO MVP A PRODUCCION

Fecha: 11 MAR 2026
Estado: Fuente persistente para retomarlo en cualquier sesion
Owner: Proyecto SYNTIweb

---

## Proposito

Este anexo concentra el plan hibrido entre:
1. Hallazgos reales de auditoria tecnica.
2. Prioridades del roadmap maestro.
3. Objetivo inmediato de negocio: publicar SYNTIstudio en produccion con flujo completo y seguro.

Este archivo reemplaza cualquier nota volatil de sesion para continuidad operativa.

---

## Diagnostico consolidado

El producto base funciona, pero hay bloqueadores de salida.

Fortalezas actuales:
- Landing y render multitenant operativos.
- Wizard de onboarding implementado.
- Dashboard Studio funcional (CRUD, diseno, analytics, config).
- BCV, QR y SEO basico activos.

Bloqueadores confirmados:
- Flujo usuario roto en onboarding: tenant owner mal asignado.
- Flujo post-login sin "Mis negocios" ni redireccion inteligente.
- Seguridad incompleta: API sin auth:sanctum, PIN sin throttle, verificacion de dominio incompleta.

---

## Prioridad unica (orden ejecutable)

### Sprint S1 — Flujo critico de usuario (obligatorio para publicar)
Objetivo: register -> onboarding -> tenant propio -> dashboard propio.

Tareas:
- S1.1 Fix onboarding owner: reemplazar User::first() por auth()->id().
- S1.2 Fix redirect post-register hacia onboarding si no hay tenant.
- S1.3 Crear pagina "Mis negocios" para listar tenants del usuario autenticado.
- S1.4 Redirect post-login inteligente: con tenant -> dashboard; sin tenant -> onboarding.

Criterio de cierre S1:
- Un usuario nuevo puede registrarse y gestionar su propio tenant sin intervencion manual en DB.

### Sprint S2 — Seguridad minima de produccion
Objetivo: cerrar riesgos criticos antes de exponer publico.

Tareas:
- S2.1 Proteger routes/api.php con auth:sanctum.
- S2.2 Agregar throttle:5,1 a verify-pin.
- S2.3 Validar domain_verified en IdentifyTenant para custom domains.
- S2.4 Eliminar console.log en dashboard.
- S2.5 Validar ownership tenant_id en rutas auth.

Criterio de cierre S2:
- No existen endpoints de mutacion sensibles sin autenticacion o rate limit.

### Sprint S3 — Confianza de startup (compliance basico)
Objetivo: habilitar aplicacion a programas tipo Microsoft/Google for Startups.

Tareas:
- S3.1 Pagina Terminos.
- S3.2 Pagina Privacidad.
- S3.3 Pagina Acerca.
- S3.4 Enlaces visibles en footer/marketing.

Criterio de cierre S3:
- El producto tiene marco legal basico visible y trazable.

### Sprint S4 — Limpieza final
Objetivo: reducir ruido tecnico antes de release.

Tareas:
- S4.1 Eliminar residuos FlyonUI.
- S4.2 Eliminar dashboard/_archive.
- S4.3 Eliminar backups .bak.
- S4.4 Eliminar old_app.js.

---

## Definicion de "Studio 100% listo"

Se considera listo para produccion cuando:
1. El ciclo completo de usuario funciona de punta a punta sin soporte manual.
2. Seguridad minima de endpoints esta aplicada.
3. Existe dashboard usable por dueno autenticado.
4. Existen Terminos/Privacidad/Acerca publicos.
5. Demo compartible operativa para terceros.

---

## Referencias oficiales de soporte

Fuentes auditadas:
- .doc/.La_Verdad_Del_Proyecto/executive-summary.md
- .doc/.La_Verdad_Del_Proyecto/system-gaps.md
- .doc/.La_Verdad_Del_Proyecto/missing-modules.md
- .doc/.La_Verdad_Del_Proyecto/technical-debt.md
- .doc/ROADMAP_MAESTRO_03MAR2026.md

---

## Regla de continuidad entre sesiones

Al iniciar una sesion nueva:
1. Leer primero este anexo.
2. Validar estado real del sprint en codigo (no solo en checklist).
3. Actualizar este anexo al cerrar cada sprint.

Este archivo es la fuente persistente principal para merge de pendientes y prioridades.

---

## Orquestador de ejecucion (orden logico bloqueado)

Archivos fuente:
- `.doc/.La_Verdad_Del_Proyecto/STUDIO_EXECUTION_PLAN.json`
- `.doc/.La_Verdad_Del_Proyecto/CAT_EXECUTION_PLAN.json`
- `.doc/.La_Verdad_Del_Proyecto/FOOD_EXECUTION_PLAN.json`
- `scripts/studio_plan_orchestrator.php`

Comandos base:
- `php scripts/studio_plan_orchestrator.php next`
- `php scripts/studio_plan_orchestrator.php status`
- `php scripts/studio_plan_orchestrator.php list`
- `php scripts/studio_plan_orchestrator.php done S1.1`

Comandos por producto:
- `php scripts/studio_plan_orchestrator.php next --plan=studio`
- `php scripts/studio_plan_orchestrator.php next --plan=cat`
- `php scripts/studio_plan_orchestrator.php next --plan=food`
- `php scripts/studio_plan_orchestrator.php status --plan=cat`
- `php scripts/studio_plan_orchestrator.php done C1.1 --plan=cat`
- `php scripts/studio_plan_orchestrator.php done F1.1 --plan=food`

Reglas del orquestador:
1. No permite cerrar tareas fuera del orden definido.
2. Valida dependencias antes de marcar una tarea como done.
3. Siempre expone la siguiente tarea valida de alta prioridad.
4. Actualiza el estado persistente en JSON (no volatil).

Tareas VS Code habilitadas:
- `Roadmap: Next Studio Priority`
- `Roadmap: Studio Status`
- `Roadmap: Studio List`
