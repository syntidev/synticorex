@props([
    'items' => [
        ['title' => 'Título del acordeón', 'content' => 'Contenido descriptivo de ejemplo.'],
    ],
    'alwaysOpen' => false,
    'groupId' => uniqid('hs-accordion-')
])

@php
    $groupAttributes = $alwaysOpen ? ['data-hs-accordion-always-open' => ''] : [];
@endphp

<div {{ $attributes->merge(['class' => 'hs-accordion-group border border-base-content/10 rounded-2xl divide-y divide-base-content/10 bg-base-100/90']) }} @foreach($groupAttributes as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>
    @foreach($items as $index => $item)
        @php
            $itemId = $groupId.'-item-'.$index;
            $panelId = $groupId.'-panel-'.$index;
        @endphp
        <div class="hs-accordion {{ $loop->first ? 'active' : '' }}" id="{{ $itemId }}">
            <button type="button"
                    class="hs-accordion-toggle group inline-flex w-full items-center justify-between gap-3 px-5 py-4 text-left font-semibold text-base-content transition"
                    aria-controls="{{ $panelId }}">
                <span>{{ $item['title'] }}</span>
                <svg class="size-4 text-base-content/70 transition-transform group-[.active]:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div id="{{ $panelId }}" class="hs-accordion-content hidden px-5 pb-5 text-sm text-base-content/70">
                {{ $item['content'] ?? '' }}
            </div>
        </div>
    @endforeach
</div>
