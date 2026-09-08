import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    800: '#0b2545',
                    900: '#07192e',
                    950: '#051221',
                },
                brand: {
                    teal: '#0e7c66',
                    green: '#3b6d11',
                    blue: '#185fa5',
                },
            },
            boxShadow: {
                card: '0 10px 40px -18px rgba(11, 37, 69, 0.18)',
            },
        },
    },

    plugins: [forms],
};
