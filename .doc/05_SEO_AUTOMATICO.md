# 🔍 SISTEMA DE SEO AUTOMÁTICO - SYNTIWEB

**Objetivo:** Generar SEO optimizado sin intervención manual  
**Método:** Detección de segmento + templates especializados  
**Niveles:** Básico (Plan 1) → Mejorado (Plan 2) → Profundo (Plan 3)

---

## 📊 SEGMENTOS DE NEGOCIO

### Lista completa:
1. **restaurante** - Restaurantes, comedores, food trucks
2. **barberia** - Barberías, peluquerías, salones de belleza
3. **servicios_tecnicos** - Plomería, electricidad, cerrajería, reparaciones
4. **retail** - Tiendas, boutiques, minimercados
5. **salud_fitness** - Gimnasios, clínicas, spa, nutrición
6. **educacion** - Academias, tutorías, cursos
7. **automotriz** - Talleres mecánicos, repuestos, lavado de autos
8. **hogar_decoracion** - Muebles, decoración, jardinería
9. **entretenimiento** - Eventos, fotografía, DJ, animación
10. **otros** - Catch-all para casos no clasificados

---

## 🎯 PLAN OPORTUNIDAD (SEO Básico)

### Características:
- SEO auto-generado al 100%
- Solo title + description
- No editable por usuario
- Basado en info básica del tenant

### Template:
```html
<title>{business_name} - {city}</title>
<meta name="description" content="{business_name} en {city}. Contáctanos al {whatsapp}">
```

### Ejemplo real:
```html
<title>Jose Burguer - Caracas</title>
<meta name="description" content="Jose Burguer en Caracas. Contáctanos al +58 412 1234567">
```

### Implementación (Blade):
```php
{{-- resources/views/layouts/master.blade.php --}}
<head>
    <title>{{ $tenant->business_name }} - {{ $tenant->city }}</title>
    <meta name="description" content="{{ $tenant->business_name }} en {{ $tenant->city }}. Contáctanos al {{ $tenant->whatsapp_sales }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>
```

---

## 🚀 PLAN CRECIMIENTO (SEO Mejorado por Segmento)

### Características:
- Templates especializados por tipo de negocio
- Keywords automáticas según segmento
- Open Graph tags
- Más detallado que Plan 1
- Aún auto-generado (no editable manualmente)

### Templates por Segmento:

#### 1. RESTAURANTE
```html
<title>{business_name} - Restaurante en {city} | Comida {especialidad}</title>
<meta name="description" content="🍔 {business_name}: Tu restaurante favorito en {city}. {especialidades}. Abierto {horario}. Pide ahora por WhatsApp.">
<meta name="keywords" content="restaurante {city}, comida {city}, {especialidad}, delivery {city}, {business_name}">

<!-- Open Graph -->
<meta property="og:title" content="{business_name} - Restaurante en {city}">
<meta property="og:description" content="{business_name}: {slogan}">
<meta property="og:image" content="{hero_image_url}">
<meta property="og:type" content="restaurant">
<meta property="og:locale" content="es_VE">
```

**Variables dinámicas:**
- `{especialidad}`: Detectado de servicios (Ej: "Hamburguesas", "Pizza", "Sushi")
- `{especialidades}`: Lista de top 3 servicios
- `{horario}`: Extraído de business_hours

**Ejemplo real:**
```html
<title>Jose Burguer - Restaurante en Caracas | Comida Rápida</title>
<meta name="description" content="🍔 Jose Burguer: Tu restaurante favorito en Caracas. Hamburguesas artesanales, Papas fritas, Pepitos. Abierto Lun-Sáb 11am-10pm. Pide ahora por WhatsApp.">
<meta name="keywords" content="restaurante caracas, comida caracas, hamburguesas, delivery caracas, jose burguer">
```

---

#### 2. BARBERÍA
```html
<title>{business_name} - Barbería en {city} | Cortes y Estilo</title>
<meta name="description" content="✂️ {business_name}: Tu barbería de confianza en {city}. {servicios}. Reserva tu cita: {whatsapp}">
<meta name="keywords" content="barbería {city}, peluquería {city}, corte de cabello, barba, {business_name}">

<meta property="og:title" content="{business_name} - Barbería Profesional">
<meta property="og:type" content="business.business">
```

**Ejemplo:**
```html
<title>Barber Kings - Barbería en Maracaibo | Cortes y Estilo</title>
<meta name="description" content="✂️ Barber Kings: Tu barbería de confianza en Maracaibo. Corte clásico, Barba, Diseño. Reserva tu cita: +58 424 9876543">
```

