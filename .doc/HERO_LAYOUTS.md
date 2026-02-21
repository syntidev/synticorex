# 🎯 Hero Layouts Documentation
### SYNTIweb - Landing V2 FlyonUI

Created: February 20, 2026  
Workspace: `C:\laragon\www\synticorex`

---

## 📋 Overview

Se han creado **4 layouts profesionales de hero section** basados en FlyonUI, cada uno optimizado para diferentes tipos de negocios y estrategias de conversión.

### Archivos Creados

```
resources/views/landing-v2/partials/
├── hero-fullscreen.blade.php  (renombrado de hero.blade.php)
├── hero-split.blade.php       (NUEVO)
├── hero-gradient.blade.php    (NUEVO)
└── hero-cards.blade.php       (NUEVO)
```

### Rutas de Testing

Accede a cada layout en: `http://synticorex.test/test/`

- **Index**: `/test/` - Índice con enlaces a todos los layouts
- **Fullscreen**: `/test/hero-fullscreen`
- **Split**: `/test/hero-split`
- **Gradient**: `/test/hero-gradient`
- **Cards**: `/test/hero-cards`

Todas las rutas cargan el Tenant ID 1 con su customización completa.

---

## 🎨 Layout 1: Hero Fullscreen

**Archivo**: `hero-fullscreen.blade.php`  
**Estilo**: Background fullscreen con contenido centrado

### Características

- ✅ Imagen de fondo a pantalla completa con overlay oscuro
- ✅ Contenido centrado verticalmente y horizontalmente
- ✅ Badge animado con tagline o año de fundación
- ✅ Título grande con SVG underline decorativo (gradiente animado)
- ✅ Descripción con límite de 200 caracteres
- ✅ CTA principal (WhatsApp → Teléfono → Contacto)
- ✅ Imagen adicional en la parte inferior (primer producto)

### Ideal Para

- Restaurantes, hoteles, spas
- Negocios que quieren impacto visual inmediato
- Landing pages con foco en una sola acción principal

### Especificaciones Técnicas

```php
// Variables requeridas
$tenant              // Modelo Tenant
$customization       // TenantCustomization (hero_filename)
$products            // Collection (para imagen inferior)

// Dimensiones
min-height: 70vh
padding-top: 10rem (pt-40)
max-width: 1280px (max-w-7xl)
```

### Soporte Multi-Hero

- **Hero Main**: Usado como background principal
- **Hero Secondary**: No utilizado en este layout
- **Hero Tertiary**: No utilizado en este layout

---

## ⚡ Layout 2: Hero Split

**Archivo**: `hero-split.blade.php`  
**Estilo**: 50% contenido / 50% imagen (dos columnas)

### Características

- ✅ Grid de 2 columnas en desktop (lg:grid-cols-2)
- ✅ Contenido a la izquierda con alineación responsiva
- ✅ Imagen principal a la derecha con decoración blur
- ✅ Badge flotante "Calidad Garantizada" sobre la imagen
- ✅ Imagen secundaria flotante (esquina inferior izquierda)
- ✅ Stats opcionales (años de experiencia + productos)
- ✅ Dos CTAs (primario + outlined)

### Ideal Para

- Empresas B2B, consultorías, agencias
- Negocios que quieren mostrar profesionalismo
- Landing pages con múltiples puntos de conversión

### Especificaciones Técnicas

```php
// Variables requeridas
$tenant                           // business_name, tagline, slogan
$customization                    // hero_main_filename, hero_secondary_filename
$products                         // Para contar cantidad disponible

// Layout
Columna izquierda: max-w-xl (contenido)
Columna derecha: Imagen 500px mobile / 600px desktop
Gap: 3rem (gap-12)
```

### Soporte Multi-Hero

- **Hero Main**: Imagen principal derecha (fallback: hero_filename)
- **Hero Secondary**: Imagen flotante inferior izquierda
- **Hero Tertiary**: No utilizado

### Elementos Únicos

```html
<!-- Decoración de fondo blur -->
<div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-3xl blur-3xl -z-10"></div>

<!-- Badge flotante -->
<div class="absolute top-6 right-6 bg-base-100 rounded-xl shadow-lg p-4">
    <div class="flex items-center gap-2">
        <span class="text-2xl">⭐</span>
        <div>
            <div class="font-bold text-base-content">Calidad</div>
            <div class="text-xs text-base-content/60">Garantizada</div>
        </div>
    </div>
</div>
```

---

## 🌈 Layout 3: Hero Gradient

**Archivo**: `hero-gradient.blade.php`  
**Estilo**: Fondo degradado animado con imágenes flotantes

### Características

- ✅ Gradiente animado de 3 colores (primary → secondary → accent)
- ✅ Patrón decorativo SVG en background (opcional)
- ✅ 3 imágenes flotantes con animación independiente
- ✅ Texto en color blanco con efectos drop-shadow
- ✅ CTAs con backdrop blur y efectos hover scale
- ✅ Features destacados con checkmarks
- ✅ Onda decorativa SVG inferior
- ✅ Puntos flotantes con animación pulse

