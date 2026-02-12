Proyecto Multitenant Landing Pages - Venezuela
Documento Técnico de Arquitectura e Implementación

1. RESUMEN EJECUTIVO
Objetivo del Proyecto
Crear una plataforma SaaS multitenant que permita a usuarios informales y PYMEs en Venezuela tener presencia digital a bajo costo mediante landing pages personalizables.
Características Principales

Sistema multitenant con almacenamiento JSON híbrido
3 planes escalonados (Básico, Pro, Premium)
Consola de administración para edición sin código
Optimizado para conexiones 3G/4G Venezuela
Infraestructura en Hostinger (hosting compartido)

Restricciones Técnicas

Presupuesto inicial: $0
Plataforma: Hostinger básico (sin Node.js)
Conexión usuarios finales: 3G/4G mayormente
Volumen por cliente: 5-30 productos/servicios (kits/paquetes)
Usuario objetivo: Usa principalmente móvil


2. ARQUITECTURA DEL SISTEMA
2.1 Modelo de Base de Datos
Decisión: Arquitectura Híbrida
├── MySQL (Una sola DB compartida)
│   ├── Tabla: tenants (id, slug, nombre, plan_id, dominio, activo)
│   ├── Tabla: plans (id, nombre, precio, limites_json)
│   ├── Tabla: users (autenticación Laravel estándar)
│   └── Tabla: payments (facturación básica)
│
└── JSON Files (Contenido editable)
    ├── /storage/tenants/cliente1.json
    ├── /storage/tenants/cliente2.json
    └── /storage/tenants/clienteN.json
Justificación:

DB compartida: Hostinger limita número de bases de datos
Volumen bajo (20-30 items/cliente) no justifica DB separada por tenant
JSON permite edición sin queries SQL complejas
Fácil backup y migración
Menor overhead de mantenimiento

Esquema JSON por Tenant:
json{
  "plan": "basico|pro|premium",
  "settings": {
    "nombre_negocio": "Mi Empresa",
    "color_primario": "#3B82F6",
    "color_secundario": "#1E40AF",
    "logo": "url_imagen",
    "favicon": "url_favicon"
  },
  "secciones": {
    "hero": {
      "titulo": "Bienvenido a Mi Negocio",
      "subtitulo": "Los mejores productos",
      "imagen_fondo": "url",
      "cta_texto": "Ver Productos",
      "cta_link": "#productos"
    },
    "acerca": {
      "titulo": "Acerca de Nosotros",
      "descripcion": "...",
      "imagen": "url"
    },
    "footer": {
      "direccion": "...",
      "telefono": "...",
      "email": "...",
      "redes_sociales": {
        "instagram": "url",
        "facebook": "url",
        "whatsapp": "numero"
      }
    }
  },
  "productos": [
    {
      "id": 1,
      "nombre": "Kit Básico",
      "descripcion": "Descripción del kit",
      "precio": 50,
      "imagen": "url",
      "destacado": true
    }
  ],
  "servicios": [
    {
      "id": 1,
      "nombre": "Servicio Premium",
      "descripcion": "...",
      "icono": "nombre_icono"
    }
  ],
  "faq": [
    {
      "pregunta": "¿Cómo comprar?",
      "respuesta": "..."
    }
  ]
}
```

### 2.2 Identificación de Tenants

**Opción 1: Subdominios** (Ideal, requiere wildcard DNS)
```
cliente1.tudominio.com
cliente2.tudominio.com
```

**Opción 2: Path-based** (Inicial recomendado para Hostinger)
```
tudominio.com/cliente1
tudominio.com/cliente2
```

**Opción 3: Dominios Custom** (Plan Premium)
```
www.negociocliente.com → apunta a tu servidor
Middleware de Identificación:
php// app/Http/Middleware/IdentifyTenant.php
public function handle(Request $request, Closure $next)
{
    // Prioridad: Custom domain > Subdomain > Path
    $tenant = $this->resolveTenant($request);
    
    if (!$tenant || !$tenant->activo) {
        abort(404);
    }
    
    // Inyectar tenant en request
    $request->merge(['tenant' => $tenant]);
    
    return $next($request);
}
```

---

## 3. STACK TECNOLÓGICO

### 3.1 Backend

**Framework: Laravel 11**

**Razones:**
- Ecosistema completo (auth, ORM, routing, templates)
- Blade templating: renderizado server-side (menos JS al cliente)
- Eloquent ORM con scopes globales para multitenant
- Artisan commands para automatización
- Storage abstraction perfecta para modelo JSON
- Comunidad hispanohablante masiva
- Mejor que Slim para escalar

**Versiones:**
```
PHP: 8.2+
Laravel: 11.x
MySQL: 8.0+ (MariaDB compatible)
Composer: 2.x
Paquetes Adicionales:
json{
  "require": {
    "laravel/framework": "^11.0",
    "laravel/breeze": "^2.0",
    "intervention/image": "^3.0"
  }
}
```

### 3.2 Frontend

**Framework CSS: Tailwind CSS 3.x**

**Razones:**
- Utility-first: rápido desarrollo
- PurgeCSS integrado: elimina 90%+ CSS no usado
- Sin jQuery ni dependencias pesadas
- Customizable por tenant (colores dinámicos)

**Interactividad: Alpine.js 3.x**

**Razones:**
- Ultra ligero: ~15KB vs 45KB+ (Vue/React)
- Sintaxis declarativa en HTML
- No requiere build process complejo
- Perfecto para interacciones simples (menú móvil, acordeones, modales)

**Comparación de peso:**
```
Alpine.js:       15 KB
Vue 3:           45 KB
React + ReactDOM: 130 KB
jQuery:          30 KB (más plugins)
```

**Build Tool: Vite**
- Viene integrado con Laravel 11
- Hot Module Replacement (HMR)
- Minificación automática
- Code splitting

### 3.3 Panel Admin

**Cuba Template (Bootstrap 5)**

**Uso exclusivo:**
- Panel de administración interno
- Dashboard con estadísticas
- Formularios de edición CRUD
- NO para landings públicas

**Livewire 3.x (Opcional)**
- Componentes reactivos sin API
- Editor visual con preview en tiempo real
- Solo en panel admin

---

## 4. OPTIMIZACIÓN PARA CONEXIONES 3G/4G

### 4.1 Estrategia de Carga

**Objetivo Inicial: <500KB carga inicial**
**Objetivo Total: <1.5MB página completa**

**ACTUALIZACIÓN - Holgura Técnica:**

Considerando desarrollo y margen de error:
```
PRESUPUESTOS ACTUALIZADOS:

