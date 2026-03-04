$ErrorActionPreference = "Stop"

# Función para oscurecer un hex 10%
function Darken-Hex {
    param([string]$hex)
    $hex = $hex -replace '^#', ''
    $r = [Convert]::ToInt32($hex.Substring(0, 2), 16)
    $g = [Convert]::ToInt32($hex.Substring(2, 2), 16)
    $b = [Convert]::ToInt32($hex.Substring(4, 2), 16)
    $r = [int]($r * 0.9)
    $g = [int]($g * 0.9)
    $b = [int]($b * 0.9)
    return "#{0:X2}{1:X2}{2:X2}" -f $r, $g, $b
}

# Datos de temas: slug => {primary, light, secondary, border}
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

$baseDir = "c:\laragon\www\synticorex\node_modules\preline\css\themes"

foreach ($tema in $temas.GetEnumerator()) {
    $slug = $tema.Name
    $colors = $tema.Value
    $filePath = Join-Path $baseDir "$slug.css"
    
    $primary700 = Darken-Hex $colors.primary
    
    # Archivo debe existir
    if (-not (Test-Path $filePath)) {
        Write-Host "❌ No existe: $slug.css"
        continue
    }
    
    $content = Get-Content $filePath -Raw
    
    # Reemplazos específicos basados en la estructura de cada tema
    # Cada tema tiene su propio slug en las referencias de color
    
    # --primary-50
    $content = $content -replace "(?<=\n\s*)--primary-50:\s*var\(--color-$slug-50\);", "  --primary-50: $($colors.light);"
    
    # --primary-500
    $content = $content -replace "(?<=\n\s*)--primary-500:\s*var\(--color-$slug-500\);", "  --primary-500: $($colors.primary);"
    
    # --primary-600
    $content = $content -replace "(?<=\n\s*)--primary-600:\s*var\(--color-$slug-600\);", "  --primary-600: $($colors.primary);"
    
    # --primary-700
    $content = $content -replace "(?<=\n\s*)--primary-700:\s*var\(--color-$slug-700\);", "  --primary-700: $primary700;"
    
    # --border (buscar tanto referencias al tema como a gray)
    $content = $content -replace "(?<=\n\s*)--border:\s*var\(--color-$slug(-gray)?-\d+\);", "  --border: $($colors.border);"
    
    # --secondary - puede ser gray-900 o similar
    $content = $content -replace "(?<=\n\s*)--secondary:\s*var\(--color-$slug(-gray)?-\d+\);", "  --secondary: $($colors.secondary);"
    
    # Asegurar que --secondary-foreground sea white
    $content = $content -replace "(?<=\n\s*)--secondary-foreground:\s*var\([^)]+\);", "  --secondary-foreground: var(--color-white);"
    
    Set-Content -Path $filePath -Value $content -Encoding UTF8
    Write-Host "✅ $slug.css"
}

Write-Host ""
Write-Host "✅ 25 temas regenerados con hex exactos."
Write-Host "Listos para: npm run build"
