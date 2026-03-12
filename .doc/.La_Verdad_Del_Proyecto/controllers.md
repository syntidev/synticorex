# CONTROLLERS — SYNTIweb

**Audit Date:** 2026-03-11  
**Total Controllers:** 28 (15 main + 9 auth + 4 food)  
**Total Lines of Code:** ~6,651

---

## Summary Statistics

| Metric | Value |
|--------|-------|
| Largest Controller | DashboardController (1,538 lines) |
| Smallest Controller | Controller.php base class (10 lines) |
| Average Lines | ~237 |
| Controllers > 400 lines | 5 (DashboardController, TenantRendererController, ProductController, ServiceController, ImageUploadController) |
| God Objects | 1 (DashboardController — 26+ public methods) |

---

## Main Controllers (15)

### 1. DashboardController.php — 1,538 lines ⚠️ GOD OBJECT
**Path:** `app/Http/Controllers/DashboardController.php`  
**Dependencies:** DollarRateService, QRService  
**Purpose:** Handles ALL dashboard CRUD operations across 7 tabs

**Public Methods (26+):**
| Method | Signature | Purpose |
|--------|-----------|---------|
| `__construct` | `(DollarRateService, QRService)` | Constructor DI |
| `index` | `(int $tenantId): View\|Response` | Render dashboard |
| `updateInfo` | `(Request, int): JsonResponse` | Update tenant info |
| `updateHeaderTop` | `(Request, int): JsonResponse` | Update header banner |
| `updateHeaderMessage` | `(Request, int): JsonResponse` | Update header message |
| `updateCta` | `(Request, int): JsonResponse` | Update CTA section |
| `createProduct` | `(Request, int): JsonResponse` | Create product |
| `updateProduct` | `(Request, int, int): JsonResponse` | Update product |
| `deleteProduct` | `(int, int): JsonResponse` | Delete product |
| `createService` | `(Request, int): JsonResponse` | Create service |
| `updateService` | `(Request, int, int): JsonResponse` | Update service |
| `deleteService` | `(int, int): JsonResponse` | Delete service |
| `updateTheme` | `(Request, int): JsonResponse` | Change theme |
| `updatePalette` | `(Request, int): JsonResponse` | Change color palette |
| `updateCurrencyConfig` | `(Request, int): JsonResponse` | Currency display mode |
| `updateTestimonials` | `(Request, int): JsonResponse` | Update testimonials JSON |
| `updateFaq` | `(Request, int): JsonResponse` | Update FAQ JSON |
| `updatePin` | `(Request, int): JsonResponse` | Change edit PIN |
| `toggleBranches` | `(Request, int): JsonResponse` | Toggle branches feature |
| `saveBranch` | `(Request, int): JsonResponse` | Create/update branch |
| `deleteBranch` | `(int, int): JsonResponse` | Delete branch |
| `updatePaymentMethods` | `(Request, int): JsonResponse` | Update payment methods |
| `updateSocialNetworks` | `(Request, int): JsonResponse` | Update social links |
| `saveSectionOrder` | `(Request, int): JsonResponse` | Reorder sections |
| `saveCustomPalette` | `(Request, int): JsonResponse` | Save custom colors |
| `updateBusinessHours` | `(Request, int): JsonResponse` | Update business hours |
| `toggleSection` | `(Request, int): JsonResponse` | Toggle section visibility |

**Technical Debt:** This controller should be split into ~5 smaller controllers (ProductDashboardController, ServiceDashboardController, DesignDashboardController, ConfigDashboardController, SectionDashboardController).

---

### 2. TenantRendererController.php — 695 lines
**Path:** `app/Http/Controllers/TenantRendererController.php`  
**Dependencies:** DollarRateService, QRService, BusinessHoursService  
**Purpose:** Renders public landing pages for tenants

| Method | Signature | Purpose |
|--------|-----------|---------|
| `__construct` | `(DollarRateService, QRService, BusinessHoursService)` | Constructor DI |
| `show` | `(string $subdomain): View\|Response\|JsonResponse` | Render by subdomain |
| `showByDomain` | `(string $domain): View\|Response\|JsonResponse` | Render by custom domain |
| `preview` | `(int $tenantId): View\|Response\|JsonResponse` | Preview mode |
| `verifyPin` | `(Request, int): JsonResponse` | PIN verification |
| `getHoursStatus` | `(int): JsonResponse` | Business hours status |
| `toggleStatus` | `(Request, int): JsonResponse` | Toggle open/closed |
| `toggleWhatsapp` | `(Request, int): JsonResponse` | Toggle WhatsApp number |

---

