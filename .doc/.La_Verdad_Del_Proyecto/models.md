# MODELS — SYNTIweb

**Audit Date:** 2026-03-11  
**Total Models:** 14  
**Total Lines:** ~1,200+

---

## Model Relationship Map

```
User (1) ──────┐
               │ hasMany
               ▼
Tenant (hub) ──┬── hasMany ──► Product ──── hasMany ──► ProductImage
               │                                         (Plan 3 only)
               ├── hasMany ──► Service
               ├── hasMany ──► AnalyticsEvent
               ├── hasMany ──► Invoice
               ├── hasMany ──► TenantBranch (Plan 3)
               ├── hasOne  ──► TenantCustomization
               ├── belongsTo ► Plan
               └── belongsTo ► ColorPalette

Plan (1) ──────► hasMany ──► Tenant
ColorPalette ──► belongsTo ► Plan (via min_plan_id)

DollarRate ──── (standalone, no FK relationships)
AiDoc ───────── (standalone, FULLTEXT search)
AiChatLog ───── belongsTo ► Tenant
```

---

## Detailed Model Inventory

### 1. Tenant.php — ~300 lines (Central Hub Model)
**Path:** `app/Models/Tenant.php`  
**Traits:** HasBlueprint

**Fillable (32 columns):**
`user_id`, `plan_id`, `subdomain`, `base_domain`, `custom_domain`, `domain_verified`, `business_name`, `business_segment`, `slogan`, `description`, `phone`, `whatsapp_sales`, `whatsapp_support`, `email`, `address`, `city`, `country`, `business_hours`, `is_open`, `edit_pin`, `currency_display`, `color_palette_id`, `meta_title`, `meta_description`, `meta_keywords`, `status`, `trial_ends_at`, `subscription_ends_at`, `plan_activated_at`, `settings`, `whatsapp_active`, `industry_segment`

**Casts:**
`domain_verified` → boolean, `business_hours` → array, `settings` → array, `is_open` → boolean, `trial_ends_at` → datetime, `subscription_ends_at` → datetime, `plan_activated_at` → datetime

**Relationships:**
| Method | Type | Target |
|--------|------|--------|
| `user()` | BelongsTo | User |
| `plan()` | BelongsTo | Plan |
| `colorPalette()` | BelongsTo | ColorPalette |
| `products()` | HasMany | Product |
| `services()` | HasMany | Service |
| `analyticsEvents()` | HasMany | AnalyticsEvent |
| `invoices()` | HasMany | Invoice |
| `customization()` | HasOne | TenantCustomization |
| `branches()` | HasMany | TenantBranch |

**Business Logic Methods:**
| Method | Returns | Purpose |
|--------|---------|---------|
| `getActiveWhatsapp()` | `?string` | Returns sales or support WhatsApp based on setting |
| `daysUntilExpiry()` | `?int` | Days until subscription expires (negative = expired) |
| `isExpiringSoon()` | `bool` | True if 0-30 days until expiry |
| `isFrozen()` | `bool` | Status === 'frozen' |
| `isArchived()` | `bool` | Status === 'archived' |
| `graceRemainingDays()` | `?int` | Days left of 30-day grace period |
| `getAvailableSections()` | `array` | 9 reorderable sections |
| `isAtLeastCrecimiento()` | `bool` | Plan 2+ check |
| `isVision()` | `bool` | Plan 3 check |
| `getBlueprintSlug()` | `string` | Maps segment to blueprint (food/retail/studio/etc.) |

---

### 2. TenantCustomization.php — ~400 lines
**Path:** `app/Models/TenantCustomization.php`

**Fillable (21 columns):**
`tenant_id`, `logo_filename`, `hero_main_filename`, `hero_secondary_filename`, `hero_tertiary_filename`, `hero_image_4_filename`, `hero_image_5_filename`, `hero_layout`, `theme_slug`, `social_networks`, `payment_methods`, `faq_items`, `cta_title`, `cta_subtitle`, `cta_button_text`, `cta_button_link`, `visual_effects`, `content_blocks`, `about_text`, `about_image_filename`, `header_message`

**Casts:**
`social_networks` → array, `payment_methods` → array, `faq_items` → array, `visual_effects` → array, `content_blocks` → array

**Business Logic (11 methods):**
| Method | Returns | Purpose |
|--------|---------|---------|
| `getSectionsOrder()` | `array` | Normalized sections_order with defaults |
| `getSectionConfig()` | `array` | Merged config for specific section |
| `isSectionVisible()` | `bool` | Section visibility check |
| `canAccessSection()` | `bool` | Plan-based section gating |
| `updateSectionConfig()` | `void` | Update section config |
| `syncSectionsConfig()` | `void` | Sync visibility states |
| `getContentBlock()` | `mixed` | Get from content_blocks JSON |
| `getHeroTitle()` | `?string` | Hero title with fallback |
| `getHeroSubtitle()` | `?string` | Hero subtitle |
| `getAboutText()` | `?string` | About text with fallback |
| `getSectionTitle()` | `string` | Custom section title |

---

### 3. Product.php — ~90 lines
**Path:** `app/Models/Product.php`

**Fillable:** `tenant_id`, `name`, `description`, `price_usd`, `price_bs`, `image_filename`, `image_url`, `position`, `is_active`, `is_featured`, `badge`

**Casts:** `price_usd` → decimal:2, `price_bs` → decimal:2, `position` → integer, `is_active` → boolean, `is_featured` → boolean

**Relationships:**
- `tenant()` → BelongsTo Tenant
- `galleryImages()` → HasMany ProductImage (ordered by position)

