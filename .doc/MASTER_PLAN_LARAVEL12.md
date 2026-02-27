# MASTER PLAN (Única Fuente de Verdad) — SYNTIWEB (Laravel 12)

**Repositorio:** `c:\laragon\www\synticorex`

**Objetivo del documento:** Consolidar, en un solo lugar, la definición operativa del proyecto para desarrollo en **Laravel 12**, con foco en:

- Arquitectura **multi-tenant** (aislamiento, resolución de tenant, storage y seguridad).
- Alcance del **MVP** (qué está operativo vs. qué es prioridad inmediata).
- Protocolo de trabajo para **IAs** (Copilot/Continue/Cascade/otros) para mantener orden, evitar drift y respetar convenciones del proyecto.

---

## 1) Documentos fuente (y precedencia)

Cuando haya discrepancias, se decide en este orden:

1. `02_MATRIZ_FEATURES_DEFINITIVA.md`
2. `03_SCHEMA_DATABASE.md`
3. `00_README.txt` + `01_ROADMAP_MVP (checklist).md`
4. `PROGRESS.md` + `TASKS_STATUS.md`
5. Resto de documentos en `.doc/` (siempre subordinados a los anteriores)

---

## 2) Visión del producto (resumen)

**SYNTIWEB** es un generador de landing pages multi-tenant con un **template único** renderizado condicionalmente por plan.

- Cada **tenant** representa un negocio.
- Un tenant tiene:
  - Plan (`plans`) con límites y features.
  - Contenido editable (productos, servicios, personalización, etc.).
  - Dominio por subdominio y opción de dominio personalizado.

## 2.1 Vía Única de Renderizado (OBLIGATORIO)

**Única vía permitida:**

- `app/Http/Controllers/TenantRendererController.php`
- `resources/views/landing/base.blade.php`
- `resources/views/landing/partials/*`

**Regla:** cualquier vista de landing que no viva bajo `resources/views/landing/` se considera legacy y no se usa.

---

## 3) Arquitectura Multi-tenant (aislamiento de datos)

### 3.1 Principio de aislamiento

- **Todo dato “de negocio” cuelga de `tenant_id`.**
- El `tenant` se resuelve *al inicio* de cada request público (landing) por dominio.
- El acceso a CRUD y dashboard debe estar protegido por autenticación y/o PIN según corresponda (ver prioridades).

**Regla de oro:**

- Ninguna query de tablas tenant-scoped (`products`, `services`, `tenant_customization`, `analytics_events`, `invoices`) debe ejecutarse sin filtrar por `tenant_id`.

### 3.2 Resolución del tenant (dominio)

**Fuentes de identidad:**

- Subdominio (ejemplo): `joseburguer.menu.vip`
- Dominio personalizado: `www.joseburguer.com`

**Modelo de datos (según schema):**

- `tenants.subdomain`
- `tenants.base_domain`
- `tenants.custom_domain`
- `tenants.domain_verified`

**Comportamiento esperado:**

- Si no hay tenant válido → **404**.
- Cachear resolución cuando sea posible para performance.

### 3.3 Layout de datos (tablas y ownership)

Tablas principales (resumen):

- `users` → dueños/usuarios.
- `tenants` → unidad multi-tenant.
- `plans` → límites + flags.
- `products` (`tenant_id`) → catálogo.
- `services` (`tenant_id`) → servicios.
- `tenant_customization` (`tenant_id` UNIQUE) → personalización y JSONs (redes, medios pago, FAQ, etc.).
- `analytics_events` (`tenant_id`) → tracking de eventos.
- `dollar_rates` → tasa BCV con fallback.
- `invoices` (`tenant_id`) → facturación.
- `color_palettes` → paletas/temas.

### 3.4 Storage por tenant

Convención documentada:

- Path lógico (frontend):
  - `/storage/tenants/{tenant_id}/...`

- Path privado sugerido (documentado):
  - `storage/app/private/tenants/{tenant_id}/`

**Convención de nombres de imágenes:**

- `logo.webp`
- `hero.webp`
- `service_01.webp ... service_15.webp`
- `product_01.webp ... product_18.webp`

**Procesamiento obligatorio:**

- Upload máx: **2MB**
- Resize: **800px ancho** (alto proporcional)
- Formato final: **WebP**
- Reemplazo: al subir un slot, eliminar el anterior del mismo slot

### 3.5 Performance y caching

Criterios de validación (todos los planes):

- Carga < 2s en 3G
- Responsive mobile-first
- Lighthouse Performance > 90
- SEO score > 85
- Accesibilidad básica (WCAG 2.0 AA)

---

## 4) Alcance del MVP (definición operativa)

### 4.1 Qué debe existir (siempre)

Template único con secciones base (todos los planes):

- `Nav`
- `Hero`
- `Servicios`
- `Productos`
- `Footer`

### 4.2 Variantes por plan (Fuente: `02_MATRIZ_FEATURES_DEFINITIVA.md`)

**Planes:**

- Plan 1: **OPORTUNIDAD**
- Plan 2: **CRECIMIENTO**
- Plan 3: **VISIÓN**

**Límites/Features (resumen):**

