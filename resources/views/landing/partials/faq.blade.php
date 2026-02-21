{{-- FAQ Section Partial --}}
<section id="faq" class="py-16 px-4 bg-base-200">
    <div class="container mx-auto max-w-3xl">
        <h2 class="text-3xl font-bold text-center mb-12 text-primary">
            Preguntas Frecuentes
        </h2>
        
        <div class="space-y-4">
            @foreach($customization->faq_items as $index => $faq)
                <details class="bg-white rounded-xl shadow-md overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
                        <span>{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        {{ $faq['answer'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
