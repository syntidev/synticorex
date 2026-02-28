# BLUEPRINTS MVP — SYNTIweb Segment Architecture (COMPLETO)

> **Objetivo:** Definir automáticamente qué campos, schemas y features se activan según el segmento elegido por el cliente.
> 
> **Filosofía:** "Molde una vez, escala infinito."
>
> **5 Blueprints MVP:** Food, Retail, Health, Professional Services, On-Demand/Technical

---

## BLUEPRINT 1: FOOD & BEVERAGE (Restaurantes, Pizzerías, Cafés)

### Required Fields
```
Info Tab:
  ├─ Nombre del Negocio *
  ├─ Teléfono/WhatsApp para Pedidos *
  ├─ Dirección & Ciudad *
  ├─ Horarios de Atención (7 días) *
  ├─ Logo
  ├─ Hero Image (foto plato destacado)
  └─ Descripción corta (slogan/tipo de comida)

Productos Tab (llamado "Menú"):
  ├─ Nombre del Plato *
  ├─ Precio *
  ├─ Foto
  ├─ Descripción (ingredientes, 50 chars)
  ├─ Categoría (Entrada/Plato Fuerte/Postre/Bebida)
  └─ [Plan 3 only] ¿Es vegetariano? / ¿Alergenos?
```

### Landing Sections
```
1. Hero Section
2. Menu Preview (6/12/20 platos)
3. Horarios & Ubicación
4. WhatsApp Pedidos
5. Testimonios (Plan 2+)
6. Contact
```

### Feature Gating
```
Plan 1: 6 platos, horarios básicos, WhatsApp simple
Plan 2: 12 platos, categorías, delivery, schema
Plan 3: 20+ platos, alergenos, reservas, FAQ schema
```

---

## BLUEPRINT 2: RETAIL (Tiendas de Ropa, Electrónica, Accesorios)

### Required Fields
```
Info Tab:
  ├─ Nombre de la Tienda *
  ├─ Teléfono/WhatsApp *
  ├─ Dirección *
  ├─ Logo
  ├─ Hero (foto de vitrina/colección)
  └─ Descripción (qué vendes)

Productos Tab:
  ├─ Nombre del Producto *
  ├─ Precio *
  ├─ SKU
  ├─ Foto(s)
  ├─ Descripción
  ├─ Stock (cantidad)
  ├─ Categoría
  └─ [Plan 3 only] ¿En oferta? / Precio anterior
```

### Landing Sections
```
1. Hero Section
2. Productos Destacados (6/12/20)
3. Categorías (Plan 2+)
4. Promociones (Plan 3 only)
5. Contact & Horarios
6. FAQ (Plan 3 only)
```

### Feature Gating
```
Plan 1: 6 productos, foto + precio, stock status
Plan 2: 12 productos, categorías, carrito WhatsApp
Plan 3: 20+ productos, descuentos, estrellas, pasarela pago
```

---

## BLUEPRINT 3: HEALTH & WELLNESS (Peluquerías, Spas, Consultorios, Gimnasios)

### Required Fields
```
Info Tab:
  ├─ Nombre del Negocio *
  ├─ Teléfono/WhatsApp *
  ├─ Dirección *
  ├─ [Plan 2+] Profesional responsable
  ├─ [Plan 3+] Cédula/Licencia
  ├─ Logo
  ├─ Hero (foto del espacio/tratamiento)
  └─ Descripción

Servicios Tab:
  ├─ Nombre del Servicio *
  ├─ Duración *
  ├─ Precio *
  ├─ Foto
  ├─ Descripción
  ├─ [Plan 2+] ¿Requiere cita?
  └─ [Plan 3+] ¿Disponible para agendar?
```

### Landing Sections
```
1. Hero Section
2. Servicios Destacados
3. Horarios
4. Profesionales (Plan 2+)
5. Agendar Cita (Plan 3 only)
6. Testimonios (Plan 2+)
7. Contact
```

### Feature Gating
```
Plan 1: 6 servicios, horarios, WhatsApp
Plan 2: 12 servicios, profesionales, schema, testimonios
Plan 3: 20+ servicios, agendar online, verificación cédula, FAQ
```

