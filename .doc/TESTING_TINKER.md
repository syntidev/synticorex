# 🧪 TESTING DE MODELOS EN TINKER - SYNTIWEB

**Fecha:** 2025-02-15  
**Estado:** Modelos creados, listos para probar

---

## ✅ MODELOS CREADOS (10/10)

### Actualizados:
1. ✅ `User.php` - Con relación `tenants()`
2. ✅ `Plan.php` - Con columnas del schema completo
3. ✅ `Tenant.php` - Con 8 relaciones completas

### Nuevos:
4. ✅ `Product.php`
5. ✅ `Service.php`
6. ✅ `TenantCustomization.php`
7. ✅ `AnalyticsEvent.php`
8. ✅ `DollarRate.php`
9. ✅ `Invoice.php`
10. ✅ `ColorPalette.php`

---

## 🚀 PASO 1: INICIAR BASE DE DATOS

### Opción A: Laragon (Windows)
```
1. Abrir Laragon
2. Click en "Start All"
3. Verificar que MySQL esté corriendo (ícono verde)
```

### Opción B: Línea de comandos
```bash
# Verificar si MySQL está corriendo
mysql -u root -p

# Si no está corriendo, iniciar servicio
# Windows:
net start mysql

# Linux/Mac:
sudo service mysql start
```

---

## 🔧 PASO 2: EJECUTAR MIGRACIONES Y SEEDERS

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ejecutar migraciones pendientes
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# O todo junto (⚠️ DESTRUCTIVO - elimina datos):
php artisan migrate:fresh --seed
```

**Resultado esperado:**
```
✓ Plans: 3 registros
✓ ColorPalettes: 20 registros
✓ DollarRates: 1 registro
✓ Users: 1 registro (test)
```

---

## 🧪 PASO 3: TESTING EN TINKER

### Iniciar Tinker:
```bash
php artisan tinker
```

---

## 📋 TESTS A REALIZAR

### TEST 1: Verificar que existen datos

```php
// Contar registros
\App\Models\Plan::count();              // Debe retornar 3
\App\Models\ColorPalette::count();      // Debe retornar 20
\App\Models\DollarRate::count();        // Debe retornar 1
\App\Models\User::count();              // Debe retornar 1
```

**✅ Resultado esperado:**
```
=> 3
=> 20
=> 1
=> 1
```

---

### TEST 2: Crear un Tenant de prueba

```php
// Obtener user y plan
$user = \App\Models\User::first();
$plan = \App\Models\Plan::where('slug', 'oportunidad')->first();
$palette = \App\Models\ColorPalette::first();

