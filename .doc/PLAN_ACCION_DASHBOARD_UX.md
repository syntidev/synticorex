# 📊 Plan de Acción: Mejoras Dashboard UX/UI

**Base:** Auditoría UX/UI Completa (25/02/2026)
**Objetivo:** Dashboard responsive, accesible y seguro
**Scope:** `resources/views/dashboard/index.blade.php` + CSS inline

---

## 🎯 Estrategia de Implementación

### Fase 1: CRÍTICA (Sprint 1 - ~3-4 horas)
Cambios que resuelven **mayor impacto en usabilidad mobile y accesibilidad:**

- [ ] **P1.1** Breakpoints responsivos completos (375/640/768/1024)
- [ ] **P1.2** Navigation mobile → Bottom Bar (hidden en desktop, visible en mobile)
- [ ] **P1.3** Tablas → Cards en mobile (<640px)
- [ ] **P1.4** Touch targets mínimos (44×44px buttons)
- [ ] **P1.5** ARIA básico en tabs (role, aria-selected, aria-controls)

### Fase 2: IMPORTANTE (Sprint 2 - ~2-3 horas)
Seguridad y accesibilidad WCAG:

- [ ] **P2.1** Headers HTTP seguridad (CSP, X-Frame-Options, Referrer-Policy)
- [ ] **P2.2** SRI para SortableJS (o mover a npm)
- [ ] **P2.3** Labels programáticas en 68 inputs
- [ ] **P2.4** Focus visible (`:focus-visible`)
- [ ] **P2.5** HTML lang="es" + meta tags
- [ ] **P2.6** Modales con role="dialog" + aria-modal

### Fase 3: MEJORA UX (Sprint 3 - ~2 horas)
Optimizaciones y DX:

- [ ] **P3.1** Skip link accesibilidad
- [ ] **P3.2** Toast/aria-live para mensajes
- [ ] **P3.3** Lazy loading de tabs
- [ ] **P3.4** Imágenes tabla con dimensiones fijas
- [ ] **P3.5** Contraste de color (4.5:1 WCAG AA)
- [ ] **P3.6** PIN field seguridad (autocomplete)

---

## 📋 Tareas Detalladas

### **P1.1 - Breakpoints Responsivos**

**Archivos afectados:** `dashboard/index.blade.php` (sección `<style>`)

**Cambios:**
```css
/* REEMPLAZAR la única @media (max-width: 768px) por: */

/* Mobile S: 320-374px */
@media (max-width: 374px) {
  .dashboard-container { padding: 12px; }
  .nav-tabs { flex-wrap: nowrap; overflow-x: auto; }
  .nav-tab { padding: 8px 12px; font-size: 12px; }
}

/* Mobile M/L: 375-639px */
@media (max-width: 639px) {
  .dashboard-container { padding: 16px; }
  .form-grid { grid-template-columns: 1fr; }
  .nav-tabs { display: none; } /* Usar bottom nav en su lugar */
  .data-table { /* Convertir a cards (P1.3) */ }
}

/* Tablet portrait: 640-767px */
@media (max-width: 767px) {
  .form-grid { grid-template-columns: repeat(2, 1fr); }
  .theme-grid { grid-template-columns: repeat(3, 1fr); }
}

/* Tablet landscape / Laptop: 768-1023px */
@media (max-width: 1023px) {
  .sidebar-info { flex-direction: column; }
}

/* Desktop: 1024px+ */
@media (min-width: 1024px) {
  .dashboard-container { max-width: 1400px; margin: 0 auto; }
  .form-grid { grid-template-columns: repeat(3, 1fr); }
}
```

**Tiempo:** 30 min

---

### **P1.2 - Navigation Mobile Bottom Bar**

**Archivos afectados:** `dashboard/index.blade.php`

