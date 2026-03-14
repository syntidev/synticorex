# SYNTIWEB — CONTEXTO DE PROYECTO
# Versión: 4.0 | MAR 2026
# ⚠️ Este archivo es contexto estático de negocio y arquitectura.
# El estado del proyecto NO va aquí — se gestiona en issues/milestones.

---

## NEGOCIO

SaaS multitenant Venezuela. Landing pages dinámicas para negocios pequeños.
Cada tenant = subdominio O dominio personalizado propio.
Dueño gestiona todo con PIN desde celular. Contacto por WhatsApp.
Moneda: REF (no $) — mercado dolarizado Venezuela 2026.

---

## ARQUITECTURA DE ARCHIVOS CLAVE

```
C:\laragon\www\synticorex\
│
├── app\Http\Controllers\
│   ├── TenantRendererController.php   ← renderiza landing pública
│   ├── DashboardController.php        ← 6 tabs CRUD (Info/Productos/Servicios/Diseño/Analytics/Config)
│   └── ImageUploadController.php      ← WebP upload
│
├── app\Services\
│   ├── DollarRateService.php          ← API BCV + fallback manual
│   └── ImageUploadService.php         ← resize + conversión WebP
│
├── app\Models\
│   ├── Tenant.php                     ← modelo principal
│   └── TenantCustomization.php        ← canAccessSection() por plan
│
├── resources\views\
│   ├── landing\
│   │   ├── base.blade.php             ← layout base + CURRENCY_MODE
│   │   └── sections\                  ← ÚNICA carpeta válida para secciones
│   └── dashboard\
│       └── index.blade.php            ← dashboard ~2000 líneas
│
├── routes\web.php                     ← todas las rutas
│
└── .github\
    ├── copilot-instructions.md        ← GOBERNANZA + reglas críticas
    ├── copilot-workspace.yml          ← skills técnicos del agente
    └── SYNTIWEB-CONTEXT.md            ← este archivo
```

---

## MULTITENANCY

```
Detección: middleware IdentifyTenant (subdomain o custom_domain)
Storage:   storage/tenants/{tenant_id}/
Regla:     SIEMPRE filtrar por tenant_id en toda query
```

---

## TENANTS DEMO ACTIVOS

| Tenant    | Plan              | PIN  | Notas                    |
|-----------|-------------------|------|--------------------------|
| techstart | Plan 3 VISIÓN     | 1234 | demo completo             |
| pizzería  | Plan 2 CRECIMIENTO| —    |                          |
| barbería  | Plan 1 OPORTUNIDAD| —    |                          |
| arepera   | SyntiFood         | —    | demo food/restaurant      |

---

## DASHBOARD

```
Activación:  Alt+S (desktop) / long press (móvil)
Acceso:      PIN requerido
Tabs (6):    Info | Productos | Servicios | Diseño | Analytics | Config
Archivo:     resources/views/dashboard/index.blade.php
```

---

## IDENTIDAD DE MARCA SYNTIWEB
### Solo aplica a interfaces internas (dashboard, wizard, marketing, auth)

```css
--sw-blue:        #4A80E4   /* acento principal — NUNCA cambia */
--sw-navy:        #1a1a1a
--sw-white:       #FFFFFF
--sw-bg:          #F8FAFF
--sw-surface:     #FFFFFF
--sw-border:      #E2E8F4
--sw-text:        #1a1a1a
--sw-text-muted:  #64748b
--sw-font:        'Geist', ui-sans-serif, system-ui, sans-serif
```

**Regla de contraste:**
```
Fondo claro  → SYNTI: #1a1a1a | web: #4A80E4
Fondo oscuro → SYNTI: #4A80E4 | web: #FFFFFF
```

---

## LOGOS POR CONTEXTO

```
Dashboard navbar  → syntiweb-logo-positive.svg   (fondo claro)
Dashboard sidebar → syntiweb-logo-negative.svg   (fondo oscuro)
Marketing navbar  → syntiweb-logo-positive.svg
Marketing footer  → syntiweb-logo-negative.svg
Onboarding        → syntiweb-logo-positive.svg
Landing tenant    → flat-positive.svg  SOLO badge "Powered by" 16x16
```

**Archivos en public/brand/:**
```
syntiweb-logo-positive.svg        ← navbar, wizard, onboarding
syntiweb-logo-negative.svg        ← sidebar, footer navy
syntiweb-logo-flat-positive.svg   ← favicon, badge Powered by 16x16
syntiweb-logo-monochrome.svg      ← sellos, documentos legales
syntiweb-logo-adaptive.svg        ← NO USAR en producción
```

---

## FAVICON KIT — PEGAR EN <head> DE TODOS LOS LAYOUTS

```html
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#4A80E4">
```

Layouts que deben incluirlo:
- `resources/views/layouts/app.blade.php`
- `resources/views/landing/base.blade.php`
- `resources/views/dashboard/index.blade.php`

---

## POWERED BY — REGLA DE NEGOCIO

SyntiWeb es la infraestructura. Por defecto: favicon SyntiWeb + footer en TODAS las landings.
El tenant NO puede quitar esto salvo plan White Label ($X/año).

```html
<!-- landing/sections/footer.blade.php — siempre al final -->
<div class="text-center text-xs text-gray-400 py-3 border-t border-gray-100">
  <a href="https://syntiweb.com" target="_blank"
     class="flex items-center justify-center gap-1 hover:opacity-70 transition">
    <img src="/brand/syntiweb-logo-flat-positive.svg" width="16" height="16" alt="SYNTIweb">
    <span>Powered by <strong>SYNTIweb</strong></span>
  </a>
</div>
```

```php
// Lógica en blade:
// @if(!$tenant->white_label) ... @endif
// Columna DB: tenants.white_label (boolean, default: false)
```

---

## HTML LOGO INLINE (cuando no hay SVG disponible)

### Fondo claro
```html
<a href="/" class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="32" height="32">
    <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#1a1a1a"/>
    <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
  </svg>
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
  </span>
</a>
```

### Fondo oscuro
```html
<a href="/" class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="32" height="32">
    <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#FFFFFF"/>
    <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
  </svg>
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
  </span>
</a>
```