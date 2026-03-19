<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AiDoc;
use Illuminate\Database\Seeder;

class AiDocSeeder extends Seeder
{
    public function run(): void
    {
        AiDoc::truncate();

        $docs = array_merge(
            $this->sharedDocs(),
            $this->studioDocs(),
            $this->foodDocs(),
            $this->catDocs(),
            $this->adminDocs(),
        );

        foreach ($docs as $doc) {
            AiDoc::create($doc);
        }

        $this->command->info('AiDocSeeder: ' . count($docs) . ' documentos cargados.');
        $this->command->info('  shared: '  . count($this->sharedDocs()));
        $this->command->info('  studio: '  . count($this->studioDocs()));
        $this->command->info('  food:   '  . count($this->foodDocs()));
        $this->command->info('  cat:    '  . count($this->catDocs()));
        $this->command->info('  admin:  '  . count($this->adminDocs()));
    }

    // =========================================================================
    // SHARED — aplican a todos los productos
    // =========================================================================

    /** @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}> */
    private function sharedDocs(): array
    {
        return [
            // ─── NUEVAS ENTRADAS TÉCNICAS (shared) ───
            [
                'slug'        => 'sistema_moneda',
                'title'       => 'Sistema de moneda — REF, bolívares, EUR y modos de display',
                'product'     => 'shared',
                'source_file' => 'app/Http/Controllers/TenantRendererController.php',
                'content'     => 'El sistema de moneda define cómo se muestran los precios en la landing pública del tenant. Los precios se almacenan en USD (price_usd en la tabla products). La moneda de referencia es REF (una unidad igual a 1 USD). El símbolo REF nunca es reemplazado por $.

Modos disponibles configurables desde Dashboard de cada tenant en la sección Configuración:
reference_only: Muestra solo el precio en REF. Ejemplo: REF 15.00
bolivares_only: Muestra solo el precio en bolívares (calculado con tasa BCV del dia). Ejemplo: Bs. 547.50
both_toggle: Muestra precio en REF y botón para alternar a Bs. Es el modo por defecto.
euro_toggle: Muestra precio en euros (calculado con tasa EUR del BCV) y botón para alternar a Bs.
hidden: Oculta el precio y muestra el texto Mas Info.

La configuración se guarda en tenant.settings bajo la clave engine_settings.currency.display.saved_display_mode. Se lee en TenantRendererController.php en el metodo show(). Las variables resultantes ($showReference, $showBolivares, $showEuro, $hidePrice) se pasan a la vista.

La tasa de conversion usada es DollarRateService::getCurrentRate() para USD y getCurrentEuroRate() para EUR. Ambas tasas vienen del BCV via API con cache de 1 hora en Redis/database.

No hay pasarela de pago. SYNTIweb no cobra a los clientes del tenant. Los precios son solo informativos.',
            ],
            [
                'slug'        => 'tasa_bcv_automatica',
                'title'       => 'Tasa BCV automática — Actualización y fallback',
                'product'     => 'shared',
                'source_file' => 'app/Services/DollarRateService.php',
                'content'     => 'La tasa BCV (Banco Central de Venezuela) se obtiene automaticamente via API externa y se cachea en la base de datos. El servicio es DollarRateService ubicado en app/Services/DollarRateService.php.

Fuentes consultadas en orden de prioridad (failover automatico):
1. dolarapi.com: ve.dolarapi.com/v1/dolares/oficial (campo promedio)
2. pydolarve.org: pydolarve.org/api/v1/dollar?monitor=bcv (campo price)

Para EUR:
1. dolarapi.com: ve.dolarapi.com/v1/euros/oficial
2. pydolarve.org: pydolarve.org/api/v1/dollar?page=bcv&monitor=euro

Cache TTL: 3600 segundos (1 hora). Clave de cache: dollar_rate_current (USD) y euro_rate_current (EUR). Si el cache esta vacio se lee desde la tabla dollar_rates filtrando por currency_type y is_active=true. Si la tabla tambien esta vacia, se usa el valor del env DOLLAR_FALLBACK_RATE (por defecto 40.00).

Actualizar manualmente: Desde el dashboard del tenant ir a Configuracion. Hay un widget visible mostrando tasa USD actual y tasa EUR. El boton Actualizar llama al endpoint POST /api/dollar-rate/refresh que requiere autenticacion de admin. Este endpoint invoca DollarRateService::fetchAndStore() y luego propagateRateToTenants() para sincronizar la tasa a todos los tenants activos.

La tasa se propaga automaticamente a todos los tenants cuando cambia mas de 10% (MAX_RATE_CHANGE_PERCENT). El campo dollar_rate en tenant.settings se actualiza masivamente.',
            ],
            [
                'slug'        => 'qr_dinamico',
                'title'       => 'QR dinamico con tracking — Shortlink y analytics',
                'product'     => 'shared',
                'source_file' => 'app/Services/QRService.php',
                'content'     => 'Cada tenant tiene un codigo QR unico que lleva a su landing page y registra eventos de escaneo en analytics. El servicio es QRService en app/Services/QRService.php. Usa la libreria simplesoftwareio/simple-qrcode.

Formato del shortlink: https://syntiweb.me/t/{tenant_id}/{codigo_unico}. El codigo unico se genera como los primeros 8 caracteres del hash SHA-256 de: tenant_id + app.key + qr_tracking. Es determinista (mismo tenant = mismo codigo siempre).

Formatos de generacion:
generateQR($tenantId, $size=300): devuelve SVG del QR. Se usa en el dashboard (panel interno).
generateQRPNG($tenantId, $size=300): devuelve binario PNG. Se usa para descarga.
getTrackingShortlink($tenantId): devuelve solo la URL del shortlink.

El QR se genera en tiempo real en DashboardController::index() y se pasa como $trackingQR (SVG, tamano 300) y $trackingQRSmall (150) al TenantRendererController para el panel flotante de la landing.

Los escaneos se registran como evento qr_scan en la tabla analytics_events via AnalyticsController::track(). El QR aparece en el panel de administracion bajo la seccion QR del dashboard y en el panel flotante de la landing (visible solo tras ingresar el PIN).',
            ],
            [
                'slug'        => 'estado_negocio_abierto_cerrado',
                'title'       => 'Estado abierto/cerrado — Horario y BusinessHoursService',
                'product'     => 'shared',
                'source_file' => 'app/Services/BusinessHoursService.php',
                'content'     => 'Cada tenant puede configurar su horario de atencion. El sistema determina automaticamente si el negocio esta abierto o cerrado y muestra un indicador visual en la landing publica. El servicio es BusinessHoursService en app/Services/BusinessHoursService.php.

Configuracion: Dashboard del tenant, tab Tu Informacion, sub-tab Horario. Se configuran dias de la semana y rangos de hora de apertura y cierre. El horario se guarda en tenant.business_hours (campo JSON en la tabla tenants).

La funcionalidad se activa por plan: todos los planes la tienen disponible. Dentro de la configuracion hay un checkbox Activar indicador de horario.

En la landing: TenantRendererController llama a isHoursFeatureEnabled() para saber si mostrar el indicador, y luego a isOpen() para el estado actual. Las variables $showHoursIndicator e $isOpen se pasan a la vista. Si el negocio esta cerrado se muestra un banner con el mensaje configurado en business_info.closed_message (desde tenant.settings).

El toggle abierto/cerrado manual tambien existe: el dueno puede forzar el estado desde el panel flotante sin importar el horario. Ruta: POST /tenant/{tenantId}/toggle-status en TenantRendererController::toggleStatus() protegida por PIN (throttle: 5 intentos por minuto).',
            ],
            [
                'slug'        => 'pin_dashboard_autenticacion',
                'title'       => 'PIN de acceso al dashboard — Autenticacion por celular',
                'product'     => 'shared',
                'source_file' => 'app/Http/Controllers/TenantRendererController.php',
                'content'     => 'El dashboard del tenant se protege con un PIN de 4 digitos. No requiere cuenta de usuario para operar — solo el PIN. Esto permite que el dueno del negocio administre desde su celular sin login tradicional.

Como activar el panel: En escritorio presionar Alt+S estando en la landing del tenant. En movil mantener presionado el logo del negocio durante 3 segundos. Aparece un modal que solicita el PIN de 4 digitos.

Verificacion del PIN: Ruta POST /tenant/{tenantId}/verify-pin en TenantRendererController::verifyPin(). Rate limit: 5 intentos por minuto (middleware throttle). El PIN se almacena hasheado con bcrypt en tenant.edit_pin (nunca en texto plano). Al crear un tenant por el wizard se asigna el PIN por defecto 1234 hasheado.

Cambiar el PIN: Dashboard del tenant, tab Configuracion, seccion PIN de Acceso. Campos: PIN actual, Nuevo PIN (4 digitos), Confirmar nuevo PIN. Se valida que el PIN actual sea correcto antes de aceptar el cambio.

Si el dueno olvidó el PIN: contactar soporte de SYNTIweb. No hay recuperacion automatica. El admin puede restablecerlo desde Filament via TenantResource editando el campo edit_pin directamente.

El PIN protege acciones sensibles: toggle abierto/cerrado, toggle WhatsApp activo, y acceso completo al panel de gestion.',
            ],
            [
                'slug'        => 'upload_imagenes_webp',
                'title'       => 'Upload de imagenes — Conversion automatica a WebP',
                'product'     => 'shared',
                'source_file' => 'app/Services/ImageUploadService.php',
                'content'     => 'Todas las imagenes subidas a SYNTIweb se convierten automaticamente a formato WebP para optimizar el rendimiento. El servicio es ImageUploadService en app/Services/ImageUploadService.php. Usa Intervention Image 3.11 con driver GD.

Restricciones de subida:
Formatos aceptados: JPEG, PNG, WebP (MIME: image/jpeg, image/png, image/webp).
Tamano maximo: 2MB (2097152 bytes). Si el archivo supera este limite se devuelve error.

Anchos maximos por tipo de imagen:
logo: 400px
hero: 1600px
product: 1000px
service: 1000px
hero-slot-1 a hero-slot-5: 1600px

Si la imagen es mas ancha que el maximo, se redimensiona manteniendo aspecto. Calidad WebP: 90.

Nombres de archivo generados (deterministicos, sobreescriben la version anterior):
logo.webp, hero.webp, product_01.webp a product_99.webp, service_01.webp a service_99.webp, hero_slot_1.webp a hero_slot_5.webp.

Ubicacion en disco: storage/app/public/tenants/{tenant_id}/{filename}. URL publica via symlink: storage/tenants/{tenant_id}/{filename}.

El controlador que orquesta las subidas es ImageUploadController en app/Http/Controllers/ImageUploadController.php. Metodos: uploadLogo, uploadHero, uploadProduct, uploadService, uploadHeroSlot. Todos verifican que el tenant exista, este activo, y que el producto/servicio pertenezca al tenant (validacion tenant_id).',
            ],
            [
                'slug'        => 'paletas_color_sistema',
                'title'       => 'Sistema de paletas de color — Temas Preline por industria',
                'product'     => 'shared',
                'source_file' => 'app/Services/PrelineThemeService.php',
                'content'     => 'SYNTIweb usa el sistema de temas de Preline UI para aplicar identidad visual al tenant. El tema se guarda en tenant_customization.theme_slug y se inyecta como data-theme="theme-{slug}" en el HTML de la landing.

Grupos de temas disponibles por industria (30+ temas):
Clean: default, harvest, ocean, cashmere
Fresh: olive, retro, bubblegum
Bold: autumn
Dark: moon
Comida: sabor-tradicional, fuego-urbano, parrilla-moderna, casa-latina
Dulces: rosa-vainilla, pistacho-suave, cielo-dulce, chocolate-caramelo
Salud: azul-confianza, verde-calma
Autoridad: azul-profesional, ejecutivo-oscuro, prestigio-clasico
Oficios: industrial-pro, negro-impacto, metal-urbano
Belleza: nude-elegante, rosa-studio
Fitness: barber-clasico, fuerza-roja, verde-potencia, azul-electrico
Educacion: azul-academico, verde-progreso, claro-simple

Disponibilidad por plan (studio):
Plan Oportunidad: 10 paletas.
Plan Crecimiento: 17 paletas.
Plan Vision: 17 paletas + paleta personalizada (4 colores hex propios).

La paleta personalizada se guarda en tenant.settings bajo engine_settings.visual.custom_palette. Cuando existe, el sistema inyecta las variables CSS --brand-* como inline style en el <head> de la landing.

Configuracion: Dashboard, tab Como Se Ve (tercer icono). Se muestra un grid con vista previa de 4 colores por tema. Al seleccionar un tema se llama a la API PUT /tenant/{id}/customization y se guarda el theme_slug.',
            ],
            [
                'slug'        => 'analytics_basico',
                'title'       => 'Analytics — Seguimiento de visitas y eventos',
                'product'     => 'shared',
                'source_file' => 'app/Http/Controllers/AnalyticsController.php',
                'content'     => 'SYNTIweb registra eventos de comportamiento de visitantes en la tabla analytics_events. El controlador es AnalyticsController en app/Http/Controllers/AnalyticsController.php.

Eventos registrados (event_type validos):
pageview: Vista de la pagina.
click_whatsapp: Clic en boton WhatsApp.
click_call: Clic en boton llamada telefonica.
click_toggle_currency: Clic en toggle de moneda.
time_on_page: Cada 30 segundos de permanencia.
qr_scan: Escaneo del QR de tracking.

Rate limit: maximo 100 eventos por minuto por tenant. Clave de cache: analytics_rate_limit:{tenant_id}.

Privacidad: La IP del visitante se guarda hasheada con SHA-256 + app.key. Nunca se almacena la IP en texto plano. El user_agent si se guarda.

KPIs disponibles en el dashboard (tab Pulso del Negocio):
Plan Oportunidad: 3 KPIs basicos (visitantes hoy, visitantes 7 dias, clics WhatsApp).
Plan Crecimiento: 6 KPIs (agrega escaneos QR, llamadas, tiempo promedio).
Plan Vision: 7 KPIs completos + grafico de lineas Chart.js (visitantes por dia) + grafico dona (distribucion eventos).

El tiempo promedio en pagina se estima multiplicando el conteo de eventos time_on_page por 30 segundos.

Los datos del grafico se calculan en AnalyticsController::getData() con ventana de 7 dias usando timezone America/Caracas.',
            ],
            [
                'slug'        => 'billing_reportar_pago',
                'title'       => 'Facturacion — Como reportar un pago de suscripcion',
                'product'     => 'shared',
                'source_file' => 'app/Http/Controllers/BillingController.php',
                'content'     => 'Los tenants pagan su suscripcion a SYNTIweb manualmente (no hay cobro automatico). El flujo es: el cliente paga por uno de los canales habilitados, sube el comprobante en el dashboard, y el equipo de SYNTIweb lo revisa y aprueba.

Canales de pago de SYNTIweb aceptados actualmente:
Pago Movil: Banco Banesco (0134), cedula V-28123456, telefono 0412-0001234.
PayPal: pagos@syntiweb.com
Zinli: pagos@syntiweb.com

Como reportar el pago: Dashboard del tenant, tab Configuracion, seccion Facturacion. Hacer clic en Reportar pago. Campos requeridos: canal de pago (select), referencia del pago (max 100 chars), fecha del pago (no puede ser futura), comprobante (archivo JPG/PNG/WebP/PDF, max 5MB).

El sistema valida que no haya un pago pendiente de revision previo. Si ya hay uno en status pending_review se rechaza el nuevo reporte con mensaje de error.

El comprobante se almacena localmente en storage/app/tenants/{tenant_id}/receipts/ (no publico). Se crea una factura en la tabla invoices con numero SYNTI-YYYY-XXXXX, status pending_review, y el periodo calculado desde la fecha de vencimiento actual.

Estados de factura: pending (no reportado), pending_review (enviado, esperando revision), paid (aprobado por admin), rejected (rechazado por admin), cancelled.

El dashboard muestra el historico de hasta 20 facturas con status_label y status_color. Si la suscripcion vence y no se renueva, el tenant pasa a estado frozen (pagina congelada).',
            ],
            [
                'slug'        => 'onboarding_flujo_selector',
                'title'       => 'Onboarding — Wizard de creacion de negocio',
                'product'     => 'shared',
                'source_file' => 'app/Http/Controllers/OnboardingController.php',
                'content'     => 'El onboarding es el asistente de configuracion inicial para crear un negocio en SYNTIweb. Ruta: GET /onboarding (selector de producto) en OnboardingController::selector(). Requiere autenticacion para completar (middleware auth).

Tres productos disponibles en el selector:
SYNTIstudio: Presencia web completa para cualquier negocio. Ruta wizard: GET /onboarding/studio.
SYNTIfood: Menu digital para negocios de comida. Ruta wizard: GET /onboarding/food. Llama a OnboardingController::food().
SYNTIcat: Catalogo de productos para tiendas. Ruta wizard: GET /onboarding/cat. Llama a OnboardingController::cat().

Rutas de guardado:
POST /onboarding/studio/guardar -> OnboardingController::store() (crea tenant studio).
POST /onboarding/food/guardar -> OnboardingController::storeFood() (crea tenant food).
POST /onboarding/cat/guardar -> OnboardingController::storeCat() (crea tenant cat).

El wizard de studio valida: business_name, business_segment (21 categorias), plan_id (debe ser blueprint studio), subdomain (alpha_dash, unico en BD), slogan, description, content_blocks.hero, about_text, phone, whatsapp_sales (obligatorio), email, city, value_prop_1/2/3.

Al crear el tenant: se ejecuta en una transaccion DB. Se crea el Tenant, se crea TenantCustomization (con hero_layout gradient y theme_slug segun segmento via PrelineThemeService::getThemeForSegment()), se asigna edit_pin hasheado (1234 por defecto), subscription_ends_at = now() + 1 año, status = active.

Validacion de subdominio en tiempo real: GET /onboarding/subdomain-check?subdomain=X devuelve JSON con available y subdomain normalizado.',
            ],
            // ─── ENTRADAS LEGACY (shared) ───
            [
                'slug'        => 'que-es-syntiweb',
                'title'       => '¿Qué es SYNTIweb?',
                'product'     => 'shared',
                'source_file' => 'primeros-pasos/que-es-syntiweb.mdx',
                'content'     => 'SYNTIweb es una plataforma que le da a cualquier negocio venezolano su propia pagina web profesional, menu digital o catalogo de productos sin necesidad de saber programar ni contratar a nadie. Tu cliente te busca en Google, escanea tu QR, o recibe tu link por WhatsApp y ve tu negocio como un profesional.

Los 3 productos de SYNTIweb son:
SYNTIstudio: Pagina web completa para cualquier negocio (barberia, clinica, academia, consultora, marca personal).
SYNTIfood: Menu digital para restaurantes, areperas, pastelerias y food trucks. 1 foto por categoria mas lista de platos con precio. Pedido directo por WhatsApp.
SYNTIcat: Catalogo con carrito para tiendas de ropa, proveedores y comercios. Los clientes eligen productos, acumulan en carrito y envian el pedido por WhatsApp con codigo de seguimiento.

SYNTIweb NO procesa pagos. No es pasarela de pago. No cobra a los clientes del negocio. Es una plataforma de presencia digital. El cliente ve la pagina, elige lo que quiere y contacta por WhatsApp.

Moneda de referencia: REF (equivale a 1 USD). Los precios se muestran en REF por defecto y opcionalmente en bolivares con tasa BCV automatica.

Cómo funciona: 1) Elige tu producto en syntiweb.com/onboarding 2) Completa el asistente de configuracion 3) Tu pagina se publica en tunegocio.syntiweb.com 4) Administra desde el panel flotante con Alt+S o long press logo en movil, mas PIN de 4 digitos.',
            ],
            [
                'slug'        => 'planes-y-precios',
                'title'       => 'Planes y precios de SYNTIweb',
                'product'     => 'shared',
                'source_file' => 'app/Services/PlanFeatureService.php',
                'content'     => 'SYNTIstudio (para cualquier negocio que necesite pagina web completa):
Plan Oportunidad: REF 99 al año. 20 productos, 3 servicios, 1 foto por producto, 10 paletas.
Plan Crecimiento: REF 149 al año. 50 productos, 6 servicios, 3 hero slots, 17 paletas.
Plan Vision: REF 199 al año. 200 productos, 9 servicios, 5 hero slots, paleta personalizada.

SYNTIfood (para restaurantes y negocios de comida):
Plan Basico: REF 12 al mes. 50 items, 6 fotos de categoria. Sin categorias ni pedido rapido.
Plan Semestral: REF 45 por 6 meses. 100 items, 12 fotos, con categorias, BCV, badges.
Plan Anual: REF 69 al año. 150 items, 18 fotos. Incluye Pedido Rapido WhatsApp y Comandas.

SYNTIcat (para tiendas y catalogos):
Plan Basico: REF 12 al mes. 50 productos, 1 imagen. Sin carrito (solo boton WA directo).
Plan Semestral: REF 45 por 6 meses. 150 productos, 3 imagenes, carrito basico, variantes talla/color.
Plan Anual: REF 69 al año. 250 productos, 6 imagenes, carrito completo mas Mini Order SC-XXXX.

Los precios en REF equivalen a USD. El equivalente en bolivares se calcula automaticamente con la tasa BCV. Se configura el modo de moneda desde Dashboard, seccion Configuracion, apartado Moneda.',
            ],
            [
                'slug'        => 'glosario',
                'title'       => 'Glosario de términos SYNTIweb',
                'product'     => 'shared',
                'source_file' => 'referencia/glosario.mdx',
                'content'     => 'Terminos generales: SYNTIweb es la plataforma completa. Landing es la pagina web publica del tenant (tunegocio.syntiweb.com). Dashboard es el panel de administracion flotante (Alt+S o long press logo mas PIN). Blueprint es el tipo de producto (studio, food, cat). Tenant es el negocio dentro de la plataforma. Subdominio es la direccion web del negocio.

Precios y moneda: REF es la referencia en dolares (REF 15 equivale a 15 USD). BCV es el Banco Central de Venezuela (tasa oficial para convertir REF a bolivares). Modos de moneda: reference_only (solo REF), bolivares_only (solo Bs.), both_toggle (REF y Bs. con toggle, modo por defecto), euro_toggle (EUR y Bs.), hidden (ocultar precios).

Dashboard tabs: Tu Informacion, Que Vendes, Como Se Ve, Tu Mensaje, Pulso del Negocio, Visual, Configuracion. PIN es el codigo de 4 digitos para acceder. Badge es la etiqueta del producto (Destacado, Hot, New, Promo). Paleta es el esquema de colores. CTA es el llamado a accion.

IMPORTANTE: Medios de pago son chips informativos, NO pasarela de pago. SYNTIweb NO procesa transacciones del negocio con sus clientes.',
            ],
        ];
    }

    // =========================================================================
    // STUDIO — SYNTIstudio (pagina web para cualquier negocio)
    // =========================================================================

    /** @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}> */
    private function studioDocs(): array
    {
        return [
            [
                'slug'        => 'studio_info_negocio_configuracion',
                'title'       => 'SYNTIstudio — Tab Tu Información: datos, horario y redes',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/info-section.blade.php',
                'content'     => 'El tab Tu Informacion es el primero del panel de administracion de SYNTIstudio. Tiene 3 sub-tabs: Negocio, Horario, Redes Sociales.

Sub-tab Negocio: campos Nombre del negocio (obligatorio), Eslogan, Subdominio (solo lectura, no editable), Telefono, WhatsApp ventas (obligatorio, prefijo +58 Venezuela), WhatsApp soporte (Plan Crecimiento y Vision), Email, Direccion, Ciudad, Maps URL (Plan Crecimiento y Vision).

Sub-tab Horario: configuracion de dias activos y rangos de hora de apertura y cierre para cada dia de la semana. Checkbox Activar indicador de horario que muestra estado abierto/cerrado en la landing. Campo Mensaje cuando estamos cerrados (customizable).

Sub-tab Redes Sociales: configuracion de links de redes. Plan Oportunidad permite hasta 2 redes (Instagram, Facebook). Plan Crecimiento hasta 6 (agrega TikTok, LinkedIn, YouTube, Twitter/X). Plan Vision cantidad ilimitada.

Los cambios se guardan via AJAX llamando a saveInfo(event) desde el formulario form-info. La respuesta actualiza la vista sin recargar. El subdominio se elige en el wizard de onboarding y no puede cambiarse despues.

Se puede personalizar el titulo y subtitulo de cada seccion de la landing desde este mismo tab (Hero title/subtitle, Products section, Services section, About section, FAQ section, etc.).',
            ],
            [
                'slug'        => 'studio_productos_crud',
                'title'       => 'SYNTIstudio — CRUD de productos con límites por plan',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/products-section.blade.php',
                'content'     => 'La seccion de productos de SYNTIstudio se administra desde Dashboard, tab Que Vendes. Muestra un grid de tarjetas con thumbnail, nombre, precio y estado activo.

Campos del producto: nombre (obligatorio), descripcion (opcional), precio USD/REF (obligatorio), imagen principal (JPG/PNG/WebP, max 2MB, auto WebP), estado activo (toggle), destacado (checkbox para priorizar en grid), badge (Destacado, Hot, New, Promo), posicion de orden.

Plan Vision (slug studio-vision): hasta 3 imagenes adicionales de galeria por producto via ProductImage (tabla product_images). El modal del producto en plan Vision muestra un slider con hasta 3 fotos.

Limites por plan:
Plan Oportunidad (studio-oportunidad): 20 productos, 1 imagen por producto.
Plan Crecimiento (studio-crecimiento): 50 productos, 1 imagen por producto.
Plan Vision (studio-vision): 200 productos, galeria de hasta 3 imagenes adicionales.

Cuando se alcanza el limite de productos el boton Agregar Producto se desactiva y aparece un mensaje con link a planes superiores. La verificacion se hace via checkAndOpenProductModal() en JavaScript comparando productos actuales con products_limit del plan.

En la landing publica: los productos activos se muestran en grid de 3 columnas (desktop). Los primeros 6 se muestran, Ver mas carga 6 mas. Los destacados (is_featured=true) aparecen primero. El precio se muestra segun el modo de moneda configurado. Boton consultar por WhatsApp en cada producto.',
            ],
            [
                'slug'        => 'studio_servicios_crud',
                'title'       => 'SYNTIstudio — CRUD de servicios con límites por plan',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/services-section.blade.php',
                'content'     => 'La seccion de servicios de SYNTIstudio se administra desde Dashboard, tab Que Vendes, debajo de los productos. Permite agregar servicios que ofrece el negocio (cortes, sesiones, asesorias, etc.).

Campos del servicio: nombre (obligatorio), descripcion (opcional), icono Tabler (selector de iconos Iconify) O imagen (solo Plan Crecimiento y Vision), texto CTA (boton de accion, ejemplo: Solicitar cita), link CTA (generalmente URL de WhatsApp), posicion de orden, estado activo (toggle).

Selector de modo visual global: Icono (tarjeta con icono grande al centro) vs Imagen (tarjeta con foto de fondo con overlay). Solo Plan Crecimiento y Vision pueden usar imagenes. Plan Oportunidad: solo modo Icono.

Limites por plan:
Plan Oportunidad (studio-oportunidad): 3 servicios.
Plan Crecimiento (studio-crecimiento): 6 servicios.
Plan Vision (studio-vision): 9 servicios.

En la landing: dos variantes de presentacion configurables en el tab Visual del dashboard: Cards (cards con icono/imagen, nombre y descripcion) y Spotlight (formato destacado con mas espacio). Los primeros 3 servicios visibles, Ver mas carga los adicionales.

No tiene precio en servicios (para precios usar productos). No tiene agendamiento, reservas, ni formulario de solicitud.',
            ],
            [
                'slug'        => 'studio_seccion_acerca_de',
                'title'       => 'SYNTIstudio — Sección Acerca de (About)',
                'product'     => 'studio',
                'source_file' => 'resources/views/landing/sections/about.blade.php',
                'content'     => 'La seccion Acerca de muestra la historia o propuesta de valor del negocio. Disponible en Plan Crecimiento y Vision. Plan Oportunidad no muestra esta seccion.

Configuracion desde el dashboard: tab Tu Mensaje, seccion Acerca de. Campos: texto de descripcion (hasta 1000 caracteres), imagen del equipo o local (WebP, max 800px ancho).

El contenido se guarda en: tenant_customization.about_text y tenant_customization.about_image_filename. El texto tambien puede venir de tenant_customization.content_blocks.about.text.

En la landing publica: layout de dos columnas (texto izquierda, imagen derecha en desktop). Variante visual configurable desde el tab Visual: split (imagen al lado). La imagen tiene border-radius y shadow para look moderno.

El titulo de la seccion es configurable desde el tab Tu Informacion, campo About section title.

Si no hay imagen subida se muestra solo el texto. Si no hay texto configurado, la seccion no se renderiza (sectionHasContent validado en la vista studio.blade.php).',
            ],
            [
                'slug'        => 'studio_hero_layouts',
                'title'       => 'SYNTIstudio — Layouts del Hero (portada)',
                'product'     => 'studio',
                'source_file' => 'resources/views/landing/templates/studio.blade.php',
                'content'     => 'El Hero es la seccion de portada en la parte superior de la landing. SYNTIstudio tiene 3 layouts de hero configurables desde el tab Visual del dashboard.

Layouts disponibles:
gradient: Fondo con gradiente usando los colores Brand del tema seleccionado. El texto (titulo y subtitulo) se muestra sobre el gradiente. No requiere imagen.
split: Imagen a la derecha, texto a la izquierda. Requiere imagen de hero subida. Layout de dos columnas.
fullscreen-v2: Imagen de fondo a pantalla completa con overlay oscuro y texto centrado con mayor impacto visual.

El layout se guarda en tenant_customization.hero_layout. Por defecto al crear un tenant se asigna gradient.

Imagenes de hero: se suben desde el tab Como Se Ve del dashboard (visual-section). Plan Oportunidad: 1 imagen de hero. Plan Crecimiento: hasta 3 imagenes (hero_main, hero_secondary, hero_tertiary). Plan Vision: hasta 5 imagenes (hero_slot_1 a hero_slot_5). Cuando hay multiples imagenes se muestra un slider automatico.

Titulo y subtitulo del hero: se configuran en el tab Tu Informacion, campos Hero title y Hero subtitle. Por defecto se usan business_name y slogan del tenant.',
            ],
            [
                'slug'        => 'studio_temas_visuales',
                'title'       => 'SYNTIstudio — Temas visuales y paletas de color por industria',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/design-section.blade.php',
                'content'     => 'SYNTIstudio incluye mas de 30 temas visuales organizados por categoria de negocio. Se configuran desde el dashboard, tab Como Se Ve (tercer icono del menu lateral). Cada tema aplica un conjunto de variables CSS --brand-* que definen colores, bordes y tipografia.

Categorias de temas:
Clean (negocio general): default, harvest, ocean, cashmere.
Fresh (creativos): olive, retro, bubblegum.
Bold (llamativos): autumn.
Dark (premium, elegantes): moon.
Comida: sabor-tradicional, fuego-urbano, parrilla-moderna, casa-latina.
Dulces/Pasteleria: rosa-vainilla, pistacho-suave, cielo-dulce, chocolate-caramelo.
Salud y clinica: azul-confianza, verde-calma.
Autoridad y finanzas: azul-profesional, ejecutivo-oscuro, prestigio-clasico.
Oficios y tecnicos: industrial-pro, negro-impacto, metal-urbano.
Belleza y estetica: nude-elegante, rosa-studio.
Fitness y deporte: barber-clasico, fuerza-roja, verde-potencia, azul-electrico.
Educacion: azul-academico, verde-progreso, claro-simple.

Disponibilidad por plan:
Plan Oportunidad: 10 temas.
Plan Crecimiento: 17 temas.
Plan Vision: 17 temas mas opcion de paleta personalizada (4 colores hex).

Paleta personalizada (solo Plan Vision): el dueno ingresa 4 colores hexadecimales y se generan las variables CSS --brand-primary, --brand-secondary, --brand-accent, --brand-text. Se guarda en tenant.settings.engine_settings.visual.custom_palette.

Al seleccionar un tema se aplica inmediatamente en preview. El tema seleccionado se guarda en tenant_customization.theme_slug.',
            ],
            [
                'slug'        => 'studio_medios_pago_chips',
                'title'       => 'SYNTIstudio — Medios de pago informativo (NO pasarela)',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/config-section.blade.php',
                'content'     => 'Los medios de pago en SYNTIstudio son chips visuales informativos que aparecen en la landing para que los clientes sepan como pagar. SYNTIweb NO procesa pagos. No cobra a los clientes del negocio. No es una pasarela de pago.

Metodos nacionales venezolanos disponibles (11): Pago Movil, Efectivo, Punto de Venta, Biopago, Cashea, Krece, Wepa, Lysto, Chollo, Wally, Kontigo.
Metodos internacionales (7): Zelle, PayPal, Zinli, AirTM, Reserve (RSV), Binance Pay, USDT.
Monedas adicionales: USD, EUR.

Configuracion: Dashboard, tab Configuracion, seccion Metodos de Pago. Se muestran como chips togglables. Al seleccionar y guardar se actualiza tenant_customization.payment_methods (array JSON).

Restricciones por plan:
Plan Oportunidad (studio-oportunidad): Solo Pago Movil + Efectivo (fijo, no configurable).
Plan Crecimiento (studio-crecimiento): Los 18 metodos configurables.
Plan Vision (studio-vision): Los 18 metodos configurables mas posibilidad de metodos por sucursal.

Detalles del metodo: Al seleccionar Pago Movil aparece sub-formulario con banco (dropdown 19 bancos venezolanos), cedula y telefono. PayPal y Zinli piden email. Estos datos aparecen en la landing bajo el chip del metodo.

En la landing publica: aparece una franja con los chips de metodos activos. Se renderiza desde landing/sections/ en el template studio/food/catalog segun el plan y la configuracion del tenant.',
            ],
            [
                'slug'        => 'studio_horarios_negocio',
                'title'       => 'SYNTIstudio — Configuración de horarios y estado abierto/cerrado',
                'product'     => 'studio',
                'source_file' => 'resources/views/dashboard/components/info-section.blade.php',
                'content'     => 'SYNTIstudio permite configurar el horario de atencion del negocio y mostrar automaticamente un indicador de abierto o cerrado en la landing publica. Disponible en todos los planes.

Configuracion: Dashboard, tab Tu Informacion, sub-tab Horario. Se configura cada dia de la semana (Lunes a Domingo) con toggle activo y rangos de hora de apertura y cierre (hora inicio y hora fin). Tambien incluye el campo Mensaje cuando estamos cerrados (texto personalizable que aparece como banner en la landing).

Activar el indicador: En el sub-tab Negocio hay un checkbox Activar indicador de horario. Al activarlo se muestra en la landing un badge verde (Abierto) o rojo (Cerrado) segun el horario actual comparado con la hora del servidor (timezone America/Caracas).

El horario se guarda en tenant.business_hours (columna JSON en la tabla tenants). El servicio BusinessHoursService::isOpen() compara la hora actual con los rangos configurados. Si el tenant tiene is_open=false se fuerza el estado cerrado independientemente del horario.

Toggle manual desde la landing: el dueno puede forzar el estado desde el panel flotante (boton Abrir/Cerrar en el encabezado del dashboard) sin modificar el horario regular. La ruta es POST /tenant/{tenantId}/toggle-status en TenantRendererController, protegida por PIN con throttle de 5 intentos por minuto.',
            ],
            [
                'slug'        => 'studio_seo_meta',
                'title'       => 'SYNTIstudio — SEO y metatags automáticos',
                'product'     => 'studio',
                'source_file' => 'resources/views/landing/base.blade.php',
                'content'     => 'SYNTIstudio genera automaticamente metatags SEO para la landing publica de cada tenant. El layout base es resources/views/landing/base.blade.php que incluye el head HTML completo.

Metatags generados automaticamente (todos los planes):
title: {business_name} — {slogan} | SYNTIweb
meta description: descripcion del negocio (tenant.description o autogenerada).
meta keywords: ciudad + segmento de negocio + nombre.
Open Graph: og:title, og:description, og:image (logo del tenant), og:url.
Twitter Card: twitter:card summary_large_image.
Canonical URL: https://{subdomain}.syntiweb.com o dominio personalizado.
PWA manifest: /manifest/{subdomain}.json con iconos y themeColor del tema activo.

Campos editables por el dueno: en el dashboard hay campos meta_title, meta_description, meta_keywords en la seccion de Tu Informacion (guardados en tenant.meta_title, tenant.meta_description, tenant.meta_keywords). Si no se llenan se usan los autogenerados.

Plan Crecimiento y Vision: Schema.org LocalBusiness generado como JSON-LD en el <head> con nombre, direccion, horario, telefono, url. Se genera en TenantRendererController::buildSchema() y se pasa como $schema a la vista.

Plan Vision: SEO avanzado con keywords personalizadas y soporte de canonical URL de dominio personalizado.',
            ],
        ];
    }

    // =========================================================================
    // FOOD — SYNTIfood (menu digital para restaurantes)
    // =========================================================================

    /** @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}> */
    private function foodDocs(): array
    {
        return [
            [
                'slug'        => 'food_menu_estructura_hibrida',
                'title'       => 'SYNTIfood — Estructura hibrida del menú: 1 foto por categoría más lista',
                'product'     => 'food',
                'source_file' => 'resources/views/landing/templates/food.blade.php',
                'content'     => 'SYNTIfood usa una estructura de menu hibrida: 1 foto representativa por categoria de comida, y debajo de cada foto una lista de platos individuales con nombre y precio. Esto imita el formato de carta fisica venezolana pero en digital.

Por que 1 sola foto por categoria: Los restaurantes venezolanos no tienen foto de todos los platos. Una foto de alta calidad por categoria (ej: foto de unas arepas para la categoria Arepas) es suficiente para generar apetencia. Esto reduce drasticamente la carga para el negocio al montar el menu.

Estructura en la landing: categorias navegables con barra sticky en la parte superior (solo Plan Crecimiento y Vision). En Plan Basico es una lista plana sin categorias. Al hacer scroll la categoria activa se resalta en la barra. Cada categoria tiene: nombre, foto grande, y lista de items con nombre, precio en REF/Bs. segun modo de moneda, y boton [+] para agregar al pedido rapido.

Datos en base de datos: las categorias se almacenan en la tabla menu_categories (tenant_id, name, position, image_filename). Los items en menu_items (category_id, tenant_id, name, description, price, available). Se carga via MenuService::getCategories() que retorna array anidado con categorias e items hijos.

Items destacados (badges): plan Crecimiento y Vision permiten marcar items con badge Popular, Nuevo, Promo. Se filtran con $featuredItems para mostrar seccion especial de destacados al inicio del menu.',
            ],
            [
                'slug'        => 'food_categorias_crud',
                'title'       => 'SYNTIfood — CRUD de categorías del menú',
                'product'     => 'food',
                'source_file' => 'resources/views/dashboard/components/menu-section.blade.php',
                'content'     => 'Las categorias organizan los platos del menu SYNTIfood. Se administran desde Dashboard, tab Que Vendes (o tab Menu en blueprints food). Solo disponible en Plan Crecimiento (food-semestral) y Plan Vision (food-anual). Plan Basico no tiene categorias (lista plana).

Campos de la categoria: nombre (obligatorio), foto representativa (1 imagen JPG/PNG/WebP hasta 2MB, auto WebP), posicion de orden.

Limites de fotos de categoria por plan:
Plan Basico (food-basico): 6 fotos.
Plan Semestral (food-semestral): 12 fotos.
Plan Vision/Anual (food-anual): 18 fotos.

El menu-section muestra una barra de uso de items con indicador de color: verde mientras hay espacio, amarillo al llegar al 70%, rojo al llegar al 90%. Muestra el contador de items/maximo.

Acciones disponibles: agregar categoria (MenuAdmin.openCategoryModal()), editar categoria (nombre e imagen), eliminar categoria (elimina tambien todos los items hijo), reordenar (drag and drop).

Las categorias son colapsables en el panel de administracion usando Alpine.js x-data open:true. Cada categoria colapsada muestra sus items como lista. Agregar item dentro de una categoria: MenuAdmin.openItemModal(catId) abre modal contextual.',
            ],
            [
                'slug'        => 'food_items_crud',
                'title'       => 'SYNTIfood — CRUD de ítems (platos) del menú',
                'product'     => 'food',
                'source_file' => 'resources/views/dashboard/components/menu-section.blade.php',
                'content'     => 'Los items son los platos individuales del menu digital SYNTIfood. Se administran desde Dashboard, tab Menu (dentro de cada categoria).

Campos del item: nombre del plato (obligatorio), precio en USD/REF (obligatorio), descripcion breve (opcional), disponible (toggle on/off para marcar si esta disponible), badge (Popular, Nuevo, Promo — solo Plan Crecimiento y Vision), posicion de orden dentro de la categoria.

Limites de items por plan:
Plan Basico (food-basico): hasta 50 items totales.
Plan Semestral (food-semestral): hasta 100 items totales.
Plan Vision/Anual (food-anual): hasta 150 items totales.

No hay foto individual por item. Solo la foto de la categoria representa visualmente al grupo. Esta es una decision de diseno intencional para simplificar la carga del menu.

El precio se muestra en la landing segun el modo de moneda configurado por el tenant. Si el modo es both_toggle el cliente puede alternar entre REF y Bs. en tiempo real.

Items desactivados (disponible=false): no aparecen en la landing publica. Esto permite marcar platos del dia agotados sin eliminarlos de la base de datos.',
            ],
            [
                'slug'        => 'food_pedido_rapido_whatsapp',
                'title'       => 'SYNTIfood — Pedido Rápido WhatsApp (solo Plan Anual)',
                'product'     => 'food',
                'source_file' => 'resources/views/landing/templates/food.blade.php',
                'content'     => 'El Pedido Rapido es la funcion estrella de SYNTIfood. Permite al cliente del restaurante acumular platos del menu y enviar el pedido estructurado por WhatsApp con un solo toque. Exclusivo del Plan Anual (food-anual, REF 69/año).

Flujo del cliente: 1) El cliente navega el menu. 2) Toca el boton [+] junto a cada plato que quiere. 3) El contador en el boton flotante Pedido (X items) se actualiza en tiempo real. 4) Al tocar el boton flotante se abre un drawer lateral con el resumen del pedido. 5) El cliente toca Enviar pedido por WhatsApp y se abre WhatsApp nativo.

