# AUDITORÍA DE ARQUITECTURA — SYNTIweb
**Fecha:** 11 de marzo de 2026  
**Auditor:** AI Architecture Auditor  
**Proyecto:** SyntiWeb — SaaS Multitenant Landing Pages  
**Stack:** Laravel 12.51 · PHP 8.3 · MySQL · Preline 4.1.2 · Tailwind 4.2 · Alpine.js 3.4.2

---

## 1. INVENTARIO TOTAL

**Resumen numérico:**
- 75 archivos PHP en `app/`
- 38 migraciones en `database/migrations/`
- 9 seeders
- 1 factory (UserFactory)
- 116 archivos Blade activos (excluyendo `_archive/` y `.claude/worktrees/`)
- 11 tests (8 Breeze defaults + 1 ExampleUnit + 1 ExampleFeature + 1 PlanLimitValidation)
- 13 servicios en `app/Services/`
- 8 comandos Artisan en `app/Console/Commands/`
- 2 middleware custom
- 4 archivos de rutas

### Modelos (14)

| Archivo | Estado | Observación |
|---------|--------|-------------|
| `app/Models/Tenant.php` | COMPLETO | `strict_types`, fillable, relaciones, trait HasBlueprint, tenant root |
| `app/Models/TenantCustomization.php` | COMPLETO | `canAccessSection()` por plan, 34 columnas denormalizadas |
| `app/Models/User.php` | COMPLETO | Auth estándar, relación con tenants |
| `app/Models/Product.php` | COMPLETO | Scoped por tenant_id, `getAllImageUrls()` |
| `app/Models/Service.php` | COMPLETO | Scoped por tenant_id |
| `app/Models/Plan.php` | COMPLETO | Constants OPORTUNIDAD/CRECIMIENTO/VISION, 9 planes con blueprints |
| `app/Models/ProductImage.php` | COMPLETO | FK a Product, posición gallery Plan 3 |
| `app/Models/TenantBranch.php` | COMPLETO | Scoped por tenant_id, Plan 3 |
| `app/Models/AnalyticsEvent.php` | COMPLETO | IP hasheada SHA256, event tracking |
| `app/Models/ColorPalette.php` | COMPLETO | min_plan_id gating, 17 paletas |
| `app/Models/DollarRate.php` | COMPLETO | Scopes scopeUsd(), scopeEur() |
| `app/Models/Invoice.php` | COMPLETO | Scoped por tenant_id, tracking de pagos |
| `app/Models/AiChatLog.php` | COMPLETO | tenant_id nullable, feedback tracking |
| `app/Models/AiDoc.php` | COMPLETO | FULLTEXT en MySQL, search() |

### Controladores (28)

| Archivo | Estado | Observación |
|---------|--------|-------------|
| `app/Http/Controllers/TenantRendererController.php` | COMPLETO | Render landing, PIN verify, eager loading, Schema.org, QR |
| `app/Http/Controllers/DashboardController.php` | COMPLETO | 7 tabs CRUD (Info, Productos, Diseño, Mensaje, Ventas, Visual, Config) |
| `app/Http/Controllers/ImageUploadController.php` | COMPLETO | Upload logo/hero/product, conversión WebP |
| `app/Http/Controllers/ProductController.php` | COMPLETO | CRUD completo, filtros, paginación, reorder |
| `app/Http/Controllers/ServiceController.php` | COMPLETO | CRUD completo, filtros, reorder |
| `app/Http/Controllers/TenantController.php` | COMPLETO | CRUD tenants, eager loading |
| `app/Http/Controllers/AnalyticsController.php` | COMPLETO | Track events, rate limiting por cache (100/min/tenant) |
| `app/Http/Controllers/CheckoutController.php` | COMPLETO | Solo plan cat-anual, genera WhatsApp URL |
| `app/Http/Controllers/OrdersController.php` | COMPLETO | Orders desde JSON en storage |
| `app/Http/Controllers/QRTrackingController.php` | COMPLETO | Shortlink redirect + analytics event |
| `app/Http/Controllers/MarketingController.php` | COMPLETO | Landing marketing, 3 planes |
| `app/Http/Controllers/OnboardingController.php` | COMPLETO | Wizard completo, checkSubdomain, tenant bootstrap |
| `app/Http/Controllers/ProfileController.php` | COMPLETO | Breeze profile management |
| `app/Http/Controllers/SyntiHelpController.php` | COMPLETO | AI chatbot con FULLTEXT search + Bytez |
| `app/Http/Controllers/Food/MenuController.php` | COMPLETO | Menu JSON para SyntiFood |
| `app/Http/Controllers/Food/CategoriesController.php` | COMPLETO | CRUD categorías, photo limit |
| `app/Http/Controllers/Food/ItemsController.php` | COMPLETO | CRUD items, validación opciones, image upload |
| `app/Http/Controllers/Food/ComandaController.php` | COMPLETO | Comanda WhatsApp, persistencia por plan |
| Auth controllers (9) | COMPLETO | Breeze estándar completo |

