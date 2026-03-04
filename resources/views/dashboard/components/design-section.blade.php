        <!-- Tab: CÃ³mo Se Ve -->
        <div id="tab-diseno" class="tab-content">
@php
// activeTheme ya viene desde el controller

$prelineThemes = [
    // Originales
    ['slug'=>'default',   'name'=>'Default',   'category'=>'Clean',     'colors'=>['#2563eb','#1e40af','#3b82f6','#ffffff']],
    ['slug'=>'harvest',   'name'=>'Harvest',   'category'=>'Clean',     'colors'=>['#92400e','#b45309','#d97706','#fefce8']],
    ['slug'=>'ocean',     'name'=>'Ocean',     'category'=>'Fresh',     'colors'=>['#0d9488','#0f766e','#14b8a6','#f0fdfa']],
    ['slug'=>'cashmere',  'name'=>'Cashmere',  'category'=>'Fresh',     'colors'=>['#9f7272','#b08080','#c49090','#fdf6f0']],
    ['slug'=>'olive',     'name'=>'Olive',     'category'=>'Fresh',     'colors'=>['#6b7c4a','#7d8f56','#8fa05e','#f7f7f0']],
    ['slug'=>'retro',     'name'=>'Retro',     'category'=>'Bold',      'colors'=>['#c026d3','#a21caf','#d946ef','#fafafa']],
    ['slug'=>'bubblegum', 'name'=>'Bubblegum', 'category'=>'Bold',      'colors'=>['#ec4899','#db2777','#f472b6','#fdf2f8']],
    ['slug'=>'autumn',    'name'=>'Autumn',    'category'=>'Bold',      'colors'=>['#d97706','#b45309','#f59e0b','#fffbeb']],
    ['slug'=>'moon',      'name'=>'Moon',      'category'=>'Dark',      'colors'=>['#3b82f6','#2563eb','#60a5fa','#0f172a']],
    // Comida
    ['slug'=>'sabor-tradicional', 'name'=>'Sabor Trad.',  'category'=>'Comida',    'colors'=>['#C62828','#8E0000','#E57373','#F5E6CC']],
    ['slug'=>'fuego-urbano',      'name'=>'Fuego Urbano', 'category'=>'Comida',    'colors'=>['#F57C00','#E65100','#FFB74D','#111111']],
    ['slug'=>'parrilla-moderna',  'name'=>'Parrilla',     'category'=>'Comida',    'colors'=>['#8E0000','#B71C1C','#EF9A9A','#424242']],
    ['slug'=>'casa-latina',       'name'=>'Casa Latina',  'category'=>'Comida',    'colors'=>['#C65D3B','#A0522D','#F4A27C','#FFF1DC']],
    // Dulces
    ['slug'=>'rosa-vainilla',      'name'=>'Rosa Vainilla', 'category'=>'Dulces',   'colors'=>['#F8BBD0','#F48FB1','#FCE4EC','#FFF3E0']],
    ['slug'=>'pistacho-suave',     'name'=>'Pistacho',      'category'=>'Dulces',   'colors'=>['#A8C686','#7CB342','#DCEDC8','#F5E6CC']],
    ['slug'=>'cielo-dulce',        'name'=>'Cielo Dulce',   'category'=>'Dulces',   'colors'=>['#B3E5FC','#81D4FA','#E1F5FE','#ffffff']],
    ['slug'=>'chocolate-caramelo', 'name'=>'Chocolate',     'category'=>'Dulces',   'colors'=>['#5D4037','#4E342E','#A1887F','#FFF3E0']],
    // Salud & Autoridad
    ['slug'=>'azul-confianza',   'name'=>'Azul Confianza',  'category'=>'Salud',    'colors'=>['#1976D2','#1565C0','#64B5F6','#ffffff']],
    ['slug'=>'verde-calma',      'name'=>'Verde Calma',     'category'=>'Salud',    'colors'=>['#66BB6A','#388E3C','#A5D6A7','#ffffff']],
    ['slug'=>'azul-profesional', 'name'=>'Azul Prof.',      'category'=>'Autoridad','colors'=>['#0D47A1','#01579B','#5C85D6','#ffffff']],
    ['slug'=>'ejecutivo-oscuro', 'name'=>'Ejecutivo',       'category'=>'Autoridad','colors'=>['#1C1F26','#263238','#546E7A','#CFD8DC']],
    ['slug'=>'prestigio-clasico','name'=>'Prestigio',       'category'=>'Autoridad','colors'=>['#1A237E','#283593','#9FA8DA','#ffffff']],
    // Oficios & Belleza
    ['slug'=>'industrial-pro',  'name'=>'Industrial',    'category'=>'Oficios',  'colors'=>['#1565C0','#263238','#64B5F6','#FF6F00']],
    ['slug'=>'negro-impacto',   'name'=>'Negro Impacto', 'category'=>'Oficios',  'colors'=>['#111111','#212121','#616161','#FFD600']],
    ['slug'=>'metal-urbano',    'name'=>'Metal Urbano',  'category'=>'Oficios',  'colors'=>['#757575','#424242','#BDBDBD','#212121']],
    ['slug'=>'nude-elegante',   'name'=>'Nude',          'category'=>'Belleza',  'colors'=>['#D7CCC8','#BCAAA4','#EFEBE9','#ffffff']],
    ['slug'=>'rosa-studio',     'name'=>'Rosa Studio',   'category'=>'Belleza',  'colors'=>['#F48FB1','#E91E8C','#FCE4EC','#ECEFF1']],
    ['slug'=>'barber-clasico',  'name'=>'Barber',        'category'=>'Belleza',  'colors'=>['#1B4332','#2D6A4F','#95D5B2','#F1E9DA']],
    // Fitness & Educacion
    ['slug'=>'fuerza-roja',    'name'=>'Fuerza Roja',    'category'=>'Fitness',  'colors'=>['#D50000','#B71C1C','#FF5252','#111111']],
    ['slug'=>'verde-potencia', 'name'=>'Verde Potencia', 'category'=>'Fitness',  'colors'=>['#00E676','#00C853','#69F0AE','#111111']],
    ['slug'=>'azul-electrico', 'name'=>'Azul Eléctrico', 'category'=>'Fitness', 'colors'=>['#2962FF','#1565C0','#82B1FF','#1C1F26']],
    ['slug'=>'azul-academico', 'name'=>'Académico',      'category'=>'Educacion','colors'=>['#1565C0','#0D47A1','#64B5F6','#ffffff']],
    ['slug'=>'verde-progreso', 'name'=>'Verde Progreso', 'category'=>'Educacion','colors'=>['#2E7D32','#1B5E20','#81C784','#F1F8E9']],
    ['slug'=>'claro-simple',   'name'=>'Claro Simple',   'category'=>'Educacion','colors'=>['#90CAF9','#64B5F6','#BBDEFB','#ffffff']],
];
$allowedSlugs = \App\Services\PrelineThemeService::getThemesByPlan($tenant->plan_id);
$prelineThemes = array_values(array_filter($prelineThemes, fn($t) => in_array($t['slug'], $allowedSlugs)));
$themesByCategory = collect($prelineThemes)->groupBy('category');
@endphp

