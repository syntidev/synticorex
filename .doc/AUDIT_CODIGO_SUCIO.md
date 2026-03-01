# AUDIT_CODIGO_SUCIO.md
**SYNTIweb — Auditoría de Código y Deuda Técnica**
*Generada: 2026-03-01 | Rama: feature/limpieza-frankenstein*

---

## RESUMEN EJECUTIVO

| Categoría | Cantidad |
|-----------|----------|
| 🔴 CRÍTICO (refactor obligatorio) | 6 |
| 🟠 ALTO (inconsistencias graves) | 8 |
| 🟡 MEDIO (mejorables) | 9 |
| 🟢 BAJO (cosmético) | 6 |
| **TOTAL ISSUES** | **29** |

**Estilo detectado predominante:** Código multicapa — mezcla de Opus (comentarios con ═══, arquitectura limpia), Gemini (schemas Blade+JSON, hero partials), Copilot (auth boilerplate sin strict_types). Inconsistencias evidentes entre sesiones de diferentes IAs.

---

## MATRIZ COMPLETA

| # | Archivo / Área | Estilo Detectado | Limpieza | Prioridad |
|---|---------------|-----------------|----------|-----------|
| 1 | `app/Http/Middleware/IdentifyTenant.php` | Gemini (schema diferente) | ❌ Roto | 🔴 CRÍTICO |
| 2 | `routes/web.php` — dashboard sin auth | Opus | ⚠️ Inseguro | 🔴 CRÍTICO |
| 3 | `app/Http/Controllers/DashboardController.php` (1375L) | Opus | ❌ God class | 🔴 CRÍTICO |
| 4 | `landing/schemas/` restaurant/store/health/professional | Gemini | ❌ Blade+JSON inválido | 🔴 CRÍTICO |
| 5 | `app/` — 18 archivos sin `declare(strict_types=1)` | Auth boilerplate | ⚠️ Incompleto | 🔴 CRÍTICO |
| 6 | `resources/css/app.css` — 11 Google Fonts individuales | Copilot | ⚠️ Performance | 🔴 CRÍTICO |
| 7 | `plan_id >= 2` hardcoded en 10+ lugares | Mixtum | ⚠️ Magic numbers | 🟠 ALTO |
| 8 | `asset()` usado en vistas para storage | Mixtum | ⚠️ Regla violada | 🟠 ALTO |
| 9 | `hero_filename` vs `hero_main_filename` inconsistencia | Gemini vs Opus | ❌ Campo duplicado | 🟠 ALTO |
| 10 | `config/tenancy.php` — dominios producción vs local | Gemini | ⚠️ Mismatch | 🟠 ALTO |
| 11 | `Tenant::$fillable` — `color_palette_id` legacy | Old session | ⚠️ Campo obsoleto | 🟠 ALTO |
| 12 | `custom_palette` en `tenant.settings` JSON vs columna propia | Mixtum | ⚠️ Arquitectura | 🟠 ALTO |
| 13 | `$allPayMeta` hardcoded en DashboardController | Opus | ⚠️ Debería ser config | 🟠 ALTO |
| 14 | Schemas (restaurant/store/health/prof) — sin `$schema` del controller | Gemini | ❌ Inconsistente | 🟠 ALTO |
| 15 | `resources/views/dashboard/_archive/` — código muerto | Old sessions | ❌ Dead code | 🟡 MEDIO |
| 16 | `dashboard/index.blade.php` — block `<style>` ~100L inline | Opus/Copilot | ⚠️ Anti-patrón | 🟡 MEDIO |
| 17 | `@php` blocks en vistas (lógica compleja) | Mixtum | ⚠️ Regla violada | 🟡 MEDIO |
| 18 | `TenantRendererController::show()` — compact() con 30+ vars | Opus | ⚠️ Mantenibilidad | 🟡 MEDIO |
| 19 | `landing/base.blade.php` — fuentes duplicadas (CSS + HTML) | Copilot | ⚠️ Performance | 🟡 MEDIO |
| 20 | `landing/base.blade.php` — `@php` con lógica `$effectiveTheme` | Mixtum | ⚠️ En vista | 🟡 MEDIO |
| 21 | `ImageUploadController` — `asset()` en respuesta JSON | Copilot | ⚠️ Relativo/path | 🟡 MEDIO |
| 22 | `DollarRateService` — fallback hardcoded `36.50` | Opus | ⚠️ Config | 🟡 MEDIO |
| 23 | `TenantCustomization::getSectionsOrder()` — normalización inline | Opus | ⚠️ Migración inline | 🟡 MEDIO |
| 24 | Inline `style=""` en dashboard components | Mixtum | ⚠️ Anti-Tailwind | 🟢 BAJO |
| 25 | `landing/schemas/restaurant.blade.php` — `Js::from()` dentro de JSON | Gemini | ⚠️ Inconsistente | 🟢 BAJO |
| 26 | `AnalyticsController` — `catch (\Exception` vs `catch (Throwable` | Opus vs Copilot | ⚠️ Inconsistente | 🟢 BAJO |
| 27 | `routes/web.php` — comentario con path local en header | Copilot | 🗑️ Cosmético | 🟢 BAJO |
| 28 | `hero.blade.php` (v1) — aún existe, sin uso activo | Old session | 🗑️ Dead code | 🟢 BAJO |
| 29 | `footer.blade.php` + `footer-v2.blade.php` — duplicado | Old session | 🗑️ Dead code | 🟢 BAJO |