Carga Inicial:
- Objetivo estricto: 500KB
- Holgura desarrollo: 750KB (+50%)
- Límite máximo: 1MB (no exceder)

Carga Total:
- Objetivo estricto: 1.5MB
- Holgura desarrollo: 2.2MB (+47%)
- Límite máximo: 3MB (no exceder)

Justificación del incremento:
- Permite incluir fuentes web custom (~100KB)
- Margen para imágenes hero optimizadas
- Espacio para animaciones sutiles
- Buffer para contenido dinámico variable
- Testing A/B sin reoptimizar constantemente

ADVERTENCIA: Estos son límites de desarrollo.
En producción, apuntar siempre a los objetivos estrictos.
```

**Desglose de Peso (con holgura):**
```
HTML:                 ~15KB  (20KB máx)
CSS (Tailwind):       ~150KB (200KB máx)
JavaScript (Alpine):  ~15KB  (25KB máx)
Fuentes Web:          ~80KB  (120KB máx)
Imágenes Above Fold:  ~200KB (300KB máx)
Iconos SVG:           ~10KB  (15KB máx)
-------------------------------------------
TOTAL INICIAL:        ~470KB (680KB real)

Below the Fold:
Imágenes productos:   ~800KB (1.2MB máx)
Imágenes servicios:   ~200KB (300KB máx)
-------------------------------------------
TOTAL PÁGINA:         ~1.47MB (2.18MB real)
4.2 Técnicas de Optimización
HTML/CSS:
html<!-- Critical CSS inline -->
<head>
    <style>
        /* Solo estilos del hero y menú */
        :root { --primary: {{ $color }}; }
        .hero { /* ... */ }
    </style>
    
    <!-- CSS completo defer -->
    <link rel="stylesheet" href="/css/app.css" media="print" onload="this.media='all'">
</head>
```

**Imágenes:**
```
1. Formato WebP con fallback JPEG
   <picture>
     <source srcset="hero.webp" type="image/webp">
     <img src="hero.jpg" alt="Hero">
   </picture>

2. Lazy loading nativo
   <img src="producto.jpg" loading="lazy">

3. Responsive images
   <img srcset="small.jpg 480w, medium.jpg 800w, large.jpg 1200w">

4. Compresión agresiva
   - JPEG: calidad 75-80%
   - WebP: calidad 70-75%
   - PNG: TinyPNG/Squoosh

5. Dimensiones objetivo
   - Hero: 1920x800 → ~150KB WebP
   - Productos: 600x600 → ~40KB WebP
   - Iconos: SVG inline o sprite
JavaScript:
javascript// Defer no crítico
<script src="/js/alpine.js" defer></script>

// Preconnect a dominios externos
<link rel="preconnect" href="https://fonts.googleapis.com">

// Prefetch siguiente página (opcional)
<link rel="prefetch" href="/productos">
Caché Agresivo:
php// .htaccess o nginx.conf
# Assets estáticos: 1 año
<FilesMatch "\.(jpg|jpeg|png|webp|gif|svg|css|js|woff2)$">
    Header set Cache-Control "max-age=31536000, public, immutable"
</FilesMatch>

# HTML: 1 hora (permite updates rápidos)
<FilesMatch "\.html$">
    Header set Cache-Control "max-age=3600, public, must-revalidate"
</FilesMatch>
```

**CDN Gratuito: Cloudflare**
```
1. Registrar dominio en Cloudflare (gratis)
2. Cambiar nameservers
3. Activar auto-minify (HTML/CSS/JS)
4. Activar Brotli compression
5. Configurar page rules para cache
4.3 Configuración Laravel Optimizada
php// .env
APP_ENV=production
APP_DEBUG=false

CACHE_DRIVER=file           # No Redis en Hostinger básico
SESSION_DRIVER=file
QUEUE_CONNECTION=database   # Jobs async livianos

# Optimización BD
DB_STRICT=false
DB_ENGINE=InnoDB