// Crear tenant
$tenant = \App\Models\Tenant::create([
    'user_id' => $user->id,
    'plan_id' => $plan->id,
    'subdomain' => 'testburguer',
    'base_domain' => 'menu.vip',
    'business_name' => 'Test Burguer',
    'business_segment' => 'restaurante',
    'country' => 'Venezuela',
    'edit_pin' => bcrypt('1234'),
    'currency_display' => 'both',
    'color_palette_id' => $palette->id,
    'status' => 'active',
    'business_hours' => [
        'monday' => '9:00-18:00',
        'tuesday' => '9:00-18:00',
        'wednesday' => '9:00-18:00',
    ],
    'is_open' => true,
]);
```

**✅ Resultado esperado:**
```
=> App\Models\Tenant {#...}
```

---

### TEST 3: Probar relaciones belongsTo

```php
// Obtener el tenant
$tenant = \App\Models\Tenant::first();

// Probar relación con User
$tenant->user;
// ✅ Debe retornar: App\Models\User {#...}

// Probar relación con Plan
$tenant->plan;
// ✅ Debe retornar: App\Models\Plan {#...}

// Verificar datos del plan
$tenant->plan->name;
// ✅ Debe retornar: "OPORTUNIDAD"

$tenant->plan->price_usd;
// ✅ Debe retornar: "49.00"

// Probar relación con ColorPalette
$tenant->colorPalette;
// ✅ Debe retornar: App\Models\ColorPalette {#...}

$tenant->colorPalette->name;
// ✅ Debe retornar: "Clásico Azul" (o la primera paleta)
```

---

### TEST 4: Probar relaciones hasMany

```php
// Obtener el tenant
$tenant = \App\Models\Tenant::first();

// Probar relación con productos (debe estar vacía)
$tenant->products;
// ✅ Debe retornar: Illuminate\Database\Eloquent\Collection {#... all: []}

$tenant->products->count();
// ✅ Debe retornar: 0

// Probar relación con servicios (debe estar vacía)
$tenant->services;
// ✅ Debe retornar: Illuminate\Database\Eloquent\Collection {#... all: []}
```

---

### TEST 5: Crear un Producto y verificar relación

```php
// Crear producto
$product = \App\Models\Product::create([
    'tenant_id' => $tenant->id,
    'name' => 'Hamburguesa Clásica',
    'description' => 'Carne, lechuga, tomate',
    'price_usd' => 5.50,
    'price_bs' => 200.75,
    'position' => 1,
    'is_active' => true,
    'is_featured' => true,
    'badge' => 'hot',
]);

// Verificar que se creó
$product->name;
// ✅ Debe retornar: "Hamburguesa Clásica"

// Verificar relación inversa
$product->tenant->business_name;
// ✅ Debe retornar: "Test Burguer"

// Refrescar tenant y verificar que ahora tiene productos
$tenant->refresh();
$tenant->products->count();
// ✅ Debe retornar: 1

$tenant->products->first()->name;
// ✅ Debe retornar: "Hamburguesa Clásica"
```

---

### TEST 6: Verificar casts (JSON a array)

```php
// El campo business_hours debe ser array, no string
$tenant->business_hours;
// ✅ Debe retornar array:
// [
//   "monday" => "9:00-18:00",
//   "tuesday" => "9:00-18:00",
//   "wednesday" => "9:00-18:00",
// ]

// Verificar que es array
is_array($tenant->business_hours);
// ✅ Debe retornar: true

// Acceder a un valor del array
$tenant->business_hours['monday'];
// ✅ Debe retornar: "9:00-18:00"
```

---

### TEST 7: Probar casts de booleanos

```php
// Los booleanos deben ser true/false, no 1/0
$tenant->is_open;
// ✅ Debe retornar: true (no 1)

$tenant->domain_verified;
// ✅ Debe retornar: false (no 0)

// Verificar tipo
is_bool($tenant->is_open);
// ✅ Debe retornar: true
```

---

### TEST 8: Probar casts de fechas

```php
// Las fechas deben ser instancias de Carbon
$tenant->created_at;
// ✅ Debe retornar: Illuminate\Support\Carbon {#...}

// Verificar clase
$tenant->created_at instanceof \Carbon\Carbon;
// ✅ Debe retornar: true

// Formatear fecha
$tenant->created_at->format('Y-m-d H:i:s');
// ✅ Debe retornar: "2025-02-15 12:34:56"
```

---

### TEST 9: Probar relaciones desde Plan

```php
// Obtener un plan
$plan = \App\Models\Plan::first();

// Ver tenants del plan
$plan->tenants;
// ✅ Debe retornar: Illuminate\Database\Eloquent\Collection con 1 tenant

$plan->tenants->count();
// ✅ Debe retornar: 1

$plan->tenants->first()->business_name;
// ✅ Debe retornar: "Test Burguer"
```

---

### TEST 10: Probar relaciones desde User

```php
// Obtener el user
$user = \App\Models\User::first();

// Ver tenants del user
$user->tenants;
// ✅ Debe retornar: Illuminate\Database\Eloquent\Collection con 1 tenant

$user->tenants->count();
// ✅ Debe retornar: 1

$user->tenants->first()->business_name;
// ✅ Debe retornar: "Test Burguer"
```

---

### TEST 11: Crear TenantCustomization

```php
$customization = \App\Models\TenantCustomization::create([
    'tenant_id' => $tenant->id,
    'social_networks' => [
        'instagram' => '@testburguer',
        'facebook' => 'TestBurguerOficial',
    ],
    'payment_methods' => [
        'zelle' => true,
        'pago_movil' => true,
        'efectivo' => true,
    ],
]);

// Verificar que el JSON se castea a array
$customization->social_networks;
// ✅ Debe retornar array:
// [
//   "instagram" => "@testburguer",
//   "facebook" => "TestBurguerOficial",
// ]

is_array($customization->social_networks);
// ✅ Debe retornar: true

// Probar relación hasOne
$tenant->refresh();
$tenant->customization;
// ✅ Debe retornar: App\Models\TenantCustomization {#...}

$tenant->customization->social_networks['instagram'];
// ✅ Debe retornar: "@testburguer"
```

---

### TEST 12: Verificar DollarRate

```php
// Obtener tasa activa
$rate = \App\Models\DollarRate::where('is_active', true)->first();

$rate->rate;
// ✅ Debe retornar: "36.50"

$rate->source;
// ✅ Debe retornar: "BCV"

// Verificar cast de boolean
is_bool($rate->is_active);
// ✅ Debe retornar: true
```

---

## 🎯 CHECKLIST DE VALIDACIÓN

Marcar cada test completado:

- [ ] **TEST 1:** Datos iniciales (3 plans, 20 palettes, 1 rate)
- [ ] **TEST 2:** Crear tenant de prueba
- [ ] **TEST 3:** Relaciones belongsTo (user, plan, colorPalette)
- [ ] **TEST 4:** Relaciones hasMany vacías (products, services)
- [ ] **TEST 5:** Crear producto y verificar relación bidireccional
- [ ] **TEST 6:** Casts de JSON a array (business_hours)
- [ ] **TEST 7:** Casts de booleanos (is_open, domain_verified)
- [ ] **TEST 8:** Casts de fechas a Carbon (created_at)
- [ ] **TEST 9:** Relaciones inversas desde Plan
- [ ] **TEST 10:** Relaciones inversas desde User
- [ ] **TEST 11:** TenantCustomization con JSON casts
- [ ] **TEST 12:** DollarRate con casts correctos

---

## 🐛 PROBLEMAS COMUNES

### Error: "Class 'App\Models\X' not found"
**Solución:** Salir de tinker y volver a entrar:
```bash
exit
php artisan tinker
```

### Error: "Column not found"
**Solución:** Ejecutar migraciones:
```bash
exit
php artisan migrate
php artisan tinker
```

### Error: "SQLSTATE[23000]: Integrity constraint violation"
**Solución:** Verificar que existan los registros relacionados (user_id, plan_id, etc.)

---

## ✅ RESULTADO ESPERADO FINAL

Si todos los tests pasan:

```
✓ 10 modelos funcionando correctamente
✓ Todas las relaciones bidireccionales OK
✓ Casts de JSON a array OK
✓ Casts de booleanos OK
✓ Casts de fechas OK
✓ Foreign keys respetando constraints
```

---

## 🚀 SIGUIENTE PASO: COMMIT

Una vez validado todo:

```bash
# Salir de tinker
exit

# Ver cambios
git status

# Agregar modelos
git add app/Models/

# Commit
git commit -m "feat: Add 10 Eloquent models with relationships and casts

- Updated: User, Plan, Tenant with schema columns
- Created: Product, Service, TenantCustomization
- Created: AnalyticsEvent, DollarRate, Invoice, ColorPalette
- All relationships tested and working
- JSON casts to array implemented
- Boolean and datetime casts verified"

# Ver log
git log --oneline -5
```

---

**FIN DEL DOCUMENTO DE TESTING**  
Todas las instrucciones listas para validar los modelos en Tinker.
