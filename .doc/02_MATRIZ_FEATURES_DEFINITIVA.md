# 📊 MATRIZ DEFINITIVA DE FEATURES - SYNTIWEB

**Versión:** 1.0 Final  
**Fecha:** Febrero 2026  
**Planes:** OPORTUNIDAD ($49) | CRECIMIENTO ($89) | VISIÓN ($159)

---

## 🎯 TABLA MAESTRA DE CARACTERÍSTICAS

| Feature / Sección | OPORTUNIDAD ($99) | CRECIMIENTO ($149) | VISIÓN ($199) |
|-------------------|------------------|-------------------|----------------|
| **PRODUCTOS**     | 6                | 12                | 18             |
| **SERVICIOS**     | 3                | 6                 | 9              |
| **IMÁGENES**      | 6 (1 por producto) | 12 (1 por producto) | 54 (3 por producto) |
| **PALETAS/TEMAS** | 5 básicas        | 10 (básicas+marca) | 20 (todas)     |
| **TASA DÓLAR**    | No               | Sí (widget)        | Sí (widget+histórico) |
| **WHATSAPP**      | 1 número         | 2 números          | 2 + filtro horario |
| **ANALYTICS**     | Básico           | Media              | Avanzado       |
| **SECCIONES EXTRA** | No             | Header Top, Acerca, Medios Pago | FAQ, CTA especial, Sucursales |
| **SEO**           | Básico           | Media              | Avanzado       |
| **REDES SOCIALES**| 1                | Todas              | Todas          |
| **MEDIOS DE PAGO**| Pago Móvil, Biopago | Todos configurables | Todos + por sucursal |
| **WHITE LABEL**   | No               | No                 | Opcional (add-on) |

---

## 📐 SECCIONES DEL TEMPLATE ÚNICO

### ✅ SECCIONES OBLIGATORIAS (Todos los planes):
1. **Nav** (Header principal)
2. **Hero** (Banner principal con imagen)
3. **Servicios** (Grid de especialidades)
4. **Productos** (Catálogo con precios)
5. **Footer** (Info legal + redes + WhatsApp)

### ⚡ SECCIONES OPCIONALES POR PLAN:

#### Plan OPORTUNIDAD:
- Solo las 5 secciones básicas

#### Plan CRECIMIENTO (+3 secciones):
- **Header Top** (Horarios/Delivery)
- **Acerca de** (Historia del negocio)
- **Medios de Pago** (Iconos informativos)

#### Plan VISIÓN (+5 secciones):
- Header Top
- Acerca de
- Medios de Pago
- **FAQ** (5 preguntas frecuentes)
- **CTA Especial** (Sección de conversión optimizada)

---

## 🖼️ SISTEMA DE IMÁGENES

### Naming Convention:
```
/storage/tenants/{tenant_id}/
  ├── logo.webp
  ├── hero.webp
  ├── service_01.webp ... service_15.webp
  └── product_01.webp ... product_40.webp
```

### Reglas de Procesamiento:
1. **Peso máximo upload:** 2MB
2. **Redimensionamiento:** Max 800px de ancho (alto proporcional)
3. **Formato final:** WebP (compresión optimizada)
4. **Reemplazo:** Al subir nueva imagen, elimina la anterior del mismo slot

### Límites por Plan:
| Plan | Logo | Hero | Servicios | Productos | Total |
|------|------|------|-----------|-----------|-------|
| OPORTUNIDAD | 1 | 1 | 0 (solo iconos) | 6 | 8 |
| CRECIMIENTO | 1 | 1 | 6 | 18 | 26 |
| VISIÓN | 1 | 1 | 15 | 40 | 57 |

---

## 💰 SISTEMA DE PRECIOS Y CONVERSIÓN

### Fuente de Tasa:
- **API:** BCV (Banco Central de Venezuela)
- **Backup:** Manual override desde core admin
- **Update:** Cada hora (cron job)
- **Fallback:** Última tasa conocida si API falla

