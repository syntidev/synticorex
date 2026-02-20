# 🎨 SYNTI Design System - Component Library

## Sistema Visual Profesional para SYNTIweb (v1.0)

---

## 📋 Índice de Componentes

| Componente | Tipo | Estados | Ubicación |
|-----------|------|---------|-----------|
| Hero | Section | default, with-image | hero.blade.php |
| Product Card | Card | default, hover, selected | product-card.blade.php |
| Service Card | Card | default, hover | services.blade.php |
| Section Header | Header | default, centered | about/cta |
| Button | Control | primary, secondary, success, danger, ghost | Global |
| Badge | Label | success, danger, primary | Global |
| Grid Layout | Layout | 1col, 2col, 3col, responsive | products/services |
| CTA Section | Section | default, with-image | cta.blade.php |

---

## 🎯 PATRONES ESTÁNDAR

### 1️⃣ HERO SECTION (fullscreen)

**Archivo:** `hero.blade.php`

```html
<section 
    class="relative min-h-screen flex items-center justify-center overflow-hidden {{ !$customization?->hero_filename ? 'bg-gradient-to-br from-primary-500 to-primary-700' : '' }}"
    @if($customization?->hero_filename)
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}'); background-size: cover; background-position: center; background-attachment: fixed;"
    @endif
>
    <div class="relative z-10 text-center px-4 py-20 max-w-5xl mx-auto">
        <!-- Contenido centrado -->
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
        <!-- SVG arrow -->
    </div>
</section>
```

**Variantes:**
- ✅ Con imagen de fondo + overlay gradient
- ✅ Fallback a gradient color
- ✅ Logo circular con ring
- ✅ Status badge con animación
- ✅ CTA buttons dual (primary + success)
- ✅ Scroll indicator animado

---

### 2️⃣ PRODUCT/SERVICE CARD

**Archivo:** `product-card.blade.php`, `services.blade.php`

```html
<div class="synti-card-hover group relative">
    <!-- Imagen/Icon -->
    <div class="aspect-square mb-4 overflow-hidden rounded-synti-lg">
        <img src="{{ $image }}" alt="{{ $name }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
    </div>
    
    <!-- Contenido -->
    <div>
        <h3 class="text-lg md:text-xl font-semibold text-neutral-900 mb-2">{{ $name }}</h3>
        <p class="text-sm md:text-base text-neutral-600 mb-4">{{ $description }}</p>
        
        <!-- Precio (si aplica) -->
        @if($price)
            <p class="text-xl md:text-2xl font-bold text-primary-600 mb-4">${{ $price }}</p>
        @endif
        
        <!-- CTA -->
        <a href="{{ $link }}" class="synti-btn-primary text-sm">
            <svg class="w-4 h-4"><!-- Icon --></svg>
            {{ $cta_text ?? 'Ver más' }}
        </a>
    </div>
</div>
```

**Estados:**
- ✅ Default (static)
- ✅ Hover (lift + shadow increase)
- ✅ Selected (border + ring)

---

### 3️⃣ SECTION HEADER (títulos de secciones)

**Archivo:** `products.blade.php`, `services.blade.php`, `about.blade.php`, `cta.blade.php`

```html
<div class="synti-container mx-auto mb-12 md:mb-16">
    <div class="synti-section-header">
        <h2 class="synti-section-title">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="synti-section-subtitle">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
```

**CSS Synti (app.css):**
```css
.synti-section-title {
    font-family: var(--synti-font-display);
    font-size: 1.875rem;
    line-height: 2.25rem;
    font-weight: 700;
    color: var(--synti-neutral-900);
    margin-bottom: var(--synti-space-md);
}

@media (min-width: 768px) {
    .synti-section-title {
        font-size: 2.25rem;
        line-height: 2.5rem;
    }
}

.synti-section-subtitle {
    font-family: var(--synti-font-body);
    font-size: 1.125rem;
    line-height: 1.75rem;
    color: var(--synti-neutral-600);
    max-width: 42rem;
    margin-left: auto;
    margin-right: auto;
}
```

---

### 4️⃣ BUTTON SYSTEM

**Clases disponibles:**

