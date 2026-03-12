# PROJECT OVERVIEW — SYNTIweb

**Audit Date:** 2026-03-11  
**Auditor:** AI Architecture Auditor  
**Repository:** c:\laragon\www\synticorex

---

## Project Summary

SYNTIweb is a **multitenant SaaS platform** targeting small businesses in Venezuela. It generates dynamic landing pages for tenants, each accessible via subdomain or custom domain. The platform targets a dollarized market using REF (reference currency) with BCV exchange rate integration.

**Business Model:** Annual subscriptions across 3 tiers:
- Plan 1 OPORTUNIDAD ($99/yr) — 6 products, 3 services
- Plan 2 CRECIMIENTO ($149/yr) — 12 products, 6 services
- Plan 3 VISIÓN ($199/yr) — 18 products, 9 services, gallery slider

**Product Verticals (Blueprints):**
- **SyntiStudio** — Professional services, portfolios
- **SyntiFood** — Restaurants, food delivery (menu + orders via WhatsApp)
- **SyntiCat** — Product catalogs, retail

---

## Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Language | PHP | ^8.2 (targeting 8.3) |
| Framework | Laravel | ^12.0 (12.51 installed) |
| Database | MySQL (dev: SQLite) | — |
| CSS Framework | Tailwind CSS | ^4.2.0 |
| UI Components | Preline | ^4.1.2 |
| JS Framework | Alpine.js | ^3.4.2 |
| Build Tool | Vite | ^7.0.7 |
| Image Processing | Intervention Image | ^3.11 |
| QR Generation | simplesoftwareio/simple-qrcode | ^4.2 |
| Icons | @iconify-json/tabler | ^1.2.27 |
| Auth | Laravel Breeze | ^2.3 |
| Testing | PHPUnit | ^11.5.3 |

---

## File Composition

| Extension | Count | Description |
|-----------|------:|-------------|
| .php | 362 | Backend logic (incl. vendor in count; ~75 app/) |
| .blade.php | ~116 | Blade templates (active, excluding archives) |
| .js | 175 | JavaScript (incl. build artifacts) |
| .md | 153 | Documentation |
| .css | 41 | Stylesheets |
| .webp | 64 | Optimized images |
| .svg | 26 | Vector graphics/icons |
| .json | 29 | Configuration files |

**Key Metrics:**
- ~75 PHP files in `app/`
- ~116 active Blade templates
- 38 database migrations
- 9 seeders, 1 factory
- 13 service classes
- 8 artisan commands
- 2 custom middleware
- ~156 routes total
- 11 test files (mostly Breeze defaults)

---

## Entry Points

| Entry Point | File | Purpose |
|-------------|------|---------|
| Web | `public/index.php` | HTTP request entry |
| Artisan CLI | `artisan` | Console commands |
| Vite Dev | `vite.config.js` | Frontend build |
| Routes | `routes/web.php` | Primary routing |
| API | `routes/api.php` | REST API endpoints |
| Scheduler | `routes/console.php` | Cron jobs |

---

## Configuration Files

| File | Purpose |
|------|---------|
| `composer.json` | PHP dependencies |
| `package.json` | NPM dependencies |
| `vite.config.js` | Frontend build pipeline |
| `tailwind.config.js` | Tailwind CSS config (legacy v3, overridden by Vite plugin) |
| `postcss.config.js` | PostCSS (empty — handled by Vite) |
| `.env` / `.env.example` | Environment variables |
| `config/tenancy.php` | Multitenancy domains/subdomains |
| `config/ai.php` | AI assistant (Bytez/Qwen3-8B) |
| `config/blueprints.php` | Business type definitions |
| `config/preline-themes.php` | 35 Preline CSS themes |
| `config/flyonui-themes.php` | **OBSOLETE** — FlyonUI removed but config persists |
| `phpunit.xml` | Test configuration |

---

## Folder Structure

```
synticorex/
├── app/
│   ├── Console/Commands/       # 8 Artisan commands
│   ├── Http/
│   │   ├── Controllers/        # 15 root + 9 auth + 4 food = 28 controllers
│   │   ├── Middleware/          # 2 middleware (IdentifyTenant, CSP)
│   │   └── Requests/           # 1 form request (ProfileUpdateRequest)
│   ├── Models/                 # 14 Eloquent models
│   ├── Providers/              # 2 providers (App, View)
│   ├── Services/               # 13 service classes
│   │   └── AI/                 # AI provider(s)
│   ├── Traits/                 # 1 trait (HasBlueprint)
│   └── View/Components/        # View component classes
├── bootstrap/                  # Laravel bootstrap
├── config/                     # 15 configuration files
├── database/
│   ├── factories/              # 1 factory (User)
│   ├── migrations/             # 38 migrations
│   ├── seeders/                # 9 seeders
│   └── scripts/                # DB utility scripts
├── docs/                       # VitePress documentation site
├── resources/
│   ├── css/                    # 2 CSS files (app.css, brand.css)
│   ├── js/                     # 3 JS files (app.js, bootstrap.js, old_app.js)
│   └── views/
│       ├── auth/               # 6 auth views (Breeze)
│       ├── components/         # 20+ reusable UI components
│       ├── dashboard/          # Dashboard: index + 11 components + 3 modals + 4 scripts + 2 partials
│       ├── errors/             # 3 error pages
│       ├── landing/            # Landing: base + 3 templates + 23 sections + 7 schemas
│       ├── layouts/            # 4 layout files
│       ├── marketing/          # 5 main + 11 section views
│       ├── onboarding/         # 5 wizard views
│       ├── profile/            # 2 profile views
│       └── tenants/            # 2 legacy tenant views
├── routes/                     # web.php, api.php, auth.php, console.php
├── scripts/                    # Validation scripts
├── tests/                      # 11 test files
├── public/                     # Static assets, favicons, brand SVGs
└── docs/                       # VitePress documentation
```
