# 📰 Blog Preline — Maqueta Local

Prototipo de blog construido con **HTML + PHP plano + Tailwind CDN + Preline UI**.
Diseñado para ser migrado a Laravel + Blade cuando esté listo.

---

## 🚀 Cómo levantar localmente

### Opción A — Con Laragon (recomendado)
1. Copia esta carpeta en: `C:\laragon\www\blog\`
2. Abre Laragon → Start All
3. Entra a: `http://blog.test`

### Opción B — PHP built-in server (sin instalar nada extra)
Requiere tener PHP instalado (viene con Laragon).

```bash
cd blog-preline
php -S localhost:8080
```
Abre: `http://localhost:8080`

### Opción C — Archivo HTML estático
No aplica directamente porque usamos `include` de PHP,
pero con la Opción B es igual de rápido.

---

## 📁 Estructura de archivos

```
blog-preline/
├── index.php          — Blog público (featured + 2 Preline + banner + 2 small)
├── post.php           — Vista individual con sidebar Preline + CTA contextual
├── admin-posts.php    — Panel: lista todos los posts, editar / borrar
├── admin-editor.php   — Editor completo (Quill + imagen + SEO preview + tags)
├── actions/
│   ├── save-post.php  — Guarda nuevo o actualiza en JSON
│   └── delete-post.php — Elimina del JSON con confirmación
└── data/posts.json    — Los 4 posts reales sobre SYNTIweb
---

## 🔄 Cómo migrar a Laravel + Blade

| Archivo PHP plano       | Equivalente en Laravel/Blade         |
|-------------------------|--------------------------------------|
| `includes/header.php`   | `resources/views/layouts/app.blade.php` |
| `includes/footer.php`   | Sección `@push('scripts')` del layout |
| `index.php`             | `resources/views/blog/index.blade.php` |
| `post.php`              | `resources/views/blog/show.blade.php`  |
| Arrays PHP `$posts`     | Colecciones Eloquent del controlador   |
| `include 'header.php'`  | `@extends('layouts.app')`             |
| `<?= $var ?>`           | `{{ $var }}`                          |

---

## 🎨 Stack usado
- **Tailwind CSS** — vía CDN (en producción usar compilado)
- **Preline UI** — vía CDN
- **Google Fonts** — Lora + DM Sans
- **PHP** — solo para `include` y arrays de datos de prueba
