{{-- Hero Split Layout --}}
<section id="home" class="min-h-screen bg-base-200 flex items-center">
    <div class="container mx-auto px-6 py-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                @if($tenant->tagline)
                    <span class="badge badge-primary badge-lg">{{ $tenant->tagline }}</span>
                @endif
                <h1 class="text-5xl lg:text-6xl font-black text-base-content">
                    {!! nl2br(e($tenant->slogan ?? $tenant->business_name)) !!}
                </h1>
                <p class="text-lg text-base-content/70">
                    {{ Str::limit($customization->about_text ?? $tenant->description, 250) }}
                </p>
                <div class="flex flex-wrap gap-4">
                    @if($tenant->whatsapp)
                    <a href="https://wa.me/{{ $tenant->whatsapp }}" class="btn btn-primary btn-lg">
                        WhatsApp
                    </a>
                    @endif
                    <a href="#about" class="btn btn-outline btn-lg">Conocer más ↓</a>
                </div>
            </div>
            <div class="relative">
                <img src="{{ $customization->hero_main_filename 
                    ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename) 
                    : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200' }}"
                     class="w-full h-[500px] object-cover rounded-3xl shadow-2xl">
            </div>
        </div>
    </div>
</section>