# Assets
ASSET_URL=https://cdn.tudominio.com  # Si usas CDN
Comandos de Optimización:
bash# Antes de deploy
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Assets
npm run build  # Vite minifica automáticamente
```

---

## 5. SISTEMA DE PLANES

### 5.1 Definición de Tiers

| Feature | Básico | Pro | Premium |
|---------|--------|-----|---------|
| **Precio** | $5/mes | $12/mes | $25/mes |
| **Productos** | 5 | 15 | 30 |
| **Servicios** | 3 | 8 | 15 |
| **Secciones** | 4 fijas | 7 + FAQ | Todas + CSS custom |
| **Imágenes** | 5 (500KB total) | 15 (2MB) | 30 (5MB) |
| **Dominio** | Subdominio | Subdominio | Custom domain |
| **Analytics** | ❌ | Google Analytics | GA + Forms |
| **Soporte** | Email | Email + WhatsApp | Prioritario |
| **Templates** | 1 básico | 3 opciones | 5 + personalizados |

**Secciones por Plan:**
```
Básico:     Hero, Productos, Acerca, Footer
Pro:        Básico + Servicios, FAQ, CTA
Premium:    Pro + Testimonios, Blog, Galería, Custom
5.2 Validación de Límites
php// app/Services/TenantContentService.php
private function validateByPlan(Tenant $tenant, array $data): void
{
    $limits = config('plans.limits')[$tenant->plan_id];
    
    // Validar productos
    if (count($data['productos'] ?? []) > $limits['productos']) {
        throw new ValidationException(
            "Tu plan permite máximo {$limits['productos']} productos"
        );
    }
    
    // Validar peso imágenes
    $totalImageSize = $this->calculateImageSize($data);
    if ($totalImageSize > $limits['storage_mb'] * 1024 * 1024) {
        throw new ValidationException(
            "Límite de almacenamiento excedido"
        );
    }
    
    // Validar secciones habilitadas
    $enabledSections = $limits['sections'];
    foreach ($data['secciones'] as $section => $content) {
        if (!in_array($section, $enabledSections)) {
            unset($data['secciones'][$section]);
        }
    }
}
```

---

## 6. ESTRUCTURA DE ARCHIVOS

### 6.1 Árbol de Directorios
```
landing-multitenant/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── TenantController.php
│   │   │   │   ├── ContentEditorController.php
│   │   │   │   ├── PlanController.php
│   │   │   │   └── MediaController.php
│   │   │   ├── LandingController.php
│   │   │   └── Auth/
│   │   │       └── (Laravel Breeze)
│   │   ├── Middleware/
│   │   │   ├── IdentifyTenant.php
│   │   │   ├── CheckPlanLimits.php
│   │   │   └── EnsureActive.php
│   │   └── Requests/
│   │       ├── TenantContentRequest.php
│   │       └── TenantCreateRequest.php
│   ├── Models/
│   │   ├── Tenant.php
│   │   ├── Plan.php
│   │   ├── User.php
│   │   └── Payment.php
│   ├── Services/
│   │   ├── TenantContentService.php
│   │   ├── ImageOptimizationService.php
│   │   └── BackupService.php
│   └── Traits/
│       └── HasTenant.php
├── resources/
│   ├── views/
│   │   ├── admin/              # Cuba Template
│   │   │   ├── layouts/
│   │   │   │   └── app.blade.php
│   │   │   ├── dashboard.blade.php
│   │   │   ├── tenants/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   └── editor/
│   │   │       ├── content.blade.php
│   │   │       ├── products.blade.php
│   │   │       ├── services.blade.php
│   │   │       └── settings.blade.php
│   │   ├── landings/           # Público optimizado
│   │   │   ├── layouts/
│   │   │   │   └── app.blade.php
│   │   │   ├── templates/
│   │   │   │   ├── classic.blade.php
│   │   │   │   ├── modern.blade.php
│   │   │   │   └── minimal.blade.php
│   │   │   └── components/
│   │   │       ├── hero.blade.php
│   │   │       ├── products.blade.php
│   │   │       ├── services.blade.php
│   │   │       ├── faq.blade.php
│   │   │       ├── cta.blade.php
│   │   │       └── footer.blade.php
│   │   └── marketing/          # Página venta del SaaS
│   │       ├── home.blade.php
│   │       ├── pricing.blade.php
│   │       └── features.blade.php
│   ├── css/
│   │   ├── admin.css           # Cuba styles
│   │   └── landing.css         # Tailwind
│   └── js/
│       ├── admin.js
│       └── landing.js          # Alpine
├── storage/
│   ├── tenants/                # JSON files
│   │   ├── ejemplo-cliente.json
│   │   └── otro-negocio.json
│   ├── backups/                # Versionado
│   │   └── tenants/
│   │       └── ejemplo-cliente/
│   │           ├── 2024-01-15-v1.json
│   │           └── 2024-01-16-v2.json
│   └── media/                  # Imágenes subidas
│       └── tenants/
│           └── ejemplo-cliente/
│               ├── logo.webp
│               └── productos/
├── routes/
│   ├── web.php                 # Landings públicas
│   ├── admin.php               # Panel admin
│   └── api.php                 # Webhooks pagos
├── database/
│   └── migrations/
│       ├── 2024_01_01_create_plans_table.php
│       ├── 2024_01_01_create_tenants_table.php
│       └── 2024_01_01_create_payments_table.php
├── config/
│   └── plans.php               # Configuración planes
└── public/
    ├── cuba/                   # Assets admin
    └── landing/                # Assets públicos
6.2 Rutas del Sistema
php// routes/web.php - Landings Públicas
Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/pricing', [MarketingController::class, 'pricing']);

// Path-based tenants
Route::middleware('tenant')->group(function() {
    Route::get('/{tenant}', [LandingController::class, 'show']);
});

