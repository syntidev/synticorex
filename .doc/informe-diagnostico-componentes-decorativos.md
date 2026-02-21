# 📋 INFORME TÉCNICO: DIAGNÓSTICO DE COMPONENTES DECORATIVOS

**Fecha:** 21 de febrero de 2026  
**Arquitecto:** GitHub Copilot (Modo Senior Laravel + Tailwind)  
**Sistema:** SynticoreX Multi-Tenant Landing Pages  
**CSS Build:** 211.99 KB (FlyonUI 2.4.1 + Tailwind CSS v4/v3 híbrido)

---

## 1️⃣ MAPEO DE RUTAS Y ARQUITECTURA

### ✅ **NO HAY "FRANKENSTEIN"** entre carpetas landing y landing-v2

**Archivo de entrada analizado:** `resources/views/landing-v2/test-hero.blade.php`

```php
// LÍNEA 12-27: Jerarquía de includes
@include('landing-v2.partials.navbar')

@switch($heroLayout)
    @case('split')
        @include('landing-v2.partials.hero-split')
    @case('gradient')
        @include('landing-v2.partials.hero-gradient')
    @case('cards')
        @include('landing-v2.partials.hero-cards')  // ❌ ARCHIVO NO EXISTE
    @default
        @include('landing-v2.partials.hero-fullscreen')
@endswitch
```

**Comparativa:**

| Archivo | Carpeta Base | Navbar | Hero | Footer | ¿Mezcla? |
|---------|--------------|--------|------|--------|----------|
| `landing/base.blade.php` | landing | landing.partials.header | landing.partials.hero | landing.partials.footer | ❌ No |
| `landing-v2/test-hero.blade.php` | landing-v2 | landing-v2.partials.navbar | landing-v2.partials.hero-* | N/A | ❌ No |

**Conclusión:** Cada archivo usa **exclusivamente** componentes de su propia carpeta. No se detecta mezcla entre `landing/` y `landing-v2/`.

---

## 2️⃣ CONFLICTO Z-INDEX: "¿QUIÉN ESTÁ TAPANDO A QUIÉN?"

### 🔴 **PROBLEMA CRÍTICO: Clases Tailwind Inválidas**

**Archivo:** `resources/views/landing-v2/partials/hero-fullscreen.blade.php` (Línea 19)

```php
// LÍNEA 19: ❌ z-1 NO ES UNA CLASE TAILWIND VÁLIDA
<h1 class="text-base-content z-1 relative text-5xl font-bold...">

// LÍNEA 30: ❌ -z-1 NO ES UNA CLASE TAILWIND VÁLIDA
<svg class="-z-1 left-25 absolute -bottom-1.5...">
```

**Duplicado en:** `resources/views/landing-v2/partials/hero.blade.php` (Líneas 19-30)

### 📌 Tailwind CSS solo reconoce estas clases z-index estándar:
- `z-0` → z-index: 0
- `z-10` → z-index: 10
- `z-20` → z-index: 20
- `z-30, z-40, z-50` → progresión estándar
- `z-auto` → z-index: auto

### ⚠️ Para valores arbitrarios debes usar corchetes:
```html
✅ CORRECTO: class="z-[1]"     → Compila a z-index: 1
✅ CORRECTO: class="z-[-1]"    → Compila a z-index: -1
❌ INCORRECTO: class="z-1"     → NO COMPILA (clase ignorada)
❌ INCORRECTO: class="-z-1"    → NO COMPILA (clase ignorada)
```

---

### 🛡️ Análisis de Contextos de Apilamiento

**Navbar:** `resources/views/landing-v2/partials/navbar.blade.php` (Línea 9)
```php
<header class="fixed top-0 z-10 w-full border-b bg-base-100">
```
✅ **z-10** es válido. Crea contexto de apilamiento SUPERIOR.

**Hero Gradient:** `resources/views/landing-v2/partials/hero-gradient.blade.php` (Línea 19)
```php
<div class="space-y-8 text-base-100 relative z-10">
```
🔴 **COLISIÓN:** `z-10` compite con navbar. Si el contenedor padre tiene `relative`, este z-10 puede renderizarse ENCIMA del navbar.

