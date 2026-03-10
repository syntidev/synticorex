# SYNTIWEB — INSTRUCCIONES MAESTRAS PARA AGENTES IA
# Versión: 3.0 | Actualizado: MAR 2026
# LEER COMPLETO ANTES DE GENERAR CUALQUIER CÓDIGO

---

## 🏗️ PROYECTO

SaaS multitenant Venezuela. Landing pages dinámicas para negocios pequeños.
Cada tenant = subdominio O dominio personalizado propio.
Dueño gestiona todo con PIN desde celular. Contacto por WhatsApp.
Moneda: REF (no $) — mercado dolarizado Venezuela 2026.
Ruta local: C:\laragon\www\synticorex\

---

## 🔧 STACK REAL

Laravel 12.51, PHP 8.3, MySQL, Preline 4.1.2, Tailwind 4.2, Alpine.js 3.4.2,
Vite 7, Intervention Image 3.11, QR: simplesoftwareio/simple-qrcode, Blade puro, JS vanilla.

---

## 🚨 REGLAS CRÍTICAS — NUNCA VIOLAR

### PHP
- `declare(strict_types=1)` en TODO archivo PHP sin excepción
- Early return pattern obligatorio
- Eager loading obligatorio (evitar N+1)
- NUNCA lógica compleja en Blade → va en Model/Controller
- Tenant isolation: siempre filtrar por tenant_id
- Imágenes: max 800px, convertir a WebP
- NUNCA usar `asset()` → siempre `@vite()`
- NUNCA `exec()` o `shell_exec()` en producción
- NUNCA `{!! !!}` salvo Schema.org generado internamente
- NUNCA `eval()` o `new Function()` en JS

### UI / CSS
- NUNCA instalar FlyonUI ni DaisyUI — fueron removidos permanentemente
- NUNCA bajar versión de Preline (4.1.2) ni Tailwind (4.2)
- NUNCA usar clases DaisyUI: `btn` `card` `modal` `badge` `drawer` `collapse`
  `dropdown` `join` `stat` `toast` `divider` `menu` `alert`
- Usar Tailwind utilitario puro para todo estilo
- Íconos: `@iconify-json/tabler` únicamente — NUNCA emojis como íconos UI

### Estructura Blade
- NUNCA usar `landing/partials/` — carpeta eliminada permanentemente
- SIEMPRE usar `landing/sections/` para todo blade de landing
- `@include` siempre como `@include('landing.sections.NOMBRE')`
- NUNCA Node.js en servidor (solo build local)

---

## 🧱 COMPONENTES INTERACTIVOS — SOLO PRELINE

```
Modal/Overlay   → hs-overlay + data-hs-overlay
Accordion       → hs-accordion + hs-accordion-toggle + hs-accordion-content
Dropdown        → hs-dropdown + hs-dropdown-toggle + hs-dropdown-menu
Tabs            → data-hs-tab + hs-tab-active:
Navbar collapse → hs-collapse + hs-collapse-toggle + data-hs-collapse
Alpine.js (x-data, x-show, x-on) es compatible con Preline — mantener intacto
```

---

## 🗂️ ARCHIVOS CLAVE

```
app/Http/Controllers/TenantRendererController.php  → renderiza landing
app/Http/Controllers/DashboardController.php       → 6 tabs CRUD
app/Http/Controllers/ImageUploadController.php     → WebP upload
app/Services/DollarRateService.php                 → API BCV + fallback
app/Models/Tenant.php                              → modelo principal
app/Models/TenantCustomization.php                 → canAccessSection()
resources/views/landing/base.blade.php             → layout + CURRENCY_MODE
resources/views/landing/sections/                  → ÚNICA carpeta válida
resources/views/dashboard/index.blade.php          → dashboard ~2000 líneas
routes/web.php                                     → todas las rutas
```

---

## ⚠️ FRONTERA CRÍTICA — DOS MUNDOS SEPARADOS