---

#### 3. SERVICIOS TÉCNICOS
```html
<title>{business_name} - {tipo_servicio} en {city} | Servicio Rápido</title>
<meta name="description" content="🔧 {business_name}: {tipo_servicio} profesional en {city}. Atención inmediata. {servicios}. Llama: {phone}">
<meta name="keywords" content="{tipo_servicio} {city}, reparaciones {city}, emergencias, {business_name}">

<meta property="og:title" content="{business_name} - Servicio Profesional 24/7">
```

**Tipos detectados:**
- "Plomería" → 🔧 Plomero
- "Electricidad" → ⚡ Electricista  
- "Cerrajería" → 🔑 Cerrajero
- "Reparaciones" → 🛠️ Técnico

**Ejemplo:**
```html
<title>Plomeros Express - Plomería en Valencia | Servicio Rápido</title>
<meta name="description" content="🔧 Plomeros Express: Plomería profesional en Valencia. Atención inmediata. Fugas, Tuberías, Instalaciones. Llama: +58 241 5556677">
```

---

#### 4. RETAIL
```html
<title>{business_name} - Tienda en {city} | {categoria_productos}</title>
<meta name="description" content="🛍️ {business_name} en {city}. {categorias}. Precios accesibles. Visítanos o pide por WhatsApp.">
<meta name="keywords" content="tienda {city}, comprar {city}, {categorias}, {business_name}">
```

---

#### 5-10. (Templates similares para otros segmentos)

### Lógica de Detección:
```php
// app/Services/SeoService.php
class SeoService
{
    public function generateMetaTags(Tenant $tenant): array
    {
        $segment = $tenant->business_segment ?? 'otros';
        $template = $this->getTemplateForSegment($segment);
        
        return $this->populateTemplate($template, $tenant);
    }
    
    private function getTemplateForSegment(string $segment): array
    {
        $templates = [
            'restaurante' => [
                'title' => '{business_name} - Restaurante en {city} | Comida {especialidad}',
                'description' => '🍔 {business_name}: Tu restaurante favorito en {city}. {especialidades}. Abierto {horario}. Pide ahora por WhatsApp.',
                'keywords' => 'restaurante {city}, comida {city}, {especialidad}, delivery {city}'
            ],
            // ... más templates
        ];
        
        return $templates[$segment] ?? $templates['otros'];
    }
    
    private function populateTemplate(array $template, Tenant $tenant): array
    {
        $variables = $this->extractVariables($tenant);
        
        foreach ($template as $key => $value) {
            foreach ($variables as $var => $replacement) {
                $template[$key] = str_replace('{' . $var . '}', $replacement, $template[$key]);
            }
        }
        
        return $template;
    }
    
    private function extractVariables(Tenant $tenant): array
    {
        return [
            'business_name' => $tenant->business_name,
            'city' => $tenant->city,
            'slogan' => $tenant->slogan ?? '',
            'whatsapp' => $tenant->whatsapp_sales,
            'phone' => $tenant->phone,
            'especialidad' => $this->detectEspecialidad($tenant),
            'especialidades' => $this->getTopServices($tenant, 3),
            'horario' => $this->formatBusinessHours($tenant),
            'hero_image_url' => $tenant->heroImageUrl(),
        ];
    }
    
    private function detectEspecialidad(Tenant $tenant): string
    {
        // Analiza los servicios y detecta la especialidad principal
        $services = $tenant->services()->pluck('name')->toArray();
        
        // Lógica de detección por keywords
        if ($this->containsAny($services, ['hamburguesa', 'burger', 'sandwich'])) {
            return 'Hamburguesas';
        }
        if ($this->containsAny($services, ['pizza'])) {
            return 'Pizza';
        }
        // ... más detecciones
        
        return 'Comida';
    }
}
```

---

## 🏆 PLAN VISIÓN (SEO Profundo)

### Características:
- Todo lo de Plan CRECIMIENTO +
- **Schema.org structured data**
- LocalBusiness markup
- Product schema (para cada producto)
- FAQ schema (si tiene FAQ)
- Breadcrumbs
- Rich Snippets habilitados