### Display Options (Usuario elige):
1. **Solo Dólares** ($)
2. **Solo Bolívares** (Bs.)
3. **Ambos** ($ XX.XX / Bs. XXX.XX)

### Redondeo:
- Dólares: 2 decimales
- Bolívares: 2 decimales

### Visibilidad de Tasa:
- **OPORTUNIDAD:** No visible
- **CRECIMIENTO:** Sí (widget en footer o header top)
- **VISIÓN:** Sí + histórico de últimos 7 días

---

## 📞 WHATSAPP CONFIGURATION

### Plan OPORTUNIDAD:
- **Cantidad:** 1 número
- **Ubicación:** Botón "Más info" en cada producto
- **Mensaje automático:** "Hola, me interesa [NOMBRE_PRODUCTO]"

### Plan CRECIMIENTO:
- **Cantidad:** Hasta 2 números
- **Tipos:** Ventas + Soporte
- **Ubicación:** Footer (junto a dirección)
- **Mensajes:** Diferenciados por tipo

### Plan VISIÓN:
- **Cantidad:** 2 números
- **Filtro horario:** Sí
- **Lógica:** Si fuera de horario → Mensaje "Estamos cerrados. Horario: [X]"
- **Ejemplo:** Usuario hace click a las 3am domingo, sistema detecta que horario es L-S 9am-10pm → Muestra aviso

---

## 🎨 PALETAS DE COLOR

### Plan OPORTUNIDAD (5 paletas):
1. **Clásico:** Azul + Blanco
2. **Calidez:** Naranja + Amarillo
3. **Natural:** Verde + Marrón
4. **Elegante:** Negro + Dorado
5. **Fresco:** Celeste + Blanco

### Plan CRECIMIENTO (10 paletas):
- Las 5 anteriores +
6. **McDonald's:** Rojo + Amarillo
7. **Starbucks:** Verde + Blanco
8. **Home Depot:** Naranja + Blanco
9. **Pizza Hut:** Rojo + Negro
10. **Tech:** Azul oscuro + Cyan

### Plan VISIÓN (20 paletas + efectos):
- Las 10 anteriores +
11-20. **Segmentos específicos** (Salud, Finanzas, Fitness, etc.)
- **Efectos adicionales:**
  - Gradientes
  - Sombras personalizadas
  - Animaciones de hover

---

## 📊 ANALYTICS ("Pulso de Venta")

### Plan OPORTUNIDAD:
- **Clicks totales del mes** (número simple)
- Actualización: Cada 24h

### Plan CRECIMIENTO:
- **Visitas diarias** (hoy)
- **Visitas mensuales** (mes actual)
- **Clicks en WhatsApp** (desglosado)
- Actualización: Tiempo real

### Plan VISIÓN:
- Todo lo anterior +
- **Top 3 productos más clickeados** (con porcentaje)
- **Horarios de mayor tráfico** (gráfica simple)
- **Histórico 30 días** (línea de tendencia)
- Actualización: Tiempo real

---

## 🔍 SEO POR PLAN

### Plan OPORTUNIDAD (Auto-generado básico):
```html
<title>[Nombre Negocio] - [Ciudad]</title>
<meta name="description" content="[Nombre Negocio] en [Ciudad]. Contáctanos al [WhatsApp]">
```

### Plan CRECIMIENTO (Por segmento):
Sistema detecta segmento (Restaurante/Barbería/Plomero/etc) y genera:
```html
<title>[Nombre] - [Segmento] en [Ciudad] | SYNTIweb</title>
<meta name="description" content="[Segmento especializado]. [Servicios principales]. Abierto [Horario]">
<meta name="keywords" content="[segmento], [ciudad], [servicios]">
```

**Segmentos disponibles:**
1. Restaurante / Comida
2. Barbería / Belleza
3. Servicios Técnicos (Plomería, Electricidad)
4. Retail / Tienda
5. Salud / Fitness
6. Educación
7. Automotriz
8. Hogar / Decoración

