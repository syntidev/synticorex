# FRONTEND — SYNTIweb

**Audit Date:** 2026-03-11  
**Total Blade Files:** ~140  
**CSS Framework:** Tailwind CSS 4.2 + 44 Preline themes  
**JS Framework:** Alpine.js 3.4.2 + Preline JS  
**Build Tool:** Vite 7.0.7

---

## Build Pipeline

```
resources/css/app.css ─────┐
                           ├──► Vite 7 ──► public/build/
resources/js/app.js ───────┘       │
                                   ├── @tailwindcss/vite (TW 4.2)
                                   └── laravel-vite-plugin (HMR)
```

**Entry Points:**
- `resources/css/app.css` — Tailwind + 44 Preline theme imports + brand vars
- `resources/js/app.js` — Alpine.js + Preline JS + Axios

---

## CSS Architecture

### app.css Imports
```
@import "tailwindcss"                      ← Tailwind 4.0 base
@import "preline/variants.css"             ← Preline variant utilities
@source "../views/**/*.blade.php"          ← CSS class scanning
@source "../js/**/*.js"
@source "preline/dist/*.js"
@import "preline/css/themes/theme.css"     ← Default theme
@import "preline/css/themes/*.css"         ← 44 business-specific themes
@import "./syntiweb-brand.css"             ← SyntiWeb brand variables
```

### Theme Categories (44 Preline themes)
| Category | Themes | Business Target |
|----------|--------|-----------------|
| General | theme, harvest, retro, ocean, bubblegum, autumn, moon, cashmere, olive | Any |
| Food | sabor-tradicional, fuego-urbano, parrilla-moderna, casa-latina | Restaurants |
| Sweet | rosa-vainilla, pistacho-suave, cielo-dulce, chocolate-caramelo | Bakeries/Cafes |
| Health | azul-confianza, verde-calma | Medical/Wellness |
| Authority | azul-profesional, ejecutivo-oscuro, prestigio-clasico | Consulting/Legal |
| Trade | industrial-pro, negro-impacto, metal-urbano | Construction/Auto |
| Beauty | nude-elegante, rosa-studio, barber-clasico | Salons/Barbers |
| Fitness | fuerza-roja, verde-potencia, azul-electrico | Gyms/Sports |
| Education | azul-academico, verde-progreso, claro-simple | Schools/Courses |

### Brand CSS Variables (syntiweb-brand.css)
```css
--sw-blue:       #4A80E4   /* Primary accent */
--sw-navy:       #1a1a1a   /* Dark text */
--sw-bg:         #F8FAFF   /* Light background */
--sw-surface:    #FFFFFF   /* Card surfaces */
--sw-border:     #E2E8F4   /* Borders */
--sw-text-muted: #64748b   /* Muted text */
--sw-font:       'Geist', ui-sans-serif, system-ui
```

---

## JavaScript Architecture

### app.js Stack
```
Alpine.js 3.4.2          ← Reactive UI (x-data, x-show, x-on)
@alpinejs/collapse       ← Accordion/collapse animations
Preline JS               ← hs-overlay, hs-accordion, hs-dropdown, hs-tab
Axios                    ← HTTP client (CSRF-aware)
Iconify (CDN)            ← Tabler icons loaded via script tag
```

### Preline Components Used
| Component | Directive | Usage |
|-----------|-----------|-------|
| Modal/Overlay | `hs-overlay` + `data-hs-overlay` | Dashboard modals, info panels |
| Accordion | `hs-accordion` | FAQ, dashboard sections |
| Dropdown | `hs-dropdown` | Navigation menus |
| Tabs | `data-hs-tab` | Dashboard tab system |
| Collapse | `hs-collapse` | Mobile nav |

---

## Layout Hierarchy