### Servicios (13)

| Archivo | Estado | Observación |
|---------|--------|-------------|
| `app/Services/DollarRateService.php` | COMPLETO | API DolarAPI.com, fallback 36.50 USD / 495.00 EUR, cache TTL |
| `app/Services/ImageUploadService.php` | COMPLETO | WebP 90%, 2MB límite, 800px max shrink |
| `app/Services/QRService.php` | COMPLETO | Código determinístico, SVG+PNG output |
| `app/Services/MenuService.php` | COMPLETO | JSON menu, límites por plan (50/100/150 items) |
| `app/Services/OrderService.php` | COMPLETO | Prefijo SC-, WhatsApp channel |
| `app/Services/ComandaService.php` | COMPLETO | Prefijo SF-, tipos sitio/llevar/delivery |
| `app/Services/BusinessHoursService.php` | COMPLETO | Horario legible "Abrimos en 15 minutos" |
| `app/Services/WhatsappMessageBuilder.php` | COMPLETO | Generación URL wa.me |
| `app/Services/ProductImageGeneratorService.php` | COMPLETO | Placeholder + GD fallback |
| `app/Services/ServiceImageGeneratorService.php` | COMPLETO | Colores por segmento |
| `app/Services/PrelineThemeService.php` | COMPLETO | Validación temas Preline por plan |
| `app/Services/FlyonUIThemeService.php` | **OBSOLETO** | Contradice regla "NUNCA FlyonUI" — código muerto |
| `app/Services/TenantBootstrap*.php` (2) | COMPLETO | Bootstrap JSON para Cat y Food |

### Vistas (116 Blade activos)

| Directorio | Archivos | Estado |
|------------|----------|--------|
| `landing/sections/` | 23 | COMPLETO — hero (3 variantes), header (3), products, services, about, testimonials, faq, branches, contact, payment_methods, cta, footer (2), floating-panel, delivery-info |
| `landing/templates/` | 3 (+2 .bak) | COMPLETO — studio, food, catalog |
| `landing/schemas/` | 7 | COMPLETO — LocalBusiness, Restaurant, Store, Health, Professional, Education, Transport |
| `landing/base.blade.php` | 1 | PARCIAL — **falta favicon kit** |
| `dashboard/components/` | 11 | COMPLETO — info, products, services, design, message, sales, visual, config, menu, orders, comandas |
| `dashboard/modals/` | 3 | COMPLETO |
| `dashboard/scripts/` | 4 | COMPLETO |
| `dashboard/partials/` | 2 | COMPLETO — synti-header-btn (Alt+H), synti-assistant |
| `dashboard/index.blade.php` | 1 | PARCIAL — **falta favicon kit** |
| `marketing/` | 5 main + 11 sections | COMPLETO |
| `onboarding/` | 5 | COMPLETO — wizard, wizard-food, wizard-cat, selector, preview |
| `auth/` | 6 | COMPLETO — Breeze estándar |
| `errors/` | 3 | **ROTO** — usan CDN Tailwind en lugar de @vite() |
| `layouts/` | 4 | PARCIAL — admin.blade.php usa CDN Tailwind |
| `components/` | 20+ | COMPLETO — UI, form, nav components |
| `dashboard/_archive/` | 8 | OBSOLETO — DaisyUI classes activas (btn, join, etc.) |

### Infraestructura

