# PROJECT_MAP — Documentación de Componentes Clave

## 1. TenantRendererController

**Propósito:** Renderiza landing pages dinámicas para tenants por subdomain o custom domain con soporte para múltiples templates, temas y monedas.

### Métodos Principales:

- **`show($subdomain)`** — Renderiza landing page por subdomain. Valida tenant activo, carga datos relacionados (plan, productos, servicios, sucursales), genera QR de tracking, resuelve template y tema.
- **`showByDomain($domain)`** — Renderiza landing page por custom domain verificado. Flujo similar a `show()` pero busca por `custom_domain`.
- **`verifyPin($tenantId)`** — Valida PIN de acceso al floating panel (dashboard privado accesible desde landing).
- **`toggleStatus($tenantId)`** — Alterna estado de operación del tenant (abierto/cerrado).
- **`toggleWhatsapp($tenantId)`** — Activa/desactiva enlace de WhatsApp en landing.
- **`resolveTemplate($tenant)`** — Determina cuál template renderizar (studio por defecto, sinticat para ecommerce) basado en settings.
- **`calculateProductPrices($products, $dollarRate)`** — Calcula precios en Bs. para productos.
- **`buildMetaTags($tenant)`** — Genera meta tags SEO (title, description, OG, canonical).
- **`buildSchema($tenant)`** — Genera Schema.org JSON-LD según tipo de negocio (Restaurant, Store, HealthAndBeautyBusiness, etc).

### Rutas que lo Invocan:

```
GET    /{subdomain}                      → show()
GET    /custom-domain/{domain}           → showByDomain()
POST   /tenant/{tenantId}/verify-pin     → verifyPin()
POST   /tenant/{tenantId}/toggle-status  → toggleStatus()
PATCH  /tenant/{tenantId}/toggle-whatsapp → toggleWhatsapp()
```

### Variables que Envía a Vistas:

**Para landing (studio.blade.php o catalog.blade.php):**
- `tenant` — Objeto Tenant completo
- `plan` — Plan asociado (Oportunidad, Crecimiento, Visión)
- `products` — Colección de productos activos con precios en Bs.
- `services` — Colección de servicios activos
- `dollarRate` — Tasa de cambio USD→Bs actual
- `euroRate` — Tasa de cambio EUR→Bs actual
- `themeSlug` — Nombre del tema (default, ocean, midnight, custom)
- `meta` — Array con metadatos SEO
- `customization` — Objeto TenantCustomization (config visual, secciones, etc)
- `currencySettings` — Array con modos de moneda (show_conversion_button, mode, default_currency, symbols)
- `displayMode` — Modo actual de visualización de moneda
- `savedDisplayMode` — Modo guardado
- `showReference` — Boolean para mostrar REF
- `showBolivares` — Boolean para mostrar Bs.
- `showEuro` — Boolean para mostrar €
- `hidePrice` — Boolean para ocultar precios
- `trackingQRSmall` — QR SVG (150px) para PIN unlock
- `trackingShortlink` — Link acortado para tracking
- `showHoursIndicator` — Boolean para mostrar indicador horario
- `isOpen` — Estado actual (abierto/cerrado)
- `closedMessage` — Mensaje personalizado cuando está cerrado
- `blueprint` — Array con info de Blueprint (schema_type, max items, etc)
- `schema` — JSON-LD Schema.org generado

---

## 2. DashboardController

**Propósito:** Gestor de 6 tabs del dashboard: Info, Productos, Servicios, Diseño, Analytics, Config. CRUD para productos/servicios/sucursales, temas, paletas, testimonios, FAQ, métodos de pago, redes sociales, horas, PIN, y ordenamiento de secciones.

### Métodos Principales:

