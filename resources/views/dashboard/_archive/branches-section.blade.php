        {{-- Tab: Sucursales (Plan 3 / VISIÓN only) --}}
        @if($plan->id === 3)
        <div id="tab-sucursales" class="tab-content">
            @php
                $branchesEnabled = data_get($tenant->settings, 'engine_settings.branches.enabled', false);
                $maxBranches = 3;
                $currentBranchCount = $branches->count();
            @endphp

            {{-- ── Sucursales header card ──────────────────────────────── --}}
            <div class="card bg-base-100 shadow-md border border-base-content/8 mb-6 card-elevated">
                <div class="card-header px-6 pt-6 pb-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-base-content">Sucursales</h2>
                            <p class="text-xs text-base-content/50">Muestra hasta 3 sucursales en tu landing pública</p>
                        </div>
                    </div>
                    <input type="checkbox" id="branches-toggle"
                           class="switch switch-success"
                           {{ $branchesEnabled ? 'checked' : '' }}
                           onchange="toggleBranchesSection()">
                </div>
                <div class="card-body pt-0">
                    <div id="branches-status" class="alert {{ $branchesEnabled ? 'alert-success' : 'alert-info' }}">
                        <span class="iconify {{ $branchesEnabled ? 'tabler--check' : 'tabler--pause' }} size-4" aria-hidden="true"></span>
                        <p id="branches-status-text" class="text-sm">
                            {{ $branchesEnabled ? 'Sección visible en tu landing pública' : 'Sección oculta en tu landing pública' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Branch list + Forms (shown only when enabled) --}}
            <div id="branches-content" {{ $branchesEnabled ? '' : 'style="display:none"' }}>

                {{-- Existing branches — intelligent grid: 1=12col, 2=6+6, 3=4+4+4 --}}
                @php
                    $branchGridClass = match($currentBranchCount) {
                        0 => 'grid-cols-1',
                        1 => 'grid-cols-1',
                        2 => 'grid-cols-1 sm:grid-cols-2',
                        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
                    };
                @endphp
                <div id="branches-list" class="grid {{ $branchGridClass }} gap-3 mb-4">
                    @foreach($branches as $branch)
                    <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 transition-all hover:border-primary/30 branch-card" id="branch-card-{{ $branch->id }}">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="branch-name text-sm font-semibold text-base-content truncate">{{ $branch->name }}</h3>
                                <p class="branch-address text-xs text-base-content/50 line-clamp-2 mt-0.5">{{ $branch->address }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button"
                                    class="btn btn-primary btn-sm btn-square"
                                    onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')"
                                    title="Editar">
                                <span class="iconify tabler--pencil size-5" aria-hidden="true"></span>
                            </button>
                            <button type="button"
                                    class="btn btn-error btn-sm btn-square"
                                    onclick="deleteBranch({{ $branch->id }})"
                                    title="Eliminar">
                                <span class="iconify tabler--trash size-5" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    @if($currentBranchCount < $maxBranches)
                    <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 flex items-center justify-center transition-all hover:border-primary/30 cursor-pointer" onclick="openBranchModal()">
                        <div class="text-center">
                            <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-2">
                                <span class="iconify tabler--plus size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <p class="text-sm font-semibold text-base-content">Agregar Sucursal</p>
                            <p class="text-xs text-base-content/50">{{ $currentBranchCount }} de {{ $maxBranches }} usadas</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

