# 🌱 SEEDERS GENERADOS - SYNTIWEB

**Fecha:** 2025-02-15  
**Total de seeders creados:** 3

---

## 📦 SEEDERS IMPLEMENTADOS

### 1️⃣ `PlansSeeder.php`
**Tabla:** `plans`  
**Registros:** 3

#### Datos insertados:

| Plan | Slug | Precio | Productos | Servicios | Imágenes | Paletas | Analytics | SEO |
|------|------|--------|-----------|-----------|----------|---------|-----------|-----|
| **OPORTUNIDAD** | oportunidad | $49.00 | 6 | 3 | 8 | 5 | basic | basic |
| **CRECIMIENTO** | crecimiento | $89.00 | 18 | 6 | 26 | 10 | standard | improved |
| **VISIÓN** | vision | $159.00 | 40 | 15 | 57 | 20 | advanced | advanced |

#### Características por plan:

**OPORTUNIDAD:**
- ❌ Sin tasa del dólar
- ❌ Sin header top
- ❌ Sin sección "Sobre nosotros"
- ❌ Sin medios de pago
- ❌ Sin FAQ
- ❌ Sin CTA especial
- 📱 1 número de WhatsApp
- ❌ Sin filtro de horario WhatsApp
- 📱 1 red social

**CRECIMIENTO:**
- ✅ Tasa del dólar
- ✅ Header top
- ✅ Sección "Sobre nosotros"
- ✅ Medios de pago
- ❌ Sin FAQ
- ❌ Sin CTA especial
- 📱 2 números de WhatsApp
- ❌ Sin filtro de horario WhatsApp
- 📱 Todas las redes sociales

**VISIÓN:**
- ✅ Tasa del dólar
- ✅ Header top
- ✅ Sección "Sobre nosotros"
- ✅ Medios de pago
- ✅ FAQ
- ✅ CTA especial
- 📱 2 números de WhatsApp
- ✅ Filtro de horario WhatsApp
- 📱 Todas las redes sociales

---

### 2️⃣ `ColorPalettesSeeder.php`
**Tabla:** `color_palettes`  
**Registros:** 20 paletas

#### Paletas CLÁSICAS (min_plan_id: 1) - 10 paletas:

| # | Nombre | Slug | Primario | Secundario | Categoría |
|---|--------|------|----------|------------|-----------|
| 1 | Clásico Azul | clasico-azul | #0066CC | #FFFFFF | clasico |
| 2 | Calidez Naranja | calidez-naranja | #FF6600 | #FFCC00 | clasico |
| 3 | Natural Verde | natural-verde | #339933 | #663300 | clasico |
| 4 | Elegante Negro | elegante-negro | #000000 | #FFD700 | clasico |
| 5 | Fresco Celeste | fresco-celeste | #00BFFF | #FFFFFF | clasico |
| 6 | Sunset Rojo | sunset-rojo | #FF4500 | #FFD700 | clasico |
| 7 | Ocean Profundo | ocean-profundo | #003366 | #66B2FF | clasico |
| 8 | Forest Green | forest-green | #228B22 | #8FBC8F | clasico |
| 9 | Tropical Vibes | tropical-vibes | #FF6B9D | #FEC601 | clasico |
| 10 | Coral Reef | coral-reef | #FF7F50 | #40E0D0 | clasico |

#### Paletas de MARCA (min_plan_id: 2) - 10 paletas:

| # | Nombre | Slug | Primario | Secundario | Categoría |
|---|--------|------|----------|------------|-----------|
| 11 | McDonald's | mcdonalds | #DA291C | #FFC72C | marca |
| 12 | Starbucks | starbucks | #00704A | #FFFFFF | marca |
| 13 | Home Depot | home-depot | #F96302 | #FFFFFF | marca |
| 14 | Pizza Hut | pizza-hut | #EE3124 | #000000 | marca |
| 15 | Tech Blue | tech-blue | #001F3F | #00D9FF | marca |
| 16 | Royal Purple | royal-purple | #6A0DAD | #FFD700 | marca |
| 17 | Midnight Dark | midnight-dark | #1A1A1A | #00D4FF | marca |
| 18 | Mint Fresh | mint-fresh | #00C9A7 | #98FF98 | marca |
| 19 | Autumn Gold | autumn-gold | #B8860B | #DAA520 | marca |
| 20 | Cyber Neon | cyber-neon | #39FF14 | #FF10F0 | marca |

**Nota:** Las paletas 11-20 solo están disponibles desde el plan CRECIMIENTO en adelante.

---

### 3️⃣ `DollarRatesSeeder.php`
**Tabla:** `dollar_rates`  
**Registros:** 1

#### Tasa inicial:

| Tasa | Fuente | Vigente desde | Vigente hasta | Activa |
|------|--------|---------------|---------------|--------|
| 36.50 Bs | BCV | Hoy (00:00) | Indefinido | ✅ Sí |

**Descripción:** Inserta una tasa inicial del dólar a 36.50 Bs. Esta puede ser actualizada posteriormente mediante el sistema.

---

## 🚀 EJECUCIÓN

### Para ejecutar todos los seeders:

```bash
php artisan db:seed
```

### Para ejecutar seeders individuales:

