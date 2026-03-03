        <!-- Tab: CÃ³mo Se Ve -->
        <div id="tab-diseno" class="tab-content">
@php
// activeTheme ya viene desde el controller

$prelineThemes = [
    ['slug'=>'default',   'name'=>'Default',   'category'=>'Clean',  'colors'=>['#2563eb','#1e40af','#3b82f6','#ffffff']],
    ['slug'=>'harvest',   'name'=>'Harvest',   'category'=>'Clean',  'colors'=>['#92400e','#b45309','#d97706','#fefce8']],
    ['slug'=>'ocean',     'name'=>'Ocean',     'category'=>'Fresh',  'colors'=>['#0d9488','#0f766e','#14b8a6','#f0fdfa']],
    ['slug'=>'cashmere',  'name'=>'Cashmere',  'category'=>'Fresh',  'colors'=>['#9f7272','#b08080','#c49090','#fdf6f0']],
    ['slug'=>'olive',     'name'=>'Olive',     'category'=>'Fresh',  'colors'=>['#6b7c4a','#7d8f56','#8fa05e','#f7f7f0']],
    ['slug'=>'retro',     'name'=>'Retro',     'category'=>'Bold',   'colors'=>['#c026d3','#a21caf','#d946ef','#fafafa']],
    ['slug'=>'bubblegum', 'name'=>'Bubblegum', 'category'=>'Bold',   'colors'=>['#ec4899','#db2777','#f472b6','#fdf2f8']],
    ['slug'=>'autumn',    'name'=>'Autumn',    'category'=>'Bold',   'colors'=>['#d97706','#b45309','#f59e0b','#fffbeb']],
    ['slug'=>'moon',      'name'=>'Moon',      'category'=>'Dark',   'colors'=>['#3b82f6','#2563eb','#60a5fa','#0f172a']],
];
$allowedSlugs = \App\Services\PrelineThemeService::getThemesByPlan($tenant->plan_id);
$prelineThemes = array_values(array_filter($prelineThemes, fn($t) => in_array($t['slug'], $allowedSlugs)));
$themesByCategory = collect($prelineThemes)->groupBy('category');
@endphp

{{-- â”€â”€ Temas + Paleta â€” 2 columnas â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">

    {{-- â•â•â• LEFT: Tema Visual (8/12) â•â•â• --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 h-full">
            <div class="px-5 pt-5 pb-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="iconify tabler--palette size-5 text-primary" aria-hidden="true"></span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-base-content">Tema Visual</h2>
                        <p class="text-xs text-base-content/50">Elige el tema que mejor represente tu marca</p>
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
                        <h3 class="text-[10px] font-bold text-base-content/40 mb-2 uppercase tracking-wider sticky top-0 bg-base-100 py-1 z-10">
                            {{ $category }}
                        </h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                            @foreach($themes as $theme)
                            @php
                                $isActive = $currentTheme === $theme['slug'];
                                $bg      = $theme['colors'][3];
                                $isDark  = in_array($theme['slug'], ['moon']);
                            @endphp
                            <label class="theme-card cursor-pointer rounded-lg overflow-hidden border-2 transition-all hover:scale-105 {{ $isActive ? 'border-primary ring-2 ring-primary/25' : 'border-base-content/10 hover:border-base-content/25' }}"
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
        <div class="bg-white rounded-xl shadow-md border border-gray-200 h-full">
            <div class="px-5 pt-5 pb-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="iconify tabler--color-swatch size-5 text-primary" aria-hidden="true"></span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-base-content">Paleta Custom</h3>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Plan VISIÓN</span>
                    </div>
                </div>
            </div>
            <div class="px-5 pb-5">
                @php
                $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? [
                    'primary' => '#570DF8', 'secondary' => '#F000B9', 'accent' => '#1DCDBC', 'base' => '#FFFFFF'
                ];
                @endphp

                <p class="text-xs text-base-content/50 mb-4">Define tus propios colores para una identidad Ãºnica.</p>

                <div class="space-y-3">
                    @foreach(['primary' => 'Primary', 'secondary' => 'Secondary', 'accent' => 'Accent', 'base' => 'Base'] as $colorKey => $colorLabel)
                    <div class="flex items-center gap-3">
                        <input type="color" id="custom-{{ $colorKey }}"
                               class="size-9 rounded-lg border border-base-content/10 cursor-pointer shrink-0 p-0.5"
                               value="{{ $customPalette[$colorKey] }}">
                        <div class="flex-1 min-w-0">
                            <span class="text-xs font-semibold text-base-content">{{ $colorLabel }}</span>
                            <span class="text-[10px] text-base-content/40 ml-1 font-mono">{{ $customPalette[$colorKey] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Live preview strip --}}
                <div class="flex rounded-lg overflow-hidden h-6 mt-4 border border-base-content/10">
                    <div class="flex-1" id="preview-primary" style="background:{{ $customPalette['primary'] }}"></div>
                    <div class="flex-1" id="preview-secondary" style="background:{{ $customPalette['secondary'] }}"></div>
                    <div class="flex-1" id="preview-accent" style="background:{{ $customPalette['accent'] }}"></div>
                    <div class="flex-1" id="preview-base" style="background:{{ $customPalette['base'] }}"></div>
                </div>

                <button onclick="applyCustomPalette()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 w-full gap-2 mt-4">
                    <span class="iconify tabler--palette size-4"></span>
                    Aplicar Paleta Custom
                </button>
            </div>
        </div>
        @else
        {{-- Non Plan 3: upsell --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 h-full">
            <div class="flex flex-col items-center justify-center text-center py-10 px-5">
                <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                    <span class="iconify tabler--color-swatch size-6 text-primary" aria-hidden="true"></span>
                </div>
                <h3 class="text-sm font-bold text-base-content mb-1">Paleta Personalizada</h3>
                <p class="text-xs text-base-content/50 mb-4">Crea tu propia combinaciÃ³n de colores con el Plan VISIÃ“N</p>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 mb-3">
                    <span class="iconify tabler--lock size-3 mr-1"></span>
                    Plan VISIÃ“N
                </span>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center text-xs py-1 px-2 rounded-md font-medium transition-colors bg-blue-100 text-blue-700 hover:bg-blue-200 gap-1">
                    <span class="iconify tabler--external-link size-3"></span>
                    Ver Planes
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

        </div>
