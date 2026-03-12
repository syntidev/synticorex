# DATABASE — SYNTIweb

**Audit Date:** 2026-03-11  
**Total Migrations:** 38  
**Total Tables:** 18  
**Total Seeders:** 9  
**Total Factories:** 1

---

## Table Summary

| Table | Migration | Rows (est.) | FK to | Purpose |
|-------|-----------|-------------|-------|---------|
| users | 0001_01_01_000000 | Low | — | Platform users |
| password_reset_tokens | 0001_01_01_000000 | Low | — | Password resets |
| sessions | 0001_01_01_000000 | Variable | users | Active sessions |
| cache | 0001_01_01_000001 | N/A | — | Cache storage |
| cache_locks | 0001_01_01_000001 | N/A | — | Cache locks |
| jobs | 0001_01_01_000002 | Low | — | Queue jobs |
| job_batches | 0001_01_01_000002 | Low | — | Job batches |
| failed_jobs | 0001_01_01_000002 | Low | — | Failed jobs |
| plans | 2025_02_11_000003 | 9 | — | Subscription plans |
| tenants | 2025_02_11_000004 | Low-Medium | users, plans | Core tenant data |
| products | 2025_02_11_000005 | Medium | tenants | Tenant products |
| services | 2025_02_11_000006 | Medium | tenants | Tenant services |
| tenant_customization | 2025_02_11_000007 | 1:1 with tenants | tenants | Visual config |
| analytics_events | 2025_02_11_000008 | High | tenants | Page view tracking |
| dollar_rates | 2025_02_11_000009 | Low | — | Exchange rates |
| invoices | 2025_02_11_000010 | Low | tenants | Billing records |
| color_palettes | 2025_02_11_000011 | 17 | — | Theme color presets |
| product_images | 2025_02_11_000013 | Medium | products | Gallery images |
| tenant_branches | 2025_02_11_000014 | Low | tenants | Branch locations |
| ai_docs | 2026_03_08_000001 | ~25 | — | AI knowledge base |
| ai_chat_logs | 2026_03_08_000001 | Growing | tenants | AI conversation logs |

---

## Detailed Table Schemas

### tenants (central table)
```sql
id                  BIGINT UNSIGNED PK AUTO_INCREMENT
user_id             BIGINT UNSIGNED FK → users(id) CASCADE DELETE
plan_id             BIGINT UNSIGNED FK → plans(id) RESTRICT DELETE
subdomain           VARCHAR(100) UNIQUE NULLABLE
base_domain         VARCHAR(100) NULLABLE
custom_domain       VARCHAR(255) UNIQUE NULLABLE
domain_verified     BOOLEAN DEFAULT false
business_name       VARCHAR(128)
business_segment    VARCHAR(50) NULLABLE
slogan              TEXT NULLABLE
description         TEXT NULLABLE
phone               VARCHAR(20) NULLABLE
whatsapp_sales      VARCHAR(20) NULLABLE
whatsapp_support    VARCHAR(20) NULLABLE
whatsapp_active     ENUM('sales','support') DEFAULT 'sales'
email               VARCHAR(255) NULLABLE
address             TEXT NULLABLE
city                VARCHAR(100) NULLABLE
country             VARCHAR(100) DEFAULT 'Venezuela'
business_hours      JSON NULLABLE
is_open             BOOLEAN DEFAULT true
edit_pin            VARCHAR(255)  -- hashed
currency_display    VARCHAR(10) DEFAULT 'both'
color_palette_id    TINYINT UNSIGNED DEFAULT 1
meta_title          VARCHAR(255) NULLABLE
meta_description    TEXT NULLABLE
meta_keywords       TEXT NULLABLE
status              VARCHAR(20) DEFAULT 'active'  -- active|frozen|archived
trial_ends_at       TIMESTAMP NULLABLE
subscription_ends_at TIMESTAMP NULLABLE
plan_activated_at   TIMESTAMP NULLABLE
settings            JSON NULLABLE
industry_segment    ENUM('FOOD_BEVERAGE','RETAIL','HEALTH_WELLNESS',
                         'PROFESSIONAL_SERVICES','ON_DEMAND') NULLABLE
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEXES:
  UNIQUE (subdomain)
  UNIQUE (custom_domain)
  INDEX (subdomain, base_domain)
  INDEX (status)
  INDEX (status, subscription_ends_at) AS idx_tenant_status_expiry
  INDEX (industry_segment)
```