### Ideal Para

- Startups tecnológicas, apps, SaaS
- Negocios modernos y creativos
- Landing pages con diseño diferenciador

### Especificaciones Técnicas

```php
// Variables requeridas
$tenant                    // business_name, slogan, tagline
$customization             // hero_main/secondary/tertiary_filename

// Animaciones personalizadas
@keyframes gradient-xy     // Background 15s ease infinite
@keyframes float           // Flotación 6s ease-in-out
@keyframes float-delay-1   // Flotación con delay 1s
@keyframes float-delay-2   // Flotación con delay 2s
```

### Soporte Multi-Hero (COMPLETO)

- **Hero Main**: Imagen flotante principal (top-right, 320x320px)
- **Hero Secondary**: Imagen flotante media (bottom-left, 256x256px)
- **Hero Tertiary**: Imagen flotante pequeña (bottom-right, 192x192px)

### Elementos Únicos

```html
<!-- Gradiente animado -->
<div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent animate-gradient-xy"></div>

<!-- Overlay legibilidad -->
<div class="absolute inset-0 bg-base-content/20"></div>

<!-- Features destacados -->
<div class="flex flex-wrap gap-6">
    <div class="flex items-center gap-2 text-base-100">
        <span class="icon-[tabler--check] size-6 text-success"></span>
        <span>Calidad Premium</span>
    </div>
    <!-- ... más features -->
</div>

<!-- Onda inferior -->
<svg viewBox="0 0 1440 120" class="w-full">
    <path d="M0,64L48,69.3C96,75..." fill="currentColor" class="text-base-100"></path>
</svg>
```

### Paleta de Colores

El gradiente usa las variables CSS de FlyonUI:
- `from-primary` (color principal del tenant)
- `via-secondary` (color secundario)
- `to-accent` (color de acento)

---

## 🎴 Layout 4: Hero Cards

**Archivo**: `hero-cards.blade.php`  
**Estilo**: Hero background + 3 cards destacadas sobrepuestas

### Características

- ✅ Hero background 70-75vh con overlay gradiente
- ✅ Contenido centrado con título épico (text-7xl)
- ✅ 3 cards sobrepuestas con margin negativo (-mt-32)
- ✅ Card central destacada con scale 105% y badge
- ✅ Efectos hover: translate-y, shadow, scale en imágenes
- ✅ Stats bar opcional inferior (4 métricas)
- ✅ Sistema inteligente de fallback para imágenes de cards

### Ideal Para

- E-commerce, marketplaces, catálogos
- Negocios con múltiples servicios/productos
- Landing pages orientadas a conversión múltiple

### Especificaciones Técnicas

```php
// Variables requeridas
$tenant                    // business_name, slogan, whatsapp
$customization             // hero_main/secondary/tertiary_filename
$products                  // Collection (para imágenes de cards)

// Layout
Hero height: 70vh - 75vh
Cards: grid md:grid-cols-3
Gap: 1.5rem (gap-6) lg:2rem (gap-8)
Overlap: -mt-32 lg:-mt-40
```

### Soporte Multi-Hero + Productos

```php
// Sistema de fallback inteligente para cards
Card 1: hero_secondary ➜ products[0] ➜ Unsplash
Card 2: hero_tertiary ➜ products[1] ➜ Unsplash
Card 3: products[2] ➜ Unsplash
```

### Estructura de Cards

```html
<div class="card bg-base-100 shadow-2xl hover:shadow-primary/20 hover:-translate-y-2 transition-all">
    <figure class="h-48 overflow-hidden">
        <img src="..." class="w-full h-full object-cover hover:scale-110 transition-transform">
    </figure>
    <div class="card-body">
        <div class="text-4xl mb-2">🎯</div>  <!-- Emoji decorativo -->
        <h3 class="card-title">Calidad Premium</h3>
        <p class="text-base-content/70">Descripción de la característica...</p>
        <div class="card-actions justify-end">
            <a href="#about" class="btn btn-sm btn-primary">
                Conocer más <span class="icon-[tabler--arrow-right]"></span>
            </a>
        </div>
    </div>
</div>
```

### Card Central (Destacada)

La segunda card tiene características especiales:
- `md:scale-105` - 5% más grande en desktop
- Badge "Destacado" en esquina superior derecha
- CTA directo a WhatsApp (si está disponible)

### Stats Bar

Barra opcional con 4 métricas:
1. Años de experiencia (calculado desde `created_at`)
2. Productos disponibles (`$products->count()`)
3. Satisfacción garantizada (100% - hardcoded)
4. Atención disponible (24/7 - hardcoded)

---

## 🔧 Implementación en Landing V2

### Configurar Layout Dinámico

