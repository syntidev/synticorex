{{-- Products Section Partial - FlyonUI --}}
<section id="products">
    <div class="bg-base-100 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-12 space-y-4 text-center sm:mb-16 lg:mb-24">
                <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Nuestros Productos</h2>
                <p class="text-base-content/80 text-xl">
                    Descubre nuestra selección de productos de calidad. Haz clic en cualquier producto para solicitar información por WhatsApp.
                </p>
                
                {{-- Currency Toggle --}}
                <div class="flex items-center justify-center gap-4">
                    <button onclick="toggleCurrency()" class="btn btn-outline btn-primary btn-sm">
                        <span class="icon-[tabler--refresh] size-4"></span>
                        <span class="currency-toggle-text">Ver en Bs.</span>
                    </button>
                    <span class="badge badge-soft">
                        Tasa BCV: Bs. {{ number_format($dollarRate ?? 36.50, 2, ',', '.') }}
                    </span>
                </div>
            </div>
            
            {{-- Products Grid --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($products as $product)
                <div class="card card-border shadow-none hover:shadow-lg transition-shadow duration-300">
                    {{-- Product Image --}}
                    <figure class="relative">
                        @if($product->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/products/' . $product->image_filename) }}" 
                                alt="{{ $product->name }}"
                                class="h-48 w-full object-cover"
                                loading="lazy"
                            />
                        @else
                            <div class="bg-base-200 flex h-48 w-full items-center justify-center">
                                <span class="icon-[tabler--photo] size-16 text-base-content/20"></span>
                            </div>
                        @endif
                        
                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            @if($product->is_featured)
                                <span class="badge badge-warning">
                                    <span class="icon-[tabler--star-filled] size-3 me-1"></span>
                                    Destacado
                                </span>
                            @endif
                            
                            @if($product->badge)
                                @switch($product->badge)
                                    @case('hot')
                                        <span class="badge badge-error">🔥 HOT</span>
                                        @break
                                    @case('new')
                                        <span class="badge badge-success">✨ NUEVO</span>
                                        @break
                                    @case('promo')
                                        <span class="badge badge-info">💰 PROMO</span>
                                        @break
                                @endswitch
                            @endif
                        </div>
                    </figure>
                    
                    {{-- Product Info --}}
                    <div class="card-body gap-3">
                        <h5 class="card-title text-xl">{{ $product->name }}</h5>
                        
                        @if($product->description)
                            <p class="text-base-content/80 line-clamp-2">{{ $product->description }}</p>
                        @endif
                        
                        {{-- Price --}}
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-primary" data-price-usd="{{ $product->price_usd }}">
                                REF {{ number_format($product->price_usd, 2) }}
                            </span>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="card-actions mt-2">
                            @if($tenant->whatsapp_sales)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Me interesa: ' . $product->name . ' (REF ' . number_format($product->price_usd, 2) . ')') }}"
                                   target="_blank"
                                   class="btn btn-success btn-block">
                                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                                    Pedir por WhatsApp
                                </a>
                            @else
                                <button class="btn btn-primary btn-block" disabled>
                                    Consultar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            {{-- Plan Limit Notice --}}
            @if($tenant->plan && $products->count() >= $tenant->plan->products_limit)
            <div class="mt-8 text-center">
                <p class="text-base-content/60 text-sm">
                    Mostrando {{ $products->count() }} de {{ $tenant->plan->products_limit }} productos (Plan {{ $tenant->plan->name }})
                </p>
            </div>
            @endif
            
            {{-- CTA Bottom --}}
            @if($tenant->whatsapp_sales)
            <div class="mt-12 text-center">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Quiero ver el catálogo completo') }}"
                   target="_blank"
                   class="btn btn-primary btn-lg btn-gradient">
                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                    Ver Catálogo Completo por WhatsApp
                    <span class="icon-[tabler--arrow-right] size-5"></span>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
