@props([
    'modalId' => uniqid('hs-modal-'),
    'title' => 'Título de la ventana',
    'closeLabel' => 'Cerrar'
])

<div id="{{ $modalId }}"
     class="hs-overlay hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm"
     data-hs-overlay-keyboard="true">
    <div class="flex min-h-full items-center justify-center px-4">
        <div class="hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 transition duration-300 w-full max-w-lg">
            <div class="rounded-3xl border border-base-content/10 bg-base-100 shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-base-content/10 px-6 py-4">
                    <div>
                        <p class="text-lg font-black text-base-content">{{ $title }}</p>
                        <p class="text-sm text-base-content/60">{{ $attributes->get('subtitle') }}</p>
                    </div>
                    <button type="button" class="inline-flex size-9 items-center justify-center rounded-full border border-base-content/10 text-base-content/70 hover:bg-base-200" data-hs-overlay="#{{ $modalId }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 text-base-content/80">
                    {{ $slot }}
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-base-content/10 px-6 py-4">
                    @isset($footer)
                        {{ $footer }}
                    @else
                        <button type="button" class="rounded-2xl border border-base-content/10 px-4 py-2 text-sm font-semibold text-base-content" data-hs-overlay="#{{ $modalId }}">
                            {{ $closeLabel }}
                        </button>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
