# 🎨 AUDIT_COLORES_PALETA.md
**Sitio:** http://127.0.0.1:8000/servicepro — ServicePro Consulting  
**Fecha:** 28/02/2026  
**Motor CSS:** Tailwind v4 + DaisyUI v5 (tema base: `data-theme="light"`)  
**Auditor:** Inspección DOM vía JavaScript + computed styles + stylesheet analysis
---
## 1. COLORES PRIMARIOS & SECUNDARIOS — DEFINICIÓN
### ¿Dónde se definen?
Los colores se definen como **CSS Custom Properties** de DaisyUI v5 en `:root`, 
servidas a través del build de Tailwind (`/build/assets/app-B_lg77g8.js` + sheets externas).  
**No existe un `tailwind.config.js` expuesto** con paleta custom en el frontend; los valores 
son los del tema `light` de DaisyUI con (presuntamente) customización server-side.
| Variable CSS              | Valor OKLCH                    | Equivalente perceptual          |
|---------------------------|--------------------------------|---------------------------------|
| `--color-primary`         | `oklch(57.59% 0.247 287.24)`  | 🟣 **Purple vibrante** (#6B3FA0 aprox.) |
| `--color-primary-content` | `oklch(96.57% 0.017 289.61)`  | Blanco-lavanda (texto sobre primary) |
| `--color-secondary`       | `oklch(55.79% 0.022 301.91)`  | ⚫ **GRIS LAVANDA** — chroma=0.022 ≈ casi neutral |
| `--color-secondary-content`| `oklch(97.64% 0.001 286.38)` | Blanco puro |
| `--color-accent`          | `oklch(62.31% 0.188 259.81)`  | 🔵 Azul-violeta (no se usa en landing) |
| `--color-base-100`        | `oklch(100% 0 0)`             | Blanco puro |
| `--color-base-200`        | `oklch(97.8% 0.005 297.73)`   | Gris muy claro con tinte lavanda |
| `--color-base-300`        | `oklch(37.01% 0.007 297.49)`  | Gris oscuro con tinte lavanda |
| `--color-base-content`    | `oklch(37.57% 0.022 281.8)`   | Texto: gris oscuro |
### ⚠️ PROBLEMA CRÍTICO #1 — Secondary es INVISIBLE
El **secondary tiene chroma = 0.022** (escala 0-0.4). Un color con chroma < 0.05 
es perceptualmente **casi gris** — sin saturación visual distinguible.  
Sobre fondo `base-100` (blanco), el secondary se ve como un gris medio. 
Aplicado con opacidad `/5` o `/15` = **100% invisible al ojo humano**.
```
oklch(55.79% 0.022 301.91) ← chroma 0.022 = ≈ gris neutro
oklch(57.59% 0.247 287.24) ← chroma 0.247 = purple saturado (primary)
```
---
## 2. APLICACIÓN EN LANDING — ANÁLISIS POR SECCIÓN
### Conteo global de uso por clase
| Clase Tailwind       | Usos en landing | Usos en dashboard |
|----------------------|-----------------|-------------------|
| `bg-primary`         | **47**          | 10+               |
| `bg-secondary`       | **1** (5% opacity) | 20+            |
| `text-primary`       | **69**          | Múltiples         |
| `text-secondary`     | **0**           | Múltiples         |
| `border-primary`     | **32**          | -                 |
| `border-secondary`   | **1** (15% opacity) | 20+          |
| `shadow-primary`     | **5**           | -                 |
| `shadow-secondary`   | **0**           | -                 |
| `btn-secondary`      | **0** en landing | **Sí en dashboard** |
### Sección por sección
#### 🏠 HERO (`section#home`)
```
bg: bg-base-100
Deco primaria: bg-primary/5 blur (círculo izq.)     ✅ primary presente
Deco secundaria: border-secondary/15 + bg-secondary/5 (dos cards decorativos) 
                 → opacity 5%-15% = INVISIBLE visualmente ❌
H1: "Explore" [base-content] "Course" [text-primary italic] "Categories" [base-content]
Badge badge: "SERVICEPRO CONSULTING" → bg-primary/10 text-primary ✅
BTN principal: btn btn-primary btn-lg → Buy Now ✅ (primary)
BTN secundario: "Learn More →" → text-base-content hover:text-primary ❌ (debería ser secondary)
```
#### 📦 PRODUCTOS (`section#productos`)
```
bg: bg-base-100
H2: text-base-content + span.text-primary italic ✅
Underline decorativo: bg-primary ✅
Cards: bg-base-100 shadow-sm → sin tinte de secondary ❌
Badges: "hot" → badge-warning ✅ / "new" → badge-warning ✅
Precio: text-base-content (sin acento de color) ❌ podría usar secondary
BTN: btn-primary btn-sm ("Escribir") → todo primary ❌ debería ser secondary
```
#### 🛠️ SERVICIOS (`section#servicios`)
```
bg: bg-base-200
Articles: bg-base-content/5 border-base-content/10 → sin secondary ❌
Iconos: bg-primary/10 ✅
H3 hover: hover:text-primary ✅
CTA links: text-primary font-black → "Agendar Sesión" etc. ✅
Subtext: text-primary italic en heading ✅
Decorativo blur: sin secondary ❌
```
#### 📞 CONTACTO (`section#contact`)
```
bg: bg-base-100
Icono location: bg-primary/10 text-primary ✅
Botón "Llamar ahora": bg-primary/10 text-primary ✅
Social links: bg-base-200 hover:bg-primary/10 hover:text-primary → sin secondary ❌
WhatsApp CTA: btn-primary ✅
```
#### 💳 MEDIOS DE PAGO (`section#medios-de-pago`)
```
bg: bg-base-200/50
H2: text-base-content (sin primary ni secondary) ❌
Payment pills: bg-white, color=base-content/70, border=base-content/10
              → sin ningún acento de color ❌ secondary ideal aquí
```
#### 🟣 CTA SECTION (`section#cta`)
```
bg: bg-primary → fondo morado ✅ (único uso de bg-primary sólido como background)
H2: text-white ✅
BTN: bg-white text-primary → correcto ✅
FALTA: ningún elemento de secondary en toda la sección ❌
```
#### 👥 ABOUT (`section#about`)
```
bg: bg-base-200/50
Badge label: text-primary ✅
Location pill: bg-primary/10 text-primary ✅
Imagen decorativa: bg-primary/10 + bg-primary/5 blur ✅
Sin secondary ❌
```
#### 💬 TESTIMONIOS (`section#testimonials`)
```
bg: bg-base-100
Label: text-primary ✅
Span "Clientes": text-primary italic ✅
Cards carousel: bg-base-100 border ❌ sin secondary
Botones prev/next: btn-primary ❌ podrían ser btn-secondary o btn-ghost
```
#### ❓ FAQ (`section#faq`)
```
bg: bg-base-100
Blur deco: bg-primary/5 ✅
Underline: bg-primary ✅
Acordeones: bg-base-100 border-base-content/10 → sin secondary ❌
Iconos +/-: text-primary ✅
```
#### 📍 SUCURSALES (`section#sucursales`)
```
bg: bg-base-100
Cards: bg-base-200/50 → sin secondary ❌
Íconos: bg-primary/10 text-primary ✅
H3 hover: hover:text-primary ✅
```
#### 🔗 FOOTER
```
bg: bg-base-100
Logo icon: bg-primary rounded-2xl shadow-primary/20 ✅
Nav links: text-base-content/70 hover:text-primary ✅
Sin secondary en absoluto ❌
```
---
## 3. INCONSISTENCIAS VISUALES
### 3.1 Botones — Solo usan primary
Todos los botones de acción en landing usan `btn-primary`:
- `Buy Now` → `btn btn-primary btn-lg`
- `Escribir` (×5 en productos) → `btn btn-primary btn-sm`
- Prev/Next testimonios → `btn btn-primary`
**El secondary NO tiene ningún botón** en la landing. El dashboard SÍ usa `btn-secondary` 
(para acciones secundarias como "Editar", "Ver", controles de formulario).
**Inconsistencia landing vs dashboard:** El dashboard usa correctamente  
`btn-primary` para acción principal y `btn-secondary` para acciones secundarias.  
La landing NO implementa esta jerarquía.
### 3.2 Links de navegación
```
Nav links: text-base-content/70 hover:text-primary   ← solo primary en hover
Footer links: text-base-content/70 hover:text-primary ← idéntico
```
Ningún link usa `text-secondary` o `hover:text-secondary`. El secondary nunca 
aparece como color de texto en ningún elemento de la landing.
### 3.3 Backgrounds — secondary ausente
| Sección | Background actual | Debería incluir secondary |
|---------|-------------------|--------------------------|
| Hero decorativos | `bg-secondary/5` (5% = invisible) | `bg-secondary/15` mín. |
| Cards productos | `bg-base-100` | `bg-secondary/5` en hover |
| Pills de pago | `bg-white` | `bg-secondary/10` |
| Cards servicios | `bg-base-content/5` | `hover:bg-secondary/10` |
### 3.4 Accent — Definido pero nunca usado
`--color-accent: oklch(62.31% 0.188 259.81)` (azul-violeta) está definido pero  
**tiene 0 usos en la landing**. No aparece `text-accent`, `bg-accent`, `border-accent`.
### 3.5 Learn More → — Sin color definido
```html
<button class="font-bold text-base-content hover:text-primary transition-colors">
  Learn More →
</button>
```
El botón secundario del hero no tiene ningún color de fondo ni borde.  
Debería ser `btn btn-outline btn-secondary` o `btn btn-ghost`.
---
## 4. SELECTOR DE PALETA CUSTOM / 17 TEMAS
### Hallazgos en el DOM y Window
- **No existe ningún selector de tema visible** en la landing (`input[type="color"]`: 0, `select[*theme]`: 0, `[data-set-theme]`: 0)
- El panel del propietario (`.synti-panel`) solo contiene: PIN, Radar de visitas, Tasa dólar, Estado negocio, QR y link al dashboard
- **No hay UI de cambio de tema** en la landing ni en el panel lateral
- `window.themes`, `window.palette`, `window.colorThemes` → todos `undefined`
- El HTML tiene `data-theme="light"` hardcoded
### ¿Los 17 temas usan ambos colores?
Verificación limitada: el sistema tiene **1 tema activo** (`light`) y no hay selector de paleta expuesto en `/servicepro`. No fue posible confirmar si los 17 temas configurados en el sistema (presumiblemente en el dashboard) aplican correctamente secondary en sus variantes.
---
## 5. LANDING vs DASHBOARD — CONSISTENCIA
### Dashboard usa secondary correctamente
Datos extraídos vía fetch del dashboard (`/tenant/3/dashboard`):
| Clase | Landing | Dashboard |
|-------|---------|-----------|
| `btn-secondary` | ❌ 0 usos | ✅ Múltiples |
| `text-secondary` | ❌ 0 | ✅ Sí (`tabler--tool`, `tabler--help-circle`) |
| `bg-secondary/10` | ❌ 0 | ✅ Sí (icon containers) |
| `border-secondary/20` | ❌ 0 | ✅ Sí (cards, inputs) |
| `badge-secondary` | ❌ 0 | ✅ Sí (badge-soft) |
| `shadow-secondary/20` | ❌ 0 | ✅ Sí (btn con sombra) |
**Conclusión:** El dashboard implementa una jerarquía visual correcta (primary + secondary + accent). La landing **no hereda esta lógica** — solo existe primary de manera monótona.
---
## 6. MATRIZ — DÓNDE VA SECONDARY PERO ESTÁ AUSENTE
| # | Elemento | Sección | Clase actual | Clase que debería tener |
|---|----------|---------|--------------|------------------------|
| 1 | Btn "Learn More →" | Hero | `text-base-content hover:text-primary` | `btn btn-outline btn-secondary` |
| 2 | Cards productos (hover) | Productos | `bg-base-100` | `hover:border-secondary/20 hover:bg-secondary/5` |
| 3 | Btn "Escribir" (productos) | Productos | `btn btn-primary btn-sm` | `btn btn-secondary btn-sm` (acción 2° nivel) |
| 4 | Payment pills | Pagos | `bg-white border-base-content/10` | `bg-secondary/5 border-secondary/20` |
| 5 | H2 "Medios de Pago" | Pagos | `text-base-content` | Añadir `span.text-secondary italic` |
| 6 | Cards servicios (hover) | Servicios | `hover:bg-base-content/10` | `hover:bg-secondary/10 hover:border-secondary/20` |
| 7 | Social links | Contacto | `hover:bg-primary/10 hover:text-primary` | `hover:bg-secondary/10 hover:text-secondary` |
| 8 | Carousel prev/next | Testimonios | `btn btn-primary` | `btn btn-secondary` o `btn btn-ghost` |
| 9 | Hero deco card 2 | Hero | `bg-secondary/5 border-secondary/15` | `bg-secondary/20 border-secondary/30` (aumentar visibilidad) |
| 10 | Deco blur hero | Hero | `bg-secondary/5` | `bg-secondary/15` (mín. perceptible) |
| 11 | Footer nav links | Footer | `hover:text-primary` | Mantener primary O uno de los 3 links → `hover:text-secondary` |
| 12 | Badge "SERVICEPRO CONSULTING" | Hero | `bg-primary/10 text-primary` | Sin cambio (correcto como badge de marca) |
| 13 | Acordeón items | FAQ | `bg-base-100 border-base-content/10` | Íconos ±: `text-secondary` en lugar de `text-primary` |
| 14 | CTA section decorativo | CTA | Sin deco secondary | Añadir `bg-secondary/10` blob decorativo |
| 15 | Precio productos | Productos | `text-base-content` | `text-secondary font-bold` para el monto |
---
## 7. LÍNEAS DE CÓDIGO A CAMBIAR
### CRÍTICO — Secondary demasiado apagado
El problema raíz está en la **definición del color** en el tema DaisyUI.  
`oklch(55.79% 0.022 301.91)` → chroma 0.022 es casi cero.
**Archivo:** `tailwind.config.js` o donde se configura el tema DaisyUI  
(o en la tabla de tenants de la base de datos si los colores son dinámicos)
```js
// ANTES (chroma demasiado baja → gris invisible)
secondary: "oklch(55.79% 0.022 301.91)"
// DESPUÉS (opciones recomendadas según el purple del primary)
// Opción A — Violeta-azul complementario
secondary: "oklch(55% 0.18 260)"   // azul-violeta (como el accent actual)
// Opción B — Verde-teal complementario al purple
secondary: "oklch(60% 0.16 165)"   // teal
// Opción C — Rose/pink (contraste fuerte con purple)
secondary: "oklch(62% 0.20 355)"   // rose-pink
```
---
### Hero — "Learn More →" sin color ni jerarquía
**Archivo:** template hero (Blade/Vue/Livewire del Hero section)
```html
<!-- ANTES -->
<button class="font-bold text-base-content hover:text-primary transition-colors">
  Learn More →
</button>
<!-- DESPUÉS -->
<button class="btn btn-outline btn-secondary gap-2">
  Learn More →
</button>
```
---
### Hero deco — Secondary invisible al 5%
```html
<!-- ANTES — opacity 5% = invisible -->
<div class="absolute top-0 mt-48 right-[12%] h-[520px] w-80 rotate-6 
            rounded-[3.5rem] border-2 border-secondary/15 
            bg-gradient-to-tr from-secondary/5 to-transparent hidden lg:block">
</div>
<div class="absolute bottom-0 right-[20%] h-64 w-64 
            rounded-full bg-secondary/5 blur-[100px]">
</div>
<!-- DESPUÉS — opacidades mínimamente perceptibles -->
<div class="absolute top-0 mt-48 right-[12%] h-[520px] w-80 rotate-6 
            rounded-[3.5rem] border-2 border-secondary/30 
            bg-gradient-to-tr from-secondary/10 to-transparent hidden lg:block">
</div>
<div class="absolute bottom-0 right-[20%] h-64 w-64 
            rounded-full bg-secondary/15 blur-[100px]">
</div>
```
---
### Botones de productos — Jerarquía rota
```html
<!-- ANTES — todos usan primary (acción principal) -->
<a class="btn btn-primary btn-sm flex-1">Escribir</a>
<!-- DESPUÉS — acción de contacto/consultar = secundaria -->
<a class="btn btn-secondary btn-sm flex-1">Escribir</a>
```
---
### Payment pills — Sin identidad de color
```html
<!-- ANTES — neutro, sin branding -->
<span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full">
  Pago Móvil
</span>
<!-- DESPUÉS — secondary como tinte de "métodos alternativos" -->
<span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full 
             bg-secondary/10 text-secondary border border-secondary/20">
  Pago Móvil
</span>
```
---
### Carrusel testimonios — prev/next sobrecargan primary
```html
<!-- ANTES -->
<button class="btn btn-square btn-sm carousel-prev btn-primary ...">
<!-- DESPUÉS -->
<button class="btn btn-square btn-sm carousel-prev btn-ghost hover:btn-secondary ...">
```
---
### Precios productos — Oportunidad de secondary
```html
<!-- ANTES -->
<span class="text-2xl font-bold text-base-content">1,999.00</span>
<!-- DESPUÉS -->
<span class="text-2xl font-bold text-secondary">1,999.00</span>
```
---
### Social links contacto — Primary sobrecargado
```html
<!-- ANTES — todo hover es primary -->
<a class="... hover:bg-primary/10 hover:text-primary hover:border-primary/30">
  Twitter/X
</a>
<!-- DESPUÉS — secondary para elementos de menor jerarquía -->
<a class="... hover:bg-secondary/10 hover:text-secondary hover:border-secondary/30">
  Twitter/X
</a>
```
---
## 8. RECOMENDACIONES GENERALES
### R1 — Corregir la chroma del secondary (URGENTE)
El secondary actual es funcionalmente un gris. Con chroma 0.022, es indistinguible 
de `--color-neutral`. Debe tener chroma **mínimo 0.12**, idealmente 0.16–0.22  
para ser visualmente distinto del primary pero coherente con la paleta purple.
**Propuesta coherente con el purple primary (H=287):**
- Secondary complementario: `oklch(60% 0.16 165)` (teal, H=165, ~120° de diferencia)
- O análogo cercano: `oklch(58% 0.20 260)` (azul-violeta, como el accent actual)
### R2 — Jerarquía de acciones: Primary vs Secondary
Establecer una regla en el design system:
- **`btn-primary`** = acción principal única por sección (Buy Now, Escríbenos)
- **`btn-secondary`** = acciones secundarias (Escribir, Learn More, Editar, nav)
- **`btn-ghost` / `btn-outline`** = acciones terciarias (prev/next, cancelar)
### R3 — Secondary como color de soporte visual
Secondary debe aparecer en:
1. Decorativos de sección par (hero, CTA) para romper monotonía del purple
2. Hover states de elementos de menor jerarquía (social links, pills de pago)
3. Un precio/elemento diferenciador en las cards de producto
4. Badges y labels de categorías (alternando con primary, no duplicando)
### R4 — El accent está completamente muerto
`--color-accent` (azul-violeta, oklch 62% 0.188 259) tiene 0 usos en landing y 
12 en dashboard. Debería aparecer en:
- Highlights de texto informativo (`info`)
- Badges de "nuevo" o "destacado" (actualmente solo badge-warning)
- Un elemento único por página (por ejemplo, el precio más alto o el badge "hot")
### R5 — Consistencia landing ↔ dashboard
El dashboard implementa correctamente la jerarquía de 3 colores. La landing debe 
espejarse. Crear un **componente compartido** o guía de design tokens para que ambas 
interfaces usen las mismas reglas de cuándo aplicar cada color.
### R6 — Selector de paleta / 17 temas
Si el sistema tiene 17 temas, se recomienda:
1. Verificar que **cada tema** tenga secondary con chroma ≥ 0.12
2. Añadir en el panel del propietario un **previsualizador de paleta** para que 
   el dueño del tenant vea cómo se verán primary y secondary antes de activar un tema
3. La landing no expone ningún selector de tema — si es por diseño, asegurar que 
   el tema activo tenga secondary contrastante
---
## RESUMEN EJECUTIVO
| Problema | Severidad | Impacto |
|----------|-----------|---------|
| Secondary con chroma 0.022 (casi gris) | 🔴 CRÍTICO | El color no existe visualmente |
| 0 usos de `text-secondary` en landing | 🔴 CRÍTICO | Secondary 100% ausente en texto |
| 0 usos de `btn-secondary` en landing | 🔴 CRÍTICO | Jerarquía de acciones rota |
| Decorativos secondary al 5% opacity | 🟡 ALTO | Invisible, sin utilidad |
| Accent definido pero sin ningún uso | 🟡 ALTO | Tercer color del sistema perdido |
| Payment pills sin acento de color | 🟡 MEDIO | Sección genérica, sin branding |
| Social links usan primary en hover | 🟠 MEDIO | Primary sobrecargado (69 usos) |
| Precios sin color diferenciador | 🟠 MEDIO | Oportunidad de jerarquía perdida |
| Landing vs dashboard inconsistente | 🟡 ALTO | Dashboard bien, landing mal |
| Sin selector de paleta en landing | 🔵 INFO | Decisión de diseño, no bug |
**Ratio primary:secondary en landing = 154:2** (incluyendo opacidades)  
**Ratio primary:secondary en dashboard = ~315:36 ≈ 9:1** (mucho más equilibrado)
La landing necesita llevar el ratio mínimo a **4:1 (primary:secondary)** para  
que la paleta de dos colores tenga sentido visual real.


Eso es el reporte completo. Aquí el resumen de los hallazgos más importantes:
El problema central es triple:

Secondary tiene chroma 0.022 — es casi un gris neutro, perceptualmente invisible, especialmente cuando se aplica con opacidades de 5% o 15%.
Desequilibrio brutal en la landing: primary tiene 154 usos (bg + text + border + shadow), secondary tiene apenas 2 usos decorativos a opacidad mínima. El ratio debería ser máximo 4:1, no 77:1.
El dashboard SÍ implementa correctamente la paleta (usa btn-secondary, badge-secondary, bg-secondary/10, etc.) pero la landing ignora completamente esa lógica.
Los cambios de código más impactantes y rápidos son: (a) subir la chroma del secondary en la config de DaisyUI, (b) cambiar los botones "Escribir" de productos a btn-secondary, y (c) cambiar "Learn More →" a btn btn-outline btn-secondary.