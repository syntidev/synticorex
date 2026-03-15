<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->meta_title ?: $post->title }} — SYNTIweb Blog</title>
    <meta name="description" content="{{ $post->meta_description ?: $post->excerpt }}">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
    <meta property="og:type" content="article">
    @if($post->image_url)
    <meta property="og:image" content="{{ $post->image_url }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .blog-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .blog-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
        .prose h2 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 0.75rem; color: #0f172a; }
        .prose h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #1e293b; }
        .prose p { margin-bottom: 1rem; line-height: 1.75; color: #334155; }
        .prose blockquote { border-left: 4px solid #4A80E4; padding: 1rem 1.5rem; margin: 1.5rem 0; background: #f8fafc; border-radius: 0 0.5rem 0.5rem 0; font-style: italic; color: #475569; }
        .prose ul, .prose ol { margin: 1rem 0; padding-left: 1.5rem; }
        .prose li { margin-bottom: 0.5rem; line-height: 1.75; color: #334155; }
        .prose ul li { list-style-type: disc; }
        .prose ol li { list-style-type: decimal; }
        .prose strong { font-weight: 600; color: #0f172a; }
        .prose em { font-style: italic; }
        .prose a { color: #4A80E4; text-decoration: underline; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer">
                <span class="text-xl font-black tracking-tight text-slate-900">SYNTI<span class="text-[#4A80E4]">web</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 cursor-pointer">Inicio</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-[#4A80E4] cursor-pointer">Blog</a>
                <a href="{{ route('onboarding.selector') }}" class="inline-flex items-center justify-center rounded-lg bg-[#4A80E4] px-4 py-2 text-sm font-semibold text-white hover:bg-[#3b6fd4] transition-colors cursor-pointer min-h-11">Crear mi página</a>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">

        {{-- Hero del post --}}
        <section class="relative">
            @if($post->image_url)
            <div class="aspect-[21/9] max-h-[420px] w-full overflow-hidden">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
            </div>
            @else
            <div class="h-48 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-800"></div>
            @endif
        </section>

        {{-- Contenido --}}
        <article class="mx-auto max-w-3xl px-4 sm:px-6 -mt-16 relative z-10">
            <div class="rounded-2xl bg-white p-6 sm:p-10 shadow-lg">

                {{-- Meta superior --}}
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    @if($post->category)
                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold text-white" style="background-color: {{ $post->category->color }}">
                        {{ $post->category->name }}
                    </span>
                    @endif
                    <span class="text-xs text-slate-500">{{ $post->published_at?->format('d M Y') }}</span>
                    <span class="text-xs text-slate-400">&middot;</span>
                    <span class="text-xs text-slate-500">{{ $post->read_time }}</span>
                </div>

                {{-- Título --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight tracking-tight">
                    {{ $post->title }}
                </h1>

                {{-- Autor --}}
                <div class="mt-6 flex items-center gap-3 border-b border-slate-100 pb-6">
                    @if($post->avatar_url)
                    <img src="{{ $post->avatar_url }}" alt="{{ $post->author }}" class="h-10 w-10 rounded-full">
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $post->author }}</p>
                        <p class="text-xs text-slate-500">{{ $post->views }} lecturas</p>
                    </div>
                </div>

                {{-- Cuerpo HTML --}}
                <div class="prose mt-8 max-w-none">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if($post->tags && count($post->tags))
                <div class="mt-10 flex flex-wrap gap-2 border-t border-slate-100 pt-6">
                    @foreach($post->tags as $tag)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </article>

        {{-- Posts relacionados --}}
        @if($related->count())
        <section class="mx-auto max-w-6xl px-4 sm:px-6 mt-14 pb-16">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Artículos relacionados</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $relPost)
                <a href="{{ route('blog.show', $relPost->slug) }}" class="blog-card flex flex-col rounded-xl overflow-hidden bg-white shadow-sm border border-slate-200/60 cursor-pointer">
                    @if($relPost->image_url)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ $relPost->image_url }}" alt="{{ $relPost->title }}" class="h-full w-full object-cover" loading="lazy">
                    </div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        @if($relPost->category)
                        <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[11px] font-semibold text-white mb-3" style="background-color: {{ $relPost->category->color }}">
                            {{ $relPost->category->name }}
                        </span>
                        @endif
                        <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2">{{ $relPost->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600 line-clamp-2 flex-1">{{ $relPost->excerpt }}</p>
                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-500">
                            <span>{{ $relPost->published_at?->format('d M Y') }}</span>
                            <span>&middot;</span>
                            <span>{{ $relPost->read_time }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- CTA final --}}
        <section class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-800 py-16 text-center">
            <div class="mx-auto max-w-2xl px-4">
                <h2 class="text-2xl sm:text-3xl font-bold text-white">Crea tu presencia digital</h2>
                <p class="mt-3 text-slate-300">Tu negocio visible en Google, listo para vender por WhatsApp. En minutos.</p>
                <a href="{{ route('onboarding.selector') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#4A80E4] px-6 py-3 text-sm font-semibold text-white hover:bg-[#3b6fd4] transition-colors cursor-pointer min-h-11">
                    Empezar ahora &rarr;
                </a>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 text-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} SYNTIweb. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