| Archivo | Estado | Observación |
|---------|--------|-------------|
| `routes/web.php` | COMPLETO | ~150 rutas, auth middleware en dashboard |
| `routes/api.php` | **PARCIAL** | API tenants/products/services SIN auth:sanctum |
| `routes/auth.php` | COMPLETO | Breeze estándar |
| `routes/console.php` | COMPLETO | Scheduler: dollar:update (hourly), tenants:check-expiry (daily 2AM) |
| `app/Http/Middleware/IdentifyTenant.php` | **PARCIAL** | Detecta subdomain + path + custom_domain. **NO verifica domain_verified** |
| `app/Http/Middleware/ContentSecurityPolicy.php` | COMPLETO | Headers CSP, deshabilitado en local |
| `config/tenancy.php` | COMPLETO | Config subdomains + custom domains |
| `config/ai.php` | COMPLETO | Bytez provider, Qwen3-8B |
| `config/preline-themes.php` | COMPLETO | 29 temas configurados |
| `config/flyonui-themes.php` | **OBSOLETO** | Contradice regla "NUNCA FlyonUI" |
| `config/blueprints.php` | COMPLETO | 4 segmentos: Food, Retail, Health, Professional |
| `composer.json` | COMPLETO | Laravel ^12.0, Intervention ^3.11, QR ^4.2, PHP ^8.2 |
| `package.json` | COMPLETO | Preline ^4.1.2, Tailwind ^4.2.0, Alpine ^3.4.2. Sin DaisyUI/FlyonUI |
| `vite.config.js` | COMPLETO | Entries: app.css + app.js, Tailwind plugin |

---

## 2. BASE DE DATOS

### Tablas existentes vs esperadas (38 migraciones → 17 tablas)

| Tabla | Existe | Columnas clave | FK | Índices | Estado |
|-------|:------:|---------------|:--:|:-------:|--------|
| `users` | ✅ | id, name, email, password | — | email unique | COMPLETO |
| `tenants` | ✅ | id, user_id, plan_id, subdomain, custom_domain, domain_verified, business_name, status, edit_pin, settings JSON, +20 cols | user_id→users, plan_id→plans | subdomain unique, custom_domain unique, (status, subscription_ends_at) | COMPLETO |
| `plans` | ✅ | id, slug, name, price_usd, products_limit, services_limit, blueprint, +12 feature flags | — | slug unique | COMPLETO |
| `products` | ✅ | id, tenant_id, name, description, price_usd, price_bs, image_filename, image_url, position, is_active, is_featured, badge | tenant_id→tenants CASCADE | (tenant_id, is_active) | COMPLETO |
| `services` | ✅ | id, tenant_id, name, description, icon_name, image_filename, position, is_active | tenant_id→tenants CASCADE | (tenant_id, is_active) | COMPLETO |
| `tenant_customization` | ✅ | id, tenant_id UNIQUE, logo, hero (×5), theme_slug, social_networks JSON, payment_methods JSON, faq_items JSON, content_blocks JSON, +20 cols | tenant_id→tenants CASCADE | tenant_id unique | COMPLETO (34 cols — denormalizado) |
| `product_images` | ✅ | id, product_id, image_filename, position | product_id→products CASCADE | (product_id, position) | COMPLETO |
| `tenant_branches` | ✅ | id, tenant_id, name, address, is_active | tenant_id→tenants CASCADE | (tenant_id, is_active) | COMPLETO |
| `analytics_events` | ✅ | id, tenant_id, event_type, user_ip, user_agent, referer, event_date, event_hour | tenant_id→tenants CASCADE | (tenant_id, event_date), (tenant_id, event_type) | COMPLETO |
| `dollar_rates` | ✅ | id, rate, source, currency_type, effective_from, effective_until, is_active | — | (is_active, effective_from) | COMPLETO |
| `invoices` | ✅ | id, tenant_id, invoice_number, amount_usd, status | tenant_id→tenants CASCADE | (tenant_id, status) | COMPLETO |
| `color_palettes` | ✅ | id (tinyInt), name, slug, colors (nullable), min_plan_id, category | — | slug unique | COMPLETO |
| `ai_docs` | ✅ | id, slug, title, product, content TEXT, source_file | — | slug unique, FULLTEXT(title, content) | COMPLETO |
| `ai_chat_logs` | ✅ | id, tenant_id nullable, product, question, answer, helpful | tenant_id→tenants SET NULL | — | COMPLETO |
| `sessions` | ✅ | Framework | user_id→users | user_id, last_activity | COMPLETO |
| `cache` / `cache_locks` | ✅ | Framework | — | — | COMPLETO |
| `jobs` / `job_batches` / `failed_jobs` | ✅ | Framework | — | queue | COMPLETO |

### Tablas que NO existen (esperadas del roadmap)

