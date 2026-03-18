@extends('marketing.layout')

@push('seo')
<title>Nosotros — Hechos en Venezuela para Venezuela | SYNTIweb</title>
<meta name="description" content="SYNTIweb es una plataforma venezolana para ayudar a pequeños negocios a tener presencia digital.">
<meta property="og:title" content="Nosotros — Hechos en Venezuela para Venezuela | SYNTIweb">
<meta property="og:description" content="SYNTIweb es una plataforma venezolana para ayudar a pequeños negocios a tener presencia digital.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('brand/syntiweb-og.png') }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <main class="min-h-screen">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <div class="mb-8 sm:mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#4A80E4] hover:opacity-80 transition-opacity cursor-pointer min-h-11">
                    <span aria-hidden="true">←</span>
                    <span>Volver al inicio</span>
                </a>
                <h1 class="mt-4 text-3xl sm:text-4xl font-black tracking-tight text-slate-900">Nosotros</h1>
                <p class="mt-3 text-base text-slate-600">Infraestructura digital simple para negocios reales que venden por WhatsApp.</p>
            </div>

            <div class="grid gap-6 sm:gap-8 md:grid-cols-2">
                <article class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-900">Mision</h2>
                    <p class="mt-3 text-slate-700 leading-relaxed">Ayudar a pequenos negocios de Venezuela a publicar su presencia digital en minutos, sin friccion tecnica, con foco en conversion y cierre comercial rapido.</p>
                </article>

                <article class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-900">Vision</h2>
                    <p class="mt-3 text-slate-700 leading-relaxed">Ser la capa de infraestructura SaaS mas confiable para comercios locales que necesitan crecer sin depender de agencias costosas ni procesos lentos.</p>
                </article>
            </div>

            <article class="mt-6 sm:mt-8 bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4">
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Como trabajamos</h2>
                <ul class="space-y-3 text-slate-700 leading-relaxed list-disc pl-5">
                    <li>Producto orientado a ejecucion: menos friccion, mas resultados.</li>
                    <li>Arquitectura multitenant para escalar muchos negocios con bajo costo operativo.</li>
                    <li>Diseno mobile-first, porque el duenno del negocio administra desde el celular.</li>
                    <li>Integracion natural con WhatsApp como canal primario de contacto y cierre.</li>
                </ul>
            </article>

            <article class="mt-6 sm:mt-8 bg-[#1a1a1a] text-white rounded-2xl p-6 sm:p-8">
                <h2 class="text-2xl font-black tracking-tight">Construimos para la calle, no para diapositivas.</h2>
                <p class="mt-3 text-slate-300 leading-relaxed">Cada decision de producto se mide por su impacto real en negocios pequenos: mas consultas, mas pedidos y mejor control operativo.</p>
                <a href="{{ route('register') }}" class="inline-flex mt-5 items-center justify-center min-h-11 px-5 rounded-xl bg-[#4A80E4] text-white font-bold hover:opacity-90 transition-opacity">Crear mi cuenta</a>
            </article>
        </section>
    </main>

@endsection