| Clase | Uso | Colores |
|-------|-----|---------|
| `.synti-btn-primary` | CTA primaria | Azul (#3B82F6) |
| `.synti-btn-secondary` | CTA secundaria | Verde (#10B981) |
| `.synti-btn-success` | Acciones positivas (WhatsApp) | Verde (#22C55E) |
| `.synti-btn-danger` | Acciones destructivas | Rojo (#EF4444) |
| `.synti-btn-ghost` | Alternativa neutra | Neutro |
| `.synti-btn-link` | Enlaces estilizados | Azul primario |

**Tamaños:**
```html
<button class="synti-btn-primary">Default</button>
<button class="synti-btn-primary synti-btn-sm">Small</button>
<button class="synti-btn-primary synti-btn-lg">Large</button>
```

**Ejemplo con ícono:**
```html
<a href="https://wa.me/..." class="synti-btn-success text-base md:text-lg px-8 py-4 min-w-50">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <!-- WhatsApp icon -->
    </svg>
    Escribinos
</a>
```

---

### 5️⃣ BADGE SYSTEM

**Clases disponibles:**

```html
<!-- Success (verde) -->
<span class="synti-badge-success">
    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
    Abierto Ahora
</span>

<!-- Danger (rojo) -->
<span class="synti-badge-danger">
    Cerrado
</span>

<!-- Primary (azul) -->
<span class="synti-badge-primary">
    Destacado
</span>
```

---

### 6️⃣ GRID LAYOUTS (Responsive)

**3 columnas (desktop), 1 columna (mobile):**
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
    @foreach($items as $item)
        <div class="synti-card-hover">
            <!-- Contenido del card -->
        </div>
    @endforeach
</div>
```

**2 columnas (desktop), 1 columna (mobile):**
```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
    @foreach($items as $item)
        <div class="synti-card-hover">
            <!-- Contenido -->
        </div>
    @endforeach
</div>
```

---

### 7️⃣ SECTION WRAPPER

**Para todas las secciones de contenido:**

```html
<section class="synti-section-white">
    <div class="synti-container">
        <!-- Contenido -->
    </div>
</section>
```

**Variantes de background:**
```html
<section class="synti-section-white">   <!-- Blanco -->
<section class="synti-section-gray">    <!-- Gris neutral-50 -->
<section class="synti-section-primary"> <!-- Azul primario-50 -->
```

---

## 📐 CSS VARIABLES DISPONIBLES

### Colores

```css
/* Primary (Azul) */
--synti-primary-500: #3B82F6;
--synti-primary-600: #2563EB;
--synti-primary-700: #1D4ED8;

/* Secondary (Verde) */
--synti-secondary-500: #10B981;
--synti-secondary-600: #059669;

/* Success */
--synti-success-500: #22C55E;
--synti-success-700: #15803D;

/* Danger */
--synti-danger-500: #EF4444;
--synti-danger-700: #B91C1C;

/* Neutral (Grises) */
--synti-neutral-50: #F9FAFB;
--synti-neutral-600: #4B5563;
--synti-neutral-900: #111827;
```

### Espaciado

```css
--synti-space-xs: 0.5rem;   /* 8px */
--synti-space-sm: 0.75rem;  /* 12px */
--synti-space-md: 1rem;     /* 16px */
--synti-space-lg: 1.5rem;   /* 24px */
--synti-space-xl: 2rem;     /* 32px */
--synti-space-2xl: 3rem;    /* 48px */
--synti-space-3xl: 4rem;    /* 64px */
```

### Tipografía

```css
--synti-font-body: 'Inter', system-ui;
--synti-font-display: 'Poppins', system-ui;
```

### Sombras

```css
--synti-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
--synti-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
--synti-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
--synti-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
```

---

## ✅ CHECKLIST PARA REFACTORIZACIÓN

Al refactorizar cada vista, verificar:

- [ ] Usa componentes Synti (`.synti-card`, `.synti-btn-*`, `.synti-badge-*`)
- [ ] Buttons con ícono usan `gap-2` para separación
- [ ] Cards usan `.synti-card-hover` para interactividad
- [ ] Section headers siguen patrón estándar
- [ ] Grid layout responsive (grid-cols-1 md:grid-cols-2/3)
- [ ] Background section correcto (white/gray/primary)
- [ ] Texto usa variables font (`var(--synti-font-body/display)`)
- [ ] Colores usan variables CSS o clases Tailwind de primary/secondary/etc
- [ ] Padding/spacing usa escala Synti
- [ ] Sin clases @apply personalizadas (solo CSS puro)
- [ ] Hero.blade.php usa background inline style + overlay gradient
- [ ] Sin estilos inline excepto donde sea necesario (backgrounds, box-shadows)

---

## 🎬 Ejemplo Completo: Sección Products

```blade
<section class="synti-section-gray">
    <div class="synti-container">
        <!-- Section Header -->
        <div class="synti-section-header mb-12">
            <h2 class="synti-section-title">Nuestros Productos</h2>
            <p class="synti-section-subtitle">Descubre nuestra colección exclusiva</p>
        </div>
        
        <!-- Grid de Products -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($products as $product)
                <div class="synti-card-hover group">
                    <!-- Imagen -->
                    <div class="aspect-square mb-4 rounded-synti-lg overflow-hidden bg-neutral-100">
                        @if($product->image)
                            <img src="{{ asset('storage/...' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif
                    </div>
                    
                    <!-- Contenido -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-neutral-900">{{ $product->name }}</h3>
                        <p class="text-sm text-neutral-600">{{ $product->description }}</p>
                        
                        <!-- Precio -->
                        @if($product->price)
                            <p class="text-2xl font-bold text-primary-600" data-price-usd="{{ $product->price }}">
                                ${{ $product->price }}
                            </p>
                        @endif
                        
                        <!-- CTA -->
                        <a href="#" class="synti-btn-primary text-sm w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Ver Producto
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
```

---

**Version:** 1.0 | **Last Update:** Feb 19, 2026  
**Autor:** SYNTIweb Design System  
**Status:** ✅ Producción
