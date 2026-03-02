        <!-- Tab: Tu Información -->
        <div id="tab-info" class="tab-content active">

            {{-- ══ Visual Assets: Logo + Hero + QR ═══════════════════════════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                {{-- Logo Card (200x200) --}}
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-body p-5">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">Logo</p>
                        <div id="logo-dropzone"
                             class="bg-base-200 rounded-box h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('logo-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'logo')">
                            @if($customization && $customization->logo_filename)
                                <img id="logo-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                                     alt="Logo" class="max-w-full max-h-full object-contain">
                            @else
                                <div id="logo-placeholder" class="text-center">
                                    {{-- Logo SVG fallback --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         viewBox="0 0 100 100" 
                                         width="64" height="64"
                                         class="mx-auto text-primary/40"
                                         style="opacity: 0.6;">
                                      <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 
                                               L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" 
                                            fill="currentColor"/>
                                      <circle cx="38" cy="63" r="14" fill="currentColor"/>
                                    </svg>
                                    <p class="text-xs text-base-content/40 mt-2">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="logo-file" accept="image/*" class="hidden" onchange="uploadLogo(event)">
                        <button onclick="document.getElementById('logo-file').click()"
                                class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="iconify tabler--upload size-4"></span>
                            Cambiar Logo
                        </button>
                    </div>
                </div>

                {{-- Hero Card (400x300) --}}
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-body p-5">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">Hero</p>
                        <div id="hero-dropzone"
                             class="bg-base-200 rounded-box h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('hero-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'hero')">
                            @if($customization && $customization->hero_main_filename)
                                <img id="hero-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename) }}"
                                     alt="Hero" class="w-full h-full object-cover">
                            @else
                                <div id="hero-placeholder" class="text-center text-base-content/30">
                                    <span class="iconify tabler--cloud-upload size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="hero-file" accept="image/*" class="hidden" onchange="uploadHero(event)">
                        <button onclick="document.getElementById('hero-file').click()"
                                class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="iconify tabler--upload size-4"></span>
                            Cambiar Hero
                        </button>
                    </div>
                </div>

                {{-- QR Card (200x200) --}}
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-body p-5">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">QR Tracking</p>
                        <div class="flex justify-center mb-2">
                            <div class="bg-white p-2 rounded-lg border border-base-content/10" style="width:140px;height:140px;overflow:hidden;">
                                <div id="qr-display" style="width:136px;height:136px;overflow:hidden;">
                                    {!! $trackingQR !!}
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-base-content/40 text-center mb-2 break-all leading-tight select-all">{{ $trackingShortlink }}</p>
                        <div class="flex gap-2">
                            <a href="/tenant/{{ $tenant->id }}/qr/download" class="btn btn-primary btn-sm gap-1 flex-1" download>
                                <span class="iconify tabler--download size-3.5"></span>
                                PNG
                            </a>
                            <button type="button" onclick="downloadQRSVG()" class="btn btn-soft btn-sm gap-1 flex-1">
                                <span class="iconify tabler--file-type-svg size-3.5"></span>
                                SVG
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ Info Form ══════════════════════════════════════════════════ --}}
            <form id="form-info" onsubmit="saveInfo(event)">
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-header px-6 pt-6 pb-4">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--building-store size-5 text-primary"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-base-content">Información del Negocio</h2>
                                <p class="text-xs text-base-content/50">Datos que se muestran en tu landing pública</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="form-control">
                                <label class="label pb-1" for="info-name">
                                    <span class="label-text font-medium text-sm">Nombre del Negocio *</span>
                                </label>
                                <input id="info-name" type="text" class="input input-bordered w-full"
                                       name="business_name" value="{{ $tenant->business_name }}" required autocomplete="organization">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Eslogan / Tagline</span>
                                </label>
                                <input type="text" name="tagline" class="input input-bordered w-full"
                                       value="{{ $tenant->tagline }}" placeholder="Tu frase corta" autocomplete="off">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Subdominio</span>
                                </label>
                                <input type="text" class="input input-bordered w-full bg-base-200 text-base-content/60"
                                       value="{{ $tenant->subdomain }}" disabled>
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Teléfono</span>
                                </label>
                                <input type="tel" name="phone" class="input input-bordered w-full"
                                       value="{{ $tenant->phone }}" placeholder="+58 XXX XXXXXXX" autocomplete="tel">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">WhatsApp</span>
                                </label>
                                <input type="tel" name="whatsapp" class="input input-bordered w-full"
                                       value="{{ $tenant->whatsapp }}" placeholder="+58 XXX XXXXXXX" autocomplete="tel">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Email</span>
                                </label>
                                <input type="email" name="email" class="input input-bordered w-full"
                                       value="{{ $tenant->email }}" autocomplete="email">
                            </div>
                            <div class="form-control sm:col-span-2">
                                <label class="label pb-1" for="info-address">
                                    <span class="label-text font-medium text-sm">Dirección</span>
                                </label>
                                <input id="info-address" type="text" class="input input-bordered w-full"
                                       name="address" value="{{ $tenant->address }}" autocomplete="street-address">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1" for="info-city">
                                    <span class="label-text font-medium text-sm">Ciudad</span>
                                </label>
                                <input id="info-city" type="text" class="input input-bordered w-full"
                                       name="city" value="{{ $tenant->city }}" autocomplete="address-level2">
                            </div>
                            @if($tenant->isAtLeastCrecimiento())
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Título Contacto
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_title" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                       placeholder="Contáctanos">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Subtítulo Contacto
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_subtitle" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                       placeholder="Estamos aquí para atenderte">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Teléfono Secundario
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="tel" name="phone_secondary" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'contact_info.phone_secondary', '') }}"
                                       placeholder="+58 XXX XXXXXXX">
                            </div>
                            <div class="form-control sm:col-span-2 lg:col-span-3">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        URL Google Maps
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="url" name="contact_maps_url" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                            </div>
                            @endif
                        </div>

                        <div class="form-control mt-3">
                            <label class="label pb-1" for="info-description">
                                <span class="label-text font-medium text-sm">Descripción del Negocio</span>
                            </label>
                            <textarea id="info-description" class="textarea textarea-bordered w-full min-h-20"
                                      name="description">{{ $tenant->description }}</textarea>
                        </div>

                        {{-- ══ Contenido Hero ════════════════════════════════════════ --}}
                        <div class="divider text-xs text-base-content/40 mt-6 mb-4">Contenido del Hero</div>

                        <div class="form-control mt-3">
                            <label class="label pb-1" for="info-hero-title">
                                <span class="label-text font-medium text-sm">Título Hero <span class="text-base-content/40 font-normal">(opcional)</span></span>
                            </label>
                            <input id="info-hero-title" type="text"
                                   class="input input-bordered w-full"
                                   name="content_blocks[hero][title]"
                                   placeholder="Ej: La mejor pizzería de Maracaibo"
                                   value="{{ $customization->getContentBlock('hero', 'title') }}">
                            <span class="label-text-alt text-base-content/50 mt-1 text-xs">Si lo dejas vacío, usamos tu eslogan.</span>
                        </div>

                        <div class="form-control mt-3">
                            <label class="label pb-1" for="info-hero-subtitle">
                                <span class="label-text font-medium text-sm">Subtítulo Hero</span>
                            </label>
                            <input id="info-hero-subtitle" type="text"
                                   class="input input-bordered w-full"
                                   name="content_blocks[hero][subtitle]"
                                   placeholder="Ej: Masa artesanal, ingredientes frescos, entrega en 30 min"
                                   value="{{ $customization->getContentBlock('hero', 'subtitle') }}">
                            <span class="label-text-alt text-base-content/50 mt-1 text-xs">Complementa el título. No repitas el eslogan.</span>
                        </div>

                        <div class="form-control mt-3">
                            <label class="label pb-1" for="info-about-text">
                                <span class="label-text font-medium text-sm">Texto Acerca De</span>
                            </label>
                            <textarea id="info-about-text" class="textarea textarea-bordered w-full" rows="4"
                                      name="about_text"
                                      placeholder="Cuéntale a tus clientes quiénes son, qué los hace especiales...">{{ $customization->about_text }}</textarea>
                            <span class="label-text-alt text-base-content/50 mt-1 text-xs">Aparece en la sección "Acerca de" de tu página.</span>
                        </div>

                        {{-- ══ Indicador de Horario (Opcional) ═══════════════════════ --}}
                        <div class="divider text-xs text-base-content/40 mt-6 mb-4">Indicador de Horario en Navbar</div>

                        <div class="alert alert-info mb-4">
                            <span class="iconify tabler--info-circle size-5 shrink-0"></span>
                            <div class="text-sm">
                                <p class="font-semibold">Indicador de Estado Opcional</p>
                                <p class="text-xs opacity-80">Muestra un badge "ABIERTO" o "CERRADO" en la navbar según tu horario de atención.</p>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="hidden" name="show_hours_indicator" value="0">
                                <input type="checkbox" name="show_hours_indicator" id="show-hours-toggle"
                                       class="switch switch-success"
                                       value="1"
                                       {{ data_get($tenant->settings, 'engine_settings.features.show_hours_indicator', false) ? 'checked' : '' }}
                                       onchange="toggleHoursIndicatorFields()">
                                <div class="flex-1">
                                    <span class="label-text font-medium">¿Mostrar estado de horario en navbar?</span>
                                    <p class="text-xs text-base-content/60 mt-0.5">Activa para mostrar badge ABIERTO/CERRADO junto al botón WhatsApp</p>
                                </div>
                            </label>
                        </div>

                        <div id="hours-indicator-fields" class="{{ data_get($tenant->settings, 'engine_settings.features.show_hours_indicator', false) ? '' : 'hidden' }} mt-4 p-4 bg-base-200/50 rounded-box space-y-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Mensaje cuando estamos cerrados</span>
                                    <span class="label-text-alt text-base-content/50" id="char-count">0 / 150</span>
                                </label>
                                <textarea id="closed-message-input" name="closed_message" class="textarea textarea-bordered w-full min-h-16"
                                          placeholder="Estamos cerrados. Te responderemos durante nuestro horario de atención."
                                          maxlength="150"
                                          oninput="updateCharCount(); updatePreview()">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/60">Este mensaje se usará en el botón de WhatsApp cuando tu negocio esté cerrado</span>
                                </label>
                            </div>

                            <div class="alert alert-info">
                                <span class="iconify tabler--eye size-5 shrink-0"></span>
                                <div class="text-xs">
                                    <p class="font-semibold mb-2">Así se verá en tu navbar</p>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="badge badge-sm gap-1 bg-success text-white border-success">
                                            <span class="iconify tabler--circle-filled size-3"></span>
                                            ABIERTO
                                        </span>
                                        <span class="text-base-content/30">o</span>
                                        <span class="badge badge-sm gap-1 bg-error text-white border-error">
                                            <span class="iconify tabler--circle-filled size-3"></span>
                                            CERRADO
                                        </span>
                                    </div>
                                    <p class="mt-3 p-2 bg-base-100/50 rounded text-xs text-base-content border-l-2 border-info">
                                        <span class="font-semibold">Mensaje WhatsApp:</span> <span id="preview-message">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 justify-end pt-4 border-t border-base-content/10 mt-4">
                            <button type="button" class="btn btn-ghost" onclick="resetForm('form-info')">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- ═══════════════════════════════════════════════════════════
                 Horario de Atención — Compact collapse / expand
            ═══════════════════════════════════════════════════════════ --}}
            @php
                $businessHours = $tenant->business_hours ?? [];
                $daysMap = [
                    'monday'    => 'Lunes',
                    'tuesday'   => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday'  => 'Jueves',
                    'friday'    => 'Viernes',
                    'saturday'  => 'Sábado',
                    'sunday'    => 'Domingo',
                ];
                $shortDaysMap = [
                    'monday' => 'Lun', 'tuesday' => 'Mar', 'wednesday' => 'Mié',
                    'thursday' => 'Jue', 'friday' => 'Vie', 'saturday' => 'Sáb', 'sunday' => 'Dom',
                ];
                $weekdays = ['monday','tuesday','wednesday','thursday','friday'];

                // Build compact summary string
                $summaryParts = [];
                $grouped = [];
                $prevKey = null;
                foreach ($daysMap as $dayKey => $dayLabel) {
                    $d = $businessHours[$dayKey] ?? null;
                    $isClosed = is_null($d) || !empty($d['closed']);
                    $key = $isClosed ? 'closed' : ($d['open'] ?? '08:00') . '-' . ($d['close'] ?? '18:00');
                    if ($key === $prevKey && !empty($grouped)) {
                        $grouped[count($grouped) - 1]['days'][] = $dayKey;
                    } else {
                        $grouped[] = ['days' => [$dayKey], 'key' => $key, 'closed' => $isClosed,
                                      'open' => $d['open'] ?? '08:00', 'close' => $d['close'] ?? '18:00'];
                    }
                    $prevKey = $key;
                }
                foreach ($grouped as $g) {
                    $first = $shortDaysMap[$g['days'][0]];
                    $last  = $shortDaysMap[end($g['days'])];
                    $range = count($g['days']) > 1 ? "{$first}-{$last}" : $first;
                    $summaryParts[] = $range . ': ' . ($g['closed'] ? 'Cerrado' : $g['open'] . ' - ' . $g['close']);
                }
                $hoursSummary = implode('  •  ', $summaryParts);

                // Detect simple mode — default to 'simple' unless weekdays clearly differ
                $wdHours = array_filter(array_map(fn($d) => $businessHours[$d] ?? null, $weekdays));
                $wdOpens  = array_filter(array_column($wdHours, 'open'), fn($v) => $v !== null);
                $wdCloses = array_filter(array_column($wdHours, 'close'), fn($v) => $v !== null);
                $allSame = empty($wdOpens)
                    || (count($wdOpens) === 5 && count(array_unique($wdOpens)) === 1 && count(array_unique($wdCloses)) === 1);
                $defaultMode = (empty($businessHours) || $allSame) ? 'simple' : 'custom';
                $wdOpen  = $wdHours ? (reset($wdHours)['open'] ?? '08:00') : '08:00';
                $wdClose = $wdHours ? (reset($wdHours)['close'] ?? '18:00') : '18:00';
                $satData = $businessHours['saturday'] ?? null;
                $sunData = $businessHours['sunday'] ?? null;
                $satClosed = is_null($satData) || !empty($satData['closed']);
                $sunClosed = is_null($sunData) || !empty($sunData['closed']);
            @endphp
            <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mt-6 mb-6"
                 x-data="{ hoursOpen: false }">

                {{-- ── Compact header — always visible ── --}}
                <div class="card-body px-5 py-4 cursor-pointer select-none"
                     @click="hoursOpen = !hoursOpen">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="iconify tabler--clock size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-sm font-bold text-base-content">Horario de Atención</h3>
                            </div>
                            <p id="hours-summary-line" class="text-xs text-base-content/60 truncate">
                                {{ $hoursSummary ?: 'Sin horario configurado' }}
                            </p>
                        </div>
                        <span class="iconify tabler--chevron-down size-5 text-base-content/40 shrink-0 transition-transform duration-200"
                              :class="hoursOpen && 'rotate-180'" aria-hidden="true"></span>
                    </div>
                </div>

                {{-- ── Expandable editor ── --}}
                <div x-show="hoursOpen" x-collapse x-cloak>
                    <div class="card-body pt-0 px-5 pb-5">
                        <div class="border-t border-base-content/10 pt-4">

                            {{-- Mode switcher --}}
                            <div class="flex rounded-lg bg-base-200/60 p-1 mb-4 gap-1">
                                <button type="button" id="hours-mode-simple" onclick="setHoursMode('simple')"
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'simple' ? 'bg-primary text-primary-content shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                                    Rápido
                                </button>
                                <button type="button" id="hours-mode-custom" onclick="setHoursMode('custom')"
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'custom' ? 'bg-primary text-primary-content shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                                    Por día
                                </button>
                            </div>

                            {{-- ── SIMPLE MODE ── --}}
                            <div id="hours-simple-mode" class="{{ $defaultMode === 'simple' ? '' : 'hidden' }}">
                                <div class="space-y-3">
                                    {{-- Weekdays --}}
                                    <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                        <div class="flex items-center gap-2 mb-2.5">
                                            <span class="text-sm font-semibold text-base-content">Lunes a Viernes</span>
                                            <span class="badge badge-soft badge-primary badge-xs">5 días</span>
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <input type="time" id="bh-simple-wd-open" class="input input-sm input-bordered w-28" value="{{ $wdOpen }}">
                                            <span class="text-xs text-base-content/40 font-medium">a</span>
                                            <input type="time" id="bh-simple-wd-close" class="input input-sm input-bordered w-28" value="{{ $wdClose }}">
                                        </div>
                                    </div>

                                    {{-- Saturday --}}
                                    <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-base-content">Sábado</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-base-content/50">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sat-closed" class="toggle toggle-error toggle-sm"
                                                       {{ $satClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sat-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sat-times" class="flex items-center gap-2 flex-wrap {{ $satClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sat-open" class="input input-sm input-bordered w-28" value="{{ $satData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-base-content/40 font-medium">a</span>
                                            <input type="time" id="bh-simple-sat-close" class="input input-sm input-bordered w-28" value="{{ $satData['close'] ?? '17:00' }}">
                                        </div>
                                    </div>

                                    {{-- Sunday --}}
                                    <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-base-content">Domingo</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-base-content/50">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sun-closed" class="toggle toggle-error toggle-sm"
                                                       {{ $sunClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sun-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sun-times" class="flex items-center gap-2 flex-wrap {{ $sunClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sun-open" class="input input-sm input-bordered w-28" value="{{ $sunData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-base-content/40 font-medium">a</span>
                                            <input type="time" id="bh-simple-sun-close" class="input input-sm input-bordered w-28" value="{{ $sunData['close'] ?? '14:00' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── CUSTOM MODE (per day) ── --}}
                            <div id="hours-custom-mode" class="{{ $defaultMode === 'custom' ? '' : 'hidden' }}">
                                <div class="space-y-2">
                                    @foreach($daysMap as $dayKey => $dayLabel)
                                    @php
                                        $dayData = $businessHours[$dayKey] ?? null;
                                        $isClosed = is_null($dayData) || !empty($dayData['closed']);
                                        $openTime = $dayData['open'] ?? '08:00';
                                        $closeTime = $dayData['close'] ?? '18:00';
                                    @endphp
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                        <span class="text-sm font-semibold text-base-content w-24 shrink-0">{{ $dayLabel }}</span>
                                        <div class="flex items-center gap-2 flex-1 flex-wrap">
                                            <input type="time" id="bh-{{ $dayKey }}-open"
                                                   class="input input-sm input-bordered w-28"
                                                   value="{{ $openTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                            <span class="text-xs text-base-content/40">a</span>
                                            <input type="time" id="bh-{{ $dayKey }}-close"
                                                   class="input input-sm input-bordered w-28"
                                                   value="{{ $closeTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                        </div>
                                        <label class="label cursor-pointer gap-2 shrink-0">
                                            <span class="label-text text-xs text-base-content/50">Cerrado</span>
                                            <input type="checkbox" id="bh-{{ $dayKey }}-closed"
                                                   class="toggle toggle-error toggle-sm"
                                                   {{ $isClosed ? 'checked' : '' }}
                                                   onchange="toggleDayClosed('{{ $dayKey }}', this.checked)">
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" onclick="saveBusinessHours()" class="btn btn-primary w-full gap-2 mt-4">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Horario
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 Redes Sociales
            ═══════════════════════════════════════════════════════════ --}}
            <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
                @php
                    $rawSocial      = $customization->social_networks ?? [];
                    $socialNetworks = is_array($rawSocial) ? $rawSocial : [];
                    $allNetworksMeta = [
                        'instagram' => ['label' => 'Instagram',  'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-instagram'],
                        'facebook'  => ['label' => 'Facebook',   'placeholder' => '@pagina o URL', 'icon' => 'tabler--brand-facebook'],
                        'tiktok'    => ['label' => 'TikTok',     'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-tiktok'],
                        'linkedin'  => ['label' => 'LinkedIn',   'placeholder' => 'URL o usuario', 'icon' => 'tabler--brand-linkedin'],
                        'youtube'   => ['label' => 'YouTube',    'placeholder' => '@canal o URL',  'icon' => 'tabler--brand-youtube'],
                        'x'         => ['label' => 'Twitter / X','placeholder' => '@tuusuario',    'icon' => 'tabler--brand-x'],
                    ];
                    $plan1Networks  = ['instagram', 'facebook', 'tiktok', 'linkedin'];
                    $availableKeys  = $plan->id === 1 ? $plan1Networks : array_keys($allNetworksMeta);
                    $plan1Selected  = array_key_first(array_intersect_key($socialNetworks, array_flip($plan1Networks))) ?? '';
                    $plan1Handle    = $plan1Selected ? ($socialNetworks[$plan1Selected] ?? '') : '';
                @endphp

                <div class="card-header px-6 pt-6 pb-4 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="iconify tabler--social size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <h3 class="text-lg font-bold text-base-content">Redes Sociales</h3>
                    </div>
                    @if($plan->id === 1)
                        <span class="badge badge-soft badge-warning badge-sm">Plan OPORTUNIDAD — 1 red social</span>
                    @else
                        <span class="badge badge-soft badge-success badge-sm">Plan {{ $plan->name }} — Todas las redes</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($plan->id === 1)
                    {{-- ── Plan 1: radio select + single handle ── --}}
                    <div class="mb-4">
                        <label class="label"><span class="label-text font-medium">Elige tu red social</span></label>
                        <div class="flex flex-wrap gap-2 mb-4" id="social-radio-group">
                            @foreach($plan1Networks as $key)
                            @php $meta = $allNetworksMeta[$key]; @endphp
                            <label id="social-radio-label-{{ $key }}"
                                   onclick="selectSocialNetwork('{{ $key }}')"
                                   class="btn btn-sm gap-1.5 {{ $plan1Selected === $key ? 'btn-primary' : 'btn-ghost border border-base-content/20' }} cursor-pointer">
                                <input type="radio" name="social_plan1_choice" value="{{ $key }}"
                                       {{ $plan1Selected === $key ? 'checked' : '' }} class="hidden">
                                <span class="iconify {{ $meta['icon'] }} size-4" aria-hidden="true"></span>
                                {{ $meta['label'] }}
                            </label>
                            @endforeach
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">
                                    Tu usuario o enlace
                                    <span id="social-plan1-network-label" class="text-primary ml-1">
                                        {{ $plan1Selected ? '(' . $allNetworksMeta[$plan1Selected]['label'] . ')' : '' }}
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="social-plan1-handle"
                                   value="{{ $plan1Handle }}"
                                   placeholder="{{ $plan1Selected ? $allNetworksMeta[$plan1Selected]['placeholder'] : 'Selecciona una red primero' }}"
                                   class="input input-bordered w-full"
                                   {{ !$plan1Selected ? 'disabled' : '' }}>
                        </div>
                    </div>

                    @else
                    {{-- ── Plan 2 + 3: grid cubo Rubik ── --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4" id="social-all-fields">
                        @foreach($availableKeys as $key)
                        @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                        <div class="flex flex-col items-center gap-2 p-3 rounded-lg border border-base-content/10 bg-base-200/40 transition-all hover:border-primary/30 hover:bg-primary/5">
                            <span class="iconify {{ $meta['icon'] }} size-7 text-primary" aria-hidden="true"></span>
                            <span class="text-[11px] font-semibold text-base-content/70">{{ $meta['label'] }}</span>
                            <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                                   value="{{ $current }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   maxlength="255"
                                   class="input input-bordered input-sm w-full text-center text-xs">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <button type="button" onclick="saveSocialNetworks()" class="btn btn-primary w-full gap-2">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Redes Sociales
                    </button>
                </div>
            </div>

        </div>

