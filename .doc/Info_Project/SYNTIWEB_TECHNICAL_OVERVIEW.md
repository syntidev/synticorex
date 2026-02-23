# 🔧 SYNTIWEB - OVERVIEW TÉCNICO Y ARQUITECTURA

**Versión:** 2.0  
**Fecha:** Febrero 2026  
**Objetivo:** Documentación técnica para entender el sistema completo

---

## 🎯 INTRODUCCIÓN

**SyntiWeb** es una plataforma **SaaS Multi-Tenant** que genera landing pages dinámicas para pequeños y medianos negocios. El sistema está diseñado para ser:

- **Ultraligero:** Carga <2s en 3G
- **Zero-code:** Usuario final sin conocimientos técnicos
- **Escalable:** Arquitectura multi-tenant preparada para miles de clientes
- **Adaptado a Venezuela:** Sistema de conversión USD/Bs automático

---

## 🏗️ ARQUITECTURA GENERAL

### Stack Tecnológico

#### Backend:
- **Framework:** Laravel 12.x (PHP 8.3)
- **Base de datos:** MySQL 8.0
- **Autenticación:** PIN de 4 dígitos (sesión)
- **Jobs/Queues:** Laravel Queues + Cron
- **Storage:** Local filesystem (producción: AWS S3)

#### Frontend:
- **Template Engine:** Blade (Laravel)
- **CSS Framework:** Tailwind CSS v4 + FlyonUI v2.4.1
- **JavaScript:** Vanilla JS (sin frameworks)
- **Build Tool:** Vite
- **Imágenes:** Intervention Image v3 (WebP conversion)

#### Infraestructura:
- **Servidor:** Ubuntu 22.04 LTS
- **Web Server:** Nginx + PHP-FPM
- **SSL:** Let's Encrypt (Wildcard)
- **DNS:** Wildcard subdomain + custom domains
- **CDN:** Cloudflare (opcional)

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### Diagrama de Relaciones

```
users (Laravel Auth)
  └─ 1:N ─→ tenants
               ├─ N:1 ─→ plans
               ├─ 1:N ─→ products
               ├─ 1:N ─→ product_images  (Plan 3: slider, máx 3 por producto)
               ├─ 1:N ─→ services
               ├─ 1:1 ─→ tenant_customization
               ├─ 1:N ─→ analytics_events
               ├─ 1:N ─→ invoices
               ├─ 1:N ─→ tenant_branches  (Plan 3: hasta 3 sucursales)
               └─ N:1 ─→ color_palettes (LEGACY, usar FlyonUI themes)
```

### Tablas Principales

#### **tenants** (Inquilinos/Negocios)
```sql
id                  INT PRIMARY KEY
user_id             INT FK → users.id
plan_id             INT FK → plans.id
business_name       VARCHAR(255) -- Nombre del negocio
subdomain           VARCHAR(63) UNIQUE -- joseburguer
custom_domain       VARCHAR(255) NULL -- www.joseburguer.com
status              ENUM('active','frozen','archived')
                    -- active: normal | frozen: landing congelada | archived: eliminación pendiente
whatsapp_number     VARCHAR(20)
email               VARCHAR(255)
city                VARCHAR(100)
address             TEXT
business_segment    VARCHAR(50) -- restaurante, barberia, etc
settings            JSON -- Configuración flexible
pin_hash            VARCHAR(255) -- Hash bcrypt del PIN
is_open             BOOLEAN DEFAULT 1
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**settings JSON structure:**
```json
{
  "engine_settings": {
    "visual": {
      "theme": {
        "flyonui_theme": "gourmet"
      }
    },
    "currency": {
      "display": {
        "mode": "both_toggle",
        "symbol_custom": "REF"  // Símbolo oficial del sistema
      },
      "exchange_rate": 36.50,
      "auto_update": true
    },
    "whatsapp": {
      "numbers": [
        {"type": "ventas", "number": "+58412XXXXXXX"},
        {"type": "soporte", "number": "+58424XXXXXXX"}
      ],
      "filter_hours": true
    },
    "schedule": {
      "monday": {"open": "09:00", "close": "18:00", "active": true},
      "tuesday": {"open": "09:00", "close": "18:00", "active": true},
      // ... resto de días
    },
    "social_networks": {
      "instagram": "@joseburguer",
      "facebook": "joseburguer",
      "tiktok": null
    },
    "payment_methods": ["zelle", "pagomovil", "binancepay", "efectivo"],
    "seo": {
      "title_override": null,
      "description_override": null,
      "keywords": ["hamburguesas", "maracay", "delivery"]
    }
  }
}
```

---

#### **plans** (Planes de Servicio)
```sql
id                  INT PRIMARY KEY
name                VARCHAR(100) -- "OPORTUNIDAD"
slug                VARCHAR(50) UNIQUE -- "oportunidad"
price_usd           DECIMAL(8,2) -- 99.00 (OPORTUNIDAD) / 149.00 (CRECIMIENTO) / 199.00 (VISIÓN)
products_limit      INT -- 6 / 12 / 18
services_limit      INT -- 3 / 6 / 9
features_json       JSON -- Features específicos
is_active           BOOLEAN DEFAULT 1
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**features_json structure (Plan OPORTUNIDAD como referencia):**
```json
{
  "palettes_count": 5,
  "show_dollar_rate": false,
  "show_dollar_widget": false,
  "whatsapp_numbers": 1,
  "social_networks_limit": 1,
  "analytics_level": "basic",
  "seo_level": "auto",
  "has_header_top": false,
  "has_about_section": false,
  "has_payment_methods": false,
  "has_faq": false,
  "has_cta_special": false,
  "has_branches": false,
  "product_images_slider": false,
  "white_label_available": false
}
```