---

## 🔴 CRÍTICOS

### 1. `IdentifyTenant` middleware — Schema mismatch, nunca aplicado

**Archivo:** `app/Http/Middleware/IdentifyTenant.php`
**Problema:** El middleware usa columnas `slug` y `activo` que no existen en el modelo `Tenant`. El modelo real usa `subdomain` y `status`. Además, el middleware está registrado como alias `tenant` en `bootstrap/app.php` pero nunca se aplica a ninguna ruta en `routes/web.php`.

El resultado: el middleware es código muerto que rompe si alguien intenta activarlo.

```php
// MIDDLEWARE (roto - columnas incorrectas):
Tenant::where('slug', $slug)->where('activo', true)->first();

// MODELO REAL:
Tenant::where('subdomain', $subdomain)->whereIn('status', ['active', 'frozen'])->first();
```

**Acción:** Corregir columnas (`slug` → `subdomain`, `activo` → `status: active`) Y aplicar el middleware a las rutas de tenant, o eliminar y consolidar la lógica en `TenantRendererController`.

---

### 2. Dashboard routes — Sin autenticación

**Archivo:** `routes/web.php` líneas 47–108
**Problema:** TODAS las rutas del panel (`/tenant/{id}/dashboard`, `/tenant/{id}/update-info`, productos, servicios, etc.) están expuestas sin ningún middleware de autenticación (`auth`, `tenant`, o validación de pertenencia).

```php
// ACTUAL — sin auth:
Route::get('/tenant/{tenantId}/dashboard', [DashboardController::class, 'index']);
Route::post('/tenant/{tenantId}/update-info', [DashboardController::class, 'updateInfo']);
// etc... 30+ rutas

// DEBERÍA ser:
Route::middleware(['auth'])->prefix('tenant/{tenantId}')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ...
});
```

La única "seguridad" actual es el PIN del dashboard (verificado por JS en el cliente). Cualquiera puede hacer POST a `/tenant/1/update-info` directamente.

**Acción:** Envolver todas las rutas dashboard en `Route::middleware(['auth'])`.

---

### 3. `DashboardController.php` — God class (1375 líneas)

**Archivo:** `app/Http/Controllers/DashboardController.php`
**Problema:** Un solo controlador maneja: carga del dashboard, info del negocio, tema, paleta custom, productos (CRUD), servicios (CRUD), sucursales, pagos, redes sociales, CTA, FAQ, testimoniales, horarios, sección order, PIN, analytics y más.

