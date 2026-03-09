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

        $docs = $this->getDocEntries();

        foreach ($docs as $doc) {
            AiDoc::create($doc);
        }

        $this->command->info('AiDocSeeder: ' . count($docs) . ' documentos cargados.');
    }

    /**
     * @return array<int, array{slug: string, title: string, product: string, content: string, source_file: string}>
     */
    private function getDocEntries(): array
    {
        return [
            // ─── PRIMEROS PASOS (shared) ───
            [
                'slug' => 'que-es-syntiweb',
                'title' => '¿Qué es SYNTIweb?',
                'product' => 'shared',
                'source_file' => 'primeros-pasos/que-es-syntiweb.mdx',
                'content' => 'SYNTIweb es una plataforma que le da a cualquier negocio venezolano su propia página web profesional, menú digital o catálogo de productos — sin necesidad de saber programar ni contratar a nadie. Tu cliente te busca en Google, escanea tu QR, o recibe tu link por WhatsApp — y ve tu negocio como un profesional.

Los 3 productos de SYNTIweb son:
- SYNTIstudio: Página web completa para cualquier negocio — barbería, clínica, academia, consultora, marca personal.
- SYNTIfood: Menú digital para restaurantes, areperas, pastelerías y food trucks. 1 foto por categoría + lista de platos con precio. Pedido directo por WhatsApp.
- SYNTIcat: Catálogo con carrito para tiendas de ropa, proveedores y comercios. Los clientes eligen productos, acumulan en carrito y envían el pedido por WhatsApp con código de seguimiento.

SYNTIweb NO procesa pagos. No es pasarela de pago. No cobra a tus clientes. Es una plataforma de presencia digital. El cliente ve tu página, elige lo que quiere y te contacta por WhatsApp.

Cómo funciona: 1) Elige tu producto en syntiweb.com/onboarding 2) Completa el asistente de configuración (5 pasos) 3) Tu página se publica en tunegocio.syntiweb.com 4) Administra desde el panel flotante con Alt+S (escritorio) o long press logo (móvil) + PIN de 4 dígitos.',
            ],
            [
                'slug' => 'primeros-pasos',
                'title' => 'Primeros pasos — Cómo crear tu negocio en SYNTIweb',
                'product' => 'shared',
                'source_file' => 'primeros-pasos/primeros-pasos.mdx',
                'content' => 'Para empezar solo necesitas: el nombre de tu negocio, tu número de WhatsApp, y saber qué vendes (productos, servicios o comida).

Paso a paso: 1) Entra a syntiweb.com/onboarding. Verás 3 opciones: Presencia Web Completa (SYNTIstudio), Menú Digital (SYNTIfood), Catálogo de Productos (SYNTIcat). 2) Completa el asistente de configuración de 5 pasos. 3) Tu página queda publicada en tunegocio.syntiweb.com. 4) Personaliza desde el panel de administración: Alt+S en escritorio o mantén presionado el logo 3 segundos en móvil, ingresa tu PIN de 4 dígitos.

Todos los planes incluyen: página publicada, QR descargable, botón de WhatsApp, panel de administración con PIN, indicador de negocio abierto/cerrado, precios en REF con tasa BCV automática, imágenes optimizadas automáticamente.

