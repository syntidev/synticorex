        <!-- Tab: Info -->
        <div id="tab-info" class="tab-content active">

            {{-- ══ Visual Assets: Logo + Hero + QR ═══════════════════════════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                {{-- Logo Card (200x200) --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
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
                                <div id="logo-placeholder" class="text-center text-base-content/30">
                                    <span class="iconify tabler--cloud-upload size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Arrastra o haz clic</p>
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
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
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
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
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
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <h2 class="card-title flex items-center gap-2">
                            <span class="iconify tabler--building-store size-5 text-primary"></span>
                            Información del Negocio
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">Datos que se muestran en tu landing pública</p>
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
                            @if($tenant->plan_id >= 2)
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

            {{-- ══ Horario de Atención (solo lectura) ══════════════════════════ --}}
            @php
                $bhReadonly = $tenant->business_hours ?? [];
                $daysReadonly = [
                    'monday' => 'Lun', 'tuesday' => 'Mar', 'wednesday' => 'Mié',
                    'thursday' => 'Jue', 'friday' => 'Vie', 'saturday' => 'Sáb', 'sunday' => 'Dom',
                ];
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-2">
                    <h3 class="card-title flex items-center gap-2 text-sm">
                        <span class="iconify tabler--clock size-4 text-primary" aria-hidden="true"></span>
                        Horario de Atención
                    </h3>
                    <span class="text-[10px] text-base-content/40">Editar en Configuración</span>
                </div>
                <div class="card-body pt-0 pb-3">
                    <div class="flex flex-wrap gap-2">
                        @foreach($daysReadonly as $dk => $dl)
                        @php $dData = $bhReadonly[$dk] ?? null; @endphp
                        <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs
                            {{ $dData ? 'bg-success/10 text-success border border-success/20' : 'bg-base-200 text-base-content/40 border border-base-content/10' }}">
                            <span class="font-semibold">{{ $dl }}</span>
                            @if($dData)
                                <span>{{ $dData['open'] ?? '?' }}–{{ $dData['close'] ?? '?' }}</span>
                            @else
                                <span>Cerrado</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

