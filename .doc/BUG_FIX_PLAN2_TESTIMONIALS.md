## BUG FIX: Testimonials Navigation Not Working in Plan 2

### ROOT CAUSE
The TestingSeeder's `sections_order` was incomplete for all test tenants. It only included:
- products, services, contact, payment_methods, cta

But did **NOT** include:
- about (required for Plan 2+)
- testimonials (required for Plan 2+)  
- faq, branches (required for Plan 3)

When a Plan 2 tenant accessed the dashboard and the customization was created, it would inherit this incomplete sections_order. In `studio.blade.php`, sections are only rendered if:
```php
$shouldRender = $isVisible && $canAccess && $sectionName !== 'hero';
```

**The Bug:** 
- Navigation header renders the link because `canAccessSection('testimonials', 2)` returns true (Plan 2 allows it)
- But the testimonials section wasn't in `sections_order`, so it never rendered
- Result: Click link → no section exists → scroll goes nowhere

### SOLUTION

**1. Updated TestingSeeder** (`database/seeders/TestingSeeder.php`)
- Added plan-based sections_order generation
- Plan 1: products, services, contact, payment_methods, cta
- Plan 2+: adds about, testimonials
- Plan 3+: adds faq, branches

**2. Created Migration** (`database/migrations/2026_04_05_000000_fix_sections_order_for_plan2_testimonials.php`)
- Fixes existing Plan 2+ tenants with incomplete sections_order
- Compares saved sections against required sections for plan
- Updates visual_effects with complete list if missing any section

### VERIFICATION

✅ TestingSeeder now creates plan-aware sections_order
✅ Migration fixes existing incomplete records
✅ DemoDataSeeder tenants will use model defaults (which include all sections by plan)
✅ Future Plan 2 tenants will have testimonials in sections_order
✅ Navigation links and rendered sections will always match

### FILES MODIFIED
1. `database/seeders/TestingSeeder.php` - Updated sections_order logic
2. `database/migrations/2026_04_05_000000_fix_sections_order_for_plan2_testimonials.php` - NEW
