{{-- Estado guía B2H — sección vacía sin contenido --}}
@php
    $guides = [
        'products'     => [
            'icon'  => 'tabler--package',
            'title' => 'Tu catálogo está vacío',
            'msg'   => 'Agrega tu primer producto en Panel → Productos y aparecerá aquí al instante.',
        ],
        'services'     => [
            'icon'  => 'tabler--tools',
            'title' => 'Aún no tienes servicios publicados',
            'msg'   => 'Agrégalos en Panel → Servicios para que tus clientes los vean.',
        ],
        'faq'          => [
            'icon'  => 'tabler--help-circle',
            'title' => 'Activa tus Preguntas Frecuentes',
            'msg'   => 'Ve a Panel → FAQ y agrega las dudas que más te preguntan tus clientes.',
        ],
        'testimonials' => [
            'icon'  => 'tabler--quote',
            'title' => 'Aún no tienes reseñas publicadas',
            'msg'   => 'Agrega los comentarios de tus clientes en Panel → Testimonios y genera más confianza.',
        ],
        'branches'     => [
            'icon'  => 'tabler--map-pin',
            'title' => 'Sección de Sucursales activa',
            'msg'   => 'Agrega tus ubicaciones en Panel → Sucursales para que aparezcan aquí.',
        ],
        'about'        => [
            'icon'  => 'tabler--building-store',
            'title' => 'Cuéntales sobre tu negocio',
            'msg'   => 'Agrega una foto y una descripción en Panel → Perfil → Acerca de.',
        ],
        'payment_methods' => [
            'icon'  => 'tabler--credit-card',
            'title' => 'Métodos de pago no configurados',
            'msg'   => 'Configura tus medios de pago en Panel → Configuración → Pagos.',
        ],
    ];
    $guide = $guides[$section] ?? null;
@endphp

@if($guide)
<section class="py-12 sm:py-16 bg-background">
    <div class="mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center text-center gap-4 py-10
                    border border-dashed border-border rounded-2xl bg-muted/30">
            <span class="iconify {{ $guide['icon'] }} size-10 text-primary/40"></span>
            <h3 class="text-foreground/60 text-base font-semibold">
                {{ $guide['title'] }}
            </h3>
            <p class="text-muted-foreground-1 text-sm max-w-sm leading-relaxed">
                {{ $guide['msg'] }}
            </p>
        </div>
    </div>
</section>
@endif
