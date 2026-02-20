{{-- CTA Section Partial - FlyonUI --}}
<section id="cta-section">
    <div class="bg-base-200 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="from-primary/30 to-success/30 p-0.25 overflow-hidden rounded-3xl bg-gradient-to-r">
                <div class="bg-base-100 rounded-3xl p-0.5">
                    <div class="from-primary/6 to-success/6 relative rounded-3xl bg-gradient-to-r to-50% p-8 lg:p-16">
                        <div class="flex justify-between gap-8 max-md:flex-col max-sm:items-center max-sm:text-center md:items-center">
                            <div class="max-w-xs space-y-4 lg:max-w-lg">
                                <h2 class="text-base-content text-xl font-bold md:text-3xl">
                                    ¿Listo para hacer tu pedido?
                                </h2>
                                <p class="text-base-content/80">
                                    Contáctanos directamente por WhatsApp y te atenderemos al instante. 
                                    Respuesta rápida, atención personalizada y los mejores productos te esperan.
                                </p>
                                
                                @if($tenant->whatsapp_sales)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Quiero hacer un pedido') }}"
                                   target="_blank"
                                   class="btn btn-gradient btn-success">
                                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                                    Escribir ahora
                                    <span class="icon-[tabler--arrow-right]"></span>
                                </a>
                                @endif
                                
                                {{-- Contact Info Pills --}}
                                <div class="flex flex-wrap gap-2 pt-4">
                                    @if($tenant->phone)
                                    <a href="tel:{{ $tenant->phone }}" class="badge badge-soft badge-lg">
                                        <span class="icon-[tabler--phone] size-4 me-1"></span>
                                        {{ $tenant->phone }}
                                    </a>
                                    @endif
                                    
                                    @if($tenant->email)
                                    <a href="mailto:{{ $tenant->email }}" class="badge badge-soft badge-lg">
                                        <span class="icon-[tabler--mail] size-4 me-1"></span>
                                        {{ $tenant->email }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Decorative Image/Icon --}}
                            <div class="max-md:hidden">
                                <div class="bg-primary/10 rounded-full p-8">
                                    <span class="icon-[tabler--message-circle-heart] size-32 text-primary"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Divider --}}
<div class="via-primary/20 mx-auto h-px w-3/5 bg-gradient-to-r from-transparent to-transparent"></div>
