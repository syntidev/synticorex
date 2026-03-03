@if($isUnlocked())
    {{ $slot }}
@else
    <div class="flex p-4 rounded-lg border gap-3 bg-blue-50 border-blue-200 text-blue-800 items-center">
        <span class="iconify tabler--lock size-5 shrink-0" aria-hidden="true"></span>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ $upgradeMessage }}</p>
        </div>
        <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
           class="text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Ver Planes</a>
    </div>
@endif