### 3. ProductController.php — 582 lines
**Path:** `app/Http/Controllers/ProductController.php`  
**Purpose:** REST API for products (parallel CRUD to DashboardController)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(Request, int): JsonResponse` | List tenant products |
| `show` | `(int, int): JsonResponse` | Get single product |
| `store` | `(Request, int): JsonResponse` | Create product |
| `update` | `(Request, int, int): JsonResponse` | Update product |
| `destroy` | `(int, int): JsonResponse` | Delete product |
| `toggleActive` | `(int, int): JsonResponse` | Toggle product visibility |
| `toggleFeatured` | `(int, int): JsonResponse` | Toggle featured status |

---

### 4. ImageUploadController.php — 501 lines
**Path:** `app/Http/Controllers/ImageUploadController.php`  
**Dependencies:** ImageUploadService  
**Purpose:** All image upload endpoints (logo, hero, product, service, gallery, about)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `uploadLogo` | `(Request, int): JsonResponse` | Upload business logo |
| `uploadHero` | `(Request, int): JsonResponse` | Upload hero image |
| `uploadProduct` | `(Request, int, int): JsonResponse` | Upload product photo |
| `uploadService` | `(Request, int, int): JsonResponse` | Upload service photo |
| `uploadProductGallery` | `(Request, int, int): JsonResponse` | Upload gallery image |
| `deleteProductGalleryImage` | `(int, int, int): JsonResponse` | Delete gallery image |
| `uploadAbout` | `(Request, int): JsonResponse` | Upload about photo |
| `uploadHeroSlot` | `(Request, int, int): JsonResponse` | Upload hero slot (1-5) |
| `deleteHeroSlot` | `(int, int): JsonResponse` | Delete hero slot |

---

### 5. TenantController.php — 491 lines
**Path:** `app/Http/Controllers/TenantController.php`  
**Purpose:** Tenant REST API (admin-level CRUD)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(Request): JsonResponse` | List all tenants |
| `show` | `(int): JsonResponse` | Get tenant details |
| `store` | `(Request): JsonResponse` | Create new tenant |
| `update` | `(Request, int): JsonResponse` | Update tenant |
| `destroy` | `(int): JsonResponse` | Delete tenant |
| `showBySubdomain` | `(string): JsonResponse` | Lookup by subdomain |
| `toggleStatus` | `(int): JsonResponse` | Toggle active status |

---

### 6. ServiceController.php — 475 lines
**Path:** `app/Http/Controllers/ServiceController.php`  
**Purpose:** REST API for services (parallel CRUD to DashboardController)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(Request, int): JsonResponse` | List tenant services |
| `show` | `(int, int): JsonResponse` | Get single service |
| `store` | `(Request, int): JsonResponse` | Create service |
| `update` | `(Request, int, int): JsonResponse` | Update service |
| `destroy` | `(int, int): JsonResponse` | Delete service |

---

### 7. OnboardingController.php — 332 lines
**Path:** `app/Http/Controllers/OnboardingController.php`  
**Purpose:** 3 onboarding wizards (studio/food/cat)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(): View\|RedirectResponse` | Studio wizard |
| `checkSubdomain` | `(Request): JsonResponse` | Check subdomain availability |
| `store` | `(Request): RedirectResponse` | Store studio tenant |
| `selector` | `(): View` | Blueprint selector |
| `food` | `(): View\|RedirectResponse` | Food wizard |
| `cat` | `(): View\|RedirectResponse` | Catalog wizard |
| `storeFood` | `(Request): RedirectResponse` | Store food tenant |
| `storeCat` | `(Request): RedirectResponse` | Store cat tenant |
| `preview` | `(Tenant): View` | Preview before publish |
| `publish` | `(Request, Tenant): RedirectResponse` | Publish tenant |

---

### 8. AnalyticsController.php — ~210 lines
**Path:** `app/Http/Controllers/AnalyticsController.php`  
**Purpose:** Analytics tracking and reporting

| Method | Signature | Purpose |
|--------|-----------|---------|
| `track` | `(Request): JsonResponse` | Track analytics event |
| `getData` | `(int): JsonResponse` | Get analytics report |
| `getToday` | `(int): JsonResponse` | Today's analytics |

---

