@extends('marketing.layout')

@section('content')
    <main class="min-h-screen">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <div class="mb-8 sm:mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#4A80E4] hover:opacity-80 transition-opacity cursor-pointer min-h-11">
                    <span aria-hidden="true">â†</span>
                    <span>Volver al inicio</span>
                </a>
                <h1 class="mt-4 text-3xl sm:text-4xl font-black tracking-tight text-slate-900">Terminos y Condiciones</h1>
                <p class="mt-3 text-sm sm:text-base text-slate-600">Ultima actualizacion: {{ now()->format('d/m/Y') }}</p>
            </div>

            <article class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 space-y-6 leading-relaxed shadow-sm">
                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">1. Objeto del servicio</h2>
                    <p class="mt-2 text-slate-700">SYNTIweb provee infraestructura SaaS para crear y administrar landings comerciales multitenant con contacto por WhatsApp. El servicio incluye herramientas de configuracion, publicacion y edicion de contenido segun el plan contratado.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">2. Cuenta y acceso</h2>
                    <p class="mt-2 text-slate-700">Cada usuario es responsable de su cuenta, credenciales y uso de terceros autorizados. El titular puede gestionar multiples negocios bajo la misma cuenta, respetando las politicas de uso y los limites de su plan.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">3. Planes, renovacion y alcance</h2>
                    <p class="mt-2 text-slate-700">Los planes se ofrecen en modalidad anual y definen limites funcionales (productos, servicios y capacidades de diseno). SYNTIweb puede actualizar funciones o limites para nuevas contrataciones y renovaciones con aviso previo.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">4. Datos, contenido y responsabilidad</h2>
                    <p class="mt-2 text-slate-700">El titular del negocio conserva la responsabilidad sobre textos, imagenes, precios, disponibilidad y cumplimiento legal de su actividad comercial. Queda prohibido publicar contenido ilicito, fraudulento o que infrinja derechos de terceros.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">5. Dominio personalizado</h2>
                    <p class="mt-2 text-slate-700">Los dominios personalizados solo se activan cuando el proceso de verificacion tecnica y de propiedad ha sido completado. Mientras no exista verificacion, el negocio permanece en subdominio administrado por la plataforma.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">6. Disponibilidad y soporte</h2>
                    <p class="mt-2 text-slate-700">SYNTIweb opera bajo mejores practicas de disponibilidad, pero no garantiza continuidad absoluta sin interrupciones. Podran existir ventanas de mantenimiento, incidentes de terceros o fuerza mayor.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">7. Terminacion</h2>
                    <p class="mt-2 text-slate-700">La cuenta puede ser suspendida o terminada por incumplimiento de estos terminos, actividad maliciosa, fraude o uso indebido de la plataforma. El usuario tambien puede dejar de usar el servicio en cualquier momento.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">8. Contacto</h2>
                    <p class="mt-2 text-slate-700">Para consultas legales o administrativas: <a class="font-semibold text-[#4A80E4] hover:opacity-80" href="mailto:impulso@syntiweb.com">impulso@syntiweb.com</a>.</p>
                </section>
            </article>
        </section>
    </main>

@endsection
