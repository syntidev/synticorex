# Cierre Operativo - 2026-03-12

## Estado del roadmap (Studio)

- S1.4 marcado como done en el orquestador.
- Siguiente tarea activa: S2.1 Protect API routes with sanctum.

## Decisiones simplificadas de producto (MVP)

1. El celular (WhatsApp) es obligatorio para crear negocio.
2. El email de contacto del negocio se toma del email de la cuenta autenticada.
3. Un usuario puede crear multiples negocios (Studio, Food, Cat) con la misma cuenta.
4. El mismo numero de celular puede reutilizarse entre negocios del mismo usuario.
5. El logout ya existe y se mantiene simple (cerrar sesion y volver al home).
6. Facturacion avanzada, tickets y soporte formal quedan fuera del MVP actual.

## Implementacion tecnica aplicada hoy

- Se hizo obligatorio `whatsapp_sales` en onboarding Studio, Food y Cat.
- Se normaliza telefono a formato `+digits` y se valida longitud 10-15 digitos.
- Si `phone` no viene, se usa el mismo valor de `whatsapp_sales`.
- En tenant nuevo, `email` se setea con el correo de la cuenta autenticada.

## Nota de alcance

Estas reglas priorizan velocidad de adopcion y cierre comercial por WhatsApp, evitando complejidad SaaS temprana.
