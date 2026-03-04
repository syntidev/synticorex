# Script para regenerar 25 temas CSS con valores hex exactos

# Función para convertir hex a RGB
function ConvertHexToRGB {
    param([string]$hex)
    $hex = $hex -replace '^#', ''
    $r = [Convert]::ToInt32($hex.Substring(0, 2), 16)
    $g = [Convert]::ToInt32($hex.Substring(2, 2), 16)
    $b = [Convert]::ToInt32($hex.Substring(4, 2), 16)
    return @($r, $g, $b)
}

# Función para convertir RGB a hex
function ConvertRGBToHex {
    param([int]$r, [int]$g, [int]$b)
    return "#{0:X2}{1:X2}{2:X2}" -f [Math]::Max(0, [Math]::Min(255, $r)), 
                                      [Math]::Max(0, [Math]::Min(255, $g)),
                                      [Math]::Max(0, [Math]::Min(255, $b))
}

# Función para oscurecer un color 10%
function DarkenColor {
    param([string]$hex)
    $rgb = ConvertHexToRGB $hex
    $r = [int]($rgb[0] * 0.9)
    $g = [int]($rgb[1] * 0.9)
    $b = [int]($rgb[2] * 0.9)
    return ConvertRGBToHex $r $g $b
}

# Diccionario de temas: [nombre] = @{primary, light, secondary, border}
$temas = @{
    "sabor-tradicional" = @{primary = "#C62828"; light = "#F5E6CC"; secondary = "#556B2F"; border = "#E8D4B0"}
    "fuego-urbano" = @{primary = "#F57C00"; light = "#111111"; secondary = "#D32F2F"; border = "#424242"}
    "parrilla-moderna" = @{primary = "#8E0000"; light = "#424242"; secondary = "#FFFFFF"; border = "#757575"}
    "casa-latina" = @{primary = "#C65D3B"; light = "#FFF1DC"; secondary = "#D4A017"; border = "#D4A017"}
    
    "rosa-vainilla" = @{primary = "#F8BBD0"; light = "#FFF3E0"; secondary = "#E0E0E0"; border = "#F5E6CC"}
    "pistacho-suave" = @{primary = "#A8C686"; light = "#F5E6CC"; secondary = "#FFFFFF"; border = "#E0E0E0"}
    "cielo-dulce" = @{primary = "#B3E5FC"; light = "#FFFFFF"; secondary = "#E1BEE7"; border = "#E0E0E0"}
    "chocolate-caramelo" = @{primary = "#5D4037"; light = "#FFF3E0"; secondary = "#C49A6C"; border = "#8D6E63"}
    
    "azul-confianza" = @{primary = "#1976D2"; light = "#FFFFFF"; secondary = "#E3F2FD"; border = "#BBDEFB"}
    "verde-calma" = @{primary = "#66BB6A"; light = "#FFFFFF"; secondary = "#C8E6C9"; border = "#A5D6A7"}
    
    "azul-profesional" = @{primary = "#0D47A1"; light = "#FFFFFF"; secondary = "#ECEFF1"; border = "#B3E5FC"}
    "ejecutivo-oscuro" = @{primary = "#1C1F26"; light = "#455A64"; secondary = "#CFD8DC"; border = "#90A4AE"}
    "prestigio-clasico" = @{primary = "#1A237E"; light = "#FFFFFF"; secondary = "#C9A227"; border = "#FFD54F"}
    
    "industrial-pro" = @{primary = "#1565C0"; light = "#263238"; secondary = "#FF6F00"; border = "#FF9100"}
    "negro-impacto" = @{primary = "#000000"; light = "#424242"; secondary = "#FFD600"; border = "#757575"}
    "metal-urbano" = @{primary = "#757575"; light = "#212121"; secondary = "#B71C1C"; border = "#EF5350"}
    
    "nude-elegante" = @{primary = "#D7CCC8"; light = "#000000"; secondary = "#C9A227"; border = "#FFD54F"}
    "rosa-studio" = @{primary = "#F48FB1"; light = "#ECEFF1"; secondary = "#B0BEC5"; border = "#90A4AE"}
    "barber-clasico" = @{primary = "#1B4332"; light = "#F1E9DA"; secondary = "#7B2D26"; border = "#A1887F"}
    
    "fuerza-roja" = @{primary = "#D50000"; light = "#000000"; secondary = "#424242"; border = "#757575"}
    "verde-potencia" = @{primary = "#00E676"; light = "#000000"; secondary = "#212121"; border = "#424242"}
    "azul-electrico" = @{primary = "#2962FF"; light = "#1C1F26"; secondary = "#455A64"; border = "#CFD8DC"}
    
    "azul-academico" = @{primary = "#1565C0"; light = "#FFFFFF"; secondary = "#FB8C00"; border = "#FFB74D"}
    "verde-progreso" = @{primary = "#2E7D32"; light = "#F1F8E9"; secondary = "#1565C0"; border = "#BBDEFB"}
    "claro-simple" = @{primary = "#90CAF9"; light = "#FFFFFF"; secondary = "#CFD8DC"; border = "#B3E5FC"}
}