Viola Single Responsibility Principle. 30+ variables pasadas a la vista en un solo `compact()`.

**Distribución recomendada:**
- `DashboardController` → solo carga (`index`)
- `TenantInfoController` → updateInfo, updateHeaderTop, updateBusinessHours
- `TenantDesignController` → updateTheme, updatePalette, saveCustomPalette
- `TenantContentController` → updateTestimonials, updateFaq, updateCta
- `TenantConfigController` → updatePin, updatePaymentMethods, updateCurrencyConfig, updateSocialNetworks

**Acción:** Dividir en 5 controladores.

---

### 4. Schemas Blade+JSON — `restaurant`, `store`, `health`, `professional`

**Archivos:**
- `resources/views/landing/schemas/restaurant.blade.php`
- `resources/views/landing/schemas/store.blade.php`
- `resources/views/landing/schemas/health.blade.php`
- `resources/views/landing/schemas/professional.blade.php`

**Problema:** Idéntico al bug que ya fue corregido en `local-business.blade.php`: usan `@if` dentro de un bloque JSON, lo que puede generar JSON inválido (trailing commas, síntaxis rota). Además usan `asset()` dentro del JSON.

```blade
{{-- PATRÓN ROTO (estos 4 archivos): --}}
{
    "@type": "Restaurant",
    @if($tenant->phone)
    "telephone": {{ Js::from($tenant->phone) }},
    @endif
```

Estos 4 schemas TAMBIÉN necesitan migrar su lógica a `TenantRendererController::buildSchema()` con un `$schemaType` selector.

**Acción:** Extender `buildSchema()` para soportar los 4 tipos, igual que `local-business`.

---

### 5. `declare(strict_types=1)` — Faltante en 18 archivos

**Archivos sin `declare(strict_types=1)`:**
```
app/Http/Controllers/Auth/AuthenticatedSessionController.php
app/Http/Controllers/Auth/ConfirmablePasswordController.php
app/Http/Controllers/Auth/EmailVerificationNotificationController.php
app/Http/Controllers/Auth/EmailVerificationPromptController.php
app/Http/Controllers/Auth/NewPasswordController.php
app/Http/Controllers/Auth/PasswordController.php
app/Http/Controllers/Auth/PasswordResetLinkController.php
app/Http/Controllers/Auth/VerifyEmailController.php
app/Http/Controllers/Controller.php
app/Http/Controllers/ProfileController.php
app/Http/Requests/Auth/LoginRequest.php
app/Http/Requests/ProfileUpdateRequest.php
app/Providers/AppServiceProvider.php
app/Providers/ViewServiceProvider.php
app/View/Components/AppLayout.php
app/View/Components/GuestLayout.php
app/Console/Commands/RegenerateProductImages.php
app/Console/Commands/RenameImagesToStandardPattern.php
```

La mayoría son boilerplate de Laravel/Breeze que nunca se actualizó. Viola la regla crítica del proyecto.

**Acción:** Agregar `declare(strict_types=1);` como línea 3 en todos.

---

### 6. `resources/css/app.css` — 11 peticiones HTTP separadas a Google Fonts

**Archivo:** `resources/css/app.css` líneas 1–11
**Problema:** 11 `@import url('https://fonts.googleapis.com/...')` separados = 11 round-trips HTTP bloqueantes en cada carga de página. Además `landing/base.blade.php` carga 2 fuentes adicionales en el `<head>` directamente.

**Impacto:** Aumenta LCP y bloqueo del render. Google PageSpeed penaliza esto.

**Acción:**
```css
/* ACTUAL — 11 imports separados: */
@import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Archivo:wght@...&display=swap');
/* ...9 más... */

/* PROPUESTA — combinar en 1 solo: */
@import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300..700&family=Inter:wght@300..800&family=Geist:wght@300..700&family=Montserrat:wght@300..700&display=swap');
```

---

## 🟠 ALTOS

### 7. `plan_id >= 2` — Magic numbers en 10+ ubicaciones

