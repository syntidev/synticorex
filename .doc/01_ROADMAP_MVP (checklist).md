# 🗺️ ROADMAP MVP - SYNTIWEB

**Objetivo:** Lanzar plataforma funcional con 3 planes  
**Timeline:** 3-4 semanas  
**Dedicación:** 8 horas/día = 40 horas/semana  
**Total estimado:** 120-160 horas

---

## 📅 FASE 1: FUNDACIÓN (Semana 1 - 40h)

### **Objetivo:** Estructura base funcional

| # | Tarea | Horas | Prioridad |
|---|-------|-------|-----------|
| 1.1 | Configurar entorno Laravel 12 limpio | 2h | 🔴 CRÍTICA |
| 1.2 | Configurar Git + GitHub repo | 1h | 🔴 CRÍTICA |
| 1.3 | Crear migraciones base de datos (10 tablas) | 6h | 🔴 CRÍTICA |
| 1.4 | Seeders para `plans` y `color_palettes` | 2h | 🔴 CRÍTICA |
| 1.5 | Modelos Eloquent con relaciones | 4h | 🔴 CRÍTICA |
| 1.6 | Middleware `IdentifyTenant` + lógica multidominio | 5h | 🔴 CRÍTICA |
| 1.7 | Sistema de autenticación (Breeze) | 3h | 🟠 ALTA |
| 1.8 | Rutas base (web.php, auth.php) | 2h | 🟠 ALTA |
| 1.9 | Configuración de storage (filesystem.php) | 2h | 🟠 ALTA |
| 1.10 | Service: TenantContentService (skeleton) | 3h | 🟠 ALTA |
| 1.11 | Service: DollarRateService (API BCV) | 4h | 🟠 ALTA |
| 1.12 | Testing de multidominio en local | 4h | 🟡 MEDIA |
| 1.13 | Documentación de setup para producción | 2h | 🟡 MEDIA |

**✅ Entregables Semana 1:**
- Base de datos funcional con seeders
- Multitenancy operativo
- Autenticación lista
- API dólar conectada

---

## 📅 FASE 2: TEMPLATE ÚNICO (Semana 2 - 40h)

### **Objetivo:** Landing page con renderizado condicional

| # | Tarea | Horas | Prioridad |
|---|-------|-------|-----------|
| 2.1 | Estructura HTML base (master.blade.php) | 4h | 🔴 CRÍTICA |
| 2.2 | Sección: Nav (responsive) | 3h | 🔴 CRÍTICA |
| 2.3 | Sección: Hero (con imagen dinámica) | 3h | 🔴 CRÍTICA |
| 2.4 | Sección: Servicios (grid con iconos/imágenes) | 5h | 🔴 CRÍTICA |
| 2.5 | Sección: Productos (catálogo con precios) | 6h | 🔴 CRÍTICA |
| 2.6 | Sección: Footer (redes + whatsapp + legal) | 3h | 🔴 CRÍTICA |
| 2.7 | Sección: Header Top (horarios/delivery) | 2h | 🟠 ALTA |
| 2.8 | Sección: Acerca de (texto enriquecido) | 2h | 🟠 ALTA |
| 2.9 | Sección: Medios de Pago (iconos) | 2h | 🟠 ALTA |
| 2.10 | Sección: FAQ (accordion) | 3h | 🟠 ALTA |
| 2.11 | Sección: CTA Especial | 2h | 🟠 ALTA |
| 2.12 | Sistema de paletas de color (CSS dinámico) | 3h | 🟠 ALTA |
| 2.13 | Testing responsive (móvil/tablet/desktop) | 4h | 🟡 MEDIA |

**✅ Entregables Semana 2:**
- Template HTML completo
- Todas las secciones funcionales
- Responsive 100%
- Renderizado condicional por plan

---

## 📅 FASE 3: DASHBOARD FLOTANTE (Semana 3 - 40h)

### **Objetivo:** Panel de edición para usuarios