### 9. MarketingController.php — 170 lines
**Path:** `app/Http/Controllers/MarketingController.php`  
**Purpose:** Marketing/sales pages

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(): View` | Main landing page |
| `planes` | `(): View` | Pricing page |
| `studio` | `(): View` | Studio product page |
| `food` | `(): View` | Food product page |
| `cat` | `(): View` | Catalog product page |

---

### 10. SyntiHelpController.php — 135 lines
**Path:** `app/Http/Controllers/SyntiHelpController.php`  
**Purpose:** AI assistant endpoints (SYNTI helper)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `ask` | `(Request): JsonResponse` | Authenticated AI query |
| `feedback` | `(Request): JsonResponse` | Rate AI response |
| `publicAsk` | `(Request): JsonResponse` | Public AI query (throttled) |

---

### 11. QRTrackingController.php — 88 lines
**Path:** `app/Http/Controllers/QRTrackingController.php`  
**Dependencies:** QRService  
**Purpose:** QR code shortlinks and downloads

| Method | Signature | Purpose |
|--------|-----------|---------|
| `handleShortlink` | `(int, string): RedirectResponse` | Redirect + track |
| `downloadQR` | `(int): Response` | Download QR PNG |

---

### 12. ProfileController.php — 62 lines
**Path:** `app/Http/Controllers/ProfileController.php`  
**Purpose:** User profile management (Breeze)

| Method | Signature | Purpose |
|--------|-----------|---------|
| `edit` | `(Request): View` | Profile edit form |
| `update` | `(ProfileUpdateRequest): RedirectResponse` | Save profile |
| `destroy` | `(Request): RedirectResponse` | Delete account |

---

### 13. CheckoutController.php — 58 lines
**Path:** `app/Http/Controllers/CheckoutController.php`  
**Dependencies:** OrderService, WhatsappMessageBuilder  
**Purpose:** SyntiCat checkout flow

| Method | Signature | Purpose |
|--------|-----------|---------|
| `store` | `(Request, string): JsonResponse` | Process checkout → WhatsApp |

---

### 14. OrdersController.php — 46 lines
**Path:** `app/Http/Controllers/OrdersController.php`  
**Purpose:** Order listing for tenant

| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(int): View` | List orders |

---

### 15. Controller.php — 10 lines
**Path:** `app/Http/Controllers/Controller.php`  
**Purpose:** Empty base class (Laravel convention)

---

## Auth Controllers (9) — Laravel Breeze Standard

All located in `app/Http/Controllers/Auth/`

| Controller | Lines | Methods |
|------------|------:|---------|
| AuthenticatedSessionController | 49 | `create()`, `store()`, `destroy()` |
| RegisteredUserController | 56 | `create()`, `store()` |
| NewPasswordController | 64 | `create()`, `store()` |
| PasswordResetLinkController | 46 | `create()`, `store()` |
| ConfirmablePasswordController | 42 | `show()`, `store()` |
| PasswordController | 31 | `update()` |
| VerifyEmailController | 29 | `__invoke()` |
| EmailVerificationNotificationController | 26 | `store()` |
| EmailVerificationPromptController | 23 | `__invoke()` |

---

## Food Controllers (4)

All located in `app/Http/Controllers/Food/`

### MenuController.php — 31 lines
**Dependencies:** MenuService  
| Method | Signature | Purpose |
|--------|-----------|---------|
| `show` | `(string): JsonResponse` | Get full menu JSON |

### ItemsController.php — 197 lines
**Dependencies:** MenuService, ImageUploadService  
| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(int, string): JsonResponse` | List items in category |
| `store` | `(Request, int, string): JsonResponse` | Add menu item |
| `update` | `(Request, int, string, string): JsonResponse` | Update item |
| `destroy` | `(int, string, string): JsonResponse` | Delete item |

### CategoriesController.php — 86 lines
**Dependencies:** MenuService  
| Method | Signature | Purpose |
|--------|-----------|---------|
| `index` | `(int): JsonResponse` | List categories |
| `store` | `(Request, int): JsonResponse` | Create category |
| `update` | `(Request, int, string): JsonResponse` | Update category |
| `destroy` | `(int, string): JsonResponse` | Delete category |

### ComandaController.php — 78 lines
**Dependencies:** ComandaService  
| Method | Signature | Purpose |
|--------|-----------|---------|
| `store` | `(Request, string): JsonResponse` | Generate comanda PDF |

---

## Patterns & Observations

1. **All controllers use `declare(strict_types=1)`** ✅
2. **Constructor-based dependency injection** — consistent across all controllers
3. **JsonResponse dominates** — dashboard is fully AJAX-driven
4. **Early return pattern** — used consistently
5. **Try-catch with logging** — standard error handling pattern
6. **No Form Request classes** — only ProfileUpdateRequest exists; all other validation is inline
7. **Parallel CRUD duplication** — Products and Services have CRUD in both DashboardController (for dashboard UI) and ProductController/ServiceController (for API). Shared logic should be extracted to services.