### tenant_customization
```sql
id                      BIGINT UNSIGNED PK
tenant_id               BIGINT UNSIGNED UNIQUE FK → tenants(id) CASCADE
logo_filename           VARCHAR(255) NULLABLE
hero_main_filename      VARCHAR(255) NULLABLE
hero_secondary_filename VARCHAR(255) NULLABLE
hero_tertiary_filename  VARCHAR(255) NULLABLE
hero_image_4_filename   VARCHAR(255) NULLABLE
hero_image_5_filename   VARCHAR(255) NULLABLE
about_image_filename    VARCHAR(255) NULLABLE
hero_layout             ENUM('fullscreen','split','gradient','cards') DEFAULT 'fullscreen'
about_text              TEXT NULLABLE
theme_slug              VARCHAR(50) DEFAULT 'light'
social_networks         JSON NULLABLE
payment_methods         JSON NULLABLE
faq_items               JSON NULLABLE
cta_title               VARCHAR(255) NULLABLE
cta_subtitle            TEXT NULLABLE
cta_button_text         VARCHAR(100) NULLABLE
cta_button_link         TEXT NULLABLE
visual_effects          JSON NULLABLE  -- Contains sections_order + sections_config
content_blocks          JSON NULLABLE  -- Free-form per-section content
header_message          VARCHAR(255) NULLABLE
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

### products
```sql
id              BIGINT UNSIGNED PK
tenant_id       BIGINT UNSIGNED FK → tenants(id) CASCADE
name            VARCHAR(255)
description     TEXT NULLABLE
price_usd       DECIMAL(10,2) NULLABLE
price_bs        DECIMAL(15,2) NULLABLE
image_filename  VARCHAR(255) NULLABLE
image_url       VARCHAR(500) NULLABLE  -- External URLs (Unsplash demos)
position        TINYINT UNSIGNED DEFAULT 0
is_active       BOOLEAN DEFAULT true
is_featured     BOOLEAN DEFAULT false
badge           VARCHAR(20) NULLABLE  -- 'hot','new','promo'
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEXES:
  INDEX (tenant_id, is_active)
```

### services
```sql
id              BIGINT UNSIGNED PK
tenant_id       BIGINT UNSIGNED FK → tenants(id) CASCADE
name            VARCHAR(255)
description     TEXT NULLABLE
icon_name       VARCHAR(50) NULLABLE
image_filename  VARCHAR(255) NULLABLE
overlay_text    VARCHAR(100) NULLABLE
cta_text        VARCHAR(50) DEFAULT 'Más información'
cta_link        TEXT NULLABLE
position        TINYINT UNSIGNED DEFAULT 0
is_active       BOOLEAN DEFAULT true
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEXES:
  INDEX (tenant_id, is_active)
```

### plans
```sql
id                      BIGINT UNSIGNED PK
slug                    VARCHAR(32) UNIQUE
blueprint               VARCHAR(20) DEFAULT 'studio'
name                    VARCHAR(64)
price_usd               DECIMAL(10,2) UNSIGNED
products_limit          SMALLINT UNSIGNED NULLABLE
services_limit          SMALLINT UNSIGNED DEFAULT 3
images_limit            SMALLINT UNSIGNED NULLABLE
color_palettes          TINYINT UNSIGNED DEFAULT 5
social_networks_limit   TINYINT UNSIGNED NULLABLE
show_dollar_rate        BOOLEAN DEFAULT false
show_header_top         BOOLEAN DEFAULT false
show_about_section      BOOLEAN DEFAULT false
show_payment_methods    BOOLEAN DEFAULT false
show_faq                BOOLEAN DEFAULT false
show_cta_special        BOOLEAN DEFAULT false
analytics_level         ENUM('basic','medium','advanced') DEFAULT 'basic'
seo_level               ENUM('basic','medium','advanced') DEFAULT 'basic'
whatsapp_numbers        TINYINT UNSIGNED DEFAULT 1
whatsapp_hour_filter    BOOLEAN DEFAULT false
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

### analytics_events
```sql
id              BIGINT UNSIGNED PK
tenant_id       BIGINT UNSIGNED FK → tenants(id) CASCADE
event_type      VARCHAR(50)  -- page_view, whatsapp_click, product_click, service_click
reference_type  VARCHAR(50) NULLABLE  -- product, service
reference_id    BIGINT UNSIGNED NULLABLE
user_ip         VARCHAR(45) NULLABLE  -- SHA-256 hashed
user_agent      TEXT NULLABLE
referer         TEXT NULLABLE
event_date      DATE
event_hour      TINYINT UNSIGNED  -- 0-23
created_at      TIMESTAMP

INDEXES:
  INDEX (tenant_id, event_date)
  INDEX (tenant_id, event_type)
```

### product_images
```sql
id              BIGINT UNSIGNED PK
product_id      BIGINT UNSIGNED FK → products(id) CASCADE
image_filename  VARCHAR(255)
position        TINYINT UNSIGNED DEFAULT 0  -- 0=second, 1=third
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEXES:
  INDEX (product_id, position)
```

### dollar_rates
```sql
id              BIGINT UNSIGNED PK
rate            DECIMAL(10,4)
source          VARCHAR(50) DEFAULT 'BCV'
currency_type   VARCHAR(3) DEFAULT 'USD'  -- USD, EUR
effective_from  TIMESTAMP
effective_until TIMESTAMP NULLABLE
is_active       BOOLEAN DEFAULT true
created_at      TIMESTAMP

INDEXES:
  INDEX (is_active, effective_from)
```