// routes/admin.php - Panel Administración
Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->group(function() {
        
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de contenido
    Route::resource('content', ContentEditorController::class);
    
    // Productos
    Route::prefix('products')->group(function() {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
    
    // Configuración
    Route::get('/settings', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);
    
    // Medios
    Route::post('/media/upload', [MediaController::class, 'upload']);
});

7. COMPONENTES CORE DEL SISTEMA
7.1 TenantContentService
php// app/Services/TenantContentService.php
namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TenantContentService
{
    private const STORAGE_PATH = 'tenants';
    
    public function get(string $slug): array
    {
        $path = $this->getPath($slug);
        
        if (!Storage::exists($path)) {
            return $this->getDefaultContent();
        }
        
        return json_decode(Storage::get($path), true);
    }
    
    public function save(Tenant $tenant, array $data): void
    {
        // Validar según plan
        $this->validateByPlan($tenant, $data);
        
        // Backup antes de guardar
        $this->backup($tenant->slug);
        
        // Sanitizar datos
        $data = $this->sanitize($data);
        
        // Guardar
        Storage::put(
            $this->getPath($tenant->slug),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        
        // Log de cambio
        activity()
            ->performedOn($tenant)
            ->log('Contenido actualizado');
    }
    
    public function backup(string $slug): void
    {
        $current = $this->get($slug);
        $backupPath = "backups/tenants/{$slug}/" . now()->format('Y-m-d-His') . '.json';
        
        Storage::put($backupPath, json_encode($current, JSON_PRETTY_PRINT));
        
        // Mantener solo últimos 10 backups
        $this->pruneOldBackups($slug, 10);
    }
    
    private function validateByPlan(Tenant $tenant, array $data): void
    {
        $limits = config("plans.limits.{$tenant->plan->slug}");
        
        $validator = Validator::make($data, [
            'productos' => "array|max:{$limits['productos']}",
            'servicios' => "array|max:{$limits['servicios']}",
        ]);
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        // Validar peso imágenes
        $totalSize = $this->calculateMediaSize($data);
        if ($totalSize > $limits['storage_mb'] * 1024 * 1024) {
            throw new \Exception("Límite de almacenamiento excedido");
        }
    }
    
    private function sanitize(array $data): array
    {
        // Limpiar HTML peligroso
        foreach ($data as $key => &$value) {
            if (is_string($value)) {
                $value = strip_tags($value, '<b><i><u><a><br><p>');
            } elseif (is_array($value)) {
                $value = $this->sanitize($value);
            }
        }
        
        return $data;
    }
    
    private function getDefaultContent(): array
    {
        return [
            'plan' => 'basico',
            'settings' => [
                'nombre_negocio' => 'Mi Negocio',
                'color_primario' => '#3B82F6',
                'color_secundario' => '#1E40AF',
            ],
            'secciones' => [
                'hero' => [
                    'titulo' => 'Bienvenido',
                    'subtitulo' => 'Tu negocio online',
                    'cta_texto' => 'Contáctanos',
                ],
            ],
            'productos' => [],
            'servicios' => [],
        ];
    }
    
    private function getPath(string $slug): string
    {
        return self::STORAGE_PATH . "/{$slug}.json";
    }
}
7.2 LandingController
php// app/Http/Controllers/LandingController.php
namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\TenantContentService;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __construct(
        private TenantContentService $contentService
    ) {}
    
    public function show(Request $request)
    {
        $tenant = $request->tenant; // Inyectado por middleware
        
        if (!$tenant->activo) {
            abort(404, 'Sitio no disponible');
        }
        
        // Cargar contenido
        $content = $this->contentService->get($tenant->slug);
        
        // Seleccionar template según plan
        $template = $this->getTemplate($tenant);
        
        // Analytics
        $this->trackVisit($tenant);
        
        return view("landings.templates.{$template}", [
            'tenant' => $tenant,
            'content' => $content,
            'plan' => $tenant->plan,
        ]);
    }
    
    private function getTemplate(Tenant $tenant): string
    {
        // Usuario puede elegir, o default por plan
        return $tenant->template ?? $tenant->plan->default_template ?? 'classic';
    }
    
    private function trackVisit(Tenant $tenant): void
    {
        // Incrementar contador simple
        $tenant->increment('visits_count');
        
        // Si plan Pro+, guardar analytics detallado
        if ($tenant->plan_id >= 2) {
            // Log en tabla analytics o enviar a Google Analytics
        }
    }
}
7.3 Middleware IdentifyTenant
php// app/Http/Middleware/IdentifyTenant.php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = $this->resolveTenant($request);
        
        if (!$tenant) {
            abort(404, 'Sitio no encontrado');
        }
        
        // Inyectar en request
        $request->merge(['tenant' => $tenant]);
        
        // Configurar vistas con datos del tenant
        view()->share('tenant', $tenant);
        
        return $next($request);
    }
    
    private function resolveTenant(Request $request): ?Tenant
    {
        // Prioridad 1: Custom domain
        $host = $request->getHost();
        $tenant = Tenant::where('dominio', $host)->first();
        if ($tenant) return $tenant;
        
        // Prioridad 2: Subdominio
        if (preg_match('/^(.+)\.tudominio\.com$/', $host, $matches)) {
            return Tenant::where('slug', $matches[1])->first();
        }
        
        // Prioridad 3: Path (tudominio.com/cliente)
        $slug = $request->segment(1);
        return Tenant::where('slug', $slug)->first();
    }
}