```
╔══════════════════════════════════════════════════════╗
║  MUNDO INTERNO (SyntiWeb)    MUNDO TENANT (público)  ║
║  ─────────────────────────   ──────────────────────  ║
║  dashboard/                  landing/                ║
║  wizard/                     landing/sections/       ║
║  marketing/                                          ║
║  auth/                                               ║
║  docs/                                               ║
╚══════════════════════════════════════════════════════╝

MUNDO INTERNO → aplica branding SyntiWeb (#4A80E4, logo SyntiWeb)
MUNDO TENANT  → JAMÁS poner logo SyntiWeb. El tenant sube su propio logo.
                JAMÁS usar colores SyntiWeb como brand del tenant.
                Usar SOLO variables --brand-* del tenant.
```

---

## 🎨 IDENTIDAD DE MARCA SYNTIWEB (solo interfaces internas)

### Colores oficiales
```css
--sw-blue:         #4A80E4   /* acento principal — NUNCA cambia */
--sw-navy:         #1a1a1a
--sw-white:        #FFFFFF
--sw-bg:           #F8FAFF
--sw-surface:      #FFFFFF
--sw-border:       #E2E8F4
--sw-text:         #1a1a1a
--sw-text-muted:   #64748b
--sw-accent-glow:  rgba(74,128,228,0.15)
--sw-font:         'Geist', ui-sans-serif, system-ui, sans-serif
```

### Regla de contraste (irrompible)
```
Fondo claro  → SYNTI: #1a1a1a | web: #4A80E4
Fondo oscuro → SYNTI: #4A80E4 | web: #FFFFFF
El azul #4A80E4 es constante en AMBOS contextos — nunca cambia
```

### Logo por contexto
```
Dashboard navbar  → syntiweb-logo-positive.svg  (fondo claro)
Dashboard sidebar → syntiweb-logo-negative.svg  (fondo oscuro)
Marketing navbar  → syntiweb-logo-positive.svg
Marketing footer  → syntiweb-logo-negative.svg
Onboarding        → syntiweb-logo-positive.svg
Landing tenant    → flat-positive.svg SOLO badge "Powered by" 16x16
```

### HTML Logo fondo claro
```html
<a href="/" class="flex items-center gap-2">
  <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="32" height="32">
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
  </span>
</a>
```

### HTML Logo fondo oscuro
```html
<a href="/" class="flex items-center gap-2">
  <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" alt="SYNTIweb" width="32" height="32">
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
  </span>
</a>
```

---

## 🍽️ SKILL: syntifood-designer
## (Aplica a landing/sections/food.blade.php)

### Estructura visual obligatoria (de arriba a abajo)
1. Hero Slider — height:220px, fade auto-play 5s, dots, sin flechas
2. Header sticky — logo redondo 72px superpuesto, nombre+estado+dirección+redes
3. Modal Información — horario, dirección, teléfono, compartir
4. Barra categorías sticky — lupa + hamburguesa + tabs con indicador deslizante
5. Banner estado cerrado — franja roja solo si !$tenant->is_open
6. Cards productos HORIZONTALES — imagen derecha 96x96px (NO negociable)
7. Badges — Popular (amarillo), Nuevo (verde), Descuento (rojo)
8. Carrito flotante fixed bottom-6 right-4

### Reglas de scroll (CRÍTICAS)
```
NUNCA overflow-hidden en body, main, section, wrapper padre
NUNCA height:100vh + overflow:hidden combinados en padres
Solo overflow-x:hidden permitido en barra de categorías
Drawer usa position:fixed — NUNCA afecta scroll del body
Al abrir drawer: document.body.style.overflow='hidden'
Al cerrar drawer: document.body.style.overflow=''
```

### Badge del carrito (fix overflow)
```html
<span class="absolute -top-2 -right-2 min-w-[22px] h-[22px]
  bg-red-500 text-white text-xs font-bold rounded-full
  flex items-center justify-center border-2 border-white">
  0
</span>
```

### Indicador de categoría activa (deslizante)
```javascript
// IntersectionObserver detecta qué categoría está en pantalla
// El indicador se mueve con: position absolute + transition left+width 300ms
// threshold: 0.3, rootMargin: '-80px 0px -60% 0px'
```