| Tabla esperada | Estado | Impacto |
|----------------|--------|---------|
| `blog_posts` | NO EXISTE | Blog no implementado |
| `testimonials` | NO EXISTE | Almacenados en JSON dentro de `tenant_customization.visual_effects` — no queryable |
| `faq` | NO EXISTE | Almacenados en JSON dentro de `tenant_customization.faq_items` — no queryable |
| `categories` (productos) | NO EXISTE | Solo SyntiFood tiene categorías (en JSON storage), SyntiStudio/Cat no |
| `users_tenants` (pivot) | NO EXISTE | Solo 1 user por tenant (`tenants.user_id`). Sin soporte multi-usuario |
| `notifications` | NO EXISTE | Sin sistema de notificaciones |

### Seeders

| Seeder | Idempotente | Observación |
|--------|:-----------:|-------------|
| `PlansSeeder` | ✅ updateOrCreate | 9 planes (studio×3, food×3, cat×3) |
| `ColorPalettesSeeder` | ⚠️ TRUNCATE | Destruye datos en re-run — OK para dev, peligroso en prod |
| `DollarRatesSeeder` | ✅ | Tasa inicial 36.50 |
| `DemoDataSeeder` | ✅ updateOrCreate | 3+ tenants demo, depende de servicios externos |
| `AiDocSeeder` | ⚠️ TRUNCATE | Destruye docs en re-run |
| `FlyonUIThemesSeeder` | N/A | Solo comentarios, no inserta datos |
| `TestingSeeder` | ✅ firstOrCreate | Datos para tests |
| `UpdateTenantsThemesSeeder` | ✅ | Utilidad, no llamado por DatabaseSeeder |
| `DatabaseSeeder` | ✅ | Orquesta: Plans → Palettes → DollarRates → Demo → AiDocs |

### Factories

| Factory | Modelo | Observación |
|---------|--------|-------------|
| `UserFactory` | User | ÚNICO factory existente |
| **TenantFactory** | — | **NO EXISTE** — PlanLimitValidationTest lo necesita y fallará |
| **ProductFactory** | — | **NO EXISTE** — Tests no pueden generar productos |
| **ServiceFactory** | — | **NO EXISTE** |

---

## 3. MULTITENANCY

### Middleware `IdentifyTenant`

**Ubicación:** `app/Http/Middleware/IdentifyTenant.php`

**Resolución triple:**
1. **Subdomain:** `pepe.tu.menu` → busca `tenants.subdomain = 'pepe'` + `status = 'active'`
2. **Path mode (dev):** `synticorex.test/pepe` → busca por path slug
3. **Custom domain:** `midominio.com` → busca `tenants.custom_domain = host` + `status = 'active'`

### Riesgos detectados

| # | Riesgo | Severidad | Detalle |
|---|--------|-----------|---------|
| 1 | **Custom domain sin verificar** | ALTA | `IdentifyTenant` no chequea `domain_verified`. Un atacante podría configurar su DNS para apuntar al servidor y robar un tenant. El controller `showByDomain()` SÍ verifica, pero el middleware inyecta el tenant en el container antes sin la verificación. |
| 2 | **API sin auth — cross-tenant posible** | CRÍTICA | `routes/api.php`: las rutas `/api/tenants/{tenantId}/products` **no tienen middleware auth ni sanctum**. Cualquier persona puede hacer CRUD de productos de cualquier tenant conociendo el ID. Solo dice `// add auth:sanctum in production`. |
| 3 | **PIN sin rate limit** | ALTA | `POST /tenant/{tenantId}/verify-pin` no tiene throttle. PIN de 4 dígitos = 10,000 combinaciones. Brute-forceable en minutos. |
| 4 | **Tenant_id como parámetro de ruta** | MEDIA | Varias rutas web usan `{tenantId}` directo en URL sin verificar que el user autenticado sea dueño (ej: `/dashboard/tenants/{tenantId}/upload/logo`). Se confía en que el PIN ya fue verificado en frontend. |

### Aislamiento por tabla

| Tabla | tenant_id FK | Aislamiento en queries | Estado |
|-------|:------------:|:----------------------:|--------|
| products | ✅ | ✅ Where tenant_id en controllers | OK |
| services | ✅ | ✅ | OK |
| product_images | Via product_id → tenant | ✅ Verifica product.tenant_id | OK |
| analytics_events | ✅ | ✅ + rate limit por tenant | OK |
| invoices | ✅ | ✅ | OK |
| tenant_branches | ✅ | ✅ | OK |
| tenant_customization | ✅ unique | ✅ | OK |
| ai_chat_logs | ✅ nullable | ⚠️ Nullable — logs compartidos | ACEPTABLE |

