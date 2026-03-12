# TECHNICAL DEBT — SYNTIweb

**Audit Date:** 2026-03-11  
**Overall Debt Level:** MODERATE — Functional but needs refactoring before scaling

---

## Debt Classification

| Category | Items | Impact |
|----------|------:|--------|
| Architecture Debt | 3 | HIGH |
| Code Duplication | 2 | MEDIUM |
| Dead Code | 4 | LOW |
| Missing Infrastructure | 5 | MEDIUM-HIGH |
| Security Debt | 3 | CRITICAL |

---

## 1. Architecture Debt

### 1.1 DashboardController — God Object ⚠️ HIGH
**File:** `app/Http/Controllers/DashboardController.php`  
**Lines:** 1,538  
**Methods:** 26+ public methods  
**Problem:** Single controller handles ALL dashboard operations across 7 tabs: tenant info, products, services, design, messaging, analytics, configuration.

**Impact:**
- Difficult to maintain and test
- High cognitive load for developers
- Risk of merge conflicts
- Violates Single Responsibility Principle

**Recommended Refactoring:**
```
DashboardController (1,538 lines)
  └── Split into:
      ├── DashboardInfoController      (updateInfo, updateHeaderTop, updateHeaderMessage, updateBusinessHours, updateSocialNetworks)
      ├── DashboardProductController   (createProduct, updateProduct, deleteProduct)
      ├── DashboardServiceController   (createService, updateService, deleteService)
      ├── DashboardDesignController    (updateTheme, updatePalette, saveCustomPalette, saveSectionOrder, toggleSection)
      ├── DashboardConfigController    (updatePin, updateCurrencyConfig, toggleBranches, saveBranch, deleteBranch, updatePaymentMethods)
      └── DashboardContentController   (updateCta, updateTestimonials, updateFaq)
```

### 1.2 Dashboard View — Monolithic Template ⚠️ MEDIUM
**File:** `resources/views/dashboard/index.blade.php`  
**Lines:** ~2,400  
**Problem:** Self-contained HTML page with inline CSS (~400 lines of `<style>`) and no layout extension. While sections are included via `@include`, the index itself is massive.

**Impact:**
- Slow to render conceptually
- Inline styles can't be cached separately
- No layout inheritance for header/sidebar

### 1.3 Parallel CRUD Duplication ⚠️ MEDIUM
**Problem:** Product and Service CRUD exists in two places:
1. `DashboardController` — For dashboard UI (web routes)
2. `ProductController` / `ServiceController` — For API (api.php routes)

Both implement the same validation, creation, and update logic independently.

**Recommended Fix:** Extract shared logic into services:
```
ProductService::create($tenantId, $data)
ProductService::update($productId, $data)
ServiceService::create($tenantId, $data)
```

---

## 2. Dead Code & FlyonUI Residuals

### 2.1 FlyonUI Artifacts (Removed Library)
| File | Type | Action |
|------|------|--------|
| `app/Services/FlyonUIThemeService.php` | Service | DELETE |
| `config/flyonui-themes.php` | Config | DELETE |
| `database/seeders/FlyonUIThemesSeeder.php` | Seeder | DELETE |
| `database/seeders/UpdateTenantsThemesSeeder.php` | Seeder | DELETE |
| `resources/js/old_app.js` | JS | DELETE |

### 2.2 Dashboard Archive (DaisyUI Files)
| File | Action |
|------|--------|
| `dashboard/_archive/analytics-section.blade.php` | DELETE |
| `dashboard/_archive/branches-section.blade.php` | DELETE |
| `dashboard/_archive/config-section.blade.php` | DELETE |
| `dashboard/_archive/design-section.blade.php` | DELETE |
| `dashboard/_archive/info-section.blade.php` | DELETE |
| `dashboard/_archive/products-section.blade.php` | DELETE |
| `dashboard/_archive/services-section.blade.php` | DELETE |

### 2.3 Backup Files
| File | Action |
|------|--------|
| `resources/views/landing/templates/food.blade.php.bak` | DELETE |
| `resources/views/landing/templates/food.blade.php.bak2` | DELETE |