```bash
# Solo planes
php artisan db:seed --class=PlansSeeder

# Solo paletas de colores
php artisan db:seed --class=ColorPalettesSeeder

# Solo tasa del dólar
php artisan db:seed --class=DollarRatesSeeder
```

### Para migrar y seedear desde cero:

```bash
php artisan migrate:fresh --seed
```

⚠️ **ADVERTENCIA:** `migrate:fresh` eliminará TODOS los datos existentes.

---

## 🔄 ORDEN DE EJECUCIÓN

El `DatabaseSeeder.php` ejecuta los seeders en este orden:

1. **PlansSeeder** → Primero (requerido por tenants)
2. **ColorPalettesSeeder** → Segundo (requerido por tenants)
3. **DollarRatesSeeder** → Tercero (necesario para conversión de precios)
4. **Usuario de prueba** → Último (opcional, para testing)

---

## 📊 ESTADÍSTICAS DE DATOS

| Tabla | Registros | Tamaño aproximado |
|-------|-----------|-------------------|
| plans | 3 | ~1 KB |
| color_palettes | 20 | ~2 KB |
| dollar_rates | 1 | ~0.5 KB |
| users | 1 | ~0.5 KB (test) |
| **TOTAL** | **25** | **~4 KB** |

---

## 🎨 ESTRUCTURA DE DATOS JSON

### Paletas de colores - Formato:

```json
{
  "id": 1,
  "name": "Clásico Azul",
  "slug": "clasico-azul",
  "primary_color": "#0066CC",
  "secondary_color": "#FFFFFF",
  "accent_color": null,
  "background_color": "#FFFFFF",
  "text_color": "#000000",
  "min_plan_id": 1,
  "category": "clasico"
}
```

### Planes - Features booleanos:

```json
{
  "show_dollar_rate": true,
  "show_header_top": true,
  "show_about_section": true,
  "show_payment_methods": true,
  "show_faq": false,
  "show_cta_special": false
}
```

---

## ✅ VALIDACIÓN

Después de ejecutar los seeders, verificar:

```sql
-- Verificar planes
SELECT id, name, slug, price_usd FROM plans;

-- Verificar paletas (primeras 5)
SELECT id, name, slug, primary_color, min_plan_id FROM color_palettes LIMIT 5;

-- Verificar tasa activa
SELECT rate, source, is_active FROM dollar_rates WHERE is_active = 1;

-- Contar registros totales
SELECT 
    (SELECT COUNT(*) FROM plans) as plans,
    (SELECT COUNT(*) FROM color_palettes) as palettes,
    (SELECT COUNT(*) FROM dollar_rates) as rates;
```

**Resultado esperado:**
```
plans: 3
palettes: 20
rates: 1
```

---

## 🔧 ACTUALIZACIÓN DEL DatabaseSeeder

El archivo `DatabaseSeeder.php` ha sido actualizado para incluir:

```php
public function run(): void
{
    // Seed de datos esenciales del sistema
    $this->call([
        PlansSeeder::class,
        ColorPalettesSeeder::class,
        DollarRatesSeeder::class,
    ]);

    // Usuario de prueba (comentar en producción)
    User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@syntiweb.com',
    ]);
}
```

---

## 📝 NOTAS ADICIONALES

### Actualizar la tasa del dólar:

```php
// Desactivar tasa anterior
DB::table('dollar_rates')
    ->where('is_active', true)
    ->update([
        'is_active' => false,
        'effective_until' => now(),
    ]);

// Insertar nueva tasa
DB::table('dollar_rates')->insert([
    'rate' => 38.50,
    'source' => 'BCV',
    'effective_from' => now(),
    'is_active' => true,
    'created_at' => now(),
]);
```

### Agregar más paletas:

Editar `ColorPalettesSeeder.php` y agregar al array `$palettes`:

```php
[
    'name' => 'Tu Paleta',
    'slug' => 'tu-paleta',
    'primary_color' => '#XXXXXX',
    'secondary_color' => '#YYYYYY',
    'accent_color' => null,
    'background_color' => '#FFFFFF',
    'text_color' => '#000000',
    'min_plan_id' => 1,
    'category' => 'clasico',
    'created_at' => now(),
]
```

---

## 🎯 PRÓXIMOS PASOS

- [ ] Ejecutar migraciones: `php artisan migrate`
- [ ] Ejecutar seeders: `php artisan db:seed`
- [ ] Verificar datos insertados
- [ ] Crear modelos Eloquent para cada tabla
- [ ] Crear relaciones en los modelos
- [ ] Crear factories para testing

---

## ✅ CHECKLIST COMPLETO

- [x] PlansSeeder creado
- [x] ColorPalettesSeeder creado (20 paletas)
- [x] DollarRatesSeeder creado
- [x] DatabaseSeeder actualizado
- [x] Datos según schema completo
- [x] Usuario de prueba incluido
- [x] Documentación completa

---

**Estado:** ✅ LISTO PARA EJECUTAR  
**Archivos creados:**
- `database/seeders/PlansSeeder.php`
- `database/seeders/ColorPalettesSeeder.php`
- `database/seeders/DollarRatesSeeder.php`
- `database/seeders/DatabaseSeeder.php` (actualizado)

**Comando para ejecutar:**
```bash
php artisan db:seed
```