**Archivos afectados:**
- `DashboardController.php` (líneas 207, 256)
- `resources/views/landing/base.blade.php` (líneas 78, 86)
- `resources/views/landing/partials/contact.blade.php` (líneas 16, 27)
- `resources/views/landing/partials/header.blade.php` (líneas 3, 4)
- `resources/views/landing/partials/payment_methods.blade.php` (línea 8)
- `resources/views/dashboard/components/design-section.blade.php` (línea 100)
- `resources/views/dashboard/components/info-section.blade.php` (línea 164)
- `resources/views/dashboard/components/message-section.blade.php` (línea 81)

**Problema:** Si los IDs de planes cambian (siempre cambian en producción o importaciones), hay que cazar cada número mágico. El modelo `Tenant` ya tiene el trait `HasBlueprint` — debería tener métodos como `$tenant->canAccess('about')` o `$tenant->isAtLeastPlan('crecimiento')`.

**Acción:** Agregar constantes en `Plan` model y métodos semánticos en `Tenant`:
```php
// app/Models/Plan.php
const OPORTUNIDAD = 1;
const CRECIMIENTO = 2;
const VISION = 3;

// app/Models/Tenant.php
public function isAtLeastCrecimiento(): bool { return $this->plan_id >= Plan::CRECIMIENTO; }
public function isVision(): bool { return $this->plan_id === Plan::VISION; }
```

---

### 8. `asset()` en vistas Blade — Regla del proyecto violada

**Afecta:** 25+ ocurrencias en:
- `landing/base.blade.php`
- `landing/partials/about.blade.php`, `hero.blade.php`, `product-card.blade.php`, `footer-v2.blade.php`, `navbar-v2.blade.php`, `header.blade.php`, `hero-fullscreen-v2.blade.php`, `hero-gradient-v2.blade.php`, `hero-split-v2.blade.php`
- `dashboard/components/products-section.blade.php`, `services-section.blade.php`, `info-section.blade.php`
- `landing/schemas/restaurant.blade.php`, `store.blade.php`, `professional.blade.php`, `health.blade.php`

**Nota:** La mayoría son `asset('storage/tenants/...')` — URLs de archivos subidos, no assets de build. La regla dice "NUNCA usar asset() → siempre @vite()" pero esto aplica a CSS/JS/assets de build. Para storage las opciones son `asset()` o `Storage::url()`. Se recomienda aclarar la regla y migrar a `Storage::url()` para consistencia semántica.

**Acción:** Crear helper `tenantStorageUrl($tenantId, $filename)` que use `Storage::url()` y unificar.

---

### 9. `hero_filename` vs `hero_main_filename` — Campo con dos nombres

**Problema:** El campo de la imagen hero del tenant tiene dos nombres distintos:
- `TenantCustomization::$fillable`: `'hero_filename'`
- `ImageUploadController::uploadHero()`: usa `hero_filename`
- Vistas hero (`hero-fullscreen-v2`, `hero-gradient-v2`, `hero-split-v2`): usan `$customization->hero_main_filename`
- El worktree `sweet-goldberg` tenía `hero_main_filename` en fillable

Resultado: Las imágenes hero se guardan pero no se muestran en las 3 vistas v2.

**Acción:** Decidir el nombre canónico (`hero_filename` ya que está en DB/fillable), actualizar las 3 vistas v2 para usar `$customization->hero_filename`.

---

### 10. `config/tenancy.php` — Dominios producción sin equivalente local

**Archivo:** `config/tenancy.php`
**Problema:** `central_domains` tiene `tu.menu`, `menu.vip`, `alto.aqui` (dominios de producción futura). El dominio local de desarrollo es `synticorex.test`. El middleware `IdentifyTenant` resuelve por estos dominios, pero ningún tenant tiene esos dominios — hace inútil el middleware localmente.

Además, `admin_domains` tiene `syntiweb.com` y `app.syntiweb.com` (producción) pero la app funciona en `synticorex.test`. No hay entrada `.test` en `admin_domains`.

