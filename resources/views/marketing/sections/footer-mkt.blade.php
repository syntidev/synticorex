{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- SYNTIweb Marketing Footer — compartido entre todas las páginas propias --}}
{{-- Incluir como: @include('marketing.sections.footer-mkt')               --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<footer class="mt-auto w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <a class="flex items-center gap-2 font-semibold text-xl text-foreground focus:outline-hidden focus:opacity-80"
               href="{{ url('/') }}">
                <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" width="24" height="24" alt="SYNTIweb">
                SYNTIweb
            </a>
            <p class="mt-1 text-xs text-muted-foreground-2">
                &copy; {{ date('Y') }} SYNTIweb. Todos los derechos reservados.
            </p>
        </div>
        <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm">
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground transition-colors"
               href="{{ url('/planes') }}">Planes</a>
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground transition-colors"
               href="{{ route('marketing.about') }}">Nosotros</a>
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground transition-colors"
               href="{{ route('marketing.privacy') }}">Privacidad</a>
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground transition-colors"
               href="{{ route('marketing.terms') }}">Términos</a>
        </div>
    </div>
</footer>