**Hero Split (decoración blur):** `resources/views/landing-v2/partials/hero-split.blade.php` (Línea 86)
```php
<div class="absolute inset-0 bg-gradient-to-br from-primary/20... blur-3xl -z-10"></div>
```
✅ **`-z-10`** es válido (Tailwind tiene clases negativas con guion). Correctamente en el fondo.

**Componente Decorativo NO USADO:** `resources/views/components/ui/decorative-background.blade.php` (Línea 1)
```php
<div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
```
✅ **`-z-10`** es válido PERO **este componente NO se está incluyendo** en ningún archivo de landing-v2. Por eso no se ve.

---

## 3️⃣ AUDITORÍA TAILWIND: Configuración de Compilación

**Archivo:** `tailwind.config.js` (Líneas 6-18)

```javascript
content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',  // ✅ INCLUYE landing-v2/**
    './node_modules/flyonui/dist/js/*.js',
],

plugins: [
    require('flyonui')
],

flyonui: {
    themes: ["light", "dark", "black", "claude", "corporate", "ghibli", "gourmet", 
             "luxury", "mintlify", "pastel", "perplexity", "shadcn", "slack", 
             "soft", "spotify", "valorant", "vscode"]  // ✅ 17 temas FlyonUI
}
```

### ✅ **Veredicto:**
- **`'./resources/views/**/*.blade.php'`** → SÍ incluye `landing-v2/**/*.blade.php`
- **FlyonUI está correctamente cargado:** Build generó 211.99 KB con banner `/*! 🚀 flyonui 2.4.1 */`
- **Clases de FlyonUI disponibles:** `bg-primary`, `text-base-content`, etc. funcionan correctamente

### ⚠️ **PERO:** Las clases inválidas `z-1` y `-z-1` (sin corchetes) NO compilan
Tailwind las ignora silenciosamente. Los elementos afectados no tienen z-index aplicado, causando que el navbar tape componentes que deberían estar en primer plano.

---

## 4️⃣ PROPUESTA: JERARQUÍA Z-INDEX ESTANDARIZADA

| Capa | Z-Index | Clase Tailwind | Uso | Ejemplos |
|------|---------|----------------|-----|----------|
| **Fondo Decorativo** | -10 | `-z-10` | Blurs, gradientes, patrones | Blur decoration, background patterns |
| **Fondo Hero** | -1 | `z-[-1]` | Overlays, SVG waves | SVG decorativo del título |
| **Contenido Base** | 0 | `z-0` | Cards, texto, imágenes | Productos, servicios, about |
| **Contenido Elevado** | 1 | `z-[1]` | Títulos destacados, badges | H1 con SVG decorativo debajo |
| **Contenido Interactivo** | 10 | `z-10` | Hero CTAs, floats | Botones hero, imágenes flotantes |
| **Navegación Fixed** | 50 | `z-50` | Navbar, header | Header fixed top |
| **Modales/Overlays** | 100 | `z-[100]` | Lightbox, sidebars | Menú hamburguesa expandido |
| **Notificaciones** | 200 | `z-[200]` | Toasts, alerts | WhatsApp button flotante |

### 📝 **Cambios Recomendados:**

**1. Corregir clases inválidas en hero-fullscreen.blade.php y hero.blade.php:**
```diff
- <h1 class="z-1 relative...">
+ <h1 class="z-[1] relative...">

- <svg class="-z-1 absolute...">
+ <svg class="z-[-1] absolute...">
```

**2. Elevar navbar para evitar colisiones:**
```diff
- <header class="fixed top-0 z-10 w-full...">
+ <header class="fixed top-0 z-50 w-full...">
```

**3. Ajustar hero-gradient contenido:**
```diff
- <div class="relative z-10">
+ <div class="relative z-[1]">
```

**4. (Opcional) Incluir decorative-background.blade.php si se desea usar:**
```blade
{{-- En hero-fullscreen.blade.php o hero-split.blade.php --}}
<x-ui.decorative-background />
```

---

## 🔍 PROBLEMAS ADICIONALES DETECTADOS

### ❌ **Archivo faltante:** hero-cards.blade.php

**Ubicación esperada:** `resources/views/landing-v2/partials/hero-cards.blade.php`  
**Estado:** ❌ NO EXISTE (solo existe `old_hero-cards.blade.php`)  
**Impacto:** Cuando `$heroLayout === 'cards'`, test-hero.blade.php **fallará** con error 500 al intentar `@include('landing-v2.partials.hero-cards')`

