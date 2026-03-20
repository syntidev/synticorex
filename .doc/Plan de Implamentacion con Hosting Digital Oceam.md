# Estrategia Técnica SYNTI.dev: Arquitectura Híbrida (JSON + Multi-dominio)

## 1. El Core: DigitalOcean (Droplet)
* **Plan:** Basic / Premium AMD (2 GB RAM / 1 vCPU) - $12.00/mes.
* **Por qué:** Al usar archivos `.json` para los clientes, la RAM no se desperdiciará en procesos de MySQL. El almacenamiento NVMe de DO hará que la lectura de esos JSON sea casi instantánea.

## 2. Gestión de Dominios (El "Cuarteto" de Productos)
Tu configuración de 4 dominios principales en el mismo servidor es totalmente posible y eficiente:
1. `syntiweb.com` (Administración / Landing)
2. `comida.pro` (Ejemplo para Producto Gastronómico)
3. `tienda.top` (Ejemplo para Mini-tienda)
4. `webrapida.com` (Ejemplo para Páginas Web)

* **Implementación:** Coolify permite agregar "N" dominios a un mismo contenedor de Laravel. Solo debes configurar los 4 dominios en Cloudflare apuntando a la misma IP.

## 3. Lógica Multitenant (Wildcard + JSON)
* **El Flujo:** Cuando entra una petición a `cliente1.comida.pro`, Laravel detecta el host, busca el archivo `storage/tenants/cliente1.json` y carga la configuración (colores, productos, logo).
* **SSL Wildcard:** Necesitarás activar el Wildcard SSL en Cloudflare y Coolify para cada uno de tus 4 dominios principales (`*.syntiweb.com`, `*.comida.pro`, etc.). Coolify lo hace automáticamente mediante el DNS Challenge.

## 4. Ventajas de esta Arquitectura
* **Carga de Servidor:** Mínima. El consumo de CPU solo subirá cuando se lea/escriba el JSON, pero no habrá procesos "durmientes" de bases de datos por cada cliente.
* **Portabilidad:** Si decides mudarte de servidor, solo mueves la carpeta de archivos `.json`. Es la máxima expresión de "Zero-Footprint".
* **Escalabilidad:** Podrías tener 500 clientes en JSON en el mismo servidor de $12 antes de notar lentitud.

## 5. Checklist de Seguridad para JSON
* **Permisos:** Asegúrate de que la carpeta donde guardas los JSON no sea accesible públicamente desde la URL (debe estar fuera de `/public`).
* **Backup:** Configura un script simple (o usa los backups de DigitalOcean) para respaldar la carpeta de datos de los clientes. Si el JSON se corrompe, pierdes al cliente.



## 6. Resumen de Instalación para .md
1. **Servidor:** Ubuntu 24.04 (DigitalOcean).
2. **Panel:** Coolify (Gestiona los 4 dominios y el despliegue de GitHub).
3. **DNS:** Cloudflare (Maneja los 4 dominios y los registros Wildcard `*`).
4. **App:** Laravel 12 configurado para leer del Filesystem según el `Header: Host`.