---

## 4. CATÁLOGO (Productos y Servicios)

### CRUD de Productos

| Operación | Implementado | Archivo |
|-----------|:------------:|---------|
| Listar | ✅ | `ProductController@index` — filtros, paginación, precio_bs calculado |
| Crear | ✅ | `ProductController@store` — validación inline |
| Editar | ✅ | `ProductController@update` |
| Eliminar | ✅ | `ProductController@destroy` |
| Reordenar | ✅ | `ProductController@reorder` |
| Toggle activo | ✅ | `ProductController@toggleActive` |
| Toggle destacado | ✅ | `ProductController@toggleFeatured` |

### CRUD de Servicios — Idéntica estructura ✅

### Límites por plan

| Plan | Productos | Servicios | Imágenes gallery |
|------|:---------:|:---------:|:----------------:|
| OPORTUNIDAD ($99) | 6 | 3 | 0 |
| CRECIMIENTO ($149) | 12 | 6 | 0 |
| VISIÓN ($199) | 18 (nullable=ilimitado) | 9 | 3 slider |

**¿Se aplican?**
- ✅ `TenantCustomization::canAccessSection()` — gating de secciones por plan
- ✅ `HasBlueprint::getMaxItems()` — retorna límite numérico por plan
- ✅ Dashboard muestra contadores y desactiva botón "Agregar" cuando se alcanza el límite
- ⚠️ **Validación solo en frontend (dashboard JS).** La API REST (`api.php`) **NO valida límites** — se pueden crear productos ilimitados via API directa.

### Upload de imágenes

| Aspecto | Estado | Detalle |
|---------|--------|---------|
| Conversión WebP | ✅ | `ImageUploadService` → Intervention Image v3, calidad 90% |
| Tamaño máximo | ✅ | 2MB, resize a 800px max dimension |
| Storage | ✅ | `storage/tenants/{tenant_id}/` |
| Validación backend | ✅ | Tipo mime, tamaño, tenant ownership |

---

## 5. FRONTEND Y DASHBOARD

### Secciones de landing existentes vs esperadas

| Sección | Plan mínimo | ¿Existe? | Variantes |
|---------|:-----------:|:--------:|-----------|
| hero | 1 | ✅ | fullscreen-v2, gradient, split |
| header / header-top | 1 | ✅ | header, header-top, navbar-v2 |
| products | 1 | ✅ | Con badge, featured, gallery |
| services | 1 | ✅ | cards, spotlight |
| contact | 1 | ✅ | Maps + cards |
| payment_methods | 1 | ✅ | 17 métodos de pago |
| cta | 1 | ✅ | centered |
| footer | 1 | ✅ | footer, footer-v2 |
| about | 2 | ✅ | Texto + imagen |
| testimonials | 2 | ✅ | Datos desde JSON en customization |
| faq | 3 | ✅ | Accordion Preline + Schema.org |
| branches | 3 | ✅ | Lista con dirección |
| floating-panel | todos | ✅ | Dashboard overlay con PIN |
| delivery-info | food | ✅ | Info delivery SyntiFood |

### Dashboard flotante

| Aspecto | Estado | Detalle |
|---------|--------|---------|
| Existe | ✅ | `landing/sections/floating-panel.blade.php` (~800 líneas) |
| Alt+S activación | ✅ | `if (e.altKey && e.key === 's')` en línea 784 |
| Long press móvil | ✅ | Touch handlers implementados |
| PIN autenticación | ✅ | Hash::check contra DB. **Sin rate limiting.** |
| Tabs reales | 7 | Info, Productos/Menu, Diseño, Mensaje, Ventas, Visual, Config |
| Tabs esperadas | 6 | Info, Productos, Servicios, Diseño, Analytics, Config |
| Discrepancia | ⚠️ | Servicios mergeado en Productos. "Mensaje" y "Visual" son tabs extra. |

### Sistema de paletas CSS

| Aspecto | Estado |
|---------|--------|
| Temas Preline | ✅ 29 temas en `config/preline-themes.php` |
| Color palettes DB | ✅ 17 paletas con min_plan_id gating |
| Custom palette | ✅ CSS variables inyectadas en `base.blade.php` si `theme_slug === 'custom'` |
| FlyonUI (OBSOLETO) | ⚠️ `FlyonUIThemeService.php` + `config/flyonui-themes.php` + migracion D.21 referencia FlyonUI themes — código muerto que confunde |

