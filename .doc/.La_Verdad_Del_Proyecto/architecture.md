# ARCHITECTURE — SYNTIweb

**Audit Date:** 2026-03-11

---

## Architecture Pattern

**MVC Monolith with Multi-Tenant SaaS Pattern**

SYNTIweb is a Laravel 12 monolith using the MVC pattern with an additional Service layer. It implements multitenancy at the application level (not database level) — all tenants share the same database, isolated by `tenant_id` foreign keys.

```
┌───────────────────────────────────────────────────────┐
│                    ENTRY POINTS                        │
│  public/index.php → Laravel HTTP Kernel                │
│  artisan → Console Kernel                              │
│  routes/console.php → Scheduler                        │
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                    ROUTING LAYER                       │
│  web.php (100+ routes)  │  api.php (30+ routes)       │
│  auth.php (15 routes)   │  console.php (2 scheduled)  │
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                      │
│  IdentifyTenant → Resolves tenant from request         │
│  ContentSecurityPolicy → Security headers              │
│  auth (Breeze) → Authentication                        │
│  throttle → Rate limiting                              │
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                  CONTROLLER LAYER                      │
│  28 controllers (15 main + 9 auth + 4 food)           │
│  DashboardController (God Object — 1538 lines)        │
│  TenantRendererController (landing render — 695 lines)│
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                   SERVICE LAYER                        │
│  15 services handling business logic                   │
│  DollarRateService, ImageUploadService, QRService      │
│  MenuService, OrderService, ComandaService, etc.       │
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                    MODEL LAYER                         │
│  14 Eloquent models                                    │
│  Tenant (central hub), TenantCustomization (34 cols)  │
│  Product, Service, Plan, ColorPalette, etc.            │
└──────────┬────────────────────────────────────────────┘
           │
┌──────────▼────────────────────────────────────────────┐
│                   DATABASE LAYER                       │
│  MySQL (dev: SQLite) — 17+ tables                      │
│  All queries filtered by tenant_id                     │
│  JSON columns for flexible configs                     │
└───────────────────────────────────────────────────────┘
```

---

## Multitenancy Strategy

### Tenant Resolution (IdentifyTenant middleware)

```
Request arrives
    ↓
Step 1: Check subdomain      → pepe.tu.menu → tenant "pepe"
Step 2: Check path segment    → synticorex.test/pepe → tenant "pepe"
Step 3: Check custom_domain   → www.pepe.com → exact host match
    ↓
Tenant found?
    YES → Inject into app(), request, view → continue
    NO  → Return 404
```

### Isolation Model

| Aspect | Strategy |
|--------|----------|
| Database | Shared DB, all tables have `tenant_id` FK |
| Storage | `storage/tenants/{tenant_id}/` per tenant |
| Domains | Subdomain OR custom domain per tenant |
| Theming | Per-tenant theme via `data-theme` attribute |
| Configuration | JSON columns in `tenant_customization` |
| Plan Enforcement | Feature gating via `canAccessSection()` |

### Data Flow: Public Landing Page

```
Browser → GET /{subdomain}
  → IdentifyTenant middleware
  → TenantRendererController@show
    → Load Tenant with eager loading:
        - plan, customization, products, services, branches
    → DollarRateService::getCurrentRate()
    → QRService::generateQR()
    → BusinessHoursService::isOpen()
    → Select template by blueprint (studio/food/cat)
    → Render Blade: landing/templates/{blueprint}.blade.php
      → extends landing/base.blade.php
      → @include landing/sections/*.blade.php (ordered by sections_order)
    → Return HTML with Schema.org
```

### Data Flow: Dashboard CRUD

```
Browser → POST /tenant/{id}/products (AJAX)
  → auth middleware (session-based)
  → DashboardController@createProduct
    → Validate request
    → Check plan limits (products_limit)
    → Create Product with tenant_id
    → ImageUploadService::process() (if image)
    → Return JSON response
```

---

## Blueprint System

SYNTIweb supports 3 business verticals ("blueprints"), each with specialized behavior:

