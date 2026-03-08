# SYNTIweb — Product Context para Generación de Documentación

> Este archivo es la fuente de verdad para Claude al generar documentación.
> NO publicar en Mintlify. Solo uso interno de agentes.

---

## PRODUCTO

**Nombre:** SYNTIweb  
**Tipo:** SaaS multitenant — landing pages para negocios venezolanos  
**URL producción:** syntiweb.com  
**Stack:** Laravel 12 · Tailwind v4 · Preline 4.1.2 · Alpine.js · MySQL · Hostinger  
**Mercado:** Venezuela 2026 — dolarizado informal, WhatsApp como canal principal

---

## LOS 3 PRODUCTOS (blueprints)

### SYNTIstudio
Página web profesional completa para cualquier negocio.

**Para quién:** Cualquier negocio local (barbería, clínica, academia, consultora, etc.)

**Features por plan:**

| Feature | Oportunidad $13/mes | Crecimiento $19/mes | Visión $25/mes |
|---------|---------------------|---------------------|----------------|
| Productos | 20 | 50 | Ilimitado |
| Servicios | 3 | 6 | 9 |
| Paletas de color | 10 | 17 | 17 + custom |
| Secciones | hero, productos, servicios, contacto, pagos, cta, footer | + about, testimonios | + faq, sucursales |
| Fotos por producto | 1 | 1 | 3 (slider) |

**Flows clave:**
1. Registro → Wizard 5 pasos → landing publicada
2. Dashboard (Alt+S / long press logo) → PIN → editar todo
3. QR sticker descargable → imprimir → cliente escanea → ve la página

---

### SYNTIfood
Menú digital para restaurantes con pedido directo por WhatsApp.

**Para quién:** Restaurantes, fondas, comidas rápidas, reposterías

**Estructura del menú:** Categoría con foto + lista de ítems con precio

**Features por plan:**

| Feature | Básico $9/mes | Semestral $39 | Anual $69 |
|---------|---------------|---------------|-----------|
| Fotos por categoría | 6 | 12 | 18 |
| Ítems en lista | 50 | 100 | 150 |
| Tasa BCV automática | ✗ | ✅ | ✅ |
| Extras/opciones | ✗ | ✅ | ✅ |
| Pedido Rápido → WhatsApp | ✗ | ✗ | ✅ |
| 2 WhatsApp (ventas+soporte) | ✗ | ✗ | ✅ |

**Flow clave Pedido Rápido:**
Cliente acumula ítems → toca "Enviar pedido" → app construye mensaje formateado → abre WhatsApp listo para enviar

---

### SYNTIcat
Catálogo de productos con carrito y checkout por WhatsApp.

**Para quién:** Tiendas, boutiques, distribuidoras, emprendedores con catálogo

**Features por plan:**

| Feature | Básico $9/mes | Semestral $39 | Anual $69 |
|---------|---------------|---------------|-----------|
| Productos | 20 | 100 | Ilimitado |
| Carrito completo | ✗ | ✅ | ✅ |
| Mini Order SC-XXXX | ✗ | ✅ | ✅ |
| Badges (nuevo/hot/promo) | ✅ | ✅ | ✅ |
| Variantes (talla/color) | ✅ | ✅ | ✅ |
| Variantes extras/opciones | ✗ | ✗ | ✅ |

**Flow clave carrito:**
Cliente navega catálogo → agrega productos → cart drawer lateral → genera código SC-XXXX → mensaje WhatsApp con orden completa

---

## SISTEMA DE MONEDA

- Símbolo: **REF** (no $ — mercado venezolano dolarizado informal)
- Modos disponibles:
  - `reference_only` → solo muestra precio en REF
  - `bolivares_only` → solo bolívares (convierte con tasa BCV)
  - `both_toggle` → toggle automático REF/Bs
  - `hidden` → sin precios visibles
- Tasa BCV se actualiza automáticamente vía API

---

## DASHBOARD (panel de administración)

- **Acceso:** Alt+S en desktop / long press 3 seg en logo desde móvil
- **Autenticación:** PIN numérico (lo define el dueño)
- **Tabs:** Info · Productos · Servicios · Diseño · Analytics · Config
- **Desde el celular:** el dueño gestiona TODO sin computadora

---

## MULTITENANCY

- Cada negocio = un subdominio: `minegocio.syntiweb.com`
- Plan Anual (Studio/Cat): puede conectar dominio propio `www.minegocio.com`
- URL del QR siempre apunta al subdominio del tenant

---

## REGLAS DE ESCRITURA PARA DOCS

- **Lector:** dueño de negocio venezolano, NO desarrollador
- **Tono:** amigable, directo, como si le explicaras a un conocido
- **Formato:** pasos numerados, acciones concretas
- **Largo:** máximo 400 palabras por archivo
- **Ejemplos:** siempre usar negocios venezolanos reales (pizzería, barbería, tienda de ropa, fonda)
- **Moneda:** escribir REF, no $
- **Sin jerga técnica:** no decir "tenant", "blueprint", "slug", "endpoint"
- **Callouts Mintlify a usar:**
  - `<Note>` → información útil extra
  - `<Warning>` → acción que puede causar pérdida de datos
  - `<Tip>` → consejo para sacarle más provecho

---

## DEMOS DEL SISTEMA (tenants de prueba)

- **techstart** → Plan Visión (Studio) — PIN: 1234
- **pizzería** → Plan Crecimiento (Studio)
- **barbería** → Plan Oportunidad (Studio)

---

## LO QUE NO EXISTE AÚN (no documentar todavía)

- Pasarela de pago (Binance/transferencia) → Fase F
- App móvil PWA → Fase F
- Bot de soporte por tenant → Fase F
- Dominio propio para Food → Fase F
- API pública → Fase F
