# SYNTIweb — Plan de Implementación: Sistema QR + Analytics + Campañas

**Versión:** 1.0  
**Fecha:** Febrero 2026  
**Autor:** Product Architect SYNTIweb  
**Contexto:** Documento de implementación técnica para desarrollo en VS Code con Claude (Sonnet 4.5 / Opus 4.5 / Haiku 4.5 + Vibe Coding)

---

## 1. CONTEXTO Y DECISIONES TOMADAS

### 1.1 El QR es Open Source — Sin costo
- El estándar QR fue creado por Denso Wave (Japón, 1994) y nunca se cobraron royalties
- Librería recomendada: `qrcode.js` o `qrcode-generator` — ambas open source, sin límites
- Flowbite QR (`https://flowbite.com/docs/components/qr-code/`) es solo el componente visual CSS — no genera QRs por sí solo, usa estas librerías por debajo
- **Decisión:** No depender de servicios de pago externos para generación de QR

### 1.2 Arquitectura elegida — URL Shortener Propio
Se descartó el QR directo al subdominio en favor de una capa de redirección propia:

```
QR impreso → go.syntiweb.com/abc123 → destino actual del tenant
```

**Razones:**
- El QR físico nunca "muere" aunque el cliente cambie de plan, dominio o URL
- Captura de analytics centralizada ANTES de redirigir
- Compatible con dominios propios del cliente sin reimprimir
- Control total de la infraestructura
- Es el corazón de la promesa comercial: "Tu QR nunca queda viejo"

---

## 2. ESTRUCTURA DE BASE DE DATOS

### 2.1 Tabla: `tenants`
```sql
tenants
  id                  -- PK
  slug                -- "pizza-la-favorita" (subdominio)
  custom_domain       -- "pizzalafavorita.com" (nullable)
  plan                -- 'basic' | 'professional' | 'menu' | 'enterprise'
  status              -- 'active' | 'suspended' | 'cancelled'
  created_at
  updated_at
```

### 2.2 Tabla: `short_links`
```sql
short_links
  id
  tenant_id           -- FK → tenants
  code                -- "abc123" (único, 6 chars alfanuméricos)
  destination_url     -- URL destino actual (cambiable sin tocar el QR)
  link_type           -- 'qr' | 'page' | 'footer' | 'campaign'
  is_active           -- boolean
  created_at
  updated_at
```

**Nota:** Cada tenant tiene UN `short_link` principal para su QR físico. El `destination_url` apunta al subdominio o dominio propio — completamente cambiable.

**Generación del code:** `Str::random(6)` de Laravel. 6 caracteres alfanuméricos = ~56 millones de combinaciones únicas.

### 2.3 Tabla: `link_hits`
```sql
link_hits
  id
  short_link_id       -- FK → short_links
  tenant_id           -- FK → tenants (desnormalizado para queries rápidas)
  campaign_id         -- FK → campaigns (nullable, si viene con UTM)
  clicked_at
  ip_hash             -- IP hasheada (SHA256), nunca raw — privacidad
  user_agent
  referer             -- URL completa de origen
  source_domain       -- dominio extraído del referer
  device_type         -- 'mobile' | 'desktop' | 'tablet'
  country_code        -- ISO 3166 (via GeoIP)
```

**`source_domain` es clave:** Si alguien ve el footer de `pizza.aqui.menu` con "Powered by SYNTIweb" y hace click → sabes qué cliente de tu red te generó ese lead. Funnel de adquisición orgánica integrado.

### 2.4 Tabla: `campaigns`
```sql
campaigns
  id
  tenant_id           -- nullable si es campaña propia de SYNTIweb
  name                -- "Promo Febrero Instagram"
  utm_source          -- "instagram" | "google" | "whatsapp"
  utm_medium          -- "paid" | "organic" | "referral"
  utm_campaign        -- "lanzamiento-2026"
  budget              -- decimal, opcional (para calcular ROI)
  starts_at
  ends_at
  created_at
```

---

## 3. LÓGICA DE REDIRECCIÓN

