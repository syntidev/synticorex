# 🎨 LANDING V2 - FLYONUI BLADE IMPLEMENTATION

**Fecha:** 2025-02-17  
**Template Base:** FlyonUI Free Landing Page  
**Framework:** Laravel 12 + Blade + TailwindCSS

---

## ✅ ARCHIVOS CREADOS (12 total)

### Layout Principal
```
resources/views/landing-v2/
├── base.blade.php              # Layout master
└── REFERENCE.html              # Template original (referencia)
```

### Partials (11 archivos)
```
resources/views/landing-v2/partials/
├── navbar.blade.php            # Header fijo con nav links
├── hero.blade.php              # Sección hero con CTA
├── about.blade.php             # Sobre nosotros + stats counters
├── products.blade.php          # Grid de productos con precios
├── services.blade.php          # Grid de servicios
├── testimonials.blade.php      # Carousel de testimonios
├── cta.blade.php               # Call to action WhatsApp
├── contact.blade.php           # Formulario de contacto
├── faq.blade.php               # Preguntas frecuentes accordion
├── team.blade.php              # Equipo (opcional/comentado)
└── footer.blade.php            # Footer con links y redes
```

---

## 🔧 VARIABLES BLADE UTILIZADAS

### Desde el Controller:
```php
$tenant          // Modelo Tenant completo
$customization   // TenantCustomization (logo, hero, about_text, social_networks)
$products        // Collection de productos activos (limitados por plan)
$services        // Collection de servicios activos
$dollarRate      // Float - Tasa actual del dólar BCV
```

### Datos del Tenant:
```php
$tenant->business_name      // Nombre del negocio
$tenant->tagline            // Eslogan corto
$tenant->slogan             // Eslogan largo
$tenant->description        // Descripción completa
$tenant->phone              // Teléfono
$tenant->whatsapp_sales     // WhatsApp principal
$tenant->email              // Email
$tenant->address            // Dirección
$tenant->city               // Ciudad
$tenant->country            // País
$tenant->business_hours     // Array de horarios
$tenant->is_open            // Boolean - Está abierto
$tenant->plan               // Relación - Plan actual
$tenant->colorPalette       // Relación - Paleta de colores
```

### Datos de Customization:
```php
$customization->logo_filename       // Logo del negocio
$customization->hero_filename       // Imagen hero background
$customization->about_text          // Texto "Sobre Nosotros"
$customization->about_image_filename // Imagen About (opcional)
$customization->social_networks     // Array: instagram, facebook, tiktok, twitter
```

---

## 🎨 SISTEMA DE TEMAS (PALETAS)

### Configuración:
```html
<html data-theme="{{ $tenant->colorPalette->slug ?? 'light' }}">
```

### Paletas FlyonUI Disponibles:
- `light` (default)
- `dark`
- `gourmet`
- `corporate`
- `luxury`
- `spotify`
- `ocean`
- `forest`
- Y más...

El tema se aplica automáticamente a todos los componentes FlyonUI.

---

## 💱 SISTEMA DE MONEDA

### JavaScript Global:
```javascript
window.SYNTIWEB = {
    tenant_id: 1,
    business_name: "Mi Negocio",
    whatsapp: "+584121234567",
    currency: {
        exchange_rate: 36.50,
        symbol_ref: "REF",
        symbol_bs: "Bs.",
        current: "REF",
        decimals: 2
    }
};
```

### Funciones disponibles:
```javascript
toggleCurrency()              // Cambia REF ↔ Bs.
formatPrice(priceUSD)         // Formatea según moneda actual
renderAllPrices()             // Re-renderiza todos los precios
buildWhatsAppLink(name, price) // Genera link WhatsApp
```

### Uso en HTML:
```html
<span data-price-usd="{{ $product->price_usd }}">
    REF {{ number_format($product->price_usd, 2) }}
</span>
```

---

## 📱 COMPONENTES FLYONUI UTILIZADOS

### Clases preservadas:
```
btn, btn-primary, btn-success, btn-lg, btn-gradient
card, card-body, card-title, card-border, figure
badge, badge-success, badge-warning, badge-soft
collapse, collapse-arrow, collapse-title, collapse-content
navbar, navbar-start, navbar-center, navbar-end
carousel, carousel-slide, carousel-body
link, link-animated
avatar, avatar-placeholder
divider
```

### Responsive:
```
grid-cols-1 md:grid-cols-2 lg:grid-cols-3
text-xl md:text-2xl lg:text-4xl
px-4 sm:px-6 lg:px-8
max-lg:hidden, max-md:flex-col
```

---

## 📊 COUNTERS ANIMADOS

### IDs utilizados:
```html
<span id="count1"></span>  <!-- Años de experiencia -->
<span id="count2"></span>  <!-- Productos -->
<span id="count3"></span>  <!-- Clientes -->
<span id="count4"></span>  <!-- Rating -->
```