### Plan VISIÓN (Profundo):
- Todo lo anterior +
- **Schema.org Product** (para cada producto)
- **LocalBusiness** (ubicación + horarios)
- **Breadcrumbs** (navegación)
- **FAQ Schema** (si tiene FAQ)

---

## 🌐 REDES SOCIALES

### Plan OPORTUNIDAD:
- Elige **1 red social**
- Opciones: Instagram, Facebook, TikTok, LinkedIn

### Plan CRECIMIENTO y VISIÓN:
- **Todas las redes populares:**
  - Instagram
  - Facebook
  - TikTok
  - LinkedIn
  - YouTube
  - Twitter/X
  - WhatsApp Business

- **Formato:** Símbolo + Usuario
- **Ejemplo:** `@joseburguer` (Instagram)

---

## 💳 MEDIOS DE PAGO (Iconos Informativos)

### Plan OPORTUNIDAD:
- No tiene sección de medios de pago

### Plan CRECIMIENTO y VISIÓN:
**Iconos disponibles:**
1. Zelle
2. Cashea
3. Pago Móvil
4. BinancePay
5. Biopago
6. Punto de Venta (POS)
7. PayPal
8. Transferencia Bancaria
9. Efectivo

**Funcionalidad:** Solo informativos (no son pasarelas de pago)  
**Activación:** Usuario marca checks de cuáles acepta

---

## 🎛️ DASHBOARD (Panel Flotante)

### Activación:
- **Desktop:** `Alt + S`
- **Móvil:** Long press (3 seg) en logo SYNTIweb

### Autenticación:
- **PIN:** 4 dígitos (generado por sistema en primer login)
- **Recuperación:** Vía email

### Estructura (Tabs):
1. **Info** - Teléfono, dirección, horarios, status abierto/cerrado
2. **Productos** - CRUD completo (nombre, precio, imagen, descripción)
3. **Servicios** - CRUD completo
4. **Diseño** - Selector de paleta, logo, hero, efectos
5. **Analytics** - Visualización de métricas (según plan)
6. **Config** - PIN, redes sociales, medios de pago, segmento

---

## 🏗️ ARQUITECTURA MULTITENANT

### Identificación:
1. **Subdominio:** `joseburguer.menu.vip`
2. **Dominio personalizado:** `www.joseburguer.com`

### Storage:
```
storage/app/private/tenants/
  ├── {tenant_id}/
  │   ├── config.json
  │   ├── images/
  │   │   ├── logo.webp
  │   │   ├── hero.webp
  │   │   ├── service_01.webp
  │   │   └── product_01.webp
  │   └── analytics.json
```

### Custom Domain:
- **Setup:** Manual (usuario configura CNAME)
- **Verificación:** Manual por admin
- **SSL:** Let's Encrypt wildcard

---

## ✅ CRITERIOS DE VALIDACIÓN

### Cada plan debe cumplir:
1. ✅ Carga < 2 segundos en 3G
2. ✅ Mobile-first responsive
3. ✅ Lighthouse Performance > 90
4. ✅ SEO score > 85
5. ✅ Accesibilidad básica (WCAG 2.0 AA)

---

## 🚀 NOTAS DE IMPLEMENTACIÓN

### Flags en Database (tenants table):
```sql
plan_id: 1 (OPORTUNIDAD) | 2 (CRECIMIENTO) | 3 (VISIÓN)
features_json: {
  "products_limit": 6,
  "services_limit": 3,
  "show_dollar_rate": false,
  "analytics_level": "basic" (OPORTUNIDAD)
  "analytics_level": "medium" (CRECIMIENTO)
  "analytics_level": "advanced" (VISIÓN),
  "seo_level": "auto",
  ...
}
```

### Renderizado Condicional (Blade):
```php
@if($tenant->plan_id >= 2)
  @include('sections.header-top')
@endif

@if($tenant->hasFeature('medios_pago'))
  @include('sections.payment-methods')
@endif
```

---

**FIN DEL DOCUMENTO**  
Última actualización: 2026-02-15
