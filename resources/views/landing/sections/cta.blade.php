{{-- CTA Section — always plan 1+ --}}
@php
    $ctaTitle   = $customization->cta_title      ?? ('¡Contacta a ' . $tenant->business_name . '!');
    $ctaSub     = $customization->cta_subtitle   ?? ($tenant->slogan ?? 'Estamos listos para atenderte. ¿Hablamos?');
    $ctaBtnText = !empty($customization->cta_button_text) ? $customization->cta_button_text : null;
    $ctaBtnLink = !empty($customization->cta_button_link) ? $customization->cta_button_link : null;

    if (!$ctaBtnText && $tenant->getActiveWhatsapp()) {
        $waNum      = preg_replace('/\D/', '', $tenant->getActiveWhatsapp());
        $ctaBtnText = 'Escríbenos por WhatsApp';
        $ctaBtnLink = 'https://wa.me/' . $waNum;
    }
@endphp

<section id="cta" class="py-8 sm:py-16 lg:py-24 relative overflow-hidden bg-primary">

    {{-- Blobs de profundidad --}}
    <div class="absolute -top-20 -left-20 size-72 rounded-full opacity-[0.15] blur-3xl pointer-events-none"
         style="background:rgba(255,255,255,0.3)"></div>

    {{-- Forma decorativa — esquina inferior izquierda --}}
    <div class="absolute -bottom-24 -left-24 pointer-events-none select-none" aria-hidden="true">
        <svg width="360" height="360" viewBox="0 0 360 360" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="30" y="30" width="300" height="300" rx="48"
                  transform="rotate(12 180 180)"
                  fill="rgba(255,255,255,0.07)" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
            <rect x="80" y="80" width="200" height="200" rx="32"
                  transform="rotate(22 180 180)"
                  fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.09)" stroke-width="1"/>
        </svg>
    </div>

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <h2 class="text-2xl font-semibold md:text-3xl lg:text-4xl text-white mb-4"
            style="text-shadow: 0 4px 24px rgba(255,255,255,0.15), 0 1px 4px rgba(255,255,255,0.08);">
            {{ $ctaTitle }}
        </h2>

        @if($ctaSub)
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                {{ $ctaSub }}
            </p>
        @endif

        @if($ctaBtnText && $ctaBtnLink)
            <a href="{{ $ctaBtnLink }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center py-3 px-6 rounded-lg font-semibold transition-all duration-200 text-lg bg-background text-primary hover:bg-background/90 gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                {{ $ctaBtnText }}
                <span class="iconify tabler--brand-whatsapp size-5" aria-hidden="true"></span>
            </a>
        @endif

    </div>
</section>