**Acción:** Agregar al config una sección para develop local, o usar `.env` variables:
```php
'central_domains' => array_filter(array_merge(
    ['tu.menu', 'menu.vip'],
    explode(',', env('LOCAL_DOMAINS', ''))
)),
```

---

### 11. `Tenant::$fillable` — `color_palette_id` campo legacy

**Archivo:** `app/Models/Tenant.php`
**Problema:** `color_palette_id` permanece en `$fillable` del modelo. El sistema de ColorPalette fue reemplazado por FlyonUI themes (`theme_slug` en TenantCustomization). La relación `colorPalette()` también existe en el modelo. Es código zombie que podría confundir.

**Acción:** Eliminar `color_palette_id` de `$fillable`, deprecar/eliminar la relación `colorPalette()`, crear migración para eliminar la columna.

---

### 12. `custom_palette` — Almacenado en JSON anidado profundo

**Problema:** La paleta custom del Plan 3 se guarda en:
```
tenant.settings['engine_settings']['visual']['custom_palette']['primary']
tenant.settings['engine_settings']['visual']['custom_palette']['secondary']
```

Esto se lee en `DashboardController`, `TenantRendererController` y `landing/base.blade.php` — 3 lugares duplicando la misma ruta de array. Un typo en cualquiera rompe silenciosamente.

**Acción:** Extraer a un método en `Tenant` o `TenantCustomization`:
```php
public function getCustomPalette(): ?array {
    return data_get($this->settings, 'engine_settings.visual.custom_palette');
}
```

---

### 13. `$allPayMeta` — Configuración de negocio en el controlador

**Archivo:** `app/Http/Controllers/DashboardController.php` líneas ~100–115
**Problema:** El array de métodos de pago con sus iconos y labels está hardcodeado dentro de `DashboardController::index()`. Si se agrega un método de pago nuevo, hay que modificar el controlador. Debería estar en `config/payment-methods.php` o en el modelo `TenantCustomization`.

```php
// ACTUAL — en el controlador:
$allPayMeta = [
    'pago_movil' => ['icon' => 'tabler--phone', 'label' => 'Pago Móvil'],
    'efectivo'   => ['icon' => 'tabler--cash',  'label' => 'Efectivo'],
    // ...12 métodos
];
```

**Acción:** Mover a `config/payment-methods.php`.

---

### 14. Schemas blueprint (restaurant/store/health/professional) — Sin `$schema` del controller

**Problema:** En `landing/base.blade.php`:
```blade
@include('landing.schemas.restaurant', compact('tenant'))
```
Solo pasa `$tenant`. Pero `local-business` ya fue migrado a recibir `$schema` pre-construido. Los 4 schemas restantes aún acceden a Eloquent relationships directamente en Blade (`$tenant->customization->logo_filename`) — potencial N+1 si la relación no está eager-loaded en ese contexto.

**Acción:** Extender `buildSchema(Tenant $tenant, string $type): array` para los 4 tipos y hacer el include con `$schema`.

---

## 🟡 MEDIOS

### 15. `resources/views/dashboard/_archive/` — Directorio de código muerto

Contiene 5 versiones antiguas de componentes:
- `_archive/config-section.blade.php`
- `_archive/design-section.blade.php`
- `_archive/info-section.blade.php`
- `_archive/products-section.blade.php`
- `_archive/services-section.blade.php`

**Acción:** Eliminar. Git tiene el historial.

---

### 16. `dashboard/index.blade.php` — Block `<style>` de ~100 líneas inline

**Líneas:** 18–90 aprox.
**Problema:** El CSS del sidebar, tabs, modals, animaciones y form focus está todo en un `<style>` inline en el HTML. En Tailwind v4, esto debería estar en `app.css` como utilidades o en una capa separada.

Además mezcla CSS plano con variables de FlyonUI (`--color-base-100`) y variables custom (`--synti`).

**Acción:** Mover a `resources/css/dashboard.css` e importar con `@vite`.

---

### 17. `@php` blocks con lógica compleja en vistas

