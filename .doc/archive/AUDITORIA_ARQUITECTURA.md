Auditoría Arquitectónica SYNTIweb - 25 Feb 2026
1. Secciones en MATRIZ_FEATURES (source of truth)
Plan 1: hero, products, services, contact, payment_methods, cta, footer (7)
Plan 2: Plan 1 + about, testimonials (9)
Plan 3: Plan 2 + faq, branches (11)
2. Secciones en Panel "Orden de Secciones"
PENDIENTE: tomar screenshot y contar
3. Secciones en base.blade.php (switch cases)
PENDIENTE: abrir archivo y contar
4. Secciones en TenantCustomization canAccessSection
PENDIENTE: revisar método
GAPS CRÍTICOS DETECTADOS

CTA renderiza pero NO aparece en panel
Sucursales habilitada en panel pero NO renderiza
Parpadeo en footer

DECISIÓN

Crear config/syntiweb.php como única fuente de verdad
Costo estimado: $15-20 Opus cuando haya presupuesto