- **`index($tenantId)`** — Renderiza dashboard con 6 tabs. Carga tenant, plan, customization, productos, servicios, sucursales, tasas de cambio, testimonios, FAQs, paletas, horarios, redes sociales, y QR de tracking.
- **`updateInfo()`** — Actualiza datos de Info tab: nombre negocio, descripción, dirección, teléfono, email, whatsapp.
- **`updateHeaderTop()`** — Actualiza texto de Header Top (Plan 2+).
- **`updateCta()`** — Actualiza Llamado a Acción.
- **`createProduct()` / `updateProduct()` / `deleteProduct()`** — CRUD de productos con validación de límites por plan.
- **`createService()` / `updateService()` / `deleteService()`** — CRUD de servicios.
- **`toggleBranches()` / `saveBranch()` / `deleteBranch()`** — Gestión de sucursales (Plan 3).
- **`updateTheme()`** — Cambia tema actual.
- **`updatePalette()`** — Genera y guarda paleta de colores custom (Plan 3 solo).
- **`saveCustomPalette()`** — Guarda paleta personalizada en settings.
- **`updateCurrencyConfig()`** — Configura modos de moneda (reference_only, bolivares_only, both_toggle, euro_toggle, hidden).
- **`updateTestimonials()`** — Guarda array de testimonios con name, title, text, rating.
- **`updateFaq()`** — Guarda array de FAQs.
- **`updatePin()`** — Cambia PIN de acceso al floating panel.
- **`updatePaymentMethods()`** — Activa/desactiva métodos de pago por plan.
- **`updateSocialNetworks()`** — Guarda URLs de redes sociales.
- **`updateBusinessHours()`** — Configura horario de atención.
- **`saveSectionOrder()`** — Guarda orden y visibilidad de secciones dinámicas (drag-and-drop).
- **`toggleSection()`** — Alterna visibilidad de una sección individual.

### Rutas que lo Invocan:

```
GET    /tenant/{tenantId}/dashboard                    → index()
POST   /tenant/{tenantId}/update-info                  → updateInfo()
POST   /tenant/{tenantId}/update-header-top            → updateHeaderTop()
POST   /tenant/{tenantId}/update-cta                   → updateCta()
POST   /tenant/{tenantId}/products                     → createProduct()
PUT    /tenant/{tenantId}/products/{productId}         → updateProduct()
DELETE /tenant/{tenantId}/products/{productId}         → deleteProduct()
POST   /tenant/{tenantId}/services                     → createService()
PUT    /tenant/{tenantId}/services/{serviceId}         → updateService()
DELETE /tenant/{tenantId}/services/{serviceId}         → deleteService()
POST   /tenant/{tenantId}/branches/toggle              → toggleBranches()
POST   /tenant/{tenantId}/branches                     → saveBranch()
DELETE /tenant/{tenantId}/branches/{branchId}         → deleteBranch()
POST   /tenant/{tenantId}/update-theme                 → updateTheme()
POST   /tenant/{tenantId}/update-palette               → updatePalette()
POST   /tenant/{tenantId}/save-custom-palette          → saveCustomPalette()
POST   /tenant/{tenantId}/update-currency-config       → updateCurrencyConfig()
POST   /tenant/{tenantId}/update-testimonials          → updateTestimonials()
POST   /tenant/{tenantId}/update-faq                   → updateFaq()
POST   /tenant/{tenantId}/update-pin                   → updatePin()
POST   /tenant/{tenantId}/update-payment-methods       → updatePaymentMethods()
POST   /tenant/{tenantId}/update-social-networks       → updateSocialNetworks()
POST   /tenant/{tenantId}/update-business-hours        → updateBusinessHours()
POST   /tenant/{tenantId}/dashboard/save-section-order → saveSectionOrder()
POST   /tenant/{tenantId}/dashboard/toggle-section     → toggleSection()
```

### Variables que Envía a Vista:

**Para dashboard/index.blade.php:**
- `tenant` — Objeto Tenant con relaciones cargadas
- `plan` — Plan asociado
- `customization` — TenantCustomization
- `products` — Colección de productos
- `services` — Colección de servicios
- `branches` — Colección de sucursales
- `dollarRate` — Tasa USD→Bs
- `euroRate` — Tasa EUR→Bs
- `daysUntilExpiry` — Días hasta expiración del plan
- `isExpiringSoon` — Boolean si expira en <7 días
- `isFrozen` — Boolean si tenant congelado
- `graceRemainingDays` — Días de período de gracia restantes
- `palettes` — Colección ColorPalette (filtradas por plan)
- `blueprint` — Info de Blueprint
- `maxItems` — Máximo de items por blueprint
- `itemLabel` — Etiqueta para items (Productos, Servicios, etc)
- `itemSingular` — Singular de item
- `currentTheme` — Tema actual
- `customPalette` — Paleta custom si existe
- `hasCustomPalette` — Boolean
- `activeTheme` — Tema activo (custom o slug)
- `trackingQR` — QR SVG (300px) para dashboard
- `trackingShortlink` — Shortlink de tracking
- `savedTestimonials` — Array de testimonios guardados
- `savedFaq` — Array de FAQs guardadas
- `plan1NetworksList` — Array de redes sociales Plan 1
- `plan2NetworksList` — Array adicional Plan 2+
- `plan3Networks` — Array adicional Plan 3
- `currentNetworks` — Redes activas del tenant
- `headerTopEnabled` — Boolean para Header Top
- `headerTopText` — Texto de Header Top
- `pin` — PIN actual
- `currencyMode` — Modo actual de moneda

