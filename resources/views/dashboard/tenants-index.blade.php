<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Negocios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header con botón -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mis Negocios</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Gestiona todos tus emprendimientos en un solo lugar</p>
                </div>
                <a href="{{ route('onboarding.selector') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="iconify tabler--plus size-5"></span>
                    Crear Negocio
                </a>
            </div>

            @if($tenants->isNotEmpty())
                <!-- Grid de negocios -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tenants as $tenant)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition cursor-pointer group">
                            <!-- Card Body -->
                            <div class="p-6">
                                <!-- Header: Logo + Status -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4 flex-1">
                                        <!-- Logo o Avatar -->
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0 text-white font-bold text-lg">
                                            {{ substr($tenant->business_name, 0, 1) }}
                                        </div>
                                        <!-- Info Básica -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $tenant->business_name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $tenant->business_segment ?? 'Studio' }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Status Indicator -->
                                    <div class="flex items-center gap-2">
                                        @if($tenant->status === 'active')
                                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Plan Badge -->
                                <div class="mb-4 inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                    {{ $tenant->plan?->name ?? 'Plan Estándar' }}
                                </div>

                                <!-- Dominio -->
                                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 font-medium uppercase tracking-wide">Dominio</p>
                                    <p class="text-sm text-gray-900 dark:text-white font-mono break-all">
                                        @if($tenant->custom_domain)
                                            {{ $tenant->custom_domain }}
                                        @else
                                            {{ $tenant->subdomain }}.{{ $tenant->base_domain ?? 'sintiweb.com' }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-4 text-center">
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $tenant->products()->count() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Productos</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $tenant->services()->count() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Servicios</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-3">
                                    <a href="@if($tenant->custom_domain)https://{{ $tenant->custom_domain }}@else{{ $tenant->subdomain }}.{{ $tenant->base_domain ?? 'sintiweb.com' }}@endif"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <span class="iconify tabler--external-link size-4"></span>
                                        Ver
                                    </a>
                                    <a href="{{ route('dashboard.edit-tenant', $tenant->id) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span class="iconify tabler--edit size-4"></span>
                                        Editar
                                    </a>
                                </div>
                            </div>

                            <!-- Footer: Timestamp -->
                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Actualizado {{ $tenant->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50 p-12 text-center">
                    <div class="mb-4 flex justify-center">
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <span class="iconify tabler--briefcase size-8 text-blue-600 dark:text-blue-400"></span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No tienes negocios aún</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        Crea tu primer negocio y comienza a vender online. Elige entre restaurante, tienda, servicios profesionales o técnicos.
                    </p>
                    <a href="{{ route('onboarding.selector') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="iconify tabler--plus size-5"></span>
                        Crear Mi Primer Negocio
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
