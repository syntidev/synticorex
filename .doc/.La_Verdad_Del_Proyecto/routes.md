# ROUTES — SYNTIweb

**Audit Date:** 2026-03-11  
**Total Routes:** ~156  
**Route Files:** 4 (web.php, api.php, auth.php, console.php)

---

## Route Summary

| File | Routes | Auth Required | Purpose |
|------|-------:|:------------:|---------|
| web.php | ~100 | Mixed | Main app routes |
| api.php | ~30 | **NONE** ⚠️ | REST API |
| auth.php | ~15 | Mixed | Authentication |
| console.php | 2 | N/A | Scheduled tasks |

---

## web.php — Main Application Routes (~100 routes)

### Public Routes (No Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/` | MarketingController@index | Homepage / marketing |
| GET | `/planes` | MarketingController@planes | Pricing page |
| GET | `/studio` | MarketingController@studio | Studio product page |
| GET | `/food` | MarketingController@food | Food product page |
| GET | `/cat` | MarketingController@cat | Catalog product page |
| GET | `/{subdomain}` | TenantRendererController@show | Public landing page |
| POST | `/tenant/{id}/verify-pin` | TenantRendererController@verifyPin | PIN verification |
| POST | `/tenant/{id}/toggle-status` | TenantRendererController@toggleStatus | Toggle open/closed |
| POST | `/tenant/{id}/toggle-whatsapp` | TenantRendererController@toggleWhatsapp | Toggle WhatsApp |
| GET | `/tenant/{id}/hours-status` | TenantRendererController@getHoursStatus | Business hours |
| GET | `/qr/{tenant}/{code}` | QRTrackingController@handleShortlink | QR redirect + track |
| GET | `/qr/{tenant}/download` | QRTrackingController@downloadQR | Download QR PNG |
| GET | `/domain/{domain}` | TenantRendererController@showByDomain | Custom domain landing |

### Public APIs (No Auth, in web.php)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/dollar-rate` | (closure) | Current USD rate |
| GET | `/api/euro-rate` | (closure) | Current EUR rate |
| POST | `/api/analytics/track` | AnalyticsController@track | Track analytics event |

### Onboarding Routes (Auth Required)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/onboarding/selector` | OnboardingController@selector | Blueprint picker |
| GET | `/onboarding/nuevo` | OnboardingController@index | Studio wizard |
| POST | `/onboarding/nuevo` | OnboardingController@store | Save studio tenant |
| GET | `/onboarding/food` | OnboardingController@food | Food wizard |
| POST | `/onboarding/food` | OnboardingController@storeFood | Save food tenant |
| GET | `/onboarding/cat` | OnboardingController@cat | Cat wizard |
| POST | `/onboarding/cat` | OnboardingController@storeCat | Save cat tenant |
| POST | `/onboarding/check-subdomain` | OnboardingController@checkSubdomain | Availability check |
| GET | `/onboarding/{tenant}/preview` | OnboardingController@preview | Preview before publish |
| POST | `/onboarding/{tenant}/publish` | OnboardingController@publish | Publish tenant |

### Dashboard Routes (Auth Required)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/tenant/{id}/dashboard` | DashboardController@index | Main dashboard view |
| PUT | `/tenant/{id}/info` | DashboardController@updateInfo | Update tenant info |
| PUT | `/tenant/{id}/header-top` | DashboardController@updateHeaderTop | Update header banner |
| PUT | `/tenant/{id}/header-message` | DashboardController@updateHeaderMessage | Update header message |
| PUT | `/tenant/{id}/cta` | DashboardController@updateCta | Update CTA section |
| PUT | `/tenant/{id}/theme` | DashboardController@updateTheme | Change theme |
| PUT | `/tenant/{id}/palette` | DashboardController@updatePalette | Change palette |
| PUT | `/tenant/{id}/currency-config` | DashboardController@updateCurrencyConfig | Currency mode |
| PUT | `/tenant/{id}/testimonials` | DashboardController@updateTestimonials | Update testimonials |
| PUT | `/tenant/{id}/faq` | DashboardController@updateFaq | Update FAQ |
| PUT | `/tenant/{id}/pin` | DashboardController@updatePin | Change PIN |
| PUT | `/tenant/{id}/payment-methods` | DashboardController@updatePaymentMethods | Payment methods |
| PUT | `/tenant/{id}/social-networks` | DashboardController@updateSocialNetworks | Social links |
| PUT | `/tenant/{id}/business-hours` | DashboardController@updateBusinessHours | Business hours |
| POST | `/tenant/{id}/section-order` | DashboardController@saveSectionOrder | Reorder sections |
| POST | `/tenant/{id}/custom-palette` | DashboardController@saveCustomPalette | Custom colors |
| POST | `/tenant/{id}/toggle-section` | DashboardController@toggleSection | Toggle section |
| POST | `/tenant/{id}/toggle-branches` | DashboardController@toggleBranches | Toggle branches |

