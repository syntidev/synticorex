# TENANT 18 (Urban Menu) - INFORMACIÓN PARA REPLICACIÓN

## � RESUMEN RÁPIDO

| Item | Valor |
|------|-------|
| **Nombre Original** | Urban Menu |
| **Blueprint** | food (comida rápida) |
| **Productos** | 28 (Perros Calientes, Arepas, Tequeños, etc.) |
| **Servicios** | 0 |
| **Plan** | food-crecimiento (100 productos max) |
| **Theme** | fuego-urbano |
| **Logo** | Sí (logo.webp en storage) |
| **Hero Images** | 5 imágenes Unsplash (fullscreen) |
| **Redes Sociales** | TikTok, Facebook, Instagram (urbanmenu.ve) |
| **Métodos Pago** | Pago Móvil, Efectivo, Biopago, Zelle |

## 📋 INSTRUCCIONES PARA CREAR NUEVO DEMO

### Opción 1: Script automático (RECOMENDADO)

1. **Configura el script** `clone_urban_menu_demo.php`:
   ```bash
   Abre: c:\laragon\www\synticorex\clone_urban_menu_demo.php
   Modifica la sección "CONFIGURACIÓN":
   ```

2. **Parámetros a cambiar:**
   - `business_name`: tu nombre para el nuevo demo
   - `subdomain`: subdomain único (ej: "urbanmenu2", "urbanmenu_carlos")
   - `whatsapp_sales`: número de WhatsApp (opcional)
   - `city`: ciudad (opcional)

3. **Ejecuta el script:**
   ```bash
   php artisan tinker --execute="require 'clone_urban_menu_demo.php';"
   ```

4. **Verifica el resultado:**
   ```bash
   http://{subdomain}.synticorex.local
   ```

---

## 📊 ESTRUCTURA CLONADA

### 28 Productos por categoría:
- **Perros Calientes** (4): Perro Caraqueño, Hot Dog Doble, etc.
- **Arepas** (4): Arepa Queso, Arepa Pollo, etc.  
- **Tequeños** (3)
- **Empanadas** (3)
- **Sándwiches** (3)
- **Bebidas** (3)
- **Postres** (5)

### Datos Copiados:
✅ Productos completos (nombre, precio USD, descripción, imagen)
✅ Categorías y subcategorías
✅ Logo (si existe en storage)
✅ Hero images (5 imágenes fullscreen)
✅ Social networks (TikTok, FB, IG)
✅ Métodos de pago
✅ Theme (fuego-urbano)
✅ Settings currency & features
✅ Layout hero (fullscreen)

### NO Copiado (personalizar nuevo demo):
❌ Dirección
❌ Teléfono principal
❌ Logo custom (usar default)
❌ Horarios (business_hours)
❌ Meta tags SEO

---

## 🔧 SI QUIERES CREAR MANUAL (SQL)

### 1. Crear tenant:
```sql
INSERT INTO tenants (
  user_id, business_name, plan_id, subdomain, blueprint,
  business_segment, slogan, is_demo, demo_product,
  whatsapp_sales, whatsapp_active, status, is_open,
  currency_display, color_palette_id, created_at
) VALUES (
  1, 'Urban Menu Clone', 13, 'urbanmenu2', 'food',
  'Comida Rápida Venezolana', 'Lo criollo, lo urbano, lo delicioso',
  1, 'food', '+584141234567', 'sales', 'active', 1,
  'both', 1, NOW()
);
```

### 2. Clonar productos (reemplaza {new_tenant_id} e {old_tenant_id}):
```sql
INSERT INTO products (
  tenant_id, name, price_usd, description, image_url, 
  category_name, is_active, is_featured, badge, created_at
)
SELECT 
  {new_tenant_id}, name, price_usd, description, image_url,
  category_name, is_active, is_featured, badge, NOW()
FROM products 
WHERE tenant_id = 18;
```

### 3. Clonar customización:
```sql
INSERT INTO tenant_customization (
  tenant_id, logo_filename, hero_main_filename,
  hero_layout, theme_slug, social_networks, payment_methods, created_at
)
SELECT 
  {new_tenant_id}, logo_filename, hero_main_filename,
  hero_layout, theme_slug, social_networks, payment_methods, NOW()
FROM tenant_customization
WHERE tenant_id = 18;
```

---

## 📲 DATOS DE CONEXIÓN TENANT 18

| Campo | Valor |
|-------|-------|
| **ID** | 18 |
| **Subdomain** | urbanmenu |
| **Blueprint** | food |
| **Plan ID** | 13 (food-crecimiento) |
| **Estado** | active |
| **Demo** | Sí |
| **Abierto** | Sí |
| **WhatsApp** | +584141234567 |
| **Moneda Display** | both (toggle Bs./REF) |

---

## 💡 TIPS

1. **Cambiar solo subdomain** para crear rápidamente múltiples demos
2. **Reutilizar logo** copiando de storage/tenants/18/
3. **Las imágenes son URLs** (Unsplash), no necesitas copiar
4. **El plan 13** permite hasta 100 productos (Urban Menu usa 28)
5. **Theme "fuego-urbano"** es automático con blueprint food

---

**Archivo Script**: `clone_urban_menu_demo.php`  
**Ejecutar**: `php artisan tinker --execute="require 'clone_urban_menu_demo.php';"`

