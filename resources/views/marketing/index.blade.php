@extends('marketing.layout')

@section('content')
<div x-data="marketingApp()">

    {{-- â•â•â• SECTIONS â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    @include('marketing.sections.hero')
    @include('marketing.sections.syntia')
    @include('marketing.sections.problema')
    @include('marketing.sections.solucion')
    @include('marketing.sections.segmentos')
    @include('marketing.sections.dashboard')
    @include('marketing.sections.valor')
    @include('marketing.sections.planes')
    @include('marketing.sections.estadisticas')
    @include('marketing.sections.configuracion')
    @include('marketing.sections.conversion')
    @include('marketing.sections.cta-final')
    {{-- Barra infraestructura --}}
    <div class="bg-slate-50 border-t border-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-300 mb-6">Infraestructura y respaldo técnico</p>
            <div class="flex items-center justify-center gap-8 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <span class="iconify tabler--brand-google size-4 text-blue-500"></span>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-600">Google Cloud</div>
                        <div class="text-xs text-slate-400">Servidores certificados</div>
                    </div>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <span class="iconify tabler--bolt size-4 text-blue-500"></span>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-600">LiteSpeed</div>
                        <div class="text-xs text-slate-400">Fibra · 99.9% uptime</div>
                    </div>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <span class="iconify tabler--lock size-4 text-emerald-500"></span>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-600">SSL · Datos seguros</div>
                        <div class="text-xs text-slate-400">Cifrado end-to-end</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function marketingApp() {
            return {
                scrolled: false,
                mobileNav: false,
                init() {
                    const onScroll = () => { this.scrolled = window.scrollY > 60; };
                    window.addEventListener('scroll', onScroll, { passive: true });
                    onScroll();

                    // Intersection Observer for fade-in
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
                    document.querySelectorAll('.mkt-fade-in').forEach(el => observer.observe(el));
                }
            };
        }
    </script>
@endpush