> **Nota VISIÓN:** `show_dollar_rate: true`, `show_dollar_widget: true`, `whatsapp_numbers: 2`, `social_networks_limit: 6`, `has_header_top: true`, `has_about_section: true`, `has_payment_methods: true`, `has_faq: true`, `has_cta_special: true`, `has_branches: true`, `product_images_slider: true`, `white_label_available: true`.

---

#### **products** (Productos del Catálogo)
```sql
id                  INT PRIMARY KEY
tenant_id           INT FK → tenants.id
name                VARCHAR(255)
description         TEXT NULL
price_usd           DECIMAL(10,2) -- Siempre en USD
price_bs            DECIMAL(12,2) NULL -- Calculado, no guardado (legacy)
image_filename      VARCHAR(255) NULL -- product_01.webp
badge               ENUM('hot','new','promo') NULL
is_featured         BOOLEAN DEFAULT 0
is_active           BOOLEAN DEFAULT 1
position            INT DEFAULT 0 -- Orden visual
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(tenant_id, is_active, position)
```

**Nota:** `price_bs` se calcula en runtime multiplicando `price_usd * current_dollar_rate`. No se guarda en DB para mantener sincronización automática.

---

#### **services** (Servicios Ofrecidos)
```sql
id                  INT PRIMARY KEY
tenant_id           INT FK → tenants.id
name                VARCHAR(255)
description         TEXT NULL
icon_class          VARCHAR(100) NULL -- fa-scissors (Font Awesome)
image_filename      VARCHAR(255) NULL -- service_01.webp
has_image           BOOLEAN DEFAULT 0
is_active           BOOLEAN DEFAULT 1
position            INT DEFAULT 0
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(tenant_id, is_active, position)
```

**Lógica de display:**
- Plan OPORTUNIDAD: Solo `icon_class` (iconos)
- Plan CRECIMIENTO+: Puede usar `icon_class` O `image_filename`
- Plan VISIÓN: Si usa imagen → overlay especial con botón info

---

#### **tenant_customization** (Personalización Visual)
```sql
id                  INT PRIMARY KEY
tenant_id           INT FK → tenants.id UNIQUE
logo_filename       VARCHAR(255) NULL -- logo.webp
hero_filename       VARCHAR(255) NULL -- hero.webp
theme_slug          VARCHAR(50) NULL -- LEGACY, usar settings.engine_settings.visual.theme.flyonui_theme
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

#### **analytics_events** (Tracking de Actividad)
```sql
id                  BIGINT PRIMARY KEY
tenant_id           INT FK → tenants.id
event_type          VARCHAR(50) -- page_view, product_click, whatsapp_click
target_id           INT NULL -- product_id o service_id
target_type         VARCHAR(50) NULL -- product, service
metadata            JSON NULL
ip_address          VARCHAR(45) NULL
user_agent          TEXT NULL
created_at          TIMESTAMP

INDEX(tenant_id, event_type, created_at)
```

**Event types:**
- `page_view`: Visita a la landing
- `product_click`: Click en botón "Más Info" de producto
- `whatsapp_click`: Click en botón WhatsApp
- `service_click`: Click en servicio

---

#### **product_images** (Slider de Fotos — Plan VISIÓN)
```sql
id                  INT PRIMARY KEY
product_id          INT FK → products.id
filename            VARCHAR(255)
position            INT DEFAULT 0 -- Orden en slider (0 = principal)
created_at          TIMESTAMP

