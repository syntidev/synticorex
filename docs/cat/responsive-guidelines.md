# CAT Responsive Guidelines (Pendientes de Pulido)

Fecha: 2026-03-14
Scope: landing CAT (`resources/views/landing/templates/catalog.blade.php`) y modal de producto CAT dashboard (`resources/views/dashboard/components/catalog-products-section.blade.php`).

## Estado actual

- Busqueda funcional (parcial y tolerante a acentos) con visibilidad inmediata de resultados.
- Header compacto integrado: logo + lupa + categorias + acciones.
- Menu movil de categorias por overlay.
- Modal publico de producto simplificado (imagen principal) y ajustes de overflow.

## Problemas observados (movil)

1. En anchos 360-412 px algunas cards se perciben altas y densas.
2. Header movil requiere afinacion final de espaciado y jerarquia visual.
3. Modal de producto aun puede requerir un ultimo ajuste de altura/CTA para evitar scroll inicial.

## Recomendacion de implementacion (siguiente iteracion)

1. Mantener hero de 3 componentes (diferenciador visual), pero bajar altura en movil.
2. Grid movil en 2 columnas solo desde umbral seguro (>=390 px) y modo compacto real:
- Imagen mas contenida por breakpoint.
- Tipografia y badges reducidos en movil.
- CTA con altura fija y padding menor.
3. Modal producto (publico):
- Garantizar que el boton principal quede visible sin scroll al abrir.
- Limitar altura de descripcion en movil.
- Mantener sin galeria lateral para evitar ruido.
4. Header movil:
- Logo truncado controlado.
- Boton categorias con touch target >=44 px.
- Lupa compacta con comportamiento consistente (abrir/cerrar/limpiar).

## Criterios de aceptacion

1. iPhone SE/12/14 y Samsung S20/S22 sin overflow horizontal.
2. Grid movil legible y balanceado en 2 columnas cuando aplique.
3. Busqueda muestra resultados en primer plano sin confusion visual.
4. Modal producto abre con CTA visible en primer viewport.

## Nota tecnica

Esto es un problema de responsive/UI, no de stack (Laravel/Node/iOS/Android). Es completamente resoluble en web app con reglas mobile-first y breakpoints correctos.
