# NEXT SESSION — Semana 5, Día 2 (Sábado)

**Fecha:** 1 Mar 2026  
**Duración estimada:** 6-8h  
**Focus:** Landing refinement + Planes desglose + Comparativas + Logo  
**Estado actual:** 95% completado (19/20 tareas Semana 5)

---

## 🎯 PRIORIDADES CRÍTICAS (EN ORDEN)

### 1. LOGO MINIMALISTA INTEGRACIÓN ⚡
**Status:** Pendiente (archivo local en progreso)
- [ ] Integrar logo en navbar (fijo, no flotante)
- [ ] Logo en hero section (grande, con breathing animation)
- [ ] Responsive: desktop (full) → mobile (reduced)
- [ ] Color: blanco/primario según theme
- [ ] Test en todos los temas FlyonUI (17)

**Archivos a tocar:**
- `resources/views/landing/sections/hero.blade.php`
- `resources/views/marketing/sections/hero.blade.php` (si existe)
- CSS: logo animation (fade-in, pulse, glow opcional)

---

### 2. LANDING SYNTIWEB — COPY B2H FINAL ✍️
**Status:** Structure 100%, Copy 80% (eliminar jerga técnica)
- [ ] Reescribir **Hero:** "¿Tu negocio está en Google?" → Sin mencionar "schema"
- [ ] Reescribir **Problema:** Pain points reales (sin "meta tags")
- [ ] Reescribir **Segmentos:** "¿Tú eres...?" → Reconocimiento emocional
- [ ] Reescribir **Valor:** "Gana $X más/mes" → ROI claro, sin tecnicismos
- [ ] Reescribir **Planes:** Comparativa visual vs competencia (CRÍTICO)
- [ ] Reescribir **CTA:** "Crea gratis" → Sin presión

**Reemplazos clave (B2H):**
```
❌ "Schema.org automático" → ✅ "Google entiende tu negocio"
❌ "Feature gating" → ✅ "Herramientas nuevas conforme creces"
❌ "Meta tags dinámicos" → ✅ "Apareces en búsquedas"
❌ "Responsive design" → ✅ "Se ve bien en celular y PC"
❌ "Blueprint específico" → ✅ "Tu tipo de negocio"
❌ "Analytics en tiempo real" → ✅ "Ves cuántos clientes tienes"
```

---

### 3. PÁGINA SEPARADA: PLANES + DESGLOSE COMPLETO 📊
**Status:** Pendiente (crear nueva página)
- [ ] Crear `marketing/planes-desglose.blade.php` O `landing/planes-detalle.blade.php`
- [ ] 3 cards expandibles (Plan 1, 2, 3)
- [ ] Cada card: features completos + iconos + badges de exclusividad
- [ ] Grid comparativo: Oportunidad vs Crecimiento vs Visión
- [ ] Tabla de features (20+ filas) con checkmarks/X visual
- [ ] "Qué obtienes exactamente" narrativa
- [ ] Pricing: transparent ($/mes y €/año opciones)
- [ ] CTA por plan: "Empezar", "Actualizar", "Escalar"

**Estructura sugerida:**
```
Hero: "Elige tu Camino"
└─ 3 Plan Cards (expandible)
   ├─ Features + Badges + Límites
   ├─ CTA + Comparativa
   └─ FAQ por plan

Tabla Comparativa: Feature Matrix
└─ 20+ features × 3 planes
   ├─ Checkmarks/X visuales
   ├─ Badges de exclusividad
   └─ Savings highlighted

Social Proof:
└─ "¿Por qué Plan 3?" testimonios
```

**Ruta:** `/planes-detalle` (accesible desde navbar + landing main)

---

### 4. COMPARATIVA DE PRECIOS (COMPETENCIA VS SYNTIWEB) 🎯
**Status:** Pendiente (generar datos realistas)
- [ ] Investigar precios reales:
  - Wix: $99-$500/año
  - Squarespace: $120-$480/año
  - Shopify: $39-$299/mes
  - Weebly: $50-$500/año
  - WordPress hosted: $120-$300/año
  - Agencias locales: $200-$1000/mes
- [ ] Crear tabla comparativa:
  - **SYNTIweb:** $49-159/mes ($588-1908/año)
  - **Competencia:** $99-1000+/mes