---

## 3. ImageUploadController

**Propósito:** Maneja upload y procesamiento de imágenes (logo, hero, productos, servicios, about) con redimensionamiento a 800px y conversión a WebP.

### Métodos Principales:

- **`uploadLogo()`** — Recibe imagen, valida, procesa (800px/WebP), guarda en `storage/tenants/{id}/logo.webp`, devuelve filename y URL.
- **`uploadHero()`** — Procesa imagen hero de sección principal.
- **`uploadProduct($productId)`** — Procesa imagen principal de producto.
- **`uploadService($serviceId)`** — Procesa imagen principal de servicio.
- **`uploadAbout()`** — Procesa imagen de sección About.
- **`uploadProductGallery($productId)`** — Carga múltiples imágenes a galería de producto (máx 3 por plan).
- **`deleteProductGalleryImage($imageId)`** — Elimina imagen de galería y borra archivo.

### Rutas que lo Invocan:

```
POST   /tenant/{tenantId}/upload/logo                          → uploadLogo()
POST   /tenant/{tenantId}/upload/hero                          → uploadHero()
POST   /tenant/{tenantId}/upload/product/{productId}           → uploadProduct()
POST   /tenant/{tenantId}/upload/service/{serviceId}           → uploadService()
POST   /tenant/{tenantId}/upload/about                         → uploadAbout()
POST   /tenant/{tenantId}/upload/product/{productId}/gallery   → uploadProductGallery()
DELETE /tenant/{tenantId}/upload/product/{productId}/gallery/{imageId} → deleteProductGalleryImage()
```

### Variables que Envía a Vistas:

**No envía a vistas — retorna JSON:**
- `success` — Boolean
- `message` — Mensaje de estado
- `filename` — Nombre del archivo guardado
- `url` — URL pública del asset
- `image_id` — ID de ProductImage (si aplica)

---

## 4. resources/views/dashboard/index.blade.php

**Propósito:** Vista maestro del dashboard de 6 tabs. Estructura HTML, tabs sidebar, CRUD modales para productos/servicios, componentes de diseño, temas, paletas, analytics mock, config.

### Estructura Principal:

- **Sidebar Navigation** — 6 tabs principales (botones sidebar):
  1. `#info-tab` — Info tab (datos del negocio)
  2. `#products-tab` — CRUD Productos
  3. `#services-tab` — CRUD Servicios
  4. `#design-tab` — Diseño (hero, about, temas, paletas)
  5. `#analytics-tab` — Analytics (chart.js, estadísticas mock)
  6. `#config-tab` — Configuración (moneda, testimonios, FAQ, sucursales, métodos pago, redes, horarios, PIN)

- **Componentes Incluidos** (`@include`):**
  - `dashboard.components.info-section` — Cuerpo tab Info
  - `dashboard.components.product-section` — Cuerpo tab Productos
  - `dashboard.components.service-section` — Cuerpo tab Servicios
  - `dashboard.components.visual-section` — Cuerpo tab Diseño (hero, logo, about, QR sticker)
  - `dashboard.components.message-section` — Secciones ordenables (Tu Mensaje)
  - `dashboard.components.analytics-section` — Analytics
  - `dashboard.components.config-section` → incluye config modular

- **Scripts Incluidos** (`@push('scripts')`):
  - `dashboard.scripts.sortable-scripts` — SortableJS init, saveSectionsOrder, moveSection, toggleSection, testimonials CRUD, FAQ CRUD
  - `dashboard.scripts.analytics-scripts` — Chart.js config y rendering
  - `dashboard.scripts.crud-scripts` — Producto/servicio modal CRUD, validaciones cliente