**HTML cambios:**
```html
<!-- AGREGAR después del container principal -->

<!-- Bottom Nav Mobile (hidden en desktop) -->
<nav class="mobile-bottom-nav hidden md:flex flex-row items-center justify-around fixed bottom-0 left-0 right-0 bg-[#0f1c32] border-t border-[rgba(255,255,255,0.1)] h-16 z-40">
  <button data-tab="info" class="flex flex-col items-center justify-center flex-1 h-full text-xs" aria-controls="tab-info">
    📋 Info
  </button>
  <button data-tab="products" class="flex flex-col items-center justify-center flex-1 h-full text-xs" aria-controls="tab-products">
    📦 Prod
  </button>
  <button data-tab="services" class="flex flex-col items-center justify-center flex-1 h-full text-xs" aria-controls="tab-services">
    🔧 Serv
  </button>
  <button data-tab="design" class="flex flex-col items-center justify-center flex-1 h-full text-xs" aria-controls="tab-design">
    🎨 Diseño
  </button>
  <button data-tab="more" class="flex flex-col items-center justify-center flex-1 h-full text-xs">
    ⋯ Más
  </button>
</nav>

<!-- Agregar padding-bottom en mobile para no ocultar contenido bajo nav -->
<style>
  @media (max-width: 767px) {
    .tab-content { padding-bottom: 80px; }
  }
</style>
```

**JS para bottom nav:**
```javascript
// Agregar en script Alpine o JS vanilla
document.querySelectorAll('[data-tab]').forEach(btn => {
  btn.addEventListener('click', () => {
    const tabName = btn.dataset.tab;
    // Mostrar tab correspondiente
    // Puede reutilizar la lógica existente de click en tabs
  });
});
```

**Tiempo:** 45 min

---

### **P1.3 - Tablas Responsivas → Cards**

**Archivos afectados:** `dashboard/index.blade.php` (tablas de productos/servicios)

**CSS agregado:**
```css
@media (max-width: 639px) {
  .data-table {
    border-collapse: collapse;
  }

  .data-table thead {
    display: none;
  }

  .data-table tbody tr {
    display: block;
    margin-bottom: 12px;
    background: #0f1c32;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,0.1);
  }

  .data-table td {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .data-table td:last-child {
    border-bottom: none;
  }

  .data-table td::before {
    content: attr(data-label);
    font-weight: 600;
    color: rgba(255,255,255,0.6);
    flex-basis: 100px;
  }
}
```

**HTML cambios** (agregar `data-label` a cada `<td>`):
```html
<!-- Antes -->
<td><img src="..." alt=""></td>
<td>Paquete Digital</td>

<!-- Después -->
<td data-label="Imagen"><img src="..." alt=""></td>
<td data-label="Nombre">Paquete Digital</td>
<td data-label="Precio USD">$599.99</td>
<td data-label="Badge"><span class="badge">HOT</span></td>
<td data-label="Activo"><input type="checkbox" checked></td>
<td data-label="Acciones">
  <button>✏️</button>
  <button>🗑️</button>
</td>
```

**Tiempo:** 60 min

---

### **P1.4 - Touch Targets Mínimos**

**Archivos afectados:** `dashboard/index.blade.php` (sección `<style>`)

**CSS agregado:**
```css
/* Buttons y elementos interactivos */
button, a[role="button"], input[type="button"],
input[type="submit"], input[type="reset"],
.btn, .nav-tab, .toggle {
  min-height: 44px;
  min-width: 44px;
  padding: 10px 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* Close/Action buttons */
.btn-close, .btn-action {
  min-height: 44px !important;
  min-width: 44px !important;
  border-radius: 6px;
}

/* Checkboxes y radios */
input[type="checkbox"],
input[type="radio"] {
  width: 24px;
  height: 24px;
  cursor: pointer;
  accent-color: #2B6FFF;
}

/* Iconos en tabla */
.data-table .action-icon {
  min-height: 44px;
  min-width: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}
```

**Tiempo:** 20 min

---

### **P1.5 - ARIA en Tabs**