| # | Tarea | Horas | Prioridad |
|---|-------|-------|-----------|
| 3.1 | Diseño UI del dashboard (Figma/sketch en papel) | 2h | 🔴 CRÍTICA |
| 3.2 | Estructura HTML del panel (side drawer) | 4h | 🔴 CRÍTICA |
| 3.3 | Sistema de activación (Alt+S / long press) | 3h | 🔴 CRÍTICA |
| 3.4 | Autenticación con PIN (modal) | 3h | 🔴 CRÍTICA |
| 3.5 | Tab 1: Info básica (form) | 3h | 🔴 CRÍTICA |
| 3.6 | Tab 2: Productos CRUD | 6h | 🔴 CRÍTICA |
| 3.7 | Tab 3: Servicios CRUD | 4h | 🔴 CRÍTICA |
| 3.8 | Tab 4: Diseño (selector paleta, efectos) | 4h | 🟠 ALTA |
| 3.9 | Tab 5: Analytics (visualización según plan) | 4h | 🟠 ALTA |
| 3.10 | Tab 6: Config (redes, medios pago, segmento) | 3h | 🟠 ALTA |
| 3.11 | Sistema de upload de imágenes (drag & drop) | 4h | 🟠 ALTA |
| 3.12 | Procesamiento de imágenes (resize + WebP) | 3h | 🟠 ALTA |
| 3.13 | API endpoints para guardar cambios (AJAX) | 4h | 🟠 ALTA |
| 3.14 | Validaciones frontend y backend | 3h | 🟡 MEDIA |

**✅ Entregables Semana 3:**
- Dashboard funcional 100%
- CRUD de productos y servicios
- Upload de imágenes optimizado
- Cambios en tiempo real

---

## 📅 FASE 4: ANALYTICS Y POLISH (Semana 4 - 40h)

### **Objetivo:** Sistema de métricas y optimizaciones

| # | Tarea | Horas | Prioridad |
|---|-------|-------|-----------|
| 4.1 | Sistema de tracking (eventos JS) | 4h | 🔴 CRÍTICA |
| 4.2 | Almacenamiento en `analytics_events` | 3h | 🔴 CRÍTICA |
| 4.3 | Dashboard analytics Plan OPORTUNIDAD | 2h | 🔴 CRÍTICA |
| 4.4 | Dashboard analytics Plan CRECIMIENTO | 3h | 🔴 CRÍTICA |
| 4.5 | Dashboard analytics Plan VISIÓN (top productos) | 4h | 🔴 CRÍTICA |
| 4.6 | SEO automático básico (Plan OPORTUNIDAD) | 3h | 🟠 ALTA |
| 4.7 | SEO por segmento (Plan CRECIMIENTO) | 5h | 🟠 ALTA |
| 4.8 | SEO profundo + Schema.org (Plan VISIÓN) | 5h | 🟠 ALTA |
| 4.9 | Sistema de generación de recibos PDF | 4h | 🟠 ALTA |
| 4.10 | Optimización de performance (lazy loading, cache) | 4h | 🟡 MEDIA |
| 4.11 | Testing E2E (flujo completo usuario) | 5h | 🟡 MEDIA |
| 4.12 | Documentación de usuario (guía rápida) | 3h | 🟡 MEDIA |

**✅ Entregables Semana 4:**
- Analytics completo
- SEO automatizado
- Performance optimizado
- Sistema listo para producción

---

## 🎯 MILESTONES CLAVE

### ✅ Milestone 1 (Fin Semana 1):
- [ ] Base de datos poblada
- [ ] Multidominio funcionando
- [ ] API dólar conectada

### ✅ Milestone 2 (Fin Semana 2):
- [ ] Landing page completa
- [ ] Responsive 100%
- [ ] Renderizado por planes OK

### ✅ Milestone 3 (Fin Semana 3):
- [ ] Dashboard funcional
- [ ] CRUD completo
- [ ] Imágenes optimizadas

### ✅ Milestone 4 (Fin Semana 4):
- [ ] Analytics operativo
- [ ] SEO automatizado
- [ ] **MVP LISTO PARA BETA** 🚀

---

## 📊 DISTRIBUCIÓN DE TIEMPO

```
SEMANA 1: Fundación          ████████████████░░░░  40h (Backend heavy)
SEMANA 2: Template           ████████████████████  40h (Frontend heavy)
SEMANA 3: Dashboard          ████████████████████  40h (Fullstack)
SEMANA 4: Analytics + Polish ████████████████░░░░  40h (Optimización)
                             ═══════════════════
                             TOTAL: 160 HORAS
```