El subdominio se elige durante el asistente y no se puede cambiar después. Elige uno corto, fácil de recordar y sin caracteres especiales. Ejemplo: elcorteperfecto.syntiweb.com, lasazondemama.syntiweb.com, modaurbana.syntiweb.com.',
            ],
            [
                'slug' => 'planes-y-precios',
                'title' => 'Planes y precios de SYNTIweb',
                'product' => 'shared',
                'source_file' => 'primeros-pasos/planes-y-precios.mdx',
                'content' => 'SYNTIstudio — Para cualquier negocio que necesite página web completa:
Plan Oportunidad: REF 99/año (~REF 13/mes). 20 productos, 3 servicios, 1 foto por producto, 10 paletas. Secciones: hero, productos, servicios, contacto, medios de pago (solo Pago Móvil y Efectivo), CTA, footer.
Plan Crecimiento: REF 149/año (~REF 19/mes). 50 productos, 6 servicios, 17 paletas, 2 líneas WhatsApp, Google Maps. Secciones adicionales: acerca de, testimonios.
Plan Visión: REF 199/año (~REF 25/mes). Productos ilimitados, 9 servicios, 3 fotos slider, paleta personalizada, SEO avanzado + Schema.org. Secciones adicionales: FAQ, sucursales (hasta 3).

SYNTIfood — Para restaurantes y negocios de comida:
Plan Básico: REF 9/mes. 50 ítems, 6 fotos de categoría. Sin categorías ni pedido rápido.
Plan Semestral: REF 39. 100 ítems, 12 fotos, con categorías, tasa BCV, extras y opciones.
Plan Anual: REF 69/año. 150 ítems, 18 fotos. Incluye Pedido Rápido WhatsApp — la función estrella.

SYNTIcat — Para tiendas y catálogos:
Plan Básico: REF 9/mes. 20 productos, 1 imagen, sin carrito (solo botón WA directo).
Plan Semestral: REF 39. 100 productos, 3 imágenes, carrito básico, variantes talla/color.
Plan Anual: REF 69/año. Productos ilimitados, 6 imágenes, carrito + Mini Order con código SC-XXXX.

Los precios están en REF (referencia dólar). El equivalente en bolívares se calcula automáticamente con la tasa BCV del día. Se configura el modo de moneda desde Dashboard → Configuración → Moneda.',
            ],

            // ─── SYNTISTUDIO ───
            [
                'slug' => 'que-es-syntistudio',
                'title' => '¿Qué es SYNTIstudio?',
                'product' => 'studio',
                'source_file' => 'studio/que-es-syntistudio.mdx',
                'content' => 'SYNTIstudio es el producto principal de SYNTIweb. Te da una página web profesional completa con productos, servicios, información de contacto, medios de pago, colores personalizados y más — todo administrable desde tu celular.

Es ideal para cualquier negocio que necesita presencia digital: barberías, clínicas, academias, consultoras, marcas personales, ferreterías, gimnasios, estudios de diseño.

Incluye: landing page completa con hasta 11 secciones configurables, panel de administración flotante con 7 tabs, QR descargable, tasa BCV automática, 25+ paletas de color organizadas por industria, botón WhatsApp, indicador de horario, imágenes optimizadas WebP.

No tiene carrito de compras (para eso está SYNTIcat), no tiene menú tipo restaurante (para eso está SYNTIfood), no procesa pagos, no envía emails, no tiene blog ni login para clientes, no rastrea inventario.

Compite con Wix ($192/año), Squarespace ($168/año) y agencias locales ($150-300+). SYNTIstudio desde REF 99/año con todo incluido, autogestión y hecho para Venezuela.',
            ],
            [
                'slug' => 'asistente-configuracion-studio',
                'title' => 'Asistente de configuración SYNTIstudio (Wizard)',
                'product' => 'studio',
                'source_file' => 'studio/asistente-configuracion.mdx',
                'content' => 'El asistente de configuración es el proceso guiado de 5 pasos para crear tu negocio SYNTIstudio. Se encuentra en syntiweb.com/onboarding → "Presencia Web Completa". Solo se ejecuta una vez al crear el negocio.

Paso 1 — Negocio: Nombre del Negocio (obligatorio), Tipo de Negocio (21+ categorías: Belleza/Estética, Restaurante, Tienda de Ropa, Salud/Clínica, Academia, etc.), Ciudad.

Paso 2 — Mensaje: Eslogan, Subtítulo Hero, Texto Acerca de.

Paso 3 — Propuesta: 3 propuestas de valor obligatorias (lo que te diferencia).

Paso 4 — Plan + Subdominio: Selección de plan (Oportunidad REF 99, Crecimiento REF 149, Visión REF 199), subdominio (se valida en tiempo real), WhatsApp ventas, teléfono, email.

Paso 5 — Revisión: Resumen de todo, confirmar y crear.

El subdominio no se puede cambiar después. El asistente no permite cargar imágenes ni agregar productos — eso se hace en el panel de administración después.',
            ],
            [
                'slug' => 'panel-administracion',
                'title' => 'Panel de administración — Dashboard SYNTIstudio',
                'product' => 'studio',
                'source_file' => 'studio/panel-administracion.mdx',
                'content' => 'El panel de administración es el dashboard flotante que aparece sobre tu página web. Se activa desde tunegocio.syntiweb.com con Alt+S (escritorio) o manteniendo presionado el logo 3 segundos (móvil). Requiere PIN de 4 dígitos.

Tiene 7 tabs en el menú lateral izquierdo:
1. Tu Información — Datos del negocio, horario, redes sociales. Sub-tabs: Negocio, Horario, Redes.
2. Qué Vendes — Productos y servicios (agregar, editar, eliminar).
3. Cómo Se Ve — Paleta de colores y tema visual. 30+ paletas organizadas por industria.
4. Tu Mensaje — Orden de secciones (drag-and-drop), testimonios, FAQ, sucursales, CTA.
5. Pulso del Negocio — Analítica y estadísticas.
6. Visual — Variantes de secciones (hero: fullscreen/gradient/split, servicios: cards/spotlight, etc.) y efectos visuales.
7. Configuración — Medios de pago (18 métodos), moneda (5 modos), PIN de acceso, información del plan.

Encabezado: logo + nombre del negocio, punto de estado (online/offline), badge de plan, reloj, tasa del dólar BCV, botón "Ver sitio".

Disponible en todos los planes. Algunos tabs muestran funciones bloqueadas con candado según el plan (ej: FAQ bloqueado en plan Oportunidad y Crecimiento).',
            ],
            [
                'slug' => 'seccion-info',
                'title' => 'Tab Tu Información — Datos, horario y redes',
                'product' => 'studio',
                'source_file' => 'studio/seccion-info.mdx',
                'content' => 'Tu Información es el primer tab del panel de administración. Tiene 3 sub-tabs: Negocio, Horario, Redes.

Sub-tab Negocio — Campos: Nombre del Negocio (obligatorio), Eslogan, Subdominio (no editable), Teléfono, WhatsApp ventas, WhatsApp Soporte (Plan Crecimiento+), Email, Dirección, Ciudad, Título Sección Contacto, Subtítulo Sección Contacto, Maps URL (Plan Crecimiento+), Indicador Horario (checkbox), Mensaje Cerrado.

También permite personalizar los títulos de cada sección de la landing: Hero Title, Hero Subtitle, Products section title/subtitle, Services section title/subtitle, About section title, Testimonials section title/eyebrow, FAQ section title/eyebrow, Contact section title, Payment Methods section title, Branches section title/eyebrow.

Sub-tab Horario — Configura días y horas de atención. Si activas el Indicador Horario, tu landing muestra automáticamente si estás abierto o cerrado.

Sub-tab Redes — Redes sociales. Plan Oportunidad: 2 redes (Instagram, Facebook). Plan Crecimiento: 4 redes (+TikTok, LinkedIn). Plan Visión: ilimitadas (+YouTube, Twitter/X).',
            ],
            [
                'slug' => 'seccion-diseno',
                'title' => 'Tab Cómo Se Ve — Paletas de color y diseño',
                'product' => 'studio',
                'source_file' => 'studio/seccion-diseno.mdx',
                'content' => 'Cómo Se Ve es el tercer tab del dashboard. Se divide en dos partes: izquierda muestra el grid de paletas disponibles, derecha muestra el selector de paleta personalizada (solo plan Visión).

Hay 30+ paletas organizadas por categoría de negocio:
- Clean: Default, Harvest, Ocean, Cashmere (negocio general)
- Fresh: Olive, Retro, Bubblegum (negocios creativos)
- Bold: Autumn (negocios llamativos)
- Dark: Moon (premium, elegantes)
- Comida: Sabor Tradicional, Fuego Urbano, Parrilla Moderna, Casa Latina
- Dulces: Rosa Vainilla, Pistacho Suave, Cielo Dulce, Chocolate Caramelo
- Salud: Azul Confianza, Verde Calma
- Autoridad: Azul Profesional, Ejecutivo Oscuro, Prestigio Clásico
- Oficios: Industrial Pro, Negro Impacto, Metal Urbano
- Belleza: Nude Elegante, Rosa Studio
- Fitness: Barber Clásico, Fuerza Roja, Verde Potencia, Azul Eléctrico
- Educación: Azul Académico, Verde Progreso, Claro Simple

Para seleccionar: haz clic en cualquier paleta → se aplica inmediatamente. Para paleta personalizada (solo Visión): introduce color hexadecimal → "Aplicar Paleta Custom".

Plan Oportunidad: 10 paletas. Plan Crecimiento: 17 paletas. Plan Visión: 17 + paleta personalizada.',
            ],
            [
                'slug' => 'seccion-productos',
                'title' => 'Tab Qué Vendes — Productos',
                'product' => 'studio',
                'source_file' => 'studio/seccion-productos.mdx',
                'content' => 'La sección Productos del tab Qué Vendes permite agregar y administrar productos. Se encuentra en Dashboard → Qué Vendes (segundo ícono). Muestra un grid de tarjetas (3 columnas desktop) con botón "Agregar Producto".

Campos del producto: Nombre (obligatorio), Descripción, Precio USD (obligatorio), Imagen (máx 2MB, auto WebP 800px), Activo (toggle), Destacado (checkbox), Badge (Destacado ⭐, Hot 🔥, New ✨, Promo 🎉), Posición (orden).

En la landing: grid de 3 columnas, primeros 6 productos visibles, botón "Ver más" carga 6 más. Precios según modo de moneda configurado.

Límites por plan: Oportunidad = 20 productos (1 foto). Crecimiento = 50 productos (1 foto). Visión = ilimitados (3 fotos slider). Cuando llegas al límite, el botón "Agregar Producto" se desactiva.

No maneja inventario ni stock. No permite variantes (talla/color) — para eso SYNTIcat. No tiene carrito de compras. No procesa pagos. No tiene categorías ni filtros.',
            ],
            [
                'slug' => 'seccion-servicios',
                'title' => 'Tab Qué Vendes — Servicios',
                'product' => 'studio',
                'source_file' => 'studio/seccion-servicios.mdx',
                'content' => 'La sección Servicios del tab Qué Vendes permite agregar servicios. Se encuentra en Dashboard → Qué Vendes, debajo de productos.

Campos: Nombre (obligatorio), Descripción, Ícono (selector) O Imagen, CTA Text (texto del botón, ej: "Solicitar cita"), CTA Link (generalmente WhatsApp), Activo (toggle), Posición.

En la landing: tarjetas con dos variantes de diseño: Cards (estándar) y Spotlight (mayor énfasis). Primeros 3 visibles, botón "Ver más" carga más.

Límites por plan: Oportunidad = 3 servicios. Crecimiento = 6 servicios. Visión = 9 servicios.

No permite precios en servicios — para precios usa Productos. No tiene agendamiento ni reservas. No tiene categorías. No tiene formulario de solicitud.',
            ],
            [
                'slug' => 'seccion-visual',
                'title' => 'Tab Visual — Variantes y efectos',
                'product' => 'studio',
                'source_file' => 'studio/seccion-visual.mdx',
                'content' => 'Visual es el sexto tab del dashboard. Permite configurar variantes de diseño para cada sección de la landing.

Variantes disponibles: Hero (fullscreen, gradient, split), Productos (grid3), Servicios (cards, spotlight), Acerca de (split — Plan Crecimiento+), FAQ (accordion — Plan Visión), CTA (centered), Footer (simple, v2).

También configura efectos visuales: espaciado entre secciones, bordes y separadores, animaciones de entrada.

No cambia colores (eso en Cómo Se Ve), no agrega contenido (eso en Tu Información y Qué Vendes), no permite CSS personalizado.',
            ],
            [
                'slug' => 'seccion-config',
                'title' => 'Tab Configuración — Pagos, moneda, PIN y plan',
                'product' => 'studio',
                'source_file' => 'studio/seccion-config.mdx',
                'content' => 'Configuración es el último tab del dashboard. Se organiza en secciones:

Barra superior: Tasas BCV — Tasa USD, Tasa EUR, botón "Actualizar".

Métodos de Pago (izquierda): 11 nacionales (Pago Móvil, Efectivo, Punto de Venta, Biopago, Cashea, Krece, Wepa, Lysto, Chollo, Wally, Kontigo) + 7 internacionales (Zelle, PayPal, Zinli, AirTM, Reserve/RSV, Binance Pay, USDT) + 2 monedas (USD, EUR). Plan Oportunidad: fijo Pago Móvil + Efectivo. Plan Crecimiento+: 18 métodos configurables. Plan Visión: también métodos por sucursal. Botón "Guardar Métodos de Pago".

IMPORTANTE: Los medios de pago son CHIPS INFORMATIVOS. SYNTIweb NO procesa pagos. Solo muestra qué métodos acepta el negocio.

Moneda (derecha): 5 modos — Solo referencia (REF/$), Solo bolívares (Bs.), Ambos con toggle (REF/Bs. por defecto), Euro con toggle (€/Bs.), Ocultar (muestra "Más Info"). Botón "Guardar Moneda".

PIN de Acceso: Campo PIN actual, Nuevo PIN (4 dígitos), Confirmar PIN. Botón "Guardar PIN".

Tu Plan: Badge de plan (amarillo Oportunidad, verde Crecimiento, azul Visión), productos usados (X de Y), servicios usados, miembro desde, renovación. Botón "Ver planes disponibles".',
            ],
            [
                'slug' => 'medios-de-pago',
                'title' => 'Medios de pago — Chips informativos, NO pasarela',
                'product' => 'studio',
                'source_file' => 'studio/medios-de-pago.mdx',
                'content' => 'Los medios de pago en SYNTIweb son CHIPS INFORMATIVOS que aparecen en tu página web para que tus clientes sepan cómo pueden pagarte. Son etiquetas visuales — NO procesan pagos ni cobran automáticamente.

SYNTIweb NO es una pasarela de pago. No procesa transacciones, no cobra a tus clientes, no maneja dinero. Solo muestra qué métodos acepta tu negocio como información visual.

Se configuran en Dashboard → Configuración → sección "Métodos de Pago".

Métodos nacionales (11): Pago Móvil, Efectivo, Punto de Venta, Biopago, Cashea, Krece, Wepa, Lysto, Chollo, Wally, Kontigo.
Métodos internacionales (7): Zelle, PayPal, Zinli, AirTM, Reserve (RSV), Binance Pay, USDT.
Monedas: USD, EUR.

Plan Oportunidad: Solo Pago Móvil y Efectivo (fijo, no se puede cambiar). Plan Crecimiento y Visión: Los 18 métodos configurables. Plan Visión: También métodos por sucursal.

En la landing aparece una franja con chips visuales. Debajo dice: "Información de medios de pago que aceptamos. Nuestro sitio web no es pasarela de pago."

Cuando un cliente quiere pagar, te contacta por WhatsApp y tú le das los datos del método elegido.',
            ],
            [
                'slug' => 'qr-sticker',
                'title' => 'QR y sticker descargable',
                'product' => 'studio',
                'source_file' => 'studio/qr-sticker.mdx',
                'content' => 'El QR de SYNTIweb es un código QR descargable que al escanearlo lleva a tu página web (tunegocio.syntiweb.com). Se genera automáticamente al crear tu negocio.

Para descargarlo: Dashboard → Configuración → sección QR.

Funcionalidades: QR único con URL de tu negocio, tracking de escaneos (registrado en analíticas), descargable en alta resolución, shortlink asociado.

Recomendaciones de impresión: Mínimo 3×3 cm (tarjeta de presentación), 5×5 cm recomendado (sticker vitrina, empaque), 10×10 cm+ (cartelera, banner).

Disponible en todos los planes. No genera QR para redes sociales. No tiene diseño personalizable (logo dentro del QR). No genera QR por producto individual.',
            ],
            [
                'slug' => 'panel-flotante',
                'title' => 'Panel flotante — Cómo acceder al dashboard',
                'product' => 'studio',
                'source_file' => 'studio/panel-flotante.mdx',
                'content' => 'El panel flotante es la interfaz de administración de SYNTIweb. No es una URL separada — aparece encima de tu landing page.

Cómo activarlo: Escritorio → presiona Alt+S. Móvil → mantén presionado el logo de tu negocio durante 3 segundos. Luego ingresa tu PIN de 4 dígitos.

Contiene los 7 tabs: Tu Información, Qué Vendes, Cómo Se Ve, Tu Mensaje, Pulso del Negocio, Visual, Configuración.

No tiene sesión persistente — cada vez que entras necesitas el PIN. No es una app nativa — funciona en el navegador web. Disponible en todos los planes.

Para cambiar el PIN: Dashboard → Configuración → PIN de Acceso → ingresa PIN actual, nuevo PIN (4 dígitos), confirma → "Guardar PIN".

Si olvidaste tu PIN, contacta al soporte de SYNTIweb para restablecerlo. No hay forma de recuperarlo automáticamente.',
            ],
            [
                'slug' => 'whatsapp',
                'title' => 'WhatsApp — Integración y configuración',
                'product' => 'studio',
                'source_file' => 'studio/whatsapp.mdx',
                'content' => 'WhatsApp es el canal principal de comunicación entre tu negocio y tus clientes. No es un chat integrado — es un botón que abre WhatsApp directamente con tu número.

Configurar: Dashboard → Tu Información → Negocio → campo "WhatsApp (ventas)". Segundo número: campo "WhatsApp Soporte" (Plan Crecimiento+).

En la landing: botón flotante de WhatsApp (esquina inferior derecha, siempre visible), botón en sección contacto, botones CTA en servicios.

Formato del número: internacional sin +. Venezuela: 584141234567 (58 + prefijo + número). Mínimo 10 dígitos.

SYNTIstudio: Plan Oportunidad = 1 línea WhatsApp. Plan Crecimiento = 2 líneas (ventas + soporte). Plan Visión = ilimitadas.
SYNTIfood Plan Anual: Pedido Rápido envía pedido completo por WhatsApp.
SYNTIcat Plan Semestral+: Carrito envía pedido con código SC-XXXX por WhatsApp.

No tiene chat integrado, no guarda historial, no envía mensajes automáticos, no funciona con WhatsApp Business API, no es bot.',
            ],

            // ─── SYNTIFOOD ───
            [
                'slug' => 'que-es-syntifood',
                'title' => '¿Qué es SYNTIfood?',
                'product' => 'food',
                'source_file' => 'food/que-es-syntifood.mdx',
                'content' => 'SYNTIfood es el producto de SYNTIweb para restaurantes, areperas, pastelerías, food trucks y negocios de comida. Te da un menú digital profesional con estructura híbrida: 1 foto por categoría + lista de platos con precio. El cliente te hace el pedido directo por WhatsApp.

No requiere foto por cada plato — solo 1 foto por categoría. El venezolano ya sabe leer un menú tipo restaurante físico.

Función estrella: Pedido Rápido (plan Anual) — el cliente toca [+] en cada plato, acumula su pedido y lo envía como mensaje estructurado por WhatsApp.

Planes: Básico REF 9/mes (50 ítems, 6 fotos, sin categorías, sin pedido rápido). Semestral REF 39 (100 ítems, 12 fotos, categorías, BCV, extras). Anual REF 69/año (150 ítems, 18 fotos, todo + Pedido Rápido WhatsApp).

No es carrito de compras, no procesa pagos, no tiene delivery integrado, no tiene inventario, no tiene reservas, no permite foto por plato, no tiene carrito persistente.

Compite con Levery (más caro, sin pedido WhatsApp) y carta impresa (REF 20 cada reimpresión).',
            ],
            [
                'slug' => 'seccion-menu',
                'title' => 'Sección Menú — Categorías y platos',
                'product' => 'food',
                'source_file' => 'food/seccion-menu.mdx',
                'content' => 'La sección Menú se administra en Dashboard → Qué Vendes. Es donde gestionas categorías de comida y platos individuales.

Categorías: tienen nombre (obligatorio), foto representativa (1 por categoría, máx 2MB, auto WebP), orden de aparición. Ejemplos: Arepas, Carnes, Bebidas, Postres.

Ítems (platos): dentro de cada categoría. Campos: nombre (obligatorio), precio (obligatorio, en USD/REF), descripción (opcional), disponible (toggle).

Extras y opciones (Plan Semestral+): tamaño (pequeño/mediano/grande), punto de cocción, extras con precio adicional.

En la landing: formato híbrido — categoría con nombre + 1 foto grande, debajo lista de platos con nombre, precio y botón [+] (si Pedido Rápido activo).

Plan Básico: 50 ítems, 6 fotos, SIN categorías (lista plana). Plan Semestral: 100 ítems, 12 fotos, CON categorías + extras. Plan Anual: 150 ítems, 18 fotos, todo + Pedido Rápido.',
            ],
            [
                'slug' => 'seccion-comandas',
                'title' => 'Comandas — Pedidos recibidos',
                'product' => 'food',
                'source_file' => 'food/seccion-comandas.mdx',
                'content' => 'La sección de Comandas muestra los pedidos recibidos por WhatsApp desde el Pedido Rápido de SYNTIfood. Se encuentra en Dashboard → Pulso del Negocio.

La gestión de comandas se realiza directamente en WhatsApp. SYNTIfood envía el pedido como mensaje estructurado a tu WhatsApp — la confirmación y preparación se coordina ahí, no en la plataforma.

No es un sistema POS, no gestiona estados de pedido (preparando, listo, entregado), no tiene notificaciones push, no calcula tiempos, no genera reportes de ventas detallados, no tiene integración con cocina (ticket printer), no cobra al cliente.

Solo el plan Anual genera pedidos estructurados automáticamente mediante el Pedido Rápido. Sin él, los clientes ven el menú pero te escriben manualmente por WhatsApp.',
            ],
            [
                'slug' => 'pedido-rapido',
                'title' => 'Pedido Rápido — Acumulador WhatsApp',
                'product' => 'food',
                'source_file' => 'food/pedido-rapido.mdx',
                'content' => 'El Pedido Rápido es la función estrella de SYNTIfood. Permite al cliente tocar [+] en cada plato del menú, acumular su pedido y enviarlo como mensaje estructurado por WhatsApp. NO es un carrito de compras — es un acumulador simple tipo "bloc de notas". SOLO disponible en plan Anual (REF 69/año).

Flujo: 1) El cliente navega el menú. 2) Toca [+] en los platos que quiere. 3) Toca "Ver pedido (X items)" para revisar. 4) Toca "Enviar pedido" → se abre WhatsApp.

