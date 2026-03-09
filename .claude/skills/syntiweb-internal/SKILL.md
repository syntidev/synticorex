---
name: syntiweb-internal
description: Diseño y desarrollo de interfaces INTERNAS de SyntiWeb: dashboard administrativo, wizard de onboarding, página comercial, blog y docs. Aplica branding SyntiWeb estrictamente. Usar SIEMPRE que se trabaje en dashboard/, wizard/, marketing/, o cualquier vista de la plataforma misma. NUNCA aplicar en landing/sections/ ni en vistas de tenants.
---

# SyntiWeb Internal UI Skill

Skill para interfaces internas de la plataforma SyntiWeb. Aplica identidad de marca SyntiWeb con precisión.

## ⚠️ FRONTERA CRÍTICA — LEER PRIMERO

```
APLICA branding SyntiWeb EN:
  resources/views/dashboard/
  resources/views/wizard/
  resources/views/marketing/
  resources/views/auth/
  resources/views/docs/

NUNCA tocar con branding SyntiWeb:
  resources/views/landing/          ← territorio del tenant
  resources/views/landing/sections/ ← logo y colores son del tenant
```

**El tenant instala su propio logo. Jamás asumir logo por defecto.**

---

## Stack UI Interno

- **Framework CSS**: Tailwind 4.2 (utilitario puro, sin clases DaisyUI/FlyonUI)
- **Componentes interactivos**: Preline 4.1.2 únicamente (atributos `hs-*`)
- **JS**: Alpine.js 3.4.2 compatible con Preline
- **Íconos**: `@iconify-json/tabler` únicamente
- **PHP**: `declare(strict_types=1)` obligatorio, early return pattern
- **Imágenes**: `@vite()` siempre, nunca `asset()`

### Clases PROHIBIDAS (DaisyUI/FlyonUI)
`btn` `card` `modal` `badge` `drawer` `collapse` `dropdown` `join` `stat` `toast` `divider` `menu` `alert`

---

## Identidad de Marca SyntiWeb

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
Fondo claro  → SYNTI: #1a1a1a  | web: #4A80E4
Fondo oscuro → SYNTI: #4A80E4  | web: #FFFFFF
El azul #4A80E4 es constante en AMBOS contextos
```

### Logo por contexto
```
Dashboard navbar    → syntiweb-logo-positive.svg  (fondo claro)
Dashboard sidebar   → syntiweb-logo-negative.svg  (fondo oscuro)
Marketing navbar    → syntiweb-logo-positive.svg
Marketing footer    → syntiweb-logo-negative.svg
Onboarding header   → syntiweb-logo-positive.svg
```

### HTML Logo — Fondo claro
```html
<a href="/" class="flex items-center gap-2">
  <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="32" height="32">
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
  </span>
</a>
```

### HTML Logo — Fondo oscuro
```html
<a href="/" class="flex items-center gap-2">
  <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" alt="SYNTIweb" width="32" height="32">
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
  </span>
</a>
```

---

## Preline Components (referencia rápida)
```
Modal/Overlay   → hs-overlay + data-hs-overlay
Accordion       → hs-accordion + hs-accordion-toggle + hs-accordion-content
Dropdown        → hs-dropdown + hs-dropdown-toggle + hs-dropdown-menu
Tabs            → data-hs-tab + hs-tab-active:
Navbar collapse → hs-collapse + hs-collapse-toggle + data-hs-collapse
```

---

## Dashboard — Estructura
- Activación: Alt+S desktop / long press móvil
- PIN de acceso requerido
- 6 tabs: Info, Productos, Servicios, Diseño, Analytics, Config
- Archivo: `resources/views/dashboard/index.blade.php`
- Siempre validar `tenant_id` en toda query
