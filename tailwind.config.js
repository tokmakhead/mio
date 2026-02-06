import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#fdf2f4',
                    100: '#fce7eb',
                    200: '#f9d0d9',
                    300: '#f4a8b8',
                    400: '#ec7591',
                    500: '#de4968',
                    600: '#c72d52',
                    700: '#a82244',
                    800: '#7d1a38',
                    900: '#5a1329',
                },
                accent: {
                    50: '#fdfcfa',
                    100: '#faf8f4',
                    200: '#f8f4eb',
                    300: '#f3dbbe',
                    400: '#e8c89a',
                    500: '#ddb576',
                    600: '#c99d5a',
                    700: '#a67d45',
                    800: '#7d5e33',
                    900: '#5a4324',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                warning: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
            },
        },
    },

    plugins: [forms],
};
