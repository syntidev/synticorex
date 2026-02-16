# 🗄️ SCHEMA DE BASE DE DATOS - SYNTIWEB

**Sistema:** Laravel 12 + MySQL  
**Versión:** 1.0  
**Charset:** utf8mb4_unicode_ci

---

## 📊 DIAGRAMA DE RELACIONES

```
users (1) ──────── (N) tenants
                      │
                      ├─── (1) plans
                      └─── (N) analytics_events

tenants (1) ────── (N) products
tenants (1) ────── (N) services
tenants (1) ────── (1) tenant_customization
tenants (1) ────── (N) invoices
```

---

## 🏗️ TABLAS PRINCIPALES

### 1. `users`
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Descripción:** Usuarios del sistema (dueños de negocios)

---

### 2. `plans`
```sql
CREATE TABLE plans (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,           -- 'OPORTUNIDAD', 'CRECIMIENTO', 'VISIÓN'
    slug VARCHAR(50) UNIQUE NOT NULL,    -- 'oportunidad', 'crecimiento', 'vision'
    price_usd DECIMAL(6,2) NOT NULL,     -- 49.00, 89.00, 159.00
    
    -- Límites
    products_limit TINYINT UNSIGNED NOT NULL,    -- 6, 18, 40
    services_limit TINYINT UNSIGNED NOT NULL,    -- 3, 6, 15
    images_limit TINYINT UNSIGNED NOT NULL,      -- 8, 26, 57
    color_palettes TINYINT UNSIGNED NOT NULL,    -- 5, 10, 20
    social_networks_limit TINYINT UNSIGNED,      -- 1, NULL (todas), NULL
    
    -- Features (boolean flags)
    show_dollar_rate BOOLEAN DEFAULT FALSE,
    show_header_top BOOLEAN DEFAULT FALSE,
    show_about_section BOOLEAN DEFAULT FALSE,
    show_payment_methods BOOLEAN DEFAULT FALSE,
    show_faq BOOLEAN DEFAULT FALSE,
    show_cta_special BOOLEAN DEFAULT FALSE,
    
    -- Analytics level: 'none', 'basic', 'standard', 'advanced'
    analytics_level VARCHAR(20) DEFAULT 'none',
    
    -- SEO level: 'basic', 'improved', 'advanced'
    seo_level VARCHAR(20) DEFAULT 'basic',
    
    whatsapp_numbers TINYINT UNSIGNED DEFAULT 1, -- 1, 2, 2
    whatsapp_hour_filter BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Seed Data:**
```sql
INSERT INTO plans (name, slug, price_usd, products_limit, services_limit, images_limit, 
                   color_palettes, social_networks_limit, analytics_level, seo_level) VALUES
('OPORTUNIDAD', 'oportunidad', 49.00, 6, 3, 8, 5, 1, 'basic', 'basic'),
('CRECIMIENTO', 'crecimiento', 89.00, 18, 6, 26, 10, NULL, 'standard', 'improved'),
('VISIÓN', 'vision', 159.00, 40, 15, 57, 20, NULL, 'advanced', 'advanced');

UPDATE plans SET 
    show_dollar_rate = TRUE,
    show_header_top = TRUE,
    show_about_section = TRUE,
    show_payment_methods = TRUE
WHERE slug IN ('crecimiento', 'vision');

UPDATE plans SET 
    show_faq = TRUE,
    show_cta_special = TRUE,
    whatsapp_numbers = 2,
    whatsapp_hour_filter = TRUE
