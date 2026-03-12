# BUSINESS MODULES — SYNTIweb

**Audit Date:** 2026-03-11

---

## Module Maturity Legend

| Level | Label | Description |
|-------|-------|-------------|
| ★★★★★ | COMPLETE | Fully implemented, tested, production-ready |
| ★★★★☆ | FUNCTIONAL | Works but has minor gaps |
| ★★★☆☆ | PARTIAL | Core features work, significant gaps |
| ★★☆☆☆ | SKELETAL | Basic structure exists, needs major work |
| ★☆☆☆☆ | STUB | Placeholder only, non-functional |
| ☆☆☆☆☆ | MISSING | Not implemented |

---

## Core Platform Modules

### 1. Authentication & Authorization — ★★★★☆ FUNCTIONAL
**Components:** Laravel Breeze, session auth, PIN system  
**What works:**
- User registration, login, logout
- Password reset, email verification
- Session-based authentication
- Tenant PIN verification for dashboard access

**Gaps:**
- No role-based access control (RBAC)
- No multi-user per tenant support
- PIN verification lacks rate limiting
- No OAuth/social login

---

### 2. Multitenancy — ★★★★☆ FUNCTIONAL
**Components:** IdentifyTenant middleware, Tenant model, subdomain + path + custom domain resolution  
**What works:**
- 3-way tenant resolution (subdomain, path, custom domain)
- Per-tenant storage isolation (`storage/tenants/{id}/`)
- Per-tenant theming (44 themes)
- Tenant status lifecycle (active → frozen → archived)
- Grace period management (30 days)

**Gaps:**
- `domain_verified` not checked in middleware
- No DNS verification flow for custom domains
- No tenant deletion cleanup (orphaned files)
- No tenant export/backup

---

### 3. Product Catalog — ★★★★★ COMPLETE
**Components:** Product model, ProductImage model, ProductController, DashboardController CRUD  
**What works:**
- Full CRUD (create, read, update, delete)
- Image upload with WebP conversion (800px, 2MB)
- Gallery images (Plan 3, max 2 additional per product)
- Dual pricing (USD + BS)
- Position ordering
- Active/featured toggles
- Badge system (hot, new, promo)
- Plan limit enforcement

---

### 4. Service Catalog — ★★★★★ COMPLETE
**Components:** Service model, ServiceController, DashboardController CRUD  
**What works:**
- Full CRUD
- Image + icon support
- CTA with custom text/link
- Overlay text (premium)
- Position ordering
- Active toggle
- Plan limit enforcement

---

### 5. Landing Page Engine — ★★★★★ COMPLETE
**Components:** TenantRendererController, 3 templates, 23 sections, 7 Schema.org schemas  
**What works:**
- 3 blueprint templates (studio, food, catalog)
- 23 reusable sections with plan-based gating
- Section reordering (drag-and-drop)
- Section visibility toggle
- Dynamic Schema.org structured data
- Custom palette override
- 44 Preline CSS themes
- SEO meta tags (title, description, OG)
- Hero with 5 image slots + 4 layouts

---

### 6. Dashboard — ★★★★☆ FUNCTIONAL
**Components:** DashboardController (1,538 lines), 39 view files  
**What works:**
- 7-tab navigation (Info, Products, Services, Design, Message, Analytics, Config)
- Full AJAX CRUD for all entities
- Real-time dollar rate display
- Plan expiry warnings
- PIN-based access

**Gaps:**
- Monolithic controller (god object)
- 2,400-line view file
- No undo/redo
- No autosave

---

### 7. Image Processing — ★★★★★ COMPLETE
**Components:** ImageUploadService, ImageUploadController (9 endpoints)  
**What works:**
- WebP conversion (90% quality)
- Max 800px resize
- 2MB upload limit
- Type validation (jpeg, png, gif, webp, svg)
- Per-tenant storage path
- Logo, hero (5 slots), product, service, gallery, about uploads
- Gallery image deletion

---

### 8. Currency System — ★★★★★ COMPLETE
**Components:** DollarRateService, DollarRate model, scheduled hourly fetch  
**What works:**
- USD + EUR rate fetching from DolarAPI.com
- Automatic propagation to tenant product prices
- 4 display modes: reference_only, bolivares_only, both_toggle, hidden
- Fallback rates (36.50 USD, 495 EUR)
- Cache-aware (5-minute TTL)