---

## BLUEPRINT 4: SERVICIOS PROFESIONALES (Abogados, Contadores, Consultores, IT)

### Required Fields
```
Info Tab:
  ├─ Nombre de la Empresa/Profesional *
  ├─ Teléfono/WhatsApp *
  ├─ Email profesional *
  ├─ [Plan 2+] Profesional responsable
  ├─ [Plan 3+] Cédula profesional
  ├─ Logo
  ├─ Hero (foto oficina/equipo)
  ├─ Descripción (especialidades)
  └─ Años de experiencia (Plan 2+)

Servicios Tab:
  ├─ Nombre del Servicio *
  ├─ Precio (rango o "bajo consulta") *
  ├─ Descripción
  ├─ [Plan 2+] Duración estimada
  ├─ [Plan 3+] ¿Consulta gratis?
  └─ [Plan 3+] Documentos descargables
```

### Landing Sections
```
1. Hero Section
2. Sobre Nosotros (Plan 2+)
3. Servicios Principales
4. Team/Profesionales (Plan 2+)
5. Portafolio/Casos (Plan 3 only)
6. FAQ (Plan 3 only)
7. Lead Magnet (Plan 3 only)
8. Contact
```

### Feature Gating
```
Plan 1: 6 servicios, perfil básico, WhatsApp
Plan 2: 12 servicios, team, testimonios, schema
Plan 3: 20+ servicios, portafolio, lead magnet, calendly, FAQ
```

---

## BLUEPRINT 5: SERVICIOS ON-DEMAND / TÉCNICOS (Mecánicos, Plomeros, Electricistas, Carpinteros, Freelancers)

### Required Fields
```
Info Tab:
  ├─ Nombre del Profesional *
  ├─ Teléfono/WhatsApp *
  ├─ Zona de cobertura (ej: "Maracaibo + alrededores") *
  ├─ Especialidad (Electricista, Plomero, Carpintero, etc.) *
  ├─ Foto de perfil
  ├─ Foto de trabajo destacado (antes/después)
  ├─ Años de experiencia
  └─ Descripción corta (slogan)

Servicios Tab:
  ├─ Tipo de servicio (Instalación, Reparación, Mantenimiento) *
  ├─ Descripción del trabajo *
  ├─ Precio base (o "presupuesto bajo consulta")
  ├─ Tiempo estimado (30 min, 1 hora, etc.)
  ├─ Foto del trabajo realizado
  └─ [Plan 3] ¿Garantía incluida?
```

### Schema.org Auto-Inject
```json
{
  "type": "LocalBusiness",
  "name": "Nombre Profesional",
  "image": "url_foto_perfil",
  "areaServed": {
    "type": "AdministrativeArea",
    "name": "Maracaibo, Zulia"
  },
  "service": [
    {
      "type": "Service",
      "name": "Reparación de motor",
      "description": "...",
      "price": "Bajo consulta"
    }
  ],
  "aggregateRating": {
    "ratingValue": "4.8",
    "reviewCount": "23"
  }
}
```

### Landing Sections
```
1. Hero Section
   └─ Foto perfil + Nombre + "Disponible" badge + Especialidad

2. ¿Qué hago?
   └─ 3 servicios principales (Instalación / Reparación / Mantenimiento)

3. Trabajos Realizados (Portfolio)
   └─ Galería antes/después (Plan 2+)

4. Zona de Cobertura
   └─ "Atiendo en: Maracaibo, Cabimas, San Francisco"

5. Testimonios (Plan 2+)
   └─ Clientes satisfechos + estrellas

6. ¿Cómo funciona?
   └─ "1. Contacta por WhatsApp, 2. Envía foto, 3. Te doy presupuesto"

7. CTA flotante
   └─ "Contacta ahora" + WhatsApp + Teléfono
```

