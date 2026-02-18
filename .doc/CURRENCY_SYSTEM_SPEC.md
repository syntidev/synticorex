# 💱 SISTEMA DE MONEDA - SYNTIWEB

**Versión:** 1.0  
**Fecha:** 2026-02-17  
**Prioridad:** 🔴 CRÍTICA (Cumplimiento Legal)

---

## 🎯 CONTEXTO LEGAL Y DE NEGOCIO

### Restricciones Actuales (2026)
- ❌ **PROHIBIDO**: Mostrar precios con símbolo "$" (transición política efecto Trump)
- ✅ **PERMITIDO**: Usar "REF" o "Ref." como símbolo de referencia
- ✅ **OBLIGATORIO**: Tener capacidad de mostrar precios en Bs.
- ⚠️ **REALIDAD**: Cifras en Bs. son muy largas y pueden impactar negativamente

### Solución Adoptada
- **Por defecto**: Mostrar precios en **REF** (referencia dólar)
- **Opción flexible**: Sistema de toggle para cambiar a Bs.
- **Preparado**: Para cambiar "REF" → "$" cuando sea legal

---

## 🏗️ ARQUITECTURA DEL SISTEMA

### 1. Tabla `dollar_rates` (Global)

Almacena histórico de tasas del dólar oficial BCV.

```sql
CREATE TABLE dollar_rates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rate DECIMAL(10,4) NOT NULL,           -- Ejemplo: 396.3674
    source VARCHAR(50) NOT NULL,            -- "dolarapi"
    fetched_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Índices:**
```sql
INDEX idx_fetched_at (fetched_at DESC)
```

---

### 2. Campo `settings` en Tabla `tenants`

Cada tenant tiene configuración de moneda en su campo JSON `settings`:

```json
{
  "engine_settings": {
    "currency": {
      "main": "USD",
      "secondary": "VES",
      "exchange_rate": 396.3674,
      "source": "dolarapi",
      "auto_update": true,
      "last_update": "2026-02-17",
      
      "display": {
        "mode": "toggle",
        "default_currency": "REF",
        "show_conversion_button": true,
        "symbols": {
          "reference": "REF",
          "bolivares": "Bs."
        },
        "decimals": 2,
        "rounding": false
      }
    }
  }
}
```

---

## 📐 MODOS DE VISUALIZACIÓN

### Modo 1: `reference_only` (Solo REF)
```
┌─────────────────────────────────┐
│  Pizza Margherita               │
│  REF 12.50                      │
│  [📱 Pedir]                     │
└─────────────────────────────────┘
```

### Modo 2: `bolivares_only` (Solo Bs.)
```
┌─────────────────────────────────┐
│  Pizza Margherita               │
│  Bs. 4,954.59                   │
│  [📱 Pedir]                     │
└─────────────────────────────────┘
```

### Modo 3: `toggle` (Híbrido - RECOMENDADO)
```
┌─────────────────────────────────┐
│  Precios en: [REF] ←→ [Bs.]    │  ← Toggle global
│                                 │
│  Pizza Margherita               │
│  REF 12.50                      │
│  [📱 Pedir] [💱 Ver en Bs.]    │  ← Botón opcional
└─────────────────────────────────┘
```

---

## 🔧 API EXTERNA UTILIZADA

### DolarAPI Venezuela (Gratuita)

**Endpoint Principal:**
```
GET https://ve.dolarapi.com/v1/dolares/oficial
```

**Respuesta:**
```json
{
  "fuente": "oficial",
  "nombre": "Oficial",
  "compra": null,
  "venta": null,
  "promedio": 396.3674,
  "fechaActualizacion": "2026-02-17T21:01:09.300Z"
}
```

**Campo a usar:** `promedio`

**Endpoint de Status:**
```
GET https://ve.dolarapi.com/v1/estado
```

**Documentación:**
https://dolarapi.com/docs/venezuela/operations/get-dolar-oficial.html

---

## ⚙️ SERVICIO: DollarRateService

### Responsabilidades

1. **Consultar API** cada hora (cron job)
2. **Almacenar** tasa en tabla `dollar_rates`
3. **Propagar** a todos los tenants con `auto_update: true`
4. **Cachear** última tasa para consultas rápidas

### Métodos Principales

```php
// Obtener tasa actual (con caché de 1 hora)
getCurrentRate(): ?float

// Consultar API y almacenar
fetchAndStore(): array

// Propagar a tenants
propagateRateToTenants(float $rate): int

// Obtener histórico
getHistoricalRates(int $days = 30): array
```

---

## 🎨 FRONTEND: Lógica de Visualización

### JavaScript (motor de rendering)

```javascript
// Estado global de moneda
let currentCurrency = CLIENT_DATA.engine_settings.currency.display.default_currency;

