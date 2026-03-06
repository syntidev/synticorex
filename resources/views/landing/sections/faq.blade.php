{{--
    FAQ Section Partial — SYNTIweb
    ─────────────────────────────────────────────
    Plan requerido : 3 (VISIÓN)
    Guard de plan  : en base.blade.php (@if plan_id >= 3)
    Variable       : $tenant->settings['business_info']['faq']
                     Array de { question, answer } — máx 5 items
    Fallbacks      : 3 FAQs genéricas con datos del tenant
--}}
@php
    $faqItems = collect(data_get($tenant->settings, 'business_info.faq', []))
        ->filter(fn($f) => !empty($f['question']) && !empty($f['answer']))
        ->take(5)
        ->values();

    // Si no hay items configurados → fallbacks dinámicos con datos del tenant
    if ($faqItems->isEmpty()) {
        $schedule    = data_get($tenant->settings, 'business_info.schedule_display', 'Lun–Sáb 9:00–18:00');
        $delivery    = (bool) data_get($tenant->settings, 'business_info.delivery_available', false);
        $payMethods  = collect(data_get($tenant->settings, 'payment_methods.global', []));
        $payText     = $payMethods->isNotEmpty()
            ? implode(', ', $payMethods->map(fn($m) => match($m) {
                'pagoMovil'  => 'Pago Móvil',
                'biopago'    => 'Biopago',
                'puntoventa' => 'Punto de Venta',
                'zinli'      => 'Zinli',
                'zelle'      => 'Zelle',
                'paypal'     => 'PayPal',
                default      => ucfirst($m),
              })->toArray())
            : 'Pago Móvil y efectivo';

        $faqItems = collect([
            [
                'question' => '¿Cuál es el horario de atención?',
                'answer'   => "Atendemos {$schedule}. Fuera de ese horario puedes escribirnos por WhatsApp y te responderemos a la brevedad.",
            ],
            [
                'question' => '¿Qué medios de pago aceptan?',
                'answer'   => "Aceptamos: {$payText}. Si tienes dudas contáctanos directamente.",
            ],
            [
                'question' => '¿Realizan entregas a domicilio?',
                'answer'   => $delivery
                    ? 'Sí, contamos con servicio de delivery. Consulta disponibilidad y tarifas por WhatsApp.'
                    : 'Por el momento no contamos con servicio de delivery. Puedes retirar tu pedido en tienda.',
            ],
        ]);
    }
@endphp

<section id="faq" class="py-8 sm:py-16 lg:py-24 bg-surface relative overflow-hidden">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 blur-[100px] rounded-full pointer-events-none" aria-hidden="true"></div>

    <div class="mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-12 space-y-3">
            <p class="text-primary text-sm font-medium uppercase tracking-wide">
                {{ $customization->getContentBlock('faq', 'eyebrow') ?: 'Resolvemos tus dudas' }}
            </p>
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
                {!! $customization->getSectionTitle('faq', 'Preguntas <span class="text-primary italic">Frecuentes</span>') !!}
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        {{-- Preline accordion con tokens correctos --}}
        <div class="hs-accordion-group flex flex-col gap-y-2 max-w-3xl mx-auto">
            @foreach($faqItems as $index => $faq)
            <div class="hs-accordion hs-accordion-active:border-layer-line bg-layer border border-transparent rounded-xl"
                 id="faq-heading-{{ $index }}">

                <button class="hs-accordion-toggle hs-accordion-active:text-primary-active inline-flex justify-between items-center gap-x-3 w-full font-semibold text-start text-foreground py-4 px-5 hover:text-muted-foreground-1 focus:outline-hidden focus:text-muted-foreground-1 disabled:opacity-50 disabled:pointer-events-none"
                        aria-expanded="false"
                        aria-controls="faq-collapse-{{ $index }}">
                    <span>{{ $faq['question'] }}</span>
                    {{-- Plus (cerrado) --}}
                    <svg class="hs-accordion-active:hidden block shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{-- Minus (abierto) --}}
                    <svg class="hs-accordion-active:block hidden shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                </button>

                <div id="faq-collapse-{{ $index }}"
                     class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                     role="region"
                     aria-labelledby="faq-heading-{{ $index }}">
                    <div class="pb-4 px-5">
                        <p class="text-foreground/70 text-sm leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>