### 3.1 Ruta principal
```
GET go.syntiweb.com/{code}
```

### 3.2 Flujo
1. Recibir request con `code`
2. Buscar `short_link` activo por `code`
3. Si no existe → 404 o redirect a syntiweb.com
4. Registrar hit en `link_hits` (async, no bloquea la redirección)
5. Detectar UTM params → asignar `campaign_id` si aplica
6. Extraer `source_domain` del header `Referer`
7. Detectar `device_type` del `User-Agent`
8. Redirect 302 → `destination_url`

### 3.3 Ejemplo Laravel
```php
Route::get('/{code}', function($code) {
    $link = ShortLink::where('code', $code)->where('is_active', true)->firstOrFail();
    
    // Registrar hit de forma asíncrona
    dispatch(new RecordLinkHit($link, request()));
    
    return redirect($link->destination_url, 302);
})->domain('go.syntiweb.com');
```

---

## 4. SISTEMA DE SUBDOMINIOS MÚLTIPLES

SYNTIweb maneja varios tipos de subdominio según el tipo de negocio:

| Tipo | Dominio | Ejemplo |
|------|---------|---------|
| Comercios locales | `negocio.punto.vip` | `barberia.punto.vip` |
| Servicios técnicos | `negocio.oficio.vip` | `plomeria.oficio.vip` |
| Restaurantes | `negocio.aqui.menu` | `pizza.aqui.menu` |
| Dominio propio | `negocio.com` | `pizzalafavorita.com` |

**El QR siempre apunta a `go.syntiweb.com/abc123`** — el destino final es transparente para el usuario y completamente controlado por el sistema.

---

## 5. DOMINIO PROPIO DEL CLIENTE

Cuando un cliente del plan Empresarial trae su propio `.com`:

1. El cliente apunta su DNS a los servidores de SYNTIweb (CNAME o A record)
2. El sistema detecta el `custom_domain` en la tabla `tenants`
3. El `destination_url` del `short_link` se actualiza al dominio propio
4. El QR ya impreso sigue funcionando sin cambios
5. Los hits se registran igual — el `source_domain` puede ser el dominio propio o el subdominio anterior

**Importante:** La URL corta `go.syntiweb.com/abc123` sigue siendo la que va impresa en el QR físico, independientemente del dominio del cliente.

---

## 6. EL RADAR — DASHBOARD DE MÉTRICAS

### 6.1 Métricas para el cliente (su dashboard)
- Visitas del día / semana / mes
- Clicks al botón WhatsApp
- Escaneos del QR
- Productos más vistos
- Horarios de mayor tráfico
- De qué ciudad / país llegan
- Dispositivo (móvil vs desktop)

### 6.2 Métricas para SYNTIweb (dashboard maestro)
- Qué cliente genera más tráfico hacia syntiweb.com (referidos orgánicos vía footer)
- Qué campaña de Ads trajo más leads
- Costo por visita (si se registra el `budget` de la campaña)
- Conversión estimada: visitante → click WhatsApp → (asumido interés)
- Top clientes por engagement
- Tráfico total del ecosistema

### 6.3 Separación de fuentes en `link_type`
- `qr` — viene del QR físico escaneado
- `page` — acceso directo a la URL del negocio
- `footer` — click en "Powered by SYNTIweb" desde página de cliente
- `campaign` — viene de un enlace de campaña con UTM

---

## 7. GENERACIÓN DEL QR EN EL FRONTEND

### 7.1 Librería recomendada
```bash
npm install qrcode
# o via CDN
# https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js
```

### 7.2 Implementación básica
```javascript
// El QR siempre apunta al shortlink, nunca al destino final
const qrUrl = `https://go.syntiweb.com/${tenant.short_link.code}`;

