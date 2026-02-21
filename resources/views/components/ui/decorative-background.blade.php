{{-- Ruta del archivo: resources/views/components/ui/decorative-background.blade.php --}}
<div class="absolute inset-0 overflow-hidden pointer-events-none z-[0]">
    {{-- 1. Aura Superior: La mantenemos fuera del área del Logo --}}
    <div class="absolute -top-48 -left-24 h-[600px] w-[600px] rounded-full bg-primary/5 blur-[120px]"></div>
    
    {{-- 2. Marco Cristal 1: El ancla es top-0 con mt-28 para que NUNCA suba al Nav --}}
    <div class="absolute top-0 mt-28 right-[5%] h-[520px] w-80 -rotate-12 rounded-[3.5rem] border-2 border-primary/20 bg-gradient-to-br from-primary/5 to-transparent backdrop-blur-[2px] hidden lg:block shadow-inner"></div>
    
    {{-- 3. Marco Cristal 2: Profundidad controlada --}}
    <div class="absolute top-0 mt-48 right-[12%] h-[520px] w-80 rotate-6 rounded-[3.5rem] border-2 border-secondary/15 bg-gradient-to-tr from-secondary/5 to-transparent hidden lg:block"></div>

    {{-- 4. Balance inferior: Para que la sección de productos respire --}}
    <div class="absolute bottom-0 right-[20%] h-64 w-64 rounded-full bg-secondary/5 blur-[100px]"></div>
</div>