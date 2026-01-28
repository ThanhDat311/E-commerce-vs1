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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Inter', ...defaultTheme.fontFamily.sans], // Thêm font cho admin
            },
            colors: {
                // ĐỔI TÊN 'primary' THÀNH 'admin-primary'
                "admin-primary": "#1111d4",
                "background-light": "#f6f6f8",
                "background-dark": "#101022",
            },
        },
    },

    plugins: [forms],
};