# 🧭 NAVBAR LANDING V2 - Documentación Completa

## 📋 Información General

**Archivo:** `resources/views/landing-v2/partials/navbar.blade.php`  
**Framework:** Laravel 12 + FlyonUI + Tailwind CSS v4  
**Responsive:** ✅ Completo (mobile + desktop)  
**Sticky:** ✅ Fixed top con z-10  
**Condicional:** ✅ Por plan del tenant

---

## 🎯 Elementos del Navbar

### 1️⃣ Siempre Visibles
| Elemento | Descripción | Variable |
|----------|-------------|----------|
| **Logo** | Imagen o inicial con fondo primary | `$customization->logo_filename` |
| **Nombre** | Nombre del negocio (truncado 180px) | `$tenant->business_name` |
| **Home** | Link ancla a #home | - |
| **Productos** | Link ancla a #products | - |
| **Servicios** | Link ancla a #services | - |
| **WhatsApp** | Botón con link a WhatsApp | `$tenant->whatsapp` |
| **Estado** | Indicador abierto/cerrado con animación pulse | `$tenant->is_open` |

### 2️⃣ Condicionales por Plan

#### 🚫 NO Mostrar en Plan Oportunidad
```php
@if($tenant->plan->slug !== 'oportunidad')
    <a href="#about">ℹ️ Nosotros</a>
@endif
```

#### 🚚 Icono Delivery (NO Oportunidad + has_delivery)
```php
@if($tenant->plan->slug !== 'oportunidad' && $tenant->has_delivery)
    <button>🚚 Delivery</button>
@endif
```

#### ❓ Link FAQ (SOLO Plan Visión)
```php
@if($tenant->plan->slug === 'vision')
    <a href="#faq">❓ FAQ</a>
@endif
```

### 3️⃣ Toggle Moneda (PRIVADO)
**Condición:** `$tenant->saved_display_mode === 'both_toggle'`

**Funcionalidad:**
- Botón en desktop y mobile
- Persistencia en `localStorage` con key `currency_{{ $tenant->id }}`
- Alterna entre REF ↔ $
- Muestra/oculta `.dollar-price` y `.ref-price`

**HTML Structure:**
```html
<button id="currency-toggle" class="currency-toggle">
    <span class="icon-[tabler--currency-dollar]"></span>
    <span class="currency-symbol">REF</span>
</button>
```

**JavaScript:**
- Event listener en todos los `.currency-toggle`
- Función `updateCurrency(currency)` sincroniza símbolos y precios
- Estado inicial desde localStorage o 'ref' por defecto

---

## 📱 Comportamiento Mobile

### Hamburger Menu
- **Botón:** `collapse-toggle` con iconos `tabler--menu-2` / `tabler--x`
- **Target:** `#navbar-collapse`
- **Animación:** Transición height con FlyonUI collapse
- **Contenido:**
  - Todos los links de navegación (stacked vertical)
  - WhatsApp button
  - Toggle moneda (si aplica)

### Diseño Responsive
```css
max-lg:w-full          /* Full width en mobile */
max-lg:flex-col        /* Stack vertical */
max-lg:mt-4            /* Margen top */
lg:flex                /* Hidden → Flex en desktop */
lg:navbar-center       /* Centrado en desktop */
```

---

## 🎨 Estados Visuales

### Logo
- **Con imagen:** `<img>` 10x10 object-contain rounded-lg
- **Sin imagen:** Inicial en círculo bg-primary text-primary-content

### Estado Abierto/Cerrado
**Desktop:**
```html
<div class="flex items-center gap-2 px-3 py-1.5 rounded-full 
            {{ $tenant->is_open ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
    <span class="animate-ping">🟢/🔴</span>
    <span>ABIERTO/CERRADO</span>
</div>
```

**Mobile:** Solo dot animado (sin texto)

### WhatsApp
- **Desktop:** Botón verde con texto "WhatsApp"
- **Mobile:** Botón circular ghost solo icono
- **URL:** `https://wa.me/{{ $tenant->whatsapp }}?text=...`

---

## 🧪 Testing

### URLs de Prueba
```bash
http://synticorex.test/test/            # Index con links
http://synticorex.test/test/navbar      # Navbar standalone
http://synticorex.test/test/hero-*      # Navbar + Hero
```

