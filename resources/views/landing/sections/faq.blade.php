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

<section id="faq" class="py-8 sm:py-16 lg:py-24 bg-background relative overflow-hidden">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 blur-[100px] rounded-full pointer-events-none" aria-hidden="true"></div>

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-16">
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
                Preguntas <span class="text-primary italic">Frecuentes</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="hs-accordion-group space-y-4">
            @foreach($faqItems as $index => $faq)
            <div class="hs-accordion bg-background border border-border rounded-[2rem] overflow-hidden shadow-sm hover:border-primary/30 transition-all duration-300"
                 id="faq-item-{{ $index }}">

                <button class="hs-accordion-toggle inline-flex items-center justify-between text-start text-base font-bold px-6 py-5 w-full text-foreground hover:text-primary transition-colors"
                        aria-controls="faq-collapse-{{ $index }}"
                        aria-expanded="false">
                    <span class="pr-4">{{ $faq['question'] }}</span>
                    <span class="shrink-0">
                        <iconify-icon icon="tabler:plus"  class="hs-accordion-active:hidden text-primary" width="20" height="20"></iconify-icon>
                        <iconify-icon icon="tabler:minus" class="hidden hs-accordion-active:inline-flex text-primary" width="20" height="20"></iconify-icon>
                    </span>
                </button>

                <div id="faq-collapse-{{ $index }}"
                     class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                     aria-labelledby="faq-item-{{ $index }}" role="region">
                    <div class="px-6 pb-5 pt-1 text-foreground/65 text-sm leading-relaxed border-t border-border">
                        {{ $faq['answer'] }}
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>