{{--
    Bottom Bar Promocional — SYNTIweb
    ─────────────────────────────────────
    Plan requerido : 2 (CRECIMIENTO) o 3 (VISIÓN)
    Guard          : en base.blade.php
    Posición       : fijo al fondo, encima del floating-panel
    Altura         : 40px (h-10)
    Variables      : $customization->header_message
--}}
@if(!empty($customization?->header_message))
<div id="bottom-bar" class="w-full z-40 h-10 bg-primary text-primary-foreground flex items-center overflow-hidden">
    <div class="flex items-center gap-2 px-4 shrink-0">
        <iconify-icon icon="tabler:speakerphone" width="16" height="16"></iconify-icon>
    </div>
    <div class="flex-1 overflow-hidden">
        <p class="whitespace-nowrap animate-marquee text-sm font-medium inline-block">
            {{ $customization->header_message }}
            &nbsp;&nbsp;&nbsp;&mdash;&nbsp;&nbsp;&nbsp;
            {{ $customization->header_message }}
        </p>
    </div>
</div>
<style>
@keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.animate-marquee { animation: marquee 18s linear infinite; }
</style>
@endif
