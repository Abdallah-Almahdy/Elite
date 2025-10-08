import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/sass/app.scss', // Our new line you can change app.scss to whatever.scss
                'resources/js/app.js',
                'resources/js/app.jsx'
            ],
            refresh: true,
        }),

    ],
     resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});
