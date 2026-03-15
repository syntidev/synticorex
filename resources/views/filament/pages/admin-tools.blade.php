<x-filament-panels::page>
    <div x-data="{
        results: {},
        loading: {},
        logLines: '',
        async runAction(name, url, method = 'POST') {
            this.loading[name] = true;
            this.results[name] = '';
            try {
                const opts = { method, headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content } };
                const res = await fetch(url, opts);
                const data = await res.json();
                this.results[name] = data.message || JSON.stringify(data);
            } catch (e) {
                this.results[name] = 'Error: ' + e.message;
            }
            this.loading[name] = false;
        },
        async loadDisk() {
            this.loading['disk'] = true;
            try {
                const res = await fetch('/admin/tools/disk', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                this.results['disk'] = `Total: ${data.disk_total} | Libre: ${data.disk_free} | Usado: ${data.disk_used} (${data.disk_used_percent}%) | Storage: ${data.storage_used}`;
            } catch (e) {
                this.results['disk'] = 'Error: ' + e.message;
            }
            this.loading['disk'] = false;
        },
        async loadLogTail() {
            this.loading['log'] = true;
            try {
                const res = await fetch('/admin/tools/log-tail', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                this.logLines = data.lines || 'Sin errores ni warnings recientes.';
            } catch (e) {
                this.logLines = 'Error: ' + e.message;
            }
            this.loading['log'] = false;
        },
        confirmAndRun(name, url, confirmMsg) {
            if (confirm(confirmMsg)) this.runAction(name, url);
        }
    }" x-init="loadDisk(); loadLogTail()">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
            @php
                $tools = [
                    ['key' => 'cache', 'icon' => 'tabler--trash', 'name' => 'Limpiar Cache', 'desc' => 'Limpia cache, config, vistas y rutas', 'url' => '/admin/tools/cache', 'confirm' => '¿Limpiar todo el cache?'],
                    ['key' => 'queue', 'icon' => 'tabler--rotate-clockwise', 'name' => 'Reiniciar Queue', 'desc' => 'Reinicia los workers de cola', 'url' => '/admin/tools/queue', 'confirm' => '¿Reiniciar queue workers?'],
                    ['key' => 'migrate', 'icon' => 'tabler--database', 'name' => 'Ejecutar Migraciones', 'desc' => 'Ejecuta migraciones pendientes', 'url' => '/admin/tools/migrate', 'confirm' => '¿Ejecutar migraciones en producción?'],
                    ['key' => 'suspend', 'icon' => 'tabler--user-off', 'name' => 'Suspender Expirados', 'desc' => 'Suspende tenants con suscripción vencida', 'url' => '/admin/tools/suspend-expired', 'confirm' => '¿Suspender todos los tenants expirados?'],
                    ['key' => 'reindex', 'icon' => 'tabler--brain', 'name' => 'Reindexar Docs IA', 'desc' => 'Reindexar base de conocimiento IA', 'url' => '/admin/tools/reindex', 'confirm' => '¿Reindexar documentos IA?'],
                ];
            @endphp

            @foreach ($tools as $tool)
                <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20 shrink-0">
                            <x-icon name="{{ $tool['icon'] }}" class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $tool['name'] }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tool['desc'] }}</p>
                        </div>
                    </div>
                    <button type="button"
                            class="cursor-pointer w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition min-h-[44px]"
                            x-on:click="confirmAndRun('{{ $tool['key'] }}', '{{ $tool['url'] }}', '{{ $tool['confirm'] }}')"
                            x-bind:disabled="loading['{{ $tool['key'] }}']">
                        <span x-show="!loading['{{ $tool['key'] }}']">Ejecutar</span>
                        <span x-show="loading['{{ $tool['key'] }}']" x-cloak>Ejecutando...</span>
                    </button>
                    <template x-if="results['{{ $tool['key'] }}']">
                        <div class="mt-3 rounded-lg bg-gray-50 dark:bg-gray-900 p-3 text-sm text-gray-700 dark:text-gray-300 font-mono whitespace-pre-wrap"
                             x-text="results['{{ $tool['key'] }}']"></div>
                    </template>
                </div>
            @endforeach

            {{-- Disk Usage Card --}}
            <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
                <div class="flex items-start gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20 shrink-0">
                        <x-icon name="tabler--server" class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Uso de Disco</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Espacio del servidor y storage</p>
                    </div>
                </div>
                <button type="button"
                        class="cursor-pointer w-full rounded-lg bg-gray-100 dark:bg-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition min-h-[44px]"
                        x-on:click="loadDisk()"
                        x-bind:disabled="loading['disk']">
                    <span x-show="!loading['disk']">Refrescar</span>
                    <span x-show="loading['disk']" x-cloak>Cargando...</span>
                </button>
                <template x-if="results['disk']">
                    <div class="mt-3 rounded-lg bg-gray-50 dark:bg-gray-900 p-3 text-sm text-gray-700 dark:text-gray-300 font-mono whitespace-pre-wrap"
                         x-text="results['disk']"></div>
                </template>
            </div>
        </div>

        {{-- Log Viewer --}}
        <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 shrink-0">
                        <x-icon name="tabler--file-alert" class="h-5 w-5 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Log Viewer</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Últimos 50 errores y warnings</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button"
                            class="cursor-pointer rounded-lg bg-gray-100 dark:bg-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition min-h-[44px]"
                            x-on:click="loadLogTail()"
                            x-bind:disabled="loading['log']">
                        Refrescar
                    </button>
                    <button type="button"
                            class="cursor-pointer rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition min-h-[44px]"
                            x-on:click="confirmAndRun('logs', '/admin/tools/logs', '¿Vaciar el archivo de log?'); setTimeout(() => loadLogTail(), 1500);">
                        Limpiar log
                    </button>
                </div>
            </div>
            <div class="rounded-lg bg-gray-900 p-4 max-h-96 overflow-y-auto">
                <pre class="text-xs font-mono whitespace-pre-wrap leading-relaxed"><template x-for="line in logLines.split('\n')" :key="line"><span x-text="line + '\n'"
                      x-bind:class="{
                          'text-red-400': line.includes('ERROR'),
                          'text-yellow-400': line.includes('WARNING') && !line.includes('ERROR'),
                          'text-gray-400': !line.includes('ERROR') && !line.includes('WARNING')
                      }"></span></template></pre>
            </div>
        </div>
    </div>
</x-filament-panels::page>