- [ ] Destacar ventajas SYNTIweb:
  - ✅ Hecho para Venezuela (BCV automático, tasa real)
  - ✅ QR dinámico + Analytics
  - ✅ Blueprints específicos por industria
  - ✅ SEO automático (Plan 1+)
  - ✅ WhatsApp nativo (no plugin costoso)
  - ✅ Sin contrato, sin setup fee
- [ ] Visual: Savings calculator ("Ahorras $X/año")
- [ ] Posicionar como "domina tu competencia a mejor precio"

**Sección propuesta:**
```
Hero: "Estás Pagando de Más"
└─ Comparativa visual
   ├─ Precio vs Funcionalidades
   ├─ Ahorros SYNTIweb
   └─ "¿Por qué pagar más si..."

Feature Parity Table:
└─ SYNTIweb vs Agencia vs Otros SaaS
   ├─ Precio ($/mes)
   ├─ Setup time
   ├─ SEO (automático)
   ├─ Analytics
   ├─ Venezuela-specific
   ├─ WhatsApp
   └─ Support
```

**Ruta:** `/comparativa` O sección dentro de `/planes-detalle`

---

### 5. PANEL FLOTANTE — DESIGN HARMONY 🎨
**Status:** Pendiente (refactor visual)
- [ ] Aplicar design system elevado (gradientes, shadows)
- [ ] Integración con dashboard visual
- [ ] Animaciones suaves (no agresivas)
- [ ] Accesibilidad (contraste, keyboard nav)
- [ ] Mobile-first responsive
- [ ] Coherencia color con landing + dashboard

**Archivos:**
- `resources/views/landing/partials/floating-panel.blade.php`
- CSS: upgrade shadows, gradients, animations
- Validar que no tenga jerga técnica en labels

---

### 6. IMÁGENES/MOCKUPS DEL PRODUCTO 📸
**Status:** Pendiente (guardar secreto, mostrar belleza)
- [ ] Screenshot landing restaurante (exemplo visual)
- [ ] Screenshot landing mecánico (exemplo visual)
- [ ] Screenshot dashboard (con blur en datos sensibles)
- [ ] Phone mockup rendering landing (device frame)
- [ ] Dashboard preview (3 segmentos diferentes side-by-side)
- [ ] Radar animado (gif corto, 3-5s)

**Estrategia:** 
- Mostrar BELLEZA sin revelar código/arquitectura
- Usar mockups profesionales (phone frames, desktop frames)
- Blur datos sensibles (números, emails)
- Enfatizar que "esto es TU landing, pero hermosa"

---

### 7. DOCUMENTACIÓN PARA SOPORTE (WIKI) 📚
**Status:** Pendiente (crear base soporte usuario)
- [ ] `docs/USUARIO_INICIO.md` (How to get started - 5 pasos)
- [ ] `docs/BLUEPRINT_GUIDE.md` (Qué es Blueprint, cómo funciona, elige tu tipo)
- [ ] `docs/PLANES_EXPLICADOS.md` (Plan 1 vs 2 vs 3, feature matrix, upgrade path)
- [ ] `docs/SEO_AUTOMATICO.md` (Cómo Google ve tu negocio, sin tecnicismos)
- [ ] `docs/PREGUNTAS_FRECUENTES.md` (FAQ usuario - 20+ preguntas)
- [ ] `docs/TROUBLESHOOTING.md` (Problemas + soluciones simples)
- [ ] `docs/GLOSARIO.md` (Términos SYNTIweb explicados fácilmente)

**Tono:** Amigable, sin jerga, paso a paso como para abuela

---

## 📋 TAREAS SECUNDARIAS (Si hay tiempo)

- [ ] Animar ROI calculator en sección "Valor"
- [ ] Integrar Calendly para agendar demos
- [ ] Lead magnet: "Guía SEO para pequeños negocios" PDF descargable
- [ ] Email sequence template (Formspree → Email welcome)
- [ ] Mobile nav collapse optimizado (hamburger menu responsive)
- [ ] Lighthouse score 95+ (performance + SEO + accessibility)
- [ ] A/B test CTA colors (azul vs púrpura vs verde)

---

## 🔄 WORKFLOW RECOMENDADO

**Orden de ejecución:**

1. **Logo integración** (30 min) → Visual coherence
2. **Copy B2H refinement** (1h) → Narrativa pura, sin jerga
3. **Planes desglose page** (2h) → Feature matrix completa + CTA
4. **Comparativa competencia** (1.5h) → Posicionamiento estratégico
5. **Panel flotante harmony** (1h) → Design consistency
6. **Mockups/imágenes** (1.5h) → Eye candy profesional
7. **Documentación soporte** (1h) → User enablement, wiki