**Afecta múltiples archivos.** La regla del proyecto prohíbe lógica compleja en Blade. Ejemplos graves:

- `landing/partials/contact.blade.php` — cálculo de `$hasMaps`, `$embedUrl`, `$phone2`, `$contactTitle` en `@php`
- `landing/partials/about.blade.php` — construcción de URLs completas en `@php`
- `landing/partials/product-card.blade.php` — construcción de array `$allImages` en `@php`
- `dashboard/components/message-section.blade.php` — acceso a `$planRequired` con comparación en `@php`

**Acción:** Mover cada bloque de lógica al controlador correspondiente o a un ViewComposer.

---

### 18. `TenantRendererController::show()` — `compact()` con 30+ variables

**Línea ~155:**
```php
return view('landing.base', compact(
    'tenant', 'plan', 'products', 'services', 'dollarRate', 'themeSlug', 
    'meta', 'customization', 'currencySettings', 'displayMode', 
    'savedDisplayMode', 'showReference', 'showBolivares', 'hidePrice', 
    'trackingQRSmall', 'trackingShortlink', 'showHoursIndicator', 
    'isOpen', 'closedMessage', 'blueprint', 'schema'
));
```

Difícil de rastrear qué variables existen en la vista. Idéntico problema en `DashboardController`.

**Acción:** Usar DTOs o ViewData objects, o al menos agrupar en arrays semánticos:
```php
$currency = compact('displayMode', 'savedDisplayMode', 'showReference', 'showBolivares', 'hidePrice', 'currencySettings');
$tracking = compact('trackingQRSmall', 'trackingShortlink');
return view('landing.base', compact('tenant', 'plan', 'products', 'services', 'dollarRate', 'themeSlug', 'meta', 'customization', 'currency', 'tracking', 'showHoursIndicator', 'isOpen', 'closedMessage', 'blueprint', 'schema'));
```

---

### 19. Google Fonts cargadas dos veces

**Archivos:** `resources/css/app.css` + `resources/views/landing/base.blade.php`

`app.css` carga `Geist` y `Public Sans`, y `landing/base.blade.php` también las carga en el `<head>` con `<link>`. Doble request, doble render blocking.

**Acción:** Las fuentes en `app.css` son suficientes (Vite las inlina en el CSS bundle). Eliminar los `<link>` de Google Fonts en `base.blade.php`.

---

### 20. `landing/base.blade.php` — Lógica de tema en `@php`

**Líneas 2–5:**
```blade
@php
$customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? null;
$effectiveTheme = $customPalette ? 'custom' : $themeSlug;
@endphp
```

Esta lógica debería resolverse en `TenantRendererController` y pasarse como `$effectiveTheme` directamente.

---

### 21. `ImageUploadController` — Retorna URLs con `asset()` relativas

**Archivo:** `app/Http/Controllers/ImageUploadController.php` líneas ~55, 99
```php
$url = asset('storage/tenants/' . $tenantId . '/' . $filename);
```

En producción con dominios custom o CDN, `asset()` puede devolver la URL incorrecta. `Storage::url()` es más robusto.

---

### 22. `DollarRateService` — Fallback hardcodeado `36.50`

**Archivo:** `app/Services/DollarRateService.php`
```php
return 36.50; // fallback hardcoded
```

Si la tasa sube a 50 o 100, el fallback dará precios incorrectos silenciosamente.

**Acción:** Mover a `config('app.dollar_rate_fallback', 36.50)` o leer el último valor de DB.

---

### 23. `TenantCustomization::getSectionsOrder()` — Normalización de formato inline

**Archivo:** `app/Models/TenantCustomization.php` líneas ~80–100
**Problema:** El método normaliza el formato antiguo (string) al nuevo (array) en tiempo de ejecución, en cada llamada. Esto es una migración de datos disfrazada de lógica de negocio.

```php
if (is_string($section)) {
    return ['name' => $section, 'visible' => true, 'order' => 0];
}
```

**Acción:** Crear una migración de datos para convertir todos los registros al nuevo formato, y eliminar el branch de normalización.

