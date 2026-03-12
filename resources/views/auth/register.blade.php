<x-guest-layout>
    {{-- Google OAuth --}}
    <div class="mb-6">
        <a href="{{ route('auth.google') }}"
           class="w-full inline-flex items-center justify-center gap-3 py-2.5 px-4 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continuar con Google
        </a>
    </div>

    <div class="relative mb-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-sm"><span class="bg-white px-3 text-gray-500">o crea tu cuenta</span></div>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="registerForm()" @submit.prevent="submitForm($event)">
        @csrf

        {{-- ═══ STEP 1: Datos de Cuenta ═══ --}}
        <div x-show="step === 1" x-transition>
            <h2 class="text-xl font-bold text-center mb-4">Crear Cuenta</h2>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    ¿Ya tienes cuenta?
                </a>
                <button type="button" @click="goToStep(2)" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Siguiente
                </button>
            </div>
        </div>

        {{-- ═══ STEP 2: Seleccionar Segmento de Industria ═══ --}}
        <div x-show="step === 2" x-transition>
            <h2 class="text-xl font-bold text-center mb-2">¿Qué tipo de negocio tienes?</h2>
            <p class="text-gray-500 text-sm text-center mb-6">Esto personaliza tu dashboard y landing page</p>

            @if($errors->has('industry_segment'))
                <div class="mb-4 text-red-600 text-sm text-center">{{ $errors->first('industry_segment') }}</div>
            @endif

            <div class="space-y-3">
                @foreach(config('blueprints') as $key => $blueprint)
                    <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                           :class="segment === '{{ $key }}'
                               ? 'border-blue-500 bg-blue-50'
                               : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50/30'"
                           @click="segment = '{{ $key }}'">
                        <input type="radio" name="industry_segment" value="{{ $key }}"
                               x-model="segment" class="hidden" required>
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="segment === '{{ $key }}' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'">
                            @switch($blueprint['icon'])
                                @case('utensils')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h2V3H3zm4 0v8c0 2.21 1.79 4 4 4s4-1.79 4-4V3h-2v8c0 1.1-.9 2-2 2s-2-.9-2-2V3H7zm10 0v18h2V3h-2z"/></svg>
                                    @break
                                @case('shopping-bag')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @break
                                @case('heart')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @break
                                @case('briefcase')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    @break
                                @case('wrench')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="font-semibold text-sm">{{ $blueprint['label'] }}</div>
                        </div>
                        <div x-show="segment === '{{ $key }}'" class="text-blue-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="goToStep(1)" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Atrás
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50"
                        :disabled="!segment">
                    Crear Cuenta
                </button>
            </div>
        </div>
    </form>

    <script>
        function registerForm() {
            return {
                step: {{ $errors->any() && old('industry_segment') ? 2 : 1 }},
                segment: '{{ old('industry_segment', '') }}',
                goToStep(n) {
                    if (n === 2) {
                        const name = document.getElementById('name');
                        const email = document.getElementById('email');
                        const pass = document.getElementById('password');
                        const confirm = document.getElementById('password_confirmation');
                        if (!name.value || !email.value || !pass.value || !confirm.value) {
                            return;
                        }
                        if (pass.value !== confirm.value) {
                            return;
                        }
                    }
                    this.step = n;
                },
                submitForm(e) {
                    if (!this.segment) return;
                    e.target.submit();
                }
            };
        }
    </script>
</x-guest-layout>
