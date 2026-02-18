{{-- CTA Special Section Partial --}}
<section id="cta" class="py-20 px-4 relative overflow-hidden" style="background-color: var(--color-primary);">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="container mx-auto max-w-4xl relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            {{ $customization->cta_title }}
        </h2>
        
        @if($customization->cta_subtitle)
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                {{ $customization->cta_subtitle }}
            </p>
        @endif
        
        @if($customization->cta_button_text && $customization->cta_button_link)
            <a 
                href="{{ $customization->cta_button_link }}"
                target="_blank"
                class="inline-flex items-center px-8 py-4 rounded-lg text-lg font-semibold transition-transform hover:scale-105"
                style="background-color: var(--color-secondary); color: var(--color-primary);"
            >
                {{ $customization->cta_button_text }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        @endif
    </div>
</section>