### Responsive

- ✅ Mobile-first en todos los templates (xs/sm/md/lg breakpoints)
- ✅ Floating panel como bottom-sheet en móvil
- ✅ Dashboard sidebar collapsa en móvil
- ✅ Grids responsivos en cards de productos/servicios
- ✅ Touch targets 44px+ en elementos interactivos

### Violaciones de UI encontradas

| # | Archivo | Violación |
|---|---------|-----------|
| 1 | `errors/404.blade.php` | ~~CDN Tailwind~~ ✅ **RESUELTO 2026-03-12** — Rediseño completo, branding SyntiWeb, SVG protagonista |
| 2 | `errors/500.blade.php` | ~~CDN Tailwind~~ ✅ **RESUELTO 2026-03-12** — Diseño consistente con 404, badge rojo 500 |
| 3 | `errors/tenant-not-found.blade.php` | ~~CDN Tailwind~~ ✅ **RESUELTO 2026-03-12** — Diseño consistente, mantiene `$identifier` |
| 4 | `layouts/admin.blade.php` | CDN Tailwind — vista experimental |
| 5 | `dashboard/_archive/services-section.blade.php` | Clases DaisyUI: `btn`, `btn-primary`, `join-item`, `btn-ghost` |
| 6 | `dashboard/_archive/products-section.blade.php` | Clases DaisyUI: `btn`, `btn-primary`, `btn-error` |

**Nota:** `_archive/` son archivos obsoletos. No se renderizan nunca, pero deberían eliminarse.

---

## 6. SEO / ANALYTICS / BLOG / AYUDA

### SeoService

| Aspecto | Estado |
|---------|--------|
| ¿Existe como clase? | **NO** — solo existe en documentación `.doc/05_SEO_AUTOMATICO.md` |
| SEO implementado | ✅ PARCIAL — inline en `TenantRendererController`: `buildMetaTags()`, `buildSchema()` |
| Meta tags | ✅ title, description, keywords, og:title, og:description, og:image, canonical |
| Schema.org | ✅ 7 schemas: Restaurant, Store, Health, Professional, Education, Transport, LocalBusiness |
| Sitemap | **NO EXISTE** |
| Robots.txt | ✅ `public/robots.txt` (estático) |

### Analytics / Tracking

| Aspecto | Estado |
|---------|--------|
| `AnalyticsController@track` | ✅ Endpoint público |
| Tipos de evento | ✅ pageview, click_whatsapp, click_call, click_toggle_currency, time_on_page, qr_scan |
| Rate limiting | ✅ 100 eventos/min/tenant via Cache |
| Privacidad IP | ✅ SHA256 hash, truncado a 45 chars |
| Dashboard analytics | ✅ `sales-section.blade.php` — muestra estadísticas |
| JS tracking frontend | ✅ Implementado en templates (studio, food) |

### Blog

| Aspecto | Estado |
|---------|--------|
| Modelo BlogPost | **NO EXISTE** |
| Migración | **NO EXISTE** |
| Controller | **NO EXISTE** |
| Rutas | **NO EXISTE** |
| Vistas | **NO EXISTE** |
| **Veredicto** | **CERO líneas de código.** Feature del roadmap sin implementar. |

### Módulo Ayuda (SYNTI Assistant)

| Aspecto | Estado |
|---------|--------|
| `SyntiHelpController` | ✅ COMPLETO |
| Búsqueda docs | ✅ FULLTEXT MySQL en `ai_docs` |
| AI provider | ✅ Bytez (Qwen3-8B) via `config/ai.php` |
| Feedback | ✅ Thumbs up/down almacenado en `ai_chat_logs` |
| Docs seeded | ✅ 20+ documentos en `AiDocSeeder` |
| Frontend | ✅ `synti-assistant.blade.php` integrado en dashboard |
| Throttle | ✅ 30 req/min authenticated, 10 req/hora público |

---

## 7. INFRAESTRUCTURA

### Routes

| Grupo | Middleware | Estado |
|-------|-----------|--------|
| Landing pública | web + IdentifyTenant | ✅ Protegido por tenant scope |
| Dashboard CRUD | auth | ✅ Requiere login |
| Image uploads | auth | ✅ Requiere login |
| API tenants/products/services | **NINGUNO** | **CRÍTICO** — Sin auth:sanctum |
| API analytics track | web (público) | ✅ Correcto — es endpoint público |
| SYNTI help | throttle:30,1 | ✅ Rate limited |
| Food CRUD | auth | ✅ Protegido |
| PIN verify | web | ⚠️ **Sin throttle** |
| Onboarding | web | ✅ Público correcto |

