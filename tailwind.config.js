import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-red-500',
        'hover:bg-red-600',
        'bg-yellow-500',
        'hover:bg-yellow-600',
        'bg-lime-200',
        'hover:bg-lime-400',
        'bg-lime-500',
        'hover:bg-lime-600',
        'bg-green-500',
        'hover:bg-green-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#4594D3', // Primary colour
                secondary: '#1EBCC5', // Secondary colour
                tertiary: '#84D0D9',
                bg: '#f3f4f6',
                windy: '#FFCB05',
                neutral: '#e2e8f0', // Neutral background colour
            }
        },
    },

    plugins: [forms],
};
