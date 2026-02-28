@if($isUnlocked())
    {{ $slot }}
@else
    <div class="alert alert-info flex items-center gap-3">
        <span class="iconify tabler--lock size-5 shrink-0" aria-hidden="true"></span>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ $upgradeMessage }}</p>
        </div>
        <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
           class="btn btn-primary btn-sm shrink-0">Ver Planes</a>
    </div>
@endif
