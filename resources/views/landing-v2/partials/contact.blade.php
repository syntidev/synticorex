{{-- Contact Section Partial - FlyonUI --}}
<section id="contact">
    <div class="bg-base-100 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            {{-- Heading Section --}}
            <div class="mb-12 text-center sm:mb-16 lg:mb-24">
                <h2 class="text-base-content mb-4 text-2xl font-semibold md:text-3xl lg:text-4xl">Contáctanos</h2>
                <p class="text-base-content/80 text-xl">
                    Estamos aquí para ayudarte. Escríbenos por WhatsApp o déjanos un mensaje y te responderemos lo antes posible.
                </p>
            </div>
            
            <div class="card shadow-md">
                <div class="card-body grid gap-10 lg:grid-cols-7">
                    {{-- Form Section --}}
                    <div class="lg:col-span-4">
                        {{-- WhatsApp CTA - Destacado --}}
                        @if($tenant->whatsapp_sales)
                        <div class="bg-success/10 border-success/30 mb-8 rounded-xl border p-6 text-center">
                            <h3 class="text-success mb-2 text-xl font-bold">
                                <span class="icon-[tabler--brand-whatsapp] size-6 me-2"></span>
                                ¡Respuesta Inmediata!
                            </h3>
                            <p class="text-base-content/80 mb-4">La forma más rápida de contactarnos es por WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Necesito información') }}"
                               target="_blank"
                               class="btn btn-success btn-lg">
                                <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                                Escribir por WhatsApp
                            </a>
                        </div>
                        @endif
                        
                        <h2 class="text-base-content mb-6 text-2xl font-semibold">Envíanos un mensaje</h2>
                        
                        {{-- Contact Form --}}
                        <form class="space-y-6" action="#" method="POST" onsubmit="handleContactForm(event);">
                            @csrf
                            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                            
                            <div class="flex gap-6 max-md:flex-col">
                                <div class="w-full">
                                    <label class="label-text" for="contact_name">Tu Nombre</label>
                                    <div class="input input-lg">
                                        <input type="text" class="grow" placeholder="Ingresa tu nombre..." id="contact_name" name="name" required />
                                        <span class="icon-[tabler--user] text-base-content/80 size-5.5 my-auto ms-3 shrink-0"></span>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label class="label-text" for="contact_phone">Teléfono</label>
                                    <div class="input input-lg">
                                        <input type="tel" class="grow" placeholder="+58 412 1234567" id="contact_phone" name="phone" />
                                        <span class="icon-[tabler--phone] text-base-content/80 size-5.5 my-auto ms-3 shrink-0"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-6 max-md:flex-col">
                                <div class="w-full">
                                    <label class="label-text" for="contact_email">Email</label>
                                    <div class="input input-lg">
                                        <input type="email" class="grow" placeholder="tu@email.com" id="contact_email" name="email" required />
                                        <span class="icon-[tabler--mail] text-base-content/80 size-5.5 my-auto ms-3 shrink-0"></span>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label class="label-text" for="contact_subject">Asunto</label>
                                    <div class="input input-lg">
                                        <input type="text" class="grow" placeholder="¿En qué podemos ayudarte?" id="contact_subject" name="subject" />
                                        <span class="icon-[tabler--tag] text-base-content/80 size-5.5 my-auto ms-3 shrink-0"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="label-text" for="contact_message">Mensaje</label>
                                <div class="textarea">
                                    <textarea class="grow resize-none" aria-label="Mensaje" placeholder="Escribe tu mensaje aquí..." id="contact_message" name="message" rows="4" required></textarea>
                                    <span class="icon-[tabler--message-circle-2] text-base-content/80 mx-4 mt-2 size-6 shrink-0"></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-gradient w-full btn-lg">
                                <span class="icon-[tabler--send] size-5"></span>
                                Enviar Mensaje
                            </button>
                        </form>
                    </div>
                    
                    {{-- Contact Information --}}
                    <div class="space-y-6 lg:col-span-3">
                        {{-- Phone/WhatsApp --}}
                        <div class="border-base-content/20 rounded-box border p-6 text-center">
                            <h3 class="text-base-content mb-4 text-xl font-semibold">
                                <span class="icon-[tabler--phone] size-5 me-2"></span>
                                Teléfonos
                            </h3>
                            @if($tenant->phone)
                            <p class="text-base-content/80 mb-2">
                                <a href="tel:{{ $tenant->phone }}" class="hover:text-primary">{{ $tenant->phone }}</a>
                            </p>
                            @endif
                            @if($tenant->whatsapp_sales)
                            <p class="text-success font-medium">
                                <span class="icon-[tabler--brand-whatsapp] size-4 me-1"></span>
                                {{ $tenant->whatsapp_sales }}
                            </p>
                            @endif
                        </div>
                        
                        {{-- Location --}}
                        @if($tenant->address || $tenant->city)
                        <div class="border-base-content/20 rounded-box border p-6 text-center">
                            <h3 class="text-base-content mb-4 text-xl font-semibold">
                                <span class="icon-[tabler--map-pin] size-5 me-2"></span>
                                Ubicación
                            </h3>
                            @if($tenant->address)
                            <p class="text-base-content/80">{{ $tenant->address }}</p>
                            @endif
                            @if($tenant->city)
                            <p class="text-base-content/80">{{ $tenant->city }}</p>
                            @endif
                            @if($tenant->country)
                            <p class="text-base-content/80">{{ $tenant->country }}</p>
                            @endif
                        </div>
                        @endif
                        
                        {{-- Business Hours --}}
                        @if($tenant->business_hours)
                        <div class="border-base-content/20 rounded-box border p-6 text-center">
                            <h3 class="text-base-content mb-4 text-xl font-semibold">
                                <span class="icon-[tabler--clock] size-5 me-2"></span>
                                Horario de Atención
                            </h3>
                            @foreach(json_decode($tenant->business_hours, true) ?? [] as $day => $hours)
                            <p class="text-base-content/80">
                                <span class="font-medium">{{ ucfirst($day) }}:</span> 
@if($hours)
    {{ $hours['open'] ?? '' }} - {{ $hours['close'] ?? '' }}
@else
    Cerrado
@endif                            </p>
                            @endforeach
                        </div>
                        @endif
                        
                        {{-- Status --}}
                        <p class="text-base-content text-center font-medium">
                            Estado actual:
                            @if($tenant->is_open)
                                <span class="text-success">🟢 Abierto</span>
                            @else
                                <span class="text-error">🔴 Cerrado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Handle contact form - redirect to WhatsApp as fallback
function handleContactForm(event) {
    event.preventDefault();
    
    const name = document.getElementById('contact_name').value;
    const phone = document.getElementById('contact_phone').value;
    const message = document.getElementById('contact_message').value;
    
    const whatsapp = window.SYNTIWEB.whatsapp;
    if (whatsapp) {
        const text = `Hola! Soy ${name}${phone ? ' (' + phone + ')' : ''}. ${message}`;
        window.open(`https://wa.me/${whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(text)}`, '_blank');
    }
    
    return false;
}
</script>