### Variables que Recibe:

```
$tenant, $plan, $customization, $products, $services, $branches,
$dollarRate, $euroRate, $daysUntilExpiry, $isExpiringSoon, $isFrozen,
$graceRemainingDays, $palettes, $blueprint, $maxItems, $itemLabel,
$itemSingular, $currentTheme, $customPalette, $hasCustomPalette,
$activeTheme, $trackingQR, $trackingShortlink, $savedTestimonials,
$savedFaq, $plan1NetworksList, $plan2NetworksList, $plan3Networks,
$currentNetworks, $headerTopEnabled, $headerTopText, $pin, $currencyMode
```

---

## 5. resources/views/landing/base.blade.php

**Propósito:** Layout maestro HTML para todas las landing pages. Solo estructura base (head, meta, styles, scripts globales); **no contiene secciones** — solo `@yield('content')` que se rellena con studio.blade.php o catalog.blade.php.

### Estructura:

- **Head:**
  - Meta tags: charset, viewport, csrf-token, SEO (title, description, keywords, canonical, og:*)
  - Fonts: Geist, Public Sans (via Google Fonts CDN)
  - Vite assets: app.css, app.js
  - Schema.org JSON-LD dinámico según `$blueprint['schema_type']` (Restaurant, Store, HealthAndBeautyBusiness, ProfessionalService, LocalBusiness)
  - Custom CSS para paleta personalizada (si Plan 3 + custom)

- **Body:**
  - Grain texture fija (background SVG overlay)
  - `@yield('content')` — dinámico según template
  - `@include('landing.sections.floating-panel')` — panel flotante (PIN, KPIs, QR, info)
  - Iconify CDN (para icons Tabler)

### Variables que Recibe:

```
$meta (array con title, description, keywords, canonical, og_title, og_description, og_image),
$tenant,
$customization (para theme_slug y custom_palette),
$themeSlug,
$blueprint,
$schema (JSON-LD generado)
```

### Rutas/Templates que lo Extienden:

- `landing.templates.studio` — Default (servicios/profesionales)
- `landing.templates.catalog` — Ecommerce
- `landing.frozen` — Página estática cuando tenant congelado

---

## Flujo de Datos Resumido

### Landing Page (Público):

1. **Request:** `GET subdomain.synticorex.test` 
2. **TenantRendererController::show()** 
3. Carga tenant, products, services, customization, rates, QR, schema
4. **Resuelve template:** studio.blade.php (defecto) o catalog.blade.php
5. **Extiende** landing.base.blade.php
6. base.blade.php renderiza head + floating-panel + `@yield('content')`
7. Studio/catalog rellena `@yield('content')` con secciones dinámicas según `getSectionsOrder()`

### Dashboard (Privado):

1. **Request:** `GET /tenant/{id}/dashboard` (autenticado)
2. **DashboardController::index()** 
3. Carga tenant, plan, customization, productos, servicios, sucursales, rates, paletas, testimonios, FAQ, etc.
4. **Renderiza** dashboard/index.blade.php
5. Tabs sidebar muestran/ocultan contenido con CSS (tab-content.active)
6. Componentes incluidos cargan secciones (info, products, services, visual, message, analytics, config)
7. Scripts inicializan SortableJS, Chart.js, CRUD modales

### Uploads de Imágenes:

1. **Request:** `POST /tenant/{id}/upload/{tipo}` + file
2. **ImageUploadController::upload{Tipo}()** 
3. ImageUploadService procesa (redimensiona 800px, convierte WebP)
4. Guarda en `storage/tenants/{id}/{tipo}.webp`
5. **Responde JSON** con filename + URL
6. JS cliente actualiza preview + form input

---

## Resumen Técnico

| Componente | Tipo | Render | Entrada | Salida |
|---|---|---|---|---|
| TenantRendererController | Controller | Blade (studio/catalog) | Subdomain/Domain | 20+ variables a vista |
| DashboardController | Controller | Blade (dashboard) | TenantId + FormData | 30+ variables a vista |
| ImageUploadController | Controller | JSON | File + TenantId | {success, filename, url} |
| landing/base.blade.php | Layout | HTML | Schema, Meta, Customization | Extend a templates |
| dashboard/index.blade.php | View | HTML | 30+ variables | 6 tabs CRUD + analytics |

