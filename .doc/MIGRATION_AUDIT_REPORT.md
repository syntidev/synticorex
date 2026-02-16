# 📊 REPORTE DE AUDITORÍA DE MIGRACIONES

**Fecha:** 2025-02-15  
**Proyecto:** SyntiWeb  
**Comparación:** Schema completo vs Migraciones actuales

---

## ✅ RESULTADO: 7 MIGRACIONES GENERADAS

Se han creado las siguientes migraciones faltantes:

1. ✨ `2025_02_11_000005_create_products_table.php`
2. ✨ `2025_02_11_000006_create_services_table.php`
3. ✨ `2025_02_11_000007_create_tenant_customization_table.php`
4. ✨ `2025_02_11_000008_create_analytics_events_table.php`
5. ✨ `2025_02_11_000009_create_dollar_rates_table.php`
6. ✨ `2025_02_11_000010_create_invoices_table.php`
7. ✨ `2025_02_11_000011_create_color_palettes_table.php`

---

## ⚠️ DISCREPANCIAS DETECTADAS EN TABLAS EXISTENTES

### 1. Tabla `plans` (Estructura diferente)

**Migración actual:**
```php
- slug (32 chars)
- nombre (64 chars)
- precio (decimal)
- limites_json (JSON)
- default_template
```

**Schema completo requiere:**
```php
- name, slug (50 chars)
- price_usd
- Límites individuales: products_limit, services_limit, images_limit, color_palettes, social_networks_limit
- Features booleanos: show_dollar_rate, show_header_top, show_about_section, show_payment_methods, show_faq, show_cta_special
- analytics_level (varchar 20)
- seo_level (varchar 20)
- whatsapp_numbers (tinyint)
- whatsapp_hour_filter (boolean)
```

**❌ PROBLEMA:** La estructura actual usa JSON para límites, el schema usa columnas individuales.

---

### 2. Tabla `tenants` (Faltan muchos campos)

**Migración actual:**
```php
- id
- slug (unique)
- nombre
- plan_id (FK)
- dominio
- activo
- visits_count
- template
```

**Schema completo requiere:**
```php
- id
- user_id (FK) ❌ FALTA
- plan_id (FK) ✓ Existe

-- Identificación
- subdomain (unique) ❌ FALTA
- base_domain ❌ FALTA
- custom_domain (unique) ❌ FALTA
- domain_verified ❌ FALTA

-- Info básica
- business_name ✓ (tienes "nombre")
- business_segment ❌ FALTA
- slogan ❌ FALTA
- description ❌ FALTA

-- Contacto
- phone ❌ FALTA
- whatsapp_sales ❌ FALTA
- whatsapp_support ❌ FALTA
- email ❌ FALTA
- address ❌ FALTA
- city ❌ FALTA
- country ❌ FALTA

-- Horarios
- business_hours (JSON) ❌ FALTA
- is_open ❌ FALTA

-- Configuración
- edit_pin (hash) ❌ FALTA
- currency_display ❌ FALTA
- color_palette_id ❌ FALTA

-- SEO
- meta_title ❌ FALTA
- meta_description ❌ FALTA
- meta_keywords ❌ FALTA

-- Status
- status ✓ (tienes "activo")
- trial_ends_at ❌ FALTA
- subscription_ends_at ❌ FALTA
```

**❌ PROBLEMA CRÍTICO:** Faltan aproximadamente 20 columnas en la tabla tenants.

---

## 🔧 ACCIONES REQUERIDAS

### OPCIÓN A: Crear migración de ALTER TABLE para `tenants`

Crear una nueva migración que añada todas las columnas faltantes:

```bash
php artisan make:migration add_missing_columns_to_tenants_table
```

### OPCIÓN B: Rehacer la migración `tenants` desde cero

⚠️ **ADVERTENCIA:** Esto requeriría eliminar datos existentes.

1. Hacer backup de la BD
2. Eliminar la migración actual de tenants
3. Crear nueva migración completa según schema

---

## 🎯 RECOMENDACIÓN

**1. Para `tenants`:** Crear migración ALTER TABLE (OPCIÓN A)

**2. Para `plans`:** Evaluar si mantener estructura JSON o migrar a columnas individuales según necesidades del proyecto.

**3. Para nuevas tablas:** Las 7 migraciones generadas ya están listas para ejecutar:

```bash
php artisan migrate
```

---

## 📋 CHECKLIST DE VERIFICACIÓN

- [x] Verificar schema completo
- [x] Comparar con migraciones existentes
- [x] Generar migraciones faltantes
- [ ] Crear migración ALTER para `tenants`
- [ ] Evaluar estructura de `plans`
- [ ] Ejecutar migraciones nuevas
- [ ] Crear seeders correspondientes
- [ ] Verificar relaciones y constraints

---

## 🔗 RELACIONES VERIFICADAS

| Relación | Status | Nota |
|----------|--------|------|
| users → tenants | ⚠️ | Falta `user_id` en tenants |
| plans → tenants | ✓ | OK |
| tenants → products | ✓ | Implementado |
| tenants → services | ✓ | Implementado |
| tenants → tenant_customization | ✓ | Implementado |
| tenants → analytics_events | ✓ | Implementado |
| tenants → invoices | ✓ | Implementado |
| color_palettes → tenants | ⚠️ | Falta `color_palette_id` en tenants |

---

**Fin del reporte**
