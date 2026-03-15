<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog — SYNTIweb</title>
    <meta name="description" content="Artículos, guías y novedades sobre presencia digital para negocios pequeños en Venezuela. Aprende a vender más con tu página SYNTIweb.">
    <meta property="og:title" content="Blog — SYNTIweb">
    <meta property="og:description" content="Artículos, guías y novedades sobre presencia digital para negocios pequeños en Venezuela.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .blog-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .blog-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
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

        {{-- Hero --}}
        <section class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-800 py-16 sm:py-20 text-center">
            <div class="mx-auto max-w-3xl px-4">
                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Blog SYNTIweb</h1>
                <p class="mt-4 text-lg text-slate-300">Guías, funciones y novedades para que tu negocio venda más en internet.</p>
            </div>
        </section>

        {{-- Post destacado --}}
        @if($featured)
        <section class="mx-auto max-w-6xl px-4 sm:px-6 -mt-10 relative z-10">
            <a href="{{ route('blog.show', $featured->slug) }}" class="block blog-card rounded-2xl overflow-hidden bg-white shadow-xl cursor-pointer">
                <div class="grid md:grid-cols-2">
                    @if($featured->image_url)
                    <div class="aspect-video md:aspect-auto">
                        <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}" class="h-full w-full object-cover" loading="lazy">
                    </div>
                    @endif
                    <div class="flex flex-col justify-center p-6 sm:p-10">
                        @if($featured->category)
                        <span class="inline-block w-fit rounded-full px-3 py-1 text-xs font-semibold text-white mb-4" style="background-color: {{ $featured->category->color }}">
                            {{ $featured->category->name }}
                        </span>
                        @endif
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">{{ $featured->title }}</h2>
                        <p class="mt-3 text-slate-600 text-sm line-clamp-3">{{ $featured->excerpt }}</p>
                        <div class="mt-5 flex items-center gap-3 text-xs text-slate-500">
                            @if($featured->avatar_url)
                            <img src="{{ $featured->avatar_url }}" alt="{{ $featured->author }}" class="h-8 w-8 rounded-full">
                            @endif
                            <span class="font-medium">{{ $featured->author }}</span>
                            <span>&middot;</span>
                            <span>{{ $featured->published_at?->format('d M Y') }}</span>
                            <span>&middot;</span>
                            <span>{{ $featured->read_time }}</span>
                        </div>
                        <span class="mt-5 inline-flex items-center text-sm font-semibold text-[#4A80E4]">
                            Leer artículo &rarr;
                        </span>
                    </div>
                </div>
            </a>
        </section>
        @endif

        {{-- Filtros de categoría --}}
        <section class="mx-auto max-w-6xl px-4 sm:px-6 mt-10 sm:mt-14">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors cursor-pointer min-h-11
                          {{ !$currentCat ? 'bg-[#4A80E4] text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    Todos
                </a>
                @foreach($categories as $category)
                <a href="{{ route('blog.index', ['cat' => $category->slug]) }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors cursor-pointer min-h-11
                          {{ $currentCat === $category->slug ? 'text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
                   @if($currentCat === $category->slug) style="background-color: {{ $category->color }}" @endif>
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </section>

        {{-- Grid de posts --}}
        <section class="mx-auto max-w-6xl px-4 sm:px-6 mt-8 pb-16">
            @if($posts->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card flex flex-col rounded-xl overflow-hidden bg-white shadow-sm border border-slate-200/60 cursor-pointer">
                    @if($post->image_url)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover" loading="lazy">
                    </div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        @if($post->category)
                        <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[11px] font-semibold text-white mb-3" style="background-color: {{ $post->category->color }}">
                            {{ $post->category->name }}
                        </span>
                        @endif
                        <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2">{{ $post->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600 line-clamp-2 flex-1">{{ $post->excerpt }}</p>
                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-500">
                            @if($post->avatar_url)
                            <img src="{{ $post->avatar_url }}" alt="{{ $post->author }}" class="h-6 w-6 rounded-full">
                            @endif
                            <span class="font-medium">{{ $post->author }}</span>
                            <span>&middot;</span>
                            <span>{{ $post->published_at?->format('d M Y') }}</span>
                            <span>&middot;</span>
                            <span>{{ $post->read_time }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
            @else
            <div class="py-20 text-center">
                <p class="text-slate-500">No hay artículos en esta categoría todavía.</p>
            </div>
            @endif
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
