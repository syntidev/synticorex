# Dashboard Architecture — `resources/views/dashboard/`

> **Última actualización:** Refactoring modular desde el monolítico `index.blade.php` (251 KB, 4316 líneas).
> CSS bundle activo: `public/build/assets/app-Cexm8fS8.css` (320.95 KB)

---

## Estructura de archivos

```
resources/views/dashboard/
├── index.blade.php                        21.6 KB  ← Shell: HTML wrapper + @includes
│
├── components/
│   ├── info-section.blade.php             22.6 KB  ← Tab 1: Info
│   ├── products-section.blade.php          6.6 KB  ← Tab 2: Productos (grid)
│   ├── services-section.blade.php          7.4 KB  ← Tab 3: Servicios (grid)
│   ├── design-section.blade.php           25.5 KB  ← Tab 4: Diseño
│   ├── analytics-section.blade.php         8.6 KB  ← Tab 5: Analytics
│   ├── branches-section.blade.php          5.5 KB  ← Tab 6: Sucursales (Plan 3)
│   └── config-section.blade.php           31.7 KB  ← Tab 7: Config
│
├── modals/
│   ├── product-modal.blade.php             6.5 KB  ← CRUD overlay Producto
│   ├── service-modal.blade.php            10.7 KB  ← CRUD overlay Servicio
│   └── shared-modals.blade.php             8.6 KB  ← Sucursal + Testimonial + FAQ + Plan Limit
│
└── scripts/
    ├── tab-product-scripts.blade.php      26.2 KB  ← Tab nav + Info/Hours + Products JS
    ├── service-scripts.blade.php          20.0 KB  ← Services JS + Icon Picker + Uploads + QR
    ├── design-config-scripts.blade.php    31.0 KB  ← Design + Analytics + Config + Social + Branches JS
    └── sortable-scripts.blade.php         13.8 KB  ← Toast global + SortableJS init + Testimonials + FAQ
```

---

## `index.blade.php` — Shell principal

Contiene exclusivamente:
- `<!DOCTYPE html>` con `data-theme="{{ $theme }}"` y CSS vars inline
- `<head>`: Chart.js CDN, `@vite(['resources/css/app.css','resources/js/app.js'])`
- Navbar superior (logo, PIN trigger, plan badge)
- Sidebar con 7 tabs de navegación
- Plan expiry alerts
- `<main id="main-content">`
- Todos los `@include()` de componentes, modales y scripts
- `</body></html>`

### Orden de @includes en index.blade.php

```blade
{{-- ── HTML Sections ── --}}
@include('dashboard.components.info-section')

@include('dashboard.components.products-section')
@include('dashboard.modals.product-modal')

@include('dashboard.components.services-section')
@include('dashboard.modals.service-modal')

@include('dashboard.modals.shared-modals')

@include('dashboard.components.design-section')

@include('dashboard.components.analytics-section')

@include('dashboard.components.branches-section')

@include('dashboard.components.config-section')

{{-- ── JavaScript ── --}}
@include('dashboard.scripts.tab-product-scripts')
@include('dashboard.scripts.service-scripts')
@include('dashboard.scripts.design-config-scripts')

@include('dashboard.scripts.sortable-scripts')
```

---

## Variables Blade disponibles en todos los includes

Inyectadas por `DashboardController` y accesibles en todos los @include:

| Variable | Tipo | Descripción |
|---|---|---|
| `$tenant` | `Tenant` | Tenant actual (con relaciones eager-loaded) |
| `$customization` | `TenantCustomization` | Config de diseño y secciones |
| `$plan` | `Plan` | Plan activo del tenant |
| `$products` | `Collection<Product>` | Con `->images` cargadas |
| `$services` | `Collection<Service>` | Con icono/imagen |
| `$theme` | `string` | Nombre del tema FlyonUI activo |
| `$qrSvg` | `string|null` | SVG del QR code (sin sanitizar) |
| `$businessHours` | `array` | Horarios por día |
| `$branchesEnabled` | `bool` | Sección sucursales visible |
| `$analyticsData` | `array` | Datos para Chart.js |
| `$dollarRate` | `DollarRate|null` | Tasa del día |

---

## Sistema de iconos

**Sintaxis correcta:** `class="iconify tabler--NOMBRE"` (dos clases separadas)  
**Nunca usar:** `class="icon-[tabler--NOMBRE]"` (arbitrary syntax no soportada por `addIconSelectors` v1.x con Tailwind v4)

Los iconos nuevos que no estén en el CSS deben agregarse al safelist en `tailwind.config.js`:
```js
// tailwind.config.js → safelist array
'tabler--NOMBRE-DEL-ICONO',
```

Iconos ya en safelist: `edit`, `trash`, `plus`, `eye`, `eye-off`, `x`, `check`, `pause`, `device-floppy`, `cloud-upload`, `upload`, `photo-scan`, `photo-up`, `download`, `file-type-svg`, `layout-sidebar-right-collapse`, `external-link`, `chart-bar`, `alert-triangle`, `circle-x`, `circle-filled`, `list-check`, `refresh`, `lock`, `help-circle`, `cash`, `credit-card`, `color-picker`, `message-star`, `sparkles`, `social`

---

## Sistema de tabs (JavaScript)

La navegación entre tabs se controla con `switchTab(tabId)` en `tab-product-scripts.blade.php`.

IDs de tab:
| Tab | `div#id` | Slug sidebar |
|---|---|---|
| Info | `tab-info` | info |
| Productos | `tab-productos` | productos |
| Servicios | `tab-servicios` | servicios |
| Diseño | `tab-diseno` | diseno |
| Analytics | `tab-analytics` | analytics |
| Sucursales | `tab-sucursales` | sucursales |
| Config | `tab-config` | config |

---

## Modales CRUD

Todos usan clases `.crud-overlay` + `.crud-dialog` (custom, no FlyonUI modal).

| Modal | ID overlay | Abrir | Cerrar |
|---|---|---|---|
| Producto | `product-modal` | `openProductModal(id?)` | `closeProductModal()` |
| Servicio | `service-modal` | `openServiceModal(id?)` | `closeServiceModal()` |
| Sucursal | `branch-modal` | `openBranchModal()` | `closeBranchModal()` |
| Testimonial | `testimonial-modal` | `addTestimonial()` / `editTestimonial(i)` | `closeTestimonialModal()` |
| FAQ | `faq-modal` | `addFaq()` / `editFaq(i,q,a)` | `closeFaqModal()` |
| Plan Limit | `plan-limit-modal` | `openLimitModal(type)` | `closeLimitModal()` |

---

## Planes y secciones

```php
// TenantCustomization::canAccessSection($section)
Plan 1 OPORTUNIDAD  → hero, products, services, contact, payment_methods, cta, footer
Plan 2 CRECIMIENTO  → + about, testimonials
Plan 3 VISIÓN       → + faq, branches
```

`branches-section.blade.php` incluye guard `@if($plan->id >= 3)`.

---

## Build

```bash
npm run build
# Output: public/build/assets/app-[hash].css
# Manifest: public/build/manifest.json
```

**Tailwind v4 + Vite** — NO requiere `tailwind.config.js` para el CSS scan.
El safelist de iconos SÍ se lee desde `tailwind.config.js` vía plugin `addIconSelectors`.