| Blueprint | Template | Dashboard Variant | Special Features |
|-----------|----------|-------------------|------------------|
| **studio** | `studio.blade.php` | Standard CRUD | Hero variants, services spotlight |
| **food** | `food.blade.php` | Menu/Categories/Items | JSON-based menu, WhatsApp orders, Comanda PDF |
| **cat** | `catalog.blade.php` | Standard CRUD | Product catalog, checkout flow |

### Plan × Blueprint Matrix

```
studio-oportunidad   $99/yr   6 products, 3 services
studio-crecimiento   $149/yr  50 products, 6 services, testimonials, about
studio-vision        $199/yr  unlimited, FAQ, branches, slider gallery

food-basico          $9/mo    50 items, basic
food-semestral       $39/6mo  100 items, BCV rate
food-anual           $69/yr   150 items, full features

cat-basico           $9/mo    20 products
cat-semestral        $39/6mo  100 products
cat-anual            $69/yr   unlimited products
```

---

## Frontend Architecture

```
┌──────────────────────────────────────────────┐
│              BUILD PIPELINE                   │
│  Vite 7 → @tailwindcss/vite → Laravel Plugin│
│  Entry: resources/css/app.css                │
│         resources/js/app.js                  │
│  Output: public/build/ (manifest.json)       │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│              CSS FRAMEWORK                    │
│  Tailwind CSS 4.2 (utility-first)            │
│  44 Preline CSS themes (imported in app.css) │
│  Custom brand vars (syntiweb-brand.css)      │
│  Per-tenant custom palette override          │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│              JS FRAMEWORK                     │
│  Alpine.js 3.4.2 (reactive, collapse plugin) │
│  Preline JS (hs-overlay, hs-accordion, etc.) │
│  Axios (HTTP client)                         │
│  Iconify (Tabler icons, CDN)                 │
│  NO React/Vue/Svelte — Blade + Alpine only   │
└──────────────────────────────────────────────┘
```

---

## Two Worlds Separation

```
╔═══════════════════════════════════════════════════════╗
║  INTERNAL WORLD (SyntiWeb)    ║  TENANT WORLD (Public) ║
║  ─────────────────────────    ║  ──────────────────── ║
║  layouts/app.blade.php        ║  landing/base.blade.php║
║  dashboard/**                 ║  landing/sections/**   ║
║  marketing/**                 ║  landing/templates/**  ║
║  onboarding/**                ║  landing/schemas/**    ║
║  auth/**                      ║                        ║
║                               ║                        ║
║  Branding: #4A80E4 SyntiWeb  ║  Branding: --brand-*   ║
║  Logo: syntiweb-logo-*.svg   ║  Logo: tenant's own    ║
╚═══════════════════════════════════════════════════════╝
```

---

## Security Architecture

| Layer | Implementation | Status |
|-------|---------------|--------|
| Authentication | Laravel Breeze (session) | ACTIVE |
| Tenant PIN | SHA-256 hash, verify endpoint | ACTIVE (no throttle) |
| CSP Headers | Custom middleware | ACTIVE |
| Rate Limiting | throttle middleware (API) | PARTIAL |
| Input Validation | Laravel Request validation | ACTIVE |
| Image Upload | Type check, 2MB limit, WebP conversion | ACTIVE |
| IP Hashing | SHA-256 in analytics | ACTIVE |
| API Auth | **MISSING** — api.php has no auth:sanctum | CRITICAL GAP |
| CORS | Laravel CORS config | ACTIVE |
| XSS Prevention | Blade `{{ }}` escaping (99% of output) | ACTIVE |

---

## Deployment Model

| Aspect | Current State |
|--------|--------------|
| Server | No deployment config found |
| CI/CD | No pipeline configured |
| Environments | .env.example present |
| Build | `npm run build` (Vite) — local only |
| Database | SQLite (dev), MySQL (prod-ready) |
| Queue Driver | database |
| Cache Driver | database |
| Session Driver | database |
| Storage | Local filesystem |
| Monitoring | None configured |
| Logging | Laravel Log (daily files) |
