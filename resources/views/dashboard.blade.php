@extends('layouts.admin')

@section('content')
<canvas id="network" class="fixed inset-0 pointer-events-none opacity-10"></canvas>

<div class="relative z-10 space-y-8">
    <div class="flex justify-between items-end border-b border-white/5 pb-6">
        <div>
            <p class="mono text-blue-500 text-[10px] uppercase tracking-widest mb-2">// System_Status</p>
            <h2 class="text-3xl font-extrabold tracking-tighter">ENGINE_CORE <span class="text-slate-500 font-light">v1.0</span></h2>
        </div>
        <div class="text-right mono">
            <p class="text-[10px] text-slate-500 uppercase">Uptime_Session</p>
            <p class="text-xl text-blue-400">02:45:12:08</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        
        <div class="col-span-12 lg:col-span-8 glass-panel rounded-lg p-6 border-l-2 border-l-blue-500">
            <div class="flex justify-between mb-6">
                <span class="mono text-[10px] uppercase tracking-widest text-slate-400">Active_Infrastructure_Flow</span>
                <span class="text-green-500 mono text-[10px]">● STABLE</span>
            </div>
            <div class="h-48 flex items-end gap-1">
                @foreach(range(1, 40) as $i)
                    <div class="flex-1 bg-blue-500/20 hover:bg-blue-500/50 transition-all rounded-t-sm" style="height: {{ rand(20, 90) }}%"></div>
                @endforeach
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-6">
            <div class="glass-panel p-6 rounded-lg group hover:border-blue-500/30 transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mono text-xs text-blue-500 mb-1">NEW_INSTANCE</p>
                        <h4 class="font-bold text-lg">Desplegar Tenant</h4>
                    </div>
                    <i class="fas fa-plus-circle text-2xl text-slate-700 group-hover:text-blue-500 transition-colors"></i>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg group hover:border-red-500/30 transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mono text-xs text-red-500 mb-1">SYSTEM_SHIELD</p>
                        <h4 class="font-bold text-lg">Firewall Global</h4>
                    </div>
                    <i class="fas fa-shield-virus text-2xl text-slate-700 group-hover:text-red-500 transition-colors"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const canvas = document.getElementById('network');
    const ctx = canvas.getContext('2d');
    let particles = [];
    function setup() {
        canvas.width = window.innerWidth; canvas.height = window.innerHeight;
        particles = Array.from({length: 30}, () => ({
            x: Math.random() * canvas.width, y: Math.random() * canvas.height,
            vx: (Math.random()-0.5)*0.1, vy: (Math.random()-0.5)*0.1
        }));
    }
    function draw() {
        ctx.clearRect(0,0,canvas.width, canvas.height);
        particles.forEach((p, i) => {
            p.x += p.vx; p.y += p.vy;
            if(p.x<0 || p.x>canvas.width) p.vx *= -1; if(p.y<0 || p.y>canvas.height) p.vy *= -1;
            ctx.fillStyle = 'rgba(59, 130, 246, 0.2)';
            ctx.beginPath(); ctx.arc(p.x, p.y, 1, 0, Math.PI*2); ctx.fill();
            particles.slice(i+1).forEach(p2 => {
                let d = Math.hypot(p.x-p2.x, p.y-p2.y);
                if(d < 200) {
                    ctx.strokeStyle = `rgba(59, 130, 246, ${0.05 * (1 - d/200)})`;
                    ctx.lineWidth = 0.5; ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(p2.x, p2.y); ctx.stroke();
                }
            });
        });
        requestAnimationFrame(draw);
    }
    setup(); draw();
    window.addEventListener('resize', setup);
</script>
@endsection