- Productos: 6 / 12 / 18
- Servicios: 3 / 6 / 9
- Imágenes: 15 / 25 / 70 (con Factor de Holgura +4 para errores de subida)
- Paletas/Temas: 5 / 10 / 20
- Tasa dólar visible: Plan 2+ (Plan 3 con histórico)
- WhatsApp: 1 / 2 / 2 (+ filtro horario en Plan 3)
- Analytics: básico / medio / avanzado
- Secciones extra:
  - Plan 1: no
  - Plan 2: Header Top, Acerca de, Medios de Pago
  - Plan 3: FAQ, CTA especial, Sucursales

### 4.3 Estado actual (según `PROGRESS.md` y `TASKS_STATUS.md`)

**Operativo (reportado como completado):**

- Migraciones + seeders + modelos + relaciones
- `IdentifyTenant` y multidominio
- Renderizado dinámico (landing)
- Dashboard flotante (Alt+S / long press) + PIN auth
- CRUD productos/servicios con límites por plan
- Upload imágenes WebP
- Sistema moneda (4 modos)
- FlyonUI integrado (temas) y distribución por plan
- QR dinámico + toggle estado

**Prioridad inmediata (pendiente):**

- Mejorar contenido textual de demos
- Agregar imágenes profesionales a demos
- Limpieza de LEGACY `updatePalette`
- Centralizar lista de 17 temas
- Middleware/autenticación en rutas tenant (hardening)
- Analytics: tracking JS → `analytics_events`
- SEO: meta tags dinámicos
- Producción: servidor + DNS + SSL + cron

---

## 5) Reglas de implementación (invariantes)

### 5.1 Invariantes de dominio

- **Toda entidad tenant-scoped debe incluir `tenant_id`** y validarse en backend.
- Los límites de plan deben aplicarse **en backend** (no solo frontend):
  - Límite de productos/servicios/imágenes.
- El renderizado condicional debe depender de:
  - `plan_id` y/o flags/props del plan, con un único origen de verdad.

### 5.2 Invariantes de seguridad

- Requests públicos: solo lectura (landing) y siempre con tenant resuelto.
- Dashboard/CRUD: requiere auth (Breeze) y/o PIN.
- Nunca exponer paths privados de storage ni permitir traversal.

---

## 6) Protocolo para IAs (Copilot/Continue/Cascade/otros)

### 6.1 Objetivo

Permitir que cualquier IA contribuya sin romper:

- aislamiento multi-tenant,
- consistencia de planes/features,
- estructura Laravel 12,
- naming conventions,
- y sin introducir archivos “huérfanos” o duplicados.

### 6.2 Reglas obligatorias (no negociables)

- Usar **rutas completas** al referenciar archivos, especialmente en instrucciones tipo “edita X”.
- Antes de crear un archivo nuevo, **verificar si ya existe** y si hay un patrón vigente.
- No crear “nuevas fuentes de verdad” en paralelo:
  - Si se necesita centralización, se define explícitamente (ej. `config/syntiweb.php`) y se migra el uso.
- Mantener los límites/planes **alineados con `02_MATRIZ_FEATURES_DEFINITIVA.md`**.
- Mantener tablas y columnas alineadas con `03_SCHEMA_DATABASE.md`.

### 6.3 Flujo recomendado para cambios

1. Identificar el documento fuente afectado (matriz/schema).
2. Ubicar el punto exacto del código en Laravel 12 (Controller/Service/Middleware/View).
3. Implementar el cambio con:
   - validación backend,
   - pruebas mínimas manuales,
   - y sin duplicar lógica.
4. Si el cambio altera comportamiento de plan/feature, actualizar **solo** la documentación “fuente” (idealmente este documento + matriz/schema si aplica).

### 6.4 Convenciones Laravel 12 (estructura)

- HTTP:
  - `app/Http/Middleware/*`
  - `app/Http/Controllers/*`
  - `routes/web.php` (landing) y `routes/*` según corresponda
- Dominio/Servicios:
  - `app/Services/*` (ej. DollarRateService, TenantContentService)
- Vistas:
  - `resources/views/*` y componentes/partials bien segmentados
- Assets:
  - `resources/js/*`, `resources/css/*` y build con Vite

**Regla:** no meter lógica compleja en Blade si puede vivir mejor en Service/Controller.

---

## 7) Puntos de atención (riesgos de drift)

- Discrepancias entre límites por plan en:
  - schema vs matriz vs implementación.
- Duplicación de “listas de secciones” y “listas de temas”.
- Rutas tenant sin hardening (auth/middleware) para endpoints sensibles.
- SEO y analytics incompletos: riesgo de “MVP funcional” pero sin trazabilidad/marketing.

---

## 8) Checklist rápido antes de tocar código

- ¿Esto afecta multi-tenant? → asegurar `tenant_id` + resolución tenant + scopes.
- ¿Esto afecta planes/features? → validar contra `02_MATRIZ_FEATURES_DEFINITIVA.md`.
- ¿Esto afecta schema? → validar contra `03_SCHEMA_DATABASE.md`.
- ¿Esto agrega endpoints? → asegurar middleware y validación.
- ¿Esto toca imágenes? → respetar WebP, 800px, 2MB, naming.

---

**Fin del documento.**
