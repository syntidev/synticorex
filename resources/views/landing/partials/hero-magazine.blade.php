{{-- Hero Magazine/Editorial (Referencia - no se usa actualmente) --}}
<section id="home" class="min-h-screen bg-base-content flex overflow-hidden">
    <div class="flex-1 p-20 flex flex-col justify-center">
        <div class="text-xs tracking-[0.4em] text-primary mb-8 uppercase">
            {{ $tenant->business_name }}
        </div>
        <h1 class="text-7xl lg:text-9xl font-black text-base-100 leading-[0.9] mb-10 tracking-tighter">
            {!! nl2br(e($tenant->slogan)) !!}
        </h1>
        <p class="text-base-100/60 text-lg max-w-md mb-10">
            {{ Str::limit($tenant->description, 200) }}
        </p>
        <a href="https://wa.me/{{ $tenant->whatsapp }}"
           class="btn btn-primary btn-lg w-fit">Contáctanos</a>
    </div>
    <div class="w-[45%] bg-cover bg-center"
         style="background-image:url('{{ asset("storage/tenants/{$tenant->id}/{$customization->hero_main_filename}") }}');
                clip-path:polygon(10% 0, 100% 0, 100% 100%, 0% 100%);">
    </div>
</section>
