{{--
    ============================================================
    SYNTI — Asistente SYNTIweb
    Archivo: resources/views/dashboard/partials/synti-assistant.blade.php
    Incluir en: resources/views/dashboard/index.blade.php (al final del body)
    Shortcut: Alt + H
    ============================================================
--}}

<div
    x-data="syntiAssistant()"
    x-init="init()"
    @keydown.alt.h.window="toggle()"
    @keydown.escape.window="close()"
>

    {{-- ── TRIGGER FLOTANTE ─────────────────────────────────── --}}
    <button
        @click="toggle()"
        x-show="!open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="fixed bottom-6 right-6 z-40 group flex items-center gap-2.5
               bg-[#4A80E4] hover:bg-[#2D5FC4] text-white
               pl-3.5 pr-4 py-2.5 rounded-full
               shadow-[0_4px_24px_rgba(74,128,228,0.45)]
               hover:shadow-[0_4px_32px_rgba(74,128,228,0.6)]
               transition-all duration-200 select-none"
        title="Asistente SYNTI (Alt+H)"
    >
        {{-- Ícono --}}
        <span class="relative flex h-5 w-5 items-center justify-center">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
            </svg>
        </span>
        <span class="text-sm font-medium tracking-wide">SYNTI</span>
        {{-- Atajo de teclado --}}
        <span class="hidden group-hover:flex items-center gap-0.5 text-[10px]
                     text-white/60 font-mono ml-1">
            <kbd class="px-1 py-0.5 bg-white/15 rounded text-[9px]">Alt</kbd>
            <span>+</span>
            <kbd class="px-1 py-0.5 bg-white/15 rounded text-[9px]">H</kbd>
        </span>
    </button>

    {{-- ── OVERLAY ──────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px]"
        style="display:none"
    ></div>

    {{-- ── MODAL ────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="fixed inset-x-4 bottom-6 z-50 mx-auto max-w-lg
               bg-white rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.18)]
               border border-gray-100 flex flex-col overflow-hidden"
        style="height: 560px; display:none"
        @click.stop
    >

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4
                    border-b border-gray-100">
            <div class="flex items-center gap-3">
                {{-- Avatar con pulso --}}
                <div class="relative flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-[#4A80E4] flex items-center
                                justify-center shadow-[0_2px_8px_rgba(74,128,228,0.4)]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="white"
                             stroke-width="1.8" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5
                                 bg-emerald-400 rounded-full border-2 border-white"></span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 leading-none">SYNTI</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Asistente SYNTIweb</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Link a docs --}}
                <a href="https://docs.syntiweb.com" target="_blank"
                   class="p-1.5 text-gray-400 hover:text-[#4A80E4]
                          hover:bg-blue-50 rounded-lg transition-colors"
                   title="Ver documentación completa">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                </a>
                {{-- Cerrar --}}
                <button @click="close()"
                        class="p-1.5 text-gray-400 hover:text-gray-600
                               hover:bg-gray-100 rounded-lg transition-colors">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mensajes --}}
        <div
            id="synti-messages"
            class="flex-1 overflow-y-auto px-5 py-4 space-y-4 scroll-smooth"
        >
            {{-- Bienvenida --}}
            <div class="flex gap-3">
                <div class="w-6 h-6 rounded-lg bg-[#4A80E4] flex-shrink-0 mt-0.5
                            flex items-center justify-center">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white"
                         stroke-width="2" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="bg-gray-50 rounded-2xl rounded-tl-sm px-4 py-3
                                text-sm text-gray-700 leading-relaxed">
                        Hola 👋 Soy SYNTI. Puedo ayudarte con cualquier duda sobre
                        <span class="font-medium text-[#4A80E4]">SYNTIweb</span> —
                        cómo usar el dashboard, configurar tu página, planes y más.
                    </div>
                    {{-- Sugerencias rápidas --}}
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <template x-for="suggestion in suggestions" :key="suggestion">
                            <button
                                @click="sendSuggestion(suggestion)"
                                class="text-[11px] px-2.5 py-1 rounded-full border border-gray-200
                                       text-gray-500 hover:border-[#4A80E4] hover:text-[#4A80E4]
                                       hover:bg-blue-50 transition-all duration-150"
                                x-text="suggestion"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Mensajes dinámicos --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    {{-- Mensaje usuario --}}
                    <div x-show="msg.role === 'user'" class="flex justify-end">
                        <div class="max-w-[82%] bg-[#4A80E4] text-white rounded-2xl
                                    rounded-tr-sm px-4 py-2.5 text-sm leading-relaxed">
                            <span x-text="msg.content"></span>
                        </div>
                    </div>

                    {{-- Mensaje SYNTI --}}
                    <div x-show="msg.role === 'assistant'" class="flex gap-3">
                        <div class="w-6 h-6 rounded-lg bg-[#4A80E4] flex-shrink-0 mt-0.5
                                    flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white"
                                 stroke-width="2" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-2xl rounded-tl-sm px-4 py-3
                                        text-sm text-gray-700 leading-relaxed"
                                 x-text="msg.content">
                            </div>
                            {{-- Fuente --}}
                            <div x-show="msg.source" class="flex items-center gap-1.5 mt-1.5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" class="w-3 h-3 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                </svg>
                                <a :href="msg.sourceUrl" target="_blank"
                                   class="text-[11px] text-[#4A80E4] hover:underline"
                                   x-text="'Ver: ' + msg.source">
                                </a>
                            </div>
                            {{-- Feedback --}}
                            <div x-show="msg.logId && msg.feedback === null"
                                 class="flex items-center gap-2 mt-2">
                                <span class="text-[11px] text-gray-400">¿Fue útil?</span>
                                <button @click="sendFeedback(msg, true)"
                                        class="text-[11px] px-2 py-0.5 rounded-full
                                               hover:bg-emerald-50 hover:text-emerald-600
                                               text-gray-400 transition-colors">
                                    👍 Sí
                                </button>
                                <button @click="sendFeedback(msg, false)"
                                        class="text-[11px] px-2 py-0.5 rounded-full
                                               hover:bg-red-50 hover:text-red-500
                                               text-gray-400 transition-colors">
                                    👎 No
                                </button>
                            </div>
                            <div x-show="msg.feedback !== null"
                                 class="text-[11px] text-gray-400 mt-1.5 italic">
                                Gracias por tu feedback ✓
                            </div>
                        </div>
                    </div>

                    {{-- Loading --}}
                    <div x-show="msg.role === 'loading'" class="flex gap-3">
                        <div class="w-6 h-6 rounded-lg bg-[#4A80E4]/20 flex-shrink-0 mt-0.5
                                    flex items-center justify-center">
                        </div>
                        <div class="bg-gray-50 rounded-2xl rounded-tl-sm px-4 py-3">
                            <div class="flex gap-1 items-center h-4">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full
                                             animate-bounce [animation-delay:0ms]"></span>
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full
                                             animate-bounce [animation-delay:150ms]"></span>
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full
                                             animate-bounce [animation-delay:300ms]"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-100 px-4 py-3">
            <div class="flex items-end gap-2 bg-gray-50 rounded-xl px-3.5 py-2.5
                        border border-transparent focus-within:border-[#4A80E4]/30
                        focus-within:bg-white transition-all duration-150">
                <textarea
                    x-model="input"
                    @keydown.enter.prevent="if(!$event.shiftKey) ask()"
                    @input="autoResize($el)"
                    placeholder="Pregunta algo… (Enter para enviar)"
                    rows="1"
                    :disabled="loading"
                    class="flex-1 bg-transparent text-sm text-gray-800 placeholder-gray-400
                           resize-none outline-none leading-relaxed max-h-24
                           disabled:opacity-50"
                ></textarea>
                <button
                    @click="ask()"
                    :disabled="loading || !input.trim()"
                    class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#4A80E4]
                           disabled:bg-gray-200 text-white flex items-center justify-center
                           hover:bg-[#2D5FC4] transition-colors duration-150
                           disabled:cursor-not-allowed"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" class="w-4 h-4 rotate-90">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </div>
            <p class="text-[10px] text-gray-400 text-center mt-2">
                Shift+Enter nueva línea · Alt+H para abrir/cerrar
            </p>
        </div>
    </div>
</div>

<script>
function syntiAssistant() {
    return {
        open:     false,
        input:    '',
        loading:  false,
        messages: [],
        suggestions: [
            '¿Cómo agrego un producto?',
            '¿Cómo descargo el QR?',
            '¿Cómo cambio los colores?',
            '¿Qué incluye mi plan?',
        ],

        init() {
            // Pre-calentar: detectar el producto activo del tenant
            this.product = '{{ $tenant->plan->blueprint ?? "studio" }}';
        },

        toggle() { this.open ? this.close() : this.openModal(); },

        openModal() {
            this.open = true;
            this.$nextTick(() => {
                document.querySelector('#synti-messages textarea')?.focus();
            });
        },

        close() { this.open = false; },

        sendSuggestion(text) {
            this.input = text;
            this.ask();
        },

        async ask() {
            const question = this.input.trim();
            if (!question || this.loading) return;

            this.input   = '';
            this.loading = true;

            // Mensaje usuario
            this.messages.push({ role: 'user', content: question });

            // Loading indicator
            this.messages.push({ role: 'loading' });
            this.scrollToBottom();

            try {
                const response = await fetch('/api/synti/ask', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({
                        question,
                        product: this.product ?? null,
                    }),
                });

                const data = await response.json();

                // Remover loading
                this.messages = this.messages.filter(m => m.role !== 'loading');

                this.messages.push({
                    role:      'assistant',
                    content:   data.answer,
                    source:    data.source    ?? null,
                    sourceUrl: data.source_url ?? null,
                    logId:     data.log_id    ?? null,
                    feedback:  null,
                });

            } catch {
                this.messages = this.messages.filter(m => m.role !== 'loading');
                this.messages.push({
                    role:     'assistant',
                    content:  'Hubo un problema al procesar tu pregunta. Intenta de nuevo.',
                    source:   null,
                    feedback: null,
                });
            } finally {
                this.loading = false;
                this.scrollToBottom();
            }
        },

        async sendFeedback(msg, helpful) {
            if (!msg.logId) return;
            msg.feedback = helpful;

            await fetch('/api/synti/feedback', {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ log_id: msg.logId, helpful }),
            });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('synti-messages');
                if (container) container.scrollTop = container.scrollHeight;
            });
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 96) + 'px';
        },
    };
}
</script>