**Acción requerida:**
```bash
# Opción 1: Renombrar el archivo viejo
mv resources/views/landing-v2/partials/old_hero-cards.blade.php \
   resources/views/landing-v2/partials/hero-cards.blade.php

# Opción 2: Crear uno nuevo basado en split/gradient
```

---

## 📊 RESUMEN EJECUTIVO

| Aspecto | Estado | Detalles |
|---------|--------|----------|
| **Mezcla landing/landing-v2** | ✅ **NO HAY** | Cada carpeta es independiente |
| **Tailwind content paths** | ✅ **CORRECTO** | landing-v2 está incluido |
| **FlyonUI compilación** | ✅ **211.99 KB** | Todos los temas cargados |
| **Clases z-index inválidas** | 🔴 **4 ERRORES** | `z-1` y `-z-1` sin corchetes |
| **Navbar tapando contenido** | 🟡 **POSIBLE** | z-10 colisiona con hero-gradient |
| **Componente decorativo** | 🟡 **NO USADO** | decorative-background.blade.php existe pero no se incluye |
| **hero-cards.blade.php** | ❌ **FALTANTE** | Archivo referenciado pero no existe |

---

## ✅ CONCLUSIÓN

**Tu problema NO es "Frankenstein"**, es una combinación de:

1. **Clases Tailwind mal formadas** (`z-1` en lugar de `z-[1]`)  
   → Tailwind las ignora → Los elementos no tienen z-index → Navbar tapa todo

2. **Colisión de z-index** entre navbar (z-10) y hero-gradient contenido (z-10)  
   → Ambos compiten en la misma capa → Comportamiento impredecible

3. **Componente decorativo huérfano**  
   → `decorative-background.blade.php` existe pero nadie lo incluye

4. **Archivo faltante**  
   → `hero-cards.blade.php` no existe pese a ser referenciado

**¿Por qué no se ven tus decorativos?**  
Porque las clases `z-1` y `-z-1` **no están compilando**. El CSS generado no contiene esas reglas. Los elementos aparecen con `z-index: auto` (valor por defecto del navegador), causando que el navbar `fixed z-10` los tape.

---

## 🛠️ PLAN DE CORRECCIÓN (NO EJECUTADO AÚN)

### Fase 1: Correcciones Críticas
1. Reemplazar `z-1` por `z-[1]` en hero-fullscreen.blade.php (línea 19)
2. Reemplazar `z-1` por `z-[1]` en hero.blade.php (línea 19)
3. Reemplazar `-z-1` por `z-[-1]` en hero-fullscreen.blade.php (línea 30)
4. Reemplazar `-z-1` por `z-[-1]` en hero.blade.php (línea 30)

### Fase 2: Prevención de Colisiones
5. Cambiar navbar de `z-10` a `z-50` en navbar.blade.php
6. Cambiar hero-gradient contenido de `z-10` a `z-[1]`

### Fase 3: Archivos Faltantes
7. Renombrar o crear hero-cards.blade.php

### Fase 4: Build & Test
8. Ejecutar `npm run build`
9. Verificar que el CSS incluya las clases arbitrarias
10. Probar visualmente en navegador

---

## 📁 ARCHIVOS AFECTADOS

```
resources/views/landing-v2/partials/
├── navbar.blade.php (z-10 → z-50)
├── hero.blade.php (z-1 → z-[1], -z-1 → z-[-1])
├── hero-fullscreen.blade.php (z-1 → z-[1], -z-1 → z-[-1])
├── hero-gradient.blade.php (z-10 → z-[1])
└── hero-cards.blade.php (❌ FALTANTE - requiere creación)

resources/views/components/ui/
└── decorative-background.blade.php (existe pero no se usa)
```

---

## 🚀 PRÓXIMOS PASOS

Esperando confirmación del usuario para:
- [ ] Aplicar correcciones de z-index
- [ ] Crear/renombrar hero-cards.blade.php
- [ ] Compilar CSS con cambios
- [ ] Probar en navegador

---

**Generado por:** GitHub Copilot  
**Fecha de generación:** 21 de febrero de 2026  
**Versión del sistema:** Laravel 12 + Tailwind v4/v3 + FlyonUI 2.4.1