Formato del mensaje que se envia: tiene emoji de plato, nombre del negocio, lista de items con cantidad y precio unitario, subtotal por item, total calculado, y enlace a la landing. Ejemplo: Pedido Donaz - 2x Arepa Reina Pepiada REF 4.00 cu - 1x Jugo Natural REF 2.50 - Total REF 10.50.

Validacion de campos del cliente: el negocio puede configurar si pide nombre, telefono y ubicacion. Los campos requeridos se configuran en tenant.settings (customer_required_fields).

El Pedido Rapido no es un carrito persistente: si el cliente cierra el navegador pierde el pedido acumulado. No hay estado guardado en servidor. No procesa pagos. Solo genera el mensaje de WhatsApp.

En Plan Basico y Plan Semestral: el boton [+] no aparece. El cliente ve el menu pero solo puede contactar al restaurante via boton de WhatsApp general.',
            ],
            [
                'slug'        => 'food_header_configuracion',
                'title'       => 'SYNTIfood — Header: logo, nombre, horario y estado del restaurante',
                'product'     => 'food',
                'source_file' => 'resources/views/landing/templates/food.blade.php',
                'content'     => 'El header de SYNTIfood muestra la identidad del restaurante en la parte superior de la landing publica. Incluye logo, nombre del negocio, indicador de estado abierto/cerrado y informacion de modo de servicio.

Elementos configurables del header:
Logo: imagen circular subida desde el tab Como Se Ve del dashboard. Se guarda como tenant_customization.logo_filename.
Nombre del restaurante: muestra tenant.business_name.
Eslogan: muestra tenant.slogan.
Indicador abierto/cerrado: si BusinessHoursService::isOpen() es true muestra badge verde Abierto, si es false muestra badge rojo Cerrado.
Modo de servicio: chips que indican el tipo de servicio disponible (comer en sitio, para llevar, delivery). Se configuran en tenant.settings.

Plan Vision (food-anual) agrega: encabezado sticky con logo reducido al hacer scroll, barra de categoria anclada (sticky), indicador de items en el carrito flotante.

El hero del restaurante es el slider de imagenes configurado en el tab Como Se Ve. Hasta 5 fotos del restaurante o platos destacados que rotan automaticamente (fotos hero_main, hero_secondary, hero_tertiary, hero_image_4, hero_image_5 en tenant_customization).',
            ],
            [
                'slug'        => 'food_hero_slider',
                'title'       => 'SYNTIfood — Hero slider de imágenes del restaurante',
                'product'     => 'food',
                'source_file' => 'resources/views/dashboard/components/visual-section.blade.php',
                'content'     => 'SYNTIfood permite subir hasta 5 imagenes que se muestran como slider en la portada del restaurante. Esto permite mostrar platos, el local, el ambiente o el equipo.

Como subir las imagenes: Dashboard, tab Como Se Ve (tercer icono). Aparece una galeria de slots de imagen numerados. Hacer click en un slot o arrastrar la imagen al area de drop. El sistema convierte automaticamente a WebP con ancho maximo 1600px.

Slots disponibles por plan:
Plan Basico (food-basico): 1 imagen (hero_main). Solo muestra la imagen sin slider.
Plan Semestral (food-semestral): hasta 3 imagenes (hero_main, hero_secondary, hero_tertiary). Slider automatico con transicion suave.
Plan Anual (food-anual): hasta 5 imagenes (hero_main, hero_secondary, hero_tertiary, hero_image_4, hero_image_5). Slider completo.

Las imagenes se almacenan como archivos WebP en storage/app/public/tenants/{tenant_id}/ con nombres: hero.webp, hero_slot_2.webp, hero_slot_3.webp, hero_slot_4.webp, hero_slot_5.webp. La ruta en tenant_customization: hero_main_filename, hero_secondary_filename, hero_tertiary_filename, hero_image_4_filename, hero_image_5_filename.

Si no hay imagen subida: el slider no aparece y se muestra un placeholder con el color del tema activo del negocio.',
            ],
            [
                'slug'        => 'food_is_open_banner',
                'title'       => 'SYNTIfood — Banner de restaurante cerrado',
                'product'     => 'food',
                'source_file' => 'resources/views/landing/templates/food.blade.php',
                'content'     => 'Cuando el restaurante esta cerrado SYNTIfood muestra un banner visible en la landing publica informando el horario o un mensaje personalizado. Disponible en todos los planes.

Activacion: el estado abierto/cerrado se determina via BusinessHoursService::isOpen() en TenantRendererController. La variable $isOpen se pasa a la vista. La variable $closedMessage contiene el texto del banner, leido desde tenant.settings.business_info.closed_message o el texto por defecto.

En la landing: cuando $isOpen es false se muestra un banner en la parte superior (antes del menu) con fondo oscuro translucido, icono de reloj, y el mensaje de cerrado. El banner no bloquea el acceso al menu (el cliente puede seguir explorando los platos).

El banner tambien aplica para cuando el dueno fuerza el cierre manual via el toggle del panel flotante (ruta POST /tenant/{tenantId}/toggle-status). El campo is_open en la tabla tenants se actualiza directamente.

Mensaje personalizable: el texto del banner se configura en Dashboard, tab Tu Informacion, sub-tab Negocio, campo Mensaje cuando estamos cerrados. Ejemplo: Estamos cerrados. Horario: Lunes a Viernes 11am-9pm.',
            ],
            [
                'slug'        => 'food_comandas_panel',
                'title'       => 'SYNTIfood — Panel de Comandas (solo Plan Anual)',
                'product'     => 'food',
                'source_file' => 'resources/views/dashboard/components/comandas-section.blade.php',
                'content'     => 'El panel de Comandas de SYNTIfood esta disponible exclusivamente en el Plan Anual (food-anual) y muestra los pedidos recibidos via el Pedido Rapido de WhatsApp. Se accede desde Dashboard, tab especifico de Comandas.

Requisito de plan: $isFoodAnual debe ser true. El DashboardController verifica si plan.slug === food-anual antes de cargar las comandas via loadFoodComandas(). En planes menores se muestra pantalla de plan requerido con candado.

Informacion mostrada por comanda en la tabla: codigo SF-XXXX, hora del pedido, nombre del cliente, listado de items con cantidades, total en REF, estado actual.

Los estados de la comanda se gestionan manualmente (el restaurante los actualiza via WhatsApp con el cliente). La vista solo muestra las comandas recibidas, no las gestiona automaticamente.

Contador diario: el header de la seccion muestra cuantas comandas se recibieron hoy.

Expansion de detalle: cada fila de la tabla es expandible usando Alpine.js x-data con opened: null. Al expandir muestra el detalle completo de la comanda incluyendo los items individuales.

Estados visuales: si no hay comandas se muestra un empty state elegante. Si el plan no es anual se muestra el estado locked con informacion sobre el plan necesario.',
            ],
        ];
    }

    // =========================================================================
    // CAT — SYNTIcat (catalogo para tiendas)
    // =========================================================================

    /** @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}> */
    private function catDocs(): array
    {
        return [
            [
                'slug'        => 'cat_catalogo_productos',
                'title'       => 'SYNTIcat — Catálogo de productos con categorías y subcategorías',
                'product'     => 'cat',
                'source_file' => 'resources/views/dashboard/components/catalog-products-section.blade.php',
                'content'     => 'SYNTIcat tiene un sistema de catalogo avanzado con categorias y subcategorias para organizar los productos de la tienda. El catalogo se administra desde Dashboard, tab Que Vendes.

Sistema de categorias: Se crean inline desde la misma vista del catalogo. Al agregar una categoria aparece con su nombre como pill/chip en la parte superior. Cada categoria puede tener subcategorias creadas con el boton sub. Las categorias y subcategorias se guardan en la tabla cat_categories (tenant_id, name, parent_id).

Acciones en categorias: agregar categoria (addCatCategory()), agregar subcategoria (addCatSubcategory(catId, input)), eliminar categoria (deleteCatCategory(id)), asignar productos a una categoria al crear o editar el producto.

Campos del producto en SYNTIcat: nombre (obligatorio), precio USD/REF (obligatorio), precio comparativo tachado (compare_price_usd), descripcion, imagen(es) segun plan, badge (Nuevo, Hot, Promo, Destacado), categoria (select de cat_categories), variantes, estado activo.

Limites por plan:
Plan Basico (cat-basico): 50 productos, 1 imagen por producto.
Plan Semestral (cat-semestral): 150 productos, 3 imagenes por producto.
Plan Anual (cat-anual): 250 productos, 6 imagenes por producto.

En la landing: grid visual de productos con foto, nombre, precio REF, precio Bs., badge, y botones de accion. Los productos se pueden filtrar por categoria en la barra de navegacion.',
            ],
            [
                'slug'        => 'cat_galeria_imagenes_limite',
                'title'       => 'SYNTIcat — Galería de imágenes por producto según plan',
                'product'     => 'cat',
                'source_file' => 'resources/views/dashboard/components/catalog-products-section.blade.php',
                'content'     => 'SYNTIcat permite subir multiples imagenes por producto para mostrar detalles, variantes de color o angulos distintos. El numero maximo de imagenes depende del plan.

Limites de imagenes por producto por plan (verificados en catalog-products-section y PlanFeatureService):
Plan Basico (cat-basico): 1 imagen por producto. Variable maxImages = 1.
Plan Semestral (cat-semestral): 3 imagenes por producto. Variable maxImages = 3.
Plan Anual (cat-anual): 6 imagenes por producto. Variable maxImages = 6.

La primera imagen es la imagen_filename principal del producto (tabla products). Las imagenes adicionales se guardan en la tabla product_images (product_id, tenant_id, image_filename, position) y se cargan via la relacion galleryImages() del modelo Product (hasMany ProductImage, ordenadas por position).

En la landing: el producto con multiples imagenes muestra un mini-slider o galeria de thumbs en el modal de detalle del producto. El boton Ver mas o Ver detalle abre el modal con el slider completo.

Como subir: desde el modal del producto en el dashboard. Se muestran slots de imagen segun el plan. Se pueden subir, reemplazar o eliminar. El procesamiento pasa por ImageUploadService::processWithCustomFilename() para las imagenes de galeria.',
            ],
            [
                'slug'        => 'cat_carrito_motor',
                'title'       => 'SYNTIcat — Motor del carrito de compras',
                'product'     => 'cat',
                'source_file' => 'resources/views/landing/templates/catalog.blade.php',
                'content'     => 'El carrito de SYNTIcat es un sistema de compras local (cliente-side) que permite acumular productos y enviar el pedido por WhatsApp. Disponible en Plan Semestral y Plan Anual.

Plan Basico (cat-basico): Sin carrito. Solo boton Consultar por WhatsApp en cada producto. El metodo de pago fijo es pagoMovil y cash.

Plan Semestral (cat-semestral): Carrito basico con Cart Drawer (panel lateral derecho). El cliente puede agregar productos, especificar cantidades, elegir variantes talla/color. El checkout genera mensaje de WhatsApp sin codigo SC.

Plan Anual (cat-anual): Carrito completo con Cart Drawer, checkout con campos del cliente (nombre, telefono, ubicacion), y codigo de orden SC-XXXX trazable.

Funcionamiento tecnico del carrito: el estado del carrito se mantiene en memoria JavaScript (no en localStorage, no en servidor). Al agregar un producto se abre automaticamente el Cart Drawer. El total se calcula en tiempo real en REF. Al hacer checkout se llama a CheckoutController::store() via POST /checkout/{subdomain}.

CheckoutController valida: items (min 1), datos del cliente (nombre max 120 chars, telefono VE formato 58412|414|416|422|424|426 + 7 digitos), items.qty y items.price. Crea un registro de orden via OrderService::generate() y costruye el mensaje via WhatsappMessageBuilder::build().

Solo cat-anual tiene acceso al checkout completo. Si el plan en la validacion no es cat-anual el CheckoutController devuelve error 403 plan_requerido.',
            ],
            [
                'slug'        => 'cat_mini_order_sc_xxxx',
                'title'       => 'SYNTIcat — Mini Order con código SC-XXXX (solo Plan Anual)',
                'product'     => 'cat',
                'source_file' => 'app/Http/Controllers/CheckoutController.php',
                'content'     => 'El Mini Order genera un codigo de seguimiento SC-XXXX unico para cada pedido realizado en SYNTIcat. Exclusivo del Plan Anual (cat-anual, REF 69/año).

Generacion del codigo: la secuencia SC-0001, SC-0002, SC-0003 se genera en OrderService::generate(). El orden se guarda como archivo JSON en el disco local del servidor en la ruta storage/app/tenants/{tenant_id}/orders/{año}/{mes}/SC-XXXX.json.

Contenido del JSON guardado: codigo SC-XXXX, lista de items (nombre, variante, cantidad, precio unitario), total en REF, nombre y telefono del cliente, ubicacion, fecha/hora de creacion, subdomain del tenant.

El codigo viaja en el mensaje de WhatsApp: ejemplo Orden SC-0047 - 2x Camisa Polo Azul Talla M REF 15.00 - Total REF 30.00.

Panel de ordenes en el dashboard: OrdersController::index() lista las ordenes del tenant leyendo los archivos JSON del disco. Ordenadas por fecha DESC. Solo visible para plan cat-anual. En planes inferiores se muestra pantalla locked.

Los estados de la orden no se gestionan desde la plataforma. El seguimiento se hace por WhatsApp entre el negocio y el cliente usando el codigo SC-XXXX como referencia.',
            ],
            [
                'slug'        => 'cat_checkout_whatsapp',
                'title'       => 'SYNTIcat — Checkout con envío del pedido por WhatsApp',
                'product'     => 'cat',
                'source_file' => 'app/Http/Controllers/CheckoutController.php',
                'content'     => 'El checkout de SYNTIcat es el flujo final que convierte el carrito en un mensaje de WhatsApp. Disponible en Plan Semestral (sin codigo SC) y Plan Anual (con codigo SC-XXXX).

Flujo: 1) El cliente llena su carrito en la landing. 2) Toca Finalizar pedido en el Cart Drawer. 3) Aparece modal del checkout con campos nombre del cliente, telefono venezolano y ubicacion/referencia de entrega. 4) Al confirmar se llama POST /checkout/{subdomain}. 5) El servidor genera la orden y construye el mensaje. 6) Se devuelve la URL de WhatsApp. 7) El navegador abre WhatsApp nativo con el mensaje pre-completado.

