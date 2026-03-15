# NEXT SESSION — Fase D: Tablero Admin
**Fecha:** 14 MAR 2026 (actualizado post-sesión)
**Commit cierre:** cbd2426 — 24 archivos, 556 inserciones
**Branch activo:** feature/limpieza-frankenstein
**Tenant demo CAT:** arepera / tenant_id=7 / http://127.0.0.1:8000/arepera

---

## ESTADO REAL AL CIERRE

### ✅ PRODUCTOS CERRADOS
| Producto | Estado | Orquestador |
|---|---|---|
| SYNTIstudio | ✅ 100% | S7.1 done |
| SYNTIfood | ✅ 100% | F5.1 done |
| SYNTIcat | ✅ 100% | C5.2 done |

### ✅ ENTREGADO HOY
- CAT app redesign completo (hero slider, chips, grid 2col, bottom nav)
- Hero slider CAT con gating por plan (1 img básico / 3 imgs semestral+anual)
- PWA manifest dinámico por tenant (columna: subdomain)
- Favicon SYNTIweb global — 8 layouts cubiertos
- Panel secreto restaurado: long press hamburger → Studio, long press logo → Food
- `#synti-mobile-trigger` oculto permanentemente
- `trackingQRSmall ?? ''` guard — no más crashes PHP
- Execution plans actualizados (CAT+FOOD+STUDIO)

### ⚠️ PENDIENTE MENOR
- Sticky bar CAT con búsqueda + moneda integrada (UX improvement, no blocker)
- Service worker PWA (solo en producción con HTTPS — Fase E)
- Documentación usabilidad en ai_docs (post D.1)
- Smoke test E2E manual CAT en browser real

---

## PRÓXIMA TAREA: D.1 — TABLERO ADMIN

### Objetivo
Panel en `/admin` exclusivo para Carlos (superadmin).
Ver, editar, suspender y eliminar tenants desde UI.

### Secuencia D.1 → D.6
```
D.1 → Gestión tenants (tabla + acciones)
D.2 → Gestión planes y precios (CRUD)
D.3 → Gestión usuarios/clientes
D.4 → Métricas globales (MRR, conversión, activos)
D.5 → Rol vendedor (comisiones)
D.6 → Rol soporte (lectura sin edición)
```

### Specs D.1
- Ruta: `/admin/tenants`
- Middleware: rol superadmin (crear si no existe)
- Tabla: tenants con columnas: negocio, plan, blueprint, estado, creado, acciones
- Acciones por tenant: Ver landing | Ver dashboard | Editar plan | Suspender | Eliminar
- Layout: usar `layouts/admin.blade.php` existente
- Stack: Preline 4.1.2 + Tailwind v4 — PROHIBIDO DaisyUI/FlyonUI
- Iconos: tabler-- únicamente

### Archivos a crear/tocar
```
app/Http/Controllers/Admin/TenantAdminController.php  (nuevo)
resources/views/admin/tenants/index.blade.php          (nuevo)
routes/web.php                                          (agregar grupo admin)
app/Http/Middleware/EnsureSuperAdmin.php               (nuevo si no existe)
```

---

## ARRANQUE DE SESIÓN

### Paso 1 — Verificar estado (PowerShell)
```powershell
cd C:\laragon\www\synticorex
git status
git log --oneline -5
php artisan tinker --execute="echo App\Models\Tenant::count() . ' tenants';"
```

### Paso 2 — Leer orquestador
```powershell
php scripts/studio_plan_orchestrator.php next --plan=cat
```

### Paso 3 — Arrancar D.1
Generar prompt Opus para TenantAdminController + vista index.

---

## DECISIONES ARQUITECTÓNICAS PENDIENTES
- Precios definitivos Studio/Food/Cat — **diferido, al final**
- Soporte tiers por plan (WhatsApp restringido por plan) — pendiente antes de landing
- Programa Aliados (comisiones) — Fase F

---

## REGLAS QUE NO CAMBIAN
- Stack: Laravel 12 + Preline 4.1.2 + Tailwind v4
- Iconos: tabler-- con iconify-icon
- Nunca hardcodear precios en Blade
- Grep de verificación en cada prompt
- Un task a la vez — Carlos aprueba antes de continuar

**Última actualización:** 14 MAR 2026 — Sesión CAT redesign + PWA
**Responsable:** Carlos Bolívar (Arquitecto) + Claude (Co-ejecutor)