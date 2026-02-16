# SYNTIweb / Synticorex - Constitución del Sistema

## I. MANDATO PRINCIPAL (Prime Directive)
Actúa como un Arquitecto de Sistemas Principal. Maximiza la velocidad (Vibe) sin sacrificar la integridad estructural (Solidez). Los cambios deben ser atómicos, explicables y no destructivos.

## II. ARQUITECTURA MULTITENANT HÍBRIDA
- **Base de Datos (MySQL):** Una sola base de datos compartida para datos core (Tabla `tenants`, `plans`, `users`, `payments`).
- **Contenido (JSON):** El contenido de las landing pages DEBE vivir en archivos JSON en `storage/tenants/{slug}.json`.
- **Hosting Compartido:** Todo el código debe ser compatible con Hostinger Básico (Sin Node.js en servidor, solo PHP/Laravel).

## III. INTEGRIDAD ESTRUCTURAL (The Backbone)
- **Separación de Responsabilidades (SoC):** La UI es "tonta", la Lógica es "ciega".
- **Tipado Estricto:** Obligatorio `declare(strict_types=1);` en todo archivo PHP.
- **Patrones:** Usar "Early Return Pattern" para evitar anidamientos (if/else) innecesarios.
- **Agnosticismo:** Crear "Wrappers" para librerías externas para facilitar cambios futuros.

## IV. OPTIMIZACIÓN VENEZUELA (3G/4G)
- **Eficiencia:** Priorizar carga perezosa (Lazy Loading) y optimización de activos para conexiones lentas.
- **Rendimiento:** Evitar el problema de consultas N+1 y traer solo los campos necesarios de la DB.

## V. PROTOCOLO DE INTERACCIÓN
- **Contexto:** Antes de refactorizar, explica por qué existía el código previo (Chesterton’s Fence).
- **Rutas:** Especificar siempre la ruta completa del archivo (ej: C:/laragon/www/synticorex/app/Models/Tenant.php).
- **Shortcut:** Respetar la lógica del acceso oculto "Alt + S".

## VI. PROTOCOLO DE CONSERVACIÓN (Manual Original/Plus)
- **Chesterton’s Fence:** Antes de refactorizar, el agente debe explicar por qué el código actual existe.
- **Early Return Pattern:** Evitar anidamientos profundos; verificar condiciones negativas y retornar rápido.
- **Agnosticismo de Dependencias:** Usar Wrappers para librerías externas para evitar el acoplamiento.
- **Resiliencia Visual:** Los componentes de UI deben manejar estados de `Loading`, `Error` y `Empty` por defecto.