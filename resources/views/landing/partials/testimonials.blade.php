{{-- About Section Partial --}}
<section id="about" class="py-16 px-4 bg-base-100">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-3xl font-bold text-center mb-8 text-primary">
            Sobre Nosotros
        </h2>
        
        <div class="prose prose-lg mx-auto text-center">
            <p class="text-gray-700 leading-relaxed">
                {{ $tenant->description }}
            </p>
        </div>
        
        {{-- Business Info --}}
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            @if($tenant->address || $tenant->city)
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <div class="text-3xl mb-3">📍</div>
                    <h3 class="font-semibold text-gray-900 mb-1">Ubicación</h3>
                    <p class="text-gray-600 text-sm">
                        {{ $tenant->address }}
                        @if($tenant->city), {{ $tenant->city }}@endif
                    </p>
                </div>
            @endif
            
            @if($tenant->phone)
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <div class="text-3xl mb-3">📞</div>
                    <h3 class="font-semibold text-gray-900 mb-1">Teléfono</h3>
                    <a href="tel:{{ $tenant->phone }}" class="text-gray-600 text-sm hover:underline">
                        {{ $tenant->phone }}
                    </a>
                </div>
            @endif
            
            @if($tenant->business_hours)
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <div class="text-3xl mb-3">🕐</div>
                    <h3 class="font-semibold text-gray-900 mb-1">Horario</h3>
                    <div class="text-gray-600 text-sm">
                        @php
                            $hours_data = is_string($tenant->business_hours) 
                                ? json_decode($tenant->business_hours, true) 
                                : $tenant->business_hours;
                        @endphp
                        @foreach(($hours_data ?? []) as $day => $hours)
                            <div>{{ ucfirst($day) }}: 
                                @if($hours)
                                    {{ $hours['open'] ?? '' }} - {{ $hours['close'] ?? 'Cerrado' }}
                                @else
                                    Cerrado
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