// Función de conversión
function formatPrice(priceUSD) {
    const rate = CLIENT_DATA.engine_settings.currency.exchange_rate;
    const display = CLIENT_DATA.engine_settings.currency.display;
    
    if (currentCurrency === 'REF') {
        return `${display.symbols.reference} ${priceUSD.toFixed(2)}`;
    } else {
        const priceBS = priceUSD * rate;
        return `${display.symbols.bolivares} ${formatNumber(priceBS)}`;
    }
}

// Formatear números grandes con separadores
function formatNumber(num) {
    return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Toggle de moneda
function toggleCurrency() {
    currentCurrency = (currentCurrency === 'REF') ? 'Bs.' : 'REF';
    renderAllPrices();
}
```

---

## 📊 TABLA DE PRODUCTOS

### Estructura en BD

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    
    -- SOLO SE ALMACENA EN USD
    price_usd DECIMAL(10,2) NOT NULL,
    
    -- NO SE ALMACENA price_bs (se calcula en runtime)
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);
```

**⚠️ IMPORTANTE:** 
- Solo almacenamos `price_usd`
- `price_bs` se calcula dinámicamente: `price_usd * exchange_rate`
- Esto garantiza que siempre esté actualizado

---

## 🔄 FLUJO DE ACTUALIZACIÓN DE TASA

```
┌─────────────────────────────────────────────────────────┐
│  CRON JOB (cada hora)                                   │
│  → php artisan dollar:update                            │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│  DollarRateService::fetchAndStore()                     │
│  1. GET https://ve.dolarapi.com/v1/dolares/oficial      │
│  2. Extraer campo "promedio"                            │
│  3. Guardar en tabla dollar_rates                       │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│  propagateRateToTenants()                               │
│  1. Buscar tenants con auto_update: true                │
│  2. Actualizar settings.currency.exchange_rate          │
│  3. Actualizar settings.currency.last_update            │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│  Cache::forget('dollar_rate_current')                   │
│  → Invalidar caché para próximas consultas              │
└─────────────────────────────────────────────────────────┘
```

---

## 🚨 REGLAS DE VALIDACIÓN

### En Creación/Edición de Productos

```php
// CORRECTO: Solo validar price_usd
'price_usd' => 'required|numeric|min:0.01|max:999999.99'

// INCORRECTO: NO validar price_bs (no existe en BD)
```

### En Renderizado Frontend

```javascript
// CORRECTO: Calcular Bs. en tiempo real
const priceBS = product.price_usd * currentRate;

// INCORRECTO: NO confiar en price_bs almacenado
```

---

## 🎯 PLAN DE MIGRACIÓN A "$"

Cuando sea legal usar "$":

1. Actualizar campo en `settings`:
```json
"symbols": {
  "reference": "$",  // ← Cambiar de "REF" a "$"
  "bolivares": "Bs."
}
```

2. **NO REQUIERE cambios en código**, solo en configuración por tenant

3. Opción de migración masiva:
```bash
php artisan currency:migrate-symbol REF $
```

---

## 📋 CHECKLIST DE IMPLEMENTACIÓN

### Backend
- [ ] Migración: Agregar campo `settings` a tabla `tenants`
- [ ] Servicio: `DollarRateService.php`
- [ ] Command: `UpdateDollarRate.php`
- [ ] Controller: `DollarRateController.php`
- [ ] Cron Job: Registrar en `Kernel.php`
- [ ] Config: Agregar API URL en `config/services.php`

### Frontend
- [ ] JavaScript: Función `formatPrice()`
- [ ] JavaScript: Función `toggleCurrency()`
- [ ] Componente: Toggle switch de moneda
- [ ] Componente: Botón "Ver en Bs." por producto
- [ ] CSS: Estilos del toggle

### Testing
- [ ] Test: Consulta exitosa a API
- [ ] Test: Manejo de API caída
- [ ] Test: Propagación a tenants
- [ ] Test: Cálculo correcto de precios
- [ ] Test: Toggle de moneda en frontend

---

## 🔐 SEGURIDAD Y PERFORMANCE

### Caché
- Tasa actual: 1 hora de TTL
- Invalidación automática al actualizar

### Rate Limiting
- API externa: Sin límites conocidos
- Cron: Máximo 1 consulta por hora
- Fallback: Usar última tasa almacada si API falla

### Validación
- Validar que `promedio` sea un número positivo
- Log de todas las actualizaciones
- Alertas si tasa cambia más del 10% en una hora

---

## 📞 CONTACTO DE SOPORTE

**API utilizada:** DolarAPI Venezuela  
**Documentación:** https://dolarapi.com/docs/venezuela  
**Status:** https://ve.dolarapi.com/v1/estado

---

**Fin del documento**