**Custom Method:** `getAllImageUrls(int $tenantId): array` — All image URLs (main + gallery)

---

### 4. Service.php — ~50 lines
**Path:** `app/Models/Service.php`

**Fillable:** `tenant_id`, `name`, `description`, `icon_name`, `image_filename`, `overlay_text`, `cta_text`, `cta_link`, `position`, `is_active`

**Casts:** `position` → integer, `is_active` → boolean

**Relationships:** `tenant()` → BelongsTo Tenant

---

### 5. Plan.php — ~62 lines
**Path:** `app/Models/Plan.php`  
**Traits:** HasFactory

**Constants:** `OPORTUNIDAD = 1`, `CRECIMIENTO = 2`, `VISION = 3`

**Fillable:** `slug`, `name`, `price_usd`, `products_limit`, `services_limit`, `images_limit`, `color_palettes`, `social_networks_limit`, `show_dollar_rate`, `show_header_top`, `show_about_section`, `show_payment_methods`, `show_faq`, `show_cta_special`, `analytics_level`, `seo_level`, `whatsapp_numbers`, `whatsapp_hour_filter`, `blueprint`

**Relationships:** `tenants()` → HasMany Tenant

---

### 6. ProductImage.php — ~32 lines
**Path:** `app/Models/ProductImage.php`

**Fillable:** `product_id`, `image_filename`, `position`  
**Casts:** `position` → integer  
**Relationships:** `product()` → BelongsTo Product  
**Note:** Plan 3 only. Max 2 additional images per product.

---

### 7. ColorPalette.php — ~58 lines
**Path:** `app/Models/ColorPalette.php`

**Fillable:** `name`, `slug`, `primary_color`, `secondary_color`, `accent_color`, `background_color`, `text_color`, `min_plan_id`, `category`

**Extended fields (migration):** `text_muted`, `background_alt`, `button_bg`, `button_text`, `button_hover_bg`, `link_color`, `link_hover`, `font_primary`, `font_secondary`, `segment_tags`, `emotional_effect`, `is_active`

**Relationships:**
- `minPlan()` → BelongsTo Plan
- `tenants()` → HasMany Tenant

---

### 8. DollarRate.php — ~50 lines
**Path:** `app/Models/DollarRate.php`  
**No UPDATED_AT**

**Fillable:** `rate`, `source`, `currency_type`, `effective_from`, `effective_until`, `is_active`  
**Casts:** `rate` → decimal:4, `is_active` → boolean

**Scopes:**
- `scopeUsd($query)` — WHERE currency_type = 'USD'
- `scopeEur($query)` — WHERE currency_type = 'EUR'

---

### 9. Invoice.php — ~35 lines
**Path:** `app/Models/Invoice.php`

**Fillable:** `tenant_id`, `invoice_number`, `amount_usd`, `currency`, `payment_method`, `payment_reference`, `payment_date`, `pdf_filename`, `status`, `period_start`, `period_end`

**Relationships:** `tenant()` → BelongsTo Tenant

---

### 10. TenantBranch.php — ~35 lines
**Path:** `app/Models/TenantBranch.php`

**Fillable:** `tenant_id`, `name`, `address`, `is_active`  
**Casts:** `is_active` → boolean  
**Relationships:** `tenant()` → BelongsTo Tenant  
**Note:** Plan 3 only. Max 3 branches.

---

### 11. AnalyticsEvent.php — ~50 lines
**Path:** `app/Models/AnalyticsEvent.php`  
**No UPDATED_AT**

**Fillable:** `tenant_id`, `event_type`, `reference_type`, `reference_id`, `user_ip`, `user_agent`, `referer`, `event_date`, `event_hour`

**Relationships:** `tenant()` → BelongsTo Tenant

---

### 12. AiDoc.php — ~130 lines
**Path:** `app/Models/AiDoc.php`

**Fillable:** `slug`, `title`, `product`, `content`, `source_file`  
**Note:** FULLTEXT index on (title, content)

**Custom Methods:**
- `search(string $query, int $limit, ?string $product): Collection` — MySQL FULLTEXT search
- `extractRelevantFragment(string $query, int $maxLength): string` — Keyword density extraction

---

### 13. AiChatLog.php — ~27 lines
**Path:** `app/Models/AiChatLog.php`

**Fillable:** `tenant_id`, `product`, `question`, `answer`, `helpful`  
**Casts:** `helpful` → integer  
**Relationships:** `tenant()` → BelongsTo Tenant

---

### 14. User.php — ~45 lines
**Path:** `app/Models/User.php`  
**Traits:** HasFactory, Notifiable

**Fillable:** `name`, `email`, `email_verified_at`, `password`, `remember_token`  
**Hidden:** `password`, `remember_token`  
**Casts:** `email_verified_at` → datetime, `password` → hashed  
**Relationships:** `tenants()` → HasMany Tenant

---

## Patterns & Observations

1. **All models use `declare(strict_types=1)`** ✅
2. **Consistent `$fillable` usage** — no `$guarded = []`
3. **JSON columns for flexibility** — TenantCustomization uses 5 JSON columns for extensible config
4. **Tenant hub pattern** — Tenant model connects to 9 other models
5. **Missing factories** — Only UserFactory exists. TenantFactory, ProductFactory, ServiceFactory missing (blocks testing)
6. **No SoftDeletes** — None of the models use soft deletes (permanent deletion everywhere)
7. **No global scopes** — Tenant filtering is done in controllers, not via model scopes
8. **Dual pricing** — Product stores both `price_usd` and `price_bs`