### invoices
```sql
id                  BIGINT UNSIGNED PK
tenant_id           BIGINT UNSIGNED FK → tenants(id) CASCADE
invoice_number      VARCHAR(50) UNIQUE
amount_usd          DECIMAL(10,2)
currency            VARCHAR(10) DEFAULT 'USD'
payment_method      VARCHAR(50) NULLABLE  -- zelle, transferencia, efectivo
payment_reference   VARCHAR(100) NULLABLE
payment_date        TIMESTAMP NULLABLE
pdf_filename        VARCHAR(255) NULLABLE
status              VARCHAR(20) DEFAULT 'pending'  -- pending, paid, cancelled
period_start        DATE
period_end          DATE
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEXES:
  INDEX (tenant_id, status)
```

### color_palettes
```sql
id              TINYINT UNSIGNED PK AUTO_INCREMENT
name            VARCHAR(100)
slug            VARCHAR(100) UNIQUE
primary_color   VARCHAR(7) NULLABLE
secondary_color VARCHAR(7) NULLABLE
accent_color    VARCHAR(7) NULLABLE
background_color VARCHAR(7) NULLABLE
text_color      VARCHAR(7) NULLABLE
min_plan_id     TINYINT UNSIGNED DEFAULT 1
category        VARCHAR(50) NULLABLE
description     VARCHAR(255) NULLABLE
text_muted      VARCHAR(20) NULLABLE
background_alt  VARCHAR(20) NULLABLE
button_bg       VARCHAR(20) NULLABLE
button_text     VARCHAR(20) NULLABLE
button_hover_bg VARCHAR(20) NULLABLE
link_color      VARCHAR(20) NULLABLE
link_hover      VARCHAR(20) NULLABLE
font_primary    VARCHAR(100) NULLABLE
font_secondary  VARCHAR(100) NULLABLE
segment_tags    TEXT NULLABLE
emotional_effect VARCHAR(255) NULLABLE
is_active       BOOLEAN DEFAULT true
created_at      TIMESTAMP
```

### tenant_branches
```sql
id          BIGINT UNSIGNED PK
tenant_id   BIGINT UNSIGNED FK → tenants(id) CASCADE
name        VARCHAR(150)
address     VARCHAR(500)
is_active   BOOLEAN DEFAULT true
created_at  TIMESTAMP
updated_at  TIMESTAMP

INDEXES:
  INDEX (tenant_id, is_active)
```

### ai_docs
```sql
id          BIGINT UNSIGNED PK
slug        VARCHAR(255) UNIQUE
title       VARCHAR(255)
product     VARCHAR(20)  -- shared, studio, food, cat
content     TEXT
source_file VARCHAR(255) NULLABLE
created_at  TIMESTAMP
updated_at  TIMESTAMP

INDEXES:
  FULLTEXT ft_search (title, content)
  INDEX (product)
```

### ai_chat_logs
```sql
id          BIGINT UNSIGNED PK
tenant_id   BIGINT UNSIGNED NULLABLE FK → tenants(id) SET NULL
product     VARCHAR(20) NULLABLE
question    TEXT
answer      TEXT
helpful     TINYINT NULLABLE  -- 1=helpful, 0=not helpful
created_at  TIMESTAMP
updated_at  TIMESTAMP
```

---

## Migration Timeline

| Phase | Period | Migrations | Changes |
|-------|--------|------------|---------|
| Foundation | Feb 2025 | 14 | Core tables: users, plans, tenants, products, services, customization, analytics, dollar_rates, invoices, color_palettes, product_images, tenant_branches |
| Schema Evolution | Feb 2026 | 8 | Extended color palettes, hero system, theme slug, plan lifecycle, sections_order fix, industry segment, about text/image |
| Feature Additions | Mar 2026 | 8 | Currency type, content blocks, WhatsApp active, header message, blueprint system, food/cat plans, AI docs/chat |
| Fixes | Mar-Apr 2026 | 4 | Nullable plan limits, hero images 4-5, sections_order Plan 2 fix |

---

## Seeders

| Seeder | Strategy | What it Creates |
|--------|----------|-----------------|
| DatabaseSeeder | Chain | Calls all below in order |
| PlansSeeder | updateOrCreate | 9 plans (3 studio + 3 food + 3 cat) |
| ColorPalettesSeeder | truncate + insert | 17 color palettes |
| DollarRatesSeeder | create | 1 initial rate (36.50 USD) |
| DemoDataSeeder | create | 2 demo tenants (TechStart + Boutique Eleganza) |
| AiDocSeeder | truncate + insert | ~25 knowledge base articles |
| TestingSeeder | create | 3 test tenants (1 per plan tier) |
| FlyonUIThemesSeeder | data only | Documents 32 themes (no table insert) |
| UpdateTenantsThemesSeeder | update | Maps theme slugs to existing tenants |

---

## Missing Database Elements

| Element | Impact | Priority |
|---------|--------|----------|
| **Factories** (Tenant, Product, Service) | Cannot run feature tests | HIGH |
| **categories table** | Products lack proper categorization (uses position/badge instead) | MEDIUM |
| **blog_posts table** | Blog section references exist but no table | LOW |
| **testimonials table** | Stored as JSON in tenant_customization | LOW (by design) |
| **users_tenants pivot** | No multi-tenant per user support | LOW |
| **audit_logs table** | No change tracking | MEDIUM |
| **notifications table** | No notification system | LOW |