```
┌─────────────────────────────────────────┐
│  INTERNAL WORLD                          │
│                                          │
│  layouts/app.blade.php ──► dashboard     │
│  layouts/guest.blade.php ──► auth        │
│  layouts/admin.blade.php ──► admin       │
│  layouts/navigation.blade.php ──► nav    │
│                                          │
│  marketing/*.blade.php (standalone)      │
│  onboarding/*.blade.php (standalone)     │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  TENANT WORLD                            │
│                                          │
│  landing/base.blade.php ─────────────── │
│    ├── landing/templates/studio.blade    │
│    ├── landing/templates/food.blade      │
│    └── landing/templates/catalog.blade   │
│         │                                │
│         └── landing/sections/*.blade     │
│              (23 reusable sections)      │
│                                          │
│  landing/schemas/*.blade (7 Schema.org)  │
└─────────────────────────────────────────┘
```

---

## View Directory Structure

### resources/views/landing/ (33 files)

**base.blade.php** — Master layout (100 lines)
- Dynamic `data-theme` attribute per tenant
- SEO meta tags (title, description, OG)
- Schema.org structured data (auto-selected by blueprint)
- Custom palette CSS variables override
- Google Fonts (Geist, Public Sans)
- Grainy texture overlay
- @vite() for CSS/JS
- Floating panel + header message includes