# Path base
$baseDir = "c:\laragon\www\synticorex\node_modules\preline\css\themes"

foreach ($tema in $temas.GetEnumerator()) {
    $themeName = $tema.Name
    $colors = $tema.Value
    $filePath = Join-Path $baseDir "$themeName.css"
    
    # Calcular -700 (10% más oscuro que 600)
    $primary700 = DarkenColor $colors.primary
    
    # Leer archivo actual
    $content = Get-Content $filePath -Raw
    
    # Extraer todo antes de :root[data-theme...
    $beforeSelector = $content -split '(:root\[data-theme="theme-[^"]+"\]|(?:\[data-theme="theme-[^"]+"\]))', 2 | Select-Object -First 1
    
    # Extraer todo desde [data-theme...].dark en adelante
    if ($content -match "\[data-theme=`"theme-[^`"]+`"\]\.dark\s*\{") {
        $darkStart = $matches[0]
        $afterSelector = $content.Substring($content.IndexOf($darkStart))
    } else {
        $afterSelector = ""
    }
    
    # Generar nuevo selector :root[data-theme...]
    $newSelector = @"
:root[data-theme="theme-$themeName"],
[data-theme="theme-$themeName"] {

  /* ============================================ */
  /* GLOBAL SURFACES + TEXT                       */
  /* ============================================ */
  
  --background: var(--color-white);
  --background-1: var(--color-$themeName-gray-50);
  --background-2: var(--color-$themeName-gray-100);
  --background-plain: var(--color-white);
  --foreground: var(--color-$themeName-gray-800);
  --foreground-inverse: var(--color-white);
  
  --inverse: var(--color-$themeName-gray-800);
  
  /* ============================================ */
  /* BORDERS (Full Scale)                         */
  /* ============================================ */
  
  --border: $($colors.border);
  --border-line-inverse: var(--color-white);
  --border-line-1: var(--color-$themeName-gray-100);
  --border-line-2: var(--color-$themeName-gray-200);
  --border-line-3: var(--color-$themeName-gray-300);
  --border-line-4: var(--color-$themeName-gray-400);
  --border-line-5: var(--color-$themeName-gray-500);
  --border-line-6: var(--color-$themeName-gray-600);
  --border-line-7: var(--color-$themeName-gray-700);
  --border-line-8: var(--color-$themeName-gray-800);
  
  /* ============================================ */
  /* PRIMARY RAMP (Full 11-shade scale)           */
  /* ============================================ */
  
  --primary-50: $($colors.light);
  --primary-100: var(--color-$themeName-100);
  --primary-200: var(--color-$themeName-200);
  --primary-300: var(--color-$themeName-300);
  --primary-400: var(--color-$themeName-400);
  --primary-500: $($colors.primary);
  --primary-600: $($colors.primary);
  --primary-700: $primary700;
  --primary-800: var(--color-$themeName-800);
  --primary-900: var(--color-$themeName-900);
  --primary-950: var(--color-$themeName-950);
  
  /* PRIMARY STATES */
  --primary: var(--color-primary-600);
  --primary-line: transparent;
  --primary-foreground: var(--color-white);
  --primary-hover: var(--color-primary-700);
  --primary-focus: var(--color-primary-700);
  --primary-active: var(--color-primary-700);
  --primary-checked: var(--color-primary-600);
  
  /* ============================================ */
  /* SECONDARY                                    */
  /* ============================================ */
  
  --secondary: $($colors.secondary);
  --secondary-line: transparent;
  --secondary-foreground: var(--color-white);
  --secondary-hover: var(--color-$themeName-gray-800);
  --secondary-focus: var(--color-$themeName-gray-800);
  --secondary-active: var(--color-$themeName-gray-800);
  
  /* ============================================ */
  /* LAYER                                        */
  /* ============================================ */
  
  --layer: var(--color-white);
  --layer-line: var(--color-$themeName-gray-200);
  --layer-foreground: var(--color-$themeName-gray-800);
  --layer-hover: var(--color-$themeName-gray-50);
  --layer-focus: var(--color-$themeName-gray-50);
  --layer-active: var(--color-$themeName-gray-50);
  
  /* ============================================ */
  /* SURFACE                                      */
  /* ============================================ */
  
  --surface: var(--color-$themeName-gray-100);
  --surface-1: var(--color-$themeName-gray-200);
  --surface-2: var(--color-$themeName-gray-300);
  --surface-3: var(--color-$themeName-gray-400);
  --surface-4: var(--color-$themeName-gray-500);
  --surface-5: var(--color-$themeName-gray-600);
  --surface-line: transparent;
  --surface-foreground: var(--color-$themeName-gray-800);
  --surface-hover: var(--color-$themeName-gray-200);
  --surface-focus: var(--color-$themeName-gray-200);
  --surface-active: var(--color-$themeName-gray-200);
  
  /* ============================================ */
  /* MUTED                                        */
  /* ============================================ */
  
  --muted: var(--color-$themeName-gray-50);
  --muted-foreground: var(--color-$themeName-gray-500);
  --muted-foreground-1: var(--color-$themeName-gray-600);
  --muted-foreground-2: var(--color-$themeName-gray-700);
  --muted-hover: var(--color-$themeName-gray-100);
  --muted-focus: var(--color-$themeName-gray-100);
  --muted-active: var(--color-$themeName-gray-100);

  --navbar: var(--color-white);
  --navbar-line: var(--color-$themeName-gray-200);
  --navbar-divider: var(--color-$themeName-gray-200);
  --navbar-nav-foreground: var(--color-$themeName-gray-800);
  --navbar-nav-hover: var(--color-$themeName-gray-100);
  --navbar-nav-focus: var(--color-$themeName-gray-100);
  --navbar-nav-active: var(--color-$themeName-gray-100);
  --navbar-nav-list-divider: var(--color-$themeName-gray-200);
  --navbar-inverse: var(--color-$themeName-gray-950);
  
  --navbar-1: var(--color-$themeName-gray-50);
  --navbar-1-line: var(--color-$themeName-gray-200);
  --navbar-1-divider: var(--color-$themeName-gray-200);
  --navbar-1-nav-foreground: var(--color-$themeName-gray-800);
  --navbar-1-nav-hover: var(--color-$themeName-gray-100);
  --navbar-1-nav-focus: var(--color-$themeName-gray-100);
  --navbar-1-nav-active: var(--color-$themeName-gray-100);
  --navbar-1-nav-list-divider: var(--color-$themeName-gray-200);

  --navbar-2: var(--color-$themeName-gray-50);
  --navbar-2-divider: var(--color-$themeName-gray-200);
  --navbar-2-nav-foreground: var(--color-$themeName-gray-800);
  --navbar-2-nav-hover: var(--color-$themeName-gray-100);
  --navbar-2-nav-focus: var(--color-$themeName-gray-100);
  --navbar-2-nav-active: var(--color-$themeName-gray-100);
  --navbar-2-nav-list-divider: var(--color-$themeName-gray-100);

  --sidebar: var(--color-white);
  --sidebar-line: var(--color-$themeName-gray-200);
  --sidebar-divider: var(--color-$themeName-gray-200);
  --sidebar-nav-foreground: var(--color-$themeName-gray-800);
  --sidebar-nav-hover: var(--color-$themeName-gray-100);
  --sidebar-nav-focus: var(--color-$themeName-gray-100);
  --sidebar-nav-active: var(--color-$themeName-gray-100);
  --sidebar-nav-list-divider: var(--color-$themeName-gray-200);
  --sidebar-inverse: var(--color-$themeName-gray-950);

  --sidebar-1: var(--color-$themeName-gray-50);
  --sidebar-1-line: var(--color-$themeName-gray-200);
  --sidebar-1-divider: var(--color-$themeName-gray-200);
  --sidebar-1-nav-foreground: var(--color-$themeName-gray-800);
  --sidebar-1-nav-hover: var(--color-$themeName-gray-100);
  --sidebar-1-nav-focus: var(--color-$themeName-gray-100);
  --sidebar-1-nav-active: var(--color-$themeName-gray-100);
  --sidebar-1-nav-list-divider: var(--color-$themeName-gray-200);
  
  --sidebar-2: var(--color-$themeName-gray-50);
  --sidebar-2-divider: var(--color-$themeName-gray-200);
  --sidebar-2-nav-foreground: var(--color-$themeName-gray-800);
  --sidebar-2-nav-hover: var(--color-$themeName-gray-100);
  --sidebar-2-nav-focus: var(--color-$themeName-gray-100);
  --sidebar-2-nav-active: var(--color-$themeName-gray-100);
  --sidebar-2-nav-list-divider: var(--color-$themeName-gray-100);

  --card: var(--color-white);
  --card-line: var(--color-$themeName-gray-200);
  --card-divider: var(--color-$themeName-gray-200);
  --card-header: var(--color-$themeName-gray-100);
  --card-footer: var(--color-$themeName-gray-100);
  --card-inverse: var(--color-$themeName-gray-950);

  --dropdown: var(--color-white);
  --dropdown-1: var(--color-$themeName-gray-50);
  --dropdown-line: var(--color-$themeName-gray-200);
  --dropdown-divider: var(--color-$themeName-gray-100);
  --dropdown-header: var(--color-$themeName-gray-100);
  --dropdown-footer: var(--color-$themeName-gray-100);
  --dropdown-item-foreground: var(--color-$themeName-gray-800);
  --dropdown-item-hover: var(--color-$themeName-gray-100);
  --dropdown-item-focus: var(--color-$themeName-gray-100);
  --dropdown-item-active: var(--color-$themeName-gray-100);
  --dropdown-inverse: var(--color-$themeName-gray-950);

  --select: var(--color-white);
  --select-1: var(--color-$themeName-gray-50);
  --select-line: var(--color-$themeName-gray-200);
  --select-item-foreground: var(--color-$themeName-gray-800);
  --select-item-hover: var(--color-$themeName-gray-100);
  --select-item-focus: var(--color-$themeName-gray-100);
  --select-item-active: var(--color-$themeName-gray-100);
  --select-inverse: var(--color-$themeName-gray-950);

  --overlay: var(--color-white);
  --overlay-divider: var(--color-$themeName-gray-200);
  --overlay-header: var(--color-$themeName-gray-100);
  --overlay-footer: var(--color-$themeName-gray-100);
  --overlay-inverse: var(--color-$themeName-gray-950);

  --popover: var(--color-white);
  --popover-line: var(--color-$themeName-gray-200);

  --tooltip: var(--color-$themeName-gray-800);
  --tooltip-foreground: var(--color-white);

  --table-line: var(--color-$themeName-gray-200);

  --footer: var(--color-white);
  --footer-line: var(--color-$themeName-gray-200);
  --footer-inverse: var(--color-$themeName-gray-950);
  
  --scrollbar-track: var(--color-$themeName-gray-200);
  --scrollbar-thumb: var(--color-$themeName-gray-400);
  --scrollbar-track-inverse: var(--color-$themeName-gray-400);
  --scrollbar-thumb-inverse: var(--color-$themeName-gray-700);

  --chart-primary: var(--color-primary-600);
  --chart-colors-primary: var(--color-primary-600);
  --chart-colors-primary-inverse: var(--color-primary-500);
  --chart-colors-primary-hex: var(--color-primary-600);
  --chart-colors-primary-hex-inverse: var(--color-primary-500);
  --chart-1: var(--color-primary-50);
  --chart-colors-chart-1: var(--color-primary-50);
  --chart-colors-chart-1-inverse: var(--color-primary-50);
  --chart-colors-chart-1-hex: var(--color-primary-50);
  --chart-colors-chart-1-hex-inverse: var(--color-primary-50);
  --chart-2: var(--color-primary-200);
  --chart-colors-chart-2: var(--color-primary-200);
  --chart-colors-chart-2-inverse: var(--color-primary-200);
  --chart-colors-chart-2-hex: var(--color-primary-200);
  --chart-colors-chart-2-hex-inverse: var(--color-primary-200);
  --chart-3: var(--color-primary-400);
  --chart-colors-chart-3: var(--color-primary-400);
  --chart-colors-chart-3-inverse: var(--color-primary-400);
  --chart-colors-chart-3-hex: var(--color-primary-400);
  --chart-colors-chart-3-hex-inverse: var(--color-primary-400);
  --chart-4: var(--color-primary-800);
  --chart-colors-chart-4: var(--color-primary-800);
  --chart-colors-chart-4-inverse: var(--color-primary-800);
  --chart-colors-chart-4-hex: var(--color-primary-800);
  --chart-colors-chart-4-hex-inverse: var(--color-primary-800);
  --chart-5: var(--color-blue-800);
  --chart-colors-chart-5: var(--color-blue-800);
  --chart-colors-chart-5-inverse: var(--color-blue-600);
  --chart-colors-chart-5-hex: var(--color-blue-800);
  --chart-colors-chart-5-hex-inverse: var(--color-blue-600);
  --chart-6: var(--color-gray-400);
  --chart-colors-chart-6: var(--color-gray-400);
  --chart-colors-chart-6-inverse: var(--color-gray-400);
  --chart-colors-chart-6-hex: var(--color-gray-400);
  --chart-colors-chart-6-hex-inverse: var(--color-gray-400);
  --chart-7: var(--color-violet-800);
  --chart-colors-chart-7: var(--color-violet-800);
  --chart-colors-chart-7-inverse: var(--color-violet-600);
  --chart-colors-chart-7-hex: var(--color-violet-800);
  --chart-colors-chart-7-hex-inverse: var(--color-violet-600);
  --chart-colors-chart-8-inverse: var(--color-gray-700);
  --chart-colors-chart-8-hex-inverse: var(--color-gray-700);
  --chart-colors-chart-9-inverse: var(--color-gray-500);
  --chart-colors-chart-9-hex-inverse: var(--color-gray-500);
  --chart-colors-chart-10-inverse: var(--color-gray-700);
  --chart-colors-chart-10-hex-inverse: var(--color-gray-700);
  --chart-colors-candlestick-upward: var(--color-primary-600);
  --chart-colors-candlestick-upward-inverse: var(--color-primary-500);
  --chart-colors-candlestick-downward: var(--color-primary-600);
  --chart-colors-candlestick-downward-inverse: var(--color-primary-500);

  --map-colors-primary: var(--color-primary-600);
  --map-colors-primary-inverse: var(--color-primary-500);
  --map-colors-default-inverse: var(--color-gray-600);
  --map-colors-highlight: var(--color-primary-300);
  --map-colors-highlight-inverse: var(--color-primary-200);
  --map-colors-border-inverse: var(--color-gray-800);
}

"@

    # Ensamblar el nuevo contenido
    $newContent = $beforeSelector + $newSelector + "`n" + $afterSelector
    
    # Guardar el archivo
    Set-Content -Path $filePath -Value $newContent -Encoding UTF8
    Write-Host "✅ Regenerado: $themeName.css"
}

Write-Host "`n✅ 25 temas regenerados con hex exactos. Listos para npm run build."