---

## 🟢 BAJOS

### 24. Inline `style=""` en dashboard components

Múltiples componentes mezclan Tailwind con estilos inline para colores y fuentes:
```blade
style="font-family:'Plus Jakarta Sans',sans-serif"
style="color:#4D8FFF"
style="background:linear-gradient(135deg,...)"
```

Se podría usar `@apply` en CSS o clases Tailwind personalizadas.

---

### 25. `landing/schemas/restaurant.blade.php` — Mezcla `Js::from()` con JSON

Usa `Js::from()` para escape seguro pero luego mezcla con `@if` que genera JSON inválido. Inconsistente con el fix de `local-business`.

---

### 26. `catch (\Exception` vs `catch (Throwable` — Inconsistente

- `TenantRendererController`: usa `catch (Throwable $e)` ✅
- `DashboardController`, `ImageUploadController`, `AnalyticsController`: usan `catch (\Exception $e)` ⚠️

`\Exception` no captura errores de PHP 8 (`TypeError`, `Error`, etc.). Debería ser `Throwable` en todo el proyecto.

---

### 27. `routes/web.php` — Comentario con path local absoluto

**Línea 3:**
```php
// C:\laragon\www\synticorex\routes\web.php
```

Comentario de path de Windows en código que va a git. Cosmético pero innecesario.

---

### 28 & 29. `hero.blade.php` y `footer.blade.php` sin `-v2` — Versiones legacy activas

Existen `hero.blade.php` y `footer.blade.php` junto a `hero-*-v2.blade.php` y `footer-v2.blade.php`. Las versiones v1 probablemente no se usan pero siguen ahí. Verificar y eliminar si están obsoletas.

---

## PLAN DE REFACTOR RECOMENDADO

### Sprint 1 — Seguridad y Críticos (1-2 días)
1. Agregar `auth` middleware a todas las rutas `/tenant/{id}/...`
2. Corregir `IdentifyTenant` (columnas correctas ó eliminarlo)
3. Agregar `declare(strict_types=1)` a los 18 archivos faltantes
4. Corregir los 4 schemas (restaurant/store/health/professional) como local-business

### Sprint 2 — Arquitectura (3-4 días)
5. Dividir `DashboardController` en 5 controladores
6. Crear `Plan::OPORTUNIDAD/CRECIMIENTO/VISION` y métodos semánticos en `Tenant`
7. Mover `$allPayMeta` a `config/payment-methods.php`
8. Resolver `hero_filename` vs `hero_main_filename`

### Sprint 3 — Limpieza y Performance (2-3 días)
9. Combinar Google Fonts en 1 request
10. Mover `<style>` inline del dashboard a `app.css` o `dashboard.css`
11. Eliminar `_archive/` directory
12. Crear helper `tenantStorageUrl()` para storage
13. Resolver el mismatch de `config/tenancy.php`

### Sprint 4 — Calidad (1-2 días)
14. Mover lógica `@php` de vistas a controladores/ViewComposers
15. Eliminar campo legacy `color_palette_id`
16. Estandarizar `catch (Throwable)` en todo el proyecto
17. Crear método `getCustomPalette()` en modelo

---

## NOTAS DE ESTILO AI DETECTADO

| Patrón | AI Probable |
|--------|-------------|
| Comentarios con `═══════` y secciones decorativas | **Opus/Claude** |
| `@php` blocks con lógica de negocio en vistas | **Gemini** |
| Boilerplate completo sin `strict_types=1` | **Copilot** (scaffolding) |
| `catch (\Illuminate\Validation\ValidationException` específico dentro de `catch (\Exception` | **Copilot** |
| Arrays de configuración extensos hardcoded en controller | **Gemini** |
| Documentación PHPDoc completa en métodos de servicio | **Opus** |
| Magic numbers `>= 2`, `=== 3` sin constantes | **Gemini/Copilot** |
| `Log::debug/info/error` sistemático | **Opus** |

---

*Fin del audit — 29 issues identificados*
