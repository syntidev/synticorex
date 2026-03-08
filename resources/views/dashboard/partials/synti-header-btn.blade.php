{{--
    ============================================================
    SYNTI — Botón en header del dashboard
    Archivo: resources/views/dashboard/partials/synti-header-btn.blade.php
    Incluir en: el header del dashboard, junto al reloj y "Ver sitio"
    ============================================================
--}}
<button
    @click="$dispatch('synti:open')"
    class="group relative flex items-center gap-1.5
           px-3 py-1.5 rounded-lg
           text-gray-500 hover:text-[#4A80E4]
           hover:bg-blue-50/80
           border border-transparent hover:border-blue-100
           transition-all duration-200 select-none"
    title="Asistente SYNTI (Alt+H)"
>
    {{-- Ícono Spark --}}
    <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 flex-shrink-0">
        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
    </svg>

    <span class="text-xs font-medium">SYNTI</span>

    {{-- Pulse periódico para llamar atención --}}
    <span
        class="absolute -top-0.5 -right-0.5 w-2 h-2"
        x-data="{ pulse: false }"
        x-init="setInterval(() => { pulse = true; setTimeout(() => pulse = false, 2000) }, 45000)"
    >
        <span
            x-show="pulse"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-50"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-150"
            class="absolute inline-flex h-full w-full rounded-full bg-[#4A80E4] opacity-75"
            style="display:none"
        ></span>
        <span
            x-show="pulse"
            class="relative inline-flex rounded-full h-2 w-2 bg-[#4A80E4]"
            style="display:none"
        ></span>
    </span>
</button>
