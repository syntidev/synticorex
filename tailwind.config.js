import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import { addDynamicIconSelectors } from '@iconify/tailwind'; // Importación correcta arriba

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flyonui/dist/js/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ADN de marca: Naranja Oro
                primary: {
                    DEFAULT: '#FF6B00',
                    '50': 'rgba(255, 107, 0, 0.05)',
                    '100': 'rgba(255, 107, 0, 0.1)',
                    '200': 'rgba(255, 107, 0, 0.2)',
                    'soft': 'rgba(255, 107, 0, 0.03)',
                },
                // Superficies de lujo (Modo Agency/LMS)
                surface: {
                    '50': '#F8FAFC',
                    '900': '#0F172A', 
                    '950': '#020617',
                }
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
        forms,
        require('flyonui'),
        addDynamicIconSelectors(), // Motor de iconos activado
    ],

    flyonui: {
        themes: ["light", "dark", "luxury", "soft", "mintlify"]
    }
};