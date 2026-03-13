<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SYNTI.dev | Production Core</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;700;800&family=JetBrains+Mono:wght@300;500&display=swap');
        
        :root { --neon-blue: #3b82f6; --deep-bg: #030303; }
        body { background-color: var(--deep-bg); color: #ececec; font-family: 'Plus Jakarta Sans', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        
        .sidebar-carbon { background: rgba(10, 10, 10, 0.8); backdrop-filter: blur(20px); border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-item-carbon { border-left: 2px solid transparent; transition: 0.3s; }
        .nav-item-carbon:hover, .nav-item-carbon.active { background: rgba(59, 130, 246, 0.05); border-left-color: var(--neon-blue); }
        
        .glass-panel { background: rgba(15, 15, 15, 0.7); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased overflow-hidden">
    <div class="flex h-screen">
        <aside class="sidebar-carbon w-72 flex flex-col p-6">
            <div class="mono font-bold tracking-tighter text-xl mb-12 uppercase">
                SYNTI<span class="text-blue-500">.dev</span>
            </div>
            
            <nav class="flex-1 space-y-1">
                <p class="mono text-[10px] text-slate-600 mb-4 tracking-[0.3em] uppercase">// Global_Systems</p>
                <a href="#" class="nav-item-carbon active flex items-center gap-3 p-3 rounded-r-lg">
                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                    <span class="mono text-xs uppercase tracking-widest">01_Dashboard</span>
                </a>
                <a href="#" class="nav-item-carbon flex items-center gap-3 p-3 rounded-r-lg text-slate-500">
                    <div class="w-1.5 h-1.5 bg-slate-800 rounded-full"></div>
                    <span class="mono text-xs uppercase tracking-widest text-slate-500">02_Tenants_Core</span>
                </a>
                <a href="{{ route('admin.billing.view') }}" class="nav-item-carbon {{ request()->routeIs('admin.billing.*') ? 'active' : '' }} flex items-center gap-3 p-3 rounded-r-lg {{ request()->routeIs('admin.billing.*') ? '' : 'text-slate-500' }}">
                    <div class="w-1.5 h-1.5 {{ request()->routeIs('admin.billing.*') ? 'bg-blue-500' : 'bg-slate-800' }} rounded-full"></div>
                    <span class="mono text-xs uppercase tracking-widest {{ request()->routeIs('admin.billing.*') ? '' : 'text-slate-500' }}">03_Billing_Queue</span>
                </a>
            </nav>

            <div class="border-t border-white/5 pt-6">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="mono text-[10px] text-blue-500 uppercase">System_Live</span>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col relative overflow-y-auto">
            <header class="p-6 border-b border-white/5 flex justify-between items-center glass-panel sticky top-0 z-50">
                <h1 class="mono text-xs uppercase tracking-[0.5em] text-slate-500">// Terminal_Session: <span class="text-white font-bold">{{ auth()->user()->name }}</span></h1>
            </header>

            <div class="p-10">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>