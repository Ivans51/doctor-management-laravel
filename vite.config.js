import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/home.js',
                'resources/js/modal.js',
                'resources/js/phone-input.js',
                'resources/js/settings-menu.js',
                'resources/js/response-management.js',
                'resources/js/sweet-management.js',
                'resources/js/utils.js',
                'resources/js/menu.js',
            ],
            refresh: true,
        }),
    ],
});
