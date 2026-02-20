{{-- FAQ Section Partial - FlyonUI --}}
<section id="faq">
    <div class="bg-base-200 relative h-full py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            {{-- Decorative Elements --}}
            <span class="intersect:motion-preset-slide-right intersect:motion-duration-800 intersect:motion-opacity-0 intersect:motion-delay-600 absolute start-[15%] max-sm:hidden opacity-5">
                <span class="icon-[tabler--help-hexagon] size-24 text-primary"></span>
            </span>
            <span class="intersect:motion-preset-slide-right intersect:motion-duration-800 intersect:motion-opacity-0 intersect:motion-delay-1200 absolute end-[15%] max-sm:hidden opacity-5">
                <span class="icon-[tabler--message-question] size-24 text-primary"></span>
            </span>
            
            {{-- FAQ Header --}}
            <div class="mb-12 space-y-4 text-wrap text-center sm:mb-16 lg:mb-24">
                <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Preguntas Frecuentes</h2>
                <p class="text-base-content/80 text-xl">
                    Encuentra respuestas a las preguntas más comunes sobre nuestros productos y servicios.
                </p>
            </div>
            
            {{-- FAQ Accordion --}}
            <div class="mx-auto max-w-3xl space-y-4">
                {{-- FAQ 1 --}}
                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-base-content/20">
                    <input type="radio" name="faq-accordion" checked="checked" />
                    <div class="collapse-title text-lg font-semibold">
                        ¿Hacen entregas a domicilio?
                    </div>
                    <div class="collapse-content text-base-content/80">
                        <p>
                            Sí, realizamos entregas a domicilio en nuestra zona de cobertura. 
                            Contáctanos por WhatsApp para conocer los detalles de envío, costos y tiempos de entrega según tu ubicación.
                        </p>
                    </div>
                </div>
                
                {{-- FAQ 2 --}}
                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-base-content/20">
                    <input type="radio" name="faq-accordion" />
                    <div class="collapse-title text-lg font-semibold">
                        ¿Cuáles son sus métodos de pago?
                    </div>
                    <div class="collapse-content text-base-content/80">
                        <p>
                            Aceptamos múltiples formas de pago para tu comodidad: efectivo, pago móvil, transferencia bancaria, 
                            Zelle y otras opciones. Consulta con nosotros el método que prefieras.
                        </p>
                    </div>
                </div>
                
                {{-- FAQ 3 --}}
                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-base-content/20">
                    <input type="radio" name="faq-accordion" />
                    <div class="collapse-title text-lg font-semibold">
                        ¿Cómo puedo hacer un pedido?
                    </div>
                    <div class="collapse-content text-base-content/80">
                        <p>
                            Hacer un pedido es muy fácil. Simplemente navega por nuestro catálogo, selecciona los productos que deseas 
                            y haz clic en el botón de WhatsApp. Te atenderemos inmediatamente para confirmar tu pedido y coordinar el pago y entrega.
                        </p>
                    </div>
                </div>
                
                {{-- FAQ 4 --}}
                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-base-content/20">
                    <input type="radio" name="faq-accordion" />
                    <div class="collapse-title text-lg font-semibold">
                        ¿Los precios incluyen impuestos?
                    </div>
                    <div class="collapse-content text-base-content/80">
                        <p>
                            Sí, todos los precios mostrados en nuestra página ya incluyen los impuestos correspondientes. 
                            El precio que ves es el precio final. Puedes cambiar entre REF (dólares de referencia) y Bolívares 
                            usando el botón de cambio de moneda.
                        </p>
                    </div>
                </div>
                
                {{-- FAQ 5 --}}
                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-base-content/20">
                    <input type="radio" name="faq-accordion" />
                    <div class="collapse-title text-lg font-semibold">
                        ¿Tienen garantía sus productos?
                    </div>
                    <div class="collapse-content text-base-content/80">
                        <p>
                            Nos comprometemos con la calidad de nuestros productos. Si tienes algún inconveniente, 
                            contáctanos dentro de las primeras 24 horas de recibido tu pedido y buscaremos la mejor solución para ti.
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- CTA --}}
            <div class="mt-12 text-center">
                <p class="text-base-content/80 mb-4">¿No encuentras lo que buscas?</p>
                @if($tenant->whatsapp_sales)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Tengo una pregunta...') }}"
                   target="_blank"
                   class="btn btn-primary btn-gradient">
                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                    Pregúntanos por WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