---

### 9. QR Code System — ★★★★☆ FUNCTIONAL
**Components:** QRService, QRTrackingController  
**What works:**
- SVG + PNG generation
- Tracking shortlinks
- Download endpoint

**Gaps:**
- No scan analytics dashboard
- No custom QR branding

---

### 10. Analytics — ★★★☆☆ PARTIAL
**Components:** AnalyticsEvent model, AnalyticsController  
**What works:**
- Event tracking (page_view, whatsapp_click, product_click, service_click)
- IP hashing (privacy-compliant)
- Date + hour bucketing
- Today's stats endpoint
- Basic reporting endpoint

**Gaps:**
- No analytics visualization in dashboard (sales-section exists but limited)
- No historical trend charts
- No conversion funnel
- No export functionality
- Tracking endpoint is unauthenticated (spammable)

---

### 11. Onboarding — ★★★★☆ FUNCTIONAL
**Components:** OnboardingController, 3 wizard views, selector  
**What works:**
- Blueprint selector (studio/food/cat)
- 3 specialized wizards
- Subdomain availability check
- Preview before publish
- Demo data seeding

**Gaps:**
- No resume/save-progress
- No guided tour after creation

---

### 12. Marketing Site — ★★★★☆ FUNCTIONAL
**Components:** MarketingController, 16 view files  
**What works:**
- Homepage with sections (hero, problem, solution, segments, stats, config, dashboard, plans, CTA)
- Individual product pages (studio, food, cat)
- Pricing comparison page

**Gaps:**
- No blog
- No testimonials/case studies
- No SEO landing pages

---

### 13. AI Assistant (SYNTI) — ★★★☆☆ PARTIAL
**Components:** SyntiHelpController, BytezProvider, AiDoc model, AiChatLog  
**What works:**
- Knowledge base with FULLTEXT search (~25 articles)
- AI query via Bytez API (Qwen3-8B)
- Response feedback (helpful/not helpful)
- Rate limiting (10/min public, 30/min auth)
- Chat logging

**Gaps:**
- External API dependency (no local model)
- No conversation history/context
- Limited knowledge base coverage
- No admin interface for knowledge management

---

## Food-Specific Modules (SyntiFood)

### 14. Menu Management — ★★★★☆ FUNCTIONAL
**Components:** MenuService, CategoriesController, ItemsController  
**What works:**
- JSON-based menu structure
- Category CRUD
- Item CRUD (with images)
- Menu rendering in food template

**Gaps:**
- No item modifiers/options
- No price variants (sizes)
- No allergen information

---

### 15. Order System (SyntiFood) — ★★★☆☆ PARTIAL
**Components:** ComandaService, ComandaController, CheckoutController  
**What works:**
- Cart → WhatsApp message generation
- Comanda PDF generation
- Basic checkout flow

**Gaps:**
- No order persistence (no orders table)
- No order history
- No order status tracking
- No payment integration
- WhatsApp-only (no online payment)

---

### 16. Business Hours — ★★★★★ COMPLETE
**Components:** BusinessHoursService, business_hours JSON column  
**What works:**
- Per-day open/close times
- Current open/closed status
- Next opening time calculation
- Dashboard management UI
- Public status display

---

## Billing Module

### 17. Invoicing — ★★☆☆☆ SKELETAL
**Components:** Invoice model, invoices table  
**What works:**
- Database schema exists
- Model with relationships

**Gaps:**
- No invoice generation logic
- No payment processing
- No subscription management
- No billing dashboard
- No email receipts

---

## Module Dependency Map

```
Authentication ──► Multitenancy ──► Product Catalog
                                ├── Service Catalog
                                ├── Landing Engine
                                ├── Dashboard ──► Image Processing
                                │              ├── Currency System
                                │              └── Analytics
                                ├── QR System
                                ├── Business Hours
                                ├── AI Assistant
                                └── Invoicing (skeletal)

Onboarding ──► Multitenancy (creates tenants)
Marketing  ──► (standalone, no tenant dependency)
```
