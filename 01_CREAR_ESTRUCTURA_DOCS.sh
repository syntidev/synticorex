#!/bin/bash
# ============================================================
# SYNTIWEB — Crear estructura /docs para Mintlify
# Ejecutar desde: C:\laragon\www\synticorex\
# Comando: bash 01_CREAR_ESTRUCTURA_DOCS.sh
# ============================================================

echo "📁 Creando estructura /docs para Mintlify..."

# Carpetas principales
mkdir -p docs/shared
mkdir -p docs/studio
mkdir -p docs/food
mkdir -p docs/cat
mkdir -p docs/logo

# Archivos shared
touch docs/shared/introduccion.mdx
touch docs/shared/quickstart.mdx
touch docs/shared/cuenta-y-planes.mdx
touch docs/shared/moneda-y-precios.mdx
touch docs/shared/dominio-y-subdominio.mdx
touch docs/shared/whatsapp.mdx
touch docs/shared/imagenes.mdx
touch docs/shared/faq.mdx
touch docs/shared/glosario.mdx
touch docs/shared/troubleshooting.mdx

# Archivos studio
touch docs/studio/que-es-studio.mdx
touch docs/studio/wizard-onboarding.mdx
touch docs/studio/dashboard.mdx
touch docs/studio/hero-y-banner.mdx
touch docs/studio/productos-y-servicios.mdx
touch docs/studio/paletas-de-color.mdx
touch docs/studio/qr-sticker.mdx
touch docs/studio/seo-automatico.mdx

# Archivos food
touch docs/food/que-es-food.mdx
touch docs/food/wizard-food.mdx
touch docs/food/categorias-menu.mdx
touch docs/food/items-lista.mdx
touch docs/food/pedido-rapido-whatsapp.mdx
touch docs/food/extras-opciones.mdx
touch docs/food/horarios-atencion.mdx

# Archivos cat
touch docs/cat/que-es-cat.mdx
touch docs/cat/wizard-cat.mdx
touch docs/cat/catalogo-productos.mdx
touch docs/cat/carrito-whatsapp.mdx
touch docs/cat/mini-order-sc.mdx
touch docs/cat/badges-productos.mdx
touch docs/cat/variantes.mdx

# Archivos raíz docs
touch docs/mint.json
touch docs/context.md

echo ""
echo "✅ Estructura creada:"
find docs -type f | sort

echo ""
echo "📌 Próximo paso:"
echo "   1. Pegar contenido de mint.json"
echo "   2. Pegar contenido de context.md"
echo "   3. git add docs/ && git commit -m 'docs: estructura inicial Mintlify'"
echo "   4. Conectar repo en dashboard.mintlify.com → directorio: docs"