---

## 🚨 RIESGOS Y CONTINGENCIAS

### Riesgo 1: API BCV falla constantemente
**Mitigación:** Implementar fallback manual + caché de última tasa

### Riesgo 2: Performance en Plan VISIÓN (40 productos)
**Mitigación:** Lazy loading + paginación opcional

### Riesgo 3: Complejidad del multidominio en shared hosting
**Mitigación:** Documentación exhaustiva + script de configuración

### Riesgo 4: Upload de imágenes muy pesadas
**Mitigación:** Validación frontend + procesamiento obligatorio

---

## 🔄 PROCESO DE DESARROLLO

### Workflow diario recomendado:
```
08:00-10:00  Desarrollo backend (lógica)
10:00-10:30  Break
10:30-13:00  Desarrollo frontend (UI)
13:00-14:00  Almuerzo
14:00-16:00  Integración + testing
16:00-16:30  Break
16:30-18:00  Documentación + git commits
```

### Git branching:
```
main          → Producción (solo merges)
develop       → Desarrollo activo
feature/*     → Features individuales
hotfix/*      → Fixes urgentes
```

---

## 📝 CHECKLIST PRE-LANZAMIENTO

### Técnico:
- [ ] Base de datos respaldada
- [ ] SSL configurado (wildcard)
- [ ] DNS wildcard activo
- [ ] Cron job tasa dólar (cada hora)
- [ ] Email SMTP configurado
- [ ] Backup automático configurado

### Funcional:
- [ ] 3 planes probados end-to-end
- [ ] Dashboard sin bugs críticos
- [ ] Analytics reportando correctamente
- [ ] Imágenes procesándose OK
- [ ] SEO generándose correctamente

### Negocio:
- [ ] Landing comercial de SYNTIweb lista
- [ ] Pricing page clara
- [ ] Proceso de registro definido
- [ ] Sistema de facturación manual listo
- [ ] 5-10 clientes beta confirmados

---

## 🎯 POST-MVP (Roadmap Futuro)

### Versión 1.1 (1-2 semanas post-launch):
- [ ] Pasarela de pago (Stripe/PayPal)
- [ ] Panel admin core.syntiweb.com
- [ ] Reportes avanzados
- [ ] Backup automático de tenant data

### Versión 1.2 (1 mes post-launch):
- [ ] Sistema de plantillas múltiples
- [ ] A/B testing de secciones
- [ ] Integraciones (Google Analytics, Meta Pixel)
- [ ] Plan ON DEMAND workflow

### Versión 2.0 (3 meses post-launch):
- [ ] Multi-landing (hasta 3 páginas por tenant)
- [ ] E-commerce básico
- [ ] App móvil para gestión
- [ ] API pública

---

## 💡 NOTAS DE EJECUCIÓN

### Priorización:
1. **CRÍTICO:** Sin esto no funciona nada
2. **ALTO:** Impacta experiencia del usuario
3. **MEDIO:** Nice to have, puede diferirse

### Estimaciones:
- Tiempos son **ESTIMADOS** (pueden variar ±30%)
- Buffer de 10h por semana para imprevistos
- Si vas adelantado, invertir en testing

### Recomendaciones:
1. Hacer commits pequeños y frecuentes
2. Testing manual diario (no esperar al final)
3. Documentar decisiones técnicas importantes
4. Pedir feedback de beta testers temprano

---

## 📞 SOPORTE DURANTE DESARROLLO

### Si te atoras:
1. Revisar docs de Laravel 12
2. Consultar ejemplos en GitHub
3. Usar Claude/ChatGPT para debugging
4. Comunidad Laravel (foros, Discord)

### Red flags para pausar:
- 🚨 Llevas >3 horas atascado en algo
- 🚨 Feature parece imposible (replantear)
- 🚨 Acumulando deuda técnica
- 🚨 Perdiendo visión del objetivo MVP

---

**¡ADELANTE! 🚀**

Primera venta objetivo: **7-10 días post-lanzamiento**  
Recuerda: MVP = Minimum **VIABLE** Product (no perfecto)

---

**FIN DEL ROADMAP**  
Última actualización: 2026-02-15