**Conflictos de rutas:** Ninguno detectado.

### Auth

| Aspecto | Estado |
|---------|--------|
| Laravel Breeze instalado | ✅ |
| Login/Register/Reset/Verify | ✅ 9 controllers auth |
| Middleware auth en Dashboard | ✅ |
| PIN system para tenants | ✅ Hash::check — pero sin rate limit |

### Jobs / Scheduler

| Tarea | Schedule | Middleware | Estado |
|-------|----------|-----------|--------|
| `dollar:update` | Hourly | withoutOverlapping, runInBackground | ✅ Fetch DolarAPI + store |
| `tenants:check-expiry` | Daily 2AM | withoutOverlapping, runInBackground | ✅ Lifecycle management |

**Nota:** No hay Jobs queue class — todo se resuelve con Scheduler y comandos Artisan. Aceptable para esta escala.

### Composer.json — Dependencias clave

| Paquete | Versión | Estado |
|---------|---------|--------|
| `laravel/framework` | ^12.0 | ✅ |
| `php` | ^8.2 | ✅ |
| `intervention/image` | ^3.11 | ✅ |
| `simplesoftwareio/simple-qrcode` | ^4.2 | ✅ |
| `laravel/breeze` (dev) | ^2.3 | ✅ |
| `phpunit/phpunit` (dev) | ^11.5 | ✅ |

**Package.json:**
| Paquete | Versión | Estado |
|---------|---------|--------|
| `preline` | ^4.1.2 | ✅ |
| `tailwindcss` | ^4.2.0 | ✅ |
| `alpinejs` | ^3.4.2 | ✅ |
| `@iconify-json/tabler` | ^1.2.27 | ✅ |
| FlyonUI | No presente | ✅ Correcto |
| DaisyUI | No presente | ✅ Correcto |

---

## 8. BUGS CRÍTICOS

| # | Archivo:línea | Descripción | Impacto |
|---|---------------|-------------|---------|
| 1 | `routes/api.php:12-45` | **API CRUD sin auth:sanctum.** Cualquier persona puede crear/editar/eliminar productos y servicios de cualquier tenant vía `/api/tenants/{id}/products`. Solo dice `// add auth:sanctum in production`. | **BRECHA DE SEGURIDAD** — Destrucción o manipulación de datos de cualquier tenant desde internet. |
| 2 | `routes/web.php:73` + `TenantRendererController.php:579` | **PIN verify sin throttle.** POST a `/tenant/{tenantId}/verify-pin` sin rate limiting. PIN de 4 dígitos = 10,000 combinaciones. Brute-forceable con script simple. | **BRECHA DE SEGURIDAD** — Acceso no autorizado al panel de administración de cualquier tenant. |
| 3 | `app/Http/Middleware/IdentifyTenant.php:77-78` | **Custom domain resuelve tenant sin verificar `domain_verified`.** El middleware ejecuta `Tenant::where('custom_domain', $host)->...->first()` sin filtrar `domain_verified = true`. Un atacante que apunte su DNS al servidor podría hijackear un tenant si configura `custom_domain` en la DB (indirecto) o si un tenant no verificado tiene custom_domain set. | **RIESGO MEDIO** — El controller `showByDomain()` SÍ verifica, pero el middleware inyecta el tenant en container para otras rutas. |
| 4 | `tests/Unit/PlanLimitValidationTest.php:30-31` | **Test usa factories que no existen.** `Tenant::factory()` y `Product::factory()` no existen. Este test **siempre falla** con `BadMethodCallException`. | **Test suite rota** — CI/CD no puede validar límites de plan. |
| 5 | `app/Services/FlyonUIThemeService.php` + `config/flyonui-themes.php` | **Código FlyonUI residual.** Las instrucciones dicen "NUNCA FlyonUI" pero existe un servicio completo + config + migración D.21 referencia FlyonUI themes. Confunde a desarrolladores y puede causar bugs si se invoca accidentalmente. | **DEUDA TÉCNICA** — Confusión, posible invocación accidental. |
| 6 | `resources/views/landing/base.blade.php` + `dashboard/index.blade.php` + `layouts/app.blade.php` | **Favicon kit ausente en TODOS los layouts.** Las instrucciones requieren el favicon kit en 3 layouts específicos. Ninguno lo tiene. | **BRANDING** — Sin favicon SyntiWeb en producción. Profesionalismo comprometido. |

