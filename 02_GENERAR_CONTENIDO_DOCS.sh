#!/bin/bash
# ============================================================
# SYNTIWEB — Generar todos los .mdx con contenido
# Ejecutar desde: C:\laragon\www\synticorex\
# Comando: bash 02_GENERAR_CONTENIDO_DOCS.sh
# ============================================================

echo "📝 Generando contenido de todos los .mdx..."

# ============================================================
# SHARED
# ============================================================

cat > docs/shared/quickstart.mdx << 'EOF'
---
title: "Primeros pasos"
description: "Desde el registro hasta tu página publicada en menos de 10 minutos"
---

Esto es todo lo que necesitas para tener tu negocio en línea hoy mismo.

## Lo que necesitas

- Un teléfono o computadora con internet
- El número de WhatsApp de tu negocio
- Una foto de tu negocio o logo (opcional, puedes agregarla después)

## Paso a paso

<Steps>
  <Step title="Crea tu cuenta">
    Ve a [syntiweb.com](https://syntiweb.com) y haz clic en **Empieza gratis**. Ingresa tu correo y crea una contraseña.
  </Step>
  <Step title="Elige tu producto">
    - **SYNTIstudio** → si tienes cualquier negocio (barbería, clínica, tienda, etc.)
    - **SYNTIfood** → si tienes restaurante, fonda o venta de comida
    - **SYNTIcat** → si vendes productos con catálogo (ropa, electrónica, etc.)
  </Step>
  <Step title="Completa el asistente">
    El sistema te hace 5 preguntas simples: nombre del negocio, tipo de negocio, WhatsApp, ciudad y colores. No necesitas saber nada técnico.
  </Step>
  <Step title="Tu página está lista">
    En segundos queda publicada en `tunegocio.syntiweb.com`. Compártela por WhatsApp o imprime el QR.
  </Step>
</Steps>

## Acceder a tu panel de administración

Desde el celular: **mantén presionado el logo de SYNTIweb 3 segundos**.
Desde computadora: presiona **Alt + S**.

Ingresa tu PIN (lo configuraste en el asistente) y ya puedes editar todo.

<Tip>
  Guarda el link de tu página y el PIN en un lugar seguro. Con eso manejas todo tu negocio digital.
</Tip>
EOF

cat > docs/shared/cuenta-y-planes.mdx << 'EOF'
---
title: "Planes y precios"
description: "Elige el plan que se adapta a tu negocio"
---

SYNTIweb tiene planes para cada tamaño de negocio. Todos incluyen tu página publicada, QR descargable y administración desde el celular.

## SYNTIstudio

Para cualquier negocio que necesite página web completa.

| | Oportunidad | Crecimiento | Visión |
|---|---|---|---|
| **Precio mensual** | REF 13/mes | REF 19/mes | REF 25/mes |
| **Precio anual** | REF 99/año | REF 149/año | REF 199/año |
| **Productos** | 20 | 50 | Ilimitados |
| **Servicios** | 3 | 6 | 9 |
| **Fotos por producto** | 1 | 1 | 3 fotos (slider) |
| **Secciones** | Básicas | + Testimonios | + FAQ + Sucursales |
| **Paletas de color** | 10 | 17 | 17 + paleta propia |

## SYNTIfood

Para restaurantes, fondas y negocios de comida.

| | Básico | Semestral | Anual |
|---|---|---|---|
| **Precio** | REF 9/mes | REF 39 | REF 69 |
| **Ítems en menú** | 50 | 100 | 150 |
| **Fotos de categoría** | 6 | 12 | 18 |
| **Tasa BCV automática** | ✗ | ✅ | ✅ |
| **Extras y opciones** | ✗ | ✅ | ✅ |
| **Pedido Rápido WhatsApp** | ✗ | ✗ | ✅ |

## SYNTIcat

Para tiendas y negocios con catálogo de productos.

| | Básico | Semestral | Anual |
|---|---|---|---|
| **Precio** | REF 9/mes | REF 39 | REF 69 |
| **Productos** | 20 | 100 | Ilimitados |
| **Carrito de compras** | ✗ | ✅ | ✅ |
| **Código de orden** | ✗ | ✅ | ✅ |
| **Variantes (talla/color)** | ✅ | ✅ | ✅ |

<Note>
  Los precios están en REF (referencia dólar). El equivalente en bolívares se calcula automáticamente con la tasa BCV del día.
</Note>
EOF

cat > docs/shared/moneda-y-precios.mdx << 'EOF'
---
title: "Moneda y precios"
description: "Cómo funciona el sistema de moneda en SYNTIweb"
---

SYNTIweb está diseñado para el mercado venezolano dolarizado. Puedes mostrar los precios de tus productos de 4 formas diferentes.

## Modos de moneda

**Solo REF** — Muestra el precio en dólares de referencia. Ejemplo: `REF 5.00`

**Solo bolívares** — Convierte automáticamente usando la tasa BCV del día. Ejemplo: `Bs. 182.50`

**REF y bolívares con toggle** — Tu cliente puede cambiar entre los dos con un botón. El más popular.

**Sin precios** — Oculta todos los precios. Útil si manejas precios por consulta.

## Cambiar el modo de moneda

1. Entra a tu panel de administración (Alt+S o long press en el logo)
2. Ve a la pestaña **Config**
3. Busca la sección **Moneda**
4. Selecciona el modo que prefieres
5. Guarda los cambios — se aplica de inmediato

## Tasa BCV automática

Si usas bolívares, SYNTIweb actualiza la tasa del BCV automáticamente cada día. No tienes que cambiar los precios manualmente cuando suba o baje el dólar.

<Tip>
  El modo **REF y bolívares con toggle** es el favorito de los negocios venezolanos porque el cliente ve el precio en lo que prefiera sin confusión.
</Tip>
EOF

cat > docs/shared/dominio-y-subdominio.mdx << 'EOF'
---
title: "Tu dirección web"
description: "Cómo funciona tu link y cómo conectar tu propio dominio"
---

## Tu dirección por defecto

Cuando creas tu negocio en SYNTIweb, tu página queda disponible en:

```
tunegocio.syntiweb.com
```

Por ejemplo: `pizzeriacaracas.syntiweb.com` o `barberiajos.syntiweb.com`

Esta dirección es permanente, gratuita y funciona desde el primer día.

## Dominio propio (plan Anual)

Si tienes o quieres comprar tu propio dominio (`www.tunegocio.com`), puedes conectarlo a SYNTIweb con el plan Anual de SYNTIstudio o SYNTIcat.

<Steps>
  <Step title="Compra tu dominio">
    En cualquier registrador: GoDaddy, Namecheap, o el proveedor que prefieras.
  </Step>
  <Step title="Configura el DNS">
    En tu registrador, agrega un registro CNAME apuntando a `tunegocio.syntiweb.com`.
  </Step>
  <Step title="Actívalo en SYNTIweb">
    Ve a **Config → Dominio propio** e ingresa tu dominio. En menos de 24 horas queda activo.
  </Step>
</Steps>

<Note>
  El dominio propio está disponible en los planes Anuales de SYNTIstudio y SYNTIcat. SYNTIfood anual también lo tendrá próximamente.
</Note>
EOF

cat > docs/shared/whatsapp.mdx << 'EOF'
---
title: "Configurar WhatsApp"
description: "Conecta tu WhatsApp para recibir pedidos y consultas"
---

WhatsApp es el canal principal de SYNTIweb. Todos los pedidos, consultas y contactos llegan a tu WhatsApp directamente.

## Agregar tu número de WhatsApp

1. Entra al panel de administración
2. Ve a la pestaña **Info**
3. En el campo **WhatsApp ventas** ingresa tu número con código de país

**Formato correcto:** `+58 412 1234567`

<Warning>
  Escribe el número completo con el código de país (+58 para Venezuela). Sin el código, los clientes no podrán escribirte desde fuera del país.
</Warning>

## Dos WhatsApp (plan Anual SYNTIfood)

Si tienes un número para ventas y otro para soporte, puedes configurar ambos. El cliente elige a cuál escribir.

- **WhatsApp ventas** → para pedidos
- **WhatsApp soporte** → para preguntas o reclamos

## Cómo llega el pedido

Cuando un cliente hace un pedido, SYNTIweb construye un mensaje automático con todos los detalles y abre WhatsApp listo para enviártelo. Tú recibes algo así:

```
Hola! Mi pedido es:
- 2x Pizza Margarita (REF 8.00 c/u)
- 1x Refresco (REF 2.00)
Total: REF 18.00
```

<Tip>
  Activa las notificaciones de WhatsApp para no perderte ningún pedido.
</Tip>
EOF

cat > docs/shared/imagenes.mdx << 'EOF'
---
title: "Subir imágenes"
description: "Cómo agregar fotos a tu negocio, productos y servicios"
---

Las imágenes hacen que tu página se vea profesional. SYNTIweb las optimiza automáticamente para que carguen rápido en cualquier teléfono.

## Tipos de imágenes

- **Logo** — La imagen principal de tu negocio. Aparece en la parte superior de tu página.
- **Hero** — La foto grande de bienvenida. Lo primero que ve tu cliente.
- **Productos** — Una foto por producto (o hasta 3 en plan Visión).
- **Servicios** — Foto representativa de cada servicio.
- **Acerca de** — Foto de tu equipo o local para la sección "Sobre nosotros".

## Cómo subir una imagen

1. Entra al panel de administración
2. Ve a la sección correspondiente (Info, Productos, etc.)
3. Haz clic en el área de imagen o en el ícono de cámara
4. Selecciona la foto desde tu teléfono o computadora
5. La imagen se guarda automáticamente

## Recomendaciones

- **Tamaño máximo:** 2MB por imagen
- **Formatos aceptados:** JPG, PNG, WebP
- **Orientación logo:** cuadrada o rectangular horizontal
- **Orientación hero:** horizontal (paisaje)
- **Productos:** cuadrada funciona mejor

<Note>
  SYNTIweb convierte todas las imágenes a formato WebP automáticamente. Esto hace que tu página cargue más rápido sin perder calidad.
</Note>
EOF

cat > docs/shared/faq.mdx << 'EOF'
---
title: "Preguntas frecuentes"
description: "Las dudas más comunes de nuestros usuarios"
---

<AccordionGroup>
  <Accordion title="¿Necesito saber programar para usar SYNTIweb?">
    No. SYNTIweb está diseñado para dueños de negocio, no para programadores. Si puedes usar WhatsApp, puedes usar SYNTIweb.
  </Accordion>

  <Accordion title="¿Puedo cambiar el diseño de mi página después?">
    Sí, en cualquier momento. Desde el panel de administración puedes cambiar colores, fotos, textos y secciones sin límite.
  </Accordion>

  <Accordion title="¿Qué pasa si se me olvida el PIN?">
    Contáctanos por WhatsApp de soporte y te ayudamos a recuperarlo. Por seguridad no podemos enviarlo por correo automáticamente.
  </Accordion>

  <Accordion title="¿Puedo tener varios negocios con la misma cuenta?">
    Cada negocio necesita su propia cuenta. Si tienes una pizzería y una barbería, necesitas dos cuentas separadas.
  </Accordion>

  <Accordion title="¿Funciona sin internet rápido?">
    Sí. SYNTIweb está optimizado para conexiones lentas. Las páginas cargan rápido incluso con datos móviles básicos.
  </Accordion>

  <Accordion title="¿Los clientes pueden pagar en línea?">
    Por ahora los pagos se coordinan directamente por WhatsApp (transferencia, pago móvil, Binance, etc.). Las pasarelas de pago en línea están en desarrollo.
  </Accordion>

  <Accordion title="¿Qué pasa si no renuevo mi plan?">
    Tu página entra en período de gracia por 7 días. Si no renuevas, la página se congela temporalmente pero no se borra. Cuando renuevas, vuelve a estar activa con todo tu contenido.
  </Accordion>

  <Accordion title="¿Puedo usar mi propio dominio como www.minegocio.com?">
    Sí, con el plan Anual de SYNTIstudio o SYNTIcat. Necesitas comprar el dominio por separado y luego conectarlo en la configuración.
  </Accordion>

  <Accordion title="¿La tasa del dólar se actualiza sola?">
    Sí. SYNTIweb consulta la tasa BCV automáticamente cada día. No tienes que actualizar precios manualmente.
  </Accordion>

  <Accordion title="¿Cómo descargo el QR de mi negocio?">
    En el panel de administración, pestaña **Diseño**, encontrarás tu QR listo para descargar e imprimir.
  </Accordion>
</AccordionGroup>
EOF

cat > docs/shared/glosario.mdx << 'EOF'
---
title: "Glosario"
description: "Términos de SYNTIweb explicados en simple"
---

## REF
La moneda que usa SYNTIweb para mostrar precios. REF significa "referencia dólar". Es el precio en dólares americanos de tu producto o servicio.

## Blueprint
El tipo de página de tu negocio. Hay tres: Studio (página completa), Food (menú digital) y Cat (catálogo con carrito).

## Panel de administración
El lugar donde editas todo tu negocio. Se accede con PIN desde el celular o computadora. Es invisible para tus clientes.

## PIN
Tu contraseña de 4 dígitos para entrar al panel de administración. Lo defines tú cuando creas tu cuenta.

## QR
El código cuadrado que tus clientes escanean con el celular para ver tu página. SYNTIweb lo genera automáticamente.

## Subdominio
La dirección de tu página: `tunegocio.syntiweb.com`. El "tunegocio" es el subdominio.

## Tasa BCV
El precio oficial del dólar según el Banco Central de Venezuela. SYNTIweb lo usa para convertir tus precios REF a bolívares automáticamente.

## Tenant
Término interno que usamos para referirnos a cada negocio en SYNTIweb. Cada tenant tiene su propia página, contenido y configuración.

## WhatsApp Checkout
Cuando un cliente hace un pedido y SYNTIweb construye el mensaje automáticamente para enviar por WhatsApp. El cliente solo toca "Enviar".

## Plan Anual / Semestral / Mensual
Las formas de pagar SYNTIweb. El anual es el más económico por mes. El semestral es por 6 meses. El mensual se paga mes a mes.
EOF

cat > docs/shared/troubleshooting.mdx << 'EOF'
---
title: "Solución de problemas"
description: "Soluciones a los problemas más comunes"
---

## No puedo entrar al panel de administración

**Problema:** Presiono Alt+S o el logo y no pasa nada.

**Solución:**
1. En desktop: asegúrate de presionar **Alt + S** al mismo tiempo
2. En celular: mantén presionado el **logo de SYNTIweb** por 3 segundos completos
3. Si sigue sin funcionar, recarga la página y vuelve a intentar

---

## Olvidé mi PIN

Escríbenos por WhatsApp de soporte indicando el nombre de tu negocio y el correo con que te registraste. Te ayudamos a recuperarlo en minutos.

---

## Mi imagen no sube

**Causas comunes:**
- La imagen pesa más de 2MB → comprímela en [squoosh.app](https://squoosh.app) antes de subir
- El formato no es compatible → convierte a JPG o PNG
- Conexión lenta → intenta con WiFi

---

## Mi página no se ve bien en el celular

1. Abre tu página en el celular
2. Toca los tres puntos del navegador
3. Selecciona **Recargar** o **Actualizar**

Si sigue con problemas, escríbenos con una captura de pantalla.

---

## El precio en bolívares está desactualizado

La tasa BCV se actualiza automáticamente cada día. Si ves un precio desactualizado:
1. Entra al panel → **Config** → **Moneda**
2. Guarda los cambios aunque no cambies nada
3. Esto fuerza la actualización de la tasa

---

## Mi WhatsApp no abre cuando el cliente hace un pedido

Verifica que el número de WhatsApp en tu configuración tenga el formato correcto: `+58 412 1234567` (con el +58 al inicio, sin espacios adicionales).

---

## ¿No encuentras tu problema aquí?

Escríbenos directamente por WhatsApp. Respondemos en horario de atención de lunes a sábado.
EOF

# ============================================================
# STUDIO
# ============================================================

cat > docs/studio/que-es-studio.mdx << 'EOF'
---
title: "¿Qué es SYNTIstudio?"
description: "Página web profesional para cualquier tipo de negocio venezolano"
---

SYNTIstudio es el producto de SYNTIweb para negocios que necesitan una **página web completa** — no solo un menú ni solo un catálogo, sino una presencia digital total.

## ¿Para qué tipo de negocio es?

Cualquiera. Algunos ejemplos:

- Barberías y salones de belleza
- Clínicas y consultorios
- Academias y centros educativos
- Tiendas de servicios técnicos
- Restaurantes que también ofrecen servicios
- Distribuidoras y negocios mixtos
- Cualquier emprendedor que quiera página web

## Qué incluye tu página

<CardGroup cols={2}>
  <Card title="Hero / Banner" icon="image">
    La foto principal de tu negocio con tu slogan y botón de WhatsApp.
  </Card>
  <Card title="Productos" icon="box">
    Galería de tus productos con foto, nombre, descripción y precio.
  </Card>
  <Card title="Servicios" icon="wrench">
    Lista de lo que ofreces con descripción y precio referencial.
  </Card>
  <Card title="Contacto" icon="phone">
    Tu WhatsApp, teléfono, dirección y mapa.
  </Card>
  <Card title="QR descargable" icon="qrcode">
    Código QR listo para imprimir y pegar en tu local.
  </Card>
  <Card title="SEO automático" icon="magnifying-glass">
    Tu negocio aparece en Google sin hacer nada extra.
  </Card>
</CardGroup>

## Diferencia con Food y Cat

| | Studio | Food | Cat |
|---|---|---|---|
| Página web completa | ✅ | ✅ | ✅ |
| Menú con lista de platos | ✗ | ✅ | ✗ |
| Carrito de compras | ✗ | ✗ | ✅ |
| Mejor para | Cualquier negocio | Restaurantes | Tiendas |

<Note>
  Si tienes un restaurante con muchos platos y precios variables, SYNTIfood es mejor opción. Si vendes productos con tallas o colores, SYNTIcat es más adecuado.
</Note>
EOF

cat > docs/studio/wizard-onboarding.mdx << 'EOF'
---
title: "Asistente de configuración"
description: "Cómo configurar tu página de Studio por primera vez"
---

Cuando creas tu cuenta en SYNTIstudio, el asistente de configuración te guía paso a paso. No tarda más de 5 minutos.

## Los 5 pasos

<Steps>
  <Step title="Nombre y tipo de negocio">
    Escribe el nombre de tu negocio y selecciona a qué se dedica (restaurante, barbería, tienda, etc.). Esto ayuda a SYNTIweb a sugerir los mejores colores y textos para ti.
  </Step>
  <Step title="Información de contacto">
    Tu número de WhatsApp, teléfono y ciudad. Con esto tus clientes pueden contactarte desde tu página.
  </Step>
  <Step title="Foto principal">
    Sube una foto de tu local, logo o producto estrella. Esta es la primera imagen que verán tus clientes. Puedes subirla después si no tienes una a mano.
  </Step>
  <Step title="Colores de tu negocio">
    Elige una paleta de colores. SYNTIweb te muestra opciones organizadas por tipo de negocio. Un click y tu página cambia de color al instante.
  </Step>
  <Step title="Confirmar y publicar">
    Revisa el resumen y haz clic en **Publicar**. Tu página queda en línea de inmediato.
  </Step>
</Steps>

## Después del asistente

Tu página ya está publicada pero puedes seguir mejorándola:

- Agrega productos y servicios desde el panel
- Sube más fotos
- Personaliza cada sección
- Descarga tu QR

<Tip>
  No esperes tener todo perfecto para publicar. Publica con lo básico y ve mejorando poco a poco. Un negocio con página básica es mejor que uno sin página.
</Tip>
EOF

cat > docs/studio/dashboard.mdx << 'EOF'
---
title: "Panel de administración"
description: "Cómo usar el panel para gestionar tu negocio"
---

El panel de administración es donde controlas todo lo de tu página. Es invisible para tus clientes — solo tú puedes verlo con tu PIN.

## Cómo abrirlo

**Desde el celular:** Mantén presionado el logo de SYNTIweb en tu página por 3 segundos.

**Desde computadora:** Presiona **Alt + S** mientras estás en tu página.

Ingresa tu PIN de 4 dígitos y listo.

## Las 6 pestañas

**Info** — Datos básicos: nombre del negocio, WhatsApp, descripción, slogan, horarios.

**Productos** — Agrega, edita o elimina productos. Sube fotos, cambia precios, activa o desactiva.

**Servicios** — Lo mismo que productos pero para tus servicios.

**Diseño** — Cambia colores, foto principal, foto de "Acerca de", y descarga tu QR.

**Analytics** — Ve cuántas visitas ha tenido tu página, de dónde vienen y qué secciones revisan más.

**Config** — Moneda, redes sociales, métodos de pago aceptados, horarios de atención, PIN.

## Guardar cambios

Cada campo tiene su botón de guardar. Los cambios se aplican en tu página en segundos — no hay que esperar ni publicar manualmente.

<Warning>
  Si cierras el panel sin guardar, los cambios se pierden. Siempre guarda antes de cerrar.
</Warning>
EOF

cat > docs/studio/hero-y-banner.mdx << 'EOF'
---
title: "Hero y banner"
description: "La foto principal y mensaje de bienvenida de tu página"
---

El hero es lo primero que ve tu cliente cuando entra a tu página. Es tu oportunidad de causar una buena primera impresión.

## Qué es el hero

Es la sección grande en la parte superior de tu página. Incluye:

- Una foto de fondo (tu local, tu producto, o algo representativo)
- El nombre de tu negocio
- Tu slogan o descripción corta
- Un botón que lleva directo a tu WhatsApp

## Cómo cambiar la foto del hero

1. Abre el panel de administración
2. Ve a **Diseño**
3. En la sección **Hero** haz clic en la imagen actual
4. Selecciona una nueva foto
5. Se guarda automáticamente

## Consejos para una buena foto de hero

- Usa una foto horizontal (paisaje), no vertical
- Que se vea tu local, tu producto estrella, o tu equipo
- Buena iluminación — evita fotos oscuras o borrosas
- Tamaño máximo 2MB

## El banner superior (plan Crecimiento y Visión)

Además del hero, puedes activar un banner en la parte más alta de la página para destacar una promoción o mensaje especial. Ejemplo: "🔥 Descuento del 20% esta semana".

Para activarlo: **Config → Banner superior → Activar → Escribe el mensaje → Guardar**.

<Tip>
  El botón del hero dice "Escríbenos" por defecto y abre tu WhatsApp. Es el elemento que más clics recibe en toda la página.
</Tip>
EOF

cat > docs/studio/productos-y-servicios.mdx << 'EOF'
---
title: "Productos y servicios"
description: "Cómo agregar y gestionar tu catálogo en SYNTIstudio"
---

## Agregar un producto

1. Abre el panel de administración
2. Ve a la pestaña **Productos**
3. Haz clic en **+ Nuevo producto**
4. Completa los campos:
   - **Nombre** (obligatorio)
   - **Descripción** (opcional pero recomendado)
   - **Precio** en REF
   - **Foto** (opcional)
   - **Badge** — puedes marcarlo como Nuevo, Hot o Promo
5. Haz clic en **Guardar**

El producto aparece en tu página de inmediato.

## Agregar un servicio

El proceso es igual que productos. Ve a la pestaña **Servicios** → **+ Nuevo servicio**.

Los servicios se muestran en una sección separada de los productos en tu página.

## Límites por plan

| Plan | Productos | Servicios |
|------|-----------|-----------|
| Oportunidad | 20 | 3 |
| Crecimiento | 50 | 6 |
| Visión | Ilimitados | 9 |

## Activar o desactivar sin borrar

Si un producto está agotado o un servicio no está disponible temporalmente, puedes desactivarlo sin borrarlo. Así no aparece en tu página pero lo tienes guardado para cuando vuelva.

Panel → Productos → toca el toggle junto al producto → queda oculto en tu página.

## Reordenar productos

Mantén presionado un producto y arrástralo a la posición que quieras. El orden se guarda automáticamente.

<Note>
  En el plan Visión cada producto puede tener hasta 3 fotos que se muestran como un carrusel. Ideal para ropa, accesorios o productos que se ven mejor desde varios ángulos.
</Note>
EOF

cat > docs/studio/paletas-de-color.mdx << 'EOF'
---
title: "Paletas de color"
description: "Personaliza los colores de tu página"
---

Los colores de tu página crean la primera impresión. SYNTIweb tiene más de 25 paletas diseñadas especialmente para cada tipo de negocio.

## Cambiar la paleta

1. Abre el panel de administración
2. Ve a **Diseño**
3. En la sección **Colores** verás todas las paletas disponibles
4. Haz clic en cualquiera — tu página cambia en tiempo real
5. Cuando encuentres la que te gusta, toca **Guardar**

## Paletas por tipo de negocio

Las paletas están organizadas por categoría para que encuentres la tuya más rápido:

- **Restaurantes** — cálidos, apetitosos
- **Salud y belleza** — limpios, profesionales
- **Tecnología** — modernos, oscuros
- **Retail** — vibrantes, comerciales
- **Servicios** — confiables, neutros
- Y más...

## Paleta personalizada (plan Visión)

Con el plan Visión puedes crear tu propia paleta con cualquier color. Útil si tu negocio ya tiene una identidad visual definida.

Panel → Diseño → **Paleta personalizada** → ingresa el código de color hexadecimal de tu marca.

<Tip>
  Si no sabes qué colores elegir, usa la paleta que ya tiene seleccionada tu tipo de negocio. Están probadas para generar confianza en tus clientes.
</Tip>
EOF

cat > docs/studio/qr-sticker.mdx << 'EOF'
---
title: "QR Sticker"
description: "Descarga e imprime el código QR de tu negocio"
---

Tu QR es uno de los activos más valiosos de tu negocio digital. Cuando un cliente lo escanea, va directo a tu página.

## Dónde encontrar tu QR

1. Abre el panel de administración
2. Ve a la pestaña **Diseño**
3. Busca la sección **QR Sticker**
4. Toca **Descargar QR**

## Cómo usarlo

**Imprímelo y pégalo en tu local** — en la caja, en la entrada, en las mesas si tienes restaurante.

**Inclúyelo en tus bolsas o empaque** — el cliente lo escanea cuando llega a casa y ya tiene tu página guardada.

**Compártelo por WhatsApp** — mándalo en tu estado de WhatsApp para que tus contactos puedan visitar tu página.

**Úsalo en tarjetas de presentación** — más moderno y útil que solo poner el link.

## Formatos disponibles

El QR se descarga listo para usar. Viene en tamaño adecuado para impresión — no pierde calidad aunque lo amplíes.

<Tip>
  Imprime el QR en tamaño mínimo de 3x3 cm para que los celulares puedan escanearlo fácilmente. Si es muy pequeño algunos teléfonos tienen dificultades.
</Tip>
EOF

cat > docs/studio/seo-automatico.mdx << 'EOF'
---
title: "SEO automático"
description: "Cómo SYNTIweb hace que tu negocio aparezca en Google"
---

SEO significa que cuando alguien busca en Google "barbería en Caracas" o "pizzería en Maracaibo", tu negocio aparece en los resultados. SYNTIweb lo hace automáticamente — no tienes que hacer nada.

## Cómo funciona

Cuando creas tu negocio, SYNTIweb genera automáticamente:

- Un título optimizado para Google basado en tu tipo de negocio y ciudad
- Una descripción que aparece debajo de tu link en los resultados
- Etiquetas especiales que Google lee para entender de qué trata tu negocio
- Un sitemap que le dice a Google todas las páginas de tu sitio

## Ejemplo real

Si tienes una barbería en Valencia:

**Lo que aparece en Google:**
```
Barbería [Tu Nombre] - Valencia | Cortes y Barba
Tu barbería de confianza en Valencia. Cortes clásicos y modernos,
arreglo de barba. Pide tu turno por WhatsApp.
```

## Mejora tu SEO con buen contenido

Aunque el SEO básico es automático, puedes mejorar tus resultados:

- Escribe descripciones detalladas en tus productos y servicios
- Usa el nombre de tu ciudad en la descripción de tu negocio
- Agrega fotos de buena calidad — Google las indexa también
- Completa todos los campos de información de tu negocio

## Plan Visión — SEO avanzado

Con el plan Visión el SEO incluye datos estructurados (Schema.org) que le dicen a Google exactamente qué tipo de negocio eres, tus horarios, productos y valoraciones. Esto puede darte un lugar destacado en resultados de búsqueda.

<Note>
  Los resultados de SEO toman entre 2 y 8 semanas en aparecer después de publicar. Google necesita tiempo para indexar páginas nuevas.
</Note>
EOF

# ============================================================
# FOOD
# ============================================================

cat > docs/food/que-es-food.mdx << 'EOF'
---
title: "¿Qué es SYNTIfood?"
description: "Menú digital para restaurantes con pedidos por WhatsApp"
---

SYNTIfood es el producto de SYNTIweb diseñado especialmente para **restaurantes, fondas, comidas rápidas y cualquier negocio de comida**.

Tu cliente escanea el QR en tu mesa o recibe el link por WhatsApp, ve tu menú completo con fotos y precios, y te hace el pedido directamente por WhatsApp — sin apps, sin cuentas, sin complicaciones.

## El problema que resuelve

La carta en papel se ensucia, se desactualiza y hay que reimprimir cada vez que cambia el precio. Con SYNTIfood actualizas el menú desde el celular en segundos y todos los clientes ven el precio correcto de inmediato.

## Cómo funciona el menú

El menú de SYNTIfood tiene una estructura simple y efectiva:

- **Categorías** con foto representativa (Entradas, Carnes, Bebidas, etc.)
- **Lista de platos** dentro de cada categoría con nombre y precio
- **Precio en REF y bolívares** actualizado con la tasa BCV del día

## Para qué tipo de negocio es ideal

- Restaurantes y fondas
- Comidas rápidas y hamburguesas
- Pizzerías
- Reposterías y panaderías
- Heladerías
- Cualquier negocio donde el cliente elige de una lista de platos

<Note>
  Si además de comida vendes productos físicos con tallas o colores, SYNTIcat puede ser más adecuado. Si tienes dudas, escríbenos por WhatsApp.
</Note>
EOF

cat > docs/food/wizard-food.mdx << 'EOF'
---
title: "Configurar tu menú"
description: "Cómo crear tu menú digital por primera vez"
---

Cuando creas tu cuenta en SYNTIfood, el asistente te guía para tener tu primer menú publicado en minutos.

## Los pasos del asistente

<Steps>
  <Step title="Nombre del restaurante">
    El nombre que verán tus clientes en la parte superior del menú. Puede ser el nombre comercial o el nombre con que te conocen.
  </Step>
  <Step title="Tipo de restaurante">
    Selecciona la categoría: restaurante, comida rápida, pizzería, repostería, etc. Esto personaliza el diseño para tu tipo de negocio.
  </Step>
  <Step title="WhatsApp de pedidos">
    El número donde van a llegar todos los pedidos. Formato: `+58 412 1234567`.
  </Step>
  <Step title="Primera categoría del menú">
    Crea tu primera categoría (por ejemplo "Platos principales") y agrega al menos un plato con nombre y precio.
  </Step>
  <Step title="Publicar">
    Tu menú queda publicado. Ya puedes compartir el link o imprimir el QR para las mesas.
  </Step>
</Steps>

## Después del asistente

Desde el panel de administración puedes:

- Agregar más categorías y platos
- Subir fotos de cada categoría
- Activar la tasa BCV automática (plan Semestral+)
- Configurar horarios de atención

<Tip>
  Empieza con las categorías más importantes. Puedes ir agregando platos poco a poco sin interrumpir el servicio.
</Tip>
EOF

cat > docs/food/categorias-menu.mdx << 'EOF'
---
title: "Categorías del menú"
description: "Cómo organizar tu menú por secciones"
---

Las categorías organizan tu menú en secciones lógicas. Cada categoría tiene una foto representativa y una lista de platos debajo.

## Crear una categoría

1. Abre el panel de administración
2. Ve a **Productos** (aquí están las categorías del menú)
3. Toca **+ Nueva categoría**
4. Escribe el nombre (ej: "Entradas", "Carnes a la plancha", "Bebidas")
5. Sube una foto representativa de esa categoría
6. Guarda

## Ejemplos de categorías por tipo de restaurante

**Restaurante completo:** Entradas · Sopas · Carnes · Pollo · Pescados · Acompañantes · Postres · Bebidas

**Comida rápida:** Hamburguesas · Perros calientes · Combos · Bebidas · Extras

**Pizzería:** Pizzas clásicas · Pizzas especiales · Pastas · Bebidas · Postres

## Límites de fotos por plan

| Plan | Fotos de categoría |
|------|-------------------|
| Básico | 6 |
| Semestral | 12 |
| Anual | 18 |

## Reordenar categorías

Mantén presionada una categoría y arrástrala al orden que prefieras. Los clientes verán las categorías en ese mismo orden.

<Tip>
  Pon las categorías más pedidas primero. En la mayoría de los restaurantes son "Platos principales" o el producto estrella del negocio.
</Tip>
EOF

cat > docs/food/items-lista.mdx << 'EOF'
---
title: "Platos y precios"
description: "Cómo agregar los platos a tu menú"
---

Los platos son los ítems dentro de cada categoría. Tienen nombre, precio y pueden activarse o desactivarse según disponibilidad.

## Agregar un plato

1. Abre el panel de administración
2. Ve a **Productos** y selecciona una categoría
3. Toca **+ Agregar plato**
4. Escribe el nombre del plato
5. Escribe el precio en REF
6. Guarda

## Límites de ítems por plan

| Plan | Ítems totales en menú |
|------|----------------------|
| Básico | 50 |
| Semestral | 100 |
| Anual | 150 |

## Activar y desactivar platos

Si un plato se agotó o no está disponible hoy, desactívalo sin borrarlo:

Panel → selecciona la categoría → toca el toggle junto al plato → desaparece del menú para los clientes.

Cuando vuelvas a tenerlo disponible, actívalo de nuevo con el mismo toggle.

## Precios en bolívares

Si tienes activada la tasa BCV (plan Semestral+), el precio en bolívares se calcula automáticamente. Tú solo manejas el precio en REF y SYNTIweb hace la conversión.

Ejemplo: Si el plato cuesta REF 8.00 y la tasa está a 36.50 Bs/REF, el cliente verá `Bs. 292.00` automáticamente.

<Warning>
  No escribas los precios en bolívares directamente. Siempre usa REF para que el sistema haga la conversión correcta con la tasa del día.
</Warning>
EOF

cat > docs/food/pedido-rapido-whatsapp.mdx << 'EOF'
---
title: "Pedido Rápido"
description: "Cómo tus clientes te hacen pedidos por WhatsApp"
---

El Pedido Rápido es la función estrella de SYNTIfood disponible en el plan Anual. Permite que tu cliente acumule varios platos y te envíe el pedido completo por WhatsApp con un solo toque.

## Cómo funciona para tu cliente

<Steps>
  <Step title="El cliente navega el menú">
    Ve las categorías, los platos y los precios en tu página.
  </Step>
  <Step title="Agrega platos al pedido">
    Toca el botón **+** junto a cada plato que quiere. Va acumulando el pedido con las cantidades.
  </Step>
  <Step title="Revisa el resumen">
    Ve el total del pedido con todos los platos seleccionados y el precio final.
  </Step>
  <Step title="Envía por WhatsApp">
    Toca **Enviar pedido** — se abre WhatsApp con el mensaje ya escrito, listo para enviar.
  </Step>
</Steps>

## Cómo llega el pedido a ti

Recibes un mensaje así en WhatsApp:

```
🍕 Pedido SYNTIfood

📋 Mi pedido:
• 2x Pizza Margarita - REF 8.00 c/u
• 1x Refresco de uva - REF 2.50
• 1x Porción de papas - REF 3.00

💰 Total: REF 21.50
   Bs. 785.25

📍 [Nombre del cliente si lo activaste]
```

## Activar Pedido Rápido

Esta función se activa automáticamente con el **plan Anual** de SYNTIfood. No necesitas configurar nada adicional.

<Note>
  En los planes Básico y Semestral, los clientes pueden escribirte directamente por WhatsApp tocando el botón de contacto, pero sin el acumulador automático de platos.
</Note>
EOF

cat > docs/food/extras-opciones.mdx << 'EOF'
---
title: "Extras y opciones"
description: "Agrega personalizaciones a los platos de tu menú"
---

Los extras y opciones permiten que tu cliente personalice su pedido. Disponible en el plan Semestral y Anual.

## Para qué sirven

- **Punto de cocción** → vuelta y vuelta, término medio, bien cocido
- **Tamaño** → pequeño, mediano, grande
- **Ingredientes extra** → queso adicional, bacon, aguacate
- **Sin ingredientes** → sin cebolla, sin picante
- **Tipo de salsa** → BBQ, mostaza, picante

## Cómo configurar extras

1. Panel de administración → **Productos**
2. Selecciona el plato al que quieres agregar extras
3. Toca **Extras y opciones**
4. Agrega las opciones con nombre y precio adicional (0 si es sin costo)
5. Guarda

## Ejemplo práctico

Para una hamburguesa puedes tener:

**Tamaño** (elige uno):
- Individual — sin costo adicional
- Doble — +REF 2.00

**Extras** (puede elegir varios):
- Queso extra — +REF 0.50
- Bacon — +REF 1.00
- Aguacate — +REF 0.75

El precio total se actualiza automáticamente según lo que elija el cliente.

<Note>
  Los extras están disponibles desde el plan Semestral. En el plan Básico los platos no tienen opciones de personalización.
</Note>
EOF

cat > docs/food/horarios-atencion.mdx << 'EOF'
---
title: "Horarios de atención"
description: "Configura cuándo está abierto tu restaurante"
---

Los horarios de atención se muestran en tu menú para que los clientes sepan cuándo pueden hacer pedidos.

## Configurar horarios

1. Panel de administración
2. Ve a **Config**
3. Busca la sección **Horarios de atención**
4. Activa los días que atiendes
5. Selecciona la hora de apertura y cierre para cada día
6. Guarda

## Estado abierto/cerrado automático

SYNTIweb muestra automáticamente si tu restaurante está **Abierto** o **Cerrado** en tiempo real según los horarios que configuraste.

Cuando está cerrado, el cliente ve un aviso con el próximo horario de apertura.

## Cambiar el estado manualmente

Si necesitas cerrar antes de lo previsto o abrir en horario especial:

Panel → **Info** → toggle **Estado del negocio** → cambia entre Abierto/Cerrado manualmente.

Este cambio manual tiene prioridad sobre el horario automático hasta que lo vuelvas a cambiar.

<Tip>
  Configura bien tus horarios desde el inicio. Un cliente que intenta pedir cuando estás cerrado y no ve aviso, probablemente busca otro restaurante.
</Tip>
EOF

# ============================================================
# CAT
# ============================================================

cat > docs/cat/que-es-cat.mdx << 'EOF'
---
title: "¿Qué es SYNTIcat?"
description: "Catálogo digital con carrito de compras y checkout por WhatsApp"
---

SYNTIcat es el producto de SYNTIweb para negocios que venden **productos físicos** y necesitan un catálogo organizado con carrito de compras.

Tu cliente navega tu catálogo, agrega lo que quiere al carrito, y te envía el pedido completo por WhatsApp. Tú recibes la orden con todos los detalles y coordinas el pago y entrega como siempre.

## ¿Para qué tipo de negocio es?

- Tiendas de ropa y calzado
- Boutiques y accesorios
- Distribuidoras de productos
- Tiendas de electrónica y tecnología
- Negocios de cosméticos y cuidado personal
- Emprendedores con catálogo de productos
- Cualquier negocio donde el cliente elige de un catálogo

## Diferencia con Studio y Food

SYNTIcat tiene carrito de compras. El cliente puede agregar varios productos, ver el total, y enviarte el pedido completo. Con Studio el cliente solo ve los productos pero no tiene carrito.

## El sistema de órdenes SC-XXXX

Cada pedido recibe un código único (ej: SC-0042). Tanto tú como el cliente tienen ese código para hacer seguimiento. Esto profesionaliza el proceso de ventas considerablemente.

<Note>
  El carrito y el sistema de órdenes están disponibles en los planes Semestral y Anual. En el plan Básico el cliente puede escribirte por WhatsApp directamente desde cada producto.
</Note>
EOF

cat > docs/cat/wizard-cat.mdx << 'EOF'
---
title: "Configurar tu catálogo"
description: "Cómo crear tu tienda en SYNTIcat por primera vez"
---

El asistente de SYNTIcat te guía para tener tu catálogo publicado en minutos.

## Los pasos del asistente

<Steps>
  <Step title="Nombre de tu tienda">
    El nombre que verán tus clientes. Puede ser tu nombre comercial o marca.
  </Step>
  <Step title="Tipo de tienda">
    Ropa, electrónica, cosméticos, etc. Esto adapta el diseño a tu negocio.
  </Step>
  <Step title="WhatsApp de pedidos">
    El número donde llegarán los pedidos. Formato: `+58 412 1234567`.
  </Step>
  <Step title="Primer producto">
    Agrega tu primer producto con nombre, precio y foto. Solo uno para comenzar.
  </Step>
  <Step title="Publicar">
    Tu catálogo queda publicado y listo para compartir.
  </Step>
</Steps>

## Después del asistente

- Agrega más productos con fotos y variantes
- Organiza por categorías
- Activa el carrito (plan Semestral+)
- Descarga el QR para tu local

<Tip>
  Empieza con tus 5 productos más vendidos. Un catálogo pequeño y bien presentado convierte mejor que uno grande y desordenado.
</Tip>
EOF

cat > docs/cat/catalogo-productos.mdx << 'EOF'
---
title: "Gestionar productos"
description: "Cómo agregar y organizar tu catálogo de productos"
---

## Agregar un producto

1. Panel de administración → **Productos**
2. Toca **+ Nuevo producto**
3. Completa los campos:
   - **Nombre** del producto
   - **Descripción** (materiales, características, etc.)
   - **Precio** en REF
   - **Foto** principal
   - **Badge** si aplica (Nuevo, Hot, Promo)
4. Guarda

## Límites por plan

| Plan | Productos |
|------|-----------|
| Básico | 20 |
| Semestral | 100 |
| Anual | Ilimitados |

## Organizar en categorías

Puedes agrupar tus productos por categorías para que el cliente navegue más fácil.

Ejemplos:
- Tienda de ropa: Camisas · Pantalones · Zapatos · Accesorios
- Electrónica: Celulares · Accesorios · Audio · Cables
- Cosméticos: Skincare · Maquillaje · Cabello · Fragancias

## Activar y desactivar productos

Si un producto está agotado, desactívalo con el toggle. Desaparece del catálogo pero queda guardado para cuando vuelva a estar disponible.

<Note>
  Agregar buenas fotos a tus productos aumenta significativamente las conversiones. Un producto sin foto recibe mucho menos atención que uno con foto.
</Note>
EOF

cat > docs/cat/carrito-whatsapp.mdx << 'EOF'
---
title: "Carrito y checkout"
description: "Cómo funciona el carrito de compras en SYNTIcat"
---

El carrito de SYNTIcat permite que tu cliente seleccione varios productos y te envíe el pedido completo por WhatsApp. Disponible en planes Semestral y Anual.

## Cómo funciona para tu cliente

<Steps>
  <Step title="Navega el catálogo">
    El cliente ve todos tus productos con fotos y precios.
  </Step>
  <Step title="Agrega al carrito">
    Toca **Agregar al carrito** en cada producto que quiere. Un drawer lateral muestra el carrito actualizado.
  </Step>
  <Step title="Selecciona variantes si aplica">
    Si el producto tiene tallas o colores, los elige antes de agregar.
  </Step>
  <Step title="Revisa el carrito">
    Ve todos los productos seleccionados, cantidades y el total.
  </Step>
  <Step title="Checkout por WhatsApp">
    Toca **Finalizar pedido** — se genera el código SC-XXXX y se abre WhatsApp con el mensaje completo listo para enviar.
  </Step>
</Steps>

## Cómo recibes el pedido

```
🛍️ Nuevo pedido SYNTIcat
📦 Orden: SC-0047

Productos:
• 1x Camisa polo azul (Talla M) - REF 15.00
• 2x Correa de cuero marrón - REF 8.00 c/u

💰 Total: REF 31.00
   Bs. 1,131.50
```

## El drawer del carrito

El carrito aparece como un panel lateral que se desliza desde la derecha. El cliente puede ver su selección en cualquier momento sin salir del catálogo.

<Tip>
  El código SC-XXXX es clave para el seguimiento. Cuando el cliente te escribe con su código, sabes exactamente qué pidió sin tener que preguntar.
</Tip>
EOF

cat > docs/cat/mini-order-sc.mdx << 'EOF'
---
title: "Sistema de órdenes SC"
description: "Cómo funciona el código de seguimiento de pedidos"
---

Cada pedido en SYNTIcat recibe un código único en formato **SC-XXXX** (ejemplo: SC-0042, SC-0043). Este código es el identificador del pedido para ti y para tu cliente.

## Para qué sirve

- **Seguimiento fácil** — cuando el cliente te pregunta por su pedido, lo buscas por el código
- **Evita confusiones** — si tienes varios pedidos similares, el código los distingue
- **Profesionalismo** — le da a tu tienda la imagen de un negocio organizado

## Cómo funciona

1. El cliente finaliza su carrito en SYNTIcat
2. El sistema genera automáticamente el próximo código disponible (SC-0001, SC-0002, etc.)
3. Ese código aparece en el mensaje de WhatsApp que el cliente te envía
4. Ambos tienen el mismo código para comunicarse

## Dónde ver las órdenes

Los pedidos se guardan como archivos JSON en el servidor, organizados por fecha. Próximamente tendrás un panel de órdenes dentro del dashboard.

<Note>
  El sistema de órdenes SC está disponible en los planes Semestral y Anual de SYNTIcat. En el plan Básico los clientes escriben directamente sin código de orden.
</Note>
EOF

cat > docs/cat/badges-productos.mdx << 'EOF'
---
title: "Badges en productos"
description: "Cómo destacar productos con etiquetas especiales"
---

Los badges son pequeñas etiquetas visuales que aparecen sobre la foto del producto para llamar la atención del cliente.

## Los 3 badges disponibles

**🆕 Nuevo** — Para productos recién llegados a tu inventario.

**🔥 Hot** — Para los productos más vendidos o populares.

**💰 Promo** — Para productos en oferta o con precio especial.

## Cómo agregar un badge

1. Panel de administración → **Productos**
2. Abre el producto al que quieres agregar el badge
3. En el campo **Badge** selecciona el que corresponde
4. Guarda

El badge aparece inmediatamente en la esquina de la foto del producto en tu catálogo.

## Cuándo usar cada uno

- **Nuevo** → cuando acabas de recibir mercancía o lanzas un producto
- **Hot** → para tus 2-3 productos más vendidos (no los pongas a todos)
- **Promo** → cuando hay descuento real o precio especial por tiempo limitado

<Warning>
  No abuses de los badges. Si todos los productos tienen badge, pierden el efecto. Úsalos solo en 2-3 productos a la vez para que realmente destaquen.
</Warning>
EOF

cat > docs/cat/variantes.mdx << 'EOF'
---
title: "Variantes de productos"
description: "Tallas, colores y opciones para tus productos"
---

Las variantes permiten que un mismo producto tenga diferentes opciones que el cliente elige antes de agregar al carrito.

## Tipos de variantes

**Talla** → S, M, L, XL, XXL (ideal para ropa y calzado)

**Color** → con selector visual de colores

**Talla y color** → combina ambas opciones

**Extras/opciones** → opciones personalizadas como materiales o características (plan Anual)

## Cómo configurar variantes

1. Panel de administración → **Productos**
2. Abre el producto
3. Ve a la sección **Variantes**
4. Selecciona el tipo: talla, color, talla+color, o extras
5. Agrega las opciones disponibles
6. Guarda

## Ejemplo práctico para ropa

Producto: **Camisa polo básica**

Variante tipo **Talla y color**:

Tallas: S · M · L · XL
Colores: Blanco · Negro · Azul marino · Rojo

Cuando el cliente quiere esa camisa, primero elige la talla y el color. En el mensaje de WhatsApp llega: "1x Camisa polo básica (Talla M - Color Azul marino)".

## Variantes sin stock

Si una talla o color específico se agotó, puedes desactivar solo esa variante sin desactivar todo el producto.

<Note>
  Las variantes básicas (talla y color) están disponibles en todos los planes. Las variantes de extras y opciones personalizadas están disponibles en el plan Anual.
</Note>
EOF

echo ""
echo "✅ Todos los archivos .mdx generados con contenido."
echo ""
echo "📦 AHORA EJECUTA:"
echo ""
echo "   git add docs/"
echo "   git commit -m \"docs: contenido completo todos los mdx\""
echo "   git push origin main"
echo ""
echo "🚀 Mintlify detectará el push y publicará todo automáticamente."