### Schema.org: LocalBusiness
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Restaurant",
  "name": "{{ $tenant->business_name }}",
  "image": "{{ $tenant->heroImageUrl() }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $tenant->address }}",
    "addressLocality": "{{ $tenant->city }}",
    "addressCountry": "VE"
  },
  "telephone": "{{ $tenant->phone }}",
  "priceRange": "$$",
  "openingHoursSpecification": [
    @foreach($tenant->businessHoursForSchema() as $day)
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "{{ $day['name'] }}",
      "opens": "{{ $day['opens'] }}",
      "closes": "{{ $day['closes'] }}"
    }@if(!$loop->last),@endif
    @endforeach
  ],
  "servesCuisine": "{{ $tenant->detectCuisineType() }}",
  "hasMenu": "{{ url('/') }}#productos"
}
</script>
```

### Schema.org: Product (por cada producto)
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->imageUrl() }}",
  "description": "{{ $product->description }}",
  "offers": {
    "@type": "Offer",
    "price": "{{ $product->price_usd }}",
    "priceCurrency": "USD",
    "availability": "https://schema.org/InStock"
  }
}
</script>
```

### Schema.org: FAQPage
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($tenant->customization->faq_items as $faq)
    {
      "@type": "Question",
      "name": "{{ $faq['question'] }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ $faq['answer'] }}"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
```

---

## 🎨 IMPLEMENTACIÓN EN BLADE

### Layout Master:
```blade
{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $seo = app(\App\Services\SeoService::class)->generateMetaTags($tenant);
    @endphp
    
    {{-- Plan OPORTUNIDAD y CRECIMIENTO --}}
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    
    @if($tenant->plan_id >= 2)
        <meta name="keywords" content="{{ $seo['keywords'] }}">
        
        {{-- Open Graph --}}
        <meta property="og:title" content="{{ $seo['og_title'] }}">
        <meta property="og:description" content="{{ $seo['og_description'] }}">
        <meta property="og:image" content="{{ $seo['og_image'] }}">
        <meta property="og:type" content="{{ $seo['og_type'] }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:locale" content="es_VE">
    @endif
    
    @if($tenant->plan_id >= 3)
        {{-- Schema.org Structured Data --}}
        {!! $seo['schema_local_business'] !!}
        
        @if($tenant->products->isNotEmpty())
            {!! $seo['schema_products'] !!}
        @endif
        
        @if($tenant->hasFAQ())
            {!! $seo['schema_faq'] !!}
        @endif
    @endif
    
    {{-- Canonical --}}
    <link rel="canonical" href="{{ url('/') }}">
    
    {{-- Resto del head... --}}
</head>
<body>
    @yield('content')
</body>
</html>
```

---

## 📈 MEJORAS TÉCNICAS

### 1. Sitemap.xml Automático
```xml
<!-- public/sitemap.xml (generado dinámicamente) -->
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{ url('/') }}</loc>
    <lastmod>{{ $tenant->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  
  @foreach($tenant->products as $product)
  <url>
    <loc>{{ url('/#producto-' . $product->id) }}</loc>
    <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  @endforeach
</urlset>
```

### 2. Robots.txt
```
# public/robots.txt
User-agent: *
Allow: /
Sitemap: {{ url('/sitemap.xml') }}
```

### 3. Performance Optimizations
```html
<!-- Preconnect a dominios externos -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

<!-- Resource hints -->
<link rel="preload" as="image" href="{{ $tenant->logoUrl() }}">
<link rel="preload" as="image" href="{{ $tenant->heroImageUrl() }}">
```

---

## ✅ CHECKLIST DE VALIDACIÓN

### Todos los planes:
- [ ] Title tag presente (max 60 chars)
- [ ] Meta description presente (max 160 chars)
- [ ] Viewport meta tag
- [ ] Charset UTF-8
- [ ] Canonical URL

### Plan CRECIMIENTO+:
- [ ] Keywords relevantes
- [ ] Open Graph completo
- [ ] Imágenes con alt text

### Plan VISIÓN:
- [ ] Schema.org LocalBusiness válido
- [ ] Schema.org Products
- [ ] Sitemap.xml generado
- [ ] Rich snippets verificados en Google Testing Tool

---

## 🧪 TESTING

### Herramientas recomendadas:
1. **Google Search Console** - Validar indexación
2. **Rich Results Test** - https://search.google.com/test/rich-results
3. **PageSpeed Insights** - Validar Core Web Vitals
4. **Schema Markup Validator** - https://validator.schema.org/

### Comandos de validación:
```bash
# Generar reporte SEO para un tenant
php artisan seo:validate {tenant_id}

# Regenerar SEO de todos los tenants
php artisan seo:regenerate --all

# Test de structured data
php artisan seo:test-schema {tenant_id}
```

---

**FIN DEL DOCUMENTO**  
Última actualización: 2026-02-15
