{{-- Hero Gradient Animado --}}
<section id="home" class="relative min-h-screen overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent"
         style="background-size:400% 400%;animation:gradient-xy 15s ease infinite;"></div>
    <div class="relative container mx-auto px-6 py-24 min-h-screen flex items-center">
        <div class="grid lg:grid-cols-2 gap-12 items-center w-full">
            <div class="text-base-100 space-y-8">
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                    {!! nl2br(e($tenant->slogan ?? 'Bienvenido')) !!}
                </h1>
                <p class="text-xl text-base-100/80 max-w-xl">
                    {{ Str::limit($tenant->description, 200) }}
                </p>
                <a href="https://wa.me/{{ $tenant->whatsapp }}"
                   class="btn btn-lg bg-base-100 text-primary">
                    Contactar Ahora
                </a>
            </div>
            <div class="hidden lg:block">
                <img src="{{ $customization->hero_main_filename
                    ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename)
                    : 'https://images.unsplash.com/photo-1552664730-d307ca884978' }}"
                     class="w-full h-96 object-cover rounded-3xl shadow-2xl rotate-2">
            </div>
        </div>
    </div>
</section>
