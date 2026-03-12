# SYSTEM GAPS — SYNTIweb

**Audit Date:** 2026-03-11  
**Focus:** Security vulnerabilities, infrastructure gaps, operational risks

---

## Gap Severity

| Severity | Label | SLA |
|----------|-------|-----|
| CRITICAL | Production blocker | Fix before deploy |
| HIGH | Significant risk | Fix within 1 week of deploy |
| MEDIUM | Moderate risk | Fix within 1 month |
| LOW | Minor issue | Fix when convenient |
| INFO | Best practice | Track for future |

---

## Security Gaps

### GAP-S01: Unprotected API Routes — CRITICAL
**Location:** `routes/api.php`  
**Description:** All 30+ REST API routes (tenant CRUD, product CRUD, service CRUD, AI helper) are publicly accessible without authentication. A comment indicates this is intentional for development: `// Protected routes (add auth:sanctum middleware in production)`.  
**Risk:** Anyone can create, modify, or delete all tenants, products, and services via the API.  
**Fix:** Wrap routes in `Route::middleware('auth:sanctum')->group()`.  
**Effort:** 1 hour

### GAP-S02: PIN Brute-Force Vulnerability — HIGH
**Location:** `TenantRendererController@verifyPin`  
**Description:** The PIN verification endpoint has no rate limiting. Tenant PINs are typically 4-6 digits, making them vulnerable to brute-force attacks.  
**Risk:** Unauthorized dashboard access via automated PIN guessing.  
**Fix:** Add `throttle:5,1` middleware (5 attempts per minute).  
**Effort:** 15 minutes

