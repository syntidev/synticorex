<div
    x-data="{
        stats: { active: 0, pending_payments: 0, open_tickets: 0 },
        init() {
            this.fetchStats();
            setInterval(() => this.fetchStats(), 30000);
        },
        async fetchStats() {
            try {
                const res = await fetch('/admin/stats-badge');
                if (res.ok) this.stats = await res.json();
            } catch (e) {}
        }
    }"
    class="flex items-center gap-2 me-2"
>
    {{-- Tenants activos --}}
    <span
        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold"
        style="background-color: rgba(74, 128, 228, 0.15); color: #4A80E4;"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4v18"/><path d="M19 21v-10l-6 -4"/><path d="M9 9l0 .01"/><path d="M9 12l0 .01"/><path d="M9 15l0 .01"/></svg>
        <span x-text="stats.active">0</span>
    </span>

    {{-- Pagos pendientes --}}
    <span
        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold"
        style="background-color: rgba(245, 158, 11, 0.15); color: #F59E0B;"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 7v5l3 3"/></svg>
        <span x-text="stats.pending_payments">0</span>
    </span>

    {{-- Tickets abiertos (solo si > 0) --}}
    <template x-if="stats.open_tickets > 0">
        <span
            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold"
            style="background-color: rgba(239, 68, 68, 0.15); color: #EF4444;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l0 2"/><path d="M15 11l0 2"/><path d="M15 17l0 2"/><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2"/></svg>
            <span x-text="stats.open_tickets">0</span>
        </span>
    </template>
</div>