---

## 9. LO QUE NO EXISTE

| Feature | Es blocker para MVP | Horas estimadas |
|---------|:--------------------:|:---------------:|
| **Rate limiting en PIN verify** | SÍ | 0.5h |
| **auth:sanctum en API** | SÍ | 1h |
| **Favicon kit en layouts** | SÍ | 0.5h |
| **Blog completo** (modelo, migration, CRUD, vistas) | NO | 15-20h |
| **SeoService como clase** (sitemap XML, robots dinámico) | NO | 5h |
| **Testimonials como tabla propia** (en vez de JSON) | NO | 4h |
| **FAQ como tabla propia** | NO | 4h |
| **Sistema de notificaciones** | NO | 8h |
| **Multi-usuario por tenant** (pivot users_tenants) | NO | 10h |
| **White Label** (favicon propio, footer custom) | ⚡ PARCIAL — `@if(!$tenant->white_label)` en footer tenant implementado. Toggle en dashboard pendiente. | 3h (resid: 2.5h) |
| **Factories para Tenant/Product/Service** | NO (sí para testing) | 2h |
| **Tests de integración reales** (más allá de Breeze) | NO (sí para calidad) | 15h |
| **Eliminar código FlyonUI residual** | NO | 1h |
| **Migrar errors/ a @vite()** | ✅ RESUELTO 2026-03-12 | ~~0.5h~~ |
| **Categorías de productos** (SyntiStudio/Cat) | NO | 6h |
| **Sitemap XML dinámico** | NO | 3h |
| **Footer "Powered by SyntiWeb" en footer.blade.php** | ✅ RESUELTO 2026-03-12 — white_label conditional + `footer-mkt.blade.php` compartido aplicado en 7 páginas | ~~0.5h~~ |
| **Pagos online** (Stripe/PayPal/local) | NO | 30h+ |
| **Verificación DNS custom_domain** | NO | 5h |

---

## 10. VEREDICTO FINAL

### Estado real del proyecto: **BUENO** (con 3 fixes de seguridad bloqueantes)

La arquitectura es sólida. Los modelos, controladores y servicios están bien escritos — `declare(strict_types=1)` en el 100% de archivos PHP, eager loading correcto, tenant isolation en todas las queries de negocio, conversión WebP implementada, Schema.org automático, QR tracking funcional, sistema de moneda con fallback.

El problema no es la calidad del código existente. El problema son **3 agujeros de seguridad** que impiden un lanzamiento responsable.

### ¿Puede lanzar beta hoy?: **CON ESTOS FIXES**

### Los 3 blockers más urgentes

| # | Blocker | Fix | Tiempo |
|---|---------|-----|:------:|
| **1** | API REST sin autenticación — permite CRUD no autorizado de datos de cualquier tenant | Agregar `middleware(['auth:sanctum'])` al grupo de rutas en `api.php`, o si no se usa externamente, eliminar las rutas API y dejar solo las rutas web | **1h** |
| **2** | PIN verify sin rate limiting — brute-force trivial | Agregar `->middleware('throttle:5,15')` a la ruta verify-pin + respuesta genérica que no revele si el tenant existe | **30min** |
| **3** | Favicon kit faltante en los 3 layouts requeridos | Copiar el bloque favicon HTML a `base.blade.php`, `dashboard/index.blade.php`, y `layouts/app.blade.php` | **15min** |

**Post-fix inmediato:** Eliminar `FlyonUIThemeService.php`, `config/flyonui-themes.php`, y archivos en `_archive/` para limpiar deuda técnica.

### Estimación para MVP deployable

| Concepto | Horas |
|----------|:-----:|
| Fixes de seguridad (#1 + #2 + #3) | 2h |
| Limpieza código muerto FlyonUI + _archive | 1h |
| ~~Migrar error pages a @vite~~ ✅ 404/500/tenant-not-found rediseñados | ~~0.5h~~ |
| ~~Footer "Powered by" + estandarización footers marketing~~ ✅ | ~~0.5h~~ |
| Factory + fix tests rotos | 2h |
| Smoke testing manual completo | 3h |
| **TOTAL para beta deployable** | **~8h** |

---

*Fin de auditoría. Sin suavizar. Sin halagos donde no corresponden. Los 75 archivos PHP y 116 blades fueron analizados.*
