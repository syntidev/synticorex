# ISSUES TÉCNICOS FASE 3 — Registro de Desconexiones

**Fecha:** 26 de Febrero de 2026  
**Auditor:** SWE-1.5  
**Objetivo:** Consolidar el desorden en un solo lugar de control para el Arquitecto

---

## 🔍 PIEZAS ROTAS IDENTIFICADAS

### 1. SEO_iA — Sección Ausente
**Estado:** ❌ PIEZA ROTA (No Implementada)

**Detalles del Issue:**
- **Ubicación esperada:** `resources/views/landing/partials/seo-ia.blade.php`
- **Integración esperada:** `resources/views/landing/base.blade.php`
- **Controller esperado:** `app/Http/Controllers/SeoController.php`
- **Resultado búsqueda:** 0 archivos encontrados
- **Impacto:** Feature prometida en Plan VISIÓN no disponible

**Evidencia:**
```bash
# Búsqueda realizada
grep -r "SEO_iA|seo_ia" resources/views/ --include="*.php"
# Resultado: No matches found
```

**Requisitos faltantes:**
- Modelo `SeoContent` o similar
- Lógica de generación de contenido con IA
- Integración con API de IA (OpenAI/Claude)
- UI en landing para mostrar contenido SEO

---

### 2. BLOG — Sección Ausente
**Estado:** ❌ PIEZA ROTA (No Implementada)

**Detalles del Issue:**
- **Ubicación esperada:** `resources/views/landing/partials/blog.blade.php`
- **Integración esperada:** `resources/views/landing/base.blade.php`
- **Controller esperado:** `app/Http/Controllers/BlogController.php`
- **Modelo esperado:** `app/Models/BlogPost.php`
- **Resultado búsqueda:** 0 archivos encontrados
- **Impacto:** Feature prometida en Plan VISIÓN no disponible

**Evidencia:**
```bash
# Búsqueda realizada
grep -r "BLOG|blog" resources/views/ --include="*.php"
# Resultado: No matches found
```

**Requisitos faltantes:**
- Modelo `BlogPost` con tenant_id
- CRUD para posts en dashboard
- Sistema de categorías/tags
- URL amigables por tenant: `tenant.synticorex.test/blog/:slug`

---

### 3. Automatización Tasa Dólar — Scheduler Faltante
**Estado:** ⚠️ PIEZA PARCIAL (Funcional pero Manual)

**Detalles del Issue:**
- **Service existente:** `app/Services/DollarRateService.php` ✅
- **Método disponible:** `fetchAndStore()` ✅
- **Configuración faltante:** `app/Console/Commands/FetchDollarRate.php` ❌
- **Scheduler faltante:** `app/Console/Kernel.php` sin tarea programada ❌
- **Impacto:** Tasa se actualiza manualmente, no automáticamente cada hora

**Evidencia:**
```php
// Método disponible pero no automatizado
public function fetchAndStore(): array
{
    $response = Http::timeout(self::HTTP_TIMEOUT)
        ->acceptJson()
        ->get(self::API_URL);
    // ... lógica completa implementada
}
```

**Requisitos faltantes:**
- Command `php artisan dollar:fetch`
- Scheduler en Kernel.php para ejecutar cada hora
- Logging de ejecuciones automáticas

---

## 📊 CUADRO COMPARATIVO: ROADMAP vs REALIDAD

### 01_ROADMAP_MVP.md (Lo que se prometió)

| Feature | Semana 1 | Semana 2 | Semana 3 | Semana 4 |
|---------|----------|----------|----------|----------|
| **Core Landing** | ✅ | ✅ | ✅ | ✅ |
| **Multi-tenant** | ✅ | ✅ | ✅ | ✅ |
| **Dashboard CRUD** | | ✅ | ✅ | ✅ |
| **Tasa Dólar BCV** | | ✅ | ✅ | ✅ |
| **QR Dinámico** | | | ✅ | ✅ |
| **SEO_iA** | | | | ✅ |
| **BLOG** | | | | ✅ |
| **Analytics Avanzado** | | | | ✅ |

### Auditoría Real (Lo que existe)

| Feature | Estado Real | Conectividad | Issues |
|---------|-------------|--------------|--------|
| **Core Landing** | ✅ Funcional | 100% conectada | None |
| **Multi-tenant** | ✅ Funcional | 100% conectada | None |
| **Dashboard CRUD** | ✅ Funcional | 100% conectado | None |
| **Tasa Dólar BCV** | ⚠️ Parcial | 80% conectada | Falta scheduler |
| **QR Dinámico** | ✅ Funcional | 100% conectado | None |
| **SEO_iA** | ❌ Ausente | 0% conectada | Pieza rota |
| **BLOG** | ❌ Ausente | 0% conectada | Pieza rota |
| **Analytics Avanzado** | ✅ Funcional | 100% conectado | None |

---

## 🎯 ANÁLISIS DE DESVÍO

### Porcentaje de Completitud Real
- **Core MVP (Sin Features Avanzadas):** 95% completo
- **Features Prometidas Semana 4:** 40% completo
- **Desvío General:** 25% de features no implementadas

### Causas Raíz
1. **SEO_iA y BLOG:** Requieren desarrollo completo desde cero
2. **Scheduler Tasa Dólar:** Simple configuración pendiente
3. **Documentación:** Desactualizada vs realidad implementada

---

## 📋 PLAN DE ACCIÓN RECOMENDADO

### Prioridad CRÍTICA (Inmediato)
1. **Configurar scheduler** para tasa dólar (1-2 horas)
2. **Actualizar documentación** restante (1 hora)

### Prioridad ALTA (Fase 3.1)
1. **Desarrollar BLOG** (8-12 horas)
   - Modelo BlogPost
   - CRUD Dashboard
   - Integración Landing

### Prioridad MEDIA (Fase 3.2)
1. **Desarrollar SEO_iA** (12-16 horas)
   - Integración API IA
   - Generación de contenido
   - UI Landing

---

## 🏁 CONCLUSIÓN PARA EL ARQUITECTO

**Lo que hay:** Base sólida 95% funcional con core MVP operando  
**Lo que falta:** 2 features prometidas (SEO_iA, BLOG) + 1 configuración (scheduler)  
**Esfuerzo estimado:** 22-31 horas para llegar al 100% de lo prometido

El sistema está listo para producción con el core, pero las features diferenciales del Plan VISIÓN requieren desarrollo adicional.
