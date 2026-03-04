        <!-- Tab: Tu Información -->
        <div id="tab-info" class="tab-content active">

            {{-- ══ Sub-tab nav ═══════════════════════════════════════════════ --}}
            <div class="flex gap-1 mb-6 bg-layer p-1 rounded-xl border border-border">
                <button onclick="switchInfoTab('visual')" id="itab-visual-btn"
                    class="info-tab-btn active flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all bg-surface shadow-sm text-primary">
                    <span class="iconify tabler--photo size-4"></span>
                    <span class="hidden sm:inline">Visual</span>
                </button>
                <button onclick="switchInfoTab('negocio')" id="itab-negocio-btn"
                    class="info-tab-btn flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all text-muted-foreground-1 hover:text-foreground">
                    <span class="iconify tabler--building-store size-4"></span>
                    <span class="hidden sm:inline">Negocio</span>
                </button>
                <button onclick="switchInfoTab('horario')" id="itab-horario-btn"
                    class="info-tab-btn flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all text-muted-foreground-1 hover:text-foreground">
                    <span class="iconify tabler--clock size-4"></span>
                    <span class="hidden sm:inline">Horario</span>
                </button>
                <button onclick="switchInfoTab('redes')" id="itab-redes-btn"
                    class="info-tab-btn flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all text-muted-foreground-1 hover:text-foreground">
                    <span class="iconify tabler--social size-4"></span>
                    <span class="hidden sm:inline">Redes</span>
                </button>
            </div>

            {{-- ══ Sub-tab: Visual ════════════════════════════════════════════ --}}
            <div id="itab-visual" class="info-tab-content">
            <div class="px-6 pb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Logo Card (200x200) --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-border">
                            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--photo size-5 text-primary"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground">Logo</h3>
                                <p class="text-xs text-muted-foreground-1">Marca o símbolo de tu negocio</p>
                            </div>
                        </div>
                        <div id="logo-dropzone"
                             class="bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
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
                                    <p class="text-xs text-muted-foreground-1 mt-2">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="logo-file" accept="image/*" class="hidden" onchange="uploadLogo(event)">
                        <button onclick="document.getElementById('logo-file').click()"
                                class="w-full inline-flex items-center justify-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover gap-2">
                            <span class="iconify tabler--upload size-4"></span>
                            Cambiar Logo
                        </button>
                    </div>
                </div>

                {{-- Hero Card (400x300) --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-border">
                            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--layout-dashboard size-5 text-primary"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground">Hero</h3>
                                <p class="text-xs text-muted-foreground-1">Imagen de portada de tu landing</p>
                            </div>
                        </div>
                        <div id="hero-dropzone"
                             class="bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('hero-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'hero')">
                            @if($customization && $customization->hero_main_filename)
                                <img id="hero-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename) }}"
                                     alt="Hero" class="w-full h-full object-cover">
                            @else
                                <div id="hero-placeholder" class="text-center text-muted-foreground-1">
                                    <span class="iconify tabler--cloud-upload size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="hero-file" accept="image/*" class="hidden" onchange="uploadHero(event)">
                        <button onclick="document.getElementById('hero-file').click()"
                                class="w-full inline-flex items-center justify-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover gap-2">
                            <span class="iconify tabler--upload size-4"></span>
                            Cambiar Hero
                        </button>
                    </div>
                </div>

                {{-- Acerca De Card --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-border">
                            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--info-circle size-5 text-primary"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground">Imagen Acerca De</h3>
                                <p class="text-xs text-muted-foreground-1">Foto del equipo o del local</p>
                            </div>
                        </div>
                        <div id="about-dropzone"
                             class="bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('about-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'about')">
                            @if($customization && $customization->about_image_filename)
                                <img id="about-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->about_image_filename) }}"
                                     alt="Acerca de" class="w-full h-full object-cover">
                            @else
                                <div class="text-center text-muted-foreground-1">
                                    <span class="iconify tabler--users size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Tu equipo o tu local</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="about-file" accept="image/*" class="hidden" onchange="uploadAbout(event)">
                        <button onclick="document.getElementById('about-file').click()"
                                class="w-full inline-flex items-center justify-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover gap-2">
                            <span class="iconify tabler--upload size-4"></span>
                            Subir imagen
                        </button>
                    </div>
                </div>

                {{-- QR Card --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-border">
                            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--qrcode size-5 text-primary"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground">QR Tracking</h3>
                                <p class="text-xs text-muted-foreground-1">Código para medir clics</p>
                            </div>
                        </div>
                        <div class="flex justify-center mb-2">
                            <div class="bg-card p-2 rounded-lg border border-card-line" style="width:140px;height:140px;overflow:hidden;">
                                <div id="qr-display" style="width:136px;height:136px;overflow:hidden;">
                                    {!! $trackingQR !!}
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-muted-foreground-1 text-center mb-2 break-all leading-tight select-all">{{ $trackingShortlink }}</p>
                            <div class="mb-3">
                            <input type="text" id="qr-slogan"
                                   placeholder="Ej: Escanéame y ve nuestro menú"
                                   maxlength="50"
                                   class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center text-xs"
                                   value="{{ $tenant->qr_slogan ?? '' }}">
                            <p class="text-[10px] text-muted-foreground-1 text-center mt-1">Este texto aparece en la tarjeta descargable</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="/tenant/{{ $tenant->id }}/qr/download" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover gap-1 flex-1" download>
                                <span class="iconify tabler--download size-3.5"></span>
                                PNG
                            </a>
                            <button type="button" onclick="downloadQRSVG()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors text-foreground hover:bg-muted-hover gap-1 flex-1">
                                <span class="iconify tabler--file-type-svg size-3.5"></span>
                                SVG
                            </button>
                        </div>

                        {{-- QR Sticker Generator --}}
                        <div class="mt-4 pt-4 border-t border-border">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="iconify tabler--sparkles size-4 text-primary"></span>
                                <span class="text-sm font-semibold text-foreground">Crear Sticker</span>
                            </div>

                            {{-- Canvas preview --}}
                            <div class="flex justify-center mb-3">
                                <canvas id="qr-sticker-canvas" width="400" height="500"
                                        class="rounded-xl border border-border shadow-sm"
                                        style="max-width:200px; height:auto;"></canvas>
                            </div>

                            {{-- Campos editables --}}
                            <div class="space-y-2 mb-3">
                                <input type="text" id="sticker-top-text"
                                       placeholder="Texto superior: Ej: 📍 Escanéame"
                                       maxlength="40"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center text-xs"
                                       oninput="renderStickerCanvas()">
                                <input type="text" id="sticker-bottom-text"
                                       placeholder="Texto inferior: Ej: Ver nuestro menú"
                                       maxlength="40"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center text-xs"
                                       oninput="renderStickerCanvas()">
                            </div>

                            <button type="button" onclick="downloadSticker()"
                                    class="w-full inline-flex items-center justify-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover gap-2">
                                <span class="iconify tabler--download size-4"></span>
                                Descargar Sticker
                            </button>
                        </div>
                    </div>
                </div>
            </div>{{-- /grid visual --}}
            </div>{{-- /wrapper px-6 pb-6 --}}
            </div>{{-- /itab-visual --}}

            {{-- ══ Sub-tab: Negocio ═══════════════════════════════════════════ --}}
            <div id="itab-negocio" class="info-tab-content hidden">

            {{-- ══ Info Form ══════════════════════════════════════════════════ --}}
            <form id="form-info" onsubmit="saveInfo(event)">
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="px-6 pt-6 pb-4">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--building-store size-5 text-primary"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-foreground">Información del Negocio</h2>
                                <p class="text-xs text-muted-foreground-1">Datos que se muestran en tu landing pública</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-border mx-6 mb-6"></div>
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-name">
                                    Nombre del Negocio *
                                </label>
                                <input id="info-name" type="text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="business_name" value="{{ $tenant->business_name }}" required autocomplete="organization"
                                       placeholder="Ej: Pizzería Don Marco"
                                       aria-label="Nombre de tu negocio">
                                <p class="text-xs text-muted-foreground-1 mt-1">Así aparecerá en tu página y en Google</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    Eslogan / Tagline
                                </label>
                                <input type="text" name="tagline" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ $tenant->tagline }}" 
                                       placeholder="Ej: La mejor pizza de Maracaibo"
                                       aria-label="Eslogan o tagline de tu negocio"
                                       autocomplete="off">
                                <p class="text-xs text-muted-foreground-1 mt-1">Una frase corta que te define</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    Subdominio
                                </label>
                                <input type="text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground-1"
                                       value="{{ $tenant->subdomain }}" disabled>
                                <p class="text-xs text-muted-foreground-1 mt-1">Tu dominio público (no se puede cambiar)</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    Teléfono
                                </label>
                                <input id="info-phone" type="tel" name="phone" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ $tenant->phone }}" 
                                       placeholder="+58 414 123 4567"
                                       aria-label="Teléfono de tu negocio"
                                       autocomplete="tel">
                                <p class="text-xs text-muted-foreground-1 mt-1">Incluye código de país</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    WhatsApp
                                </label>
                                <input id="info-whatsapp" type="tel" name="whatsapp_sales" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ $tenant->whatsapp_sales }}" 
                                       placeholder="+58 414 123 4567"
                                       aria-label="Número de WhatsApp de tu negocio"
                                       autocomplete="tel">
                                <p class="text-xs text-muted-foreground-1 mt-1">El número que recibirá los mensajes de tus clientes</p>
                            </div>
                            @if($plan->whatsapp_numbers >= 2)
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-whatsapp-support">
                                    WhatsApp Soporte
                                </label>
                                <input id="info-whatsapp-support" type="tel" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="whatsapp_support"
                                       value="{{ $tenant->whatsapp_support }}"
                                       placeholder="+58 414 000 0000"
                                       aria-label="WhatsApp de soporte o línea secundaria">
                                <p class="text-xs text-muted-foreground-1 mt-1">Línea de respaldo si la principal no está disponible</p>
                            </div>
                            @endif
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    Email
                                </label>
                                <input type="email" name="email" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ $tenant->email }}" 
                                       placeholder="contacto@tunegocio.com"
                                       aria-label="Email de tu negocio"
                                       autocomplete="email">
                                <p class="text-xs text-muted-foreground-1 mt-1">Para que tus clientes te contacten</p>
                            </div>
                            <div class="form-control sm:col-span-2">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-address">
                                    Dirección
                                </label>
                                <input id="info-address" type="text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="address" value="{{ $tenant->address }}" 
                                       placeholder="Av. Libertador, Torre X, Piso 3"
                                       aria-label="Dirección de tu negocio"
                                       autocomplete="street-address">
                                <p class="text-xs text-muted-foreground-1 mt-1">Ayuda a tus clientes a encontrarte</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-city">
                                    Ciudad
                                </label>
                                <input id="info-city" type="text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="city" value="{{ $tenant->city }}" 
                                       placeholder="Maracaibo"
                                       aria-label="Ciudad de tu negocio"
                                       autocomplete="address-level2">
                                <p class="text-xs text-muted-foreground-1 mt-1">Tu ubicación principal</p>
                            </div>
                            @if($tenant->isAtLeastCrecimiento())
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1">
                                        Título Contacto
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_title" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                       placeholder="Contáctanos">
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1">
                                        Subtítulo Contacto
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_subtitle" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                       placeholder="Estamos aquí para atenderte">
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1">
                                        Teléfono Secundario
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="tel" name="phone_secondary" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'contact_info.phone_secondary', '') }}"
                                       placeholder="+58 XXX XXXXXXX">
                            </div>
                            <div class="form-control sm:col-span-2 lg:col-span-3">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1">
                                        URL Google Maps
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="url" name="contact_maps_url" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                            </div>
                            @endif
                        </div>

                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-description">
                                Descripción del Negocio
                            </label>
                            <textarea id="info-description" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none min-h-20"
                                      name="description"
                                      placeholder="Cuéntale a tus clientes qué haces, por qué eres la mejor opción..."
                                      aria-label="Descripción de tu negocio">{{ $tenant->description }}</textarea>
                            <p class="text-xs text-muted-foreground-1 mt-1">Cuéntale a tus clientes qué haces y por qué eres la mejor opción</p>
                        </div>

                        {{-- ══ Contenido Hero ════════════════════════════════════════ --}}
                        <div class="border-t border-border my-4 mt-6 text-xs text-muted-foreground-1">Contenido del Hero</div>

                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-hero-title">
                                Título Hero <span class="text-muted-foreground-1 font-normal">(opcional)</span>
                            </label>
                            <input id="info-hero-title" type="text"
                                   class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                   name="content_blocks[hero][title]"
                                   placeholder="Ej: La mejor pizzería de Maracaibo"
                                   value="{{ $customization?->getContentBlock('hero', 'title') ?? '' }}">
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Si lo dejas vacío, usamos tu eslogan.</p>
                        </div>

                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-hero-subtitle">
                                Subtítulo Hero
                            </label>
                            <input id="info-hero-subtitle" type="text"
                                   class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                   name="content_blocks[hero][subtitle]"
                                   placeholder="Ej: Masa artesanal, ingredientes frescos, entrega en 30 min"
                                   value="{{ $customization?->getContentBlock('hero', 'subtitle') ?? '' }}">
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Complementa el título. No repitas el eslogan.</p>
                        </div>

                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-about-text">
                                Texto Acerca De
                            </label>
                            <textarea id="info-about-text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="4"
                                      name="about_text"
                                      placeholder="Cuéntale a tus clientes quiénes son, qué los hace especiales...">{{ $customization?->about_text ?? '' }}</textarea>
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Aparece en la sección "Acerca de" de tu página.</p>
                        </div>

                        {{-- ══ Indicador de Horario (Opcional) ═══════════════════════ --}}
                        <div class="border-t border-border my-4 mt-6 text-xs text-muted-foreground-1">Indicador de Horario en Navbar</div>

                        <div class="flex p-4 rounded-lg border gap-3 bg-info/5 border-info/20 text-info mb-4">
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
                                    <span class="text-sm font-medium text-foreground">¿Mostrar estado de horario en navbar?</span>
                                    <p class="text-xs text-muted-foreground-1 mt-0.5">Activa para mostrar badge ABIERTO/CERRADO junto al botón WhatsApp</p>
                                </div>
                            </label>
                        </div>

                        <div id="hours-indicator-fields" class="{{ data_get($tenant->settings, 'engine_settings.features.show_hours_indicator', false) ? '' : 'hidden' }} mt-4 p-4 bg-layer rounded-lg space-y-4">
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1 justify-between">
                                        Mensaje cuando estamos cerrados
                                        <span class="text-muted-foreground-1 text-xs font-normal" id="char-count">0 / 150</span>
                                    </span>
                                </label>
                                <textarea id="closed-message-input" name="closed_message" class="textarea textarea-bordered w-full min-h-16"
                                          placeholder="Estamos cerrados. Te responderemos durante nuestro horario de atención."
                                          maxlength="150"
                                          oninput="updateCharCount(); updatePreview()">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</textarea>
                                <p class="mt-1.5 text-xs text-muted-foreground-2">Este mensaje se usará en el botón de WhatsApp cuando tu negocio esté cerrado</p>
                            </div>

                            <div class="flex p-4 rounded-lg border gap-3 bg-info/5 border-info/20 text-info">
                                <span class="iconify tabler--eye size-5 shrink-0"></span>
                                <div class="text-xs">
                                    <p class="font-semibold mb-2">Así se verá en tu navbar</p>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium gap-1 bg-green-500 text-white">
                                            <span class="iconify tabler--circle-filled size-3"></span>
                                            ABIERTO
                                        </span>
                                        <span class="text-muted-foreground-1">o</span>
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium gap-1 bg-red-500 text-white">
                                            <span class="iconify tabler--circle-filled size-3"></span>
                                            CERRADO
                                        </span>
                                    </div>
                                    <p class="mt-3 p-2 bg-card rounded text-xs text-foreground border-l-2 border-info">
                                        <span class="font-semibold">Mensaje WhatsApp:</span> <span id="preview-message">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>{{-- /wrapper px-6 pb-6 --}}

                        <div class="flex items-center gap-3 justify-end pt-4 border-t border-border mt-4">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" onclick="resetForm('form-info')">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            </div>{{-- /itab-negocio --}}

            {{-- ══ Sub-tab: Horario ═══════════════════════════════════════════ --}}
            <div id="itab-horario" class="info-tab-content hidden">

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
            <div class="bg-surface rounded-xl shadow-sm border border-border mb-6"
                 x-data="{ hoursOpen: true }" @cloak>

                {{-- ── Compact header — always visible ── --}}
                <div class="px-5 py-4 cursor-pointer select-none"
                     @click="hoursOpen = !hoursOpen">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="iconify tabler--clock size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-sm font-bold text-foreground">Horario de Atención</h3>
                            </div>
                            <p id="hours-summary-line" class="text-xs text-muted-foreground-1 truncate">
                                {{ $hoursSummary ?: 'Sin horario configurado' }}
                            </p>
                        </div>
                        <span class="iconify tabler--chevron-down size-5 text-muted-foreground-1 shrink-0 transition-transform duration-200"
                              :class="hoursOpen && 'rotate-180'" aria-hidden="true"></span>
                    </div>
                </div>

                {{-- ── Expandable editor ── --}}
                <div x-show="hoursOpen" x-collapse x-cloak>
                    <div class="pt-0 px-6 pb-6">
                        <div class="border-t border-border pt-4">

                            {{-- Mode switcher --}}
                            <div class="flex rounded-lg bg-layer p-1 mb-4 gap-1">
                                <button type="button" id="hours-mode-simple" onclick="setHoursMode('simple')"
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'simple' ? 'bg-primary text-primary-content shadow-sm' : 'text-muted-foreground-1 hover:text-foreground' }}">
                                    Rápido
                                </button>
                                <button type="button" id="hours-mode-custom" onclick="setHoursMode('custom')"
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'custom' ? 'bg-primary text-primary-content shadow-sm' : 'text-muted-foreground-1 hover:text-foreground' }}">
                                    Por día
                                </button>
                            </div>

                            {{-- ── SIMPLE MODE ── --}}
                            <div id="hours-simple-mode" class="{{ $defaultMode === 'simple' ? '' : 'hidden' }}">
                                <div class="space-y-3">
                                    {{-- Weekdays --}}
                                    <div class="p-3 rounded-lg bg-layer border border-border">
                                        <div class="flex items-center gap-2 mb-2.5">
                                            <span class="text-sm font-semibold text-foreground">Lunes a Viernes</span>
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-info/10 text-info">5 días</span>
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <input type="time" id="bh-simple-wd-open" class="input input-sm input-bordered w-28" value="{{ $wdOpen }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
                                            <input type="time" id="bh-simple-wd-close" class="input input-sm input-bordered w-28" value="{{ $wdClose }}">
                                        </div>
                                    </div>

                                    {{-- Saturday --}}
                                    <div class="p-3 rounded-lg bg-layer border border-border">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-foreground">Sábado</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sat-closed" class="toggle toggle-error toggle-sm"
                                                       {{ $satClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sat-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sat-times" class="flex items-center gap-2 flex-wrap {{ $satClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sat-open" class="input input-sm input-bordered w-28" value="{{ $satData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
                                            <input type="time" id="bh-simple-sat-close" class="input input-sm input-bordered w-28" value="{{ $satData['close'] ?? '17:00' }}">
                                        </div>
                                    </div>

                                    {{-- Sunday --}}
                                    <div class="p-3 rounded-lg bg-layer border border-border">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-foreground">Domingo</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sun-closed" class="toggle toggle-error toggle-sm"
                                                       {{ $sunClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sun-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sun-times" class="flex items-center gap-2 flex-wrap {{ $sunClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sun-open" class="input input-sm input-bordered w-28" value="{{ $sunData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
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
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-layer border border-border">
                                        <span class="text-sm font-semibold text-foreground w-24 shrink-0">{{ $dayLabel }}</span>
                                        <div class="flex items-center gap-2 flex-1 flex-wrap">
                                            <input type="time" id="bh-{{ $dayKey }}-open"
                                                   class="input input-sm input-bordered w-28"
                                                   value="{{ $openTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                            <span class="text-xs text-muted-foreground-1">a</span>
                                            <input type="time" id="bh-{{ $dayKey }}-close"
                                                   class="input input-sm input-bordered w-28"
                                                   value="{{ $closeTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                        </div>
                                        <label class="label cursor-pointer gap-2 shrink-0">
                                            <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                            <input type="checkbox" id="bh-{{ $dayKey }}-closed"
                                                   class="toggle toggle-error toggle-sm"
                                                   {{ $isClosed ? 'checked' : '' }}
                                                   onchange="toggleDayClosed('{{ $dayKey }}', this.checked)">
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" onclick="saveBusinessHours()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover w-full gap-2 mt-4">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Horario
                            </button>
                        </div>
                    </div>
                </div>
            </div>{{-- /horario card --}}

            </div>{{-- /itab-horario --}}

            {{-- ══ Sub-tab: Redes ════════════════════════════════════════════ --}}
            <div id="itab-redes" class="info-tab-content hidden">

            {{-- ═══════════════════════════════════════════════════════════
                 Redes Sociales
            ═══════════════════════════════════════════════════════════ --}}
            <div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
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

                <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="iconify tabler--social size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <h3 class="text-lg font-bold text-foreground">Redes Sociales</h3>
                    </div>
                    @if($plan->id === 1)
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">Plan OPORTUNIDAD — 1 red social</span>
                    @else
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-green-100 text-green-700">Plan {{ $plan->name }} — Todas las redes</span>
                    @endif
                </div>
                <div class="px-6 pb-6">
                    @if($plan->id === 1)
                    {{-- ── Plan 1: radio select + single handle ── --}}
                    <div class="mb-4">
                        <label class="inline-block text-sm font-medium text-foreground mb-1">Elige tu red social</label>
                        <div class="flex flex-wrap gap-2 mb-4" id="social-radio-group">
                            @foreach($plan1Networks as $key)
                            @php $meta = $allNetworksMeta[$key]; @endphp
                            <label id="social-radio-label-{{ $key }}"
                                   onclick="selectSocialNetwork('{{ $key }}')"
                                   class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors {{ $plan1Selected === $key ? 'bg-primary text-white hover:bg-primary-hover' : 'text-foreground hover:bg-muted-hover border border-border' }} cursor-pointer">
                                <input type="radio" name="social_plan1_choice" value="{{ $key }}"
                                       {{ $plan1Selected === $key ? 'checked' : '' }} class="hidden">
                                <span class="iconify {{ $meta['icon'] }} size-4" aria-hidden="true"></span>
                                {{ $meta['label'] }}
                            </label>
                            @endforeach
                        </div>
                        <div class="form-control">
                            <label class="inline-block text-sm font-medium text-foreground mb-1">
                                Tu usuario o enlace
                                <span id="social-plan1-network-label" class="text-primary ml-1">
                                    {{ $plan1Selected ? '(' . $allNetworksMeta[$plan1Selected]['label'] . ')' : '' }}
                                </span>
                            </label>
                            <input type="text" id="social-plan1-handle"
                                   value="{{ $plan1Handle }}"
                                   placeholder="{{ $plan1Selected ? $allNetworksMeta[$plan1Selected]['placeholder'] : 'Selecciona una red primero' }}"
                                   class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                   {{ !$plan1Selected ? 'disabled' : '' }}>
                        </div>
                    </div>

                    @else
                    {{-- ── Plan 2 + 3: grid cubo Rubik ── --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4" id="social-all-fields">
                        @foreach($availableKeys as $key)
                        @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                            <div class="flex flex-col items-center gap-2 p-3 rounded-lg border border-border bg-layer transition-all hover:border-primary/30 hover:bg-primary/5">
                            <span class="iconify {{ $meta['icon'] }} size-7 text-primary" aria-hidden="true"></span>
                            <span class="text-[11px] font-semibold text-foreground">{{ $meta['label'] }}</span>
                            <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                                   value="{{ $current }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   maxlength="255"
                                   class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center text-xs">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <button type="button" onclick="saveSocialNetworks()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover w-full gap-2">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Redes Sociales
                    </button>
                </div>
            </div>{{-- /redes card --}}

            </div>{{-- /itab-redes --}}

        </div>{{-- /tab-info --}}

        <script>
        function switchInfoTab(tab) {
            document.querySelectorAll('.info-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.info-tab-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-surface', 'shadow-sm', 'text-primary');
                btn.classList.add('text-muted-foreground-1');
            });
            document.getElementById('itab-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('itab-' + tab + '-btn');
            activeBtn.classList.add('active', 'bg-surface', 'shadow-sm', 'text-primary');
            activeBtn.classList.remove('text-muted-foreground-1');
        }
        function uploadAbout(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/tenant/{{ $tenant->id }}/upload/about`, {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const dropzone = document.getElementById('about-dropzone');
                    dropzone.innerHTML = `<img src="${data.url}" class="w-full h-full object-cover" alt="Acerca de">`;
                    showToast('✅ Imagen actualizada');
                } else {
                    showToast('❌ Error al subir imagen', 'error');
                }
            })
            .catch(() => showToast('❌ Error de conexión', 'error'));
        }

        // ── QR Sticker Generator ──────────────────────────────────────
        const STICKER_BIZ_NAME = @json($tenant->business_name ?? 'Mi Negocio');
        const STICKER_PRIMARY  = getComputedStyle(document.documentElement)
                                   .getPropertyValue('--color-primary').trim() || '#4A80E4';

        function getStickerQRDataURL() {
            const qrSvg = document.querySelector('#qr-display svg');
            if (!qrSvg) return null;
            const svgData = new XMLSerializer().serializeToString(qrSvg);
            const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            return URL.createObjectURL(svgBlob);
        }

        function renderStickerCanvas() {
            const canvas  = document.getElementById('qr-sticker-canvas');
            if (!canvas) return;
            const ctx     = canvas.getContext('2d');
            const W = 400, H = 500;
            const topText    = document.getElementById('sticker-top-text')?.value    || '📍 Escanéame';
            const bottomText = document.getElementById('sticker-bottom-text')?.value || STICKER_BIZ_NAME;

            // Background
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.roundRect(0, 0, W, H, 24);
            ctx.fill();

            // Primary color bar top
            ctx.fillStyle = STICKER_PRIMARY;
            ctx.beginPath();
            ctx.roundRect(0, 0, W, 70, [24, 24, 0, 0]);
            ctx.fill();

            // Top text
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 22px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(topText, W / 2, 44);

            // Business name below bar
            ctx.fillStyle = '#1a1a1a';
            ctx.font = 'bold 20px Inter, sans-serif';
            ctx.fillText(STICKER_BIZ_NAME, W / 2, 110);

            // QR code
            const qrUrl = getStickerQRDataURL();
            if (qrUrl) {
                const qrImg = new Image();
                qrImg.onload = () => {
                    ctx.drawImage(qrImg, 60, 130, 280, 280);
                    URL.revokeObjectURL(qrUrl);

                    // Bottom text
                    ctx.fillStyle = STICKER_PRIMARY;
                    ctx.font = 'bold 18px Inter, sans-serif';
                    ctx.fillText(bottomText, W / 2, 450);

                    // SYNTIweb badge
                    ctx.fillStyle = '#94a3b8';
                    ctx.font = '12px Inter, sans-serif';
                    ctx.fillText('syntiweb.com', W / 2, 480);
                };
                qrImg.src = qrUrl;
            }
        }

        function downloadSticker() {
            renderStickerCanvas();
            setTimeout(() => {
                const canvas = document.getElementById('qr-sticker-canvas');
                const link   = document.createElement('a');
                link.download = 'sticker-qr-' + STICKER_BIZ_NAME.toLowerCase().replace(/\s+/g, '-') + '.png';
                link.href     = canvas.toDataURL('image/png', 1.0);
                link.click();
            }, 300);
        }

        // Render inicial al cargar
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(renderStickerCanvas, 500);
        });
        // Re-render al cambiar al sub-tab Visual
        document.getElementById('itab-visual-btn')
            ?.addEventListener('click', () => setTimeout(renderStickerCanvas, 100));
        // ── End QR Sticker Generator ──────────────────────────────────
        </script>