INDEX(product_id, position)
-- Máximo 3 imágenes por producto (enforceado en controller)
-- Solo disponible Plan VISIÓN
```

---

#### **tenant_branches** (Sucursales — Plan VISIÓN)
```sql
id                  INT PRIMARY KEY
tenant_id           INT FK → tenants.id
name                VARCHAR(255)       -- "Sede Las Mercedes"
address             TEXT NULL
phone               VARCHAR(50) NULL
payment_methods     JSON NULL          -- ["pagomovil","zelle","efectivo"]
position            INT DEFAULT 0
is_active           BOOLEAN DEFAULT 1
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(tenant_id, is_active, position)
-- Máximo 3 sucursales por tenant (enforceado en controller)
-- Solo disponible Plan VISIÓN
```

---

#### **dollar_rates** (Historial de Tasa del Dólar)
```sql
id                  INT PRIMARY KEY
rate                DECIMAL(10,2) -- 36.50
source              VARCHAR(100) -- "BCV", "Manual Override"
is_active           BOOLEAN DEFAULT 1 -- Solo 1 activo a la vez
effective_from      TIMESTAMP
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(is_active, effective_from)
```

**Lógica:**
1. Sistema usa la tasa donde `is_active = 1`
2. Cron job cada hora consulta API BCV
3. Si tasa cambió → Desactiva anterior, crea nueva
4. Si API falla → Mantiene última conocida
5. Fallback hardcoded: 36.50

---

## � CICLO DE VIDA DEL TENANT

### Estados del Plan

```
active → frozen → archived
```

| Estado | Descripción | Landing | Dashboard |
|--------|-------------|---------|-----------|
| `active` | Normal | Visible | Accesible |
| `frozen` | Plan vencido (gracia 30 días) | Página "renovando" | Acceso limitado |
| `archived` | Gracia 30 días vencida | 404 | Bloqueado |

### Lógica de Transición

- **active → frozen:** `expires_at` alcanzado, sin renovación. Cron diario 02:00am.
- **frozen → archived:** 30 días en frozen sin renovar.
- **Landing frozen:** `resources/views/landing/frozen.blade.php` — "Este negocio está renovando su presencia digital"
- **Renovación:** Pago activa inmediatamente el tenant, regresa a `active`.

### Helpers en Tenant Model

```php
$tenant->isFrozen()           // bool
$tenant->isExpiringSoon(7)    // bool (dentro de N días)
$tenant->daysUntilExpiry()    // int
$tenant->graceRemainingDays() // int (cuando está frozen)
```

---

## �🚀 FLUJO DE RENDERIZADO

### 1. Request Handling

```
User Request → Nginx → PHP-FPM → Laravel Router
```

#### Identificación del Tenant:

**Opción A: Subdomain**
```
http://joseburguer.menu.vip
```

Laravel middleware extrae subdomain:
```php
$subdomain = explode('.', $request->getHost())[0]; // "joseburguer"
$tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
```

**Opción B: Custom Domain**
```
http://www.joseburguer.com
```

Middleware busca por custom_domain:
```php
$domain = $request->getHost(); // "www.joseburguer.com"
$tenant = Tenant::where('custom_domain', $domain)->firstOrFail();
```

---

### 2. Data Fetching (TenantRendererController)

```php
public function show(string $subdomain)
{
    // 1. Encontrar tenant
    $tenant = Tenant::with(['plan', 'customization'])
        ->where('subdomain', $subdomain)
        ->where('status', 'active')
        ->firstOrFail();
    
    // 2. Obtener tasa del dólar (cached 1 hora)
    $dollarRate = DollarRateService::getCurrentRate();
    
    // 3. Cargar productos activos
    $products = $tenant->products()
        ->where('is_active', true)
        ->orderBy('position')
        ->get();
    
    // 4. Calcular price_bs en runtime
    foreach ($products as $product) {
        $product->price_bs = $product->price_usd * $dollarRate;
    }
    
    // 5. Extraer configuración visual
    $themeSlug = $tenant->settings['engine_settings']['visual']['theme']['flyonui_theme'] ?? 'light';
    
    // 6. Extraer configuración de moneda
    $currencySettings = $this->extractCurrencySettings($tenant);
    
    // 7. Renderizar Blade
    return view('landing.base', [
        'tenant' => $tenant,
        'plan' => $tenant->plan,
        'products' => $products,
        'services' => $tenant->services,
        'dollarRate' => $dollarRate,
        'themeSlug' => $themeSlug,
        'currencySettings' => $currencySettings,
        'meta' => $this->buildMetaTags($tenant)
    ]);
}
```

---

### 3. Blade Rendering (landing/base.blade.php)

```blade
<!DOCTYPE html>
<html lang="es" data-theme="{{ $themeSlug }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('landing.partials.header')
    @include('landing.partials.hero')
    
    @if($plan->id >= 2)
        @include('landing.partials.about')
    @endif
    
    @include('landing.partials.services')
    @include('landing.partials.products')
    
    @if($plan->id >= 3)
        @include('landing.partials.faq')
    @endif
    
    @include('landing.partials.footer')
    
    <script>
        const TENANT_ID = {{ $tenant->id }};
        const CURRENCY_MODE = "{{ $currencySettings['mode'] }}";
        const EXCHANGE_RATE = {{ $dollarRate }};
    </script>