WHERE slug = 'vision';
```

---

### 3. `tenants`
```sql
CREATE TABLE tenants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    plan_id TINYINT UNSIGNED NOT NULL,
    
    -- Identificación
    subdomain VARCHAR(100) UNIQUE NULL,      -- 'joseburguer' en joseburguer.menu.vip
    base_domain VARCHAR(100) NULL,           -- 'menu.vip'
    custom_domain VARCHAR(255) UNIQUE NULL,  -- 'www.joseburguer.com'
    domain_verified BOOLEAN DEFAULT FALSE,
    
    -- Info básica del negocio
    business_name VARCHAR(255) NOT NULL,
    business_segment VARCHAR(50) NULL,       -- 'restaurante', 'barberia', 'plomero', etc.
    slogan TEXT NULL,
    description TEXT NULL,
    
    -- Contacto
    phone VARCHAR(20) NULL,
    whatsapp_sales VARCHAR(20) NULL,
    whatsapp_support VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    address TEXT NULL,
    city VARCHAR(100) NULL,
    country VARCHAR(100) DEFAULT 'Venezuela',
    
    -- Horarios (JSON)
    business_hours JSON NULL,  -- {"monday": "9:00-18:00", "tuesday": "9:00-18:00", ...}
    is_open BOOLEAN DEFAULT TRUE,  -- Toggle manual abierto/cerrado
    
    -- Configuración
    edit_pin VARCHAR(255) NOT NULL,  -- Hash del PIN de 4 dígitos
    currency_display VARCHAR(10) DEFAULT 'both',  -- 'usd', 'bs', 'both'
    color_palette_id TINYINT UNSIGNED DEFAULT 1,
    
    -- SEO
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords TEXT NULL,
    
    -- Status
    status VARCHAR(20) DEFAULT 'active',  -- 'active', 'suspended', 'cancelled'
    trial_ends_at TIMESTAMP NULL,
    subscription_ends_at TIMESTAMP NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id),
    
    INDEX idx_subdomain_base (subdomain, base_domain),
    INDEX idx_custom_domain (custom_domain),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 4. `products`
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    
    -- Info del producto
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price_usd DECIMAL(10,2) NULL,
    price_bs DECIMAL(15,2) NULL,  -- Calculado automáticamente
    
    -- Imagen
    image_filename VARCHAR(255) NULL,  -- product_01.webp, product_02.webp, etc.
    
    -- Organización
    position TINYINT UNSIGNED DEFAULT 0,  -- Orden de visualización
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,    -- Para "destacados"
    
    -- Badges
    badge VARCHAR(20) NULL,  -- 'hot', 'new', 'promo', null
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    INDEX idx_tenant_active (tenant_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 5. `services`
```sql
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    
    -- Info del servicio
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    
    -- Visual
    icon_name VARCHAR(50) NULL,        -- 'scissors', 'wrench', 'burger', etc.
    image_filename VARCHAR(255) NULL,  -- service_01.webp (Plan CRECIMIENTO+)
    overlay_text VARCHAR(100) NULL,    -- Texto sobre imagen (Plan VISIÓN)
    
    -- Link
    cta_text VARCHAR(50) DEFAULT 'Más información',
    cta_link TEXT NULL,  -- URL personalizada o default WhatsApp
    
    -- Organización
    position TINYINT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    INDEX idx_tenant_active (tenant_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 6. `tenant_customization`
```sql
CREATE TABLE tenant_customization (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED UNIQUE NOT NULL,
    
    -- Imágenes principales
    logo_filename VARCHAR(255) NULL,
    hero_filename VARCHAR(255) NULL,
    
    -- Redes sociales (JSON)
    social_networks JSON NULL,
    /* Ejemplo:
    {
        "instagram": "@joseburguer",
        "facebook": "JoseBurguerOficial",
        "tiktok": "@joseburguer"
    }
    */
    
    -- Medios de pago (JSON)
    payment_methods JSON NULL,
    /* Ejemplo:
    {
        "zelle": true,
        "cashea": true,
        "pago_movil": true,
        "binance": false,
        "efectivo": true
    }
    */
    
    -- FAQ (JSON - solo Plan VISIÓN)
    faq_items JSON NULL,
    /* Ejemplo:
    [
        {"question": "¿Hacen delivery?", "answer": "Sí, hasta 5km"},
        {"question": "¿Cuál es el tiempo de espera?", "answer": "15-20 min"}
    ]
    */
    
    -- CTA Especial (Plan VISIÓN)
    cta_title VARCHAR(255) NULL,
    cta_subtitle TEXT NULL,
    cta_button_text VARCHAR(100) NULL,
    cta_button_link TEXT NULL,
    
    -- Efectos visuales (Plan VISIÓN)
    visual_effects JSON NULL,
    /* Ejemplo:
    {
        "hero_parallax": true,
        "fade_in_sections": true,
        "hover_animations": true
    }
    */
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 7. `analytics_events`
```sql
CREATE TABLE analytics_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    
    -- Tipo de evento
    event_type VARCHAR(50) NOT NULL,  -- 'page_view', 'whatsapp_click', 'product_click', 'service_click'
    
    -- Referencia (si aplica)
    reference_type VARCHAR(50) NULL,  -- 'product', 'service', null
    reference_id BIGINT UNSIGNED NULL,
    
    -- Contexto
    user_ip VARCHAR(45) NULL,
    user_agent TEXT NULL,
    referer TEXT NULL,
    
    -- Timestamp del evento
    event_date DATE NOT NULL,
    event_hour TINYINT UNSIGNED NOT NULL,  -- 0-23
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    INDEX idx_tenant_date (tenant_id, event_date),
    INDEX idx_tenant_type (tenant_id, event_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 8. `dollar_rates`
```sql
CREATE TABLE dollar_rates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Tasa
    rate DECIMAL(10,2) NOT NULL,  -- Ej: 36.50
    source VARCHAR(50) DEFAULT 'BCV',  -- 'BCV', 'manual'
    
    -- Validez
    effective_from TIMESTAMP NOT NULL,
    effective_until TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_active (is_active, effective_from)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9. `invoices`
```sql
CREATE TABLE invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    
    -- Info de factura
    invoice_number VARCHAR(50) UNIQUE NOT NULL,  -- SYNTI-2026-00001
    amount_usd DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'USD',
    
    -- Pago
    payment_method VARCHAR(50) NULL,  -- 'zelle', 'transferencia', 'efectivo'
    payment_reference VARCHAR(100) NULL,
    payment_date TIMESTAMP NULL,
    
    -- PDF
    pdf_filename VARCHAR(255) NULL,
    
    -- Status
    status VARCHAR(20) DEFAULT 'pending',  -- 'pending', 'paid', 'cancelled'
    
    -- Periodo cubierto
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    INDEX idx_tenant_status (tenant_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 10. `color_palettes`
```sql
CREATE TABLE color_palettes (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    
    -- Colores (hex)
    primary_color VARCHAR(7) NOT NULL,    -- #FF0000
    secondary_color VARCHAR(7) NOT NULL,  -- #FFFF00
    accent_color VARCHAR(7) NULL,
    background_color VARCHAR(7) DEFAULT '#FFFFFF',
    text_color VARCHAR(7) DEFAULT '#000000',
    
    -- Disponibilidad
    min_plan_id TINYINT UNSIGNED DEFAULT 1,  -- Desde qué plan está disponible
    
    -- Categoría (para organizar)
    category VARCHAR(50) NULL,  -- 'clasico', 'marca', 'segmento'
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Seed Data (Primeras 5 paletas):**
```sql
INSERT INTO color_palettes (name, slug, primary_color, secondary_color, category, min_plan_id) VALUES
('Clásico Azul', 'clasico-azul', '#0066CC', '#FFFFFF', 'clasico', 1),
('Calidez Naranja', 'calidez-naranja', '#FF6600', '#FFCC00', 'clasico', 1),
('Natural Verde', 'natural-verde', '#339933', '#663300', 'clasico', 1),
('Elegante Negro', 'elegante-negro', '#000000', '#FFD700', 'clasico', 1),
('Fresco Celeste', 'fresco-celeste', '#00BFFF', '#FFFFFF', 'clasico', 1),

('McDonald\'s', 'mcdonalds', '#DA291C', '#FFC72C', 'marca', 2),
('Starbucks', 'starbucks', '#00704A', '#FFFFFF', 'marca', 2),
('Home Depot', 'home-depot', '#F96302', '#FFFFFF', 'marca', 2),
('Pizza Hut', 'pizza-hut', '#EE3124', '#000000', 'marca', 2),
('Tech Blue', 'tech-blue', '#001F3F', '#00D9FF', 'marca', 2);
```

---

## 📐 RELACIONES Y CONSTRAINTS

### Cascade Rules:
- `users` → `tenants`: ON DELETE CASCADE
- `tenants` → `products`: ON DELETE CASCADE
- `tenants` → `services`: ON DELETE CASCADE
- `tenants` → `tenant_customization`: ON DELETE CASCADE
- `tenants` → `analytics_events`: ON DELETE CASCADE
- `tenants` → `invoices`: ON DELETE CASCADE

### Unique Constraints:
- `tenants.subdomain` UNIQUE
- `tenants.custom_domain` UNIQUE
- `users.email` UNIQUE
- `invoices.invoice_number` UNIQUE

---

## 🔄 QUERIES COMUNES

### 1. Obtener tenant por dominio:
```sql
-- Subdominio
SELECT * FROM tenants 
WHERE subdomain = 'joseburguer' 
  AND base_domain = 'menu.vip' 
  AND status = 'active';

-- Custom domain
SELECT * FROM tenants 
WHERE custom_domain = 'www.joseburguer.com' 
  AND status = 'active';
```

### 2. Productos activos de un tenant:
```sql
SELECT * FROM products 
WHERE tenant_id = :tenant_id 
  AND is_active = TRUE 
ORDER BY position ASC, created_at DESC;
```

### 3. Analytics del día:
```sql
SELECT 
    event_type,
    COUNT(*) as total
FROM analytics_events
WHERE tenant_id = :tenant_id
  AND event_date = CURDATE()
GROUP BY event_type;
```

### 4. Top 3 productos más clickeados (mes actual):
```sql
SELECT 
    p.id,
    p.name,
    COUNT(ae.id) as clicks
FROM products p
LEFT JOIN analytics_events ae ON ae.reference_id = p.id 
    AND ae.reference_type = 'product'
    AND ae.event_type = 'product_click'
    AND MONTH(ae.event_date) = MONTH(CURDATE())
WHERE p.tenant_id = :tenant_id
  AND p.is_active = TRUE
GROUP BY p.id, p.name
ORDER BY clicks DESC
LIMIT 3;
```

### 5. Tasa del dólar activa:
```sql
SELECT rate FROM dollar_rates
WHERE is_active = TRUE
  AND effective_from <= NOW()
  AND (effective_until IS NULL OR effective_until > NOW())
ORDER BY effective_from DESC
LIMIT 1;
```

---

## 🚀 MIGRACIONES LARAVEL

### Orden de ejecución:
1. `create_users_table`
2. `create_plans_table`
3. `create_tenants_table`
4. `create_products_table`
5. `create_services_table`
6. `create_tenant_customization_table`
7. `create_analytics_events_table`
8. `create_dollar_rates_table`
9. `create_invoices_table`
10. `create_color_palettes_table`

---

**FIN DEL SCHEMA**  
Total de tablas: 10  
Última actualización: 2026-02-15