QRCode.toCanvas(document.getElementById('qr-canvas'), qrUrl, {
    width: 300,
    margin: 2,
    color: {
        dark: '#07101F',   // Navy del sistema
        light: '#FFFFFF'
    }
});
```

### 7.3 Descarga del QR para impresión
```javascript
QRCode.toDataURL(qrUrl, { width: 1200, margin: 2 }, (err, url) => {
    const link = document.createElement('a');
    link.download = `qr-${tenant.slug}.png`;
    link.href = url;
    link.click();
});
```

---

## 8. TAREAS DE IMPLEMENTACIÓN

### TAREA QR-001 — Tabla short_links + migración
- [ ] Crear migración `create_short_links_table`
- [ ] Crear modelo `ShortLink` con relación a `Tenant`
- [ ] Generar código único al crear tenant (observer o evento)
- [ ] Seed de datos de prueba

### TAREA QR-002 — Ruta de redirección
- [ ] Configurar subdominio `go.syntiweb.com` en servidor
- [ ] Crear route con wildcard de subdominio en Laravel
- [ ] Implementar redirect 302 con fallback a 404
- [ ] Test de redirección básica

### TAREA QR-003 — Registro de hits (async)
- [ ] Crear migración `create_link_hits_table`
- [ ] Crear Job `RecordLinkHit` (queue)
- [ ] Parser de User-Agent para `device_type`
- [ ] Parser de Referer para `source_domain`
- [ ] Hash de IP (SHA256 sin salt fijo — no reversible)
- [ ] Test de registro de hits

### TAREA QR-004 — Sistema de campañas
- [ ] Crear migración `create_campaigns_table`
- [ ] Middleware para detectar UTM params en request
- [ ] Asignar `campaign_id` al hit si UTM coincide
- [ ] CRUD básico de campañas en dashboard admin

### TAREA QR-005 — Generación del QR en frontend
- [ ] Instalar `qrcode` o integrar via CDN
- [ ] Componente de visualización del QR en dashboard del cliente
- [ ] Botón de descarga en alta resolución (1200px para impresión)
- [ ] Preview con el estilo de SYNTIweb

### TAREA QR-006 — El Radar (dashboard métricas cliente)
- [ ] Query de hits del día / semana / mes por tenant
- [ ] Top productos vistos (requiere tracking de página de producto)
- [ ] Hits por `link_type` (QR vs directo vs footer)
- [ ] Hits por `device_type`
- [ ] Visualización en el panel flotante (Alt+S) del demo

### TAREA QR-007 — Dashboard maestro SYNTIweb
- [ ] Vista de tráfico total del ecosistema
- [ ] Clientes que más tráfico generan hacia SYNTIweb (footer referrals)
- [ ] Performance por campaña (hits, costo, ROI estimado)
- [ ] Exportación CSV de métricas

### TAREA QR-008 — Soporte de dominio propio
- [ ] Campo `custom_domain` en tabla `tenants`
- [ ] Wildcard DNS / certificado SSL automático (Let's Encrypt)
- [ ] Detección de dominio propio en middleware de routing
- [ ] Update automático de `destination_url` al activar dominio propio

---

## 9. CONSIDERACIONES DE SEGURIDAD

- **IP nunca en raw** — siempre hashear antes de guardar
- **Rate limiting** en la ruta de redirección — prevenir abuso
- **Validar `destination_url`** — solo permitir dominios del ecosistema SYNTIweb
- **Códigos únicos** — verificar colisión antes de guardar (retry si existe)
- **HTTPS obligatorio** en `go.syntiweb.com`

---

## 10. NOTAS DEL PRODUCT ARCHITECT

- El sistema de shortlinks es la infraestructura central que habilita: QR dinámico, analytics, campañas y dominio propio — todo en una sola tabla
- El "Radar" que se vende al cliente es la misma infraestructura del dashboard maestro de SYNTIweb, solo filtrada por tenant
- Prioridad de implementación: QR-001 → QR-002 → QR-003 → QR-005 (MVP funcional) → resto en iteraciones
- El panel flotante (Alt+S) de la landing demo ya tiene el diseño visual del Radar listo para conectar con datos reales

---

*Documento generado en sesión de arquitectura — exportar como referencia para contexto de desarrollo en VS Code.*
