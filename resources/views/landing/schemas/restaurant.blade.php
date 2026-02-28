{{-- Schema.org: Restaurant --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Restaurant",
    "name": {{ Js::from($tenant->business_name) }},
    "description": {{ Js::from($tenant->description ?? '') }},
    @if($tenant->customization && $tenant->customization->logo_filename)
    "image": "{{ asset('storage/tenants/' . $tenant->id . '/' . $tenant->customization->logo_filename) }}",
    @endif
    "address": {
        "@type": "PostalAddress",
        "streetAddress": {{ Js::from($tenant->address ?? '') }},
        "addressLocality": {{ Js::from($tenant->city ?? '') }},
        "addressCountry": "VE"
    },
    @if($tenant->phone)
    "telephone": {{ Js::from($tenant->phone) }},
    @endif
    @if($tenant->email)
    "email": {{ Js::from($tenant->email) }},
    @endif
    "servesCuisine": {{ Js::from($tenant->business_segment ?? 'General') }},
    @if($tenant->whatsapp_sales)
    "potentialAction": {
        "@type": "OrderAction",
        "target": "https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}"
    },
    @endif
    "url": {{ Js::from(url('/' . $tenant->subdomain)) }}
}
</script>
