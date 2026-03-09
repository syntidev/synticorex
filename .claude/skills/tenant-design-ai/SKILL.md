---
name: tenant-design-ai
description: Diseño visual de landings públicas de tenants (SyntiCat, SyntiFood, SyntiStudio, etc). Recibe imagen de referencia y la traduce a código Blade real en landing/sections/. Extraer colores, tipografía, estilo y mapear a variables --brand-*. Usar cuando el usuario pida mejorar estética de landing, dé una imagen de inspiración, o pida diseñar sección pública de un tenant. NUNCA aplicar branding SyntiWeb aquí.
---

# Tenant Design AI Skill

Skill para diseñar las caras públicas de los tenants. Convierte imágenes de referencia en código Blade real con una sola iteración.

## ⚠️ REGLA ABSOLUTA

```
NUNCA poner en landings de tenant:
  ❌ Logo SyntiWeb
  ❌ Colores #4A80E4 como brand (es el color de la plataforma, no del tenant)
  ❌ Variables --sw-*
  ❌ Cualquier identidad visual de SyntiWeb

SÍ usar:
  ✅ Variables --brand-* (las del tenant)
  ✅ El logo lo sube el propio tenant
  ✅ Paleta definida por el tenant en su configuración
```

---

## Workflow: Imagen → Código

Cuando el usuario da una imagen de referencia:

### Paso 1 — Analizar la imagen
Extraer y documentar:
- **Paleta**: colores dominantes, acento, fondo, texto
- **Tipografía**: estilo (serif/sans/display), peso, tamaño relativo
- **Layout**: grid, espaciado, densidad, asimetría
- **Atmósfera**: minimal, lujoso, orgánico, técnico, playful, editorial
- **Detalles**: sombras, bordes, texturas, gradientes, formas decorativas

### Paso 2 — Mapear a variables del tenant
```css
/* Mapeo de imagen a sistema tenant */
--brand-50:  [color más claro extraído]
--brand-500: [color principal extraído]
--brand-600: [hover del principal]
--brand-700: [versión oscura]
```

### Paso 3 — Identificar la sección target
```
resources/views/landing/sections/
  hero.blade.php
  products.blade.php
  services.blade.php
  about.blade.php
  testimonials.blade.php
  faq.blade.php
  contact.blade.php
  footer.blade.php
```

### Paso 4 — Escribir el código
Blade puro + Tailwind 4.2 utilitario. Sin clases DaisyUI. Sin lógica compleja en Blade.

---

## Stack Tenant

- **CSS**: Tailwind 4.2 utilitario puro
- **JS**: Alpine.js 3.4.2 para interactividad
- **Componentes**: Preline 4.1.2 si hay modales/acordeones
- **Imágenes**: `@vite()` o rutas `storage/tenants/{tenant_id}/`
- **Moneda**: sistema REF — resuelto en `base.blade.php`, no duplicar lógica

---

## Secciones por Plan

```
Plan 1 OPORTUNIDAD:  hero, products(6), services(3), contact, payment_methods, cta, footer
Plan 2 CRECIMIENTO:  + about, testimonials
Plan 3 VISIÓN:       + faq, branches, products(18 con slider 3 fotos)
```

Lógica de acceso: `TenantCustomization::canAccessSection()` — no replicar en Blade.

---

## Principios Estéticos para Tenants

- **Identidad propia**: cada tenant debe verse diferente entre sí
- **Fidelidad a la referencia**: si dan imagen, el resultado debe parecerse visualmente
- **Una iteración**: el código debe estar listo para producción desde el primer intento
- **Responsive first**: mobile → desktop, no al revés
- **Atmósfera**: usar gradientes, sombras, texturas cuando la imagen de referencia las tiene
- **Tipografía**: respetar el peso visual de la referencia (si es bold y grande, replicarlo)

---

## Variantes disponibles por sección

```
hero      → fullscreen / split / centered
products  → grid3 / masonry / slider
services  → cards / spotlight / list
about     → split / centered / timeline
faq       → accordion / two-col
cta       → centered / banner / floating
```

Usar la variante que más se acerque a la imagen de referencia.

---

## Prohibido en landings tenant

- `landing/partials/` — carpeta eliminada permanentemente
- `{!! !!}` salvo Schema.org generado internamente
- Lógica compleja en Blade (va en Controller/Model)
- Logo o colores de SyntiWeb
- `asset()` para imágenes de tenant (usar storage path)