**Archivos afectados:** `dashboard/index.blade.php` (nav tabs)

**Cambios HTML:**
```html
<!-- Antes -->
<div class="nav-tabs">
  <button class="nav-tab active" onclick="switchTab('info')">📋 Info</button>
  <button class="nav-tab" onclick="switchTab('products')">📦 Productos</button>
  ...
</div>

<!-- Después -->
<nav role="navigation" aria-label="Secciones del dashboard">
  <ul role="tablist" aria-label="Pestañas principales">
    <li role="presentation">
      <button
        role="tab"
        aria-selected="true"
        aria-controls="tab-info-panel"
        id="tab-info"
        class="nav-tab active"
        onclick="switchTab('info')">
        📋 Info
      </button>
    </li>
    <li role="presentation">
      <button
        role="tab"
        aria-selected="false"
        aria-controls="tab-products-panel"
        id="tab-products"
        class="nav-tab"
        onclick="switchTab('products')">
        📦 Productos
      </button>
    </li>
    ...
  </ul>
</nav>

<!-- Paneles de contenido -->
<div id="tab-info-panel" role="tabpanel" aria-labelledby="tab-info">
  <!-- Contenido Info -->
</div>

<div id="tab-products-panel" role="tabpanel" aria-labelledby="tab-products" hidden>
  <!-- Contenido Productos -->
</div>
```

**JS para navegación por teclado:**
```javascript
document.querySelectorAll('[role="tab"]').forEach((tab, index) => {
  tab.addEventListener('keydown', (e) => {
    let nextTab;
    if (e.key === 'ArrowRight') {
      nextTab = tab.parentElement.nextElementSibling?.querySelector('[role="tab"]');
    } else if (e.key === 'ArrowLeft') {
      nextTab = tab.parentElement.previousElementSibling?.querySelector('[role="tab"]');
    }
    if (nextTab) {
      nextTab.focus();
      nextTab.click();
    }
  });
});
```

**Tiempo:** 45 min

---

### **P2.1 - Security Headers**

**Archivos:** Nuevo `app/Http/Middleware/SecurityHeadersMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $csp = "
            default-src 'self';
            script-src 'self' cdn.jsdelivr.net;
            style-src 'self' 'unsafe-inline' fonts.googleapis.com;
            font-src 'self' fonts.gstatic.com;
            img-src 'self' data:;
            connect-src 'self';
            frame-ancestors 'self';
        ";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
```

**Registrar en `app/Http/Kernel.php`:**
```php
protected $middleware = [
    // ...
    \App\Http\Middleware\SecurityHeadersMiddleware::class,
];
```

**Tiempo:** 30 min

---

### **P2.2 - SRI para SortableJS**

**Opción A: Usar CDN con integrity** (30 min)
```html
<!-- Reemplazar en blade -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
        integrity="sha256-..."
        crossorigin="anonymous"></script>
```

**Opción B: Instalar via npm** (60 min, recomendado)
```bash
npm install sortablejs
```

```javascript
// En resources/js/app.js
import Sortable from 'sortablejs';
window.Sortable = Sortable;
```

**Tiempo:** 30-60 min

---

### **P2.3 - Labels para Inputs**

**Archivos:** `dashboard/index.blade.php` (formularios)

**Patrón a seguir:**
```html
<!-- Antes -->
<label>Nombre del Negocio</label>
<input type="text" name="business_name" />

<!-- Después -->
<label for="business-name">Nombre del Negocio</label>
<input id="business-name" type="text" name="business_name" />
```

**Aplicar a 68 inputs en:**
- Sección Info (name, phone, email, address, etc.)
- Productos CRUD (name, price, badge, etc.)
- Servicios CRUD (name, icon, etc.)
- PIN Change
- Etc.

**Tiempo:** 90 min (repetitivo pero crítico)

---

### **P2.4 - Focus Visible**

**Archivos:** `dashboard/index.blade.php` (sección `<style>`)

