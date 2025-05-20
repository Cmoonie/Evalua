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
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#4594D3', // Primary colour
                secondary: '#1EBCC5', // Secondary colour
                tertiary: '#84D0D9',
                windy: '#FFCB05',
                neutral: '#e2e8f0', // Neutral background colour
            }
        },
    },

    plugins: [forms],
};
