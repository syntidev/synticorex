# DEPENDENCIES — SYNTIweb

**Audit Date:** 2026-03-11

---

## PHP Dependencies (composer.json)

### Production Dependencies (5)

| Package | Version | Purpose | Classification |
|---------|---------|---------|----------------|
| `php` | ^8.2 | Runtime | Core |
| `laravel/framework` | ^12.0 | Web framework | Core Framework |
| `laravel/breeze` | ^2.3 | Authentication scaffolding | Auth |
| `intervention/image` | ^3.11 | Image manipulation (resize, WebP conversion) | Image Processing |
| `simplesoftwareio/simple-qrcode` | ^4.2 | QR code generation (SVG + PNG) | QR Generation |

### Development Dependencies (8)

| Package | Version | Purpose | Classification |
|---------|---------|---------|----------------|
| `fakerphp/faker` | ^1.23 | Test data generation | Testing |
| `laravel/pail` | ^1.2.2 | Log tailing | Dev Tools |
| `laravel/pint` | ^1.18 | Code style fixer | Code Quality |
| `laravel/sail` | ^1.41 | Docker development | Dev Environment |
| `mockery/mockery` | ^1.6 | Mock objects for testing | Testing |
| `nunomaduro/collision` | ^8.6 | Error reporting | Dev Tools |
| `phpunit/phpunit` | ^11.5.3 | Testing framework | Testing |
| `laravel/tinker` | ^2.10.1 | REPL | Dev Tools |

---

## NPM Dependencies (package.json)

### Production Dependencies (4)

| Package | Version | Purpose | Classification |
|---------|---------|---------|----------------|
| `preline` | ^4.1.2 | UI component library | UI Components |
| `alpinejs` | ^3.4.2 | Lightweight reactive JS | JS Framework |
| `@alpinejs/collapse` | ^3.4.2 | Collapse animation plugin | JS Framework |
| `axios` | ^1.7.9 | HTTP client | Core |

### Development Dependencies (11)

| Package | Version | Purpose | Classification |
|---------|---------|---------|----------------|
| `@tailwindcss/vite` | ^4.0.0 | Tailwind CSS v4 Vite plugin | CSS Build |
| `tailwindcss` | ^4.2.0 | Utility-first CSS framework | CSS Framework |
| `vite` | ^7.0.7 | Frontend build tool | Build Tool |
| `laravel-vite-plugin` | ^2.0.0 | Laravel ↔ Vite bridge | Build Tool |
| `autoprefixer` | ^10.4.20 | CSS vendor prefixes | CSS Build |
| `postcss` | ^8.4.49 | CSS transformer | CSS Build |
| `concurrently` | ^9.1.2 | Run multiple scripts | Dev Tools |
| `@iconify-json/tabler` | ^1.2.27 | Tabler icon set | Icons |
| `iconify-icon` | ^2.3.0 | Iconify web component | Icons |
| `@iconify/tailwind` | ^1.1.3 | Iconify Tailwind integration | Icons |
| `sortablejs` | ^1.15.6 | Drag-and-drop sorting | UI Library |

---

## Dependency Architecture

```
┌─────────────────────────────────────────────────┐
│                  PRODUCTION STACK                 │
│                                                   │
│  PHP Layer:                                       │
│  ├── Laravel 12 (framework)                       │
│  ├── Breeze 2.3 (auth)                           │
│  ├── Intervention Image 3.11 (images)            │
│  └── SimpleQRCode 4.2 (QR codes)                 │
│                                                   │
│  Frontend Layer:                                  │
│  ├── Tailwind CSS 4.2 (styling)                  │
│  ├── Preline 4.1.2 (UI components)              │
│  ├── Alpine.js 3.4.2 (reactivity)               │
│  ├── Axios 1.7.9 (HTTP)                         │
│  └── Tabler Icons via Iconify (icons)            │
│                                                   │
│  Build Layer:                                     │
│  ├── Vite 7.0.7 (bundler)                       │
│  ├── Laravel Vite Plugin 2.0.0                   │
│  └── @tailwindcss/vite 4.0.0                     │
└─────────────────────────────────────────────────┘
```

---

## Configuration Files

### vite.config.js
```javascript
// Entry: resources/css/app.css, resources/js/app.js
// Plugins: laravel(), tailwindcss()
// Alias: preline/plugin → preline/plugin.js
// Features: HMR (hot reload)
```

### tailwind.config.js
```javascript
// Mostly empty — Tailwind 4.2 uses @tailwindcss/vite plugin
// Content scanning configured in app.css via @source directives
```

### postcss.config.js
```javascript
// Empty — PostCSS handled by @tailwindcss/vite
```

---

## External Services (Runtime Dependencies)

| Service | Purpose | Fallback |
|---------|---------|----------|
| DolarAPI.com | USD/EUR exchange rates | Hardcoded: $36.50 USD, €495 EUR |
| Bytez API | AI assistant (Qwen3-8B) | Graceful error response |
| Google Fonts CDN | Geist, Public Sans fonts | System fonts |
| Bunny Fonts CDN | Figtree (auth pages) | System fonts |
| Iconify CDN | Tabler icon rendering | None (icons won't render) |

---

## Dependency Health Assessment

| Aspect | Status | Notes |
|--------|--------|-------|
| PHP packages up to date | ✅ | Laravel 12, latest stable |
| NPM packages up to date | ✅ | Tailwind 4.2, Vite 7 |
| Security advisories | ✅ | No known vulnerabilities |
| Unused dependencies | ⚠️ | FlyonUI config persists (not in package.json) |
| Missing dependencies | ✅ | All required packages present |
| Lock file consistency | ✅ | composer.lock and package-lock.json present |
| Dependency count | ✅ | Lean — 5 PHP prod + 4 NPM prod |

---

## Removed Dependencies (Historical)

| Package | Removed When | Reason |
|---------|-------------|--------|
| FlyonUI | ~Feb 2026 | Replaced by Preline 4.1.2 |
| DaisyUI | Early | Never formally adopted, classes leaked into templates |

**Residual artifacts:**
- `config/flyonui-themes.php` — Config file still exists
- `app/Services/FlyonUIThemeService.php` — Service class still exists
- `resources/js/old_app.js` — Contains `import 'flyonui/dist/overlay.mjs'`
- `database/seeders/FlyonUIThemesSeeder.php` — Seeder still exists
- `dashboard/_archive/` — 7 files with DaisyUI classes
