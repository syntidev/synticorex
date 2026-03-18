@extends('marketing.layout')

@push('seo')
<title>Contacto | SYNTIweb</title>
<meta name="description" content="Escríbenos por WhatsApp o formulario. Respondemos en menos de 24 horas.">
<meta property="og:title" content="Contacto | SYNTIweb">
<meta property="og:description" content="Escríbenos por WhatsApp o formulario. Respondemos en menos de 24 horas.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('brand/syntiweb-og.png') }}">
<meta property="og:type" content="website">
@endpush

@section('content')
{{-- ══ Ícono decorativo emergente (mobile: sobresale del borde superior) ══ --}}
<div class="flex justify-center -mb-10 relative z-10 pt-10 md:hidden">
    <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100"
         style="background:linear-gradient(135deg,#eff5ff,#dbeafe)">
        <iconify-icon icon="tabler:message-dots" width="40" height="40" style="color:#4A80E4"></iconify-icon>
    </div>
</div>

<section class="relative overflow-hidden py-16 md:py-24" style="background:linear-gradient(160deg,#f8fafc 0%,#ffffff 60%,#f0f7ff 100%)">

    {{-- Marca de agua: logo SYNTIweb --}}
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
        <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="" width="300" height="300"
             class="opacity-[0.04] select-none">
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header — ícono solo en desktop --}}
        <div class="text-center mb-12">
            <div class="hidden md:flex justify-center mb-5">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100"
                     style="background:linear-gradient(135deg,#eff5ff,#dbeafe)">
                    <iconify-icon icon="tabler:message-dots" width="40" height="40" style="color:#4A80E4"></iconify-icon>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-slate-900">¿Hablamos?</h2>
            <p class="mt-3 text-slate-500">Estamos para ayudarte a dar el primer paso.</p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

            {{-- Columna izquierda — datos de contacto --}}
            <div class="space-y-4">
                <a href="mailto:impulso@syntiweb.com"
                   class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <iconify-icon icon="tabler:mail" width="24" height="24" style="color:#4A80E4"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Email</p>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-[#4A80E4] transition-colors">impulso@syntiweb.com</p>
                    </div>
                </a>

                <a href="https://wa.me/584243244788"
                   target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <iconify-icon icon="tabler:brand-whatsapp" width="24" height="24" class="text-emerald-500"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">WhatsApp</p>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-emerald-600 transition-colors">+58 424 324 4788</p>
                    </div>
                </a>
            </div>

            {{-- Columna derecha — formulario Formspree --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <form action="https://formspree.io/f/mnjbvelo" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="contact-nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                        <input type="text" id="contact-nombre" name="nombre" required
                               placeholder="Tu nombre"
                               class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4] transition">
                    </div>
                    <div>
                        <label for="contact-email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" id="contact-email" name="email" required
                               placeholder="tu@email.com"
                               class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4] transition">
                    </div>
                    <div>
                        <label for="contact-mensaje" class="block text-sm font-medium text-slate-700 mb-1">Mensaje</label>
                        <textarea id="contact-mensaje" name="mensaje" rows="4" required
                                  placeholder="¿En qué podemos ayudarte?"
                                  class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4] transition resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-3 px-4 rounded-lg font-semibold text-white transition-all hover:-translate-y-0.5 cursor-pointer"
                            style="background:#4A80E4;box-shadow:0 4px 14px 0 color-mix(in oklch,#4A80E4 40%,transparent)">
                        Enviar mensaje
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection
