📋 AUDITORÍA UX/UI COMPLETA — Dashboard SYNTIweb
http://127.0.0.1:8000/tenant/3/dashboard · ServicePro Consulting
Fecha: 25/02/2026 · Auditor: Claude Sonnet 4.6 · Para: Claude Code
1. ECOSISTEMA TÉCNICO DETECTADO
Stack actual

Backend: Laravel (URL pattern /tenant/{id}/dashboard, CSRF meta presente)
Frontend CSS: CSS custom en <style> inline (14.886 chars) + bundle Vite app-DvlTHU1E.css
Frontend JS: Bundle Vite app-CBbTb_k3.js + SortableJS 1.15.0 (CDN externo)
Framework JS reactivo: Alpine.js (detectado en window.Alpine) — pero cero componentes x-data encontrados en el DOM. Alpine no se está usando para nada funcional.
Framework UI: FlyonUI/DaisyUI referenciado en el título de sección pero con uso ínfimo: solo 1 .btn, 1 .card, 3 .badge. El 95% del UI es CSS custom.
Tailwind CSS: Detectado parcialmente pero con clases utilitarias casi inexistentes en el markup.
Viewport: width=device-width, initial-scale=1.0 ✅ (correcto pero insuficiente sin minimum-scale=1)
Total media queries: UNA SOLA — @media (max-width: 768px) con solo 4 reglas. Crítico.


