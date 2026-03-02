{{--
    Testimonials Section — FlyonUI Carousel oficial
    Hardcoded placeholders until dynamic testimonials system is connected
--}}

<section id="testimonios" class="py-8 sm:py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="space-y-4 text-center mb-10">
      <p class="text-primary text-sm font-medium uppercase">Lo que dicen</p>
      <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">
        Testimonios de <span class="text-primary italic">Clientes</span>
      </h2>
      <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="card card-border shadow-none">
        <div class="card-body gap-5">
          <div class="flex items-center gap-3">
            <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-17.png" alt="Cliente" class="size-10 rounded-full object-cover">
            <div>
              <h4 class="text-base font-medium">María González</h4>
              <p class="text-sm text-base-content/80">Cliente satisfecha</p>
            </div>
          </div>
          <div class="flex gap-1">
            @for($i=1;$i<=5;$i++)<span class="icon-[tabler--star-filled] text-warning size-5"></span>@endfor
          </div>
          <p class="text-base-content/80">"Excelente servicio, superaron todas mis expectativas. 100% recomendados."</p>
        </div>
      </div>
      <div class="card card-border shadow-none">
        <div class="card-body gap-5">
          <div class="flex items-center gap-3">
            <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-5.png" alt="Cliente" class="size-10 rounded-full object-cover">
            <div>
              <h4 class="text-base font-medium">Carlos Rodríguez</h4>
              <p class="text-sm text-base-content/80">Emprendedor</p>
            </div>
          </div>
          <div class="flex gap-1">
            @for($i=1;$i<=5;$i++)<span class="icon-[tabler--star-filled] text-warning size-5"></span>@endfor
          </div>
          <p class="text-base-content/80">"La mejor inversión para mi negocio. Profesionales y con resultados reales."</p>
        </div>
      </div>
    </div>
  </div>
</section>
