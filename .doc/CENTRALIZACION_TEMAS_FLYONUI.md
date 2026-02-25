# 🎨 Centralización de Temas FlyonUI

**Completado:** 25 Feb 2026
**Tarea S4:** Centralizar lista 17 temas FlyonUI

---

## 📋 Resumen de Cambios

Se ha eliminado la **duplicación de hardcoded theme lists** en el codebase y se ha creado una **única fuente de verdad** para los 17 temas FlyonUI oficiales.

### Antes (Duplicado ❌)
```php
// DashboardController.php - updateTheme()
$validThemes = [
    'light', 'dark', 'black', 'claude', 'corporate', 'ghibli', 'gourmet',
    'luxury', 'mintlify', 'pastel', 'perplexity', 'shadcn', 'slack',
    'soft', 'spotify', 'valorant', 'vscode'
];

// DashboardController.php - updatePalette() [LEGACY]
$validThemes = [
    'light', 'dark', 'black', 'claude', 'corporate', 'ghibli',
    'gourmet', 'luxury', 'mintlify', 'pastel', 'perplexity',
    'shadcn', 'slack', 'soft', 'spotify', 'valorant', 'vscode'
];
```

### Después (Centralizado ✅)
```php
// config/flyonui-themes.php
// Single source of truth para todos los 17 temas

// DashboardController.php
$validated = $request->validate([
    'theme_slug' => 'required|string|' .
        FlyonUIThemeService::getValidationRule($tenant->plan_id)
]);
```

---

## 📁 Archivos Creados

### 1. **config/flyonui-themes.php**
Nueva configuración centralizada con:
- **17 temas** (10 Plan 1 + 7 Plan 2)
- **Agrupamiento por plan**: `by_plan.1`, `by_plan.2`, `by_plan.3`
- **Lista completa**: `all`
- **Organización**: `plan1`, `plan2_extra`

```php
config('flyonui-themes.all')               // Todos los 17
config('flyonui-themes.by_plan.1')         // 10 temas Plan 1
config('flyonui-themes.by_plan.2')         // 17 temas Plan 2
config('flyonui-themes.plan1')             // Lista base
config('flyonui-themes.plan2_extra')       // 7 adicionales
```

### 2. **app/Services/FlyonUIThemeService.php**
Servicio de utilidad con métodos estáticos:

```php
// Obtener temas por plan
FlyonUIThemeService::getThemesByPlan(1)    // Devuelve array

// Validar tema
FlyonUIThemeService::isValidTheme('light', 1)  // true/false

// Regla de validación
FlyonUIThemeService::getValidationRule(1)  // "in:light,dark,..."

// Obtener todos
FlyonUIThemeService::getAllThemes()

// Especiales
FlyonUIThemeService::getPlan1Themes()
FlyonUIThemeService::getPlan2ExtraThemes()
FlyonUIThemeService::getDefaultTheme()     // 'light'
```

---

## 📝 Archivos Modificados

### **app/Http/Controllers/DashboardController.php**
**Cambios:**
- ✅ Importado `FlyonUIThemeService`
- ✅ Reemplazado hardcoded list en `updateTheme()` (línea 482-488)
- ✅ Reemplazado hardcoded list en `updatePalette()` (línea 528-534)
- ✅ Reducción: -18 líneas, +5 líneas (mayor claridad)

**Antes:**
```php
$validThemes = [
    'light', 'dark', 'black', ..., 'vscode'
];
$validated = $request->validate([
    'theme_slug' => 'required|string|in:' . implode(',', $validThemes)
]);
```

**Después:**
```php
$validated = $request->validate([
    'theme_slug' => 'required|string|' .
        FlyonUIThemeService::getValidationRule($tenant->plan_id)
]);
```

---

## 🔄 Relaciones y Referencias

### Flujo de Datos de Temas
```
┌─────────────────────────────────────┐
│  config/flyonui-themes.php          │ ← ÚNICA FUENTE DE VERDAD
│  (17 temas definidos)               │
└────────────────┬────────────────────┘
                 │
        ┌────────┴────────┐
        │                 │
        ↓                 ↓
  DashboardController   ColorPalettesSeeder
  (validación)          (BD: color_palettes)
        │                 │
        │                 ↓
        │           Dashboard View
        │           (visualización)
        │                 │
        └─────────┬───────┘
                  ↓
           FlyonUIThemeService
           (utilidades)
```

### Donde se usan los Temas
1. **Validación API** → `DashboardController::updateTheme()`, `updatePalette()`
2. **Base de Datos** → `ColorPalette` model (colores, metadatos)
3. **Frontend** → `dashboard/index.blade.php` (selector visual)
4. **Landing** → `TenantRendererController::show()` (aplica via `data-theme`)

---

## 🎯 Ventajas

✅ **Una única fuente de verdad** → Cambios en un solo lugar
✅ **Menos código duplicado** → -18 líneas innecesarias
✅ **Reutilizable** → Service con métodos reutilizables
✅ **Type-safe** → Métodos estáticos con tipado completo
✅ **Fácil de mantener** → Config clara y documentada
✅ **Escalable** → Agregar nuevos temas en config/flyonui-themes.php
✅ **Validación respeta planes** → Temas limitados por plan automáticamente

---

## 🧪 Verificación

**Validar config:**
```bash
php artisan tinker
>>> config('flyonui-themes.all')      # Ver todos los temas
>>> config('flyonui-themes.by_plan.1') # Ver temas Plan 1
```

**Validar service:**
```bash
php artisan tinker
>>> App\Services\FlyonUIThemeService::getAllThemes()
>>> App\Services\FlyonUIThemeService::getValidationRule(1)
```

**Validar controller:**
```bash
# Las validaciones en updateTheme() y updatePalette() ahora usan el service
```

---

## 📌 Próximos Pasos S4

- [ ] ✅ **Centralizar lista 17 temas FlyonUI** (COMPLETADO)
- [ ] Limpieza código LEGACY updatePalette
- [ ] Middleware autenticación rutas tenant
- [ ] Analytics: tracking JS → analytics_events
- [ ] SEO: meta tags dinámicos

---

## 🔗 Referencias

- **Config:** `config/flyonui-themes.php`
- **Service:** `app/Services/FlyonUIThemeService.php`
- **Controller:** `app/Http/Controllers/DashboardController.php`
- **Database:** `database/seeders/ColorPalettesSeeder.php`
- **View:** `resources/views/dashboard/index.blade.php`