### GAP-S03: Custom Domain Without Verification — MEDIUM
**Location:** `app/Http/Middleware/IdentifyTenant.php`  
**Description:** The tenant resolution middleware queries custom domains without checking `domain_verified = true`. A tenant could set any domain (including domains they don't own) and the system would serve content for it.  
**Risk:** Domain impersonation, phishing potential.  
**Fix:** Add `->where('domain_verified', true)` to the custom domain query.  
**Effort:** 5 minutes

### GAP-S04: Error Pages Use CDN Tailwind — LOW
**Location:** `resources/views/errors/404.blade.php`, `500.blade.php`  
**Description:** Error pages load Tailwind CSS from a CDN instead of using `@vite()`. This means they depend on an external service and bypass CSP.  
**Risk:** Error pages may not render correctly if CDN is unreachable. CSP violation.  
**Fix:** Use `@vite(['resources/css/app.css'])` or inline minimal CSS.  
**Effort:** 15 minutes

### GAP-S05: Analytics Tracking Open to Abuse — MEDIUM
**Location:** `/api/analytics/track` (web.php)  
**Description:** The analytics event tracking endpoint is public with no rate limiting or validation. Bots could inflate analytics data.  
**Risk:** Unreliable analytics, disk usage from excessive records.  
**Fix:** Add throttle middleware, validate event types, add honeypot/captcha for non-JS clients.  
**Effort:** 1-2 hours

### GAP-S06: No CSRF on Some Public POST Endpoints — MEDIUM
**Location:** Several public POST routes in web.php  
**Description:** While Laravel includes CSRF protection by default, some endpoints may be excluded or called via AJAX without proper token handling. Review needed.  
**Risk:** CSRF attacks on toggle-status, toggle-whatsapp, verify-pin.  
**Fix:** Verify all POST endpoints validate CSRF tokens.  
**Effort:** 1 hour (audit)

---

## Infrastructure Gaps

### GAP-I01: No Deployment Configuration — HIGH
**Description:** No Dockerfile, docker-compose.yml, Forge config, vapor.yml, or any deployment automation. The project runs on Laragon locally with no path to production.  
**Risk:** Manual deployments are error-prone, non-reproducible.  
**Fix:** Create deployment configuration for target environment.  
**Effort:** 8-16 hours

### GAP-I02: No Database Backup Strategy — HIGH
**Description:** No backup script, no spatie/laravel-backup, no cron job for DB dumps. Tenant data could be permanently lost.  
**Risk:** Complete data loss on server failure.  
**Fix:** Implement automated daily backups (spatie/laravel-backup or mysqldump cron).  
**Effort:** 4-8 hours

### GAP-I03: No Error Tracking — HIGH
**Description:** Errors are logged to Laravel's daily log files only. No Sentry, Bugsnag, Flare, or equivalent for alerting.  
**Risk:** Production errors go unnoticed until users report them.  
**Fix:** Integrate Sentry or Laravel Flare.  
**Effort:** 2-4 hours

### GAP-I04: No Health Monitoring — MEDIUM
**Description:** No health check endpoint, no uptime monitoring, no resource usage alerts.  
**Risk:** Platform downtime without notification.  
**Fix:** Add `/health` endpoint + external monitoring (UptimeRobot, BetterStack).  
**Effort:** 2-4 hours

### GAP-I05: Queue System Untested — MEDIUM
**Description:** Queue driver is set to `database` but no jobs are dispatched in the codebase. Image processing, email sending, and rate propagation all run synchronously.  
**Risk:** Slow request times under load (image processing blocks response).  
**Fix:** Move heavy operations (image WebP conversion, rate propagation) to queued jobs.  
**Effort:** 4-8 hours

### GAP-I06: No SSL/TLS Configuration — MEDIUM
**Description:** No SSL certificate provisioning for custom domains. Multi-tenant custom domains require wildcard or per-domain SSL.  
**Risk:** Custom domain tenants can't use HTTPS.  
**Fix:** Implement Let's Encrypt automation (Caddy, Certbot, or Cloudflare).  
**Effort:** 8-16 hours

---

## Data Integrity Gaps

### GAP-D01: No Soft Deletes — MEDIUM
**Description:** None of the 14 models use SoftDeletes. Deletion is permanent.  
**Risk:** Accidental data loss with no recovery. No audit trail of deletions.  
**Fix:** Add SoftDeletes to Tenant, Product, Service models.  
**Effort:** 2-3 hours

### GAP-D02: No Database Transactions in Multi-Step Operations — MEDIUM
**Description:** Operations like tenant creation (involves Tenant + TenantCustomization + initial products) don't appear to use `DB::transaction()`.  
**Risk:** Partial data creation on failure (tenant exists but no customization).  
**Fix:** Wrap multi-model operations in transactions.  
**Effort:** 2-4 hours

### GAP-D03: Orphaned Files on Delete — LOW
**Description:** When a product or service is deleted, the associated image files in `storage/tenants/{id}/` are not cleaned up.  
**Risk:** Storage bloat over time.  
**Fix:** Add file cleanup in delete methods or use model observer.  
**Effort:** 2-3 hours

### GAP-D04: No Data Export — LOW
**Description:** Tenants cannot export their data (products, services, analytics).  
**Risk:** Vendor lock-in concern, GDPR/privacy compliance gap.  
**Fix:** Add CSV/JSON export endpoints.  
**Effort:** 4-6 hours

---

## Testing Gaps

### GAP-T01: Minimal Test Coverage — HIGH
**Description:** 11 test files exist, mostly Breeze defaults. No feature tests for core business logic (CRUD, multitenancy, landing pages).  
**Risk:** Regressions go undetected, refactoring is risky.  
**Coverage estimate:** <10%

**Missing test categories:**
| Category | Tests Needed |
|----------|-------------|
| Tenant resolution | Does subdomain, path, custom domain resolve correctly? |
| Product CRUD | Create, update, delete with plan limits |
| Service CRUD | Create, update, delete with plan limits |
| Image upload | WebP conversion, size limits |
| Dashboard access | PIN verification, tenant ownership |
| Landing rendering | Template selection, section ordering |
| Currency system | Rate fetch, propagation, display modes |
| Subscription | Expiry, freeze, archive lifecycle |

### GAP-T02: Missing Factories — HIGH
**Description:** Only UserFactory exists. Cannot generate test data for core models.  
**Fix:** Create TenantFactory, ProductFactory, ServiceFactory, etc.  
**Effort:** 2-3 hours

### GAP-T03: No Integration Tests — MEDIUM
**Description:** No tests verify the full request lifecycle (HTTP → Controller → Service → Model → Response).  
**Fix:** Add Feature tests using RefreshDatabase.  
**Effort:** 8-16 hours

---

## Operational Gaps

### GAP-O01: No CI/CD Pipeline — HIGH
**Description:** No GitHub Actions, GitLab CI, or equivalent. Deployment is manual.  
**Risk:** Human error in deployment, no automated testing gate.

### GAP-O02: No Environment Documentation — MEDIUM
**Description:** `.env.example` exists but no deployment guide, no runbook, no architecture decision records (ADRs).  
**Risk:** Bus factor — only original developer knows deployment procedure.

### GAP-O03: No Performance Baselines — LOW
**Description:** No load testing, no response time benchmarks, no query performance analysis.  
**Risk:** Unknown breaking point under production load.

---

## Gap Summary Matrix

| ID | Gap | Severity | Category | Effort |
|----|-----|----------|----------|--------|
| S01 | Unprotected API | CRITICAL | Security | 1h |
| S02 | PIN brute-force | HIGH | Security | 15m |
| S03 | Domain verification | MEDIUM | Security | 5m |
| S04 | CDN in error pages | LOW | Security | 15m |
| S05 | Analytics abuse | MEDIUM | Security | 1-2h |
| S06 | CSRF audit | MEDIUM | Security | 1h |
| I01 | No deployment | HIGH | Infrastructure | 8-16h |
| I02 | No backups | HIGH | Infrastructure | 4-8h |
| I03 | No error tracking | HIGH | Infrastructure | 2-4h |
| I04 | No monitoring | MEDIUM | Infrastructure | 2-4h |
| I05 | Sync queue | MEDIUM | Infrastructure | 4-8h |
| I06 | No SSL for domains | MEDIUM | Infrastructure | 8-16h |
| D01 | No soft deletes | MEDIUM | Data | 2-3h |
| D02 | No transactions | MEDIUM | Data | 2-4h |
| D03 | Orphaned files | LOW | Data | 2-3h |
| D04 | No data export | LOW | Data | 4-6h |
| T01 | Minimal tests | HIGH | Testing | 20-40h |
| T02 | Missing factories | HIGH | Testing | 2-3h |
| T03 | No integration tests | MEDIUM | Testing | 8-16h |
| O01 | No CI/CD | HIGH | Operations | 8-16h |
| O02 | No runbook | MEDIUM | Operations | 4-8h |
| O03 | No perf baselines | LOW | Operations | 4-8h |