### Feature Gating
```
Plan 1 (SEMILLA):
  ✓ Perfil profesional básico
  ✓ 6 trabajos en galería
  ✓ Zona de cobertura fija
  ✓ Botón WhatsApp "Solicitar servicio"
  ✓ Horario de atención
  ✗ Reservar cita
  ✗ Testimonios
  ✗ Sistema de urgencias

Plan 2 (IMPULSO):
  ✓ Hasta 12 trabajos (antes/después)
  ✓ Testimonios + Estrellas
  ✓ Service Schema visible en Google
  ✓ "Disponible para emergencias" badge (opcional)
  ✓ Múltiples servicios
  ✗ Agendar cita online
  ✗ Alertas de zona cercana

Plan 3 (VISIÓN):
  ✓ Galería ilimitada
  ✓ Agendar cita online
  ✓ FAQ Schema
  ✓ Sistema de "Urgencia 24/7"
  ✓ Pulso: Servicios más solicitados
  ✓ Descuentos para servicios combinados
  ✓ Ficha de créditos (cédula, seguros, garantía)
```

---

## ONBOARDING FLOW (Lo que el cliente ve)

```
Step 1: ¿Qué tipo de negocio tienes?
  ┌─────────────────────────────────────────┐
  │ O  Alimentos (Restaurante, Pizzería)    │
  │ O  Comercio (Tienda, E-commerce)        │
  │ O  Salud & Belleza (Peluquería, Spa)    │
  │ O  Servicios (Abogado, Consultor)       │
  │ O  Servicios Técnicos (Mecánico, Obra)  │
  └─────────────────────────────────────────┘

Step 2: [Automático] Sistema carga Blueprint específico
  └─ Campos del Dashboard se adaptan
  └─ Landing sections se generan
  └─ Schema.org se inyecta

Step 3: Usuario completa Info + Productos/Servicios
  └─ Solo ve campos relevantes para su segmento
  └─ Ayudas contextuales (ej: "Foto de plato" si es Alimentos)

Step 4: Landing generada automáticamente
  └─ Con schema específico del segmento
  └─ Con features según plan
```

---

## IMPLEMENTACIÓN TÉCNICA

### En la BD:
```sql
ALTER TABLE tenants ADD COLUMN industry_segment VARCHAR(50);
-- Values: FOOD_BEVERAGE, RETAIL, HEALTH_WELLNESS, PROFESSIONAL_SERVICES, ON_DEMAND
```

### En el Controller (Onboarding):
```php
$tenant->industry_segment = $request->validated()['industry_segment'];
$blueprint = config("blueprints.{$tenant->industry_segment}");
$tenant->settings['blueprint_config'] = $blueprint;
$tenant->save();
```

### En el Dashboard (conditionals):
```blade
@if($tenant->industry_segment === 'FOOD_BEVERAGE')
  <!-- Mostrar "Menú" en lugar de "Productos" -->
  <tab-label>Menú</tab-label>
  @include('dashboard.components.menu-section')

@elseif($tenant->industry_segment === 'HEALTH_WELLNESS')
  <!-- Mostrar "Servicios" con campos de cita -->
  <tab-label>Servicios</tab-label>
  @include('dashboard.components.health-services-section')

@elseif($tenant->industry_segment === 'ON_DEMAND')
  <!-- Mostrar "Trabajos Realizados" con galería antes/después -->
  <tab-label>Portfolio</tab-label>
  @include('dashboard.components.portfolio-section')

@endif
```

### En el Landing (render):
```blade
@switch($tenant->industry_segment)
  @case('FOOD_BEVERAGE')
    @include('landing.schemas.restaurant')
    @include('landing.partials.menu-section')
    @break
  
  @case('ON_DEMAND')
    @include('landing.schemas.local-business')
    @include('landing.partials.portfolio-before-after')
    @break
  
  @default
    @include('landing.schemas.local-business')
@endswitch
```

---

## VALIDACIÓN

✅ **Cada Blueprint es repetible** (1000 restaurantes = 1 Blueprint)
✅ **Escalabilidad sin costo extra** (mismo código, datos diferentes)
✅ **SEO específico del segmento** (no genérico)
✅ **UX limpio** (usuario ve solo campos relevantes)
✅ **Upselling natural** (Plan 1 → Plan 2 → Plan 3)
✅ **5 segmentos cubren 95% de micro-emprendimientos** en Venezuela