### Checklist de Validación
- [ ] Logo muestra imagen o inicial correctamente
- [ ] Nombre del negocio truncado sin desborde
- [ ] 3 links básicos siempre visibles (Home, Productos, Servicios)
- [ ] Link "Nosotros" NO aparece en plan Oportunidad
- [ ] Icono Delivery solo si NO Oportunidad + has_delivery
- [ ] Link "FAQ" solo en plan Visión
- [ ] Estado abierto/cerrado con animación pulse
- [ ] WhatsApp funciona (mobile + desktop)
- [ ] Toggle moneda solo si saved_display_mode === 'both_toggle'
- [ ] Hamburger menu funciona en mobile
- [ ] Collapse muestra todos los links verticalmente
- [ ] Sticky navbar al hacer scroll
- [ ] data-theme hereda del colorPalette del tenant

---

## 🔧 Integración en Landing V2

### En base.blade.php
```blade
<!DOCTYPE html>
<html lang="es" data-theme="{{ $tenant->colorPalette->slug }}">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
    {{-- Navbar --}}
    @include('landing-v2.partials.navbar')

    {{-- Hero dinámico --}}
    @switch($customization->hero_layout)
        @case('split') @include('landing-v2.partials.hero-split') @break
        @case('gradient') @include('landing-v2.partials.hero-gradient') @break
        @case('cards') @include('landing-v2.partials.hero-cards') @break
        @default @include('landing-v2.partials.hero-fullscreen')
    @endswitch

    {{-- Resto de secciones --}}
    @include('landing-v2.partials.about')
    @include('landing-v2.partials.products')
    @include('landing-v2.partials.services')
    @include('landing-v2.partials.testimonials')
    @include('landing-v2.partials.faq')
    @include('landing-v2.partials.contact')
    @include('landing-v2.partials.footer')

</body>
</html>
```

### Variables Requeridas
```php
$tenant                           // Model con plan, colorPalette
$customization                    // Model con logo_filename
$tenant->plan->slug               // oportunidad|crecimiento|vision
$tenant->has_delivery             // boolean
$tenant->is_open                  // boolean
$tenant->whatsapp                 // string|null
$tenant->saved_display_mode       // ref_only|dollar_only|both_toggle|both_separate
```

---

## 🎯 Próximos Pasos

1. **Probar navbar en browser** → http://synticorex.test/test/navbar
2. **Validar condicionales** → Cambiar plan del tenant 1 y verificar
3. **Toggle moneda** → Configurar `saved_display_mode = 'both_toggle'` y probar
4. **Integración** → Incluir navbar en base.blade.php
5. **Crear secciones restantes** → about, products, services, etc.

---

## 📊 Matriz de Visibilidad por Plan

| Elemento | Oportunidad | Crecimiento | Visión |
|----------|-------------|-------------|--------|
| Logo | ✅ | ✅ | ✅ |
| Nombre | ✅ | ✅ | ✅ |
| Home | ✅ | ✅ | ✅ |
| Productos | ✅ | ✅ | ✅ |
| Servicios | ✅ | ✅ | ✅ |
| **Nosotros** | ❌ | ✅ | ✅ |
| **Delivery** | ❌ | ✅ (si has_delivery) | ✅ (si has_delivery) |
| **FAQ** | ❌ | ❌ | ✅ |
| WhatsApp | ✅ | ✅ | ✅ |
| Estado | ✅ | ✅ | ✅ |
| Toggle Moneda | ✅ (si privado) | ✅ (si privado) | ✅ (si privado) |

---

## 🐛 Troubleshooting

### Navbar no sticky
```blade
{{-- Verificar clase fixed en header --}}
<header class="fixed top-0 z-10 w-full">
```

### Hamburger no funciona
```bash
# Verificar que FlyonUI JS está cargado
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Logo no se muestra
```bash
# Verificar storage link
php artisan storage:link

# Verificar ruta
storage/tenants/{tenant_id}/{logo_filename}
```

### Toggle moneda no persiste
```javascript
// Verificar localStorage en DevTools → Application → localStorage
// Key: currency_{tenant_id}
// Value: 'ref' o 'dollar'
```

---

**Autor:** SYNTIweb Development Team  
**Fecha:** 2026-02-20  
**Versión:** 1.0.0
