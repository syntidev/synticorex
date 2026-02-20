import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
            // SYNTI DESIGN SYSTEM - Color Tokens
            colors: {
                primary: {
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6', // Base
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },
                secondary: {
                    50: '#FAF5FF',
                    100: '#F3E8FF',
                    200: '#E9D5FF',
                    300: '#D8B4FE',
                    400: '#C084FC',
                    500: '#8B5CF6', // Base
                    600: '#7C3AED',
                    700: '#6D28D9',
                    800: '#5B21B6',
                    900: '#4C1D95',
                },
                accent: {
                    50: '#ECFEFF',
                    100: '#CFFAFE',
                    200: '#A5F3FC',
                    300: '#67E8F9',
                    400: '#22D3EE',
                    500: '#06B6D4', // Base
                    600: '#0891B2',
                    700: '#0E7490',
                    800: '#155E75',
                    900: '#164E63',
                },
                neutral: {
                    50: '#F9FAFB',
                    100: '#F3F4F6',
                    200: '#E5E7EB',
                    300: '#D1D5DB',
                    400: '#9CA3AF',
                    500: '#6B7280', // Base
                    600: '#4B5563',
                    700: '#374151',
                    800: '#1F2937',
                    900: '#111827',
                },
                success: {
                    50: '#F0FDF4',
                    100: '#DCFCE7',
                    500: '#22C55E',
                    600: '#16A34A',
                    700: '#15803D',
                },
                danger: {
                    50: '#FEF2F2',
                    100: '#FEE2E2',
                    500: '#EF4444',
                    600: '#DC2626',
                    700: '#B91C1C',
                },
            },

            // SYNTI DESIGN SYSTEM - Typography
            fontFamily: {
                synti: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                'synti-display': ['Poppins', 'system-ui', '-apple-system', 'sans-serif'],
            },

            fontSize: {
                'synti-xs': ['0.75rem', { lineHeight: '1rem' }],
                'synti-sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'synti-base': ['1rem', { lineHeight: '1.5rem' }],
                'synti-lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'synti-xl': ['1.25rem', { lineHeight: '1.75rem' }],
                'synti-2xl': ['1.5rem', { lineHeight: '2rem' }],
                'synti-3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                'synti-4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            },

            // SYNTI DESIGN SYSTEM - Spacing
            spacing: {
                'synti-xs': '0.5rem',   // 8px
                'synti-sm': '0.75rem',  // 12px
                'synti-md': '1rem',     // 16px
                'synti-lg': '1.5rem',   // 24px
                'synti-xl': '2rem',     // 32px
                'synti-2xl': '3rem',    // 48px
                'synti-3xl': '4rem',    // 64px
            },

            // SYNTI DESIGN SYSTEM - Border Radius
            borderRadius: {
                'synti': '0.5rem',      // 8px
                'synti-lg': '0.75rem',  // 12px
                'synti-xl': '1rem',     // 16px
            },

            // SYNTI DESIGN SYSTEM - Shadows
            boxShadow: {
                'synti': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'synti-md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'synti-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'synti-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                'synti-2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
            },
        },
    },

    plugins: [
        forms,
        require('flyonui'),
        require('flyonui/plugin'),
    ],

    flyonui: {
        themes: ["light"],
    },
};
