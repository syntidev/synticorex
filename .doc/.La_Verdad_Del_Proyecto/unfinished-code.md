# UNFINISHED CODE — SYNTIweb

**Audit Date:** 2026-03-11  
**Scan Method:** Recursive grep for TODO, FIXME, HACK, XXX, dd(), dump(), var_dump(), debugger, console.log, "add in production"

---

## Summary

| Marker | Count | Severity |
|--------|------:|----------|
| TODO | 0 | — |
| FIXME | 0 | — |
| HACK | 0 | — |
| XXX | 0 | — |
| dd() | 0 | — |
| dump() | 0 | — |
| var_dump() | 0 | — |
| debugger | 0 | — |
| console.log | 4 | LOW |
| Production-deferred comments | 1 | **CRITICAL** |
| Intentional Log:: calls | 100+ | INFO (production logging) |

---

## Debug Statements Found

### console.log (4 instances)

1. **dashboard/components/config-section.blade.php** (formerly settings.blade.php)
   - Line ~409: `console.log()` in JavaScript section
   - Purpose: Debug logging during config save

2. **dashboard/index.blade.php**
   - Line ~215: `console.log()` in inline script
   - Purpose: Debug logging during dashboard init

3. **dashboard/scripts/design-config-scripts.blade.php**
   - Line ~281: `console.log()` in design configuration
   - Purpose: Debug logging for theme switching

4. **dashboard/scripts/sortable-scripts.blade.php**
   - Line ~54: `console.log()` in section reordering
   - Purpose: Debug logging for drag-and-drop events

**Recommendation:** Remove all 4 console.log statements before production. They leak internal state to browser DevTools.

---

## Production-Deferred Code

### 1. API Authentication — CRITICAL ⚠️

**File:** `routes/api.php`, line 12  
**Content:** `// Protected routes (add auth:sanctum middleware in production)`

**Impact:** ALL API routes (tenant CRUD, product CRUD, service CRUD, AI helper) are publicly accessible without authentication. Any person can:
- List all tenants
- Create/update/delete any tenant
- Create/update/delete any product
- Create/update/delete any service
- Toggle tenant status

**Required Action:**
```php
Route::middleware('auth:sanctum')->group(function () {
    // All current api.php routes go here
});
```

---

## Intentional Logging (Not Issues)

The codebase uses `Log::info()`, `Log::warning()`, and `Log::error()` extensively (100+ instances) across services and controllers for production-grade logging. These are intentional and properly structured:

- `DollarRateService` — Logs rate fetches, failures, propagation
- `ImageUploadService` — Logs upload success, WebP conversion
- `TenantRendererController` — Logs rendering events
- `DashboardController` — Logs CRUD operations
- `OnboardingController` — Logs tenant creation

---

## Backup Files (Should Be Removed)

| File | Purpose |
|------|---------|
| `resources/views/landing/templates/food.blade.php.bak` | Backup of food template |
| `resources/views/landing/templates/food.blade.php.bak2` | Second backup of food template |
| `resources/js/old_app.js` | Legacy app.js with FlyonUI import |

---

## Dead Code (Unreachable)

### dashboard/_archive/ — 7 files
These files contain DaisyUI class names (`btn`, `card`, `modal`, `badge`, etc.) that were once used but are now replaced by Preline equivalents. They are not included anywhere:

- `_archive/analytics-section.blade.php`
- `_archive/branches-section.blade.php`
- `_archive/config-section.blade.php`
- `_archive/design-section.blade.php`
- `_archive/info-section.blade.php`
- `_archive/products-section.blade.php`
- `_archive/services-section.blade.php`

### FlyonUI Residual Code
- `app/Services/FlyonUIThemeService.php` — Service for a removed UI library
- `config/flyonui-themes.php` — Configuration for removed library
- `database/seeders/FlyonUIThemesSeeder.php` — Seeder for removed library
- `database/seeders/UpdateTenantsThemesSeeder.php` — Maps FlyonUI themes to tenants

---

## Completeness Assessment

| Area | Status |
|------|--------|
| No dangling TODO/FIXME | ✅ Clean |
| No debug statements in PHP | ✅ Clean |
| No dd()/dump() left behind | ✅ Clean |
| Console.log in JS | ⚠️ 4 instances to clean |
| Production-deferred auth | ❌ CRITICAL — must fix before deploy |
| Dead code / backups | ⚠️ Minor cleanup needed |
