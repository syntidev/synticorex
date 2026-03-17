@extends('marketing.layout')

@section('content')
    <main class="min-h-screen">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <div class="mb-8 sm:mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#4A80E4] hover:opacity-80 transition-opacity cursor-pointer min-h-11">
                    <span aria-hidden="true">â†</span>
                    <span>Volver al inicio</span>
                </a>
                <h1 class="mt-4 text-3xl sm:text-4xl font-black tracking-tight text-slate-900">Politica de Privacidad</h1>
                <p class="mt-3 text-sm sm:text-base text-slate-600">Ultima actualizacion: {{ now()->format('d/m/Y') }}</p>
            </div>

            <article class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 space-y-6 leading-relaxed shadow-sm">
                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">1. Datos que recopilamos</h2>
                    <p class="mt-2 text-slate-700">Recopilamos datos de registro de cuenta, informacion de negocio configurada por el usuario y eventos tecnicos necesarios para operar la plataforma (por ejemplo, autenticacion, seguridad y analitica basica).</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">2. Uso de la informacion</h2>
                    <p class="mt-2 text-slate-700">Los datos se usan para prestar el servicio, mantener seguridad operativa, habilitar funcionalidades contratadas, mejorar rendimiento y brindar soporte. No vendemos datos personales a terceros.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">3. Conservacion y seguridad</h2>
                    <p class="mt-2 text-slate-700">Aplicamos medidas razonables de seguridad tecnica y organizativa. Ningun sistema es infalible, pero trabajamos para minimizar riesgos de acceso no autorizado, perdida o alteracion de datos.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">4. Datos de terceros y canales externos</h2>
                    <p class="mt-2 text-slate-700">SYNTIweb integra enlaces o flujos hacia terceros como WhatsApp y proveedores de dominio. El tratamiento de datos en esos servicios se rige por sus propias politicas.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">5. Derechos del titular</h2>
                    <p class="mt-2 text-slate-700">El titular de la cuenta puede solicitar actualizacion o correccion de su informacion en lo que aplique al servicio activo, a traves de los canales de soporte.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">6. Cambios en esta politica</h2>
                    <p class="mt-2 text-slate-700">Podemos actualizar esta politica para reflejar cambios operativos, legales o de producto. La version vigente sera publicada en esta misma URL.</p>
                </section>

                <section>
                    <h2 class="text-xl font-extrabold text-slate-900">7. Contacto</h2>
                    <p class="mt-2 text-slate-700">Para solicitudes sobre privacidad: <a class="font-semibold text-[#4A80E4] hover:opacity-80" href="mailto:impulso@syntiweb.com">impulso@syntiweb.com</a>.</p>
                </section>
            </article>
        </section>
    </main>

@endsection
