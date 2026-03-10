import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'SYNTIweb Docs',
  description: 'Documentacion oficial de SYNTIweb',
  lang: 'es',
  base: '/',

  themeConfig: {
    logo: {
      light: '/syntiweb-logo-positive.svg',
      dark:  '/syntiweb-logo-negative.svg',
    },
    siteTitle: 'SYNTIweb',

    nav: [
      { text: 'Inicio',      link: '/shared/introduccion' },
      { text: 'SYNTIstudio', link: '/studio/que-es-studio' },
      { text: 'SYNTIfood',   link: '/food/que-es-food' },
      { text: 'SYNTIcat',    link: '/cat/que-es-cat' },
    ],

    sidebar: {
      '/shared/': [{ text: 'General', items: [
        { text: 'Introduccion',         link: '/shared/introduccion' },
        { text: 'Guia rapida',          link: '/shared/quickstart' },
        { text: 'Cuenta y planes',      link: '/shared/cuenta-y-planes' },
        { text: 'Preguntas frecuentes', link: '/shared/faq' },
      ]}],
      '/studio/': [{ text: 'SYNTIstudio', items: [
        { text: 'Que es Studio',  link: '/studio/que-es-studio' },
        { text: 'Tu panel',       link: '/studio/dashboard' },
        { text: 'Hero y Banner',  link: '/studio/hero-y-banner' },
        { text: 'Productos',      link: '/studio/seccion-productos' },
        { text: 'Servicios',      link: '/studio/seccion-servicios' },
        { text: 'Diseno',         link: '/studio/seccion-diseno' },
        { text: 'QR Sticker',     link: '/studio/qr-sticker' },
        { text: 'WhatsApp',       link: '/studio/whatsapp' },
        { text: 'SEO automatico', link: '/studio/seo-automatico' },
      ]}],
      '/food/': [{ text: 'SYNTIfood', items: [
        { text: 'Que es Food',     link: '/food/que-es-food' },
        { text: 'Tu menu',         link: '/food/seccion-menu' },
        { text: 'Categorias',      link: '/food/categorias-menu' },
        { text: 'Items y precios', link: '/food/items-lista' },
        { text: 'Pedido rapido',   link: '/food/pedido-rapido' },
        { text: 'Horarios',        link: '/food/horarios-atencion' },
      ]}],
      '/cat/': [{ text: 'SYNTIcat', items: [
        { text: 'Que es Cat',       link: '/cat/que-es-cat' },
        { text: 'Tu catalogo',      link: '/cat/catalogo-productos' },
        { text: 'Variantes',        link: '/cat/variantes' },
        { text: 'Carrito WhatsApp', link: '/cat/carrito-whatsapp' },
      ]}],
    },

    docFooter: {
      prev: 'Pagina anterior',
      next: 'Pagina siguiente',
    },

    search: {
      provider: 'local',
      options: {
        translations: {
          button: { buttonText: 'Buscar', buttonAriaLabel: 'Buscar' },
          modal: {
            noResultsText: 'Sin resultados para',
            resetButtonTitle: 'Limpiar',
            footer: {
              selectText: 'seleccionar',
              navigateText: 'navegar',
              closeText: 'cerrar',
            }
          }
        }
      }
    },
  }
})