**templates/** — 3 blueprint templates (+2 backups)
| File | Lines | Extends | Purpose |
|------|------:|---------|---------|
| studio.blade.php | ~200 | landing.base | Studio/professional template |
| food.blade.php | ~300 | landing.base | Restaurant/food template |
| catalog.blade.php | ~200 | landing.base | Catalog/retail template |

**sections/** — 23 reusable section components
| Section | Lines | Plan | Purpose |
|---------|------:|------|---------|
| hero-fullscreen-v2.blade.php | ~150 | 1+ | Full-viewport hero |
| hero-split.blade.php | ~120 | 1+ | Split layout hero |
| hero-gradient.blade.php | ~130 | 1+ | Gradient hero |
| header.blade.php | ~80 | 1+ | Sticky header |
| header-top.blade.php | ~30 | 2+ | Top banner bar |
| navbar-v2.blade.php | ~100 | 1+ | Navigation bar |
| products.blade.php | ~200 | 1+ | Product grid |
| product-card.blade.php | ~80 | 1+ | Single product card |
| services.blade.php | ~150 | 1+ | Services grid |
| services-spotlight.blade.php | ~120 | 1+ | Services spotlight variant |
| about.blade.php | ~150 | 2+ | About section |
| testimonials.blade.php | ~100 | 2+ | Testimonials carousel |
| faq.blade.php | ~80 | 3+ | FAQ accordion |
| branches.blade.php | ~60 | 3+ | Branch locations |
| contact.blade.php | ~100 | 1+ | Contact info |
| payment_methods.blade.php | ~80 | 1+ | Payment methods |
| cta.blade.php | ~80 | 1+ | Call-to-action |
| footer.blade.php | ~100 | 1+ | Standard footer |
| footer-v2.blade.php | ~120 | 1+ | Footer variant |
| floating-panel.blade.php | ~760 | 1+ | Floating info panel |
| delivery-info.blade.php | ~40 | Food | Delivery terms |
| _empty-guide.blade.php | ~30 | — | Empty state guide |

**schemas/** — 7 Schema.org structured data
| Schema | Blueprint |
|--------|-----------|
| restaurant.blade.php | Food |
| store.blade.php | Retail/Cat |
| health.blade.php | Health/Wellness |
| professional.blade.php | Professional |
| education.blade.php | Education |
| transport.blade.php | Transport |
| local-business.blade.php | Default fallback |

---

### resources/views/dashboard/ (39 files)

**index.blade.php** — ~2,400 lines (monolithic)
- Self-contained HTML (no layout extension)
- Fixed header with logo, clock, dollar rate
- Sidebar with 7-tab navigation (hs-overlay)
- Plan expiry warnings
- Includes 11 component sections

**components/** — 11 tab content sections
| Component | Lines | Tab | Purpose |
|-----------|------:|-----|---------|
| info-section.blade.php | ~913 | Tu Información | Business info, hours, social |
| products-section.blade.php | ~400 | Qué Vendes | Product CRUD |
| services-section.blade.php | ~350 | Qué Vendes | Service CRUD |
| menu-section.blade.php | ~300 | Qué Vendes | Food menu (SyntiFood) |
| design-section.blade.php | ~400 | Cómo Se Ve | Theme, palette, layout |
| message-section.blade.php | ~200 | Tu Mensaje | Header message, CTA |
| sales-section.blade.php | ~250 | Pulso del Negocio | Analytics dashboard |
| visual-section.blade.php | ~200 | Visual | Section reordering, visibility |
| config-section.blade.php | ~300 | Configuración | PIN, currency, branches |
| comandas-section.blade.php | ~150 | — | Food order management |
| orders-section.blade.php | ~150 | — | Cat order management |

**modals/** — 3 modal templates
| Modal | Purpose |
|-------|---------|
| product-modal.blade.php | Create/edit product form |
| service-modal.blade.php | Create/edit service form |
| shared-modals.blade.php | Confirmation dialogs |

**scripts/** — 4 inline JavaScript sections
| Script | Lines | Purpose |
|--------|------:|---------|
| design-config-scripts.blade.php | ~745 | Theme/palette/section config JS |
| tab-product-scripts.blade.php | ~500 | Product CRUD AJAX |
| service-scripts.blade.php | ~400 | Service CRUD AJAX |
| sortable-scripts.blade.php | ~300 | Drag-and-drop section ordering |

**partials/** — 2 utility includes
| Partial | Purpose |
|---------|---------|
| synti-assistant.blade.php | AI chat widget |
| synti-header-btn.blade.php | Header action button |

**_archive/** — 7 deprecated files (DaisyUI classes — dead code)

---

### resources/views/marketing/ (16 files)

| File | Purpose |
|------|---------|
| index.blade.php | Main marketing page |
| planes.blade.php | Pricing comparison |
| studio.blade.php | Studio product page |
| food.blade.php | Food product page |
| cat.blade.php | Catalog product page |
| sections/hero.blade.php | Marketing hero |
| sections/problema.blade.php | Problem statement |
| sections/solucion.blade.php | Solution presentation |
| sections/segmentos.blade.php | Business segments |
| sections/estadisticas.blade.php | Stats/numbers |
| sections/configuracion.blade.php | Config showcase |
| sections/dashboard.blade.php | Dashboard preview |
| sections/mundo-tenant.blade.php | Tenant world |
| sections/conversion.blade.php | Conversion section |
| sections/planes.blade.php | Plans comparison |
| sections/valor.blade.php | Value proposition |
| sections/cta-final.blade.php | Final CTA |

---

### resources/views/components/ (20 files)

**Base Components (14):** application-logo, auth-session-status, danger-button, dropdown, dropdown-link, feature-gate, input-error, input-label, modal, nav-link, primary-button, responsive-nav-link, secondary-button, text-input

**UI Components (4):** ui/accordion, ui/decorative-background, ui/dropdown, ui/modal

**Dashboard Components (1):** dashboard/empty-state

---

## Fonts

| Font | Source | Usage |
|------|--------|-------|
| Geist | Google Fonts CDN | SyntiWeb brand, landing pages |
| Public Sans | Google Fonts CDN | Landing page body text |
| Figtree | Bunny Fonts CDN | Guest/auth pages (Breeze default) |

---

## Frontend Issues

| Issue | Severity | Location |
|-------|----------|----------|
| Error pages use CDN Tailwind | LOW | errors/404.blade.php, errors/500.blade.php |
| Dashboard is 2,400 lines (monolithic) | MEDIUM | dashboard/index.blade.php |
| _archive/ contains DaisyUI code | LOW | dashboard/_archive/ (7 files) |
| food.blade.php.bak files | LOW | landing/templates/ (2 backup files) |
| 4 console.log statements in JS | LOW | Multiple dashboard scripts |
| Iconify loaded via CDN | INFO | Could be bundled for offline |
| Google Fonts loaded via CDN | INFO | No offline fallback |
