@props([
    'label' => 'Acciones',
    'items' => [
        ['label' => 'Editar', 'href' => '#'],
        ['label' => 'Duplicar', 'href' => '#'],
        ['label' => 'Eliminar', 'href' => '#']
    ],
    'placement' => 'bottom-end',
    'dropdownId' => uniqid('hs-dropdown-')
])

<div class="hs-dropdown relative inline-flex" data-hs-dropdown>
    <button id="{{ $dropdownId }}" type="button"
            class="hs-dropdown-toggle inline-flex items-center gap-2 rounded-2xl border border-base-content/10 bg-base-100 px-4 py-2 text-sm font-semibold text-base-content shadow-sm transition hover:bg-base-200"
            aria-haspopup="menu"
            aria-expanded="false"
            data-hs-dropdown-placement="{{ $placement }}">
        {{ $label }}
        <svg class="size-4 text-base-content/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>

    <div class="hs-dropdown-menu z-50 mt-2 hidden min-w-[12rem] rounded-2xl border border-base-content/10 bg-base-100 p-1.5 text-sm text-base-content shadow-xl transition-[opacity,margin] hs-dropdown-open:opacity-100" aria-labelledby="{{ $dropdownId }}">
        @foreach($items as $item)
            <a href="{{ $item['href'] ?? '#' }}"
               class="flex w-full items-center rounded-xl px-3 py-2 font-medium text-base-content/80 transition hover:bg-base-200"
               data-hs-dropdown-item>
                {{ $item['label'] ?? '' }}
            </a>
        @endforeach
    </div>
</div>