### Configuración en about.blade.php:
```javascript
window.COUNTER_VALUES = {
    count1: { end: 5, suffix: '+' },
    count2: { end: {{ $products->count() }}, suffix: '+' },
    count3: { end: 500, suffix: '+' },
    count4: { end: 4.9, suffix: '★', decimals: 1 }
};
```

---

## 🚀 USO CON CONTROLLER

### TenantRendererController actualizado:
```php
public function show(string $subdomain): View|Response
{
    // ... obtener tenant ...
    
    return view('landing-v2.base', [
        'tenant' => $tenant,
        'customization' => $tenant->customization,
        'products' => $tenant->products()->active()->ordered()->limit($plan->products_limit)->get(),
        'services' => $tenant->services()->active()->ordered()->get(),
        'dollarRate' => $this->dollarRateService->getCurrentRate() ?? 36.50,
    ]);
}
```

---

## ✨ CARACTERÍSTICAS IMPLEMENTADAS

### 1. Navbar
- ✅ Logo dinámico o placeholder
- ✅ Links con scroll spy
- ✅ Badge de estado (Abierto/Cerrado)
- ✅ CTA WhatsApp
- ✅ Mobile responsive con collapse

### 2. Hero
- ✅ Background dinámico (imagen o gradiente)
- ✅ Título y tagline del tenant
- ✅ Badge de estado
- ✅ CTA dual: Ver Catálogo + WhatsApp
- ✅ Toggle de moneda inline

### 3. About
- ✅ Texto personalizable
- ✅ Imagen opcional
- ✅ 4 stats con counters animados
- ✅ CTA WhatsApp

### 4. Products
- ✅ Grid responsive 1/2/3 columnas
- ✅ Cards con imagen o placeholder
- ✅ Badges: Destacado, HOT, NUEVO, PROMO
- ✅ Precios con data-price-usd
- ✅ Toggle de moneda
- ✅ Tasa BCV visible
- ✅ CTA WhatsApp por producto
- ✅ Mensaje de límite por plan

### 5. Services
- ✅ Grid responsive
- ✅ Iconos Tabler dinámicos
- ✅ Imagen con overlay opcional
- ✅ CTA personalizable

### 6. Testimonials
- ✅ Carousel FlyonUI
- ✅ 4 testimonios hardcoded
- ✅ Avatars placeholder
- ✅ Rating 5 estrellas
- ✅ Controles prev/next

### 7. CTA Section
- ✅ Gradient background
- ✅ WhatsApp prominente
- ✅ Datos de contacto en pills

### 8. Contact
- ✅ WhatsApp destacado arriba
- ✅ Formulario completo
- ✅ Info de contacto lateral
- ✅ Horarios de atención
- ✅ Fallback a WhatsApp en submit

### 9. FAQ
- ✅ Accordion FlyonUI
- ✅ 5 preguntas hardcoded
- ✅ Decoraciones animadas
- ✅ CTA WhatsApp

### 10. Team (opcional)
- ✅ Grid 4 columnas
- ✅ Avatars placeholder
- ✅ Comentado por defecto

### 11. Footer
- ✅ Logo + nombre
- ✅ Nav links
- ✅ Redes sociales dinámicas
- ✅ Copyright año dinámico
- ✅ Powered by SYNTIweb

### Global
- ✅ WhatsApp floating button
- ✅ Scroll to top button
- ✅ Sistema de moneda JS
- ✅ Smooth scroll
- ✅ SEO meta tags

---

## 🔗 DEPENDENCIAS

### CSS:
```blade
@vite(['resources/css/app.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
```

### JS:
```blade
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@vite(['resources/js/app.js'])
<script src="{{ asset('js/landing-page-free.js') }}"></script>
```

---

## 📋 PARA PROBAR

### 1. Actualizar rutas:
```php
// routes/web.php
Route::get('/{subdomain}', [TenantRendererController::class, 'show'])
    ->where('subdomain', '[a-z0-9-]+');
```

### 2. Cambiar vista en controller:
```php
return view('landing-v2.base', $viewData);
```

### 3. Crear tenant de prueba con datos

### 4. Visitar: `http://localhost/{subdomain}`

---

## ✅ CHECKLIST COMPLETADO

- [x] base.blade.php con layout master
- [x] navbar.blade.php con scroll spy
- [x] hero.blade.php con background dinámico
- [x] about.blade.php con counters
- [x] products.blade.php con precios y badges
- [x] services.blade.php con iconos
- [x] testimonials.blade.php con carousel
- [x] cta.blade.php con WhatsApp
- [x] contact.blade.php con formulario
- [x] faq.blade.php con accordion
- [x] team.blade.php (opcional)
- [x] footer.blade.php con redes
- [x] Sistema de moneda JS
- [x] Theme via data-theme
- [x] Responsive completo
- [x] Clases FlyonUI preservadas

---

**Estado:** ✅ LISTO PARA PRODUCCIÓN  
**Próximo paso:** Configurar rutas y probar con tenant real