### Product CRUD (Dashboard, Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/tenant/{id}/products` | DashboardController@createProduct | Create |
| PUT | `/tenant/{id}/products/{pid}` | DashboardController@updateProduct | Update |
| DELETE | `/tenant/{id}/products/{pid}` | DashboardController@deleteProduct | Delete |

### Service CRUD (Dashboard, Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/tenant/{id}/services` | DashboardController@createService | Create |
| PUT | `/tenant/{id}/services/{sid}` | DashboardController@updateService | Update |
| DELETE | `/tenant/{id}/services/{sid}` | DashboardController@deleteService | Delete |

### Branch CRUD (Dashboard, Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/tenant/{id}/branches` | DashboardController@saveBranch | Create/Update |
| DELETE | `/tenant/{id}/branches/{bid}` | DashboardController@deleteBranch | Delete |

### Image Upload Routes (Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/tenant/{id}/upload/logo` | ImageUploadController@uploadLogo | Logo upload |
| POST | `/tenant/{id}/upload/hero` | ImageUploadController@uploadHero | Hero image |
| POST | `/tenant/{id}/upload/hero-slot/{slot}` | ImageUploadController@uploadHeroSlot | Hero slot 1-5 |
| DELETE | `/tenant/{id}/upload/hero-slot/{slot}` | ImageUploadController@deleteHeroSlot | Remove hero slot |
| POST | `/tenant/{id}/upload/product/{pid}` | ImageUploadController@uploadProduct | Product photo |
| POST | `/tenant/{id}/upload/service/{sid}` | ImageUploadController@uploadService | Service photo |
| POST | `/tenant/{id}/upload/product/{pid}/gallery` | ImageUploadController@uploadProductGallery | Gallery photo |
| DELETE | `/tenant/{id}/product/{pid}/gallery/{imgId}` | ImageUploadController@deleteProductGalleryImage | Remove gallery |
| POST | `/tenant/{id}/upload/about` | ImageUploadController@uploadAbout | About photo |

### Food-Specific Routes (Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/tenant/{id}/menu/categories` | CategoriesController@index | List categories |
| POST | `/tenant/{id}/menu/categories` | CategoriesController@store | Create category |
| PUT | `/tenant/{id}/menu/categories/{slug}` | CategoriesController@update | Update category |
| DELETE | `/tenant/{id}/menu/categories/{slug}` | CategoriesController@destroy | Delete category |
| GET | `/tenant/{id}/menu/{catSlug}/items` | ItemsController@index | List items in category |
| POST | `/tenant/{id}/menu/{catSlug}/items` | ItemsController@store | Create menu item |
| PUT | `/tenant/{id}/menu/{catSlug}/items/{slug}` | ItemsController@update | Update item |
| DELETE | `/tenant/{id}/menu/{catSlug}/items/{slug}` | ItemsController@destroy | Delete item |
| POST | `/food-checkout/{subdomain}` | ComandaController@store | Submit food order |
| POST | `/checkout/{subdomain}` | CheckoutController@store | Submit cat order |
| GET | `/tenant/{id}/orders` | OrdersController@index | View orders |

### Analytics (Auth)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/tenant/{id}/analytics` | AnalyticsController@getData | Full report |
| GET | `/tenant/{id}/analytics/today` | AnalyticsController@getToday | Today's data |

---

## api.php — REST API (~30 routes)

### ⚠️ CRITICAL: No authentication middleware applied

**Comment in file:** `// Protected routes (add auth:sanctum middleware in production)`