**Total: ~8.5h (distribuido en 6-8h sesión)**

---

## 💡 IDEAS NUEVAS DE CARLOS (CAPTURADAS)

✅ **CRÍTICAS:**
- Sección Planes **APARTE** con desglose detallado (no inline)
- Comparativa de precios vs competencia (estrategia "domina competencia sin overpagar")
- Logo minimalista integrado en todo (navbar + hero)
- Página landing separada para Planes (mejor SEO, compartible)

💡 **NICE TO HAVE:**
- ROI calculator interactivo (slider: "Si tienes X clientes...")
- Video demo 30s del producto en acción
- Testimonios de beta testers
- Heatmap de curiosidad (qué secciones mantienen usuarios enganchados)
- Chat bot para preguntas frecuentes (Intercom o similar)

---

## 📊 MÉTRICAS A ALCANZAR (FINAL)

- [ ] Landing performance: **<3s load time** (Lighthouse)
- [ ] Lighthouse: **95+ (all categories)**
- [ ] Conversion funnel: **Clear CTA path** (no confusion)
- [ ] B2H clarity: **0 technical jargon in final copy**
- [ ] Mobile responsiveness: **100% across 3 breakpoints** (mobile/tablet/desktop)
- [ ] Visual coherence: **Logo + Design system + Animation harmony**
- [ ] SEO readiness: **All meta tags, og:tags, schema.org**

---

## 🚀 OBJETIVO FINAL (SEMANA 5 END - VIERNES)

**SYNTIWEB READY FOR PUBLIC BETA**
- ✅ Landing: 100% B2H, gorgeous, convertible, competitive
- ✅ Blueprints: Auto-segmentation 100% working
- ✅ Dashboard: Functional, beautiful, intuitive per segment
- ✅ Plans page: Detailed, transparent, with comparison
- ✅ Documentation: Support team can onboard customers
- ✅ Logo: Minimalista, integrado, profesional
- **Status:** Production-ready for Venezuela market launch 🇻🇪

---

## 📝 COMMIT MESSAGES ESPERADOS (MAÑANA)

```
feat: add logo minimalista — navbar + hero animations + all themes
refactor: landing copy b2h — remove technical jargon, clarity focus
feat: create planes-detalle page — feature matrix + expandible cards + cta per plan
feat: add price comparison section — syntiweb vs competitors + savings calc
refactor: floating panel design — apply elevated design system, accessibility
feat: add mockups/screenshots — dashboard + landing previews + device frames
docs: create wiki support docs — usuario guide, blueprints, planes, faq, glosario
chore: merge feature/limpieza-frankenstein → main (Release prep)
```

---

## 🎯 DAILY STANDUP TEMPLATE (PARA MAÑANA)

**9:00 AM:** Café + 10min plan review
**9:10 AM:** Logo integration (30 min) ← Quick win
**9:45 AM:** Copy B2H (1h)
**10:45 AM:** Break (15 min)
**11:00 AM:** Planes page (2h)
**1:00 PM:** Lunch (1h)
**2:00 PM:** Comparativa (1.5h)
**3:30 PM:** Break (15 min)
**3:45 PM:** Panel + Mockups (1.5h)
**5:15 PM:** Docs (1h)
**6:15 PM:** Commit + Push
**6:30 PM:** ✨ Done

---

**Última actualización:** 28 Feb 2026, 03:25 AM  
**Responsable:** Carlos (Architect) + Claude (Consultant)  
**Próxima sync:** 1 Mar 2026 (Morning ☕, fresh energy)

---

## 📌 NOTAS IMPORTANTES

- **No escribas código cansado.** Si son las 5 AM, duerme. Mañana será mejor.
- **B2H es CRÍTICO.** Cualquier palabra técnica = revisar 2x.
- **Logo primero.** Es 30 min pero da coherencia a todo.
- **Comparativa vende.** Muestra que DOMINA al competidor (precio + features).
- **Planes page es independiente.** URL `/planes-detalle`, linkeable, shareable.
- **Docs son GOLD.** Reduce soporte futuro 50%.

---

**STATE: 95% SEMANA 5 → 100% PRODUCTION-READY (VIERNES)**

🚀 **Let's ship this.**