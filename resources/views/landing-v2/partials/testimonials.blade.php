{{-- Testimonials Section Partial - FlyonUI --}}
<section id="testimonials">
    <div class="bg-base-100 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div id="testimonials-carousel" data-carousel='{ "loadingClasses": "opacity-0", "slidesQty": { "xs": 1, "md": 2 } }' class="relative flex w-full gap-12 max-lg:flex-col md:gap-16 lg:items-center lg:gap-24">
                {{-- Header & Controls --}}
                <div>
                    <div class="space-y-4">
                        <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Lo que dicen nuestros clientes</h2>
                        <p class="text-base-content/80 text-xl">
                            La satisfacción de nuestros clientes es nuestra mayor recompensa.
                        </p>
                        <div class="flex gap-4">
                            <button class="btn btn-square btn-sm carousel-prev btn-primary carousel-disabled:opacity-100 carousel-disabled:btn-outline relative hover:text-white" disabled="disabled">
                                <span class="icon-[tabler--arrow-left] size-5"></span>
                            </button>
                            <button class="btn btn-square btn-sm carousel-next btn-primary carousel-disabled:opacity-100 carousel-disabled:btn-outline relative hover:text-white">
                                <span class="icon-[tabler--arrow-right] size-5"></span>
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Carousel --}}
                <div class="carousel rounded-box">
                    <div class="carousel-body gap-6 opacity-0">
                        {{-- Testimonial 1 --}}
                        <div class="carousel-slide">
                            <div class="card card-border hover:border-primary transition-border h-full shadow-none duration-300">
                                <div class="card-body gap-5">
                                    {{-- User Info --}}
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content size-10 rounded-full">
                                                <span class="text-lg">MC</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-base-content font-medium">María C.</h4>
                                            <p class="text-base-content/80 text-sm">Cliente frecuente</p>
                                        </div>
                                    </div>
                                    {{-- Stars --}}
                                    <div class="flex gap-1">
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                    </div>
                                    {{-- Content --}}
                                    <p class="text-base-content/80">"Excelente atención y productos de primera calidad. Siempre vuelvo porque la experiencia es inmejorable."</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Testimonial 2 --}}
                        <div class="carousel-slide">
                            <div class="card card-border hover:border-primary transition-border h-full shadow-none duration-300">
                                <div class="card-body gap-5">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-secondary text-secondary-content size-10 rounded-full">
                                                <span class="text-lg">JP</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-base-content font-medium">José P.</h4>
                                            <p class="text-base-content/80 text-sm">Cliente nuevo</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-half-filled] text-warning size-6 shrink-0"></span>
                                    </div>
                                    <p class="text-base-content/80">"Me recomendaron este negocio y no me decepcionó. El servicio por WhatsApp es súper rápido y eficiente."</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Testimonial 3 --}}
                        <div class="carousel-slide">
                            <div class="card card-border hover:border-primary transition-border h-full shadow-none duration-300">
                                <div class="card-body gap-5">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-accent text-accent-content size-10 rounded-full">
                                                <span class="text-lg">LR</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-base-content font-medium">Laura R.</h4>
                                            <p class="text-base-content/80 text-sm">Cliente VIP</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                    </div>
                                    <p class="text-base-content/80">"La mejor relación calidad-precio. Los productos son excelentes y el trato es muy profesional. 100% recomendado."</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Testimonial 4 --}}
                        <div class="carousel-slide">
                            <div class="card card-border hover:border-primary transition-border h-full shadow-none duration-300">
                                <div class="card-body gap-5">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-info text-info-content size-10 rounded-full">
                                                <span class="text-lg">AG</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-base-content font-medium">Andrés G.</h4>
                                            <p class="text-base-content/80 text-sm">Cliente empresarial</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                        <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                                    </div>
                                    <p class="text-base-content/80">"Hacemos pedidos regularmente para nuestra empresa. La puntualidad y seriedad son impecables."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