{{-- â”€â”€ Temas + Paleta â€” 2 columnas â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="p-6">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">

    {{-- ● LEFT: Tema Visual (8/12) ● --}}
    <div class="lg:col-span-8">
        <div class="bg-surface rounded-xl shadow-sm border border-border h-full">
            <div class="px-5 pt-5 pb-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="iconify tabler--palette size-5 text-primary" aria-hidden="true"></span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-foreground">Tema Visual</h2>
                        <p class="text-xs text-muted-foreground-1">Elige el tema que mejor represente tu marca</p>
                    </div>
                </div>
            </div>
            <div class="pt-1 px-5 pb-5">
                <div id="theme-success-message" class="flex p-4 rounded-lg border gap-3 bg-green-50 border-green-200 text-green-800 mb-3" style="display:none;">
                    <span class="iconify tabler--check size-4"></span>
                    <span class="text-sm">Tema actualizado correctamente</span>
                </div>

                <div class="max-h-[420px] overflow-y-auto pr-1 space-y-3">
                    @foreach($themesByCategory as $category => $themes)
                    <div>
                        <h3 class="text-[10px] font-bold text-muted-foreground-1 mb-2 uppercase tracking-wider sticky top-0 bg-surface py-1 z-10">
                            {{ $category }}
                        </h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                            @foreach($themes as $theme)
                            @php
                                $isActive = $themeSlug === $theme['slug'];
                                $bg      = $theme['colors'][3];
                                $isDark  = in_array($theme['slug'], ['moon']);
                            @endphp
                            <label class="theme-card cursor-pointer rounded-lg overflow-hidden border-2 transition-all hover:scale-105 {{ $isActive ? 'border-primary ring-2 ring-primary/25' : 'border-border hover:border-border' }}"
                                 data-slug="{{ $theme['slug'] }}"
                                 onclick="updateTheme('{{ $theme['slug'] }}')"
                                 style="background:{{ $bg }}">
                                {{-- Color bars --}}
                                <div class="flex h-6">
                                    @foreach(array_slice($theme['colors'], 0, 4) as $color)
                                    <div class="flex-1" style="background:{{ $color }}"></div>
                                    @endforeach
                                </div>
                                <div class="px-2 py-1 flex items-center justify-between gap-1">
                                    <span class="text-[10px] font-semibold truncate" style="color:{{ $isDark ? 'rgba(255,255,255,.9)' : 'rgba(0,0,0,.85)' }}">{{ $theme['name'] }}</span>
                                    @if($isActive)<span class="iconify tabler--check size-3 text-primary shrink-0"></span>@endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- â•â•â• RIGHT: Paleta Personalizada (4/12) â•â•â• --}}
    <div class="lg:col-span-4">
        @if($tenant->isVision())
        <div class="bg-surface rounded-xl shadow-sm border border-border h-full">
            <div class="px-5 pt-5 pb-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="iconify tabler--color-swatch size-5 text-primary" aria-hidden="true"></span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-foreground">Paleta Custom</h3>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Plan VISIÓN</span>
                    </div>
                </div>
            </div>
            <div class="px-5 pb-5">
                @php
                $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? ['primary' => '#3B82F6'];
                @endphp

                <p class="text-xs text-muted-foreground-1 mb-4">Define el color primario de tu marca.</p>

                <div class="flex items-center gap-3">
                    <input type="color" id="custom-primary"
                           class="size-12 rounded-lg border border-border cursor-pointer shrink-0 p-1"
                           value="{{ $customPalette['primary'] }}">
                    <div class="flex-1 min-w-0">
                        <span class="text-xs font-semibold text-foreground">Color Primario</span>
                        <span class="text-[10px] text-muted-foreground-1 ml-1 font-mono">{{ $customPalette['primary'] }}</span>
                    </div>
                </div>

                <button onclick="applyCustomPalette()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 w-full gap-2 mt-4">
                    <span class="iconify tabler--palette size-4"></span>
                    Aplicar Paleta Custom
                </button>
            </div>
        </div>
        @else
        {{-- Non Plan 3: upsell --}}
        <div class="bg-surface rounded-xl shadow-sm border border-border h-full">
            <div class="flex flex-col items-center justify-center text-center py-10 px-5">
                <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                    <span class="iconify tabler--color-swatch size-6 text-primary" aria-hidden="true"></span>
                </div>
                <h3 class="text-sm font-bold text-foreground mb-1">Paleta Personalizada</h3>
                <p class="text-xs text-muted-foreground-1 mb-4">Crea tu propia combinaciÃ³n de colores con el Plan VISIÃ“N</p>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 mb-3">
                    <span class="iconify tabler--lock size-3 mr-1"></span>
                    Plan VISIÃ“N
                </span>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center text-xs py-1 px-2 rounded-lg font-medium transition-colors bg-blue-100 text-blue-700 hover:bg-blue-200 gap-1">
                    <span class="iconify tabler--external-link size-3"></span>
                    Ver Planes
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
</div>{{-- /p-6 --}}

        </div>
