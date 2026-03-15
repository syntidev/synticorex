<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Último check: {{ $timestamp }}
            </p>
            <div class="flex gap-2">
                <a href="{{ url()->current() }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 cursor-pointer transition">
                    <span class="iconify tabler--refresh size-4"></span>
                    Refrescar
                </a>
                <form method="POST" action="{{ url('admin/health/refresh') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 cursor-pointer transition">
                        <span class="iconify tabler--activity size-4"></span>
                        Ejecutar checks ahora
                    </button>
                </form>
            </div>
        </div>

        {{-- Grid of checks --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($checks as $check)
                @php
                    $colors = match ($check['status']) {
                        'ok'      => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/30', 'border' => 'border-emerald-200 dark:border-emerald-800', 'icon' => 'text-emerald-600 dark:text-emerald-400', 'iconName' => 'tabler--circle-check'],
                        'warning' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-800', 'icon' => 'text-amber-600 dark:text-amber-400', 'iconName' => 'tabler--alert-triangle'],
                        'error'   => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-800', 'icon' => 'text-red-600 dark:text-red-400', 'iconName' => 'tabler--circle-x'],
                        default   => ['bg' => 'bg-gray-50 dark:bg-gray-900', 'border' => 'border-gray-200 dark:border-gray-700', 'icon' => 'text-gray-400', 'iconName' => 'tabler--minus'],
                    };
                @endphp
                <div class="rounded-xl border {{ $colors['border'] }} {{ $colors['bg'] }} p-4">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 mt-0.5">
                            <span class="iconify {{ $colors['iconName'] }} {{ $colors['icon'] }} size-6"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $check['label'] }}
                                </h3>
                                @if ($check['latency_ms'] !== null)
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200/70 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $check['latency_ms'] }}ms
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $check['message'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