Validaciones en CheckoutController::store(): name obligatorio max 120 chars, phone con regex venezolano (58 + prefijo 412/414/416/422/424/426 + 7 digitos), items minimo 1, items.title max 200, items.qty integer minimo 1, items.price numeric minimo 0, items.variant opcional max 100.

El numero de WhatsApp del negocio usado para el checkout es el activo segun tenant.whatsapp_active: si es support usa whatsapp_support, sino usa whatsapp_sales. Si ningun numero esta configurado se usa tenant.phone.

No hay confirmacion automatica, no hay sistema de pago integrado, no hay seguimiento de estados de entrega desde la plataforma.',
            ],
            [
                'slug'        => 'cat_variantes_producto',
                'title'       => 'SYNTIcat — Variantes de producto: talla y color',
                'product'     => 'cat',
                'source_file' => 'app/Models/Product.php',
                'content'     => 'SYNTIcat soporta variantes de producto (talla, color, opciones libres) para tiendas de ropa y similares. Disponible en Plan Semestral y Plan Anual.

Tipos de variantes disponibles:
simple: Sin variantes. El cliente solo especifica cantidad. (Todos los planes).
size: Variante de talla (XS, S, M, L, XL, XXL). (Plan Semestral y Anual).
size_color: Variante de talla mas color (ej: S Azul, M Rojo). (Plan Semestral y Anual).
options: Opciones libres personalizables (ej: Sin cebolla, Con extra queso). (Solo Plan Anual).

Plan Basico (cat-basico): solo tipo simple, sin variantes. No hay selector de talla ni color.

Como configurar: al crear o editar un producto en el dashboard se selecciona el tipo de variante. Para size y size_color se agregan las tallas y colores disponibles. Para options se definen las opciones en texto libre.

En la landing: al tocar Agregar al carrito el cliente ve un modal o selector con las variantes disponibles antes de confirmar. La variante elegida se incluye en el mensaje de WhatsApp como texto: 2x Camisa Polo (Talla M, Color Azul).

Las variantes no manejan inventario por variante. No hay alerta de agotado por talla. No hay precios diferentes por variante.',
            ],
            [
                'slug'        => 'cat_header_configuracion',
                'title'       => 'SYNTIcat — Header del catálogo: logo, nombre y barra de categorías',
                'product'     => 'cat',
                'source_file' => 'resources/views/landing/templates/catalog.blade.php',
                'content'     => 'El header de SYNTIcat muestra la identidad de la tienda y la navegacion por categorias. Es fijo en la parte superior de la landing publica.

Elementos del header:
Logo: imagen circular del negocio subida desde Visual del dashboard (tenant_customization.logo_filename).
Nombre de la tienda: tenant.business_name.
Eslogan: tenant.slogan.
Icono del carrito: muestra el contador de items en el carrito en tiempo real. Al tocar abre el Cart Drawer. Solo visible en Plan Semestral y Plan Anual.
Barra de categorias sticky: debajo del header principal, muestra las categorias de cat_categories como pills navegables. Al tocar una categoria hace scroll hasta esa seccion. Solo visible en planes con categorias configuradas.

Plan Basico (cat-basico): header simple sin barra de categorias, sin carrito visible. Solo logo, nombre y boton de WhatsApp.

Metodos de pago en catalog.blade.php: el template incluye informacion de los 18 metodos de pago del tenant (si configurados). Plan Basico ve solo pagoMovil + cash fijos. Planes superiores ven los metodos configurados por el dueno.

El header es responsive. En movil el carrito flotante aparece como boton fijo en la esquina inferior derecha para facilitar el acceso rapido.',
            ],
        ];
    }

    // =========================================================================
    // ADMIN — Panel Filament (uso interno SYNTIweb)
    // =========================================================================

    /** @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}> */
    private function adminDocs(): array
    {
        return [
            [
                'slug'        => 'admin_filament_panel',
                'title'       => 'Admin — Panel Filament: acceso y estructura',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/',
                'content'     => 'El panel de administracion interno de SYNTIweb esta construido con Filament PHP. Solo accesible para usuarios con rol admin. No es el dashboard del tenant — es el panel de gestion de la plataforma completa.

URL de acceso: /admin (configurada en config/filament-copilot.php o AppServiceProvider). Requiere usuario autenticado con permiso de admin (middleware EnsureAdmin).

Estructura de navegacion por grupos:
Plataforma: Tenants (gestion de negocios), Users (usuarios).
Facturacion: Invoices (facturas y pagos pendientes).
Infraestructura: Domains (dominios personalizados).
Contenido: Blog Posts (articulos del blog SyntiBlog), Landing Sections (secciones de landing), Media.
Configuracion: Plans (planes de suscripcion), Support Tickets.

Widgets del dashboard Filament:
StatsOverviewWidget: KPIs de la plataforma (tenants activos, nuevos este mes, MRR actual vs mes anterior, pagos pendientes, tickets abiertos, tenants suspendidos, en trial).
CurrencyRatesWidget: tasas BCV actuales (USD/Bs. y EUR/Bs.) con timestamp de ultima actualizacion.
BlueprintDonutChart: distribucion de tenants por tipo de producto (studio/food/cat).
RevenueLineChart: grafico de ingresos mensuales.
LatestTenantsWidget: ultimos tenants creados.',
            ],
            [
                'slug'        => 'admin_tenants_gestion',
                'title'       => 'Admin — Gestión de tenants (clientes)',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/Tenants/TenantResource.php',
                'content'     => 'TenantResource es el recurso principal de Filament para gestionar los negocios clientes. Ubicado en app/Filament/Resources/Tenants/TenantResource.php. Grupo de navegacion: Plataforma, orden 1.

Campos gestionables: business_name, subdomain, plan (select de todos los planes), user (select de usuarios registrados), status (active/frozen/suspended), subscription_ends_at (DateTimePicker), is_demo (boolean).

Estados posibles del tenant: active (normal), frozen (suscripcion vencida, pagina congelada con mensaje), suspended (bloqueado por admin — no accesible).

Herramientas Copilot integradas: ListTenantsTool, SearchTenantsTool, SuspendTenantTool, RestoreTenantTool. Estas herramientas permiten al asistente IA interno buscar y gestionar tenants via linguaje natural.

Acciones desde Filament: crear tenant nuevo, editar datos, cambiar plan, suspender tenant, restaurar tenant suspendido, ver invoices del tenant.

Filtros de tabla: por status, por blueprint, por plan, por fecha de creacion (rango). Busqueda por business_name y subdomain.

El admin puede también modificar el edit_pin del tenant para restablecerlo si el dueno lo olvido. Este campo se guarda siempre hasheado con bcrypt.',
            ],
            [
                'slug'        => 'admin_planes_crud',
                'title'       => 'Admin — CRUD de planes de suscripción',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/Plans/PlanResource.php',
                'content'     => 'PlanResource permite gestionar los planes de suscripcion desde el panel Filament. Ubicado en app/Filament/Resources/Plans/PlanResource.php. Grupo Configuracion, orden 2.

Campos de un plan: slug (read-only, identificador unico tipo studio-oportunidad), blueprint (read-only: studio/food/cat), name (nombre de display), price_usd (precio anual en USD), y campos de features/limites.

Limites almacenados en la tabla plans: products_limit, services_limit, images_limit, color_palettes, social_networks_limit, whatsapp_numbers.

Flags de features booleanas: show_dollar_rate, show_header_top, show_about_section, show_payment_methods, show_faq, show_cta_special, whatsapp_hour_filter.

Niveles de analytics y SEO: analytics_level (basico/avanzado/full), seo_level.

Los planes NO se crean desde la UI — se insertan via seeders (database/seeders/). Solo se editan precios y limites desde Filament. Nunca deben eliminarse planes activos que tienen tenants asociados.

El slug del plan es la clave que usa el sistema para identificar el blueprint y los limites en todo el codigo. Cambiar el slug de un plan activo romperia la logica de negocio.',
            ],
            [
                'slug'        => 'admin_billing_cola_pagos',
                'title'       => 'Admin — Cola de pagos pendientes y facturación',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/Invoices/InvoiceResource.php',
                'content'     => 'InvoiceResource gestiona las facturas y los pagos reportados por los tenants. Ubicado en app/Filament/Resources/Invoices/InvoiceResource.php. Grupo Facturacion, orden 5.

Badge de navegacion: muestra el conteo de facturas en estado pending_review (color warning naranja). Permite ver cuantos pagos hay pendientes de revision de un vistazo en el menu lateral.

Flujo de facturacion: 1) El tenant reporta un pago desde su dashboard (BillingController::reportPayment()). 2) Se crea una Invoice con status pending_review. 3) El admin ve la factura en InvoiceResource. 4) Revisa el comprobante (receipt_path en almacenamiento local). 5) Aprueba (status=paid, extiende subscription_ends_at del tenant) o rechaza (status=rejected).