### Paleta tenant (NUNCA colores SyntiWeb aquí)
```css
--brand-50, --brand-500, --brand-600, --brand-700
Verde abierto: #22c55e | Rojo cerrado: #ef4444
```

---

## 🎨 SKILL: tenant-design-ai
## (Diseño visual de cualquier landing pública de tenant)

### Cuando recibes una imagen de referencia:
1. Extraer paleta → mapear a --brand-50/500/600/700
2. Identificar atmósfera: minimal / lujoso / orgánico / técnico / playful
3. Respetar tipografía de la imagen (peso, tamaño, jerarquía)
4. Escribir blade en landing/sections/ — nunca en landing/partials/
5. Una iteración = código production-ready

### Secciones disponibles por plan
```
Plan 1: hero, products(6), services(3), contact, payment_methods, cta, footer
Plan 2: + about, testimonials
Plan 3: + faq, branches, products(18 con slider 3 fotos)
```

### Variantes por sección
```
hero      → fullscreen / split / centered
products  → grid3 / masonry / slider
services  → cards / spotlight / list
about     → split / centered / timeline
faq       → accordion / two-col
cta       → centered / banner / floating
```

---

## 🎨 SKILL: ui-design-system
## (Generación de tokens de diseño desde color base)

Cuando necesitas generar una paleta completa desde un color:
```bash
python3 .claude/skills/ui-design-system/scripts/design_token_generator.py [color] [style] [format]
# Styles: modern, classic, playful
# Formats: json, css, scss
# Ejemplo: python3 ... "#4A80E4" modern css
```
Genera: escala de color completa, tipografía modular, grid 8pt, sombras, breakpoints.

---

## 🎨 SKILL: ui-ux-pro-max
## (Inteligencia de diseño UI/UX)

### Reglas profesionales que SIEMPRE aplican

**Tipografía — NUNCA usar:**
Inter, Roboto, Arial, Open Sans, Montserrat — señalan "no pensé en esto"

**Tipografía — SÍ usar (con personalidad):**
- Moderna: Satoshi, Cabinet Grotesk, Bricolage Grotesque
- Editorial: Playfair Display, Fraunces, Newsreader
- Técnica: IBM Plex, Space Grotesk

**Layout:**
- Touch targets mínimo 44x44px
- `cursor-pointer` en TODO elemento clickeable
- Transiciones: 150-300ms (no más lento)
- Hover: siempre dar feedback visual
- Mobile first — bottom navigation para apps mobile-heavy

**Contraste:**
- Texto normal: mínimo 4.5:1
- Texto muted mínimo: #475569 (slate-600) — nunca más claro
- Bordes en light mode: border-gray-200 mínimo

**Scroll y layout:**
- `overflow-y: hidden` en padres MATA el scroll — prohibido
- Z-index escala: 10, 20, 30, 50, 100
- Nunca contenido oculto detrás de navbars fijos sin padding-top

**Animaciones:**
```css
/* SIEMPRE respetar preferencias del usuario */
@media (prefers-reduced-motion: reduce) {
  * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}
```

**Anti-patrones a evitar:**
- Purple gradient + Inter font = SaaS genérico sin personalidad
- Glassmorphism everywhere = reduce legibilidad
- Cards everywhere = aburrido y predecible
- Emojis como íconos UI = no profesional

---

## 📐 MULTITENANCY

```
Un tenant = subdominio O dominio personalizado
Detección: middleware IdentifyTenant (subdomain o custom_domain)
Storage: storage/tenants/{tenant_id}/
SIEMPRE filtrar por tenant_id en toda query
```

---

## 💰 PLANES

```
Plan 1 OPORTUNIDAD  $99/año:  6 productos, 3 servicios, 10 paletas
Plan 2 CRECIMIENTO $149/año: 12 productos, 6 servicios, 17 paletas
Plan 3 VISIÓN      $199/año: 18 productos (slider 3 fotos), 9 servicios, 17 paletas + custom
```

---

