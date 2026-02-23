<section id="faq" class="py-24 bg-surface-50 relative overflow-hidden">
    {{-- Detalle Geométrico: Círculo de luz tenue en la esquina --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="container mx-auto max-w-4xl px-6 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">
                Preguntas <span class="text-primary italic">Frecuentes</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="accordion accordion-border accordion-flush space-y-4">
            @if(isset($customization->faq_items) && count($customization->faq_items) > 0)
                @foreach($customization->faq_items as $index => $faq)
                    <div class="accordion-item bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-glow-primary transition-all duration-300" id="faq-item-{{ $index }}">
                        <button class="accordion-toggle inline-flex items-center justify-between text-start text-lg font-bold px-8 py-6 w-full text-slate-800 hover:text-primary transition-colors" 
                                aria-controls="faq-collapse-{{ $index }}" 
                                aria-expanded="false">
                            {{ $faq['question'] }}
                            <iconify-icon icon="tabler:plus" class="accordion-item-active:hidden text-primary transition-all" width="24" height="24"></iconify-icon>
                            <iconify-icon icon="tabler:minus" class="hidden accordion-item-active:inline-flex text-primary transition-all" width="24" height="24"></iconify-icon>
                        </button>
                        <div id="faq-collapse-{{ $index }}" class="accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="faq-item-{{ $index }}" role="region">
                            <div class="px-8 pb-6 text-slate-500 leading-relaxed border-t border-slate-50 pt-4">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-12 border-2 border-dashed border-slate-200 rounded-[2rem]">
                    <p class="text-slate-400 italic">Configura tus preguntas frecuentes en el panel.</p>
                </div>
            @endif
        </div>
    </div>
</section>