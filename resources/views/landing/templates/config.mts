import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'SYNTIweb Docs',
  description: 'Documentación oficial de SYNTIweb',
  lang: 'es',
  base: '/',

  themeConfig: {
    logo: '/images/syntiweb-light.svg',
    siteTitle: 'SYNTIweb',

    nav: [
      { text: 'Inicio',        link: '/shared/introduccion' },
      { text: 'SYNTIstudio',   link: '/studio/que-es-studio' },
      { text: 'SYNTIfood',     link: '/food/que-es-food' },
      { text: 'SYNTIcat',      link: '/cat/que-es-cat' },
    ],

    sidebar: {
      '/shared/':  [{ text: 'General', items: [
        { text: 'Introducción',    link: '/shared/introduccion' },
        { text: 'Quickstart',      link: '/shared/quickstart' },
        { text: 'Cuenta y planes', link: '/shared/cuenta-y-planes' },
        { text: 'FAQ',             link: '/shared/faq' },
      ]}],
      '/studio/': [{ text: 'SYNTIstudio', items: [
        { text: 'Qué es Studio',   link: '/studio/que-es-studio' },
        { text: 'Dashboard',       link: '/studio/dashboard' },
        { text: 'Hero y Banner',   link: '/studio/hero-y-banner' },
        { text: 'Productos',       link: '/studio/seccion-productos' },
        { text: 'Servicios',       link: '/studio/seccion-servicios' },
        { text: 'Diseño',          link: '/studio/seccion-diseno' },
        { text: 'QR Sticker',      link: '/studio/qr-sticker' },
        { text: 'WhatsApp',        link: '/studio/whatsapp' },
        { text: 'SEO automático',  link: '/studio/seo-automatico' },
      ]}],
      '/food/': [{ text: 'SYNTIfood', items: [
        { text: 'Qué es Food',     link: '/food/que-es-food' },
        { text: 'Menú',            link: '/food/seccion-menu' },
        { text: 'Categorías',      link: '/food/categorias-menu' },
        { text: 'Items y precios', link: '/food/items-lista' },
        { text: 'Pedido rápido',   link: '/food/pedido-rapido' },
        { text: 'Horarios',        link: '/food/horarios-atencion' },
      ]}],
      '/cat/': [{ text: 'SYNTIcat', items: [
        { text: 'Qué es Cat',      link: '/cat/que-es-cat' },
        { text: 'Catálogo',        link: '/cat/catalogo-productos' },
        { text: 'Variantes',       link: '/cat/variantes' },
        { text: 'Carrito WhatsApp',link: '/cat/carrito-whatsapp' },
      ]}],
    },

    search: { provider: 'local' },
  }
})