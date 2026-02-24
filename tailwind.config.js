import defaultTheme from 'tailwindcss/defaultTheme';
import { addIconSelectors } from '@iconify/tailwind';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flyonui/dist/js/*.js',
    ],

    safelist: [
        // Base classes
        'iconify', 'iconify-color',
        // Iconos de negocios
        'tabler--briefcase', 'tabler--building-store', 'tabler--award', 
        'tabler--certificate', 'tabler--crown', 'tabler--diamond',
        'tabler--rocket', 'tabler--target', 'tabler--trophy', 
        'tabler--star', 'tabler--heart', 'tabler--thumb-up',
        'tabler--shield-check', 'tabler--rosette-discount-check',
        // Servicios fisicos
        'tabler--tool', 'tabler--hammer', 'tabler--paint', 
        'tabler--scissors', 'tabler--needle-thread', 'tabler--pencil-bolt',
        'tabler--bolt', 'tabler--car', 'tabler--home', 
        'tabler--building', 'tabler--bucket', 'tabler--wash',
        // Tecnologia
        'tabler--device-desktop', 'tabler--device-mobile', 'tabler--wifi',
        'tabler--cpu', 'tabler--code', 'tabler--cloud',
        'tabler--headset', 'tabler--printer',
        // Foto / Medios
        'tabler--camera', 'tabler--video', 'tabler--microphone',
        'tabler--palette', 'tabler--ballpen', 'tabler--photo',
        // Salud y Bienestar
        'tabler--stethoscope', 'tabler--first-aid-kit', 'tabler--activity',
        'tabler--bath', 'tabler--barbell', 'tabler--leaf',
        'tabler--eye', 'tabler--brain',
        // Educacion
        'tabler--book', 'tabler--school', 'tabler--pencil', 'tabler--flask',
        // Comida y Bebida
        'tabler--soup', 'tabler--pizza', 'tabler--coffee', 'tabler--apple',
        // Logistica
        'tabler--shopping-cart', 'tabler--package', 'tabler--truck', 'tabler--map-pin',
        // Comunicacion
        'tabler--phone', 'tabler--mail', 'tabler--message-circle',
        'tabler--calendar', 'tabler--clock', 'tabler--users', 'tabler--user-check',
        // Iconos adicionales
        'tabler--settings', 'tabler--circle-check',
        // Iconos de navegacion
        'tabler--menu-2', 'tabler--x',
        // Landing-v2 icons
        'tabler--layout-navbar', 'tabler--layout-dashboard', 'tabler--maximize',
        'tabler--layout-columns', 'tabler--color-swatch', 'tabler--cards',
        'tabler--info-circle', 'tabler--arrow-left', 'tabler--arrow-right', 'tabler--bulb',
        'tabler--brand-whatsapp', 'tabler--brand-instagram', 'tabler--brand-facebook',
        'tabler--brand-tiktok', 'tabler--brand-x', 'tabler--currency-dollar',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'radial-glow': 'radial-gradient(circle at center, var(--tw-gradient-stops))',
                'conic-gradient': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
            },
            boxShadow: {
                'glow-primary': '0 0 20px rgba(255, 107, 0, 0.15)',
                'glow-strong': '0 0 50px rgba(255, 107, 0, 0.3)',
            },
            spacing: {
                'section-gap': '128px',
            }
        },
    },

    plugins: [
        addIconSelectors(['tabler']),
    ],
};