Campos de la factura: invoice_number (SYNTI-YYYY-XXXXX autoincrementado), tenant_id, amount_usd, payment_channel (pago_movil/paypal/zinli), payment_reference, payment_date, receipt_path, status, admin_notes, reviewed_at, reviewed_by, period_start, period_end.

Exportacion: permite exportar facturas a Excel via pxlrbt/filament-excel.

Paginas disponibles: ListInvoices (tabla con filtros) y ViewInvoice (ver detalle, no editar formulario). La aprobacion o rechazo se hace via acciones personalizadas en la vista de detalle.',
            ],
            [
                'slug'        => 'admin_dominios_modulo',
                'title'       => 'Admin — Módulo de dominios personalizados',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/DomainResource.php',
                'content'     => 'DomainResource gestiona los dominios personalizados asociados a los tenants. Ubicado en app/Filament/Resources/DomainResource.php. Grupo Infraestructura, orden 10.

Un dominio personalizado permite que la landing del tenant sea accesible via su propio dominio (ej: mitienda.com) en lugar de mitienda.syntiweb.com. Es un add-on de $18 USD disponible para todos los planes.

Tipos de dominio: platform (subdominio syntiweb.com estandar), addon (dominio personalizado pagado como extra), external (dominio externo gestionado por el cliente).

Gestionado por: SYNTIweb (el equipo de soporte configura el DNS) o cliente (el cliente configura sus propios DNS).

Campos: domain (nombre del dominio sin protocolo), TLD (.com, .net, .ve, etc.), type (select), managed_by (select), Toggle active, DatePicker (fecha de vencimiento del addon), Textarea notes (instrucciones o notas del admin).

La relacion con el tenant es via tenant_id (searchable por nombre del negocio). Un tenant puede tener mltiples registros de dominio pero solo uno activo a la vez.

El campo domain_verified en la tabla tenants indica si el dominio personalizado fue verificado y apuntado correctamente.',
            ],
            [
                'slug'        => 'admin_blog_syntiblog',
                'title'       => 'Admin — Blog SyntiBlog (contenido marketing)',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/BlogPostResource.php',
                'content'     => 'BlogPostResource gestiona los articulos del blog publico de SYNTIweb (SyntiBlog). Ubicado en app/Filament/Resources/BlogPostResource.php. Grupo Contenido, orden 10.

El blog es publico y accesible en syntiweb.com/blog y syntiweb.com/blog/{slug}. Sirve como contenido de marketing para atraer trafico organico y educar a los clientes potenciales.

Campos del articulo: title (titulo), body (contenido rico con RichEditor de Filament), published_at (fecha de publicacion con DatePicker), featured_image (FileUpload), tags (TagsInput con terminos SEO), excerpt (resumen corto con Textarea), is_published (Toggle visible/oculto), category (Select de categorias).

El slug se autogenera desde el titulo o se puede editar manualmente. Los artculos no publicados (is_published = false) no aparecen en la ruta publica del blog.

Rutas publicas: GET /blog en MarketingController::blog() (lista articulos publicados). GET /blog/{slug} en MarketingController::blogPost() (articulo individual con SEO completo).',
            ],
            [
                'slug'        => 'admin_health_monitor',
                'title'       => 'Admin — Monitor de salud del sistema (health checks)',
                'product'     => 'admin',
                'source_file' => 'public/monitor.php',
                'content'     => 'SYNTIweb incluye un monitor de salud del sistema accesible publicamente en /monitor.php (archivo en la carpeta public/). Este endpoint verifica el estado operativo de los componentes criticos.

Verificaciones tipicas de health check: conexion a base de datos MySQL, disponibilidad de almacenamiento, acceso a cache (Redis o DB), version de PHP, estado de migraciones pendientes.

El endpoint /monitor.php es de solo lectura y no requiere autenticacion (es publico para uso de servicios de monitoreo externo como UptimeRobot o herramientas de devops). No expone datos sensibles — solo responde con estado OK/ERROR y codigo HTTP 200 o 500.

Para verificar el estado del sistema manualmente: acceder a /monitor.php en el navegador o via curl. Un estado bueno devuelve HTTP 200 con JSON de health checks en verde. Un estado con error devuelve HTTP 500 con detalle del componente fallido.

Herramientas adicionales de administracion: php artisan cache:clear para limpiar cache, php artisan migrate para ejecutar migraciones, php artisan db:seed --class=AiDocSeeder para recargar la base de conocimiento del asistente IA.',
            ],
            [
                'slug'        => 'admin_herramientas',
                'title'       => 'Admin — Herramientas: caché, migraciones y suspensión',
                'product'     => 'admin',
                'source_file' => 'app/Filament/Resources/Tenants/TenantResource.php',
                'content'     => 'El panel Filament incluye herramientas de administracion para gestionar el sistema y los tenants.

Herramientas de sistema: Limpiar cache (php artisan cache:clear o equivalente via Filament action), ejecutar migraciones pendientes, recargar seeders de configuracion.

Herramientas por tenant:
Suspender tenant: cambia status a suspended. La landing muestra pagina de suspension. El dueno no puede acceder al dashboard. Se usa ante pagos impagos graves o violaciones de TOS.
Restaurar tenant suspendido: cambia status a active. Requiere verificar que el pago este al dia.
Congelar tenant (freeze): se produce automaticamente cuando subscription_ends_at vence. El campo isFrozen() del modelo Tenant devuelve true cuando status=frozen. La landing muestra pagina de congelamiento en landing.frozen.
Cambiar plan: el admin puede cambiar el plan_id del tenant para upgrade o downgrade. Al bajar de plan el sistema respeta los limites del nuevo plan (los productos/servicios en exceso quedan inactivos).

Acciones de SuspendTenantTool y RestoreTenantTool son herramientas Copilot integradas en TenantResource que permiten realizar estas acciones via comandos de lenguaje natural desde el asistente SYNTiA.',
            ],
            [
                'slug'        => 'admin_company_settings',
                'title'       => 'Admin — Configuración global de la empresa SYNTIweb',
                'product'     => 'admin',
                'source_file' => 'config/app.php',
                'content'     => 'La configuracion global de SYNTIweb se gestiona via variables de entorno en el archivo .env y archivos de configuracion en config/. No hay UI en Filament para editar estas configuraciones — se hacen directamente en el servidor.

Variables criticas en .env:
APP_NAME: nombre de la aplicacion (SYNTIweb).
APP_URL: URL base del dominio principal.
DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD: conexion MySQL.
DOLLAR_FALLBACK_RATE: tasa de fallback si la API del BCV no responde.
ONBOARDING_MODE: modo del wizard de onboarding (admin para produccion, open para desarrollo).
MAIL_* variables: configuracion SMTP para notificaciones.

Configuracion especializada: config/tenancy.php para reglas de tenant isolation, config/currency.php para configuracion de moneda, config/blueprints.php para definicion de blueprints.

La URL base del shortlink de QR (https://syntiweb.me) esta hardcoded en QRService::getTrackingShortlink(). Para cambiarlo requiere modificar el codigo del servicio.

El canal de pago a los clientes (para que SYNTIweb reciba pagos) esta hardcoded en BillingController::SYNTIWEB_PAYMENT_CHANNELS con los datos de Pago Movil, PayPal y Zinli.',
            ],
            [
                'slug'        => 'admin_smtp_configuracion',
                'title'       => 'Admin — Configuración SMTP y notificaciones por email',
                'product'     => 'admin',
                'source_file' => 'config/mail.php',
                'content'     => 'SYNTIweb envia notificaciones por email en eventos clave. La configuracion del servidor de correo se hace via variables de entorno en .env.

Variables SMTP en .env: MAIL_MAILER (smtp, ses, sendmail, log, etc.), MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION (tls/ssl), MAIL_FROM_ADDRESS (email remitente), MAIL_FROM_NAME (nombre del remitente, usualmente SYNTIweb).

Eventos que generan email: registro de nuevo usuario (verificacion de email), reporte de pago enviado por un tenant (notificacion al admin), aprobacion o rechazo de pago (notificacion al tenant), suscripcion proxima a vencer (recordatorio).

Las notificaciones se implementan via clases en app/Notifications/. Se despachan desde los controladores o via eventos de Eloquent (Observers en app/Observers/).

En desarrollo local se puede usar log como MAIL_MAILER para que los emails se escriban en storage/logs/laravel.log en lugar de enviarse realmente.

Para probar la configuracion SMTP: php artisan tinker -> Mail::raw(Test, fn($m) => $m->to(test@example.com)->subject(Prueba));',
            ],
        ];
    }
    // =========================================================================
    // Totales al final del archivo (comentario referencia):
    // shared:  13 entradas
    // studio:   9 entradas
    // food:     8 entradas
    // cat:      7 entradas
    // admin:    9 entradas
    // TOTAL:   46 entradas
    // =========================================================================
}