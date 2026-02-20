{{-- About Us Section Partial - FlyonUI --}}
<section id="about">
    <div class="bg-base-200 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-12 md:gap-16 lg:gap-24">
                {{-- Header Section --}}
                <div class="space-y-4 text-center">
                    <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Sobre Nosotros</h2>
                    <p class="text-base-content/80 text-xl">
                        {{ $customization->about_text ?? 'Somos un negocio comprometido con la calidad y el mejor servicio. Cada detalle está pensado para ofrecerte una experiencia única.' }}
                    </p>
                    @if($tenant->whatsapp_sales)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}" 
                       target="_blank"
                       class="btn btn-primary btn-lg btn-gradient">
                        Conocer más
                        <span class="icon-[tabler--arrow-right] size-5 rtl:rotate-180"></span>
                    </a>
                    @endif
                </div>
                
                {{-- Image & Stats --}}
                <div class="lg:h-161 relative mb-8 h-full w-full rounded-xl max-lg:space-y-6 sm:mb-16 lg:mb-24">
                    {{-- About Image --}}
                    @if($customization->about_image_filename ?? null)
                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->about_image_filename) }}" 
                         alt="Sobre {{ $tenant->business_name }}" 
                         class="h-full w-full rounded-xl object-cover" />
                    @else
                    <div class="bg-primary/10 flex h-64 lg:h-full w-full items-center justify-center rounded-xl">
                        <span class="icon-[tabler--building-store] size-32 text-primary/30"></span>
                    </div>
                    @endif
                    
                    {{-- Stats Card --}}
                    <div class="bg-base-100 border-base-content/20 rounded-box lg:-bottom-25 intersect:motion-preset-fade intersect:motion-opacity-0 intersect:motion-duration-800 grid gap-10 border px-10 py-8 sm:max-lg:grid-cols-2 lg:absolute lg:left-1/2 lg:w-3/4 lg:-translate-x-1/2 lg:grid-cols-4 xl:w-max">
                        {{-- Stat 1 - Años/Experiencia --}}
                        <div class="flex flex-col items-center justify-center gap-6">
                            <span class="icon-[tabler--award] text-primary size-10"></span>
                            <div class="space-y-2 text-center">
                                <span class="text-primary text-3xl font-semibold" id="count1"></span>
                                <p class="text-base-content/80">Años de Experiencia</p>
                            </div>
                        </div>
                        
                        {{-- Stat 2 - Productos --}}
                        <div class="flex flex-col items-center justify-center gap-6">
                            <span class="icon-[tabler--package] text-primary size-10"></span>
                            <div class="space-y-2 text-center">
                                <span class="text-primary text-3xl font-semibold" id="count2"></span>
                                <p class="text-base-content/80">Productos</p>
                            </div>
                        </div>
                        
                        {{-- Stat 3 - Clientes --}}
                        <div class="flex flex-col items-center justify-center gap-6">
                            <span class="icon-[tabler--users] text-primary size-10"></span>
                            <div class="space-y-2 text-center">
                                <span class="text-primary text-3xl font-semibold" id="count3"></span>
                                <p class="text-base-content/80">Clientes Felices</p>
                            </div>
                        </div>
                        
                        {{-- Stat 4 - Rating/Servicios --}}
                        <div class="flex flex-col items-center justify-center gap-6">
                            <span class="icon-[tabler--star] text-primary size-10"></span>
                            <div class="space-y-2 text-center">
                                <span class="text-primary text-3xl font-semibold" id="count4"></span>
                                <p class="text-base-content/80">Calificación</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Counter values for JS --}}
<script>
    // These values will be animated by landing-page-free.js
    // count1 = years, count2 = products, count3 = customers, count4 = rating
    window.COUNTER_VALUES = {
        count1: { end: 5, suffix: '+' },
        count2: { end: {{ $products->count() }}, suffix: '+' },
        count3: { end: 500, suffix: '+' },
        count4: { end: 4.9, suffix: '★', decimals: 1 }
    };
</script>
