# SYNTIWEB — IDENTIDAD DE MARCA (DEFINITIVO)
# NO MODIFICAR SIN INSTRUCCIÓN EXPLÍCITA DEL ARQUITECTO

## Colores oficiales
AZUL_MARCA   = #4A80E4   ← acento principal, NUNCA cambia
NAVY_OSCURO  = #1a1a1a
BLANCO       = #FFFFFF

## Logo símbolo (bracket + círculo)
Fondo claro:  bracket #1a1a1a | círculo #4A80E4
Fondo oscuro: bracket #FFFFFF  | círculo #4A80E4

## Texto wordmark
Fondo claro:  SYNTI #1a1a1a | web #4A80E4
Fondo oscuro: SYNTI #4A80E4  | web #FFFFFF

## Código HTML navbar — FONDO CLARO
```html
<a href="/" class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="32" height="32">
    <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#1a1a1a"/>
    <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
  </svg>
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
  </span>
</a>
```

## Código HTML navbar — FONDO OSCURO
```html
<a href="/" class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="32" height="32">
    <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#FFFFFF"/>
    <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
  </svg>
  <span class="font-bold text-lg tracking-tight">
    <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
  </span>
</a>
```

## Archivos SVG por contexto
syntiweb-logo-positive.svg      → fondo claro
syntiweb-logo-negative.svg      → fondo oscuro  
syntiweb-logo-adaptive.svg      → detecta automático
syntiweb-logo-flat-positive.svg → favicon/apps
syntiweb-logo-monochrome.svg    → sellos/legal