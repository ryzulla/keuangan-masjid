// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                gold: {
                    50:  '#fdfbf0',
                    100: '#f9f0c4',
                    200: '#f3e18a',
                    300: '#e8cb5a',
                    400: '#d4af37',
                    500: '#b8970c',
                    600: '#9b7e0e',
                    700: '#7a630b',
                    800: '#5c4a08',
                    900: '#3d3206',
                    950: '#1e1803',
                },
                dark: {
                    50:  '#f5f5f5',
                    100: '#e0e0e0',
                    200: '#c2c2c2',
                    300: '#a3a3a3',
                    400: '#858585',
                    500: '#5c6368',
                    600: '#474747',
                    700: '#2d2d2d',
                    800: '#1a1a1a',
                    850: '#141414',
                    900: '#0f0f0f',
                    950: '#080808',
                },
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],
};
