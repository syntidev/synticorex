@extends('layouts.app')

@section('title', 'Configuración de Tenant')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Configuración</h1>
        <p class="text-gray-600 mt-2">Gestione la configuración general, moneda y apariencia de su tenant</p>
    </div>

    <!-- Tabs Container -->
    <div x-data="{ activeTab: 'general' }" class="space-y-6">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 bg-white rounded-t-lg">
            <div class="flex space-x-8 px-6">
                <!-- General Tab -->
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="py-4 font-medium transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    General
                </button>

                <!-- Currency Tab -->
                <button @click="activeTab = 'currency'" 
                        :class="activeTab === 'currency' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="py-4 font-medium transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Moneda
                </button>

                <!-- Appearance Tab -->
                <button @click="activeTab = 'appearance'" 
                        :class="activeTab === 'appearance' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="py-4 font-medium transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    Apariencia
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="bg-white rounded-b-lg shadow">
            <!-- General Settings Tab -->
            <div x-show="activeTab === 'general'" class="p-6 space-y-6">
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Business Name -->
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Negocio
                        </label>
                        <input type="text" id="business_name" name="business_name" 
                               value="{{ $tenant->business_name ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Nombre visible en todas las interfaces</p>
                    </div>

                    <!-- Slogan -->
                    <div>
                        <label for="slogan" class="block text-sm font-medium text-gray-700 mb-2">
                            Eslogan / Tagline
                        </label>
                        <textarea id="slogan" name="slogan" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $tenant->slogan ?? '' }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Breve descripción del negocio</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $tenant->description ?? '' }}</textarea>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ $tenant->email ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ $tenant->phone ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- WhatsApp Numbers -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="whatsapp_sales" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Ventas
                            </label>
                            <input type="tel" id="whatsapp_sales" name="whatsapp_sales" 
                                   value="{{ $tenant->whatsapp_sales ?? '' }}"
                                   placeholder="+58 412 1234567"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="whatsapp_support" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Soporte
                            </label>
                            <input type="tel" id="whatsapp_support" name="whatsapp_support" 
                                   value="{{ $tenant->whatsapp_support ?? '' }}"
                                   placeholder="+58 412 1234567"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $tenant->address ?? '' }}</textarea>
                    </div>

                    <!-- City & Country -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ciudad
                            </label>
                            <input type="text" id="city" name="city" 
                                   value="{{ $tenant->city ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                País
                            </label>
                            <input type="text" id="country" name="country" 
                                   value="{{ $tenant->country ?? 'Venezuela' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Currency Settings Tab -->
            <div x-show="activeTab === 'currency'" class="p-6 space-y-6">
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Exchange Rate -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-blue-900 font-medium">Tasa de Cambio Actual</p>
                                <p class="text-2xl font-bold text-blue-600 mt-1">
                                    1 USD = {{ $currentRate ?? 36.50 }} Bs.
                                </p>
                                <p class="text-xs text-blue-700 mt-2">Última actualización: {{ $lastUpdate ?? 'hace 1 hora' }}</p>
                            </div>
                            <div class="text-right">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Auto Update Toggle -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Actualización Automática</p>
                            <p class="text-sm text-gray-600">Obtener tasa de cambio automáticamente cada hora desde DolarAPI</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_update" value="1" 
                                   {{ isset($settings['engine_settings']['currency']['auto_update']) && $settings['engine_settings']['currency']['auto_update'] ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Currency Display Mode -->
                    <div>
                        <label for="display_mode" class="block text-sm font-medium text-gray-700 mb-3">
                            Modo de Visualización
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" 
                                   :class="{ 'border-blue-500 bg-blue-50': displayMode === 'toggle' }">
                                <input type="radio" name="display_mode" value="toggle" 
                                       @change="displayMode = 'toggle'"
                                       class="w-4 h-4 text-blue-600">
                                <span class="ml-2 font-medium text-gray-700">Botón Toggle</span>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition"
                                   :class="{ 'border-blue-500 bg-blue-50': displayMode === 'separate' }">
                                <input type="radio" name="display_mode" value="separate" 
                                       @change="displayMode = 'separate'"
                                       class="w-4 h-4 text-blue-600">
                                <span class="ml-2 font-medium text-gray-700">Separado</span>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition"
                                   :class="{ 'border-blue-500 bg-blue-50': displayMode === 'combined' }">
                                <input type="radio" name="display_mode" value="combined" 
                                       @change="displayMode = 'combined'"
                                       class="w-4 h-4 text-blue-600">
                                <span class="ml-2 font-medium text-gray-700">Combinado</span>
                            </label>
                        </div>
                    </div>

                    <!-- Currency Symbols -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="symbol_ref" class="block text-sm font-medium text-gray-700 mb-2">
                                Símbolo Moneda Referencia
                            </label>
                            <input type="text" id="symbol_ref" name="symbol_ref" 
                                   value="{{ $settings['engine_settings']['currency']['symbols']['reference'] ?? 'REF' }}"
                                   maxlength="10"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="symbol_bs" class="block text-sm font-medium text-gray-700 mb-2">
                                Símbolo Bolívares
                            </label>
                            <input type="text" id="symbol_bs" name="symbol_bs" 
                                   value="{{ $settings['engine_settings']['currency']['symbols']['bolivares'] ?? 'Bs.' }}"
                                   maxlength="10"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Decimals & Rounding -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="decimals" class="block text-sm font-medium text-gray-700 mb-2">
                                Decimales
                            </label>
                            <select id="decimals" name="decimals" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="0" {{ $settings['engine_settings']['currency']['decimals'] === 0 ? 'selected' : '' }}>0</option>
                                <option value="2" {{ $settings['engine_settings']['currency']['decimals'] === 2 ? 'selected' : '' }}>2</option>
                                <option value="4" {{ $settings['engine_settings']['currency']['decimals'] === 4 ? 'selected' : '' }}>4</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Redondeo
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rounding" value="1" 
                                       {{ isset($settings['engine_settings']['currency']['rounding']) && $settings['engine_settings']['currency']['rounding'] ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Habilitar redondeo automático</span>
                            </label>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Appearance Settings Tab -->
            <div x-show="activeTab === 'appearance'" class="p-6 space-y-6">
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Color Palette Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            Paleta de Colores
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @for ($i = 1; $i <= 6; $i++)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="color_palette_id" value="{{ $i }}" 
                                           {{ $tenant->color_palette_id === $i ? 'checked' : '' }}
                                           class="sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg group-hover:border-blue-500 transition"
                                         :class="{ 'border-blue-500 bg-blue-50': $checked($i) }">
                                        <!-- Color Palette Preview -->
                                        <div class="flex gap-2 mb-2">
                                            @switch($i)
                                                @case(1)
                                                    <div class="w-8 h-8 bg-blue-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-blue-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-blue-200 rounded"></div>
                                                    @break
                                                @case(2)
                                                    <div class="w-8 h-8 bg-green-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-green-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-green-200 rounded"></div>
                                                    @break
                                                @case(3)
                                                    <div class="w-8 h-8 bg-purple-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-purple-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-purple-200 rounded"></div>
                                                    @break
                                                @case(4)
                                                    <div class="w-8 h-8 bg-red-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-red-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-red-200 rounded"></div>
                                                    @break
                                                @case(5)
                                                    <div class="w-8 h-8 bg-yellow-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-yellow-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-yellow-200 rounded"></div>
                                                    @break
                                                @case(6)
                                                    <div class="w-8 h-8 bg-gray-600 rounded"></div>
                                                    <div class="w-8 h-8 bg-gray-400 rounded"></div>
                                                    <div class="w-8 h-8 bg-gray-200 rounded"></div>
                                                    @break
                                            @endswitch
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">
                                            @switch($i)
                                                @case(1) Azul @break
                                                @case(2) Verde @break
                                                @case(3) Púrpura @break
                                                @case(4) Rojo @break
                                                @case(5) Amarillo @break
                                                @case(6) Gris @break
                                            @endswitch
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Paleta {{ $i }}</p>
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <input type="file" id="logo" name="logo" accept="image/*" class="hidden">
                            <p class="text-gray-600 mb-2">Haz clic para seleccionar o arrastra una imagen</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                        </div>
                    </div>

                    <!-- Font Size -->
                    <div>
                        <label for="font_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Tamaño de Fuente Base
                        </label>
                        <select id="font_size" name="font_size" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="small">Pequeño</option>
                            <option value="normal" selected>Normal</option>
                            <option value="large">Grande</option>
                        </select>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hook reservado para futuras interacciones de settings.
</script>
@endpush
