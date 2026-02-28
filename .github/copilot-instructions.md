# SYNTIWEB - Instrucciones para GitHub Copilot

## PROYECTO
SaaS multitenant Laravel 12. Landing pages dinámicas para negocios venezolanos.
Ruta local: C:\laragon\www\synticorex\

## STACK REAL
Laravel 12.51, PHP 8.3, MySQL, FlyonUI 2.4.1, Tailwind 4.2, Alpine.js 3.4.2, Vite 7, Intervention Image 3.11, QR: simplesoftwareio/simple-qrcode, Blade puro, JS vanilla.

## REGLAS CRÍTICAS
- declare(strict_types=1) en TODO archivo PHP
- NUNCA bajar versión de FlyonUI (mantener 2.4.1) ni Tailwind (mantener 4.2)
- NUNCA usar asset() → siempre @vite()
- NUNCA lógica compleja en Blade → va en Model/Controller
- Tenant isolation: siempre filtrar por tenant_id
- Early return pattern obligatorio
- Eager loading obligatorio (evitar N+1)
- Imágenes: max 800px, convertir a WebP

## MULTITENANCY
- Un tenant = un subdominio O un dominio personalizado
- Detección por subdomain o custom_domain en middleware IdentifyTenant
- Storage: storage/tenants/{tenant_id}/

## PLANES
- Plan 1 OPORTUNIDAD $99/año: 6 productos, 3 servicios, 10 paletas
- Plan 2 CRECIMIENTO $149/año: 12 productos, 6 servicios, 17 paletas
- Plan 3 VISIÓN $199/año: 18 productos (slider 3 fotos c/u), 9 servicios, 17 paletas + custom infinito

## SECCIONES POR PLAN
- Plan 1: hero, products, services, contact, payment_methods, cta, footer
- Plan 2: + about, testimonials
- Plan 3: + faq, branches
- Lógica en: app/Models/TenantCustomization.php → canAccessSection()
- Secciones ordenables y con visibilidad toggle por tenant
- Variantes por sección: hero(fullscreen), products(grid3), services(cards/spotlight), about(split), faq(accordion), cta(centered)

## TEMAS
- Plan 1: 10 paletas FlyonUI
- Plan 2: 17 paletas FlyonUI
- Plan 3: 17 paletas + custom infinito (4 colores: primary, secondary, accent, base)
- data-theme siempre en <html>, nunca en divs internos
- Custom palette: data-theme="custom" + CSS variables inline en <style>
- Variables: --color-primary, --color-secondary, --color-accent, --color-base-100

## SISTEMA MONEDA
- Símbolo REF (no $)
- Modos: reference_only / bolivares_only / both_toggle / hidden
- CURRENCY_MODE resuelto en base.blade.php

## DASHBOARD
- Activación: Alt+S desktop / long press móvil
- PIN de acceso
- 6 tabs: Info, Productos, Servicios, Diseño, Analytics, Config

## ARCHIVOS CLAVE
- TenantRendererController.php → renderiza landing
- DashboardController.php → 6 tabs CRUD
- resources/views/landing/base.blade.php → layout principal
- resources/views/dashboard/index.blade.php → dashboard ~2000 líneas

## PROHIBIDO
- Node.js en servidor (solo build local)
- exec() o shell_exec() en producción
- {!! !!} salvo Schema.org generado internamente
- eval() o new Function() en JS