8. TEMPLATES DE LANDING
8.1 Layout Base
blade{{-- resources/views/landings/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $content['settings']['descripcion'] ?? $tenant->nombre }}">
    
    <title>{{ $content['settings']['nombre_negocio'] ?? $tenant->nombre }}</title>
    
    {{-- Favicon --}}
    @if(isset($content['settings']['favicon']))
    <link rel="icon" href="{{ $content['settings']['favicon'] }}">
    @endif
    
    {{-- Preconnect a dominios externos --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    {{-- Critical CSS inline --}}
    <style>
        :root {
            --color-primary: {{ $content['settings']['color_primario'] ?? '#3B82F6' }};
            --color-secondary: {{ $content['settings']['color_secundario'] ?? '#1E40AF' }};
        }
        
        /* Critical above-the-fold styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; }
        .hero { min-height: 100vh; background: var(--color-primary); }
    </style>
    
    {{-- Full CSS defer --}}
    <link rel="stylesheet" href="{{ mix('css/landing.css') }}" media="print" onload="this.media='all'">
    
    {{-- OpenGraph para redes sociales --}}
    <meta property="og:title" content="{{ $content['settings']['nombre_negocio'] ?? $tenant->nombre }}">
    <meta property="og:description" content="{{ $content['settings']['descripcion'] ?? '' }}">
    @if(isset($content['settings']['logo']))
    <meta property="og:image" content="{{ $content['settings']['logo'] }}">
    @endif
</head>
<body>
    {{-- Header/Menu --}}
    <x-landing.header :content="$content" />
    
    {{-- Contenido principal --}}
    @yield('content')
    
    {{-- Footer --}}
    <x-landing.footer :content="$content" />
    
    {{-- JavaScript --}}
    <script src="{{ mix('js/alpine.min.js') }}" defer></script>
    
    {{-- Google Analytics (solo Plan Pro+) --}}
    @if($plan->id >= 2 && !empty($content['settings']['google_analytics']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $content['settings']['google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $content['settings']['google_analytics'] }}');
    </script>
    @endif
</body>
</html>
8.2 Componente Hero
blade{{-- resources/views/components/landing/hero.blade.php --}}
<section class="hero relative flex items-center justify-center text-white"
         style="background-image: url('{{ $content['hero']['imagen_fondo'] ?? '' }}'); background-size: cover;">
    
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    
    {{-- Contenido --}}
    <div class="relative z-10 container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            {{ $content['hero']['titulo'] ?? 'Bienvenido' }}
        </h1>
        
        <p class="text-xl md:text-2xl mb-8">
            {{ $content['hero']['subtitulo'] ?? '' }}
        </p>
        
        @if(!empty($content['hero']['cta_texto']))
        <a href="{{ $content['hero']['cta_link'] ?? '#contacto' }}" 
           class="inline-block bg-white text-primary px-8 py-3 rounded-full font-semibold hover:bg-opacity-90 transition">
            {{ $content['hero']['cta_texto'] }}
        </a>
        @endif
    </div>
    
    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>
8.3 Componente Productos
blade{{-- resources/views/components/landing/products.blade.php --}}
<section id="productos" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
            {{ $content['secciones']['productos']['titulo'] ?? 'Nuestros Productos' }}
        </h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($content['productos'] ?? [] as $producto)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                {{-- Imagen --}}
                <div class="relative pb-[75%]">
                    <img 
                        src="{{ $producto['imagen'] }}" 
                        alt="{{ $producto['nombre'] }}"
                        loading="lazy"
                        class="absolute inset-0 w-full h-full object-cover"
                    >
                    
                    @if($producto['destacado'] ?? false)
                    <span class="absolute top-2 right-2 bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-semibold">
                        Destacado
                    </span>
                    @endif
                </div>
                
                {{-- Contenido --}}
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $producto['nombre'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $producto['descripcion'] }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold" style="color: var(--color-primary);">
                            ${{ number_format($producto['precio'], 2) }}
                        </span>
                        
                        <a href="#contacto" 
                           class="bg-primary text-white px-4 py-2 rounded hover:opacity-90 transition">
                            Consultar
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
8.4 Componente FAQ (Solo Plan Pro+)
blade{{-- resources/views/components/landing/faq.blade.php --}}
@if($plan->id >= 2)
<section id="faq" class="py-16">
    <div class="container mx-auto px-4 max-w-3xl">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
            Preguntas Frecuentes
        </h2>
        
        <div x-data="{ active: null }" class="space-y-4">
            @foreach($content['faq'] ?? [] as $index => $item)
            <div class="border border-gray-200 rounded-lg">
                <button 
                    @click="active === {{ $index }} ? active = null : active = {{ $index }}"
                    class="w-full text-left p-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold">{{ $item['pregunta'] }}</span>
                    <svg 
                        class="w-5 h-5 transform transition-transform"
                        :class="{ 'rotate-180': active === {{ $index }} }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div 
                    x-show="active === {{ $index }}"
                    x-collapse
                    class="p-4 pt-0 text-gray-600">
                    {{ $item['respuesta'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

9. PANEL DE ADMINISTRACIÓN (CUBA TEMPLATE)
9.1 Dashboard Principal
blade{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Stats Cards --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Visitas Hoy</p>
                            <h4 class="mb-0">{{ $stats['visits_today'] }}</h4>
                        </div>
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Productos</p>
                            <h4 class="mb-0">{{ count($content['productos'] ?? []) }}/{{ $plan->limite_productos }}</h4>
                        </div>
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-success rounded-circle fs-3">
                                <i class="fa fa-box"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Más stats... --}}
    </div>
    
    <div class="row">
        {{-- Quick Actions --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Acciones Rápidas</h4>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.content.edit') }}" class="btn btn-primary">
                            <i class="fa fa-edit me-1"></i> Editar Contenido
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-box me-1"></i> Gestionar Productos
                        </a>
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-primary">
                            <i class="fa fa-cog me-1"></i> Configuración
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Preview Landing --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Vista Previa de tu Sitio</h4>
                </div>
                <div class="card-body p-0">
                    <iframe 
                        src="{{ route('landing.show', auth()->user()->tenant->slug) }}"
                        width="100%" 
                        height="600" 
                        frameborder="0">
                    </iframe>
                </div>
                <div class="card-footer">
                    <a href="{{ route('landing.show', auth()->user()->tenant->slug) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        Ver sitio completo <i class="fa fa-external-link-alt ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Sidebar --}}
        <div class="col-xl-4">
            {{-- Plan Info --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tu Plan: {{ $plan->nombre }}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fa fa-check text-success me-2"></i>
                            {{ $plan->limite_productos }} Productos
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check text-success me-2"></i>
                            {{ $plan->limite_servicios }} Servicios
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check text-success me-2"></i>
                            {{ $plan->limite_storage_mb }}MB Almacenamiento
                        </li>
                    </ul>
                    
                    @if($plan->id < 3)
                    <a href="{{ route('admin.upgrade') }}" class="btn btn-primary w-100 mt-3">
                        Mejorar Plan
                    </a>
                    @endif
                </div>
            </div>
            
            {{-- Recent Activity --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Actividad Reciente</h4>
                </div>
                <div class="card-body">
                    {{-- Lista de cambios --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
9.2 Editor de Contenido
blade{{-- resources/views/admin/editor/content.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- Editor --}}
            <div class="col-xl-8">
                {{-- Tabs por secciones --}}
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#hero">Hero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#productos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#servicios">Servicios</a>
                    </li>
                    @if($plan->id >= 2)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#faq">FAQ</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#settings">Configuración</a>
                    </li>
                </ul>
                
                <div class="tab-content p-3 border border-top-0">
                    {{-- Tab Hero --}}
                    <div class="tab-pane active" id="hero">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Título Principal</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="hero[titulo]"
                                        value="{{ $content['hero']['titulo'] ?? '' }}"
                                        maxlength="100">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Subtítulo</label>
                                    <textarea 
                                        class="form-control" 
                                        name="hero[subtitulo]"
                                        rows="3"
                                        maxlength="200">{{ $content['hero']['subtitulo'] ?? '' }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Imagen de Fondo</label>
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        name="hero[imagen_fondo]"
                                        accept="image/jpeg,image/png,image/webp">
                                    @if(!empty($content['hero']['imagen_fondo']))
                                    <img src="{{ $content['hero']['imagen_fondo'] }}" class="img-thumbnail mt-2" width="200">
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Texto Botón</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="hero[cta_texto]"
                                            value="{{ $content['hero']['cta_texto'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Link Botón</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="hero[cta_link]"
                                            value="{{ $content['hero']['cta_link'] ?? '#contacto' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tab Productos --}}
                    <div class="tab-pane" id="productos">
                        <div id="productos-container">
                            @foreach($content['productos'] ?? [] as $i => $producto)
                            <div class="card mb-3 producto-item">
                                <div class="card-header d-flex justify-content-between">
                                    <span>Producto {{ $i + 1 }}</span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProducto(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                name="productos[{{ $i }}][nombre]"
                                                value="{{ $producto['nombre'] }}"
                                                required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Precio</label>
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                name="productos[{{ $i }}][precio]"
                                                value="{{ $producto['precio'] }}"
                                                step="0.01"
                                                required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Destacado</label>
                                            <div class="form-check form-switch">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="productos[{{ $i }}][destacado]"
                                                    {{ ($producto['destacado'] ?? false) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea 
                                                class="form-control" 
                                                name="productos[{{ $i }}][descripcion]"
                                                rows="2">{{ $producto['descripcion'] }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Imagen</label>
                                            <input 
                                                type="file" 
                                                class="form-control" 
                                                name="productos[{{ $i }}][imagen]"
                                                accept="image/*">
                                            @if(!empty($producto['imagen']))
                                            <img src="{{ $producto['imagen'] }}" class="img-thumbnail mt-2" width="150">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if(count($content['productos'] ?? []) < $plan->limite_productos)
                        <button type="button" class="btn btn-outline-primary" onclick="addProducto()">
                            <i class="fa fa-plus me-1"></i> Agregar Producto
                        </button>
                        @else
                        <div class="alert alert-warning">
                            Has alcanzado el límite de productos de tu plan ({{ $plan->limite_productos }})
                        </div>
                        @endif
                    </div>
                    
                    {{-- Más tabs... --}}
                </div>
                
                {{-- Botones de acción --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Guardar Cambios
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                        Cancelar
                    </button>
                </div>
            </div>
            
            {{-- Preview Sidebar --}}
            <div class="col-xl-4">
                <div class="card sticky-top">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Vista Previa</h4>
                    </div>
                    <div class="card-body p-0">
                        <iframe 
                            id="preview-frame"
                            src="{{ route('landing.preview', $tenant->slug) }}"
                            width="100%" 
                            height="800"
                            frameborder="0">
                        </iframe>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="refreshPreview()">
                            <i class="fa fa-sync me-1"></i> Actualizar Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function addProducto() {
    const container = document.getElementById('productos-container');
    const index = container.children.length;
    
    // Template de producto nuevo
    const template = `
        <div class="card mb-3 producto-item">
            <div class="card-header d-flex justify-content-between">
                <span>Producto ${index + 1}</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeProducto(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <!-- Campos del producto -->
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
}

function removeProducto(btn) {
    if (confirm('¿Eliminar este producto?')) {
        btn.closest('.producto-item').remove();
    }
}

function refreshPreview() {
    document.getElementById('preview-frame').src += '';
}
</script>
@endpush
@endsection

10. DESPLIEGUE EN HOSTINGER
10.1 Preparación del Servidor
bash# SSH al servidor Hostinger
ssh usuario@tudominio.com

# Verificar versiones
php -v  # Debe ser 8.2+
composer -v
mysql -V

# Crear base de datos en cPanel
# Nombre: usuario_landing
# Usuario: usuario_landing
# Password: [generado]
10.2 Proceso de Deploy
bash# 1. Clonar/subir proyecto
cd public_html
git clone tu-repo.git landing-multitenant
# O subir vía FTP/SFTP

# 2. Instalar dependencias
cd landing-multitenant
composer install --optimize-autoloader --no-dev

# 3. Configurar .env
cp .env.example .env
nano .env

# Editar:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_DATABASE=usuario_landing
DB_USERNAME=usuario_landing
DB_PASSWORD=tu_password

# 4. Generar key
php artisan key:generate

# 5. Migraciones
php artisan migrate --force

# 6. Seeders (planes, datos iniciales)
php artisan db:seed --class=PlansSeeder

# 7. Optimizaciones
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 8. Compilar assets
npm install
npm run build

# 9. Permisos
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 10. Link storage
php artisan storage:link
10.3 Configuración .htaccess
apache# public/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Laravel routes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Cache headers
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType font/woff2 "access plus 1 year"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

### 10.4 Configuración Cloudflare
```
1. Agregar dominio a Cloudflare
2. Cambiar nameservers en Hostinger
3. SSL/TLS: Full (strict)
4. Auto Minify: HTML, CSS, JS
5. Brotli: Enabled
6. Cache Level: Standard
7. Page Rules:
   - *.jpg, *.png, *.webp: Cache Everything, Edge TTL 1 month
   - /admin/*: Cache Level: Bypass
8. Speed → Optimization:
   - Auto Minify: On
   - Rocket Loader: Off (conflicta con Alpine)
```

---

## 11. ROADMAP DE IMPLEMENTACIÓN

### Fase 1: MVP (Semanas 1-2)

**Objetivos:**
- Sistema de autenticación funcionando
- 1 template de landing básico
- CRUD de productos (JSON)
- Panel admin con Cuba
- Deploy en Hostinger

**Tareas:**
```
✓ Instalar Laravel + Breeze
✓ Configurar DB y migraciones
✓ Crear modelo Tenant y Plan
✓ Implementar TenantContentService
✓ Crear template landing "Classic"
✓ Panel admin básico (Cuba)
✓ Editor de contenido simple
✓ Sistema de identificación tenants (path-based)
✓ Deploy inicial
```

**Entregables:**
- Login/registro funcionando
- 1 landing page funcional
- Admin para editar contenido
- Documentación básica

### Fase 2: Escalabilidad (Semanas 3-4)

**Objetivos:**
- 3 templates diferentes
- Sistema de planes con límites
- Optimización de imágenes
- Pasarela de pago

**Tareas:**
```
✓ Templates: Modern, Minimal
✓ Validación de límites por plan
✓ ImageOptimizationService (WebP)
✓ Integración Zelle/PayPal manual
✓ Dashboard con estadísticas
✓ Sistema de backups automáticos
✓ Lazy loading imágenes
✓ CDN Cloudflare configurado
```

**Entregables:**
- 3 templates seleccionables
- Validación de planes funcionando
- Sistema de pagos (manual inicial)
- Optimizaciones de performance

### Fase 3: Producción (Semanas 5-6)

**Objetivos:**
- Subdominios funcionando
- Custom domains (plan Premium)
- Analytics integrado
- Soporte WhatsApp

**Tareas:**
```
✓ Wildcard DNS configurado
✓ Middleware subdominios
✓ Custom domain mapping
✓ Google Analytics (planes Pro+)
✓ Forms de contacto
✓ WhatsApp API integration
✓ Email notifications
✓ Testing completo
✓ SEO optimization
```

**Entregables:**
- Sistema multitenant completo
- Todas las features por plan
- Documentación usuario final
- Plan de marketing

### Fase 4: Crecimiento (Semana 7+)

**Objetivos:**
- Más templates
- Features avanzadas
- Automatización de pagos
- Escalabilidad

**Tareas:**
```
✓ 5+ templates profesionales
✓ Blog system (plan Premium)
✓ E-commerce básico
✓ Stripe/MercadoPago integration
✓ Afiliados system
✓ Migración a VPS (si crece)
✓ App móvil (PWA)
```

---

## 12. MÉTRICAS DE ÉXITO

### KPIs Técnicos
```
Performance:
✓ Lighthouse Score: >90
✓ First Contentful Paint: <1.5s
✓ Time to Interactive: <3.5s
✓ Total Page Size: <2.2MB (con holgura)
✓ Server Response: <200ms

Uptime:
✓ Disponibilidad: >99.5%
✓ Zero data loss

Escalabilidad:
✓ Soportar 100 tenants simultáneos
✓ <2s carga landing con 50 tenants activos
```

### KPIs de Negocio
```
Mes 1-3:
✓ 10 clientes beta
✓ 2 conversiones de Basic → Pro
✓ 0 churns

Mes 4-6:
✓ 50 clientes activos
✓ $500/mes MRR
✓ <5% churn rate

Mes 7-12:
✓ 200 clientes
✓ $2000/mes MRR
✓ Break-even alcanzado

13. MANTENIMIENTO Y SOPORTE
Backups Automáticos
php// app/Console/Commands/BackupTenants.php
use Illuminate\Console\Command;

class BackupTenants extends Command
{
    protected $signature = 'tenants:backup';
    
    public function handle()
    {
        $tenants = Tenant::where('activo', true)->get();
        
        foreach ($tenants as $tenant) {
            app(TenantContentService::class)->backup($tenant->slug);
        }
        
        // Backup DB
        $this->call('backup:run', ['--only-db' => true]);
        
        $this->info('Backups completados');
    }
}

// Cron job (crontab)
0 3 * * * cd /path/to/app && php artisan tenants:backup
Monitoreo
php// Instalar Laravel Telescope (dev)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

// Monitoreo producción: usar logs
Log::info('Tenant created', ['tenant_id' => $tenant->id]);
Log::error('Payment failed', ['tenant_id' => $tenant->id, 'error' => $e->getMessage()]);

// Alertas por email
if ($errorRate > 5) {
    Mail::to('admin@tudominio.com')->send(new ErrorAlert());
}
Updates
bash# Workflow de actualización
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

14. CONTINGENCIAS Y RIESGOS
Riesgo 1: Límites de Hostinger
Problema: Hostinger puede limitar recursos (CPU, memoria, I/O)
Mitigación:

Caché agresivo (reduce queries DB)
Cloudflare (reduce carga servidor)
Monitorear uso recursos
Plan de migración a VPS si >100 tenants

Riesgo 2: Crecimiento de JSON Files
Problema: Miles de archivos JSON pueden ralentizar filesystem
Mitigación:

Estructura por carpetas: /tenants/a/abc123.json
Migrar a DB cuando >1000 tenants
Index en filesystem para búsqueda rápida

Riesgo 3: Pagos Venezuela
Problema: Pasarelas internacionales difíciles en Venezuela
Solución:

Fase 1: Zelle/PayPal manual
Fase 2: Binance Pay / Criptomonedas
Fase 3: Integración local (Pagomóvil, Pago Móvil C2P)

Riesgo 4: Seguridad
Problema: Inyección de código malicioso en JSON
Mitigación:
php// Sanitización estricta
private function sanitize(array $data): array
{
    foreach ($data as $key => &$value) {
        if (is_string($value)) {
            // Solo permitir tags seguros
            $value = strip_tags($value, '<b><i><u><a><br><p>');
            // Escapar HTML entities
            $value = htmlspecialchars($value, ENT_QUOTES);
        }
    }
    return $data;
}

// Validación de imágenes
$file->mimeType(); // Verificar es imagen real
getimagesize($file); // Verificar no es script disfrazado

15. CONCLUSIONES Y RECOMENDACIONES
Decisiones Clave Tomadas

Laravel sobre Slim: Ecosistema completo, mejor para escalar
JSON híbrido sobre DB pura: Simplicidad inicial, fácil migración después
Cuba solo admin: Optimización para usuarios finales (3G)
Tailwind sobre Bootstrap: Menor peso, más customizable
Path-based inicial: Más simple que subdominios, migración fácil

Próximos Pasos Inmediatos

Instalar Laravel: composer create-project laravel/laravel landing-multitenant
Leer skill docx y pptx: Para entender generación documentos
Configurar Hostinger: Crear DB, configurar dominio
Implementar Fase 1: MVP en 2 semanas
Beta testing: 5-10 usuarios reales

Recursos Necesarios
Tiempo:

Desarrollo MVP: 60-80 horas
Testing y ajustes: 20 horas
Deploy y documentación: 10 horas
Total: ~100 horas (2.5 meses part-time)

Costo:

Hostinger: ~$3-5/mes
Dominio: ~$10/año
Cuba Template: $29 (one-time)
Cloudflare: Gratis
Total inicial: ~$50

Métricas de Validación
Antes de escalar, validar:

✓ 10 usuarios beta satisfechos
✓ <3s carga landing en 3G
✓ Cero bugs críticos
✓ Proceso de onboarding <10 minutos
✓ Uptime >99% por 30 días


16. ANEXOS
A. Configuración Planes
php// config/plans.php
return [
    'basico' => [
        'id' => 1,
        'nombre' => 'Básico',
        'precio' => 5,
        'limite_productos' => 5,
        'limite_servicios' => 3,
        'limite_secciones' => ['hero', 'productos', 'acerca', 'footer'],
        'storage_mb' => 5,
        'custom_domain' => false,
        'analytics' => false,
        'templates' => ['classic'],
    ],
    'pro' => [
        'id' => 2,
        'nombre' => 'Pro',
        'precio' => 12,
        'limite_productos' => 15,
        'limite_servicios' => 8,
        'limite_secciones' => ['hero', 'productos', 'servicios', 'acerca', 'faq', 'cta', 'footer'],
        'storage_mb' => 20,
        'custom_domain' => false,
        'analytics' => true,
        'templates' => ['classic', 'modern', 'minimal'],
    ],
    'premium' => [
        'id' => 3,
        'nombre' => 'Premium',
        'precio' => 25,
        'limite_productos' => 30,
        'limite_servicios' => 15,
        'limite_secciones' => 'all',
        'storage_mb' => 50,
        'custom_domain' => true,
        'analytics' => true,
        'templates' => 'all',
        'custom_css' => true,
    ],
];
B. Comandos Útiles
bash# Desarrollo
php artisan serve
npm run dev

# Crear tenant
php artisan tinker
>>> Tenant::create(['slug' => 'ejemplo', 'nombre' => 'Mi Negocio', 'plan_id' => 1])

# Ver logs
tail -f storage/logs/laravel.log

# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Backup manual
php artisan tenants:backup

# Optimizar imágenes batch
php artisan tenants:optimize-images
```

### C. Checklist Pre-Launch
```
[ ] SSL configurado (HTTPS)
[ ] .env en producción (APP_DEBUG=false)
[ ] Todas las rutas protegidas
[ ] Validación de inputs
[ ] Rate limiting habilitado
[ ] Backups automáticos configurados
[ ] Monitoreo de errores (logs)
[ ] Emails transaccionales funcionando
[ ] Términos y condiciones
[ ] Política de privacidad
[ ] Página de ayuda/FAQ
[ ] Lighthouse score >90
[ ] Test en móviles reales (3G)
[ ] Cross-browser testing
[ ] Plan de rollback
[ ] Documentación completa

Documento generado: 2025-01-XX
Versión: 1.0
Para: Proyecto Multitenant Landing Pages Venezuela
Próxima revisión: Post-MVP (Fase 2)

NOTA IMPORTANTE PARA PRÓXIMA INSTANCIA:
Este documento contiene TODO el análisis técnico realizado. Al entrenar nuevo modelo o continuar desarrollo, usar este markdown como insumo completo para evitar repetir análisis. Enfocarse en implementación práctica según roadmap definido.