2. ARQUITECTURA DE NAVEGACIÓN — PROBLEMAS CRÍTICOS
2.1 Sistema de Tabs (Menú Principal)
Problema: La navegación principal usa 7 tabs horizontales (Info, Productos, Servicios, Diseño, Analytics, Sucursales, Config) con padding de 16px 20px cada una. A 1094px de ancho total funciona bien en desktop. En mobile (375px) el contenedor tiene overflow-x: auto pero sin ningún indicador visual de scroll (sin flechas, sin fade, sin scroll dots). El usuario no sabe que puede deslizar.
Métricas de los tabs:
Todos tienen: height 55px ✅ (>44px WCAG) 
En mobile: 7 tabs × ~125px promedio = ~875px total en viewport de 375px → overflow invisible
Problema de lógica: Los tabs no tienen role="tab" ni role="tablist" ni aria-selected. El navegador del usuario no puede entender la estructura semánticamente. Un lector de pantalla los leerá como lista simple.
Problema de un solo dedo en móvil: Para llegar a "Config" (último tab), el usuario debe hacer scroll horizontal de la nav mientras intenta no activar el tab adyacente. No hay indicador de qué tabs existen más allá del viewport. El patrón correcto para mobile-first sería un bottom navigation bar de máximo 5 ítems o un drawer/hamburger lateral.
2.2 Header
css.dashboard-header {
    padding: 16px 24px;  /* ← padding fijo, no escala */
    justify-content: space-between;
}
En mobile el header muestra logo + nombre de negocio + botón "Cerrar" en una sola línea. Con nombres de negocio largos habrá truncado o desbordamiento. El botón "Cerrar ✕" tiene height: 37px (< 44px mínimo WCAG 2.5.5 para touch targets). Incumple Apple HIG y Material Design (mínimo 44-48px).
3. RESPONSIVE / ADAPTABILIDAD — DIAGNÓSTICO COMPLETO
3.1 Estado actual: Catastrófico en mobile
Solo existe 1 media query a 768px. Comparación con estándares:
BreakpointEstándar esperadoEstado actual320px (mobile S)Layout stack 1 columna❌ No definido375px (iPhone SE/15)Stack, nav hamburger❌ No definido428px (iPhone Pro Max)Stack, nav scroll o bottom bar❌ No definido600px (tablet portrait)Layout 2 col parcial❌ No definido768px (tablet landscape)Breakpoint detectado✅ Parcial (solo form-grid)1024px (laptop)Layout completo✅ Funciona por defecto1280px+ (desktop)Max-width 1400px centrado✅ Bien
3.2 Tabla de Productos/Servicios en Mobile
La tabla data-table tiene overflow: hidden en su contenedor. Las columnas Imagen, Nombre, Precio USD, Badge, Activo, Acciones en 375px se apilan ilegiblemente o se cortan. No existe ningún estilo responsive para la tabla. En mobile las tablas deben convertirse a tarjetas verticales (patrón "card-table" o "stacked table").
Las imágenes de productos en la tabla muestran thumbnails recortados con texto encima (Auditor Ejecuti...) porque el <td> no tiene width definido. En 1158px se ve aceptable, en 375px sería caótico.
3.3 Form Grid
css.form-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}
```
El `minmax(300px, 1fr)` colapsa correctamente a 1 columna cuando el viewport es < 600px. **Esta es la única parte responsiva funcional del dashboard.** Sin embargo, no hay breakpoint para campo "Descripción" (textarea), que queda muy alta en mobile.
### 3.4 Modales (CRUD de productos/servicios)
Los modales `.crud-overlay` usan `position: fixed` con contenido en `.crud-dialog`. No se encontraron estilos responsive para los modales. En mobile un modal que no adapte su ancho al viewport es inutilizable con una mano.
### 3.5 Sección Diseño/Temas
Los theme swatches (tarjetas de color) están en grid implícito sin definir. En mobile aparecerán 2-4 por fila en forma irregular. No hay `grid-template-columns` explícito responsivo.
---
## 4. USABILIDAD — PROBLEMAS DETALLADOS
### 4.1 Arquitectura de Información
El dashboard actual tiene una sola pantalla que carga **todo el contenido** de todas las tabs simultáneamente en el DOM (incluidos formularios, tablas y modales). Esto genera:
- Tiempo de carga inicial innecesariamente largo
- 68 inputs sin label asociado correctamente (via `for`/`id` o `aria-label`) — todos los inputs del dashboard fallan este check
- Scroll excesivo dentro de cada tab (la tab Config tiene 6 secciones largas)
### 4.2 Navegación de un Solo Dedo (Thumb Zone)
En un smartphone de 6.1" (iPhone 15, 390px), la zona cómoda de alcance con el pulgar cubre la parte inferior de la pantalla. Los elementos de acción más importantes están en:
- **Header (top):** Botón "Cerrar" — zona DIFÍCIL con pulgar
- **Nav tabs (top):** Todos los tabs — zona DIFÍCIL con pulgar
- **Contenido central:** Formularios y botones — zona MEDIA
- **No hay nada en bottom:** Zona FÁCIL completamente desaprovechada
El patrón correcto sería mover la navegación principal al **bottom** en mobile, siguiendo el patrón de apps nativas (Instagram, WhatsApp, etc.).
### 4.3 Velocidad de Navegación
- Cambiar de tab requiere scroll hasta la navegación, encontrar el tab correcto, hacer tap. Si el usuario está en medio de la página, debe scrollear de regreso al top primero.
- La barra de navegación NO es sticky separada del header — header y nav se comportan como bloque unificado sticky, pero la nav no tiene su propio `position: sticky` independiente.
- No hay breadcrumbs ni indicador de posición actual más allá del subrayado del tab activo.
### 4.4 Feedback Visual
- El botón "Guardar Cambios" no tiene estado de loading/success visible más allá de posibles alerts JS.
- Los toggles (Active/Inactive productos) no tienen animación de transición definida en CSS.
- Los badges HOT/NEW son correctos visualmente, pero el ícono ✏️🗑️ para acciones en tabla es muy pequeño (~ 24×24px) para touch.
### 4.5 Tabla de Productos — Problemas Específicos
Las imágenes de productos en la tabla no tienen `width`/`height` fijo en CSS, produciendo layout shift. El texto de la imagen alt (`Auditor Ejecuti...`) se recorta porque el `<td>` se contrae. Los botones de acción (✏️ y 🗑️) no tienen tamaño definido — son pure emoji sin hitbox adecuado.
---
## 5. ACCESIBILIDAD — INCUMPLIMIENTOS WCAG 2.1
| Criterio | Nivel | Estado | Detalle |
|---|---|---|---|
| 1.3.1 Info and Relationships | A | ❌ FALLA | Tabs sin `role="tab"/"tablist"`, tablas sin `<caption>` |
| 1.4.3 Contrast | AA | ⚠️ REVISAR | Color `rgba(255,255,255,0.6)` sobre `#07101F` ≈ 4.2:1 (justo en el límite, falla para texto <18px normal weight) |
| 2.1.1 Keyboard | A | ❌ FALLA | Tabs navegan por click, sin soporte de flechas de teclado (ARIA Authoring Practices Guide tablist pattern) |
| 2.4.3 Focus Order | A | ❌ FALLA | No se detectó gestión de focus visible (`:focus-visible` ausente en CSS) |
| 2.4.7 Focus Visible | AA | ❌ FALLA | Sin `:focus-visible` definido en el stylesheet |
| 2.5.5 Target Size | AAA | ❌ FALLA | Botón "Cerrar" = 37px height, íconos tabla ~24px |
| 3.1.1 Language of Page | A | ⚠️ REVISAR | `<html lang="">` — lang no definido o vacío |
| 4.1.2 Name, Role, Value | A | ❌ FALLA | 68 inputs sin label programático. Modales sin `role="dialog"` ni `aria-modal` |
| 4.1.3 Status Messages | AA | ❌ FALLA | Sin `aria-live` para mensajes de éxito/error |
**Skip link:** Ausente. Usuario de teclado debe tabular por todos los 7 nav items en cada carga.
---
## 6. SEGURIDAD — REVISIÓN DE CAPAS
### 6.1 Lo que funciona
- **CSRF Token** presente como `<meta name="csrf-token">` ✅ (Laravel standard)
- **HTTPS** en producción (actualmente HTTP local en dev, pero se asume HTTPS en prod)
### 6.2 Vulnerabilidades y ausencias
**Crítico:**
- **Sin `Content-Security-Policy`** (CSP) — ni como meta tag ni (verificable desde JS) como header HTTP. Esto expone a XSS.
- **Sin `X-Frame-Options`** — el dashboard podría ser embebido en un iframe malicioso (clickjacking). En Laravel se puede forzar vía `FrameGuard` middleware.
- **Sin `Referrer-Policy`** — las URLs del dashboard con `/tenant/{id}/` filtran información de tenant a terceros.
- **SortableJS desde CDN externo** (`cdn.jsdelivr.net`) sin `integrity` (SRI hash) ni `crossorigin="anonymous"`. Si jsDelivr es comprometido, se ejecuta código arbitrario en el dashboard del cliente.
**Moderado:**
- El campo PIN se gestiona con `type="password"` ✅ pero si el formulario de PIN no tiene protección adicional rate-limiting (throttle), es vulnerable a fuerza bruta.
- No se detectan `autocomplete="off"` en campos de PIN, lo que puede llevar a que el navegador guarde el PIN en su autocompletado.
- No hay `rel="noopener noreferrer"` verificado en el link externo "Ver planes disponibles ↗" (`href="https://syntiweb.com/planes"`).
---
## 7. DISTRIBUCIÓN VERTICAL/HORIZONTAL — RECOMENDACIÓN DE LAYOUT
### 7.1 Patrón recomendado por dispositivo
**Mobile (< 640px) — Shell Navigation:**
```
┌──────────────────────┐
│ [Logo] [Neg. Name]   │  ← Header minimal: 56px sticky top
├──────────────────────┤
│                      │
│   CONTENIDO ACTIVO   │  ← Full width, padding 16px
│   (solo 1 sección)   │
│                      │
│                      │
└──────────────────────┘
│ Info │ Prod │ Serv │ ⋯ │  ← Bottom bar 5 ítems max, 56px
└──────────────────────┘
```
**Tablet (640px–1024px) — Side Nav o Tab Bar:**
```
┌─────────┬────────────────────┐
│  SIDE   │  HEADER            │
│  NAV    ├────────────────────┤
│ vertical│                    │
│  icons  │  CONTENIDO         │
│  +text  │  2 columnas        │
│         │                    │
└─────────┴────────────────────┘
```
**Desktop (> 1024px) — Layout actual mejorado:**
```
┌──────────────────────────────────────┐
│ HEADER sticky                        │
├──────────────────────────────────────┤
│ TAB NAV horizontal                   │
├──────────────────────────────────────┤
│  CONTENIDO max-width:1400px          │
│  (grid 3 col para forms)             │
└──────────────────────────────────────┘

8. FRAMEWORK — RECOMENDACIÓN DE CAMBIO
8.1 Situación actual: CSS custom + Alpine + FlyonUI parcialmente
El dashboard tiene un problema de identidad de stack: mezcla FlyonUI/DaisyUI (componentes basados en Tailwind), CSS custom masivo (14k chars inline), y Alpine.js que no se usa. Esto genera deuda técnica, inconsistencia visual y dificultad de mantenimiento.
8.2 Recomendación: Adoptar FlyonUI completamente
Dado que ya está instalado (CSS bundle referencia FlyonUI en la sección Diseño), la recomendación es migrar 100% al sistema de componentes de FlyonUI/DaisyUI sobre Tailwind CSS, eliminando el CSS custom inline. Ventajas:

FlyonUI incluye componentes responsive por defecto: bottom-nav, drawer, navbar, tabs con ARIA correcto
El sistema de temas ya funciona (Light/Dark/etc.) — solo falta aplicarlo al dashboard mismo
Tailwind CSS Utilities eliminan la necesidad de escribir media queries manualmente
Alpine.js ya instalado se puede usar para los componentes interactivos de FlyonUI

8.3 Alternativa si se quiere más control: Tailwind + componentes propios
Si no se quiere depender de FlyonUI, usar Tailwind CSS puro con componentes propios basados en la guía de Alpine.js. El costo es mayor pero da más flexibilidad.
8.4 No recomendado

React/Vue: Overhead innecesario para este tipo de dashboard admin. Laravel + Alpine + Tailwind es el stack óptimo para este caso de uso.
Bootstrap: Más pesado que FlyonUI/Tailwind y con componentes más difíciles de customizar con el sistema de temas actual.


9. LISTA DE CAMBIOS PRIORITARIOS PARA CLAUDE CODE
🔴 CRÍTICO (implementar primero)
1. Breakpoints responsivos completos
css/* Reemplazar la única @media existente por: */
@media (max-width: 375px) { ... }   /* Mobile S */
@media (max-width: 640px) { ... }   /* Mobile M/L */
@media (max-width: 768px) { ... }   /* Tablet portrait */
@media (max-width: 1024px) { ... }  /* Tablet landscape / Laptop */
2. Navegación mobile — Bottom Navigation Bar
html<!-- En mobile, reemplazar nav horizontal por: -->
<nav class="mobile-bottom-nav" role="navigation" aria-label="Navegación principal">
  <!-- máx 5 ítems con overflow en "Más" -->
</nav>
El trigger debe ser Tailwind: hidden md:flex para nav horizontal desktop, flex md:hidden para bottom nav mobile.
3. Tabla responsiva → Cards en mobile
css@media (max-width: 640px) {
  .data-table thead { display: none; }
  .data-table tr { 
    display: block; 
    margin-bottom: 16px;
    background: #0f1c32;
    border-radius: 8px;
    padding: 12px;
  }
  .data-table td { 
    display: flex; justify-content: space-between; 
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }
  .data-table td::before { 
    content: attr(data-label); 
    font-weight: 600; color: rgba(255,255,255,0.6); 
  }
}
/* + agregar data-label="Nombre" etc. a cada <td> */
4. Touch targets mínimos
css.btn-close { min-height: 44px; min-width: 44px; }
.btn-action { min-height: 44px; min-width: 44px; padding: 10px 12px; }
.nav-tab { min-height: 48px; } /* ya cumple, mantener */
5. ARIA en tabs
html<nav role="navigation">
  <ul role="tablist" aria-label="Secciones del dashboard">
    <li role="tab" aria-selected="true" aria-controls="tab-info" tabindex="0">📋 Info</li>
    ...
  </ul>
</nav>
<div id="tab-info" role="tabpanel" aria-labelledby="tab-info-btn">...</div>
Agregar navegación con flechas via Alpine.js o JS vanilla.
🟡 IMPORTANTE (segunda iteración)
6. Seguridad — Headers HTTP (en Laravel)
php// app/Http/Middleware/SecurityHeaders.php
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
$response->headers->set('Content-Security-Policy', 
  "default-src 'self'; script-src 'self' cdn.jsdelivr.net 'nonce-{$nonce}'; style-src 'self' 'unsafe-inline' fonts.googleapis.com;"
);
7. SRI para SortableJS
html<!-- Reemplazar: -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- Por: -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" 
        integrity="sha256-[HASH]" 
        crossorigin="anonymous"></script>
<!-- O mejor: instalar via npm e incluir en el bundle Vite -->
8. Labels para inputs (68 inputs sin label programático)
html<!-- Reemplazar: -->
<label>Nombre del Negocio</label>
<input type="text">
<!-- Por: -->
<label for="business-name">Nombre del Negocio</label>
<input id="business-name" type="text" autocomplete="organization">
9. Focus visible
css:focus-visible {
  outline: 2px solid #2B6FFF;
  outline-offset: 2px;
  border-radius: 4px;
}
/* En inputs ya con border: */
input:focus-visible, textarea:focus-visible, select:focus-visible {
  border-color: #2B6FFF;
  box-shadow: 0 0 0 3px rgba(43, 111, 255, 0.25);
  outline: none;
}
10. HTML lang y meta tags
html<html lang="es">
<head>
  <meta name="robots" content="noindex, nofollow"> <!-- Dashboard privado -->
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <link rel="noopener" ...> <!-- en links externos -->
</head>
11. Modales con ARIA correcto
html<div role="dialog" aria-modal="true" aria-labelledby="modal-title" id="product-modal">
  <h2 id="modal-title">Agregar Producto</h2>
  ...
</div>
Con Alpine.js: x-trap para focus trap dentro del modal, @keydown.escape="closeModal()".
12. PIN field — seguridad adicional
html<input type="password" autocomplete="current-password" id="pin-actual">
<input type="password" autocomplete="new-password" id="pin-nuevo">
<!-- + en backend: rate limiting en la ruta de cambio de PIN -->
🟢 MEJORA UX (tercera iteración)
13. Skip link accesibilidad
html<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600">
  Saltar al contenido principal
</a>
14. Feedback de acciones (aria-live)
html<div role="status" aria-live="polite" aria-atomic="true" id="toast-region">
  <!-- Los mensajes de éxito/error se inyectan aquí -->
</div>
15. Lazy loading de tabs
En lugar de cargar todo el DOM de todas las tabs desde el inicio, usar Alpine.js o Livewire para cargar el contenido de cada tab bajo demanda:
js// Alpine.js component
Alpine.data('dashboard', () => ({
  activeTab: 'info',
  loadedTabs: new Set(['info']),
  switchTab(tab) {
    this.activeTab = tab;
    this.loadedTabs.add(tab);
  }
}))
16. Imágenes de tabla con dimensiones fijas
css.data-table .product-thumb {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 6px;
  aspect-ratio: 1/1;
}
```
**17. Relación de contraste**
Reemplazar `rgba(255,255,255,0.6)` (#a0a8b4 sobre #07101F ≈ 4.2:1) por `rgba(255,255,255,0.75)` para texto de ayuda y subtítulos, asegurando ≥ 4.5:1 para WCAG AA.
---
## 10. RESUMEN EJECUTIVO PARA CLAUDE CODE
```
PUNTAJE ACTUAL:
├── Responsividad: 2/10  (1 breakpoint, tablas no adaptadas, nav inutilizable en mobile)
├── Usabilidad mobile: 3/10  (nav difícil, touch targets incompletos, no bottom nav)  
├── Accesibilidad WCAG: 3/10  (68 inputs sin label, sin roles ARIA, sin focus visible)
├── Seguridad: 5/10  (CSRF OK, falta CSP/X-Frame/SRI/lang)
├── Performance: 7/10  (bundle Vite OK, pero todo el DOM carga de una vez)
└── Arquitectura CSS: 4/10  (CSS inline masivo, 1 media query, mix de sistemas)
ACCIÓN PRIORITARIA:
1. Migrar CSS custom inline → Tailwind CSS utilities + FlyonUI components
2. Implementar 4 breakpoints (375/640/768/1024)  
3. Crear bottom navigation para mobile (<640px)
4. Convertir tablas en cards en mobile
5. Agregar ARIA completo (tablist, dialog, live regions)
6. Fix 68 labels de inputs
7. Agregar security headers en Laravel Middleware
8. Mover SortableJS al bundle Vite (eliminar CDN externo)
9. Focus visible en toda la app
10. Skip link + lang="es" en <html>

NOTA FINAL: El stack Laravel + Alpine.js + Tailwind CSS + FlyonUI es el correcto para este proyecto. No se recomienda cambiar de framework, sino usarlo correctamente. El problema no es el stack elegido — es que el dashboard se construyó principalmente con CSS custom ignorando los componentes de FlyonUI que ya están disponibles. La solución es migrar el CSS existente hacia las clases utilitarias de Tailwind y los componentes de FlyonUI, resultando en un dashboard completamente responsive, accesible y mantenible sin añadir ninguna dependencia nueva.