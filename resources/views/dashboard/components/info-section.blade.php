        <!-- Tab: Tu Información -->
        <div id="tab-info" class="tab-content active">

            {{-- ══ Sub-tab nav ═══════════════════════════════════════════════ --}}
            <div class="flex gap-1 mb-6 bg-layer p-1 rounded-xl border border-border">
                <button onclick="switchInfoTab('negocio')" id="itab-negocio-btn"
                    class="info-tab-btn active flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all bg-surface shadow-sm text-primary">
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

            {{-- ══ Sub-tab: Negocio ═══════════════════════════════════════════ --}}
            <div id="itab-negocio" class="info-tab-content">

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
                                    Eslogan
                                </label>
                                <input type="text" name="slogan" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ $tenant->slogan }}" 
                                       placeholder="Ej: La mejor pizza de Maracaibo"
                                       aria-label="Eslogan de tu negocio"
                                       autocomplete="off">
                                <p class="text-xs text-muted-foreground-1 mt-1">Título principal del Hero cuando no hay título personalizado</p>
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
                                <div class="flex items-center gap-2">
                                    <span class="py-1.5 sm:py-2 px-3 bg-muted/60 border border-layer-line rounded-lg text-sm font-medium text-foreground select-none shrink-0">+58</span>
                                    <input id="info-whatsapp" type="tel" name="whatsapp_sales"
                                           class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                           value="{{ preg_replace('/^58/', '', preg_replace('/\D/', '', $tenant->whatsapp_sales ?? '')) }}"
                                           placeholder="4241234567"
                                           maxlength="10"
                                           pattern="(0?)(412|414|416|422|424|426)\d{7}"
                                           aria-label="Número de WhatsApp de tu negocio"
                                           autocomplete="tel">
                                </div>
                                <p id="wa-validation-msg" class="text-xs mt-1 hidden text-red-500"></p>
                                <p class="text-xs text-muted-foreground-1 mt-1">Solo Venezuela. Operadoras: 412, 414, 416, 422, 424, 426</p>
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
                            {{-- Título y Subtítulo Contacto: disponibles para TODOS los planes --}}
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-contact-title">
                                    Título Sección Contacto
                                </label>
                                <input id="info-contact-title" type="text" name="contact_title" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                       placeholder="Estamos para ayudarte">
                                <p class="text-xs text-muted-foreground-1 mt-1">Encabezado de la sección Contáctanos. Si está vacío, usa el Eslogan.</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-contact-subtitle">
                                    Texto Sección Contacto
                                </label>
                                <input id="info-contact-subtitle" type="text" name="contact_subtitle" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                       placeholder="Contáctanos y te atendemos a la brevedad.">
                                <p class="text-xs text-muted-foreground-1 mt-1">Párrafo descriptivo debajo del título de la sección Contáctanos. Variable independiente.</p>
                            </div>
                            @if($tenant->isAtLeastCrecimiento() && !in_array($blueprint ?? '', ['food', 'cat']))
                            <div class="form-control sm:col-span-2 lg:col-span-3">
                                <label class="inline-block text-sm font-medium text-foreground mb-1">
                                    <span class="flex items-center gap-1">
                                        URL Google Maps
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="url" id="contact-maps-url-input" name="contact_maps_url" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                  <p class="text-xs text-blue-900 font-semibold mb-2">💡 Cómo obtener el enlace:</p>
                                  <ol class="text-xs text-blue-800 space-y-1 list-decimal list-inside">
                                    <li>Abre la ubicación en <strong>Google Maps</strong></li>
                                    <li>Haz clic en <strong>"Compartir"</strong></li>
                                    <li>Selecciona la pestaña <strong>"Incorporar un mapa"</strong></li>
                                    <li>Copia el código <code class="bg-white px-1 rounded">&lt;iframe&gt;...&lt;/iframe&gt;</code> <strong>completo</strong></li>
                                    <li>Pégalo aquí ↑ <strong>El sistema extraerá automáticamente la URL</strong></li>
                                  </ol>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- ══ SEO / Metadatos ══════════════════════════════════════ --}}
                        <div class="border-t border-border my-4 mt-6 text-xs text-muted-foreground-1">SEO y Metadatos</div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-meta-title">
                                    Título SEO (meta title)
                                </label>
                                <input id="info-meta-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="meta_title"
                                       placeholder="Ej: Pizzería Don Marco — La mejor de Maracaibo"
                                       value="{{ $tenant->meta_title ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Aparece en la pestaña del navegador y en Google. Vacío = nombre del negocio.</p>
                            </div>
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-meta-keywords">
                                    Palabras clave SEO
                                </label>
                                <input id="info-meta-keywords" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="meta_keywords"
                                       placeholder="pizza, delivery, maracaibo, artesanal"
                                       value="{{ $tenant->meta_keywords ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Separadas por coma. Ayudan al posicionamiento en buscadores.</p>
                            </div>
                        </div>

                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-meta-description">
                                Descripción SEO (meta description)
                            </label>
                            <textarea id="info-meta-description" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none min-h-20"
                                      name="meta_description"
                                      maxlength="255"
                                      placeholder="Breve descripción de tu negocio para buscadores..."
                                      aria-label="Descripción SEO de tu negocio">{{ $tenant->meta_description ?? '' }}</textarea>
                            <p class="text-xs text-muted-foreground-1 mt-1">Usada como <strong>meta descripción en Google</strong>, OpenGraph y Schema.org. Vacío = usa la descripción del negocio como fallback.</p>
                        </div>

                        {{-- ══ Contenido Hero ════════════════════════════════════════ --}}
                        @if($blueprint !== 'food')
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
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Si lo dejas vacío, el Hero usa tu <strong>Eslogan</strong> como título.</p>
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
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Texto secundario del Hero. Si está vacío, no se muestra subtítulo.</p>
                        </div>
                        @endif

                        @if($blueprint !== 'food')
                        <div class="form-control mt-3">
                            <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-about-text">
                                Texto Sección Acerca De
                            </label>
                            <textarea id="info-about-text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="4"
                                      name="about_text"
                                      placeholder="Cuéntale a tus clientes quiénes son, qué los hace especiales...">{{ $customization?->about_text ?? '' }}</textarea>
                            <p class="mt-1.5 text-xs text-muted-foreground-2">Texto de la sección &ldquo;Acerca de nosotros&rdquo;. <strong>Variable independiente</strong>, no comparte contenido con Hero ni Contacto.</p>
                        </div>
                        @endif
                        @if(!in_array($blueprint ?? '', ['food', 'cat']))
                        {{-- ══ Títulos de Secciones (editables por sección) ══════════ --}}
                        <div class="border-t border-border my-4 mt-6 text-xs text-muted-foreground-1">Títulos de Secciones</div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                            @if(!in_array($blueprint ?? '', ['food', 'cat']))
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-products-title">
                                    Título Sección Productos
                                </label>
                                <input id="info-products-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[products][title]"
                                       placeholder="Nuestros Productos"
                                       value="{{ $customization?->getContentBlock('products', 'title') ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Vacío = "Nuestros Productos"</p>
                            </div>

                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-products-subtitle">
                                    Subtítulo Sección Productos
                                </label>
                                <input id="info-products-subtitle" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[products][subtitle]"
                                       placeholder="Descubre lo mejor que tenemos para ofrecerte…"
                                       value="{{ $customization?->getContentBlock('products', 'subtitle') ?? '' }}">
                            </div>
                            @endif

                            @if($blueprint !== 'food')
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-services-title">
                                    Título Sección Servicios
                                </label>
                                <input id="info-services-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[services][title]"
                                       placeholder="Nuestros Servicios"
                                       value="{{ $customization?->getContentBlock('services', 'title') ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Vacío = "Nuestros Servicios"</p>
                            </div>

                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-services-subtitle">
                                    Subtítulo Sección Servicios
                                </label>
                                <input id="info-services-subtitle" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[services][subtitle]"
                                       placeholder="Soluciones diseñadas para hacer tu experiencia única…"
                                       value="{{ $customization?->getContentBlock('services', 'subtitle') ?? '' }}">
                            </div>

                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-about-title">
                                    Título Sección Acerca De
                                </label>
                                <input id="info-about-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[about][title]"
                                       placeholder="Acerca de nosotros"
                                       value="{{ $customization?->getContentBlock('about', 'title') ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Vacío = "Acerca de nosotros"</p>
                            </div>
                            @endif

                            @if($blueprint !== 'food')
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-contact-h2">
                                    Título Sección Contacto (h2)
                                </label>
                                <input id="info-contact-h2" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[contact][title]"
                                       placeholder="Contáctanos"
                                       value="{{ $customization?->getContentBlock('contact', 'title') ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Vacío = "Contáctanos"</p>
                            </div>
                            @endif

                            @if($blueprint !== 'food')
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-payment-title">
                                    Título Sección Medios de Pago
                                </label>
                                <input id="info-payment-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[payment_methods][title]"
                                       placeholder="Medios de Pago"
                                       value="{{ $customization?->getContentBlock('payment_methods', 'title') ?? '' }}">
                                <p class="text-xs text-muted-foreground-1 mt-1">Vacío = "Medios de Pago"</p>
                            </div>
                            @endif

                            @if($blueprint !== 'food' && $tenant->isAtLeastCrecimiento())
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-testimonials-title">
                                    <span class="flex items-center gap-1">
                                        Título Sección Testimonios
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input id="info-testimonials-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[testimonials][title]"
                                       placeholder="Testimonios de Clientes"
                                       value="{{ $customization?->getContentBlock('testimonials', 'title') ?? '' }}">
                            </div>
                            @endif

                            @if($blueprint !== 'food' && $tenant->plan_id >= 3)
                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-faq-title">
                                    <span class="flex items-center gap-1">
                                        Título Sección FAQ
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-purple-100 text-purple-700">VISIÓN</span>
                                    </span>
                                </label>
                                <input id="info-faq-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[faq][title]"
                                       placeholder="Preguntas Frecuentes"
                                       value="{{ $customization?->getContentBlock('faq', 'title') ?? '' }}">
                            </div>

                            <div class="form-control">
                                <label class="inline-block text-sm font-medium text-foreground mb-1" for="info-branches-title">
                                    <span class="flex items-center gap-1">
                                        Título Sección Sucursales
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-purple-100 text-purple-700">VISIÓN</span>
                                    </span>
                                </label>
                                <input id="info-branches-title" type="text"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                       name="content_blocks[branches][title]"
                                       placeholder="Encuéntranos Cerca de Ti"
                                       value="{{ $customization?->getContentBlock('branches', 'title') ?? '' }}">
                            </div>
                            @endif

                        </div>
                        @endif

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

                {{-- ══ Indicador de Horario (Opcional) ═══════════════════════ --}}
                <div class="px-6 pb-4 pt-4">
                    <div class="border-t border-border my-4 mt-0 text-xs text-muted-foreground-1">Indicador de Horario en Navbar</div>

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
                                   class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-green-500 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-green-500 focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
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
                            <textarea id="closed-message-input" name="closed_message" class="py-2 px-3 block w-full bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus min-h-16"
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
                </div>{{-- /indicador horario --}}

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
                                            <input type="time" id="bh-simple-wd-open" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $wdOpen }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
                                            <input type="time" id="bh-simple-wd-close" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $wdClose }}">
                                        </div>
                                    </div>

                                    {{-- Saturday --}}
                                    <div class="p-3 rounded-lg bg-layer border border-border">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-foreground">Sábado</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sat-closed" class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-red-500 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-red-500 focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
                                                       {{ $satClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sat-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sat-times" class="flex items-center gap-2 flex-wrap {{ $satClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sat-open" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $satData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
                                            <input type="time" id="bh-simple-sat-close" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $satData['close'] ?? '17:00' }}">
                                        </div>
                                    </div>

                                    {{-- Sunday --}}
                                    <div class="p-3 rounded-lg bg-layer border border-border">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-sm font-semibold text-foreground">Domingo</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                                <input type="checkbox" id="bh-simple-sun-closed" class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-red-500 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-red-500 focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
                                                       {{ $sunClosed ? 'checked' : '' }}
                                                       onchange="document.getElementById('bh-simple-sun-times').classList.toggle('hidden', this.checked)">
                                            </label>
                                        </div>
                                        <div id="bh-simple-sun-times" class="flex items-center gap-2 flex-wrap {{ $sunClosed ? 'hidden' : '' }}">
                                            <input type="time" id="bh-simple-sun-open" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $sunData['open'] ?? '09:00' }}">
                                            <span class="text-xs text-muted-foreground-1 font-medium">a</span>
                                            <input type="time" id="bh-simple-sun-close" class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28" value="{{ $sunData['close'] ?? '14:00' }}">
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
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-layer border border-border flex-wrap">
                                        <span class="text-sm font-semibold text-foreground w-24 shrink-0">{{ $dayLabel }}</span>
                                        <div class="flex items-center gap-2 flex-1 flex-wrap min-w-0">
                                            <input type="time" id="bh-{{ $dayKey }}-open"
                                                   class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28"
                                                   value="{{ $openTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                            <span class="text-xs text-muted-foreground-1">a</span>
                                            <input type="time" id="bh-{{ $dayKey }}-close"
                                                   class="py-1.5 px-2.5 block bg-layer border border-border shadow-2xs rounded-lg text-sm text-foreground focus:border-primary-focus focus:ring-primary-focus w-28"
                                                   value="{{ $closeTime }}"
                                                   {{ $isClosed ? 'disabled' : '' }}>
                                        </div>
                                        <label class="flex items-center cursor-pointer gap-2 shrink-0">
                                            <span class="text-xs text-muted-foreground-1">Cerrado</span>
                                            <input type="checkbox" id="bh-{{ $dayKey }}-closed"
                                                   class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-red-500 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-red-500 focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
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

        // ── Scan corners (marco profesional tipo visor) ──────────────
        function drawScanCorners(ctx, x, y, size, cLen, lw, color) {
            ctx.save();
            ctx.strokeStyle = color;
            ctx.lineWidth   = lw;
            ctx.lineCap     = 'square';
            ctx.lineJoin    = 'miter';
            const c = cLen;
            // TL
            ctx.beginPath(); ctx.moveTo(x + c, y);        ctx.lineTo(x, y);        ctx.lineTo(x, y + c);        ctx.stroke();
            // TR
            ctx.beginPath(); ctx.moveTo(x + size - c, y); ctx.lineTo(x + size, y); ctx.lineTo(x + size, y + c); ctx.stroke();
            // BR
            ctx.beginPath(); ctx.moveTo(x + size, y + size - c); ctx.lineTo(x + size, y + size); ctx.lineTo(x + size - c, y + size); ctx.stroke();
            // BL
            ctx.beginPath(); ctx.moveTo(x + c, y + size);  ctx.lineTo(x, y + size);  ctx.lineTo(x, y + size - c);  ctx.stroke();
            ctx.restore();
        }

        function renderStickerCanvas() {
            const canvas  = document.getElementById('qr-sticker-canvas');
            if (!canvas) return;
            const ctx     = canvas.getContext('2d');
            const W = 400, H = 500;
            const topText    = document.getElementById('sticker-top-text')?.value    || 'Usa tu cámara';
            const bottomText = document.getElementById('sticker-bottom-text')?.value || 'Información';

            // Background (off-white premium)
            ctx.fillStyle = '#f8fafc';
            ctx.beginPath();
            ctx.roundRect(0, 0, W, H, 24);
            ctx.fill();

            // Primary color bar top
            ctx.fillStyle = STICKER_PRIMARY;
            ctx.beginPath();
            ctx.roundRect(0, 0, W, 74, [24, 24, 0, 0]);
            ctx.fill();

            // Top text — call to action (ALL CAPS, clean)
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 20px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(topText.toUpperCase(), W / 2, 46);

            // Business name
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 21px Inter, sans-serif';
            ctx.fillText(STICKER_BIZ_NAME, W / 2, 114);

            // Scan corners (before QR — drawn again after for visibility)
            drawScanCorners(ctx, 48, 124, 304, 30, 5, STICKER_PRIMARY);

            // QR code
            const qrUrl = getStickerQRDataURL();
            if (qrUrl) {
                const qrImg = new Image();
                qrImg.onload = () => {
                    ctx.drawImage(qrImg, 58, 134, 284, 284);
                    URL.revokeObjectURL(qrUrl);

                    // Redraw corners on top for crisp look
                    drawScanCorners(ctx, 48, 124, 304, 30, 5, STICKER_PRIMARY);

                    // Bottom tag — brand color
                    ctx.fillStyle = STICKER_PRIMARY;
                    ctx.font      = 'bold 17px Inter, sans-serif';
                    ctx.fillText(bottomText, W / 2, 452);

                    // SYNTIweb wordmark
                    ctx.fillStyle = '#94a3b8';
                    ctx.font      = '12px Inter, sans-serif';
                    ctx.fillText('syntiweb.com', W / 2, 482);
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

        // ── Google Maps Embed URL Extractor ──────────────────────────
        // Detecta si el usuario pegó HTML de iframe y extrae automáticamente la URL
        const mapsUrlInput = document.getElementById('contact-maps-url-input');
        if (mapsUrlInput) {
            mapsUrlInput.addEventListener('input', function() {
                const value = this.value.trim();
                
                // Si contiene <iframe, extrae la URL del atributo src
                if (value.includes('<iframe') && value.includes('src=')) {
                    const srcMatch = value.match(/src=["']([^"']+)["']/);
                    if (srcMatch && srcMatch[1]) {
                        const extractedUrl = srcMatch[1];
                        this.value = extractedUrl;
                        
                        // Feedback visual: borde verde temporalmente
                        this.classList.add('border-green-500', 'border');
                        setTimeout(() => {
                            this.classList.remove('border-green-500', 'border');
                        }, 2000);
                    }
                }
            });
            
            // Evento paste para capturar pega acelerada
            mapsUrlInput.addEventListener('paste', function(e) {
                setTimeout(() => {
                    const value = this.value.trim();
                    if (value.includes('<iframe') && value.includes('src=')) {
                        const srcMatch = value.match(/src=["']([^"']+)["']/);
                        if (srcMatch && srcMatch[1]) {
                            const extractedUrl = srcMatch[1];
                            this.value = extractedUrl;
                            
                            // Feedback: toast visual
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
                            toast.textContent = '✓ URL de Google Maps extraída correctamente';
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 3000);
                        }
                    }
                }, 50);
            });
        }
        // ── End Google Maps URL Extractor ──────────────────────────────
        </script>
