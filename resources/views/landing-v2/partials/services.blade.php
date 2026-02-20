{{-- Services Section Partial - FlyonUI --}}
<section id="services">
    <div class="bg-base-200 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-12 space-y-4 text-center sm:mb-16 lg:mb-24">
                <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Nuestros Servicios</h2>
                <p class="text-base-content/80 text-xl">
                    Ofrecemos una variedad de servicios pensados para satisfacer tus necesidades. Cada servicio está diseñado con atención al detalle.
                </p>
            </div>
            
            {{-- Services Grid --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $service)
                <div class="card card-border shadow-none hover:border-primary transition-border duration-300">
                    {{-- Service Image or Icon --}}
                    <figure class="relative">
                        @if($service->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                alt="{{ $service->name }}"
                                class="h-48 w-full object-cover"
                                loading="lazy"
                            />
                            @if($service->overlay_text)
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="text-white text-xl font-bold text-center px-4">{{ $service->overlay_text }}</span>
                            </div>
                            @endif
                        @else
                            <div class="bg-primary/10 flex h-48 w-full items-center justify-center">
                                @if($service->icon_name)
                                    @switch($service->icon_name)
                                        @case('scissors')
                                            <span class="icon-[tabler--scissors] size-20 text-primary"></span>
                                            @break
                                        @case('wrench')
                                            <span class="icon-[tabler--tool] size-20 text-primary"></span>
                                            @break
                                        @case('burger')
                                            <span class="icon-[tabler--burger] size-20 text-primary"></span>
                                            @break
                                        @case('pizza')
                                            <span class="icon-[tabler--pizza] size-20 text-primary"></span>
                                            @break
                                        @case('car')
                                            <span class="icon-[tabler--car] size-20 text-primary"></span>
                                            @break
                                        @case('home')
                                            <span class="icon-[tabler--home] size-20 text-primary"></span>
                                            @break
                                        @case('heart')
                                            <span class="icon-[tabler--heart] size-20 text-primary"></span>
                                            @break
                                        @case('star')
                                            <span class="icon-[tabler--star] size-20 text-primary"></span>
                                            @break
                                        @case('truck')
                                            <span class="icon-[tabler--truck-delivery] size-20 text-primary"></span>
                                            @break
                                        @case('clock')
                                            <span class="icon-[tabler--clock] size-20 text-primary"></span>
                                            @break
                                        @default
                                            <span class="icon-[tabler--briefcase] size-20 text-primary"></span>
                                    @endswitch
                                @else
                                    <span class="icon-[tabler--briefcase] size-20 text-primary"></span>
                                @endif
                            </div>
                        @endif
                    </figure>
                    
                    {{-- Service Info --}}
                    <div class="card-body gap-3">
                        <h5 class="card-title text-xl">{{ $service->name }}</h5>
                        
                        @if($service->description)
                            <p class="text-base-content/80 mb-5">{{ $service->description }}</p>
                        @endif
                        
                        {{-- CTA Button --}}
                        <div class="card-actions">
                            @php
                                $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                    : '#contact');
                                $ctaText = $service->cta_text ?? 'Más información';
                            @endphp
                            
                            <a href="{{ $ctaLink }}" 
                               target="{{ $service->cta_link ? '_blank' : '_self' }}"
                               class="btn btn-primary btn-gradient">
                                {{ $ctaText }}
                                <span class="icon-[tabler--arrow-right] size-5 rtl:rotate-180"></span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
