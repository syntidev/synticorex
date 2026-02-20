{{-- Team Section Partial - FlyonUI (Opcional) --}}
{{-- Descomenta este partial en base.blade.php si deseas mostrar el equipo --}}

<section id="team">
    <div class="bg-base-100 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center sm:mb-16 lg:mb-24">
                <h2 class="text-base-content mb-4 text-2xl font-semibold md:text-3xl lg:text-4xl">Conoce a Nuestro Equipo</h2>
                <p class="text-base-content/80 text-xl">
                    Las personas apasionadas detrás de nuestro éxito.
                </p>
            </div>
            
            {{-- Team Members Grid --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                {{-- Team Member 1 --}}
                <div class="card card-border hover:border-primary h-max shadow-none">
                    <figure class="bg-base-200 pt-10.5 flex items-center justify-center">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content size-32 rounded-full">
                                <span class="text-4xl">JD</span>
                            </div>
                        </div>
                    </figure>
                    <div class="card-body gap-3 p-5">
                        <h3 class="text-base-content text-lg font-medium">Juan D.</h3>
                        <div class="divider my-1"></div>
                        <div>
                            <p class="text-base-content/80 mb-1 font-medium">Director General</p>
                            <p class="text-base-content/80 text-sm">Líder visionario con más de 10 años de experiencia.</p>
                        </div>
                    </div>
                </div>
                
                {{-- Team Member 2 --}}
                <div class="card card-border hover:border-primary h-max shadow-none">
                    <figure class="bg-base-200 pt-10.5 flex items-center justify-center">
                        <div class="avatar placeholder">
                            <div class="bg-secondary text-secondary-content size-32 rounded-full">
                                <span class="text-4xl">MR</span>
                            </div>
                        </div>
                    </figure>
                    <div class="card-body gap-3 p-5">
                        <h3 class="text-base-content text-lg font-medium">María R.</h3>
                        <div class="divider my-1"></div>
                        <div>
                            <p class="text-base-content/80 mb-1 font-medium">Gerente de Operaciones</p>
                            <p class="text-base-content/80 text-sm">Experta en logística y atención al cliente.</p>
                        </div>
                    </div>
                </div>
                
                {{-- Team Member 3 --}}
                <div class="card card-border hover:border-primary h-max shadow-none">
                    <figure class="bg-base-200 pt-10.5 flex items-center justify-center">
                        <div class="avatar placeholder">
                            <div class="bg-accent text-accent-content size-32 rounded-full">
                                <span class="text-4xl">CP</span>
                            </div>
                        </div>
                    </figure>
                    <div class="card-body gap-3 p-5">
                        <h3 class="text-base-content text-lg font-medium">Carlos P.</h3>
                        <div class="divider my-1"></div>
                        <div>
                            <p class="text-base-content/80 mb-1 font-medium">Jefe de Producción</p>
                            <p class="text-base-content/80 text-sm">Garantiza la calidad en cada producto.</p>
                        </div>
                    </div>
                </div>
                
                {{-- Team Member 4 --}}
                <div class="card card-border hover:border-primary h-max shadow-none">
                    <figure class="bg-base-200 pt-10.5 flex items-center justify-center">
                        <div class="avatar placeholder">
                            <div class="bg-info text-info-content size-32 rounded-full">
                                <span class="text-4xl">AL</span>
                            </div>
                        </div>
                    </figure>
                    <div class="card-body gap-3 p-5">
                        <h3 class="text-base-content text-lg font-medium">Ana L.</h3>
                        <div class="divider my-1"></div>
                        <div>
                            <p class="text-base-content/80 mb-1 font-medium">Atención al Cliente</p>
                            <p class="text-base-content/80 text-sm">Siempre lista para ayudarte con una sonrisa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