Formato del mensaje: "🍽️ Pedido desde [Nombre del Negocio] — 2× Arepa reina pepiada — $4.00 c/u — 1× Jugo de guayaba — $2.50 — Total: $10.50 — Enviado desde syntiweb.com"

El restaurante recibe en WhatsApp, confirma disponibilidad y coordina el pago.

No es carrito persistente (se pierde si cierra el navegador), no tiene checkout (no pide dirección ni datos), no procesa pagos, no calcula envío, no tiene confirmación automática. Plan Básico y Semestral: no disponible.',
            ],
            [
                'slug' => 'categorias-e-items',
                'title' => 'Categorías e ítems del menú',
                'product' => 'food',
                'source_file' => 'food/categorias-e-items.mdx',
                'content' => 'Las categorías agrupan los platos en el menú digital (ej: "Entradas", "Platos fuertes", "Bebidas"). Cada categoría tiene nombre, 1 foto representativa y orden. Los ítems son platos individuales con nombre, precio y descripción opcional.

Extras y opciones (Plan Semestral+): Tamaño (pequeño/mediano/grande), punto de cocción (término medio/bien cocido), extras con precio adicional (queso extra, aguacate, papas).

Plan Básico: 50 ítems, 6 fotos, SIN categorías (lista plana). Plan Semestral: 100 ítems, 12 fotos, CON categorías + extras. Plan Anual: 150 ítems, 18 fotos, toda la funcionalidad.

No permite foto individual por ítem, no tiene subcategorías, no tiene alérgenos ni info nutricional, no importa menú desde Excel/PDF, no tiene precios por tamaño integrados.',
            ],

            // ─── SYNTICAT ───
            [
                'slug' => 'que-es-synticat',
                'title' => '¿Qué es SYNTIcat?',
                'product' => 'cat',
                'source_file' => 'cat/que-es-synticat.mdx',
                'content' => 'SYNTIcat es el producto de SYNTIweb para tiendas de ropa, proveedores, comercios con catálogo y retail venezolano. Te da un catálogo visual profesional con productos, fotos, variantes (talla, color), carrito de compras y checkout por WhatsApp. En el plan Anual, cada pedido tiene código de seguimiento SC-XXXX.

Planes: Básico REF 9/mes (20 productos, 1 imagen, sin carrito — solo botón WA directo). Semestral REF 39 (100 productos, 3 imágenes, carrito básico, variantes talla/color). Anual REF 69/año (productos ilimitados, 6 imágenes, carrito completo + Mini Order SC-XXXX).

No procesa pagos, no tiene pasarela, no maneja inventario, no tiene wishlist, no tiene búsqueda/filtros, no tiene categorías, no tiene reseñas, no calcula envío.

Compite con Cattaly (REF 97/año sin carrito). SYNTIcat REF 69/año con carrito incluido.',
            ],
            [
                'slug' => 'catalogo-productos-cat',
                'title' => 'Catálogo de productos SYNTIcat',
                'product' => 'cat',
                'source_file' => 'cat/catalogo-productos.mdx',
                'content' => 'El catálogo de productos se administra en Dashboard → Qué Vendes. Campos: Nombre (obligatorio), Precio (obligatorio, USD/REF), Descripción, Imágenes (1 a 6 según plan, máx 2MB, auto WebP 800px), Badge (Nuevo 🆕, Hot 🔥, Promo 💰), Activo (toggle), Variantes.

Variantes por plan: Básico = solo simple (sin variantes). Semestral = talla (size) y talla+color (size_color). Anual = todas + opciones libres (options — extras, personalización).

En la landing: grid de tarjetas con foto, nombre, precio, badge. Botón "Agregar al carrito" (Semestral+) o "Consultar por WhatsApp" (Básico).

Límites: Básico = 20 productos (1 imagen). Semestral = 100 productos (3 imágenes). Anual = ilimitados (6 imágenes).

Para desactivar un producto agotado: toggle Activo off — desaparece del catálogo pero queda guardado.',
            ],
            [
                'slug' => 'carrito-whatsapp',
                'title' => 'Carrito y WhatsApp checkout SYNTIcat',
                'product' => 'cat',
                'source_file' => 'cat/carrito-whatsapp.mdx',
                'content' => 'El carrito de SYNTIcat permite a los clientes seleccionar productos, elegir variantes, revisar y enviar el pedido por WhatsApp. No es checkout con pasarela — es un carrito que genera un mensaje de WhatsApp.

Flujo: 1) Cliente navega catálogo. 2) Toca "Agregar al carrito" (elige variantes si aplica). 3) Se abre Cart Drawer (panel lateral derecho) con productos, cantidades y total. 4) Revisa y modifica. 5) "Finalizar pedido" → WhatsApp se abre con mensaje estructurado.