Edita `resources/views/landing-v2/base.blade.php`:

```blade
{{-- Hero Section (dinámico según hero_layout) --}}
@switch($customization->hero_layout)
    @case('split')
        @include('landing-v2.partials.hero-split')
        @break
    @case('gradient')
        @include('landing-v2.partials.hero-gradient')
        @break
    @case('cards')
        @include('landing-v2.partials.hero-cards')
        @break
    @default
        @include('landing-v2.partials.hero-fullscreen')
@endswitch
```

### Variables Disponibles en TenantCustomization

```php
// Campos de base de datos (migration 2026_02_20_120000)
$customization->hero_main_filename        // varchar(255) nullable
$customization->hero_secondary_filename   // varchar(255) nullable
$customization->hero_tertiary_filename    // varchar(255) nullable
$customization->hero_layout               // enum('fullscreen','split','gradient','cards')

// Métodos helper (ver app/Models/TenantCustomization.php)
$customization->getHeroMainUrl()          // string - URL completa con fallback
$customization->getHeroSecondaryUrl()     // ?string - URL o null
$customization->getHeroTertiaryUrl()      // ?string - URL o null
$customization->canUseMultipleHeros()     // bool - false si plan = 'oportunidad'
$customization->maxHeroImages()           // int - 1, 2 o 3 según plan
```

### Límites por Plan

| Plan | Hero Images | Método |
|------|------------|---------|
| **OPORTUNIDAD** | 1 (solo main) | `maxHeroImages()` = 1 |
| **CRECIMIENTO** | 2 (main + secondary) | `maxHeroImages()` = 2 |
| **VISIÓN** | 3 (main + secondary + tertiary) | `maxHeroImages()` = 3 |

---

## 📊 Comparación de Layouts

| Feature | Fullscreen | Split | Gradient | Cards |
|---------|-----------|-------|----------|-------|
| **Multi-Hero** | ❌ (solo 1) | ⚠️ (usa 2) | ✅ (usa 3) | ⚠️ (usa 2-3) |
| **Impacto Visual** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Conversión** | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Profesionalismo** | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Mobile UX** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Velocidad Carga** | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Animaciones** | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

---

## 🎯 Recomendaciones por Tipo de Negocio

### Restaurantes & Food Service
🏆 **Recomendado**: `hero-fullscreen` o `hero-cards`
- Fullscreen: Foto grande del plato estrella
- Cards: Mostrar 3 especialidades destacadas

### Empresas B2B & Consultorías
🏆 **Recomendado**: `hero-split`
- Profesional, claro, con múltiples CTAs
- Espacio para texto explicativo

### Startups & Tech
🏆 **Recomendado**: `hero-gradient`
- Moderno, diferenciador
- Animaciones llamativas

### E-commerce & Retail
🏆 **Recomendado**: `hero-cards`
- Múltiples productos destacados
- CTAs directos en cada card

---

## 🚀 Testing & QA

### Checklist de Pruebas

Para cada layout, verificar:

- [ ] **Responsividad**: Mobile (375px) / Tablet (768px) / Desktop (1440px)
- [ ] **Imágenes**: Carga correcta con fallback a Unsplash
- [ ] **CTAs**: WhatsApp → Phone → Anchor funcionando
- [ ] **Texto**: Truncado con `Str::limit()` sin romper layout
- [ ] **Performance**: PageSpeed score > 80
- [ ] **Animaciones**: Smooth 60fps sin lag
- [ ] **Accesibilidad**: Alt text, ARIA labels, contrast ratio

### Comandos de Testing

```bash
# Abrir navegador en rutas de test
start http://synticorex.test/test/

# Ver logs en tiempo real
php artisan tail

# Limpiar caché de vistas
php artisan view:clear

# Optimizar imágenes de tenant 1
php artisan storage:link
```

---

## 📝 Próximos Pasos

### Implementar en Dashboard

1. **Crear selector de layouts** en dashboard de tenant
2. **Upload múltiple de hero images** según plan
3. **Preview en vivo** antes de publicar
4. **A/B testing** para comparar conversiones

### Mejoras Futuras

- [ ] Soporte para video background (hero-fullscreen)
- [ ] Parallax scrolling (hero-gradient)
- [ ] Carrusel de heros rotativos
- [ ] Lazy loading optimizado para múltiples imágenes
- [ ] WebP/AVIF automático con fallback

---

## 📚 Referencias

- **FlyonUI Docs**: https://flyonui.com/docs
- **Hero Blocks**: https://flyonui.com/blocks/marketing-ui/hero-section
- **Tailwind CSS**: https://tailwindcss.com
- **Iconify Icons**: https://icon-sets.iconify.design/tabler/

---

**Creado por**: GitHub Copilot (Claude Sonnet 4.5)  
**Fecha**: 20/02/2026  
**Proyecto**: SYNTIweb Landing V2 - Multitenant System
