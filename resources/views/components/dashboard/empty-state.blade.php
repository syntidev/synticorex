@props([
    'icon'        => 'inbox',
    'title'       => 'Sin contenido',
    'message'     => '',
    'action'      => null,
    'actionLabel' => 'Agregar',
])

<div class="flex flex-col items-center justify-center py-14 text-center px-4">
    <div class="size-16 rounded-2xl bg-muted flex items-center justify-center mb-4">
        <span class="iconify tabler--{{ $icon }} size-8 text-muted-foreground-2" aria-hidden="true"></span>
    </div>
    <h3 class="font-semibold text-base text-foreground mb-1">{{ $title }}</h3>
    @if($message)
        <p class="text-sm text-muted-foreground-1 max-w-xs">{{ $message }}</p>
    @endif
    @if($action)
        <a href="{{ $action }}"
           class="mt-4 inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-sm font-medium bg-primary text-primary-foreground hover:bg-primary-hover transition-colors">
            <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
            {{ $actionLabel }}
        </a>
    @endif
</div>