Formato: "🛍️ Nuevo pedido SYNTIcat — 📦 Orden: SC-0047 — 1× Camisa polo azul (Talla M) — REF 15.00 — 2× Correa de cuero marrón — REF 8.00 c/u — 💰 Total: REF 31.00 — Bs. 1,131.50"

Plan Básico: SIN carrito — solo botón "Consultar por WhatsApp" por producto individual. Plan Semestral: carrito básico sin código SC. Plan Anual: carrito completo + código SC-XXXX.

El carrito usa localStorage (si borran datos del navegador se pierde). No procesa pagos, no pide datos del cliente, no calcula envío, no tiene cupones.',
            ],
            [
                'slug' => 'mini-order-sc',
                'title' => 'Mini Order — Código de seguimiento SC-XXXX',
                'product' => 'cat',
                'source_file' => 'cat/mini-order-sc.mdx',
                'content' => 'El Mini Order genera un código de seguimiento SC-XXXX para cada pedido en SYNTIcat. Formato secuencial: SC-0001, SC-0002, SC-0003. Solo disponible en plan Anual (REF 69/año).

El código viaja en el mensaje de WhatsApp y se guarda como archivo JSON en el servidor del tenant (storage/tenants/{tenant_id}/orders/2026/03/SC-XXXX.json).

JSON incluye: código SC-XXXX, lista de productos (nombre, variante, cantidad, precio), total, fecha/hora, tenant ID.

Usos: Identificar pedidos ("¿Tu pedido es el SC-0047?"), evitar confusiones entre pedidos similares, dar imagen profesional, seguimiento ("Tu pedido SC-0047 ya está listo").

No rastrea estados (preparando/enviado/entregado), no envía notificaciones automáticas, no tiene panel de órdenes en dashboard (próximamente), no genera factura, no permite cancelar desde plataforma.

Plan Básico y Semestral: no disponible.',
            ],

            // ─── REFERENCIA ───
            [
                'slug' => 'glosario',
                'title' => 'Glosario de términos SYNTIweb',
                'product' => 'shared',
                'source_file' => 'referencia/glosario.mdx',
                'content' => 'Términos generales: SYNTIweb = la plataforma completa. Landing = tu página web pública (tunegocio.syntiweb.com). Dashboard = panel de administración flotante (Alt+S/long press logo + PIN). Blueprint = tipo de producto (studio/food/cat). Tenant = tu negocio dentro de la plataforma. Subdominio = tu dirección web (tunegocio.syntiweb.com).

Precios y moneda: REF = referencia en dólares (REF 15 = $15 USD). BCV = Banco Central de Venezuela (tasa oficial para convertir REF a bolívares). Modo de moneda: reference_only (solo REF/$), bolivares_only (solo Bs.), both_toggle (REF y Bs. con toggle — por defecto), euro_toggle (€ y Bs.), hidden (ocultar precios).

Dashboard: Los 7 tabs — Tu Información, Qué Vendes, Cómo Se Ve, Tu Mensaje, Pulso del Negocio, Visual, Configuración. PIN = 4 dígitos. Badge = etiqueta de producto. Paleta = esquema de colores. CTA = Llamado a Acción.

Productos: Variante = talla/color (SYNTIcat). Categoría = agrupación de platos (SYNTIfood). Ítem = plato individual.

Pedidos: Pedido Rápido = acumulador WhatsApp (SYNTIfood Anual). Mini Order/SC-XXXX = código de seguimiento (SYNTIcat Anual). Cart Drawer = panel lateral del carrito.

Técnicos: WebP = formato de imagen optimizado. QR = código escaneable. Schema.org = SEO estructurado.

IMPORTANTE: Medios de pago = chips informativos, NO pasarela de pago. SYNTIweb NO procesa transacciones.',
            ],
            [
                'slug' => 'preguntas-frecuentes',
                'title' => 'Preguntas frecuentes',
                'product' => 'shared',
                'source_file' => 'referencia/preguntas-frecuentes.mdx',
                'content' => 'Plataforma: No necesitas programar ni diseñador. Funciona en cualquier celular con navegador web. Puedes tener múltiples negocios (cada uno con su plan). Si no renuevas, tu página se congela pero los datos se mantienen.

Dashboard: Se activa con Alt+S (escritorio) o long press logo 3 segundos (móvil) + PIN de 4 dígitos. Si olvidaste el PIN, contacta soporte para restablecerlo. Los cambios se reflejan inmediatamente. El subdominio no se puede cambiar después de crearlo.

Planes: REF = referencia dólares. Puedes subir de plan en cualquier momento. SYNTIstudio (desde REF 99/año), SYNTIfood (desde REF 9/mes), SYNTIcat (desde REF 9/mes). No se pueden combinar en un solo subdominio.

Pagos: SYNTIweb NO cobra a tus clientes ni procesa pagos. Los medios de pago son chips informativos. La tasa BCV se actualiza automáticamente (actualizar manualmente desde Dashboard → Configuración → botón "Actualizar"). Se puede mostrar precios en euros (modo euro_toggle). Se pueden ocultar precios (modo hidden → muestra "Más Info").

Imágenes: Formatos JPG, PNG, WebP. Máximo 2MB. Se convierten automáticamente a WebP 800px. Fotos por producto: Studio 1-3 según plan, Food 1 por categoría, Cat 1-6 según plan.

Problemas: Alt+S no funciona → verificar que estás en tu landing, no en campo de texto, reiniciar navegador. Imágenes no suben → verificar tamaño (<2MB) y formato. Tasa BCV deactualizada → botón "Actualizar" en Configuración. Página "No disponible" → plan vencido o tenant suspendido, contactar soporte.',
            ],
        ];
    }
}