## 💱 SISTEMA MONEDA

```
Símbolo: REF (nunca $)
Modos: reference_only / bolivares_only / both_toggle / hidden
Lógica resuelta en base.blade.php — no duplicar
```

---

## 🖥️ DASHBOARD

```
Activación: Alt+S desktop / long press móvil
PIN de acceso requerido
6 tabs: Info, Productos, Servicios, Diseño, Analytics, Config
Archivo: resources/views/dashboard/index.blade.php
```

---

## 🧪 TENANTS DEMO ACTIVOS

```
techstart  → Plan 3 VISIÓN      | PIN: 1234
pizzería   → Plan 2 CRECIMIENTO
barbería   → Plan 1 OPORTUNIDAD
arepera    → SyntiFood demo
```

---

## ✅ CHECKLIST ANTES DE ENTREGAR CÓDIGO

- [ ] declare(strict_types=1) en PHP
- [ ] Sin clases DaisyUI en HTML
- [ ] Sin overflow-hidden en contenedores padre
- [ ] cursor-pointer en elementos clickeables
- [ ] Touch targets ≥ 44px
- [ ] @vite() en lugar de asset()
- [ ] tenant_id validado en queries
- [ ] Sin logo SyntiWeb en vistas de tenant
- [ ] Sin brand SyntiWeb en landing/sections/
- [ ] Blade en landing/sections/ nunca en landing/partials/


---

## 🖼️ BRAND — SVG LOGO INLINE (cuando no hay archivo SVG disponible)

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

### Archivos SVG en public/brand/
```
syntiweb-logo-positive.svg      → fondo claro (navbar, wizard, onboarding)
syntiweb-logo-negative.svg      → fondo oscuro (sidebar, footer navy)
syntiweb-logo-adaptive.svg      → NO USAR en producción
syntiweb-logo-flat-positive.svg → favicon, badge "Powered by" 16x16
syntiweb-logo-monochrome.svg    → sellos, documentos legales
```

---

## 🔖 FAVICON KIT — PEGAR EN <head> DE TODOS LOS LAYOUTS
```html
<!-- SYNTIweb Favicon Kit -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#4A80E4">
```

### Archivos en public/ (NO mover, NO renombrar)
```
favicon.ico
favicon-16x16.png
favicon-32x32.png
favicon-32x32.png
apple-touch-icon.png
android-chrome-192x192.png
android-chrome-512x512.png
site.webmanifest
brand/syntiweb-logo-*.svg
```

### Layouts que DEBEN tener el favicon kit
```
resources/views/layouts/app.blade.php
resources/views/landing/base.blade.php
resources/views/dashboard/index.blade.php
```

---

## 🏷️ FAVICON Y FOOTER — LÓGICA DE PROPIEDAD

### Regla de negocio
SyntiWeb es la infraestructura. Todo tenant corre sobre ella.
Por defecto: favicon SyntiWeb + footer "Powered by SyntiWeb" en TODAS las landings.
El tenant NO puede quitar esto salvo que pague personalización.

### Favicon — Universal en todos los layouts incluido landing/base.blade.php
```html
<!-- Siempre presente, no es configurable por tenant -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#4A80E4">
```

### Footer — "Powered by SyntiWeb" obligatorio
```html
<!-- En landing/sections/footer.blade.php — siempre al final -->
<div class="text-center text-xs text-gray-400 py-3 border-t border-gray-100">
  <a href="https://syntiweb.com" target="_blank" class="flex items-center justify-center gap-1 hover:opacity-70 transition">
    <img src="/brand/syntiweb-logo-flat-positive.svg" width="16" height="16" alt="SYNTIweb">
    <span>Powered by <strong>SYNTIweb</strong></span>
  </a>
</div>
```

### Personalización (feature de pago futuro)
```
Plan estándar   → favicon SyntiWeb + "Powered by SyntiWeb" (obligatorio)
Plan White Label → $X/año: favicon propio + footer personalizado
Columna DB: tenants.white_label (boolean, default: false)
Lógica: @if(!$tenant->white_label) ... @endif
```