```css
/* Focus visible global */
:focus-visible {
  outline: 2px solid #2B6FFF;
  outline-offset: 2px;
  border-radius: 4px;
}

/* Inputs con borde */
input:focus-visible,
textarea:focus-visible,
select:focus-visible {
  border-color: #2B6FFF;
  box-shadow: 0 0 0 3px rgba(43, 111, 255, 0.25);
  outline: none;
}

/* Buttons */
button:focus-visible,
a[role="button"]:focus-visible {
  box-shadow: 0 0 0 3px rgba(43, 111, 255, 0.25);
  outline: none;
}
```

**Tiempo:** 20 min

---

### **P2.5 - HTML lang + Meta Tags**

**Archivo:** `dashboard/index.blade.php` (`<html>` tag)

```html
<!-- Cambiar -->
<html lang="es">
<head>
  <!-- ... meta existentes ... -->

  <!-- Agregar -->
  <meta name="robots" content="noindex, nofollow">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <meta name="description" content="Dashboard de gestión de negocio">
  <meta name="theme-color" content="#07101F">
</head>
```

**Tiempo:** 10 min

---

### **P2.6 - Modales ARIA**

**Archivos:** `dashboard/index.blade.php` (modales CRUD)

```html
<!-- Antes -->
<div class="crud-overlay" style="display: none;">
  <div class="crud-dialog">
    <h2>Agregar Producto</h2>
    ...
  </div>
</div>

<!-- Después -->
<div class="crud-overlay" id="product-modal" style="display: none;">
  <div class="crud-dialog" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <h2 id="modal-title">Agregar Producto</h2>
    <button class="btn-close" aria-label="Cerrar">✕</button>
    ...
    <button aria-label="Cancelar">Cancelar</button>
    <button aria-label="Guardar producto">Guardar</button>
  </div>
</div>
```

**JS agregado:**
```javascript
// Focus trap en modal (con Alpine.js)
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = 'block';
  modal.focus();

  // Trap focus
  const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea');
  const firstElement = focusableElements[0];
  firstElement.focus();
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = 'none';
}

// Cerrar con ESC
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('[role="dialog"]').forEach(m => closeModal(m.id));
  }
});
```

**Tiempo:** 45 min

---

## 📊 Estimación de Esfuerzo

| Fase | Tarea | Tiempo | Prioridad |
|------|-------|--------|-----------|
| **1** | P1.1 - Breakpoints | 30 min | 🔴 |
| **1** | P1.2 - Bottom Nav | 45 min | 🔴 |
| **1** | P1.3 - Tables → Cards | 60 min | 🔴 |
| **1** | P1.4 - Touch Targets | 20 min | 🔴 |
| **1** | P1.5 - ARIA Tabs | 45 min | 🔴 |
| **2** | P2.1 - Security Headers | 30 min | 🟡 |
| **2** | P2.2 - SRI/npm | 30-60 min | 🟡 |
| **2** | P2.3 - Input Labels | 90 min | 🟡 |
| **2** | P2.4 - Focus Visible | 20 min | 🟡 |
| **2** | P2.5 - Meta Tags | 10 min | 🟡 |
| **2** | P2.6 - Modal ARIA | 45 min | 🟡 |

**Total Fase 1 (CRÍTICA):** ~200 min (3.3 horas)
**Total Fase 2 (IMPORTANTE):** ~225 min (3.75 horas)
**Total Fase 3 (MEJORA):** ~120 min (2 horas)

**Tiempo total completo:** ~10.5 horas (distribuido en 3 sprints)

---

## 🚀 Recomendación de Ejecución

1. **Esta semana:** Fase 1 (P1.1-P1.5) → Dashboard funcional en mobile
2. **Próxima semana:** Fase 2 (P2.1-P2.6) → Seguridad + Accesibilidad
3. **Después:** Fase 3 → Optimizaciones UX

¿Deseas que comience con **P1.1 (Breakpoints)** como primer cambio?
