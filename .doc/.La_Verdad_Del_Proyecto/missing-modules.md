# MISSING MODULES — SYNTIweb

**Audit Date:** 2026-03-11  
**Assessment:** What the platform needs but doesn't have yet

---

## Priority Legend

| Priority | Label | Meaning |
|----------|-------|---------|
| P0 | BLOCKER | Must have before production launch |
| P1 | CRITICAL | Needed within first quarter of operation |
| P2 | IMPORTANT | Needed for growth/scaling |
| P3 | NICE-TO-HAVE | Would improve platform but not urgent |

---

## P0 — Production Blockers

### 1. API Authentication Layer
**What's missing:** All 30+ API routes are publicly accessible  
**Required:** Sanctum token-based auth for API routes  
**Scope:** 1-2 hours. Wrap api.php routes in `auth:sanctum` middleware group.  
**Dependencies:** Laravel Sanctum (already included with Breeze)

### 2. Rate Limiting for Sensitive Endpoints
**What's missing:** PIN verification, analytics tracking, and some public endpoints lack rate limiting  
**Required:**
- PIN: `throttle:5,1` (5 attempts/minute)
- Analytics: `throttle:60,1` (60 events/minute per IP)
- Status toggles: `throttle:10,1`
**Scope:** 30 minutes

### 3. Admin Panel
**What's missing:** No superadmin interface for platform management  
**Required:**
- Tenant listing and management
- User management
- Plan management
- Revenue dashboard
- System health monitoring
**Scope:** 40-60 hours (or integrate Laravel Nova/Filament)

---

## P1 — First Quarter Needs

### 4. Subscription & Billing System
**What's missing:** Invoice model exists but no billing logic  
**Required:**
- Plan upgrade/downgrade flow
- Payment processing (Zelle, bank transfer, Binance)
- Invoice generation (PDF)
- Payment confirmation workflow
- Subscription renewal reminders
- Grace period enforcement (partially exists in Artisan command)
**Scope:** 60-80 hours

### 5. Email Notification System
**What's missing:** No transactional emails beyond Breeze auth  
**Required:**
- Welcome email on registration
- Tenant creation confirmation
- Subscription expiry warnings (7 days, 3 days, 1 day)
- Payment confirmation
- Password change notification
- Weekly analytics digest
**Scope:** 15-20 hours

### 6. Error Tracking / Monitoring
**What's missing:** No external error tracking (Sentry, Bugsnag, etc.)  
**Required:**
- Exception tracking with context
- Performance monitoring
- Alert on critical errors
- Dashboard for error trends
**Scope:** 2-4 hours (SaaS integration) or 20+ hours (self-hosted)

### 7. Backup Strategy
**What's missing:** No database backup, no file backup  
**Required:**
- Automated daily DB backups
- Tenant file storage backup
- Backup retention policy
- Restore procedure documentation
**Scope:** 4-8 hours (using spatie/laravel-backup)

### 8. CI/CD Pipeline
**What's missing:** No deployment automation  
**Required:**
- Automated testing on push
- Staging environment deployment
- Production deployment with rollback
- Database migration safety checks
**Scope:** 8-16 hours

---

## P2 — Growth Features

### 9. Audit Log / Activity Tracking
**What's missing:** No record of who changed what  
**Required:**
- Log all CRUD operations
- Track dashboard actions with timestamps
- IP + user agent logging
- Admin audit trail
**Scope:** 8-12 hours (using spatie/laravel-activitylog)

### 10. Blog System
**What's missing:** Referenced in marketing but no implementation  
**Required:**
- Blog post CRUD for marketing site
- Rich text editor
- SEO meta per post
- Category/tag system
- RSS feed
**Scope:** 20-30 hours

### 11. Product Categories
**What's missing:** Products use `position` + `badge` instead of categories  
**Required:**
- Categories table with tenant_id
- Product-category relationship
- Category-based filtering in landing pages
- Category management in dashboard
**Scope:** 8-12 hours

### 12. Multi-language Support
**What's missing:** All content is hardcoded in Spanish  
**Required:**
- Laravel localization setup
- Spanish + English at minimum
- Tenant-facing content language selection
- Translatable product/service names
**Scope:** 30-40 hours (significant effort)

### 13. Feature Flags
**What's missing:** No feature toggle system  
**Required:**
- Per-tenant feature toggles
- A/B testing capability
- Gradual rollout support
**Scope:** 8-12 hours  

### 14. White-Label System
**What's missing:** DB column exists (`white_label`) but no implementation  
**Required:**
- Custom favicon upload/serve
- Custom "Powered by" removal
- Custom domain SSL provisioning
- Billing for white-label add-on
**Scope:** 15-20 hours

---

## P3 — Nice-to-Have

### 15. Webhook System
**What's missing:** No event-driven integrations  
**Would provide:**
- Notify external systems on tenant events
- Integration with Zapier/Make
- Custom webhook endpoints per tenant

### 16. API Rate/Usage Dashboard
**What's missing:** No visibility into API usage  
**Would provide:**
- Per-tenant API call tracking
- Rate limit visualization
- Usage-based billing foundation

### 17. File Manager
**What's missing:** No media library  
**Would provide:**
- Browse all uploaded images
- Bulk delete/organize
- Storage usage per tenant
- Image optimization queue

### 18. SEO Tools
**What's missing:** Basic SEO exists but no advanced tools  
**Would provide:**
- Sitemap.xml generation per tenant
- robots.txt per tenant
- SEO score/audit
- Google Search Console integration

### 19. Template Marketplace
**What's missing:** Fixed 3 templates (studio/food/cat)  
**Would provide:**
- Community/marketplace templates
- Template preview
- One-click template switch
- Custom template builder

### 20. Mobile App (PWA)
**What's missing:** No mobile-specific interface  
**Would provide:**
- Tenant dashboard as PWA
- Push notifications
- Offline support for menu browsing
- Add to homescreen

---

## Module Priority Roadmap

```
PHASE 0 — Pre-Launch (P0)
├── API Authentication (1-2h)
├── Rate Limiting (30min)
└── Admin Panel (40-60h)

PHASE 1 — Launch + 90 Days (P1)
├── Subscription & Billing (60-80h)
├── Email Notifications (15-20h)
├── Error Tracking (2-4h)
├── Backup Strategy (4-8h)
└── CI/CD Pipeline (8-16h)

PHASE 2 — Growth (P2)
├── Audit Logging (8-12h)
├── Blog System (20-30h)
├── Product Categories (8-12h)
├── Feature Flags (8-12h)
└── White-Label (15-20h)

PHASE 3 — Maturity (P3)
├── Webhook System
├── API Dashboard
├── File Manager
├── SEO Tools
├── Template Marketplace
└── PWA
```
