        <!-- Tab: Visual — Imágenes, Logo, QR -->
        <div id="tab-visual" class="tab-content">
            <div class="px-6 pb-6 pt-2">
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
                             class="relative bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('logo-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'logo')">
                            @if($customization && $customization->logo_filename)
                                <img id="logo-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                                     alt="Logo" class="max-w-full max-h-full object-contain">
                                <button type="button"
                                        onclick="event.stopPropagation(); openImgPreview('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}')"
                                        class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/25 transition-colors group">
                                    <span class="iconify tabler--eye size-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"></span>
                                </button>
                            @else
                                <div id="logo-placeholder" class="text-center">
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
                             class="relative bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('hero-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'hero')">
                            @if($customization && $customization->hero_main_filename)
                                <img id="hero-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename) }}"
                                     alt="Hero" class="w-full h-full object-cover">
                                <button type="button"
                                        onclick="event.stopPropagation(); openImgPreview('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename) }}')"
                                        class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/25 transition-colors group">
                                    <span class="iconify tabler--eye size-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"></span>
                                </button>
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
                             class="relative bg-layer rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('about-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'about')">
                            @if($customization && $customization->about_image_filename)
                                <img id="about-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->about_image_filename) }}"
                                     alt="Acerca de" class="w-full h-full object-cover">
                                <button type="button"
                                        onclick="event.stopPropagation(); openImgPreview('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->about_image_filename) }}')"
                                        class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/25 transition-colors group">
                                    <span class="iconify tabler--eye size-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"></span>
                                </button>
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
                                <span class="iconify tabler--scan size-5 text-primary"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground">Tu Código Digital</h3>
                                <p class="text-xs text-muted-foreground-1">Imprime una vez, mide siempre</p>
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
                                   placeholder="Ej: Apunta tu cámara y descubre más"
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
                            <div class="flex justify-center mb-3">
                                <canvas id="qr-sticker-canvas" width="400" height="500"
                                        class="rounded-xl border border-border shadow-sm"
                                        style="max-width:200px; height:auto;"></canvas>
                            </div>
                            <div class="space-y-2 mb-3">
                                <input type="text" id="sticker-top-text"
                                       placeholder="Ej: Usa tu cámara"
                                       maxlength="40"
                                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center text-xs"
                                       oninput="renderStickerCanvas()">
                                <input type="text" id="sticker-bottom-text"
                                       placeholder="Ej: Información"
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
            </div>{{-- /wrapper --}}

        {{-- Fullscreen image preview modal --}}
        <div id="img-preview-modal"
             onclick="closeImgPreview()"
             class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/85 backdrop-blur-sm p-4"
             style="display:none">
            <img id="img-preview-src" src="" alt="Vista previa"
                 class="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
                 onclick="event.stopPropagation()">
            <button type="button"
                    onclick="event.stopPropagation(); closeImgPreview()"
                    class="absolute top-4 right-4 size-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                <span class="iconify tabler--x size-5"></span>
            </button>
        </div>

        {{-- ── Image Preview + QR Brand Colorization ── --}}
        <script>
        window.openImgPreview = function(url) {
            document.getElementById('img-preview-src').src = url;
            document.getElementById('img-preview-modal').style.display = 'flex';
        };
        window.closeImgPreview = function() {
            document.getElementById('img-preview-modal').style.display = 'none';
            document.getElementById('img-preview-src').src = '';
        };
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { window.closeImgPreview && closeImgPreview(); }
        });

        (function () {
            function applyQRColor(sel) {
                var p = getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim() || '#1a1a1a';
                var svg = document.querySelector(sel + ' svg');
                if (!svg) return;
                svg.querySelectorAll('[fill]').forEach(function (el) {
                    var f = el.getAttribute('fill');
                    if (f && (f === '#000000' || f.toLowerCase() === '#000' || f.toLowerCase() === 'black')) {
                        el.setAttribute('fill', p);
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () { applyQRColor('#qr-display'); }, 200);
            });
            var vBtn = document.getElementById('itab-visual-btn');
            if (vBtn) vBtn.addEventListener('click', function () {
                setTimeout(function () { applyQRColor('#qr-display'); }, 200);
            });
        })();
        </script>
        </div>{{-- /tab-visual --}}
