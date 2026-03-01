{{-- CTA Section — always plan 1+ --}}
@php
    $ctaTitle   = $customization->cta_title      ?? ('¡Contacta a ' . $tenant->business_name . '!');
    $ctaSub     = $customization->cta_subtitle   ?? ($tenant->slogan ?? 'Estamos listos para atenderte. ¿Hablamos?');
    $ctaBtnText = $customization->cta_button_text ?? null;
    $ctaBtnLink = $customization->cta_button_link ?? null;

    // Fallback: WhatsApp del tenant si no hay botón configurado
    if (!$ctaBtnText && $tenant->whatsapp_sales) {
        $ctaBtnText = 'Escríbenos por WhatsApp';
        $ctaBtnLink = 'https://wa.me/' . preg_replace('/\D/', '', $tenant->whatsapp_sales);
    }
@endphp

<section id="cta" class="py-8 sm:py-16 lg:py-24 relative overflow-hidden bg-primary">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none"
         style="background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <h2 class="text-2xl font-semibold md:text-3xl lg:text-4xl text-white mb-4">
            {{ $ctaTitle }}
        </h2>

        @if($ctaSub)
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                {{ $ctaSub }}
            </p>
        @endif

        @if($ctaBtnText && $ctaBtnLink)
            <a href="{{ $ctaBtnLink }}" target="_blank" rel="noopener noreferrer"
               class="btn btn-lg bg-white text-primary hover:bg-white/90 gap-2 font-semibold shadow-lg">
                {{ $ctaBtnText }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        @endif

    </div>
</section>