### 2.4 Legacy Views
| File | Purpose | Action |
|------|---------|--------|
| `resources/views/tenants/dashboard.blade.php` | Old dashboard | VERIFY if used, likely DELETE |
| `resources/views/tenants/settings.blade.php` | Old settings | VERIFY if used, likely DELETE |

---

## 3. Missing Infrastructure

### 3.1 No Model Factories
| Missing Factory | Impact |
|----------------|--------|
| TenantFactory | Cannot test tenant creation flows |
| ProductFactory | Cannot test product operations |
| ServiceFactory | Cannot test service operations |
| TenantCustomizationFactory | Cannot test customization |

### 3.2 No Form Request Classes
Only `ProfileUpdateRequest` exists. All other validation is inline in controllers. This means:
- Validation logic is duplicated between Dashboard and API controllers
- No reusable validation rules
- Harder to test validation independently

### 3.3 No Repository Pattern
Controllers query Eloquent directly. For current scale this is acceptable, but limits:
- Cache layer insertion
- Query optimization
- Database swapping

### 3.4 No Event/Listener System
Business events (tenant created, product updated, subscription expired) are handled inline rather than dispatched as events. This prevents:
- Notification triggers
- Audit logging
- Async processing

### 3.5 No API Resources / Transformers
API responses return raw model data. Missing:
- Consistent response format
- Field selection
- Pagination standards

---

## 4. Large Files (> 500 lines)

| File | Lines | Concern |
|------|------:|---------|
| DashboardController.php | 1,538 | God Object |
| dashboard/index.blade.php | 2,400 | Monolithic template |
| info-section.blade.php | 913 | Large form component |
| floating-panel.blade.php | 760 | Complex interactive panel |
| design-config-scripts.blade.php | 745 | Large JS section |
| TenantRendererController.php | 695 | Complex but justified |
| ProductController.php | 582 | CRUD — acceptable |
| DollarRateService.php | 501 | External API — acceptable |
| ImageUploadController.php | 501 | Multiple upload types — acceptable |
| tab-product-scripts.blade.php | 500 | Large JS section |
| TenantController.php | 491 | CRUD — acceptable |
| ServiceController.php | 475 | CRUD — acceptable |

---

## 5. Security Debt

### 5.1 Unprotected API Routes — CRITICAL
**Risk:** Full CRUD API exposed without authentication  
**File:** `routes/api.php`  
**Fix:** Add `auth:sanctum` middleware group

### 5.2 PIN Verification Without Throttle — HIGH
**Risk:** Brute-force attack on 4-digit PIN  
**File:** `TenantRendererController@verifyPin`  
**Fix:** Add throttle middleware (e.g., `throttle:5,1` = 5 attempts per minute)

### 5.3 Missing domain_verified Check — MEDIUM
**Risk:** Tenant with unverified custom domain could be served  
**File:** `app/Http/Middleware/IdentifyTenant.php`  
**Fix:** Add `where('domain_verified', true)` to custom domain query

---

## Debt Prioritization Matrix

| Priority | Item | Effort | Impact |
|----------|------|--------|--------|
| P0 | API authentication (auth:sanctum) | 1 hour | Blocks production |
| P0 | PIN brute-force throttle | 30 min | Security risk |
| P1 | domain_verified check | 15 min | Security gap |
| P1 | Delete FlyonUI artifacts | 30 min | Dead code cleanup |
| P1 | Delete _archive/ and .bak files | 15 min | Dead code cleanup |
| P2 | Split DashboardController | 4-8 hours | Maintainability |
| P2 | Extract CRUD to services | 4-6 hours | DRY principle |
| P2 | Add model factories | 2-3 hours | Testing enablement |
| P3 | Form Request classes | 3-4 hours | Validation DRY |
| P3 | API Resources | 2-3 hours | API consistency |
| P3 | Remove console.log | 15 min | Code hygiene |
| P4 | Extract dashboard inline CSS | 2-3 hours | Performance |