### Tenant API

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/tenants` | TenantController@index | List all tenants |
| POST | `/api/tenants` | TenantController@store | Create tenant |
| GET | `/api/tenants/{id}` | TenantController@show | Get tenant |
| PUT | `/api/tenants/{id}` | TenantController@update | Update tenant |
| DELETE | `/api/tenants/{id}` | TenantController@destroy | Delete tenant |
| POST | `/api/tenants/{id}/toggle-status` | TenantController@toggleStatus | Toggle status |
| GET | `/api/tenants/subdomain/{sub}` | TenantController@showBySubdomain | Lookup |
| GET | `/api/public/{subdomain}` | TenantRendererController@show | Public landing data |

### Product API (Nested under tenant)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/tenants/{id}/products` | ProductController@index | List products |
| POST | `/api/tenants/{id}/products` | ProductController@store | Create product |
| GET | `/api/tenants/{id}/products/{pid}` | ProductController@show | Get product |
| PUT | `/api/tenants/{id}/products/{pid}` | ProductController@update | Update product |
| DELETE | `/api/tenants/{id}/products/{pid}` | ProductController@destroy | Delete product |
| POST | `/api/tenants/{id}/products/{pid}/toggle-active` | ProductController@toggleActive | Toggle active |
| POST | `/api/tenants/{id}/products/{pid}/toggle-featured` | ProductController@toggleFeatured | Toggle featured |

### Service API (Nested under tenant)

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/tenants/{id}/services` | ServiceController@index | List services |
| POST | `/api/tenants/{id}/services` | ServiceController@store | Create service |
| GET | `/api/tenants/{id}/services/{sid}` | ServiceController@show | Get service |
| PUT | `/api/tenants/{id}/services/{sid}` | ServiceController@update | Update service |
| DELETE | `/api/tenants/{id}/services/{sid}` | ServiceController@destroy | Delete service |

### SYNTI AI Helper (Throttled)

| Method | URI | Controller@Method | Throttle | Purpose |
|--------|-----|-------------------|----------|---------|
| POST | `/api/synti/ask` | SyntiHelpController@ask | 30/min | Auth AI query |
| POST | `/api/synti/feedback` | SyntiHelpController@feedback | 30/min | Rate response |
| POST | `/api/synti/public-ask` | SyntiHelpController@publicAsk | 10/min | Public AI query |

---

## auth.php — Authentication Routes (15 routes, Breeze)

### Guest-Only Routes

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/register` | RegisteredUserController@create | Registration form |
| POST | `/register` | RegisteredUserController@store | Submit registration |
| GET | `/login` | AuthenticatedSessionController@create | Login form |
| POST | `/login` | AuthenticatedSessionController@store | Submit login |
| GET | `/forgot-password` | PasswordResetLinkController@create | Password reset form |
| POST | `/forgot-password` | PasswordResetLinkController@store | Send reset link |
| GET | `/reset-password/{token}` | NewPasswordController@create | New password form |
| POST | `/reset-password` | NewPasswordController@store | Submit new password |

### Authenticated Routes

| Method | URI | Controller@Method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/verify-email` | EmailVerificationPromptController | Verify email page |
| GET | `/verify-email/{id}/{hash}` | VerifyEmailController | Confirm email |
| POST | `/email/verification-notification` | EmailVerificationNotificationController@store | Resend email |
| GET | `/confirm-password` | ConfirmablePasswordController@show | Confirm password page |
| POST | `/confirm-password` | ConfirmablePasswordController@store | Submit password |
| PUT | `/password` | PasswordController@update | Update password |
| POST | `/logout` | AuthenticatedSessionController@destroy | Logout |

---

## console.php — Scheduled Commands (2)

| Schedule | Command | Purpose |
|----------|---------|---------|
| Hourly | `dollar:update` | Fetch USD/EUR rates from DolarAPI.com |
| Daily 2:00 AM | `tenants:check-expiry` | Transition expired tenants: active → frozen → archived |

---

## Route Security Observations

1. **API routes have NO authentication** — All CRUD operations (create, update, delete tenants/products/services) are publicly accessible ⚠️ CRITICAL
2. **PIN verification has no rate limiting** — vulnerable to brute force
3. **Dashboard routes require auth but no tenant ownership check** — any authenticated user could potentially access any tenant's dashboard (depends on controller logic)
4. **Analytics tracking is unauthenticated** — could be spammed
5. **SYNTI AI public endpoint is properly throttled** (10/min) ✅
