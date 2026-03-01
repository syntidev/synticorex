        <!-- Tab: CÃ³mo Se Ve -->
        <div id="tab-diseno" class="tab-content">
@php
// activeTheme ya viene desde el controller

// Colores hardcodeados de cada tema FlyonUI (primary, secondary, accent, neutral, base)
$flyonuiThemes = [
    // DEFAULT
    ['slug'=>'light', 'name'=>'Light', 'category'=>'Default', 'colors'=>['#570df8','#f000b8','#37cdbe','#ffffff']],
    ['slug'=>'dark', 'name'=>'Dark', 'category'=>'Default', 'colors'=>['#661ae6','#d926a9','#1fb2a6','#2a303c']],
    // DARK MODES
    ['slug'=>'black', 'name'=>'Black', 'category'=>'Dark Modes', 'font'=>'Geist', 'colors'=>['#ffffff','#ffffff','#ffffff','#000000']],
    ['slug'=>'spotify', 'name'=>'Spotify', 'category'=>'Dark Modes', 'font'=>'Montserrat', 'colors'=>['#1db954','#1ed760','#1db954','#121212']],
    ['slug'=>'valorant', 'name'=>'Valorant', 'category'=>'Dark Modes', 'font'=>'Syne', 'colors'=>['#ff4655','#bd3944','#ff4655','#0f1923']],
    // PROFESSIONAL
    ['slug'=>'claude', 'name'=>'Claude', 'category'=>'Professional', 'font'=>'Lato', 'colors'=>['#da7756','#a0785a','#e8c9a0','#f5f0e8']],
    ['slug'=>'corporate', 'name'=>'Corporate', 'category'=>'Professional', 'font'=>'Inter', 'colors'=>['#4b6bfb','#7b92b2','#67cba0','#ffffff']],
    ['slug'=>'gourmet', 'name'=>'Gourmet', 'category'=>'Professional', 'font'=>'Montserrat', 'colors'=>['#9b2335','#d4a76a','#c8a97e','#fdfaf5']],
    ['slug'=>'luxury', 'name'=>'Luxury', 'category'=>'Professional', 'font'=>'Rubik', 'colors'=>['#ffffff','#a08740','#c5a028','#09090b']],
    // CREATIVE
    ['slug'=>'ghibli', 'name'=>'Ghibli', 'category'=>'Creative', 'font'=>'Amaranth', 'colors'=>['#6b7c5c','#c49a6c','#e8a87c','#faf6f0']],
    ['slug'=>'pastel', 'name'=>'Pastel', 'category'=>'Creative', 'font'=>'Open Sans', 'colors'=>['#d1c1f7','#f7d6c1','#c1f7d6','#ffffff']],
    ['slug'=>'soft', 'name'=>'Soft', 'category'=>'Creative', 'font'=>'Rubik', 'colors'=>['#6b21a8','#db2777','#0891b2','#ffffff']],
    // TECH
    ['slug'=>'mintlify', 'name'=>'Mintlify', 'category'=>'Tech', 'font'=>'Work Sans', 'colors'=>['#0ea474','#7c3aed','#0ea5e9','#ffffff']],
    ['slug'=>'perplexity', 'name'=>'Perplexity', 'category'=>'Tech', 'font'=>'Inter', 'colors'=>['#20b8cd','#1a9ab0','#15808f','#16191d']],
    ['slug'=>'shadcn', 'name'=>'Shadcn', 'category'=>'Tech', 'font'=>'Geist', 'colors'=>['#18181b','#f4f4f5','#18181b','#ffffff']],
    ['slug'=>'slack', 'name'=>'Slack', 'category'=>'Tech', 'font'=>'Lato', 'colors'=>['#4a154b','#1264a3','#ecb22e','#3f0e40']],
    ['slug'=>'vscode', 'name'=>'VS Code', 'category'=>'Tech', 'font'=>'DM Mono', 'colors'=>['#007acc','#6a9955','#569cd6','#1e1e1e']],
];

// Filter to only the themes available for this tenant's plan (from DB)
$allowedSlugs = $palettes->pluck('slug')->toArray();
$flyonuiThemes = array_values(array_filter($flyonuiThemes, fn($t) => in_array($t['slug'], $allowedSlugs)));
$themesByCategory = collect($flyonuiThemes)->groupBy('category');
@endphp

{{-- â”€â”€ Temas + Paleta â€” 2 columnas â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">

    {{-- â•â•â• LEFT: Tema Visual (8/12) â•â•â• --}}
    <div class="lg:col-span-8">
        <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated h-full">
            <div class="card-header px-5 pt-5 pb-3">
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
            <div class="card-body pt-1 px-5 pb-5">
                <div id="theme-success-message" class="alert alert-success mb-3" style="display:none;">
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
                                $isDark  = in_array($theme['slug'], ['dark','black','spotify','valorant','luxury','perplexity','slack','vscode']);
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
        <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated h-full">
            <div class="card-header px-5 pt-5 pb-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="iconify tabler--color-swatch size-5 text-primary" aria-hidden="true"></span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-base-content">Paleta Custom</h3>
                        <span class="badge badge-soft badge-info badge-xs">Plan VISIÃ“N</span>
                    </div>
                </div>
            </div>
            <div class="card-body px-5 pb-5">
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

                <button onclick="applyCustomPalette()" class="btn btn-primary btn-sm w-full gap-2 mt-4">
                    <span class="iconify tabler--palette size-4"></span>
                    Aplicar Paleta Custom
                </button>
            </div>
        </div>
        @else
        {{-- Non Plan 3: upsell --}}
        <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated h-full">
            <div class="card-body flex flex-col items-center justify-center text-center py-10 px-5">
                <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                    <span class="iconify tabler--color-swatch size-6 text-primary" aria-hidden="true"></span>
                </div>
                <h3 class="text-sm font-bold text-base-content mb-1">Paleta Personalizada</h3>
                <p class="text-xs text-base-content/50 mb-4">Crea tu propia combinaciÃ³n de colores con el Plan VISIÃ“N</p>
                <span class="badge badge-soft badge-warning badge-sm mb-3">
                    <span class="iconify tabler--lock size-3 mr-1"></span>
                    Plan VISIÃ“N
                </span>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   class="btn btn-soft btn-primary btn-xs gap-1">
                    <span class="iconify tabler--external-link size-3"></span>
                    Ver Planes
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

        </div>