</body>
</html>
```

---

### 4. Renderizado Condicional por Plan

**Ejemplo: products.blade.php**
```blade
<section id="productos">
    <div class="container">
        <h2>Nuestros Productos</h2>
        
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    @if($product->image_filename)
                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}" 
                             alt="{{ $product->name }}">
                    @endif
                    
                    <h3>{{ $product->name }}</h3>
                    
                    @if($product->badge)
                        <span class="badge badge-{{ $product->badge }}">
                            {{ strtoupper($product->badge) }}
                        </span>
                    @endif
                    
                    @if($product->description)
                        <p>{{ $product->description }}</p>
                    @endif
                    
                    @if($product->price_usd)
                        <div class="price">
                            @if($currencySettings['mode'] === 'usd_only')
                                ${{ number_format($product->price_usd, 2) }}
                            @elseif($currencySettings['mode'] === 'bs_only')
                                Bs. {{ number_format($product->price_bs, 2) }}
                            @else
                                ${{ number_format($product->price_usd, 2) }} / 
                                Bs. {{ number_format($product->price_bs, 2) }}
                            @endif
                        </div>
                    @endif
                    
                    <a href="https://wa.me/{{ $tenant->whatsapp_number }}?text=Hola, me interesa {{ urlencode($product->name) }}" 
                       class="btn-whatsapp" 
                       onclick="trackEvent('product_click', {{ $product->id }})">
                        Más Info →
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
```

---

## 🎨 SISTEMA DE TEMAS (FlyonUI)

### Arquitectura CSS

FlyonUI usa **CSS Variables** para temas. Laravel solo necesita aplicar el atributo correcto:

```html
<html data-theme="gourmet">
```

FlyonUI automáticamente aplica estas variables:
```css
[data-theme="gourmet"] {
  --primary: #9b2335;
  --secondary: #d4a76a;
  --accent: #c8a97e;
  --base-100: #fdfaf5;
  --base-content: #1a1a1a;
}
```

### CSS Compilado (app.css vía Vite)

```css
/* resources/css/app.css */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* FlyonUI themes importados automáticamente vía tailwind.config.js */
```

**tailwind.config.js:**
```javascript
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./node_modules/flyonui/dist/js/*.js"
  ],
  plugins: [
    require("flyonui"),
    require("flyonui/plugin")
  ],
  flyonui: {
    themes: [
      "light", "dark", "black", "claude", "corporate", 
      "ghibli", "gourmet", "luxury", "mintlify", "pastel", 
      "perplexity", "shadcn", "slack", "soft", "spotify", 
      "valorant", "vscode"
    ]
  }
}
```

### Cambio de Tema (Dashboard)

**Frontend (dashboard/index.blade.php):**
```javascript
function updateTheme(themeSlug) {
    fetch(`/tenant/${tenantId}/update-palette`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ theme: themeSlug })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload(); // Recarga para aplicar nuevo tema
        }
    });
}
```

**Backend (DashboardController):**
```php
public function updatePalette(Request $request, int $tenantId): JsonResponse
{
    $validated = $request->validate([
        'theme' => 'required|in:light,dark,black,claude,corporate,ghibli,...'
    ]);
    
    $tenant = Tenant::findOrFail($tenantId);
    
    $settings = $tenant->settings;
    $settings['engine_settings']['visual']['theme']['flyonui_theme'] = $validated['theme'];
    
    $tenant->settings = $settings;
    $tenant->save();
    
    return response()->json(['success' => true]);
}
```

---

## 💰 SISTEMA DE CONVERSIÓN USD/BS

### DollarRateService (Servicio Central)

```php
class DollarRateService
{
    const CACHE_KEY = 'dollar_rate_current';
    const CACHE_TTL = 3600; // 1 hora
    const FALLBACK_RATE = 36.50;
    
    /**
     * Obtener tasa actual (con cache)
     */
    public function getCurrentRate(): float
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function() {
            $activeRate = DollarRate::where('is_active', true)
                ->orderByDesc('effective_from')
                ->first();
            
            return $activeRate ? $activeRate->rate : self::FALLBACK_RATE;
        });
    }
    
    /**
     * Fetch desde API BCV y guardar
     */
    public function fetchAndStore(): bool
    {
        try {
            $response = Http::timeout(5)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if (!$response->successful()) {
                throw new \Exception('API no disponible');
            }
            
            $data = $response->json();
            $newRate = $data['promedio'] ?? null;
            
            if (!$newRate || $newRate <= 0) {
                throw new \Exception('Tasa inválida');
            }
            
            // Desactivar tasa anterior
            DollarRate::where('is_active', true)->update(['is_active' => false]);
            
            // Crear nueva tasa activa
            DollarRate::create([
                'rate' => $newRate,
                'source' => 'BCV API',
                'is_active' => true,
                'effective_from' => now()
            ]);
            
            // Limpiar cache
            Cache::forget(self::CACHE_KEY);
            
            // Propagar a todos los tenants
            $this->propagateRateToTenants($newRate);
            
            Log::info("Dollar rate updated: {$newRate}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Dollar rate fetch failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Propagar nueva tasa a settings de todos los tenants
     */
    protected function propagateRateToTenants(float $rate): void
    {
        $tenants = Tenant::where('status', 'active')->get();
        
        foreach ($tenants as $tenant) {
            // Verificar que tenant tenga auto_update activado
            $autoUpdate = data_get($tenant->settings, 'engine_settings.currency.auto_update', true);
            
            if (!$autoUpdate) {
                continue;
            }
            
            $settings = $tenant->settings;
            $settings['engine_settings']['currency']['exchange_rate'] = $rate;
            
            $tenant->settings = $settings;
            $tenant->save();
        }
        
        Log::info("Rate propagated to " . $tenants->count() . " tenants");
    }
}
```

### Comando Artisan (Cron Job)

```php
// app/Console/Commands/UpdateDollarRate.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DollarRateService;

class UpdateDollarRate extends Command
{
    protected $signature = 'dollar:update';
    protected $description = 'Fetch and update dollar rate from BCV API';
    
    public function handle(DollarRateService $service)
    {
        $this->info('Fetching dollar rate...');
        
        if ($service->fetchAndStore()) {
            $rate = $service->getCurrentRate();
            $this->info("✓ Rate updated: Bs. {$rate}");
            return 0;
        } else {
            $this->error('✗ Failed to update rate (using cached)');
            return 1;
        }
    }
}
```

**Crontab (producción):**
```bash
# /etc/crontab
0 * * * * cd /var/www/syntiweb && php artisan dollar:update >> /var/log/syntiweb/cron.log 2>&1
```

**Laravel Scheduler (app/Console/Kernel.php):**
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('dollar:update')
             ->hourly()
             ->appendOutputTo(storage_path('logs/dollar-cron.log'));
}
```

---

## 📸 SISTEMA DE IMÁGENES

### ImageUploadService

```php
class ImageUploadService
{
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    const MAX_WIDTH = 800;
    const WEBP_QUALITY = 80;
    const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    
    /**
     * Procesar y guardar imagen
     */
    public function process(
        UploadedFile $file, 
        int $tenantId, 
        string $type, // 'logo', 'hero', 'product', 'service'
        int $index = 1
    ): string {
        // 1. Validar tamaño
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File too large (max 2MB)');
        }
        
        // 2. Validar MIME
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \Exception('Invalid image format');
        }
        
        // 3. Generar nombre
        $filename = $this->generateFilename($type, $index);
        
        // 4. Procesar con Intervention Image
        $image = ImageManager::gd()->read($file->getPathname());
        
        // 5. Resize si es necesario
        if ($image->width() > self::MAX_WIDTH) {
            $image->scale(width: self::MAX_WIDTH);
        }
        
        // 6. Convertir a WebP
        $encoded = $image->toWebp(quality: self::WEBP_QUALITY);
        
        // 7. Guardar
        $path = storage_path("app/public/tenants/{$tenantId}");
        
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        file_put_contents("{$path}/{$filename}", $encoded);
        
        return $filename;
    }
    
    /**
     * Generar nombre consistente
     */
    protected function generateFilename(string $type, int $index): string
    {
        return match($type) {
            'logo' => 'logo.webp',
            'hero' => 'hero.webp',
            'product' => 'product_' . str_pad($index, 2, '0', STR_PAD_LEFT) . '.webp',
            'service' => 'service_' . str_pad($index, 2, '0', STR_PAD_LEFT) . '.webp',
        };
    }
    
    /**
     * Eliminar imagen
     */
    public function delete(int $tenantId, string $filename): bool
    {
        $path = storage_path("app/public/tenants/{$tenantId}/{$filename}");
        
        if (file_exists($path)) {
            return unlink($path);
        }
        
        return false;
    }
}
```

### ImageUploadController

```php
class ImageUploadController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}
    
    /**
     * Upload product image
     */
    public function uploadProduct(Request $request, int $tenantId, int $productId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);
        
        $tenant = Tenant::findOrFail($tenantId);
        $product = Product::where('tenant_id', $tenantId)
                         ->findOrFail($productId);
        
        // Eliminar imagen anterior si existe
        if ($product->image_filename) {
            $this->imageService->delete($tenantId, $product->image_filename);
        }
        
        // Procesar nueva
        $index = $product->position ?? 1;
        $filename = $this->imageService->process(
            $request->file('image'),
            $tenantId,
            'product',
            $index
        );
        
        // Actualizar DB
        $product->image_filename = $filename;
        $product->save();
        
        return response()->json([
            'success' => true,
            'filename' => $filename,
            'url' => asset("storage/tenants/{$tenantId}/{$filename}")
        ]);
    }
}
```

---

## 🎛️ DASHBOARD (Panel de Control)

### Activación y Autenticación

**JavaScript (landing/base.blade.php):**
```javascript
// Desktop: Alt+S
document.addEventListener('keydown', (e) => {
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        showPinModal();
    }
});

// Móvil: Long press 3s en logo
let longPressTimer;
const logo = document.querySelector('.syntiweb-logo');

logo.addEventListener('touchstart', () => {
    longPressTimer = setTimeout(() => {
        navigator.vibrate?.(50);
        showPinModal();
    }, 3000);
});

logo.addEventListener('touchend', () => {
    clearTimeout(longPressTimer);
});

// Modal PIN
function showPinModal() {
    const modal = document.getElementById('pin-modal');
    modal.classList.add('active');
    document.getElementById('pin-input').focus();
}

// Verificación
async function verifyPin() {
    const pin = document.getElementById('pin-input').value;
    
    const response = await fetch('/dashboard/verify-pin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pin, tenant_id: TENANT_ID })
    });
    
    const data = await response.json();
    
    if (data.success) {
        sessionStorage.setItem('dashboard_access', 'true');
        window.location.href = `/tenant/${TENANT_ID}/dashboard`;
    } else {
        alert('PIN incorrecto');
        document.getElementById('pin-input').value = '';
    }
}
```

**Backend (DashboardController):**
```php
public function verifyPin(Request $request): JsonResponse
{
    $validated = $request->validate([
        'pin' => 'required|digits:4',
        'tenant_id' => 'required|integer'
    ]);
    
    $tenant = Tenant::findOrFail($validated['tenant_id']);
    
    if (Hash::check($validated['pin'], $tenant->pin_hash)) {
        // Crear sesión
        session([
            'dashboard_access' => true,
            'tenant_id' => $tenant->id
        ]);
        
        return response()->json(['success' => true]);
    }
    
    // Log intento fallido
    Log::warning("Failed PIN attempt for tenant {$tenant->id}");
    
    return response()->json(['success' => false], 401);
}
```

---

### CRUD de Productos (Ejemplo)

**Frontend (dashboard/index.blade.php - Tab Productos):**
```javascript
async function createProduct() {
    const formData = {
        name: document.getElementById('product-name').value,
        description: document.getElementById('product-description').value,
        price_usd: parseFloat(document.getElementById('product-price').value),
        badge: document.getElementById('product-badge').value || null
    };
    
    const response = await fetch(`/tenant/${TENANT_ID}/products`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    });
    
    const data = await response.json();
    
    if (data.success) {
        alert('Producto creado');
        loadProducts(); // Recargar lista
        closeProductModal();
    } else {
        alert(data.message);
    }
}
```

**Backend (DashboardController):**
```php
public function createProduct(Request $request, int $tenantId): JsonResponse
{
    $tenant = Tenant::with('plan')->findOrFail($tenantId);
    
    // Verificar límite del plan
    $currentCount = $tenant->products()->count();
    $limit = $tenant->plan->products_limit;
    
    if ($currentCount >= $limit) {
        return response()->json([
            'success' => false,
            'message' => "Límite alcanzado ({$limit} productos para plan {$tenant->plan->name})"
        ], 422);
    }
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:5000',
        'price_usd' => 'required|numeric|min:0',
        'badge' => 'nullable|in:hot,new,promo'
    ]);
    
    // Auto-position al final
    $maxPosition = $tenant->products()->max('position') ?? 0;
    $validated['position'] = $maxPosition + 1;
    $validated['tenant_id'] = $tenantId;
    
    $product = Product::create($validated);
    
    return response()->json([
        'success' => true,
        'product' => $product
    ], 201);
}
```

---

## 📊 ANALYTICS

### Tracking Frontend

**JavaScript (landing/base.blade.php):**
```javascript
function trackEvent(eventType, targetId = null, targetType = null) {
    fetch('/analytics/track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            tenant_id: TENANT_ID,
            event_type: eventType,
            target_id: targetId,
            target_type: targetType,
            metadata: {
                url: window.location.href,
                referrer: document.referrer
            }
        })
    });
}

// Track page view on load
trackEvent('page_view');

// Track product clicks
document.querySelectorAll('.btn-whatsapp').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        trackEvent('product_click', productId, 'product');
    });
});
```

### Backend (AnalyticsController)

```php
public function track(Request $request): JsonResponse
{
    $validated = $request->validate([
        'tenant_id' => 'required|integer|exists:tenants,id',
        'event_type' => 'required|in:page_view,product_click,service_click,whatsapp_click',
        'target_id' => 'nullable|integer',
        'target_type' => 'nullable|in:product,service',
        'metadata' => 'nullable|array'
    ]);
    
    AnalyticsEvent::create([
        'tenant_id' => $validated['tenant_id'],
        'event_type' => $validated['event_type'],
        'target_id' => $validated['target_id'] ?? null,
        'target_type' => $validated['target_type'] ?? null,
        'metadata' => $validated['metadata'] ?? null,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);
    
    return response()->json(['success' => true]);
}
```

### Dashboard Analytics Display

```php
public function getAnalytics(int $tenantId): JsonResponse
{
    $tenant = Tenant::with('plan')->findOrFail($tenantId);
    $level = $tenant->plan->features_json['analytics_level'] ?? 'basic';
    
    $data = [];
    
    // Básico: Clicks del mes
    if ($level === 'basic') {
        $data['monthly_clicks'] = AnalyticsEvent::where('tenant_id', $tenantId)
            ->whereMonth('created_at', now()->month)
            ->count();
    }
    
    // Mejorado: + Visitas diarias
    if (in_array($level, ['intermediate', 'advanced'])) {
        $data['daily_views'] = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'page_view')
            ->whereDate('created_at', today())
            ->count();
            
        $data['monthly_views'] = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'page_view')
            ->whereMonth('created_at', now()->month)
            ->count();
    }
    
    // Avanzado: + Top productos
    if ($level === 'advanced') {
        $data['top_products'] = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'product_click')
            ->select('target_id', DB::raw('COUNT(*) as clicks'))
            ->groupBy('target_id')
            ->orderByDesc('clicks')
            ->limit(3)
            ->with('product:id,name')
            ->get();
    }
    
    return response()->json($data);
}
```

---

## 🔐 SEGURIDAD

### PIN System

**Generación (primer acceso):**
```php
public function generatePin(int $tenantId): string
{
    $pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    
    $tenant = Tenant::findOrFail($tenantId);
    $tenant->pin_hash = Hash::make($pin);
    $tenant->save();
    
    // Enviar por email
    Mail::to($tenant->email)->send(new PinGenerated($tenant, $pin));
    
    return $pin;
}
```

**Cambio de PIN:**
```php
public function updatePin(Request $request, int $tenantId): JsonResponse
{
    $validated = $request->validate([
        'current_pin' => 'required|digits:4',
        'new_pin' => 'required|digits:4|different:current_pin',
        'new_pin_confirmation' => 'required|same:new_pin'
    ]);
    
    $tenant = Tenant::findOrFail($tenantId);
    
    // Verificar PIN actual
    if (!Hash::check($validated['current_pin'], $tenant->pin_hash)) {
        return response()->json([
            'success' => false,
            'message' => 'PIN actual incorrecto'
        ], 401);
    }
    
    // Actualizar
    $tenant->pin_hash = Hash::make($validated['new_pin']);
    $tenant->save();
    
    return response()->json(['success' => true]);
}
```

### Rate Limiting

**routes/web.php:**
```php
Route::post('/dashboard/verify-pin', [DashboardController::class, 'verifyPin'])
    ->middleware('throttle:5,1'); // 5 intentos por minuto
    
Route::prefix('tenant/{tenantId}')->middleware(['auth.pin', 'throttle:60,1'])->group(function() {
    Route::post('/products', [DashboardController::class, 'createProduct']);
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct']);
    // ...
});
```

---

## 🚀 DEPLOYMENT

### Requisitos Servidor

```
- Ubuntu 22.04 LTS
- Nginx 1.18+
- PHP 8.3 + extensiones: gd, mysql, mbstring, xml, curl, zip
- MySQL 8.0+
- Node.js 20+ (para builds)
- Composer 2.x
- Certbot (SSL)
- Supervisor (queues)
```

### Nginx Config (Wildcard Subdomain)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name menu.vip *.menu.vip;
    
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name menu.vip *.menu.vip;
    
    ssl_certificate /etc/letsencrypt/live/menu.vip/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/menu.vip/privkey.pem;
    
    root /var/www/syntiweb/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### SSL Wildcard (Let's Encrypt)

```bash
sudo certbot certonly \
  --manual \
  --preferred-challenges=dns \
  --email admin@syntiweb.com \
  --server https://acme-v02.api.letsencrypt.org/directory \
  --agree-tos \
  -d menu.vip \
  -d *.menu.vip
```

### Laravel Config Production

**.env:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://menu.vip

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=syntiweb_prod
DB_USERNAME=syntiweb_user
DB_PASSWORD=STRONG_PASSWORD_HERE

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Queue Worker (Supervisor)

**/etc/supervisor/conf.d/syntiweb-worker.conf:**
```ini
[program:syntiweb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/syntiweb/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/syntiweb/storage/logs/worker.log
stopwaitsecs=3600
```

### Cron Jobs

```bash
# /etc/cron.d/syntiweb

# Laravel Scheduler (maneja todos los comandos)
* * * * * www-data cd /var/www/syntiweb && php artisan schedule:run >> /dev/null 2>&1

# Backup diario a las 3am
0 3 * * * www-data /var/www/syntiweb/scripts/backup.sh >> /var/log/syntiweb/backup.log 2>&1
```

**app/Console/Kernel.php:**
```php
protected function schedule(Schedule $schedule)
{
    // Tasa dólar cada hora
    $schedule->command('dollar:update')->hourly();
    
    // Backup DB diario 3am
    $schedule->command('backup:run')->dailyAt('03:00');
    
    // Limpiar analytics viejos (>90 días)
    $schedule->command('analytics:cleanup')->weekly();
}
```

---

## 📊 MONITOREO Y LOGS

### Laravel Logs

```
storage/logs/
├── laravel-2026-02-22.log          # General
├── dollar-cron.log                  # Tasa dólar
├── worker.log                       # Queue workers
└── nginx/
    ├── access.log
    └── error.log
```

### Métricas Clave

**Performance:**
- Response time < 200ms (cached)
- Response time < 800ms (uncached)
- Image load < 500ms

**Availability:**
- Uptime > 99.9%
- SSL certificate auto-renewal

**Business:**
- Tenants activos
- Page views/día
- Conversión WhatsApp clicks

---

## 🧪 TESTING

### PHPUnit Tests

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=TenantRenderingTest
php artisan test --filter=DollarRateServiceTest
```

**Ejemplo: tests/Feature/TenantRenderingTest.php**
```php
public function test_tenant_landing_renders_correctly()
{
    $tenant = Tenant::factory()->create([
        'subdomain' => 'testburguer',
        'status' => 'active'
    ]);
    
    $product = Product::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Hamburguesa Test'
    ]);
    
    $response = $this->get('/testburguer');
    
    $response->assertStatus(200);
    $response->assertSee('Hamburguesa Test');
    $response->assertSee($tenant->business_name);
}
```

---

## 📚 DOCUMENTACIÓN ADICIONAL

### APIs Externas Utilizadas

**Tasa del Dólar:**
- **Endpoint:** `https://ve.dolarapi.com/v1/dolares/oficial`
- **Límite:** Sin límite (público)
- **Backup:** Manual override en admin

---

## 🎯 ROADMAP TÉCNICO

### Próximas Mejoras

#### Q1 2026:
- [ ] Multi-idioma (ES/EN)
- [ ] PWA (Progressive Web App)
- [ ] Dark mode manual
- [ ] A/B testing temas

#### Q2 2026:
- [ ] Integración Mercado Pago
- [ ] Reservas/Citas online
- [ ] Sistema de reviews
- [ ] Email marketing integrado

#### Q3 2026:
- [ ] Multi-tenant database (schema separation)
- [ ] CDN para imágenes
- [ ] SMS notifications
- [ ] API pública para integraciones

---

**Última actualización:** Febrero 2026  
**Documento generado para:** Referencia técnica del sistema  
**Mantenido por:** Equipo Development SyntiWeb
