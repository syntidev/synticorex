# ✅ MIGRACIONES GENERADAS - SYNTIWEB

**Fecha:** 2025-02-15  
**Total de migraciones creadas:** 8

---

## 📦 NUEVAS MIGRACIONES

### 1️⃣ `2025_02_11_000005_create_products_table.php`
**Tabla:** `products`  
**Descripción:** Productos del catálogo de cada tenant

**Columnas principales:**
- `tenant_id` (FK)
- `name`, `description`
- `price_usd`, `price_bs`
- `image_filename`
- `position`, `is_active`, `is_featured`
- `badge` (hot, new, promo)

---

### 2️⃣ `2025_02_11_000006_create_services_table.php`
**Tabla:** `services`  
**Descripción:** Servicios ofrecidos por cada tenant

**Columnas principales:**
- `tenant_id` (FK)
- `name`, `description`
- `icon_name`, `image_filename`, `overlay_text`
- `cta_text`, `cta_link`
- `position`, `is_active`

---

### 3️⃣ `2025_02_11_000007_create_tenant_customization_table.php`
**Tabla:** `tenant_customization`  
**Descripción:** Personalización visual y contenido adicional

**Columnas principales:**
- `tenant_id` (FK UNIQUE)
- `logo_filename`, `hero_filename`
- `social_networks` (JSON)
- `payment_methods` (JSON)
- `faq_items` (JSON) - Solo Plan VISIÓN
- `cta_title`, `cta_subtitle`, `cta_button_text`, `cta_button_link` - Solo Plan VISIÓN
- `visual_effects` (JSON) - Solo Plan VISIÓN

---

### 4️⃣ `2025_02_11_000008_create_analytics_events_table.php`
**Tabla:** `analytics_events`  
**Descripción:** Registro de eventos para analytics

**Columnas principales:**
- `tenant_id` (FK)
- `event_type` (page_view, whatsapp_click, product_click, service_click)
- `reference_type`, `reference_id` (product/service)
- `user_ip`, `user_agent`, `referer`
- `event_date`, `event_hour`

**Índices:**
- `(tenant_id, event_date)`
- `(tenant_id, event_type)`

---

### 5️⃣ `2025_02_11_000009_create_dollar_rates_table.php`
**Tabla:** `dollar_rates`  
**Descripción:** Tasas de cambio USD/BS

**Columnas principales:**
- `rate` (decimal 10,2)
- `source` (BCV, manual)
- `effective_from`, `effective_until`
- `is_active`

**Índice:** `(is_active, effective_from)`

---

### 6️⃣ `2025_02_11_000010_create_invoices_table.php`
**Tabla:** `invoices`  
**Descripción:** Facturas de suscripciones

**Columnas principales:**
- `tenant_id` (FK)
- `invoice_number` (UNIQUE) - Formato: SYNTI-2026-00001
- `amount_usd`, `currency`
- `payment_method`, `payment_reference`, `payment_date`
- `pdf_filename`
- `status` (pending, paid, cancelled)
- `period_start`, `period_end`

**Índice:** `(tenant_id, status)`

---

### 7️⃣ `2025_02_11_000011_create_color_palettes_table.php`
**Tabla:** `color_palettes`  
**Descripción:** Paletas de colores predefinidas

**Columnas principales:**
- `id` (tinyIncrements)
- `name`, `slug` (UNIQUE)
- `primary_color`, `secondary_color`, `accent_color`
- `background_color`, `text_color`
- `min_plan_id` - Disponibilidad según plan
- `category` (clasico, marca, segmento)

**Seed requerido:** Ver `03_SCHEMA_DATABASE.md` para datos iniciales

---

### 8️⃣ `2025_02_11_000012_add_missing_columns_to_tenants_table.php` ⚠️
**Tabla:** `tenants` (ALTER TABLE)  
**Descripción:** Actualiza la tabla tenants para coincidir con el schema completo

**Columnas añadidas:**
- `user_id` (FK) - Relación con users
- `subdomain`, `base_domain`, `custom_domain`, `domain_verified`
- `business_segment`, `slogan`, `description`
- `phone`, `whatsapp_sales`, `whatsapp_support`, `email`
- `address`, `city`, `country`
- `business_hours` (JSON), `is_open`
- `edit_pin`, `currency_display`, `color_palette_id`
- `meta_title`, `meta_description`, `meta_keywords`
- `status`, `trial_ends_at`, `subscription_ends_at`

**Columnas renombradas:**
- `nombre` → `business_name`

**Columnas eliminadas:**
- `slug` ❌
- `visits_count` ❌
- `template` ❌
- `activo` ❌ (reemplazado por `status`)
- `dominio` ❌ (reemplazado por `subdomain`/`custom_domain`)

---

## ⚡ EJECUCIÓN

### Para ejecutar todas las migraciones nuevas:

```bash
php artisan migrate
```

### ⚠️ ADVERTENCIA IMPORTANTE

La migración `000012_add_missing_columns_to_tenants_table.php` es **DESTRUCTIVA** porque:

1. Elimina columnas existentes: `slug`, `visits_count`, `template`, `activo`, `dominio`
2. Requiere que exista la tabla `users`
3. Requiere la columna `edit_pin` (no puede ser null)

**ANTES DE EJECUTAR:**

1. ✅ Hacer backup completo de la base de datos
2. ✅ Revisar si hay datos en producción que se perderán
3. ✅ Ejecutar primero en entorno de desarrollo/testing
4. ✅ Si hay datos existentes, migrarlos manualmente antes

---

## 📋 ORDEN DE EJECUCIÓN RECOMENDADO

```
1. users (ya existe)
2. plans (ya existe)
3. tenants (ya existe)
4. 000012_add_missing_columns_to_tenants_table ⚠️
5. 000005_create_products_table
6. 000006_create_services_table
7. 000007_create_tenant_customization_table
8. 000008_create_analytics_events_table
9. 000009_create_dollar_rates_table
10. 000010_create_invoices_table
11. 000011_create_color_palettes_table
```

Laravel ejecutará automáticamente en orden alfabético según el timestamp del nombre del archivo.

---

## 🔗 RELACIONES IMPLEMENTADAS

```
users (1) ──────── (N) tenants
                      │
                      ├─── (1) plans
                      ├─── (N) products
                      ├─── (N) services
                      ├─── (1) tenant_customization
                      ├─── (N) analytics_events
                      ├─── (N) invoices
                      └─── (1) color_palettes
```

**Todas las relaciones con `ON DELETE CASCADE` excepto `plans`**

---

## 📊 SIGUIENTE PASO: SEEDERS

Crear seeders para:

1. ✅ `PlansSeeder` - Datos de los 3 planes (OPORTUNIDAD, CRECIMIENTO, VISIÓN)
2. ✅ `ColorPalettesSeeder` - 10 paletas predefinidas
3. 🔄 `DollarRatesSeeder` - Tasa inicial del dólar

```bash
php artisan make:seeder PlansSeeder
php artisan make:seeder ColorPalettesSeeder
php artisan make:seeder DollarRatesSeeder
```

---

## ✅ VALIDACIÓN COMPLETADA

- [x] 7 tablas nuevas creadas
- [x] 1 migración ALTER para tenants
- [x] Todas las columnas del schema implementadas
- [x] Índices agregados según documentación
- [x] Foreign keys con CASCADE configurados
- [x] Tipos de datos coinciden con el schema

---

**Estado:** ✅ LISTO PARA EJECUTAR  
**Archivo de referencia:** `.doc/03_SCHEMA_DATABASE.md`  
**Reporte detallado:** `.doc/MIGRATION_AUDIT_REPORT.md`
